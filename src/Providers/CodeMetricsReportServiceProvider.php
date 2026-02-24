<?php

declare(strict_types=1);

namespace Ts\CodeMetricsReport\Providers;

use Illuminate\Support\ServiceProvider;

class CodeMetricsReportServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/code_metrics_tool.php',
            'code_metrics_tool'
        );
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'code-metrics-report');

        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
    }
}
