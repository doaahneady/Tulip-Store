<?php

namespace App\Services\Dashboard\Contracts;

use App\Models\User;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Response;

interface ExportServiceInterface
{
    /**
     * Export data to CSV format with streaming response
     *
     * @param Collection $data The data to export
     * @param array $columns Column definitions ['key' => 'Header Label']
     * @param string $filename The filename for the download
     * @return StreamedResponse
     */
    public function exportToCSV(Collection $data, array $columns, string $filename): StreamedResponse;

    /**
     * Export data to PDF format using DomPDF
     *
     * @param Collection $data The data to export
     * @param array $columns Column definitions ['key' => 'Header Label']
     * @param string $template The blade template to use for PDF
     * @param array $metadata Additional metadata (title, subtitle, etc.)
     * @return Response
     */
    public function exportToPDF(Collection $data, array $columns, string $template, array $metadata = []): Response;

    /**
     * Queue a large export for async processing (datasets > 1000 rows)
     *
     * @param Collection $data The data to export
     * @param array $columns Column definitions
     * @param string $format Export format ('csv' or 'pdf')
     * @param User $user The user requesting the export
     * @return void
     */
    public function queueLargeExport(Collection $data, array $columns, string $format, User $user): void;

    /**
     * Check if a dataset should be queued for async export
     *
     * @param Collection $data The data to check
     * @return bool True if dataset exceeds threshold
     */
    public function shouldQueueExport(Collection $data): bool;

    /**
     * Get the export threshold (number of rows)
     *
     * @return int
     */
    public function getExportThreshold(): int;
}
