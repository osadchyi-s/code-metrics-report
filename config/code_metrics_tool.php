<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Code Metrics Report Path
    |--------------------------------------------------------------------------
    |
    | Path to the JSON report generated during CI and baked into the
    | deployment image. Relative paths are resolved from the project base.
    |
    */
    'report_path' => env('CODE_METRICS_REPORT_PATH', 'reports/code-metrics.json'),

    /*
    |--------------------------------------------------------------------------
    | CI Build Metadata
    |--------------------------------------------------------------------------
    |
    | These variables are injected by Bitbucket Pipelines and embedded in the
    | generated report. Defaults to 'local' when running outside of CI.
    |
    */
    'commit' => env('BITBUCKET_COMMIT', 'local'),
    'branch' => env('BITBUCKET_BRANCH', 'local'),
    'build_number' => env('BITBUCKET_BUILD_NUMBER', '0'),
];
