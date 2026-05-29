# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.2.1] - 2026-05-29

### Added

- **Button link variant** — `<x-pajak::button>` now accepts an `href` prop; when set, the component renders as an `<a>` tag with full button styling; disabled state uses `is-disabled` class and `aria-disabled` instead of the HTML `disabled` attribute

### Fixed

- Keyboard navigation for **Accordion** — Arrow Up/Down, Home, and End move focus between headers (WAI-ARIA accordion pattern)
- Keyboard navigation for **Tabs** — Arrow keys, Home, and End cycle focus through enabled tabs; roving tabindex ensures only the active tab is in the tab stop sequence
- Keyboard navigation for **Segmented control** — Arrow keys, Home, and End move focus and select the next/previous enabled option; roving tabindex applied on init
- Keyboard navigation for **Select** — Arrow keys navigate options in the open dropdown; Escape closes the dropdown and returns focus to the trigger; Backspace on an empty multiselect input removes the last chip with a screen-reader announcement; ArrowDown on the trigger or search input moves focus into the option list
- Keyboard navigation for **Calendar** — Arrow keys, Page Up/Down, Home, and End navigate the day grid; roving tabindex resolves the focusable day on each render; month navigation announcements delivered via an `aria-live` region; panel open/close returns focus correctly to the trigger
- Keyboard navigation for **Popover** — opening moves focus to the first focusable element inside the panel (or the panel itself); closing returns focus to the trigger
- **Button hover colour** — link buttons no longer lose their variant text colour on hover due to `<a>` tag colour inheritance; all button variants now explicitly restore their colour in the `:hover` rule
- **Calendar day hover** — selected, range-start, and range-end days no longer revert to the neutral hover background; they retain the primary-600 background on hover
- Screen-reader announcements for **Repeater** row add/remove via a `role="status"` live region; announcement strings come from the `pajak::ui.form.repeater.row_added` and `row_removed` translation keys (CS and SK translations included)
- Screen-reader announcement for **Copy button** — successful copy is announced via a `role="status"` live region; the bubble is now also dismissed immediately on page scroll
- Decorative SVG icons in **Toast** and **Copy button** marked `aria-hidden="true"`
- **Nav tab** `tabindex` attribute — inactive tabs now receive `tabindex="-1"` so keyboard users skip them; the active tab gets `tabindex="0"`
- **Calendar** ARIA improvements — trigger and dialog panel have `aria-label`; navigation buttons have `aria-label`; weekday header cells have `role="columnheader"` and full day-name `aria-label`; day grid has `role="grid"`; day buttons have `role="gridcell"`, full date `aria-label`, and `aria-pressed` state; decorative elements are `aria-hidden`; screen-reader live region added for month navigation announcements

## [0.2.0] - 2026-05-25

### Added

#### Display components

- **Spinner** — arc and dot-pulse spinners in six sizes and multiple colour variants; `<x-pajak::spinner>` with `type`, `size`, and `color` props
- **Badge** — inline status badge with `color`, `size`, `outline`, and `dot` (indicator dot only) modes; `<x-pajak::badge>`
- **Tooltip** — CSS-driven tooltip wrapper with eight placement options; `<x-pajak::tooltip text="..." placement="...">`
- **Divider** — horizontal/vertical rule with solid/dashed/dotted styles, light/strong weight, and optional text label; `<x-pajak::divider>`
- **Alert** — dismissible alert in info/success/warning/error types and banner/card variants; `<x-pajak::alert>`
- **Avatar** — initials-based avatar with coloured backgrounds, six sizes, online/offline/away status indicator, and ring style; `<x-pajak::avatar>`; pair with `<x-pajak::avatar-group>` for stacked groups
- **Skeleton** — loading placeholder in line, circle, and rectangle shapes; `<x-pajak::skeleton>`
- **Progress** — determinate progress bar with label and percentage display; `<x-pajak::progress value="..." max="...">`
- **Badge** — colour, size, outline, and dot variants; `BadgeColor` enum covers primary, secondary, neutral, success, warning, and error

#### Navigation components

- **Breadcrumbs** — navigation trail accepting `BreadcrumbItem` DTOs, with chevron or slash separators and pill variant; `<x-pajak::breadcrumbs :items="$items">`
- **Tabs** — tab container with underline and boxed variants and interactive JS switching; `<x-pajak::tabs>` + `<x-pajak::tab>`
- **Segmented control** — button-group selector with default and pill variants, full-width mode, and JS activation; `<x-pajak::segmented>` + `<x-pajak::segmented-option>`
- **Navbar** — top navigation bar with standard and transparent variants and slots for logo, links, and actions; `<x-pajak::navbar>` + `<x-pajak::nav-link>`
- **Nav tab bar** — bottom/secondary tab bar for mobile-style navigation; `<x-pajak::nav-tab-bar>` + `<x-pajak::nav-tab>`
- **Sidebar** — collapsible sidebar with standard, compact, and floating variants and multi-level item support; `<x-pajak::sidebar>` + `<x-pajak::sidebar-section>` + `<x-pajak::sidebar-item>` + `<x-pajak::sidebar-sub-item>`

#### Layout & container components

- **Card** — content container with default, bordered, and accent colour variants; `<x-pajak::card>`
- **Accordion** — expandable disclosure panels with single/multi open modes and bordered/flush variants; `<x-pajak::accordion>` + `<x-pajak::accordion-item>`
- **Banner** — full-width informational strip with info/success/warning/error types, optional progress bar, dismissible mode, and sticky-top placement; `<x-pajak::banner>`
- **Empty state** — empty-data placeholder with icon, text, and action slot in default and card variants; `<x-pajak::empty-state>`
- **List** — styled list container with list row items; `<x-pajak::list>` + `<x-pajak::list-row>`

#### Overlay & interactive components

- **Modal** — dialog overlay with xs–xl sizes and sheet (bottom-drawer) mode; JS-controlled via `PajakModal`; `<x-pajak::modal id="...">`
- **Dialog** — confirmation dialog with info/warning/danger/success types, stacked-button layout option, and JS control via `PajakDialog`; `<x-pajak::dialog id="...">`
- **Drawer** — slide-in panel from left, right, top, or bottom; JS-controlled via `PajakDrawer`; `<x-pajak::drawer id="...">`
- **Popover** — anchored floating panel with eight placement options and optional title; JS-controlled via `PajakPopover`; `<x-pajak::popover id="...">`

#### Data display components

- **Stepper** — step progress indicator with horizontal, vertical, and circle variants; tracks current/total step count; `<x-pajak::stepper>` + `<x-pajak::stepper-step>`
- **Detail** — key/value display component with compact, 2-column grid, and flush variants; optional header with action link; supports copyable rows; `<x-pajak::detail>` + `<x-pajak::detail-row>`
- **Copy button** — button that copies a string to the clipboard with icon-only mode; `<x-pajak::copy-button value="...">`
- **Error page** — full-page error display with built-in titles and descriptions for 401/403/404/500/503 codes; custom title/description override; `<x-pajak::error-page code="404">`

#### Table component

- **Table** — full-featured data table built via a fluent `Table::make()` builder, AJAX-powered, with server-side state management via `sessionStorage`
  - **Columns** — `TextColumn`, `BadgeColumn`, `StatusColumn`, `DateColumn`, `AmountColumn`, `AvatarColumn`, `TwoLineColumn`; columns are sortable, hideable, and support colour maps via `HasColorMap`
  - **Filters** — `TextFilter`, `SelectFilter`, `NumberFilter`, `DateFilter`; filter chips shown inline, editors rendered in a popover
  - **Actions** — `LinkAction`, `FormAction`, `ModalAction`, `ConfirmAction`; configurable inline vs overflow position, per-row visibility via `visibleIf()`
  - **Pagination** — `EloquentPaginator` wraps `LengthAwarePaginator`; `ArrayPaginator` wraps in-memory collections; per-page options selectable by users
  - **Bulk actions** — row selection with a bulk-action bar; `selectable()` / `bulkActions()` on the builder
  - **Column visibility** — user-togglable column show/hide; `columnVisibility()` on the builder
  - **Search** — integrated search input; `searchable()` on the builder
  - **Localisation** — translation files for EN, CS, and SK

#### Email components

- **Email template components** — a full suite of HTML-email Blade components for building responsive transactional emails: `<x-pajak::email-wrap>`, `email-header`, `email-hero`, `email-body`, `email-cta`, `email-alert`, `email-note`, `email-infocard>` / `email-infocard-row`, `email-steps` / `email-step`, `email-divider`, `email-footer`; accent colour theming via `EmailAccent` enum

#### Button enhancements

- **Button loading state** — `loading` prop on `<x-pajak::button>` renders an arc spinner and dims the label text; `cursor: progress` and `pointer-events: none` are applied automatically

#### Value objects

- **Money** — `Money` value object converts minor-unit integers to formatted currency strings with configurable currency symbol, multiplier, and symbol placement

[0.2.0]: https://github.com/patrikjak/ui/compare/v0.1.0...v0.2.0
[Unreleased]: https://github.com/patrikjak/ui/compare/v0.2.0...HEAD
