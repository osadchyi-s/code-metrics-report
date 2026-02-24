<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Ts\CodeMetricsReport\Livewire\CodeMetricsReport;

Route::get('code-metrics-report', CodeMetricsReport::class)
    ->middleware(['nova', 'admin'])
    ->name('code-metrics-report');
