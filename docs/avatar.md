# Avatar

Circular user avatar displaying initials. Available as `<x-pajak::avatar>` and `<x-pajak::avatar-group>`.

> All components support dark mode — see [dark-mode.md](dark-mode.md).

## Assets

### Pre-built (no build step required)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/main.js') }}"></script>
```

Or use the avatar-only bundle:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/avatar-standalone.css') }}">
```

### Source import (recommended for production)

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

```scss
// Avatar only
@use 'vendor/pajak/ui/css/avatar/avatar-standalone';

// Or just the partial (when variables are already imported)
@use 'vendor/pajak/ui/css/avatar/avatar';
```

---

## Usage

```blade
{{-- Default --}}
<x-pajak::avatar initials="JK" />

{{-- Sizes --}}
<x-pajak::avatar initials="JK" size="xs" />
<x-pajak::avatar initials="JK" size="sm" />
<x-pajak::avatar initials="JK" size="lg" />
<x-pajak::avatar initials="JK" size="xl" />

{{-- Color variants --}}
<x-pajak::avatar initials="AW" color="sand" />
<x-pajak::avatar initials="MN" color="teal" />
<x-pajak::avatar initials="PR" color="purple" />
<x-pajak::avatar initials="EZ" color="coral" />
<x-pajak::avatar initials="TS" color="slate" />

{{-- Status dot --}}
<x-pajak::avatar initials="JK" status="online" />
<x-pajak::avatar initials="AW" color="sand" status="away" />
<x-pajak::avatar initials="MN" color="teal" status="offline" />

{{-- Ring --}}
<x-pajak::avatar initials="JK" :ring="true" />

{{-- Group stack with overflow count --}}
<x-pajak::avatar-group :overflow="4">
    <x-pajak::avatar initials="JK" />
    <x-pajak::avatar initials="AW" color="sand" />
    <x-pajak::avatar initials="MN" color="teal" />
    <x-pajak::avatar initials="PR" color="purple" />
</x-pajak::avatar-group>
```

---

## Props

### `<x-pajak::avatar>`

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `initials` | `string` | — | Text shown inside the avatar (required) |
| `color` | `AvatarColor` | `AvatarColor::Blue` | Background color |
| `size` | `AvatarSize` | `AvatarSize::Md` | Avatar size |
| `status` | `AvatarStatus\|null` | `null` | Shows a status dot when set |
| `ring` | `bool` | `false` | Primary-colored focus ring around the avatar |

### `<x-pajak::avatar-group>`

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `size` | `AvatarSize` | `AvatarSize::Md` | Size applied to the overflow count bubble |
| `overflow` | `int` | `0` | Number shown in the trailing overflow bubble; hidden when 0 |

**Slot:** `$slot` — one or more `<x-pajak::avatar>` components.

### `AvatarColor` values

| Value | Background |
|-------|-----------|
| `blue` | `#5386E4` |
| `sand` | `#C9A96E` |
| `teal` | `#1C8880` |
| `purple` | `#6B4EBA` |
| `coral` | `#E07A5F` |
| `slate` | `color-neutral-500` |

### `AvatarSize` values

| Value | Pixel size |
|-------|-----------|
| `xs` | 20px |
| `sm` | 28px |
| `md` | 36px |
| `lg` | 48px |
| `xl` | 64px |

### `AvatarStatus` values

| Value | Dot color |
|-------|----------|
| `online` | Green (`color-success`) |
| `away` | Amber (`color-warning`) |
| `offline` | Grey (`color-neutral-300`) |
