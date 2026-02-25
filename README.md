
Livewire package that generates and renders a code metrics report page for Nova / admin users.

## Requirements

- PHP ^8.2
- Laravel ^12.0
- Livewire ^4.0

## Installation

Because this package is distributed via GitHub (not Packagist), add the VCS repository and the version constraint to your `composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/dmkulyk/code-metrics-report"
    }
  ],
  "require": {
    "dmkulyk/code-metrics-report": "^1.0"
  }
}
```

Then install:

```bash
composer update dmkulyk/code-metrics-report
```

The service provider is auto-discovered by Laravel — no manual registration required.

## Artisan Command

### `code-metrics:generate`

Scans the `app/` directory, counts lines of code, and writes a JSON report to the configured output path.
Optionally reads a PHPUnit Clover XML file to include code-coverage data.

```bash
php artisan code-metrics:generate
```

**Options**

| Option | Description | Default |
|---|---|---|
| `--coverage-xml=` | Path to a PHPUnit Clover XML coverage report | `reports/coverage.xml` |
| `--output=` | Override the output path for the JSON report | value from config |

**Examples**

```bash
# Basic run — writes to reports/code-metrics.json
php artisan code-metrics:generate

# With coverage data
php artisan code-metrics:generate --coverage-xml=reports/coverage.xml

# Custom output path
php artisan code-metrics:generate --output=storage/metrics.json
```

**Typical CI usage (Bitbucket Pipelines)**

```yaml
- php artisan code-metrics:generate --coverage-xml=reports/coverage.xml
```

The command reads CI metadata from environment variables automatically (see [Configuration](#configuration)).

### Output format

```json
{
  "generated_at": "2026-02-25T12:00:00+00:00",
  "commit": "abc1234",
  "branch": "main",
  "build_number": "42",
  "summary": {
    "total_lines": 32741,
    "code_lines": 23315,
    "comment_lines": 4171,
    "blank_lines": 5255,
    "code_coverage": 84.5
  },
  "files": [
    {
      "name": "app/Http/Controllers/ExampleController.php",
      "total_lines": 120,
      "code_lines": 85,
      "comment_lines": 20,
      "blank_lines": 15
    }
  ]
}
```

`code_coverage` is only present when a valid `--coverage-xml` file is found.

## Route

The package registers a read-only view route:

| Method | URI | Name |
|---|---|---|
| GET | `/code-metrics-report` | `code-metrics-report` |

Route middleware applied: `nova`, `admin`.

## Configuration

Publish the config if you need to override defaults:

```bash
php artisan vendor:publish --tag=code-metrics-report-config
```

`config/code_metrics_tool.php`:

| Key | Env variable | Default |
|---|---|---|
| `report_path` | `CODE_METRICS_REPORT_PATH` | `reports/code-metrics.json` |
| `commit` | `BITBUCKET_COMMIT` | `local` |
| `branch` | `BITBUCKET_BRANCH` | `local` |
| `build_number` | `BITBUCKET_BUILD_NUMBER` | `0` |

## Changelog

### v1.0.1
- Added `code-metrics:generate` artisan command with optional Clover XML coverage parsing.

### v1.0.0
- Initial release: Livewire component and route for rendering a pre-generated metrics JSON report.

## Notes

- Views are loaded with namespace `code-metrics-report::`.
- Service provider is auto-discovered by Laravel.
