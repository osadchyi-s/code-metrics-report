<?php

declare(strict_types=1);

namespace Ts\CodeMetricsReport\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use SimpleXMLElement;
use Throwable;

class GenerateCodeMetricsReport extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'code-metrics:generate
                            {--coverage-xml= : Path to the PHPUnit Clover XML coverage report}
                            {--output= : Override the output path for the JSON report}';

    /**
     * The console command description.
     */
    protected $description = 'Generate a code-metrics.json report from the app/ source tree and optional PHPUnit coverage data.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $outputPath = $this->option('output')
            ?? config('code_metrics_tool.report_path', 'reports/code-metrics.json');

        $fullOutput = str_starts_with($outputPath, DIRECTORY_SEPARATOR)
            ? $outputPath
            : base_path($outputPath);

        $coverageXmlPath = $this->option('coverage-xml')
            ?? base_path('reports/coverage.xml');

        $this->info('Scanning source files…');
        [$fileSummaries, $totals] = $this->scanSourceFiles(base_path('app'));

        $coverage = null;
        if (File::exists($coverageXmlPath)) {
            $this->info('Parsing coverage report…');
            $coverage = $this->parseCoverage($coverageXmlPath);
        } else {
            $this->warn("Coverage XML not found at {$coverageXmlPath} — skipping.");
        }

        $report = [
            'generated_at' => now()->toIso8601String(),
            'commit' => config('code_metrics_tool.commit'),
            'branch' => config('code_metrics_tool.branch'),
            'build_number' => config('code_metrics_tool.build_number'),
            'summary' => array_merge($totals, $coverage !== null ? ['code_coverage' => $coverage] : []),
            'files' => $fileSummaries,
        ];

        File::ensureDirectoryExists(dirname($fullOutput));
        File::put($fullOutput, json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->info("Report written to {$fullOutput}");
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total lines', number_format($totals['total_lines'])],
                ['Code lines', number_format($totals['code_lines'])],
                ['Comment lines', number_format($totals['comment_lines'])],
                ['Blank lines', number_format($totals['blank_lines'])],
                ['Files analysed', number_format(count($fileSummaries))],
                ['Coverage', $coverage !== null ? $coverage . '%' : 'N/A'],
            ]
        );

        return self::SUCCESS;
    }

    /**
     * Recursively scan PHP files under $directory and return per-file stats plus totals.
     *
     * @return array{0: list<array<string, mixed>>, 1: array<string, int>}
     */
    private function scanSourceFiles(string $directory): array
    {
        $files = File::allFiles($directory);
        $fileSummaries = [];
        $totals = ['total_lines' => 0, 'code_lines' => 0, 'comment_lines' => 0, 'blank_lines' => 0];

        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $lines = file($file->getRealPath(), FILE_IGNORE_NEW_LINES);
            $stats = $this->countLines($lines);
            $relativeName = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file->getRealPath());

            $fileSummaries[] = array_merge(['name' => $relativeName], $stats);

            foreach (array_keys($totals) as $key) {
                $totals[$key] += $stats[$key];
            }
        }

        usort($fileSummaries, fn ($a, $b) => $b['total_lines'] <=> $a['total_lines']);

        return [$fileSummaries, $totals];
    }

    /**
     * Count total/code/comment/blank lines for an array of raw source lines.
     *
     * @param  string[]  $lines
     * @return array{total_lines: int, code_lines: int, comment_lines: int, blank_lines: int}
     */
    private function countLines(array $lines): array
    {
        $total = count($lines);
        $blank = 0;
        $comment = 0;
        $inBlock = false;

        foreach ($lines as $raw) {
            $line = trim($raw);

            if ($line === '') {
                $blank++;

                continue;
            }

            if ($inBlock) {
                $comment++;
                if (str_contains($line, '*/')) {
                    $inBlock = false;
                }

                continue;
            }

            if (str_starts_with($line, '//') || str_starts_with($line, '#')) {
                $comment++;

                continue;
            }

            if (str_starts_with($line, '/*') || str_starts_with($line, '/**')) {
                $comment++;
                $inBlock = ! str_contains($line, '*/');

                continue;
            }

            if (str_starts_with($line, '*')) {
                $comment++;

                continue;
            }
        }

        $code = $total - $blank - $comment;

        return [
            'total_lines' => $total,
            'code_lines' => max(0, $code),
            'comment_lines' => $comment,
            'blank_lines' => $blank,
        ];
    }

    /**
     * Parse a PHPUnit Clover XML file and return the overall line coverage percentage.
     */
    private function parseCoverage(string $xmlPath): float
    {
        try {
            $xml = new SimpleXMLElement(File::get($xmlPath));
            $metrics = $xml->project->metrics ?? null;

            if ($metrics === null) {
                return 0.0;
            }

            $covered = (int) $metrics['coveredstatements'];
            $total = (int) $metrics['statements'];

            if ($total === 0) {
                return 0.0;
            }

            return round($covered / $total * 100, 2);
        } catch (Throwable) {
            $this->warn('Could not parse coverage XML — returning 0.');

            return 0.0;
        }
    }
}
