# Navbar

A top navigation bar component supporting six layout variants, plus a separate bottom tab bar for mobile. No JavaScript required — active state is controlled via props.

## Asset inclusion

```html
<!-- Full bundle (already includes navbar) -->
<link rel="stylesheet" href="/vendor/pajak/ui/main.css">

<!-- Standalone -->
<link rel="stylesheet" href="/vendor/pajak/ui/navbar-standalone.css">
```

## Navbar variants

### Standard (default)

Rounded pill navbar — the default app shell, light background.

```blade
<x-pajak::navbar>
    <x-slot:brand>
        <span class="my-mark">P</span>
        <span class="my-word">pajak</span>
    </x-slot:brand>
    <x-slot:links>
        <x-pajak::nav-link href="/" label="Dashboard" :active="true" />
        <x-pajak::nav-link href="/returns" label="Returns" />
        <x-pajak::nav-link href="/docs" label="Documents" :count="3" />
    </x-slot:links>
    <x-slot:actions>
        <button>Help</button>
        <x-pajak::avatar initials="AK" />
    </x-slot:actions>
</x-pajak::navbar>
```

### Underline

Flat bottom-border with underline active indicator — for content-heavy pages.

```blade
<x-pajak::navbar variant="underline">
    <x-slot:brand>…</x-slot:brand>
    <x-slot:links>
        <x-pajak::nav-link href="/" label="Home" />
        <x-pajak::nav-link href="/returns" label="Returns" :active="true" />
    </x-slot:links>
    <x-slot:actions>…</x-slot:actions>
</x-pajak::navbar>
```

### Dark

Dark background — for marketing or contrast pages.

```blade
<x-pajak::navbar variant="dark">
    <x-slot:brand>…</x-slot:brand>
    <x-slot:links>
        <x-pajak::nav-link href="/pricing" label="Pricing" :active="true" />
    </x-slot:links>
    <x-slot:actions>…</x-slot:actions>
</x-pajak::navbar>
```

### Split

Fixed 240 px brand panel on the left, flexible main area on the right — pairs with a persistent sidebar.

```blade
<x-pajak::navbar variant="split">
    <x-slot:brand>…</x-slot:brand>
    <x-slot:title>
        <span style="color: var(--fg-tertiary)">Returns / </span>2025 Federal
    </x-slot:title>
    <x-slot:actions>
        <button>Save draft</button>
        <x-pajak::avatar initials="AK" />
    </x-slot:actions>
</x-pajak::navbar>
```

### Stacked

Two-tier navbar: primary nav on top, secondary sub-links below on a subtle background.

```blade
<x-pajak::navbar variant="stacked">
    <x-slot:brand>…</x-slot:brand>
    <x-slot:links>
        <x-pajak::nav-link href="/" label="Dashboard" />
        <x-pajak::nav-link href="/returns" label="Returns" :active="true" />
    </x-slot:links>
    <x-slot:actions>…</x-slot:actions>
    <x-slot:subLinks>
        <x-pajak::nav-link href="/all" label="All returns" :active="true" />
        <x-pajak::nav-link href="/progress" label="In progress" />
        <x-pajak::nav-link href="/submitted" label="Submitted" />
    </x-slot:subLinks>
</x-pajak::navbar>
```

### Mobile

Compact 56 px bar with a hamburger button, title stack, and action icons.

```blade
<x-pajak::navbar variant="mobile">
    <x-slot:brand>
        <button class="pajak-navbar__menu-btn" aria-label="Menu">…</button>
    </x-slot:brand>
    <x-slot:links>
        <div class="pajak-navbar__title">
            <div class="pajak-navbar__title-line">2025 Federal</div>
            <div class="pajak-navbar__subtitle">Step 4 of 9 · Deductions</div>
        </div>
    </x-slot:links>
    <x-slot:actions>…</x-slot:actions>
</x-pajak::navbar>
```

## Navbar props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `variant` | `NavbarVariant` | `Standard` | Layout variant. |

## Navbar slots

| Slot | Variants | Description |
|------|----------|-------------|
| `brand` | All | Brand mark / logo area. |
| `links` | Standard, Underline, Dark, Stacked, Mobile | Navigation link list. |
| `actions` | All | Right-side actions (buttons, avatar, search). |
| `subLinks` | Stacked only | Secondary navigation row rendered below the main row. |
| `title` | Split only | Page title / breadcrumb displayed in the main area. |

## NavLink props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | `string` | — | **Required.** Link text. |
| `href` | `string` | `'#'` | Link URL. |
| `active` | `bool` | `false` | Marks the link as the current page. |
| `count` | `int\|null` | `null` | Pill badge with a number (e.g. unread count). |
| `dot` | `bool` | `false` | Small red notification dot. |

---

## Bottom tab bar

A mobile bottom navigation bar with icon + label tab items.

```blade
<x-pajak::nav-tab-bar>
    <x-pajak::nav-tab label="Home" :active="true">
        <x-slot:icon>
            <svg …>…</svg>
        </x-slot:icon>
    </x-pajak::nav-tab>
    <x-pajak::nav-tab label="Returns">
        <x-slot:icon>…</x-slot:icon>
    </x-pajak::nav-tab>
    <x-pajak::nav-tab label="Docs" :dot="true">
        <x-slot:icon>…</x-slot:icon>
    </x-pajak::nav-tab>
    <x-pajak::nav-tab label="Profile">
        <x-slot:icon>…</x-slot:icon>
    </x-pajak::nav-tab>
</x-pajak::nav-tab-bar>
```

## NavTab props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | `string` | — | **Required.** Tab label text. |
| `active` | `bool` | `false` | Marks this tab as selected. |
| `dot` | `bool` | `false` | Small red notification dot overlaid on the icon. |

## NavTab slots

| Slot | Description |
|------|-------------|
| `icon` | SVG icon rendered above the label. Optional — tab renders label only if absent. |
