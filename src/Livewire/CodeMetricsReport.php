<?php

declare(strict_types=1);

namespace Ts\CodeMetricsReport\Livewire;

use Exception;
use Illuminate\Support\Facades\File;
use Livewire\Component;

class CodeMetricsReport extends Component
{
    /**
     * The code metrics report data.
     */
    public ?array $report = null;

    /**
     * Error message if report cannot be loaded.
     */
    public ?string $error = null;

    /**
     * Currently selected metric view.
     */
    public string $selectedView = 'overview';

    /**
     * Mount the component and load the report.
     */
    public function mount(): void
    {
        $this->loadReport();
    }

    /**
     * Load the code metrics report from file.
     */
    public function loadReport(): void
    {
        try {
            $reportPath = config('code_metrics_tool.report_path', 'reports/code-metrics.json');
            $fullPath = str_starts_with($reportPath, DIRECTORY_SEPARATOR)
                ? $reportPath
                : base_path($reportPath);

            if (! File::exists($fullPath)) {
                $this->error = "Code metrics report not found at: {$reportPath}";
                $this->report = null;

                return;
            }

            $content = File::get($fullPath);
            $this->report = json_decode($content, true);

            if (! is_array($this->report)) {
                $this->error = 'Invalid JSON format in code metrics report.';
                $this->report = null;

                return;
            }

            $this->error = null;
        } catch (Exception $e) {
            $this->error = 'Error loading code metrics report: ' . $e->getMessage();
            $this->report = null;
        }
    }

    /**
     * Change the selected view.
     */
    public function changeView(string $view): void
    {
        $this->selectedView = $view;
    }

    /**
     * Render the component.
     */
    public function render(): \Illuminate\View\View
    {
        return view('code-metrics-report::livewire.code-metrics-report', [
            'report' => $this->report,
            'error' => $this->error,
            'selectedView' => $this->selectedView,
        ])->layout('code-metrics-report::components.layouts.code-metrics-report');
    }
}
