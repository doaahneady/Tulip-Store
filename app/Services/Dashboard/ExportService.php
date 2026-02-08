<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Services\Dashboard\Contracts\ExportServiceInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportService implements ExportServiceInterface
{
    /**
     * Threshold for queuing large exports (number of rows)
     */
    protected int $exportThreshold = 1000;

    public function __construct(
        protected AuditService $auditService
    ) {}

    /**
     * Export data to CSV format with streaming response
     *
     * @param  Collection  $data  The data to export
     * @param  array  $columns  Column definitions ['key' => 'Header Label']
     * @param  string  $filename  The filename for the download
     */
    public function exportToCSV(Collection $data, array $columns, string $filename): StreamedResponse
    {
        // Log the export action
        $this->auditService->logExport('csv_export', $data->count());

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($data, $columns) {
            $handle = fopen('php://output', 'w');

            // Add BOM for UTF-8 Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            // Write header row
            fputcsv($handle, array_values($columns));

            // Write data rows
            foreach ($data as $row) {
                $rowData = [];
                foreach (array_keys($columns) as $key) {
                    $value = $this->getNestedValue($row, $key);
                    $rowData[] = $this->formatCsvValue($value);
                }
                fputcsv($handle, $rowData);
            }

            fclose($handle);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function exportToXLS(Collection $data, array $columns, string $filename): Response
    {
        $this->auditService->logExport('xls_export', $data->count());

        $html = '<html><head><meta charset="UTF-8"></head><body><table border="1"><thead><tr>';
        foreach (array_values($columns) as $label) {
            $html .= '<th>'.e((string) $label).'</th>';
        }
        $html .= '</tr></thead><tbody>';

        foreach ($data as $row) {
            $html .= '<tr>';
            foreach (array_keys($columns) as $key) {
                $value = $this->getNestedValue($row, $key);
                $cell = $this->formatCsvValue($value);
                $html .= '<td>'.e($cell).'</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</tbody></table></body></html>';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ]);
    }

    /**
     * Export data to PDF format using DomPDF
     *
     * @param  Collection  $data  The data to export
     * @param  array  $columns  Column definitions ['key' => 'Header Label']
     * @param  string  $template  The blade template to use for PDF
     * @param  array  $metadata  Additional metadata (title, subtitle, etc.)
     */
    public function exportToPDF(Collection $data, array $columns, string $template, array $metadata = []): Response
    {
        // Log the export action
        $this->auditService->logExport('pdf_export', $data->count());

        $defaultMetadata = [
            'title' => 'Export Report',
            'subtitle' => '',
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'company_name' => config('app.name', 'Tulip Store'),
        ];

        $viewData = [
            'data' => $data,
            'columns' => $columns,
            'metadata' => array_merge($defaultMetadata, $metadata),
        ];

        $pdf = Pdf::loadView($template, $viewData);

        // Set paper size and orientation
        $pdf->setPaper('a4', 'landscape');

        $filename = $metadata['filename'] ?? 'export_'.now()->format('Y-m-d_His').'.pdf';

        return $pdf->download($filename);
    }

    /**
     * Queue a large export for async processing (datasets > 1000 rows)
     *
     * @param  Collection  $data  The data to export
     * @param  array  $columns  Column definitions
     * @param  string  $format  Export format ('csv' or 'pdf')
     * @param  User  $user  The user requesting the export
     */
    public function queueLargeExport(Collection $data, array $columns, string $format, User $user): void
    {
        // Log the queued export action
        $this->auditService->logExport('queued_'.$format.'_export', $data->count());

        // For now, we'll dispatch a job to handle the export
        // In a real implementation, this would dispatch to a queue
        dispatch(function () use ($data, $columns, $format, $user) {
            $filename = 'export_'.now()->format('Y-m-d_His').'.'.$format;
            $path = storage_path('app/exports/'.$user->id.'/'.$filename);

            // Ensure directory exists
            if (! is_dir(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }

            if ($format === 'csv') {
                $this->generateCsvFile($data, $columns, $path);
            } else {
                $this->generatePdfFile($data, $columns, $path);
            }

            // Notify user that export is ready
            // In a real implementation, this would send a notification
        })->afterResponse();
    }

    /**
     * Check if a dataset should be queued for async export
     *
     * @param  Collection  $data  The data to check
     * @return bool True if dataset exceeds threshold
     */
    public function shouldQueueExport(Collection $data): bool
    {
        return $data->count() > $this->exportThreshold;
    }

    /**
     * Get the export threshold (number of rows)
     */
    public function getExportThreshold(): int
    {
        return $this->exportThreshold;
    }

    /**
     * Set the export threshold (useful for testing)
     */
    public function setExportThreshold(int $threshold): void
    {
        $this->exportThreshold = $threshold;
    }

    /**
     * Get nested value from array or object using dot notation
     *
     * @param  mixed  $item  The item to extract value from
     * @param  string  $key  The key (supports dot notation)
     */
    protected function getNestedValue(mixed $item, string $key): mixed
    {
        if (is_array($item)) {
            return data_get($item, $key);
        }

        if (is_object($item)) {
            // Handle dot notation for objects
            $keys = explode('.', $key);
            $value = $item;

            foreach ($keys as $k) {
                if (is_object($value) && isset($value->{$k})) {
                    $value = $value->{$k};
                } elseif (is_array($value) && isset($value[$k])) {
                    $value = $value[$k];
                } else {
                    return null;
                }
            }

            return $value;
        }

        return null;
    }

    /**
     * Format a value for CSV output
     *
     * @param  mixed  $value  The value to format
     */
    protected function formatCsvValue(mixed $value): string
    {
        if (is_null($value)) {
            return '';
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        if (is_array($value)) {
            return json_encode($value);
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        return (string) $value;
    }

    /**
     * Generate a CSV file and save to disk
     *
     * @param  Collection  $data  The data to export
     * @param  array  $columns  Column definitions
     * @param  string  $path  File path to save
     */
    protected function generateCsvFile(Collection $data, array $columns, string $path): void
    {
        $handle = fopen($path, 'w');

        // Add BOM for UTF-8 Excel compatibility
        fwrite($handle, "\xEF\xBB\xBF");

        // Write header row
        fputcsv($handle, array_values($columns));

        // Write data rows
        foreach ($data as $row) {
            $rowData = [];
            foreach (array_keys($columns) as $key) {
                $value = $this->getNestedValue($row, $key);
                $rowData[] = $this->formatCsvValue($value);
            }
            fputcsv($handle, $rowData);
        }

        fclose($handle);
    }

    /**
     * Generate a PDF file and save to disk
     *
     * @param  Collection  $data  The data to export
     * @param  array  $columns  Column definitions
     * @param  string  $path  File path to save
     */
    protected function generatePdfFile(Collection $data, array $columns, string $path): void
    {
        $viewData = [
            'data' => $data,
            'columns' => $columns,
            'metadata' => [
                'title' => 'Export Report',
                'generated_at' => now()->format('Y-m-d H:i:s'),
                'company_name' => config('app.name', 'Tulip Store'),
            ],
        ];

        $pdf = Pdf::loadView('dashboard.exports.pdf-template', $viewData);
        $pdf->setPaper('a4', 'landscape');
        $pdf->save($path);
    }

    /**
     * Export data with role-based filtering applied
     * This ensures exported data matches what the user can see in the dashboard
     *
     * @param  Collection  $data  The pre-filtered data (already filtered by role)
     * @param  array  $columns  Column definitions
     * @param  string  $format  Export format ('csv' or 'pdf')
     * @param  string  $filename  The filename for the download
     * @param  User  $user  The user requesting the export
     */
    public function exportWithRoleFilter(
        Collection $data,
        array $columns,
        string $format,
        string $filename,
        User $user
    ): StreamedResponse|Response {
        if ($format === 'csv') {
            return $this->exportToCSV($data, $columns, $filename);
        }

        return $this->exportToPDF($data, $columns, 'dashboard.exports.pdf-template', [
            'filename' => $filename,
            'title' => 'Export Report',
            'user' => $user->name,
        ]);
    }

    public function saveExportToStorage(Collection $data, array $columns, string $format, string $path): void
    {
        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        if ($format === 'csv') {
            $this->generateCsvFile($data, $columns, $path);

            return;
        }

        $this->generatePdfFile($data, $columns, $path);
    }
}
