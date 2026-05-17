# CLAUDE.md

This file provides guidance to Claude Code when working with code in this repository.

## Project Overview

`pajak/ui` is a Laravel UI component library providing reusable Blade components with PHP backend and frontend assets (SCSS, TypeScript). It is not a standalone app — it is installed into Laravel projects via Composer.

## Commands

All commands must be run via Docker. The `cli` service runs PHP (8.5), and the `node` service runs Node (25).

### Tests
```bash
docker compose run --rm cli vendor/bin/phpunit                        # all tests
docker compose run --rm cli vendor/bin/phpunit --testsuite Unit       # unit tests only
docker compose run --rm cli vendor/bin/phpunit --testsuite Integration # integration tests only
docker compose run --rm cli vendor/bin/phpunit --filter TestClassName  # single test class
```

### Linting & Static Analysis
```bash
docker compose run --rm cli vendor/bin/phpcs --standard=ruleset.xml   # code style (Slevomat)
docker compose run --rm cli vendor/bin/phpcbf --standard=ruleset.xml  # auto-fix code style
docker compose run --rm cli php -d memory_limit=2G vendor/bin/phpstan analyse # static analysis (level 6)
```

Note: PHPStan always exits with code 1 due to an unused `missingType.generics` ignore in `phpstan.neon`. This is pre-existing and not caused by new code — if the only reported "error" is the unused-ignore message, the code is clean.

### Frontend Assets
```bash
docker compose run --rm node npm run build   # build CSS/TS assets via Vite
docker compose run --rm node npm run dev     # dev server with HMR
```

## Architecture

### Source Layout

```
src/
├── UiServiceProvider.php          — package entry point
├── Common/                        — shared utilities, enums, DTOs, services
│   ├── Console/Commands/          — Artisan commands
│   ├── Dto/                       — ToastData, UploadedFile
│   ├── Enums/                     — MessageType, Method, Size, State, ToastType
│   └── View/
│       └── Button.php
├── Calendar/
│   └── View/Components/
│       └── DatePicker.php
└── Form/
    ├── Concerns/
    │   └── HasFileUploads.php
    ├── Enums/                     — FormLayout, TelPattern
    └── View/Components/           — all form Blade component classes
resources/
├── assets/
│   ├── css/
│   │   ├── variables.scss         — all design tokens (colors, type, spacing, radius, shadows)
│   │   ├── main.scss              — full bundle (imports all feature partials)
│   │   ├── button/
│   │   │   ├── index.scss         — button-only bundle entry point
│   │   │   └── _button.scss
│   │   ├── calendar/
│   │   │   ├── calendar-standalone.scss — calendar-only bundle entry point
│   │   │   └── _calendar.scss
│   │   ├── form/
│   │   │   ├── form-standalone.scss — form-only bundle entry point
│   │   │   ├── _field.scss
│   │   │   ├── _input.scss
│   │   │   ├── _select.scss
│   │   │   ├── _checkbox.scss
│   │   │   ├── _radio.scss
│   │   │   ├── _toggle.scss
│   │   │   ├── _slider.scss
│   │   │   ├── _repeater.scss
│   │   │   ├── _file.scss
│   │   │   ├── _file-upload.scss
│   │   │   └── _dropzone.scss
│   │   └── toast/
│   │       ├── toast-standalone.scss — toast-only bundle entry point
│   │       └── _toast.scss
│   └── js/
│       ├── main.ts                — full JS bundle (re-exports all feature modules)
│       ├── registry.ts            — component registry
│       ├── calendar/
│       │   └── calendar.ts        — PajakCalendar / DatePicker API
│       ├── form/
│       │   ├── form.ts            — form-only bundle entry point (re-exports all form APIs)
│       │   ├── select.ts          — PajakSelect API
│       │   ├── toggle.ts          — PajakToggle API
│       │   ├── checkbox.ts        — PajakCheckbox API
│       │   ├── slider.ts          — PajakSlider API
│       │   ├── repeater.ts        — PajakRepeater API
│       │   ├── file.ts            — PajakFile API
│       │   ├── file-upload.ts     — PajakFileUpload API
│       │   └── form-submit.ts     — form submission helpers
│       ├── http/
│       │   └── connector.ts       — HTTP connector for AJAX requests
│       └── toast/
│           └── toast.ts           — PajakToast API
└── views/
    ├── calendar/                  — Blade templates for calendar components
    ├── common/                    — Blade templates for common components (button)
    └── form/                      — Blade templates for form components
```

### Blade Component Namespaces

| Namespace | PHP class path | Usage |
|-----------|---------------|-------|
| `pajak` | `Pajak\Ui\Common\View\*` | `<x-pajak-*>` |
| `pajak-form` | `Pajak\Ui\Form\View\Components\*` | `<x-pajak-form::input>` |
| `pajak-calendar` | `Pajak\Ui\Calendar\View\Components\*` | `<x-pajak-calendar::date-picker>` |

### Key Identifiers

| Concern | Value |
|---------|-------|
| Composer name | `pajak/ui` |
| PHP namespace | `Pajak\Ui` |
| Blade prefix (common) | `pajak` |
| Blade prefix (form) | `pajak-form` |
| Config key | `pajak-ui` |
| Artisan install | `install:pajak-ui` |
| Route prefix | `pajak` |
| Asset publish tag | `pajak-ui-assets` |
| Source publish tag | `pajak-ui-sources` |
| Views publish tag | `pajak-ui-views` |
| Config publish tag | `pajak-ui-config` |
| Translations publish tag | `pajak-ui-translations` |
| Public asset path | `vendor/pajak/ui` |

### Frontend

#### Built assets (`public/assets/`)

Built assets **are committed** to the repository so consumers can use the package without a build step.

Vite builds CSS/JS bundles into `public/assets/`:

| File | Description |
|------|-------------|
| `public/assets/main.css` | Full CSS bundle — all components |
| `public/assets/main.js` | Full JS bundle — all components |
| `public/assets/form-standalone.css` | Form-only CSS bundle |
| `public/assets/form.js` | Form-only JS bundle |
| `public/assets/repeater.js` | Repeater-only JS bundle |
| `public/assets/slider.js` | Slider-only JS bundle |
| `public/assets/connector.js` | HTTP connector JS bundle |
| `public/assets/toast.js` | Toast JS bundle |
| `public/assets/toast-standalone.css` | Toast-only CSS bundle |
| `public/assets/calendar.js` | Calendar JS bundle |
| `public/assets/calendar-standalone.css` | Calendar-only CSS bundle |

After adding or changing CSS/JS, rebuild and commit the `public/assets/` output:
```bash
docker compose run --rm node npm run build
```

#### Granular CSS — source import (recommended for production)

Vite does not produce separate per-feature CSS files from the built output. For granular inclusion, consumers should import SCSS source files directly. Publish the sources:
```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

This copies the SCSS/TS source tree to `resources/css/vendor/pajak/ui/` and `resources/js/vendor/pajak/ui/`. Then import only what you need in your own Vite config:

```scss
// Import only form components
@use 'vendor/pajak/ui/css/form/form-standalone';

// Or individual partials
@use 'vendor/pajak/ui/css/form/input';
@use 'vendor/pajak/ui/css/form/toggle';
```

```ts
// Import all form JS at once
import { PajakSelect, PajakToggle, PajakCheckbox } from 'vendor/pajak/ui/js/form/form';

// Or import individual modules
import { PajakSelect } from 'vendor/pajak/ui/js/form/select';
PajakSelect.initAll();
```

#### Re-publishing built assets

After building, re-publish to a consumer project:
```bash
php artisan vendor:publish --tag=pajak-ui-assets --force
```

### Adding a New Feature Module

When adding a new feature (e.g. `table`, `nav`):

1. Create `src/<Feature>/View/Components/*.php`
2. Create `resources/views/<feature>/*.blade.php`
3. Create `resources/assets/css/<feature>/` with `_component.scss` partials and an `index.scss` that imports them all (plus `@use '../variables'`)
4. Create `resources/assets/js/<feature>/*.ts` with named API exports
5. Create `resources/assets/js/<feature>/<feature>.ts` that re-exports all JS APIs for that feature
6. Add `@use '<feature>/<component>'` to `resources/assets/css/main.scss`
7. Add feature exports to `resources/assets/js/main.ts` (import from `./<feature>/<feature>`)
8. Add `Blade::componentNamespace(...)` in `UiServiceProvider::registerComponentNamespaces()`
9. Add both Vite entries to `vite.config.js`: `resources/assets/css/<feature>/index.scss` and `resources/assets/js/<feature>/<feature>.ts`
10. Rebuild: `docker compose run --rm node npm run build`

> **Naming rule:** the JS bundle entry must be named `<feature>.ts` (not `index.ts`) to avoid a Rollup output collision with the CSS `index.scss` entry, which would cause Vite to append a numeric suffix (`index2.js`).

## Documentation

Component usage docs live in `docs/`:

| File | Contents |
|------|----------|
| `docs/form.md` | All form components — props, Blade usage, JS API, asset inclusion |
| `docs/button.md` | Button component — props, Blade usage |
| `docs/calendar.md` | Calendar / date-picker component — props, Blade usage, JS API |
| `docs/toast.md` | Toast notification system — JS API, asset inclusion |
| `docs/http.md` | HTTP connector — JS API for AJAX requests |
| `docs/dark-mode.md` | Dark mode support — configuration and usage |

**Every new component or prop must be documented in the corresponding `docs/` file before the work is considered complete.** Follow the existing format: a usage example, a props table, and (where applicable) a JS API section.

## Coding Conventions

- Every PHP file starts with `declare(strict_types=1);`
- Slevomat coding standard enforced via `ruleset.xml`
- DTOs are `final readonly class` with constructor promotion
- Enums are backed string enums (`enum Foo: string`)
- PHP 8.5+ required; use modern features (readonly, enums, match, named arguments, nullsafe operator)
- Use `sprintf()` for string building — no concatenation with `.`
- Blade component `render()` must return `Illuminate\Contracts\View\View` (not the concrete `Illuminate\View\View`)
