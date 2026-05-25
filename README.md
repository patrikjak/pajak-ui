# PAJAK - UI

[![PHP Version](https://img.shields.io/badge/php-8.5-blue.svg)](https://www.php.net/releases/8.5/)
[![Laravel](https://img.shields.io/badge/laravel-13-red.svg)](https://laravel.com/)
[![Node Version](https://img.shields.io/badge/node-25-green.svg)](https://nodejs.org/en/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

PAJAK UI is a Laravel package providing reusable Blade components for common UI patterns, along with accompanying frontend assets (SCSS, TypeScript) built with Vite. It is designed to be installed into Laravel projects via Composer, offering a consistent and customizable UI foundation.

Live component previews and usage examples are available at **[design.pajak.studio](https://design.pajak.studio)**.

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

For quick component selection, see **[`docs/components.md`](docs/components.md)** — a single-page index with Blade tags, purpose, key props, and when-to-use guidance for every component.

Full component reference lives in [`docs/`](docs/):

**Forms & Inputs**

| File | Contents |
|------|----------|
| [`docs/form.md`](docs/form.md) | Form components — `field`, `input`, `password`, `email`, `number`, `tel`, `url`, `textarea`, `select`, `toggle`, `checkbox`, `radio`, `radio-card`, `file`, `dropzone`, `slider`, `repeater`, `section`, `group`, `hidden`, `avatar` |
| [`docs/calendar.md`](docs/calendar.md) | Calendar / date-picker — props, Blade usage, JS API |
| [`docs/avatar.md`](docs/avatar.md) | Avatar component — standalone usage outside forms |

**Actions & Feedback**

| File | Contents |
|------|----------|
| [`docs/button.md`](docs/button.md) | Button component — variants, sizes, loading state |
| [`docs/copy-button.md`](docs/copy-button.md) | Copy-to-clipboard button — variants, JS API |
| [`docs/toast.md`](docs/toast.md) | Toast notification system — JS API, asset inclusion |
| [`docs/alert.md`](docs/alert.md) | Inline alert banners — types and dismissal |
| [`docs/banner.md`](docs/banner.md) | Page-level banners — variants and dismiss |
| [`docs/dialog.md`](docs/dialog.md) | Confirmation dialogs — types, JS API |
| [`docs/modal.md`](docs/modal.md) | Modal overlays — size variants, JS API |
| [`docs/drawer.md`](docs/drawer.md) | Side drawers — placement variants, JS API |
| [`docs/progress.md`](docs/progress.md) | Progress bar component |
| [`docs/spinner.md`](docs/spinner.md) | Arc spinner — sizes, variants |

**Navigation**

| File | Contents |
|------|----------|
| [`docs/navbar.md`](docs/navbar.md) | Navbar and nav tab bar components |
| [`docs/sidebar.md`](docs/sidebar.md) | Sidebar with sections, items, and sub-items |
| [`docs/breadcrumbs.md`](docs/breadcrumbs.md) | Breadcrumb trail component |
| [`docs/tabs.md`](docs/tabs.md) | Tab groups — variants and interactive tabs |
| [`docs/segmented.md`](docs/segmented.md) | Segmented control |
| [`docs/stepper.md`](docs/stepper.md) | Multi-step stepper — variants and states |

**Layout & Display**

| File | Contents |
|------|----------|
| [`docs/card.md`](docs/card.md) | Card component — variants and slots |
| [`docs/table.md`](docs/table.md) | Server-driven AJAX data table — PHP builder API, filters, pagination, actions |
| [`docs/list.md`](docs/list.md) | List and list-row components |
| [`docs/detail.md`](docs/detail.md) | Detail display — key/value rows and variants |
| [`docs/accordion.md`](docs/accordion.md) | Accordion — variants and modes |
| [`docs/popover.md`](docs/popover.md) | Popover — placement options, JS API |
| [`docs/tooltip.md`](docs/tooltip.md) | Tooltip component |
| [`docs/badge.md`](docs/badge.md) | Badge / status chip component |
| [`docs/divider.md`](docs/divider.md) | Horizontal divider |
| [`docs/skeleton.md`](docs/skeleton.md) | Skeleton loading placeholders |
| [`docs/empty-state.md`](docs/empty-state.md) | Empty state — variants and slots |
| [`docs/error-page.md`](docs/error-page.md) | Error pages — 404, 500, 403, 401, 503 |

**Email**

| File | Contents |
|------|----------|
| [`docs/email.md`](docs/email.md) | Email template components — composable Blade sub-components for transactional emails |

**Utilities**

| File | Contents |
|------|----------|
| [`docs/http.md`](docs/http.md) | HTTP connector — JS API for AJAX form submission |
| [`docs/dark-mode.md`](docs/dark-mode.md) | Dark mode support — configuration and usage |
| [`docs/value-objects.md`](docs/value-objects.md) | Value objects — PHP helpers shipped with the package |

## License

[MIT](LICENSE)
