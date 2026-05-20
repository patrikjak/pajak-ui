# Sidebar

A vertical navigation sidebar supporting six layout variants. No JavaScript required — active state and variant differences are controlled via props and CSS.

## Asset inclusion

```html
<!-- Full bundle (already includes sidebar) -->
<link rel="stylesheet" href="/vendor/pajak/ui/main.css">

<!-- Standalone -->
<link rel="stylesheet" href="/vendor/pajak/ui/sidebar-standalone.css">
```

## Basic usage

```blade
<x-pajak::sidebar>
    <x-slot:brand>
        <span class="my-mark">P</span>
        <span class="my-word">pajak</span>
    </x-slot:brand>

    <x-pajak::sidebar-section label="Workspace" />
    <x-pajak::sidebar-item href="/" label="Dashboard" :active="true">
        <x-slot:icon><svg …>…</svg></x-slot:icon>
    </x-pajak::sidebar-item>
    <x-pajak::sidebar-item href="/returns" label="Returns" :count="12">
        <x-slot:icon><svg …>…</svg></x-slot:icon>
    </x-pajak::sidebar-item>
    <x-pajak::sidebar-item href="/docs" label="Documents" :dot="true">
        <x-slot:icon><svg …>…</svg></x-slot:icon>
    </x-pajak::sidebar-item>

    <x-slot:footer>
        {{-- User card --}}
    </x-slot:footer>
</x-pajak::sidebar>
```

## Variants

### Standard (default)

240 px wide, white background, grouped sections, footer user card.

### Rail

72 px icon-only collapsed rail. Labels are hidden via CSS; a tooltip appears on hover. The `icon` slot of `SidebarItem` is required for this variant to be useful.

```blade
<x-pajak::sidebar variant="rail">
    <x-slot:brand>…mark only…</x-slot:brand>
    <x-pajak::sidebar-item href="/" label="Dashboard" :active="true">
        <x-slot:icon><svg …>…</svg></x-slot:icon>
    </x-pajak::sidebar-item>
</x-pajak::sidebar>
```

### Dark

Same structure as Standard with dark background — for admin or high-contrast app shells.

```blade
<x-pajak::sidebar variant="dark">…</x-pajak::sidebar>
```

### Workspace

Standard width with a `header` slot above the scroll area — used for workspace switchers, primary CTA buttons, or similar.

```blade
<x-pajak::sidebar variant="workspace">
    <x-slot:brand>…</x-slot:brand>
    <x-slot:header>
        {{-- Workspace switcher --}}
        {{-- Primary CTA button --}}
    </x-slot:header>
    …items…
</x-pajak::sidebar>
```

### Wide

280 px — same as Standard but wider. Used when items have sub-nav expanded inline.

```blade
<x-pajak::sidebar variant="wide">
    <x-pajak::sidebar-item href="/returns" label="Returns" :active="true">
        <x-slot:icon>…</x-slot:icon>
    </x-pajak::sidebar-item>
    <div class="pajak-sb__sub">
        <x-pajak::sidebar-sub-item href="/all" label="All returns" :count="24" />
        <x-pajak::sidebar-sub-item href="/progress" label="In progress" :active="true" :count="8" />
        <x-pajak::sidebar-sub-item href="/submitted" label="Submitted" />
    </div>
    <x-pajak::sidebar-item href="/clients" label="Clients">
        <x-slot:icon>…</x-slot:icon>
    </x-pajak::sidebar-item>
</x-pajak::sidebar>
```

### Filters

260 px, subtle page background. The scroll slot holds free-form filter content (checkboxes, chips, sliders) — no filter-specific sub-components are provided.

```blade
<x-pajak::sidebar variant="filters">
    <x-slot:brand>Filter returns</x-slot:brand>
    {{-- Your filter groups here --}}
    <x-slot:footer>
        <button>Cancel</button>
        <button>Apply</button>
    </x-slot:footer>
</x-pajak::sidebar>
```

## Sidebar props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `variant` | `SidebarVariant` | `Standard` | Layout variant. |

## Sidebar slots

| Slot | Description |
|------|-------------|
| `brand` | Top brand/logo area. |
| `header` | Optional area between brand and scroll body — workspace switcher, CTA, etc. |
| `$slot` (default) | Scrollable body — section labels, nav items, sub-nav. |
| `footer` | Bottom pinned area — user card, action buttons. |

## SidebarItem props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | `string` | — | **Required.** Nav item text (also used as rail tooltip). |
| `href` | `string` | `'#'` | Link URL. |
| `active` | `bool` | `false` | Marks the item as the current page. |
| `count` | `int\|null` | `null` | Pill badge with a number. |
| `dot` | `bool` | `false` | Small red notification dot (replaces count). |
| `warn` | `bool` | `false` | Applies red/warning color to the count badge. |

## SidebarItem slots

| Slot | Description |
|------|-------------|
| `icon` | SVG icon rendered before the label. Required for rail variant. |

## SidebarSection props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | `string` | — | **Required.** Section heading text. Hidden in rail variant. |

## SidebarSubItem props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | `string` | — | **Required.** Sub-item link text. |
| `href` | `string` | `'#'` | Link URL. |
| `active` | `bool` | `false` | Marks as current page. |
| `count` | `int\|null` | `null` | Count shown at the right (muted, or primary when active). |

Sub-items must be wrapped in `<div class="pajak-sb__sub">` to get the correct indent and gap.
