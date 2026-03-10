<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class GeneratePlaywrightEvidenceReport extends Command
{
    protected $signature = 'evidence:playwright-report {--dir=test-results} {--out=} {--limit=200}';

    protected $description = 'Generate a PDF evidence report from Playwright artifacts';

    public function handle(): int
    {
        $dir = (string) $this->option('dir');
        $dir = $dir !== '' ? $dir : 'test-results';
        $dirPath = base_path($dir);

        if (! is_dir($dirPath)) {
            $this->error("Directory not found: {$dirPath}");
            return self::FAILURE;
        }

        $out = (string) ($this->option('out') ?: '');
        $outPath = $out !== '' ? base_path($out) : $dirPath.DIRECTORY_SEPARATOR.'evidence-report.pdf';

        $limit = (int) $this->option('limit');
        if ($limit < 1) {
            $limit = 200;
        }

        $files = collect(File::allFiles($dirPath))
            ->filter(fn ($f) => strtolower($f->getExtension()) === 'png')
            ->sortBy(fn ($f) => $f->getMTime())
            ->values()
            ->take($limit);

        $items = [];
        foreach ($files as $file) {
            $path = $file->getPathname();
            $contents = @file_get_contents($path);
            if ($contents === false) {
                continue;
            }
            $items[] = [
                'name' => $file->getFilename(),
                'relative' => str_replace(base_path().DIRECTORY_SEPARATOR, '', $path),
                'base64' => base64_encode($contents),
                'mtime' => date('c', (int) $file->getMTime()),
            ];
        }

        $meta = [
            'generated_at' => now()->toIso8601String(),
            'dir' => $dir,
            'out' => str_replace(base_path().DIRECTORY_SEPARATOR, '', $outPath),
            'count' => count($items),
        ];

        $pdf = \PDF::loadView('reports.playwright-evidence', [
            'meta' => $meta,
            'items' => $items,
            'summary' => [
                'projects' => Arr::wrap(env('PLAYWRIGHT_PROJECTS')),
            ],
        ])->setPaper('a4', 'portrait');

        $pdfDir = dirname($outPath);
        if (! is_dir($pdfDir)) {
            mkdir($pdfDir, 0777, true);
        }
        file_put_contents($outPath, $pdf->output());

        $this->info('Evidence report generated: '.$outPath);
        return self::SUCCESS;
    }
}

