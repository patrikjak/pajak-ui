# Email Template Components

Composable Blade sub-components for building styled transactional emails. All components are CSS-only (no JavaScript) and are intentionally free of dark-mode overrides — email clients handle media queries inconsistently, so colours are resolved to explicit light-mode values.

## Assets

### Pre-built (no build step required)

Include the full bundle:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
```

Or use the email-only bundle:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/email-standalone.css') }}">
```

### Source import (recommended for production)

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

```scss
@use 'vendor/pajak/ui/css/email/email-standalone';
```

---

## Component overview

| Component | Tag | Purpose |
|-----------|-----|---------|
| `EmailWrap` | `<x-pajak::email-wrap>` | 600 px centred column — outermost wrapper |
| `EmailHeader` | `<x-pajak::email-header>` | Blue top bar with logo slot and optional tag chip |
| `EmailHero` | `<x-pajak::email-hero>` | Coloured hero band — eyebrow, title, subtitle |
| `EmailBody` | `<x-pajak::email-body>` | White content area |
| `EmailInfocard` | `<x-pajak::email-infocard>` | Key–value detail card |
| `EmailInfocardRow` | `<x-pajak::email-infocard-row>` | Single row inside an infocard |
| `EmailAlert` | `<x-pajak::email-alert>` | Contextual alert strip |
| `EmailCta` | `<x-pajak::email-cta>` | CTA button link |
| `EmailSteps` | `<x-pajak::email-steps>` | Numbered steps container |
| `EmailStep` | `<x-pajak::email-step>` | Single numbered step |
| `EmailDivider` | `<x-pajak::email-divider>` | Horizontal rule |
| `EmailNote` | `<x-pajak::email-note>` | Small muted footnote paragraph |
| `EmailFooter` | `<x-pajak::email-footer>` | Footer with logo, links, and legal copy |

---

## Full example

```blade
<x-pajak::email-wrap>

    <x-pajak::email-header tag="Welcome">
        <x-slot:logo>
            <img src="{{ asset('images/logo.svg') }}" alt="ACME" height="32">
        </x-slot:logo>
    </x-pajak::email-header>

    <x-pajak::email-hero color="#3F6FCA">
        <x-slot:eyebrow>Getting started</x-slot:eyebrow>
        <x-slot:title>Welcome — your account is ready.</x-slot:title>
        Here's what to do next to get up and running.
    </x-pajak::email-hero>

    <x-pajak::email-body>

        <p style="font-size:17px;font-weight:600;margin-bottom:14px;">Hi {{ $user->first_name }},</p>
        <p style="font-size:15px;line-height:1.65;color:#45566D;margin-bottom:20px;">
            Thank you for creating your account. We're here every step of the way.
        </p>

        <x-pajak::email-steps>
            <x-pajak::email-step :number="1" title="Complete your profile"
                description="Add your details so we can pre-fill your information." />
            <x-pajak::email-step :number="2" title="Import your documents"
                description="Upload or connect your account for automatic import." />
            <x-pajak::email-step :number="3" title="Review and submit"
                description="We'll check everything before you confirm." />
        </x-pajak::email-steps>

        <x-pajak::email-cta href="{{ route('dashboard') }}">
            Get started
        </x-pajak::email-cta>

        <x-pajak::email-divider />

        <x-pajak::email-note>
            Questions? Our support team is available Monday–Friday, 9:00–18:00.
        </x-pajak::email-note>

    </x-pajak::email-body>

    <x-pajak::email-footer>
        <x-slot:logo>
            <img src="{{ asset('images/logo-footer.svg') }}" alt="ACME" height="24">
        </x-slot:logo>

        <x-slot:links>
            <a href="{{ route('account') }}">My Account</a>
            <a href="{{ route('help') }}">Help Centre</a>
            <a href="{{ route('privacy') }}">Privacy Policy</a>
        </x-slot:links>

        ACME Corp · 123 Example Street · Warsaw, Poland<br>
        You received this because you created an account. <a href="{{ $unsubscribeUrl }}">Unsubscribe</a>.
    </x-pajak::email-footer>

</x-pajak::email-wrap>
```

---

## EmailWrap

Root wrapper — a 600 px centred column. Place all other email components inside it.

```blade
<x-pajak::email-wrap>
    {{-- email components here --}}
</x-pajak::email-wrap>
```

**Slot:** `$slot` — all child components.

---

## EmailHeader

Top bar with the primary blue background. Accepts a `$logo` named slot and an optional `tag` prop for a small chip label.

```blade
{{-- Logo image --}}
<x-pajak::email-header tag="Confirmation">
    <x-slot:logo>
        <img src="{{ asset('images/logo-white.svg') }}" alt="ACME" height="32">
    </x-slot:logo>
</x-pajak::email-header>

{{-- SVG logo --}}
<x-pajak::email-header tag="Reminder">
    <x-slot:logo>
        <svg .../>
    </x-slot:logo>
</x-pajak::email-header>

{{-- No tag --}}
<x-pajak::email-header>
    <x-slot:logo>
        <img src="{{ asset('images/logo-white.svg') }}" alt="ACME" height="32">
    </x-slot:logo>
</x-pajak::email-header>
```

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `tag` | `?string` | `null` | Short chip label displayed on the right (e.g. `Welcome`, `Reminder`) |

| Slot | Description |
|------|-------------|
| `$logo` | Logo image or SVG — rendered on a blue background, so use a white/light logo |

---

## EmailHero

Coloured hero band below the header. Background colour is set via the `color` prop (any CSS colour value). Supports named slots for eyebrow label, title, and a default slot for the subtitle.

```blade
{{-- Primary blue (default) --}}
<x-pajak::email-hero>
    <x-slot:eyebrow>Getting started</x-slot:eyebrow>
    <x-slot:title>Welcome to ACME.</x-slot:title>
    Your account is ready. Here's what to do next.
</x-pajak::email-hero>

{{-- Green for confirmation --}}
<x-pajak::email-hero color="#1A7A43">
    <x-slot:eyebrow>Submitted successfully</x-slot:eyebrow>
    <x-slot:title>Your return is on its way.</x-slot:title>
    Keep this confirmation for your records.
</x-pajak::email-hero>

{{-- Warning amber --}}
<x-pajak::email-hero color="#B87318">
    <x-slot:eyebrow>26 days remaining</x-slot:eyebrow>
    <x-slot:title>Don't miss the deadline.</x-slot:title>
    Your draft is saved — just a few minutes to complete.
</x-pajak::email-hero>
```

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `color` | `string` | `'var(--color-primary-600)'` | CSS colour value for the hero band background |

| Slot | Description |
|------|-------------|
| `$eyebrow` | Small uppercase label above the title |
| `$title` | Large headline — supports HTML (e.g. `<br>` for line breaks) |
| `$slot` | Subtitle / body copy below the title |

---

## EmailBody

White content area. All body content (salutation, paragraphs, infocards, alerts, CTAs) goes inside this component.

```blade
<x-pajak::email-body>
    <p style="font-size:17px;font-weight:600;margin-bottom:14px;">Hi Jan,</p>
    <p style="font-size:15px;line-height:1.65;color:#45566D;margin-bottom:20px;">
        Your return has been submitted successfully.
    </p>
    {{-- infocards, alerts, cta, divider, note --}}
</x-pajak::email-body>
```

**Slot:** `$slot` — all body content.

> **Note:** Salutation and paragraph text are written directly as HTML inside `EmailBody` rather than via a dedicated component — this keeps the API surface small and gives full control over font sizes, weights, and spacing per email.

---

## EmailInfocard

A rounded key–value detail card, typically used to summarise submission details, draft status, or document metadata.

```blade
<x-pajak::email-infocard title="Submission details">
    <x-pajak::email-infocard-row label="Confirmation number" value="REF-2025-08412" accent="blue" />
    <x-pajak::email-infocard-row label="Return type" value="PIT-37" />
    <x-pajak::email-infocard-row label="Tax year" value="2025" />
    <x-pajak::email-infocard-row label="Net result" value="Refund: 1 240,00 PLN" accent="green" />
</x-pajak::email-infocard>
```

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `title` | `?string` | `null` | Optional section label displayed above the rows |

**Slot:** `$slot` — `EmailInfocardRow` components.

### EmailInfocardRow

A single row inside `EmailInfocard`.

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | `string` | — | Left-side label (muted) |
| `value` | `string` | — | Right-side value (bold) |
| `accent` | `?string` | `null` | Colour accent for the value: `blue` \| `green` |

---

## EmailAlert

A contextual alert strip. The `type` prop drives the icon and colour scheme. Named slots for the title and message.

```blade
{{-- Success --}}
<x-pajak::email-alert type="success">
    <x-slot:title>Refund processing time</x-slot:title>
    Your refund is typically processed within 45 days of submission.
</x-pajak::email-alert>

{{-- Warning --}}
<x-pajak::email-alert type="warning">
    <x-slot:title>Late filing penalty</x-slot:title>
    Submitting after the deadline may result in a significant penalty.
</x-pajak::email-alert>

{{-- Info --}}
<x-pajak::email-alert type="info">
    <x-slot:title>Secure access</x-slot:title>
    Your document is protected by your account login.
</x-pajak::email-alert>
```

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `type` | `string` | `'info'` | `info` \| `success` \| `warning` \| `error` |

| Slot | Description |
|------|-------------|
| `$title` | Bold heading inside the alert |
| `$slot` | Alert message body |

---

## EmailCta

A centred call-to-action link button. Multiple `EmailCta` instances can be placed side-by-side by wrapping them in a flex container, or use the `secondary` prop for an outline variant alongside a primary.

```blade
{{-- Primary (default blue) --}}
<x-pajak::email-cta href="{{ route('dashboard') }}">
    Start my return
</x-pajak::email-cta>

{{-- Custom colour --}}
<x-pajak::email-cta href="{{ route('submission', $ref) }}" color="#27AE60">
    View my submission
</x-pajak::email-cta>

{{-- Secondary outline --}}
<x-pajak::email-cta href="{{ route('account') }}" :secondary="true">
    View in account
</x-pajak::email-cta>

{{-- Side-by-side primary + secondary --}}
<div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin:28px 0;">
    <x-pajak::email-cta href="{{ route('download', $doc) }}">Download PDF</x-pajak::email-cta>
    <x-pajak::email-cta href="{{ route('account') }}" :secondary="true">View in account</x-pajak::email-cta>
</div>
```

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `href` | `string` | — | Link destination URL |
| `secondary` | `bool` | `false` | Renders an outline/ghost variant instead of filled |
| `color` | `?string` | `null` | CSS colour value to override the default primary blue background (ignored for secondary) |

**Slot:** `$slot` — button label text.

---

## EmailSteps + EmailStep

A vertical numbered step list. Each `EmailStep` renders a numbered (or done-check) bubble alongside a title and optional description.

```blade
<x-pajak::email-steps>
    <x-pajak::email-step :number="1" :done="true"
        title="Profile completed"
        description="Your personal details are saved." />
    <x-pajak::email-step :number="2"
        title="Import your PIT-11"
        description="Upload your employer's statement." />
    <x-pajak::email-step :number="3"
        title="Review and submit"
        description="Most returns take under 10 minutes." />
</x-pajak::email-steps>
```

**EmailStep props:**

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `number` | `int` | — | Step number displayed in the bubble |
| `title` | `string` | — | Step heading |
| `description` | `?string` | `null` | Optional supporting text |
| `done` | `bool` | `false` | Replaces the number with a check icon and changes the bubble to green |

---

## EmailDivider

A thin horizontal rule for separating content sections.

```blade
<x-pajak::email-divider />
```

No props or slots.

---

## EmailNote

A small muted footnote paragraph — typically placed after a divider for legal, support, or reference copy.

```blade
<x-pajak::email-note>
    Save or print this email as your proof of submission. The confirmation number above
    is your official reference.
</x-pajak::email-note>

{{-- With a link --}}
<x-pajak::email-note>
    Questions? Contact us at
    <a href="mailto:support@example.com">support@example.com</a>.
</x-pajak::email-note>
```

**Slot:** `$slot` — footnote content. Plain links (`<a>` tags) are styled automatically.

---

## EmailFooter

The footer band at the bottom of the email. Accepts a `$logo` named slot, a `$links` named slot for navigation links, and a default slot for the legal/address copy.

```blade
<x-pajak::email-footer>
    <x-slot:logo>
        <img src="{{ asset('images/logo-blue.svg') }}" alt="ACME" height="24">
    </x-slot:logo>

    <x-slot:links>
        <a href="{{ route('account') }}">My Account</a>
        <a href="{{ route('help') }}">Help Centre</a>
        <a href="{{ route('privacy') }}">Privacy Policy</a>
        <a href="{{ $unsubscribeUrl }}">Unsubscribe</a>
    </x-slot:links>

    ACME Corp · 123 Example Street · Warsaw, Poland<br>
    You received this because you created an account.
    <a href="{{ $unsubscribeUrl }}">Unsubscribe</a>.
</x-pajak::email-footer>
```

| Slot | Description |
|------|-------------|
| `$logo` | Logo displayed centred at the top of the footer |
| `$links` | Navigation links — each `<a>` is styled automatically |
| `$slot` | Legal/address copy below the links |
