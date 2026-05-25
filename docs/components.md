# Component Index

Quick reference for selecting the right component. Each entry lists the Blade tag, what it does, key props, and disambiguation notes where components overlap.

---

## Forms & Inputs

| Component | Blade tag | Purpose | Key props |
|-----------|-----------|---------|-----------|
| Form wrapper | `<x-pajak-form::form>` | AJAX-aware `<form>` with CSRF, method spoofing, and submit handling | `action`, `method`, `id` |
| Input | `<x-pajak-form::input>` | Generic text input | `name`, `label`, `type`, `value`, `disabled`, `error` |
| Email input | `<x-pajak-form::email>` | Email-typed input | `name`, `label`, `value`, `error` |
| Password input | `<x-pajak-form::password>` | Password with show/hide toggle | `name`, `label`, `error` |
| Number input | `<x-pajak-form::number>` | Numeric input | `name`, `label`, `min`, `max`, `step` |
| Tel input | `<x-pajak-form::tel>` | Phone number input with pattern | `name`, `label`, `pattern` |
| URL input | `<x-pajak-form::url>` | URL-typed input | `name`, `label`, `value` |
| Textarea | `<x-pajak-form::textarea>` | Multi-line text input | `name`, `label`, `rows`, `error` |
| Select | `<x-pajak-form::select>` | Styled `<select>` with search and async loading | `name`, `label`, `options`, `multiple`, `searchable`, `error` |
| Toggle | `<x-pajak-form::toggle>` | Boolean on/off switch | `name`, `label`, `checked`, `disabled` |
| Checkbox | `<x-pajak-form::checkbox>` | Single checkbox with optional description | `name`, `label`, `description`, `checked`, `disabled`, `error` |
| Radio | `<x-pajak-form::radio>` | Single radio button | `name`, `label`, `value`, `checked` |
| Radio card | `<x-pajak-form::radio-card>` | Large card-style radio option | `name`, `label`, `value`, `description`, `checked` |
| File | `<x-pajak-form::file>` | File upload input with preview | `name`, `label`, `accept`, `error` |
| Dropzone | `<x-pajak-form::dropzone>` | Drag-and-drop file upload area | `name`, `label`, `accept`, `multiple` |
| Slider | `<x-pajak-form::slider>` | Range slider | `name`, `label`, `min`, `max`, `step`, `value` |
| Repeater | `<x-pajak-form::repeater>` | Dynamic add/remove row list | `name`, `label`, `min`, `max` |
| Form section | `<x-pajak-form::section>` | Titled section divider inside a form | `title`, `description` |
| Form group | `<x-pajak-form::group>` | Groups fields side-by-side | — |
| Hidden | `<x-pajak-form::hidden>` | Hidden input | `name`, `value` |
| Avatar (form) | `<x-pajak-form::avatar>` | Avatar image picker within a form | `name`, `label` |
| Date picker | `<x-pajak-calendar::date-picker>` | Floating date / date-range picker with optional time and presets | `name`, `label`, `value`, `range`, `time`, `presets` |

> **Form docs:** [form.md](form.md) · [calendar.md](calendar.md)

---

## Actions & Buttons

| Component | Blade tag | Purpose | Key props |
|-----------|-----------|---------|-----------|
| Button | `<x-pajak::button>` | General-purpose button | `variant` (`primary`/`secondary`/`ghost`/`danger`/`link`), `size`, `type`, `disabled`, `loading` |
| Copy button | `<x-pajak::copy-button>` | Copies a value to clipboard on click; wraps any trigger content | `value`, `variant` (`button`/`icon`) |

> **Choosing Button vs Copy Button:** use `copy-button` only when the sole action is clipboard copy. For everything else, use `button`.

> **Button docs:** [button.md](button.md) · [copy-button.md](copy-button.md)

---

## Feedback & Notifications

| Component | Blade tag | Purpose | When to use |
|-----------|-----------|---------|-------------|
| Toast | `<x-pajak::toast>` + JS `PajakToast` | Transient auto-dismissing overlay notification | Confirmations, async results — not for persistent warnings |
| Alert | `<x-pajak::alert>` | Inline contextual message (info/success/warning/error) | Persistent, in-page feedback tied to content — not dismissible by default |
| Banner | `<x-pajak::banner>` | Full-width page-level notice with optional dismiss | Announcements, maintenance notices, onboarding prompts at the top of a page |
| Dialog | `<x-pajak::dialog>` | Small centered confirmation dialog (native `<dialog>`) | Simple yes/no confirmations and small notices |
| Modal | `<x-pajak::modal>` | Larger overlay with head/body/footer and close button | Forms, detail views, multi-step flows inside an overlay |
| Drawer | `<x-pajak::drawer>` | Panel sliding in from a viewport edge | Filter panels, side-sheets, mobile navigation |

> **Choosing between overlays:**
> - **Toast** — fire-and-forget, auto-dismisses, no user decision needed
> - **Alert** — inline, stays visible, tied to a specific section
> - **Banner** — page-top, full-width, one call-to-action
> - **Dialog** — requires a binary decision (confirm/cancel), compact
> - **Modal** — richer content that needs the user's full attention (forms, details)
> - **Drawer** — supplementary panel without leaving the current page (filters, navigation)

> **Key props:**
> - `alert`: `type` (`info`/`success`/`warning`/`error`), `title`, `dismissible`
> - `banner`: `type`, `title`, `dismissible`, `$action` slot
> - `dialog`: `id`, `type` (`DialogType`), `title`, `message`
> - `modal`: `id`, `title`, `size`
> - `drawer`: `id`, `title`, `side` (`right`/`left`/`bottom`/`top`)

> **Docs:** [toast.md](toast.md) · [alert.md](alert.md) · [banner.md](banner.md) · [dialog.md](dialog.md) · [modal.md](modal.md) · [drawer.md](drawer.md)

---

## Status & Progress

| Component | Blade tag | Purpose | Key props |
|-----------|-----------|---------|-----------|
| Badge | `<x-pajak::badge>` | Small inline status/category chip | `color` (`BadgeColor`), `size`, `outline`, `dot` |
| Spinner | `<x-pajak::spinner>` | Loading indicator | `size` (`xs`/`sm`/`md`/`lg`/`xl`), `variant` (`arc`/`dots`/`bar`) |
| Progress | `<x-pajak::progress>` | Linear determinate progress bar | `value` (0–100), `size`, `color` |
| Skeleton | `<x-pajak::skeleton>` | Shimmer placeholder while content loads | `shape`, `style` (set width inline) |
| Stepper | `<x-pajak::stepper>` + `<x-pajak::stepper-step>` | Multi-step progress indicator | `variant` (`horizontal`/`pill`/`vertical`/`bar`); step: `step`, `title`, `state` (`StepperStepState`) |

> **Choosing Spinner vs Skeleton vs Progress:**
> - **Spinner** — unknown duration, simple in-place loading (button, overlay)
> - **Skeleton** — replaces real UI while loading to prevent layout shift
> - **Progress** — known percentage completion (upload, multi-step process)

> **Docs:** [badge.md](badge.md) · [spinner.md](spinner.md) · [progress.md](progress.md) · [skeleton.md](skeleton.md) · [stepper.md](stepper.md)

---

## Navigation

| Component | Blade tag | Purpose | Key props |
|-----------|-----------|---------|-----------|
| Navbar | `<x-pajak::navbar>` + `<x-pajak::nav-link>` | Top navigation bar | `variant`, `nav-link`: `href`, `label`, `active`, `icon` |
| Nav tab bar | `<x-pajak::nav-tab-bar>` + `<x-pajak::nav-tab>` | Bottom mobile tab bar | `nav-tab`: `href`, `label`, `active`, `icon` |
| Sidebar | `<x-pajak::sidebar>` + `<x-pajak::sidebar-item>` + `<x-pajak::sidebar-section>` + `<x-pajak::sidebar-sub-item>` | Vertical navigation sidebar | `variant`; item: `href`, `label`, `active`, `icon` |
| Breadcrumbs | `<x-pajak::breadcrumbs>` | Navigation breadcrumb trail | `items` (`BreadcrumbItem[]`), `separator` |
| Tabs | `<x-pajak::tabs>` + `<x-pajak::tab>` | Horizontal tab strip | `variant`; tab: `label`, `active`, `count`, `panel` |
| Segmented | `<x-pajak::segmented>` + `<x-pajak::segmented-option>` | Compact mutually-exclusive option picker | option: `label`, `value`, `active` |

> **Choosing Tabs vs Segmented:** use `tabs` for navigating between full content sections; use `segmented` for compact in-page option switching (e.g. day/week/month view toggle).

> **Docs:** [navbar.md](navbar.md) · [sidebar.md](sidebar.md) · [breadcrumbs.md](breadcrumbs.md) · [tabs.md](tabs.md) · [segmented.md](segmented.md)

---

## Layout & Structure

| Component | Blade tag | Purpose | Key props |
|-----------|-----------|---------|-----------|
| Card | `<x-pajak::card>` | Surface container for grouped content | `kicker`, `title`; slots: `$header`, `$footer` |
| Divider | `<x-pajak::divider>` | Horizontal rule or inline OR-separator | `strength` (`subtle`/`default`/`strong`), `label` |
| Accordion | `<x-pajak::accordion>` + `<x-pajak::accordion-item>` | Expandable/collapsible content sections | `variant`, `mode`; item: `title`, `subtitle`, `open` |
| Detail | `<x-pajak::detail>` + `<x-pajak::detail-row>` | Structured key-value data display | `variant`; row: `key`, slot = value |
| List | `<x-pajak::list>` + `<x-pajak::list-row>` | Card-style list of rows with leading/body/trailing slots | row slots: `$leading`, `$trailing` |
| Table | `<x-pajak-table::table>` | Server-driven AJAX data table with PHP builder API | `:table` (`TableBuilder`), `:paginator` |
| Empty state | `<x-pajak::empty-state>` | Placeholder for empty lists or search results | `title`, `message`; slots: `$icon`, `$actions` |
| Error page | `<x-pajak::error-page>` | Full-viewport HTTP error page | `:code` (`404`/`500`/`403`/`401`/`503`), `title`, `description`; slot: `$actions` |

> **Docs:** [card.md](card.md) · [divider.md](divider.md) · [accordion.md](accordion.md) · [detail.md](detail.md) · [list.md](list.md) · [table.md](table.md) · [empty-state.md](empty-state.md) · [error-page.md](error-page.md)

---

## Overlays & Contextual UI

| Component | Blade tag | Purpose | Key props |
|-----------|-----------|---------|-----------|
| Tooltip | `<x-pajak::tooltip>` | Short hover/focus label on any trigger element | `text`, `placement` (`top`/`right`/`bottom`/`left`) |
| Popover | `<x-pajak::popover>` | Rich anchored panel with head/body/foot slots | `id`, `title`, `placement`; trigger via `data-pajak-popover-trigger` |

> **Tooltip vs Popover:** use `tooltip` for a single short text label (no interaction, no actions). Use `popover` for rich content with buttons, links, or structured layout.

> **Docs:** [tooltip.md](tooltip.md) · [popover.md](popover.md)

---

## Display Helpers

| Component | Blade tag | Purpose | Key props |
|-----------|-----------|---------|-----------|
| Avatar | `<x-pajak::avatar>` | Circular user avatar from initials | `initials`, `size`, `color` |
| Avatar group | `<x-pajak::avatar-group>` | Overlapping stack of avatars | `avatars` (array), `max` |

> **Docs:** [avatar.md](avatar.md)

---

## Email Templates

Composable CSS-only Blade components for transactional emails. No JavaScript, no dark-mode overrides.

| Component | Blade tag | Purpose |
|-----------|-----------|---------|
| Wrap | `<x-pajak::email-wrap>` | 600 px centred outer column |
| Header | `<x-pajak::email-header>` | Blue top bar with logo slot |
| Hero | `<x-pajak::email-hero>` | Coloured hero band with title/subtitle |
| Body | `<x-pajak::email-body>` | Main content area |
| CTA | `<x-pajak::email-cta>` | Centered button call-to-action |
| Info card | `<x-pajak::email-infocard>` + `<x-pajak::email-infocard-row>` | Key-value summary table |
| Steps | `<x-pajak::email-steps>` + `<x-pajak::email-step>` | Numbered step list |
| Note | `<x-pajak::email-note>` | Indented callout / caveat block |
| Divider | `<x-pajak::email-divider>` | Horizontal rule |
| Footer | `<x-pajak::email-footer>` | Footer with address and unsubscribe slot |

> **Docs:** [email.md](email.md)

---

## Utilities

| Resource | What it is |
|----------|-----------|
| `PajakToast` JS API | `window.Pajak.toast.show({ message, type, duration })` — see [toast.md](toast.md) |
| HTTP connector | `PajakConnector` — typed `fetch` wrapper for AJAX form submission — see [http.md](http.md) |
| Value objects | PHP classes in `Pajak\Ui\Common\ValueObject\` — see [value-objects.md](value-objects.md) |
| Dark mode | `data-theme="dark"` on any ancestor — see [dark-mode.md](dark-mode.md) |
