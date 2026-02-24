# Code Metrics Report

Livewire package that renders a code metrics report page for Nova/admin users.

## Installation

Add repository and package in your Laravel app `composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/dmkulyk/code-metrics-report"
    }
  ],
  "require": {
    "dmkulyk/code-metrics-report": "dev-main"
  }
}
```

Then run:

```bash
composer update dmkulyk/code-metrics-report
```

## Route

The package registers:

- `GET /code-metrics-report`

Route middleware:

- `nova`
- `admin`

Route name:

- `code-metrics-report`

## Configuration

The package uses `code_metrics_tool` config key.

Current defaults:

- `report_path`: `reports/code-metrics.json`
- `commit`: `BITBUCKET_COMMIT` (or `local`)
- `branch`: `BITBUCKET_BRANCH` (or `local`)
- `build_number`: `BITBUCKET_BUILD_NUMBER` (or `0`)

## Report Format

The report file should be valid JSON. The UI expects keys like:

- `summary`
- `files`
- `generated_at`

## Notes

- Views are loaded with namespace `code-metrics-report::`.
- Service provider is auto-discovered by Laravel.
