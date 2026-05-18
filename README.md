# PAJAK - UI

[![PHP Version](https://img.shields.io/badge/php-8.5-blue.svg)](https://www.php.net/releases/8.5/)
[![Laravel](https://img.shields.io/badge/laravel-13-red.svg)](https://laravel.com/)
[![Node Version](https://img.shields.io/badge/node-25-green.svg)](https://nodejs.org/en/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

PAJAK UI is a Laravel package providing reusable Blade components for common UI patterns, along with accompanying frontend assets (SCSS, TypeScript) built with Vite. It is designed to be installed into Laravel projects via Composer, offering a consistent and customizable UI foundation.

Live component previews and usage examples are available at **[design.pajak.sk](https://design.pajak.sk)**.

## Requirements

- PHP 8.5+
- Laravel 13+

## Installation

```bash
composer require pajak/ui
```

The service provider is auto-discovered. Run the install command to publish the config and assets:

```bash
php artisan install:pajak-ui
```

## Asset Inclusion

**Pre-built assets** are published to `public/vendor/pajak/ui/` during installation — no build step required.

**From source** (recommended for production, granular inclusion): publish the SCSS/TS sources and import only what you need in your own Vite config:

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

```scss
// Import only what you need
@use 'vendor/pajak/ui/css/form/form-standalone';
@use 'vendor/pajak/ui/css/button/index';
```

## Documentation

Component reference lives in [`docs/`](docs/):

| File | Contents |
|------|----------|
| [`docs/form.md`](docs/form.md) | Form components — `field`, `input`, `password`, `email`, `number`, `tel`, `url`, `textarea`, `select`, `toggle`, `checkbox`, `radio`, `radio-card`, `file`, `dropzone`, `slider`, `repeater`, `section`, `group`, `hidden`, `avatar` |
| [`docs/button.md`](docs/button.md) | Button component — props, Blade usage |
| [`docs/calendar.md`](docs/calendar.md) | Calendar / date-picker component — props, Blade usage, JS API |
| [`docs/toast.md`](docs/toast.md) | Toast notification system — JS API, asset inclusion |
| [`docs/http.md`](docs/http.md) | HTTP connector — JS API for AJAX form submission |
| [`docs/dark-mode.md`](docs/dark-mode.md) | Dark mode support — configuration and usage |

## License

[MIT](LICENSE)
