# Form Components

Blade components for building forms. All components use the `pajak-form` namespace.

> All components support dark mode — see [dark-mode.md](dark-mode.md).

## Assets

### Pre-built (no build step required)

Include the full bundle published to your `public/` directory. The script must be loaded as a module:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/main.js') }}"></script>
```

Or include only the form bundle:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/form-standalone.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/form.js') }}"></script>
```

The pre-built bundle **auto-initialises** every component on the page once the DOM is ready — no manual `initAll()` call is required. For dynamically-added markup, the APIs are also exposed on `window.Pajak` (e.g. `window.Pajak.PajakCheckbox.initAll(container)` or `window.Pajak.initAll()` to re-scan everything).

### Source import (recommended for production)

Publish the sources:

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

Then import only what you need:

```scss
// All form components 
@use 'vendor/pajak/ui/css/form/form-standalone';

// Or individual partials
@use 'vendor/pajak/ui/css/form/input';
@use 'vendor/pajak/ui/css/form/select';
@use 'vendor/pajak/ui/css/form/toggle';
@use 'vendor/pajak/ui/css/form/checkbox';
@use 'vendor/pajak/ui/css/form/radio';
@use 'vendor/pajak/ui/css/form/field';
```

```ts
// All form JS
import { PajakSelect, PajakToggle, PajakCheckbox, PajakFile } from 'vendor/pajak/ui/js/form/form';

// Or individual modules
import { PajakSelect } from 'vendor/pajak/ui/js/form/select';
import { PajakToggle } from 'vendor/pajak/ui/js/form/toggle';
import { PajakCheckbox } from 'vendor/pajak/ui/js/form/checkbox';
import { PajakFile } from 'vendor/pajak/ui/js/form/file';
```

> **Double-init safety:** every component tracks its instances in a `WeakMap`. Calling `init()` or `initAll()` on an already-upgraded element returns the existing instance — no duplicate listeners are attached. Calling `destroy()` removes the element from the registry so it can be re-initialised cleanly afterwards.

---

## Form

A `<form>` wrapper that handles CSRF injection, method spoofing (for PUT, PATCH, DELETE), and renders a translatable submit button automatically.

```blade
{{-- POST (default) --}}
<x-pajak-form::form action="/login">
    <x-pajak-form::email name="email" label="Email" />
    <x-pajak-form::password name="password" />
</x-pajak-form::form>

{{-- Custom submit label --}}
<x-pajak-form::form action="/login" submit-text="Sign in">
    ...
</x-pajak-form::form>

{{-- PUT — automatically adds @method('PUT') and posts via POST --}}
<x-pajak-form::form action="/users/1" :method="\Pajak\Ui\Common\Enums\Method::Put">
    ...
</x-pajak-form::form>

{{-- DELETE with large submit button --}}
<x-pajak-form::form
    action="/users/1"
    :method="\Pajak\Ui\Common\Enums\Method::Delete"
    submit-text="Delete account"
    submit-size="lg"
>
</x-pajak-form::form>

{{-- Inline layout — label (160 px) beside each input --}}
<x-pajak-form::form action="/deductions/add" :layout="\Pajak\Ui\Form\Enums\FormLayout::Inline">
    <x-pajak-form::select name="category" label="Category" :options="$categories" />
    <x-pajak-form::input name="amount" label="Amount" />
    <x-pajak-form::textarea name="notes" label="Notes" />
</x-pajak-form::form>

{{-- Sectioned layout — use with <x-pajak-form::section> --}}
<x-pajak-form::form action="/settings" :layout="\Pajak\Ui\Form\Enums\FormLayout::Sectioned">
    <x-pajak-form::section title="Account" description="How you sign in and how we reach you.">
        <x-pajak-form::input name="display_name" label="Display name" />
        <x-pajak-form::email name="email" label="Email" />
    </x-pajak-form::section>

    <x-pajak-form::section title="Tax preferences" description="Defaults applied to every new return.">
        <x-pajak-form::group :inline="true">
            <x-pajak-form::select name="tax_year" label="Default tax year" :options="$years" />
            <x-pajak-form::select name="filing_status" label="Filing status" :options="$statuses" />
        </x-pajak-form::group>
    </x-pajak-form::section>
</x-pajak-form::form>
```

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `action` | `string` | — | Form `action` URL |
| `method` | `Method` | `Method::Post` | HTTP method enum. GET and POST map directly; PUT, PATCH, DELETE post with `@method` spoofing |
| `submit-text` | `string\|null` | `null` | Submit button label. When `null`, falls back to the current locale translation (`pajak::ui.form.submit`) |
| `submit-size` | `string` | `'md'` | Submit button size: `sm` \| `md` \| `lg` |
| `layout` | `FormLayout` | `FormLayout::Stacked` | Form layout mode: `FormLayout::Stacked` \| `FormLayout::Inline` \| `FormLayout::Sectioned` |
| `redirect` | `string\|null` | `null` | URL to redirect to after a successful async submission. Rendered as `data-redirect` on the `<form>` element |
| `hide-submit` | `bool` | `false` | When `true`, the built-in submit button and its wrapper `div` are not rendered. Use with an external `data-pajak-form` button. |

### FormLayout enum

```php
use Pajak\Ui\Form\Enums\FormLayout;

FormLayout::Stacked   // default — label above input, fields stacked vertically
FormLayout::Inline    // label (160 px) beside input on the same row, collapses on mobile
FormLayout::Sectioned // no-gap form; use <x-pajak-form::section> for labelled sections
```

**Slot:** `$slot` — form fields rendered before the submit button.

Additional HTML attributes (e.g. `class`, `enctype`, `id`) are forwarded to the `<form>` element.

### Method enum

```php
use Pajak\Ui\Common\Enums\Method;

Method::Get    // <form method="GET">
Method::Post   // <form method="POST">
Method::Put    // <form method="POST"> + @method('PUT')
Method::Patch  // <form method="POST"> + @method('PATCH')
Method::Delete // <form method="POST"> + @method('DELETE')
```

### Async submission

Every `<x-pajak-form::form>` submits asynchronously via `fetch` — no page refresh. The submit button shows an arc spinner while the request is in-flight and is re-enabled once it completes.

**Validation errors (HTTP 422)** are parsed and displayed inline, attached to the matching field's `.pajak-field` wrapper. Each error removes itself as soon as the user changes the input.

**On success**, the form checks for a `data-redirect` attribute and performs a hard redirect if present. Otherwise it fires a `pajak:form:success` event on the form element.

**On other errors**, a `pajak:form:error` event is fired so the consumer can show a toast or notification.

```blade
{{-- Redirect after successful submission --}}
<x-pajak-form::form action="/settings" :redirect="route('dashboard')">
    ...
</x-pajak-form::form>
```

```js
// Listen for success / error when no redirect is set
document.querySelector('form').addEventListener('pajak:form:success', (e) => {
    console.log(e.detail.response); // parsed JSON response body
});

document.querySelector('form').addEventListener('pajak:form:error', (e) => {
    console.log(e.detail.status, e.detail.response);
});
```

### External submit button

Place the submit button outside the form by hiding the built-in one with `hide-submit` and adding `data-pajak-form` to any `<button>` element. The value must be a CSS selector that resolves to the target `<form>`.

The external button participates in the full async flow: arc spinner while in-flight, validation error display, toast, and redirect — identical to the built-in submit button.

```blade
{{-- Form without its own submit button --}}
<x-pajak-form::form id="profile-form" action="/profile" :hide-submit="true">
    <x-pajak-form::input name="name" label="Name" />
    <x-pajak-form::email name="email" label="Email" />
</x-pajak-form::form>

{{-- Button can live anywhere in the DOM --}}
<x-pajak::button data-pajak-form="#profile-form">
    <span class="pajak-btn__label">Save profile</span>
</x-pajak::button>
```

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `hide-submit` | `bool` | `false` | When `true`, the built-in submit button and its wrapper are not rendered |

| Attribute | Element | Description |
|-----------|---------|-------------|
| `data-pajak-form` | `<button>` | CSS selector of the target form. Wired automatically by `initFormSubmitLoaders`. |

### Injecting extra data

Two mechanisms let you add custom fields to the `FormData` before it is sent.

**`pajak:form:before-submit` event** — fired on the form element immediately before the HTTP request, after any registered extra data (see below) has already been merged in. The event is cancelable: calling `e.preventDefault()` aborts the submission entirely (the spinner stops, no request is made).

```js
const form = document.querySelector('form');

form.addEventListener('pajak:form:before-submit', (e) => {
    // e.detail.data is the FormData that will be sent — mutate it freely
    e.detail.data.set('source', 'sidebar');
    e.detail.data.set('userId', getCurrentUserId());

    // Or cancel the submit entirely:
    // e.preventDefault();
});
```

**`PajakForm` imperative API** — register static extra fields on a form instance upfront. These are merged into `FormData` on every submit (before the event fires), so the event handler sees them too.

```js
import { PajakForm } from 'vendor/pajak/ui/js/form/form';
// or via the window global:
// const { PajakForm } = window.Pajak;

const form = document.getElementById('my-form');

PajakForm.addData(form, 'locale', 'sk');       // add / overwrite a key
PajakForm.removeData(form, 'locale');           // remove a specific key
PajakForm.clearData(form);                      // remove all extra keys for this form
```

| Method | Description |
|--------|-------------|
| `PajakForm.addData(form, key, value)` | Registers a key/value pair that is appended to `FormData` on every submit. Overwrites any previously registered value for the same key. |
| `PajakForm.removeData(form, key)` | Removes a previously registered key. |
| `PajakForm.clearData(form)` | Clears all extra data registered for the given form. |

Method spoofing is handled automatically: if a `<input name="_method" value="PUT|PATCH|DELETE">` is present (added by `@method()`), the correct HTTP verb is used for the fetch request.

### Translations

The default submit label resolves from the package translations. Override per locale in your own `lang/vendor/pajak/` files:

```php
// lang/vendor/pajak/en/ui.php
return [
    'form' => [
        'submit' => 'Save changes',
    ],
];
```

---

## Group

A layout wrapper for form fields. By default it stacks fields vertically with consistent spacing (`--space-4`, 16 px). Add `:inline="true"` to place fields side by side in equal-width columns.

Use it whenever you need to group fields outside of a `<x-pajak-form::form>` — for example, inside a modal, a sidebar panel, or a partial that isn't a full form.

```blade
{{-- Vertical stack (default) --}}
<x-pajak-form::group>
    <x-pajak-form::input name="first_name" label="First name" />
    <x-pajak-form::input name="last_name" label="Last name" />
</x-pajak-form::group>

{{-- Inline row — fields share the width equally, aligned to their input bottom edge --}}
<x-pajak-form::group :inline="true">
    <x-pajak-form::input name="first_name" label="First name" />
    <x-pajak-form::input name="last_name" label="Last name" />
    <x-pajak-form::select name="title" label="Title" :options="$titles" />
</x-pajak-form::group>

{{-- Mix any field types --}}
<x-pajak-form::group>
    <x-pajak-form::input name="email" label="Email" />
    <x-pajak-form::select name="role" label="Role" :options="$roles" />
    <x-pajak-form::toggle name="active" label="Active" />
</x-pajak-form::group>

{{-- Extra HTML attributes are forwarded to the wrapper div --}}
<x-pajak-form::group class="my-custom-group" id="profile-fields">
    ...
</x-pajak-form::group>
```

`<x-pajak-form::form>` already applies the same gap between its direct children, so you do **not** need `<x-pajak-form::group>` inside a `<x-pajak-form::form>` — unless you want an inline row inside the form.

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `inline` | `bool` | `false` | When `true`, fields are laid out in a horizontal row with equal widths |

**Slot:** `$slot` — any form components or arbitrary HTML.

Additional HTML attributes (e.g. `class`, `id`) are forwarded to the wrapper `<div>`.

---

## Section

A two-column section divider for use inside a `FormLayout::Sectioned` form. The left column holds a title and optional description; the right column is the field body slot. Sections are separated by a horizontal border automatically when used inside a sectioned form.

```blade
@use('Pajak\Ui\Form\Enums\FormLayout')

<x-pajak-form::form action="/settings" :layout="FormLayout::Sectioned">
    <x-pajak-form::section title="Account" description="How you sign in and how we reach you.">
        <x-pajak-form::input name="display_name" label="Display name" />
        <x-pajak-form::email name="email" label="Email" />
    </x-pajak-form::section>

    <x-pajak-form::section title="Notifications" description="Choose what you want to hear about.">
        <x-pajak-form::toggle name="filing_reminders" label="Filing reminders" checked />
        <x-pajak-form::toggle name="product_news" label="Product news" />
    </x-pajak-form::section>
</x-pajak-form::form>

{{-- Section can also be used standalone (outside a form) --}}
<x-pajak-form::section title="Preferences">
    <x-pajak-form::select name="language" label="Language" :options="$languages" />
</x-pajak-form::section>
```

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `title` | `string\|null` | `null` | Section heading shown in the left meta column |
| `description` | `string\|null` | `null` | Supporting text shown below the title |

**Slot:** `$slot` — form fields or any HTML rendered in the right body column.

Additional HTML attributes (e.g. `class`, `id`) are forwarded to the wrapper `<div>`.

---

## Input

A text input with optional label, icon slot, size, and state modifiers.

```blade
<x-pajak-form::input name="username" label="Username" placeholder="Enter username" />

{{-- Without label --}}
<x-pajak-form::input name="search" placeholder="Search…" />

{{-- Sizes: sm | md (default) | lg --}}
<x-pajak-form::input name="search" label="Search" size="sm" />

{{-- States: default | error | success --}}
<x-pajak-form::input name="code" label="Code" state="error" />

{{-- With icon --}}
<x-pajak-form::input name="search" label="Search">
    <x-slot:icon>
        <svg .../>
    </x-slot:icon>
</x-pajak-form::input>

{{-- Disabled --}}
<x-pajak-form::input name="ref" label="Reference" value="READ-001" disabled />

{{-- With inline error (automatically sets state to error) --}}
<x-pajak-form::input name="username" label="Username" :error="$errors->first('username')" :value="old('username')" />
```

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | `string` | — | `name` attribute |
| `label` | `string\|null` | `null` | Label text shown above the input. When `null`, no label is rendered |
| `type` | `string` | `'text'` | Input type (`text`, `search`, etc.) |
| `placeholder` | `string\|null` | `null` | Placeholder text |
| `value` | `mixed` | `null` | Pre-filled value |
| `state` | `string` | `'default'` | `default` \| `error` \| `success` |
| `size` | `string` | `'md'` | `sm` \| `md` \| `lg` |
| `disabled` | `bool` | `false` | Disables the input |
| `id` | `string\|null` | `null` | Overrides the default `id` (falls back to `name`) |
| `error` | `string\|null` | `null` | Error message shown below the input; automatically sets `state` to `error` |
| `autocomplete` | `string\|null` | `null` | Value for the `autocomplete` attribute. When `null`, the attribute is omitted |
| `pattern` | `string\|null` | `null` | HTML `pattern` attribute regex. When `null`, the attribute is omitted |

**Slots:** `icon` — SVG or icon element shown inside the left edge of the input.

---

## Password

A password input with an inline label and an optional inline confirmation field.

```blade
<x-pajak-form::password name="password" placeholder="Enter password" />

{{-- Custom label --}}
<x-pajak-form::password name="password" label="Your password" placeholder="Enter password" />

{{-- With confirmation field --}}
<x-pajak-form::password
    name="password"
    :confirmation="true"
    confirmation-placeholder="Confirm password"
    confirmation-error="{{ $errors->first('password_confirmation') }}"
/>

{{-- With error handling --}}
<x-pajak-form::password
    name="password"
    :error="$errors->first('password')"
    :confirmation="true"
    :confirmation-error="$errors->first('password_confirmation')"
/>
```

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | `string` | — | `name` attribute; confirmation field uses `{name}_confirmation` |
| `label` | `string\|null` | `null` | Label shown above the primary field. Falls back to the locale translation (`pajak::ui.form.password.label`) |
| `confirmation` | `bool` | `false` | When `true`, renders a second confirm password field |
| `confirmation-label` | `string\|null` | `null` | Label shown above the confirmation field. Falls back to the locale translation (`pajak::ui.form.password.confirmation_label`) |
| `confirmation-placeholder` | `string\|null` | `null` | Placeholder for the confirmation field |
| `confirmation-error` | `string\|null` | `null` | Error message shown below the confirmation field |
| `confirmation-autocomplete` | `string` | `'new-password'` | `autocomplete` for the confirmation field |
| `placeholder` | `string\|null` | `null` | Placeholder for the primary field |
| `value` | `mixed` | `null` | Pre-filled value (primary field only) |
| `state` | `string` | `'default'` | `default` \| `error` \| `success` |
| `size` | `string` | `'md'` | `sm` \| `md` \| `lg` |
| `disabled` | `bool` | `false` | Disables both fields |
| `id` | `string\|null` | `null` | Overrides the primary field `id` (falls back to `name`) |
| `error` | `string\|null` | `null` | Error message shown below the primary field; automatically sets `state` to `error` |
| `autocomplete` | `string` | `'new-password'` | `autocomplete` for the primary field |

---

## Email

A convenience wrapper around `<x-pajak-form::input>` with `type="email"`.

```blade
<x-pajak-form::email name="email" label="Email" placeholder="you@example.com" />
```

Accepts all the same props as [Input](#input) except `type`. Defaults `autocomplete` to `'email'`.

---

## Number

A convenience wrapper around `<x-pajak-form::input>` with `type="number"`.

```blade
<x-pajak-form::number name="quantity" label="Quantity" placeholder="0" />
```

Accepts all the same props as [Input](#input) except `type`. Pass HTML attributes like `min`, `max`, and `step` directly. `autocomplete` is omitted by default.

---

## Tel

A convenience wrapper around `<x-pajak-form::input>` with `type="tel"`.

```blade
<x-pajak-form::tel name="phone" label="Phone" placeholder="+1 555 000 0000" />
```

Accepts all the same props as [Input](#input) except `type`. Defaults `autocomplete` to `'tel'`. Adds a `pattern` prop that accepts a `TelPattern` enum case or any custom regex string.

```blade
@use('Pajak\Ui\Form\Enums\TelPattern')

<x-pajak-form::tel
    name="phone"
    label="Phone number"
    :pattern="TelPattern::Sk"
    :placeholder="TelPattern::Sk->placeholder()"
/>
```

### TelPattern enum

`Pajak\Ui\Form\Enums\TelPattern` provides pre-built `pattern` regexes and matching placeholders for common locales.

| Case | Pattern | Example placeholder |
|------|---------|-------------------|
| `TelPattern::Sk` | `(\+421\|0)[0-9\s]{9,12}` | `+421 912 345 678` |
| `TelPattern::Cz` | `(\+420\|0)[0-9\s]{9,12}` | `+420 601 234 567` |
| `TelPattern::International` | `\+[1-9][0-9\s\-]{6,18}` | `+1 555 000 0000` |

---

## Url

A convenience wrapper around `<x-pajak-form::input>` with `type="url"`.

```blade
<x-pajak-form::url name="website" label="Website" placeholder="https://example.com" />
```

Accepts all the same props as [Input](#input) except `type`. Defaults `autocomplete` to `'url'`.

---

## Hidden

A hidden input for passing data with a form without rendering any UI.

```blade
<x-pajak-form::hidden name="token" value="abc123" />
<x-pajak-form::hidden name="_redirect" :value="route('dashboard')" />
```

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | `string` | — | `name` attribute |
| `value` | `mixed` | `null` | Value submitted with the form |

---

## Textarea

A resizable multi-line text input with an optional label.

```blade
<x-pajak-form::textarea name="bio" label="Bio" placeholder="Tell us about yourself" rows="6" />

{{-- Without label --}}
<x-pajak-form::textarea name="notes" placeholder="Notes…" />

{{-- With inline error --}}
<x-pajak-form::textarea name="message" label="Message" :error="$errors->first('message')" />
```

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | `string` | — | `name` attribute |
| `label` | `string\|null` | `null` | Label text shown above the textarea. When `null`, no label is rendered |
| `placeholder` | `string\|null` | `null` | Placeholder text |
| `value` | `mixed` | `null` | Pre-filled value |
| `state` | `string` | `'default'` | `default` \| `error` \| `success` |
| `disabled` | `bool` | `false` | Disables the textarea |
| `rows` | `int` | `4` | Number of visible rows |
| `id` | `string\|null` | `null` | Overrides the default `id` (falls back to `name`) |
| `error` | `string\|null` | `null` | Error message shown below the textarea; automatically sets `state` to `error` |

---

## Select

A custom-styled select backed by a native `<select>`. Requires JS to upgrade the custom trigger and dropdown. Supports single select, searchable single select, option groups, per-option metadata, and multiselect with dismissible chips.

### Single select

Pass options via the `:options` prop (a `key => label` array or any iterable), or write `<option>` tags directly in the slot. Both can be combined — prop options render first, slot options append after.

```blade
{{-- Via :options prop --}}
<x-pajak-form::select name="country" label="Country" placeholder="Choose a country" :options="$countries" />

{{-- Pre-selected via :value --}}
<x-pajak-form::select name="country" label="Country" :options="$countries" value="sk" />

{{-- Grouped options (renders <optgroup>) --}}
<x-pajak-form::select name="country" label="Country" placeholder="Choose a country" :options="[
    'Europe' => ['sk' => 'Slovakia', 'cz' => 'Czech Republic', 'de' => 'Germany'],
    'Asia'   => ['jp' => 'Japan', 'cn' => 'China'],
]" />

{{-- Via slot (raw HTML) --}}
<x-pajak-form::select name="country" label="Country" placeholder="Choose a country">
    <option value="sk">Slovakia</option>
    <option value="cz">Czech Republic</option>
    <option value="de">Germany</option>
</x-pajak-form::select>

{{-- With inline error --}}
<x-pajak-form::select name="tier" label="Plan" :error="$errors->first('tier')" placeholder="Select tier">
    <option value="free">Free</option>
    <option value="pro">Pro</option>
</x-pajak-form::select>
```

### Searchable

Add `:searchable="true"` to render a search input at the top of the dropdown.

```blade
<x-pajak-form::select name="country" label="Country" placeholder="Choose a country" :searchable="true">
    <option value="sk">Slovakia</option>
    <option value="cz">Czech Republic</option>
    <option value="de">Germany</option>
    <option value="at">Austria</option>
</x-pajak-form::select>
```

### Option groups

```blade
<x-pajak-form::select name="source" label="Income source" placeholder="Select income source" :searchable="true">
    <optgroup label="Recent">
        <option value="acme" data-meta="PIT-11">Acme Sp. z o.o.</option>
        <option value="globex" data-meta="PIT-11">Globex Sp. z o.o.</option>
    </optgroup>
    <optgroup label="Other sources">
        <option value="freelance" data-meta="PIT-36">Freelance / B2B</option>
        <option value="rental" data-meta="PIT-28">Rental income</option>
    </optgroup>
</x-pajak-form::select>
```

The `data-meta` attribute on an `<option>` renders a secondary label aligned to the right of the option row.

### Multiselect

```blade
<x-pajak-form::select
    name="deductions"
    label="Deductions"
    placeholder="Add deduction…"
    :multiple="true"
    :options="$deductions"
    :value="['internet', 'charity']"
/>
```

> The `name` attribute automatically gets `[]` appended in multi mode for correct PHP array submission.

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | `string` | — | `name` attribute (`[]` appended automatically in multi mode) |
| `label` | `string\|null` | `null` | Label text shown above the select. When `null`, no label is rendered |
| `options` | `iterable` | `[]` | Flat `key => label` or grouped `group => [key => label]` iterable rendered as `<option>`/`<optgroup>` elements |
| `value` | `mixed` | `null` | Pre-selected value (string) or array of values for multi-select |
| `placeholder` | `string\|null` | `'Select option'` | Placeholder shown when nothing is selected |
| `searchable` | `bool` | `false` | Adds an inline search input to filter options (single mode only) |
| `search-placeholder` | `string\|null` | `'Search…'` | Placeholder for the search input |
| `multiple` | `bool` | `false` | Enables multiselect with chip UI |
| `state` | `string` | `'default'` | `default` \| `error` \| `success` |
| `disabled` | `bool` | `false` | Disables the select |
| `id` | `string\|null` | `null` | Overrides the default `id` (falls back to `name`) |
| `error` | `string\|null` | `null` | Error message shown below the select; automatically sets `state` to `error` |

**Slot:** `$slot` — `<option>` and `<optgroup>` elements. Options accept a `data-meta` attribute for secondary label text.

### Keyboard navigation

The trigger follows the ARIA combobox pattern. The dropdown options support full keyboard navigation.

| Key | Action |
|-----|--------|
| `Enter` / `Space` | Open / close the dropdown from the trigger |
| `Escape` | Close the dropdown and return focus to the trigger |
| `↓` (on trigger or search input) | Move focus into the options list |
| `↑` / `↓` (in dropdown) | Move focus between options |
| `Home` / `End` | Jump to first / last option |
| `Enter` (on focused option) | Select the option |
| `Backspace` (multi, empty input) | Remove the last chip; announces removal to screen readers |

In multiselect, chip removal (via the × button or Backspace) is announced to screen readers via a live region.

### JS initialisation

```ts
import { PajakSelect } from 'vendor/pajak/ui/js/form/select';

PajakSelect.initAll();

const instance = PajakSelect.init(document.querySelector('[data-pajak-select]'));

instance.destroy();
```

---

## Toggle

An accessible on/off switch (`role="switch"`). Emits a `pajak:toggle` custom event on change.

```blade
<x-pajak-form::toggle name="notifications" label="Enable notifications" />

{{-- Pre-checked --}}
<x-pajak-form::toggle name="darkmode" label="Dark mode" checked />

{{-- Sizes: sm | md (default) | lg --}}
<x-pajak-form::toggle name="beta" label="Beta features" size="sm" />

{{-- Disabled --}}
<x-pajak-form::toggle name="locked" label="Locked" checked disabled />
```

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | `string` | — | `name` attribute |
| `checked` | `bool` | `false` | Initial checked state |
| `disabled` | `bool` | `false` | Disables the toggle |
| `size` | `string` | `'md'` | `sm` \| `md` \| `lg` |
| `label` | `string\|null` | `null` | Label text shown beside the toggle |
| `id` | `string\|null` | `null` | Overrides the default `id` (falls back to `name`) |
| `error` | `string\|null` | `null` | Error message shown below the toggle |

**Slot:** `$slot` — additional content rendered inside the `<label>`.

### JS initialisation

```ts
import { PajakToggle } from 'vendor/pajak/ui/js/form/toggle';

PajakToggle.initAll();

const instance = PajakToggle.init(document.querySelector('.pajak-toggle'));

console.log(instance.checked); // boolean
instance.checked = true;

instance.destroy();
```

**Event:** `pajak:toggle` bubbles from the `<label>` element with `detail: { checked: boolean }`.

---

## Checkbox

A styled checkbox with a required label, optional description, and indeterminate state support.

```blade
<x-pajak-form::checkbox name="agree" label="I accept the terms" />

{{-- Pre-checked --}}
<x-pajak-form::checkbox name="remember" label="Remember me" checked />

{{-- With description --}}
<x-pajak-form::checkbox
    name="marketing"
    label="Marketing emails"
    description="Occasional product updates and tips."
/>

{{-- Custom value --}}
<x-pajak-form::checkbox name="roles[]" value="admin" label="Admin" />
```

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | `string` | — | `name` attribute |
| `value` | `mixed` | — | Value submitted when checked (required) |
| `checked` | `bool` | `false` | Initial checked state |
| `disabled` | `bool` | `false` | Disables the checkbox |
| `label` | `string` | — | Label text (required) |
| `description` | `string\|null` | `null` | Secondary description text below the label |
| `id` | `string\|null` | `null` | Overrides the default `id` (falls back to `name`) |
| `error` | `string\|null` | `null` | Error message shown below the checkbox |

**Slot:** `$slot` — additional content rendered inside the `<label>`.

### JS initialisation

```ts
import { PajakCheckbox } from 'vendor/pajak/ui/js/form/checkbox';

PajakCheckbox.initAll();

const instance = PajakCheckbox.init(document.querySelector('.pajak-checkbox'));

instance.indeterminate = true;
instance.checked = false;

instance.destroy();
```

**Event:** `pajak:checkbox` bubbles from the `<label>` element with `detail: { checked: boolean, indeterminate: boolean }`.

---

## Radio

A styled radio button with optional label and description.

```blade
<x-pajak-form::radio name="plan" value="free" label="Free" checked />
<x-pajak-form::radio name="plan" value="pro" label="Pro" />

{{-- With description --}}
<x-pajak-form::radio
    name="plan"
    value="enterprise"
    label="Enterprise"
    description="Custom pricing and SLA."
/>
```

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | `string` | — | `name` attribute (same for all radios in a group) |
| `value` | `mixed` | — | Submitted value (required) |
| `checked` | `bool` | `false` | Initial checked state |
| `disabled` | `bool` | `false` | Disables the radio |
| `label` | `string\|null` | `null` | Label text |
| `description` | `string\|null` | `null` | Secondary description text |
| `id` | `string\|null` | `null` | Overrides the default `id` (falls back to `name_value`) |
| `error` | `string\|null` | `null` | Error message shown below the radio |

**Slot:** `$slot` — additional content rendered inside the `<label>`.

---

## Radio Card

A card-style radio button with a label and optional hint. The checked state is driven by the native `:checked` HTML attribute — no JS required.

```blade
<x-pajak-form::radio-card name="plan" value="free" label="Free" hint="Up to 3 projects" checked />
<x-pajak-form::radio-card name="plan" value="pro" label="Pro" hint="Unlimited projects" />
```

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | `string` | — | `name` attribute |
| `value` | `mixed` | — | Submitted value (required) |
| `checked` | `bool` | `false` | Initial checked state |
| `disabled` | `bool` | `false` | Disables the radio card |
| `label` | `string\|null` | `null` | Primary label text |
| `hint` | `string\|null` | `null` | Secondary hint text |
| `id` | `string\|null` | `null` | Overrides the default `id` (falls back to `name_value`) |
| `error` | `string\|null` | `null` | Error message shown below the radio card |

**Slot:** `$slot` — additional content rendered inside the card content area.

---

## Repeater

A dynamic field-group component that lets users add and remove rows of form fields at runtime.

Write field components inside the slot using plain field-key names — the repeater automatically composes `name`, `id`, and `for` attributes into array notation for both server-rendered rows and dynamically added rows.

### Simple repeater (one field per row)

```blade
<x-pajak-form::repeater name="emails" label="Email addresses" :min="1" :max="5">
    <x-pajak-form::email name="address" label="Email" />
</x-pajak-form::repeater>
```

Renders as `name="emails[0][address]"`, `id="emails_0_address"` for the first row.

### Complex repeater (multiple fields per row)

```blade
<x-pajak-form::repeater name="items" label="Line items" :count="2">
    <x-pajak-form::input name="description" label="Description" placeholder="Item description" />
    <x-pajak-form::number name="qty" label="Qty" placeholder="1" />
    <x-pajak-form::input name="price" label="Price" placeholder="0.00" />
</x-pajak-form::repeater>
```

### Auto-binding rules

The repeater rewrites attributes in its slot at both render time (Blade) and when rows are cloned (JS):

| Original attribute | Becomes |
|--------------------|---------|
| `name="field"` | `name="repeaterName[index][field]"` |
| `id="field"` | `id="repeaterName_index_field"` |
| `for="field"` | `for="repeaterName_index_field"` |

Auto-binding applies when the attribute value contains no `[` character. To bypass auto-binding — for example when nesting repeaters or supplying a fully-qualified name — write the full value explicitly:

```blade
<x-pajak-form::input name="links[0][label]" label="Label" />
```

### Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | `string` | required | Array key prefix used as the repeater namespace |
| `label` | `string\|null` | `null` | Optional section heading rendered above the rows |
| `min` | `int` | `0` | Minimum rows — remove button is disabled when row count equals `min` |
| `max` | `int\|null` | `null` | Maximum rows — add button is hidden when row count equals `max` |
| `count` | `int` | `1` | Number of empty rows pre-rendered on page load |
| `add-label` | `string\|null` | `null` | Label for the add button. Falls back to the `pajak::ui.form.repeater.add` translation |
| `remove-label` | `string\|null` | `null` | Label and `aria-label` for per-row remove buttons. Falls back to `pajak::ui.form.repeater.remove` |

### JS API

```ts
import { PajakRepeater } from 'vendor/pajak/ui/js/form/repeater';

const instance = PajakRepeater.init(document.querySelector('[data-pajak-repeater]'));

PajakRepeater.initAll(document.getElementById('my-section'));
```

| Member | Description |
|--------|-------------|
| `instance.rowCount` | Current number of rows |
| `instance.add()` | Programmatically add a row (respects `max`) |
| `instance.destroy()` | Remove event listeners and de-register the instance |

### Events

| Event | Fired when |
|-------|-----------|
| `pajak:repeater:add` | A row is added |
| `pajak:repeater:remove` | A row is removed |

### Accessibility

Row additions and removals are announced to screen readers via a `role="status"` live region injected into the repeater wrapper. The announcement text comes from the `pajak::ui.form.repeater.row_added` / `row_removed` translation keys.

---

## Slider

A draggable range input with a single thumb or a two-thumb range variant. Submits its value via a hidden `<input>`.

### Single slider

```blade
<x-pajak-form::slider
    name="years"
    :min="0"
    :max="30"
    :step="1"
    :value="12"
    label="Years of mortgage"
    suffix="yrs"
/>
```

### Range slider (two thumbs)

```blade
<x-pajak-form::slider
    name="amount"
    :min="0"
    :max="50000"
    :step="500"
    :value="5000"
    :value-max="35000"
    :range="true"
    label="Invoice amount"
    suffix="zł"
/>
```

Range sliders submit two hidden inputs: `name[min]` and `name[max]`.

### Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | `string` | — | Form field name (required) |
| `min` | `float` | `0` | Minimum value |
| `max` | `float` | `100` | Maximum value |
| `step` | `float` | `1` | Snap interval |
| `value` | `float` | `0` | Initial value (or range min when `range=true`) |
| `value-max` | `float\|null` | `null` | Initial range max (defaults to `max`; only used when `range=true`) |
| `range` | `bool` | `false` | Render two thumbs for a min/max range |
| `disabled` | `bool` | `false` | Disable interaction |
| `show-bubble` | `bool` | `false` | Always show the value bubble above the thumb |
| `label` | `string\|null` | `null` | Label shown above the track |
| `suffix` | `string\|null` | `null` | Unit appended to bubble and event values (e.g. `%`, `zł`) |
| `id` | `string\|null` | `null` | Override element id (defaults to `name`) |
| `error` | `string\|null` | `null` | Validation error message |

### JS API

```ts
import { PajakSlider } from 'vendor/pajak/ui/js/form/slider';

// Upgrade a single track-wrap element
const instance = PajakSlider.init(el);

// Upgrade all sliders in the document (or a subtree)
PajakSlider.initAll();
PajakSlider.initAll(container);
```

#### Single slider instance

| Member | Description |
|--------|-------------|
| `instance.value` | Get or set the current value |
| `instance.destroy()` | Remove listeners and de-register |

#### Range instance

| Member | Description |
|--------|-------------|
| `instance.min` | Get or set the current minimum value |
| `instance.max` | Get or set the current maximum value |
| `instance.destroy()` | Remove listeners and de-register |

### Events

Both variants dispatch `pajak:slider` on the track-wrap element (bubbles).

| Event | `detail` | Fired when |
|-------|----------|-----------|
| `pajak:slider` | `{ value: number }` | Single slider value changes |
| `pajak:slider` | `{ min: number, max: number }` | Range slider values change |

---

## File

A styled `<input type="file">`. Requires JS — the filename updates via `PajakFile` when the user selects a file.

```blade
<x-pajak-form::file name="attachment" label="Attachment" />

{{-- With accept filter and custom placeholder --}}
<x-pajak-form::file
    name="attachment"
    label="Attachment"
    accept=".pdf,image/*"
    placeholder="Select a PDF or image…"
/>

{{-- Error state --}}
<x-pajak-form::file name="attachment" label="Attachment" :error="$errors->first('attachment')" />
```

### Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | `string` | — | Input name |
| `label` | `?string` | `null` | Label text above the input |
| `state` | `State` | `State::Default` | Explicit state (`State::Error`, `State::Success`) |
| `disabled` | `bool` | `false` | Disables the input |
| `id` | `?string` | `null` | Overrides the HTML `id` (defaults to `name`) |
| `error` | `?string` | `null` | Error message — sets state to `State::Error` automatically |
| `accept` | `?string` | `null` | `accept` attribute passed to the native input |
| `placeholder` | `?string` | `null` | Text shown before a file is chosen; defaults to the `pajak::ui.form.file.placeholder` translation |

### JS initialisation

```ts
import { PajakFile } from 'vendor/pajak/ui/js/form/form';

PajakFile.initAll();                       // initialise all on page
const instance = PajakFile.init(labelEl); // initialise one
instance.destroy();
```

The pre-built bundle initialises `PajakFile` automatically — no manual call required.

---

## Dropzone

A drag-and-drop upload zone that shows a file list below it. Supports single and multiple file modes. Works with standard HTML form submission — no async upload.

### Pre-populated files (edit forms)

Pass existing files via the `:files` prop as an array or `Collection` of `UploadedFile` DTOs:

```php
use Pajak\Ui\Common\Dto\UploadedFile;

$files = [
    new UploadedFile(id: 42, name: 'contract.pdf', size: 214_000),
    new UploadedFile(id: 43, name: 'invoice.pdf', size: 198_000),
];
```

```blade
<x-pajak-form::dropzone name="documents" :files="$files" />
```

When the user removes a pre-populated file, a hidden `<input name="documents_delete[]" value="{id}">` is added so your controller can handle deletion. Kept files stay as `<input name="documents_existing[]" value="{id}">`.

### Usage

```blade
{{-- Multiple files (default) --}}
<x-pajak-form::dropzone name="documents" label="Documents" accept=".pdf,image/*" />

{{-- Single file --}}
<x-pajak-form::dropzone name="contract" label="Contract" :multiple="false" />

{{-- Edit form with existing files --}}
<x-pajak-form::dropzone
    name="documents"
    label="Documents"
    :files="$existingFiles"
    :error="$errors->first('documents')"
/>
```

### Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | `string` | — | Input name (files submitted as `name[]`) |
| `multiple` | `bool` | `true` | Allow multiple files |
| `files` | `iterable<UploadedFile>` | `[]` | Pre-populated existing files |
| `label` | `?string` | `null` | Label text |
| `state` | `State` | `State::Default` | Explicit state |
| `disabled` | `bool` | `false` | Disables drag-and-drop and all remove buttons |
| `id` | `?string` | `null` | Overrides the HTML `id` |
| `error` | `?string` | `null` | Error message |
| `accept` | `?string` | `null` | `accept` attribute on the hidden file input |

### JS API

```ts
import { PajakDropzone } from 'vendor/pajak/ui/js/form/form';

PajakDropzone.initAll();                          // initialise all on page
const instance = PajakDropzone.init(wrapEl);     // initialise one
instance.destroy();                               // tear down
```

### Events

| Event | `detail` | Fired when |
|-------|----------|-----------|
| `pajak:dropzone:add` | `{ file: File }` | A new file is added to the list |
| `pajak:dropzone:remove` | `{ id: string, type: 'new' \| 'existing' }` | A file is removed |

Both events bubble from the dropzone wrapper element.

---

## Avatar

A circular image upload with an initials fallback. Suitable for profile photos. Standard form submission — no async upload.

### Usage

```blade
{{-- New avatar --}}
<x-pajak-form::avatar name="avatar" label="Profile photo" initials="JK" />

{{-- Edit form with existing image --}}
<x-pajak-form::avatar
    name="avatar"
    label="Profile photo"
    initials="JK"
    :src="$user->avatar_url"
    :error="$errors->first('avatar')"
/>
```

When the user removes the existing image, a hidden `<input name="avatar_delete" value="1">` is added. As long as the existing image is kept, `<input name="avatar_existing" value="1">` remains present.

### Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | `string` | — | Input name |
| `initials` | `?string` | `null` | 1–2 character fallback shown when no image |
| `src` | `?string` | `null` | Current image URL (server-side) |
| `label` | `?string` | `null` | Label text |
| `state` | `State` | `State::Default` | Explicit state |
| `disabled` | `bool` | `false` | Disables the circle click and upload button |
| `id` | `?string` | `null` | Overrides the HTML `id` |
| `error` | `?string` | `null` | Error message |
| `accept` | `?string` | `null` | `accept` attribute on the hidden file input. Defaults to `'image/*'` when `null`; pass an empty string to remove the restriction |

### JS API

```ts
import { PajakAvatar } from 'vendor/pajak/ui/js/form/form';

PajakAvatar.initAll();
const instance = PajakAvatar.init(wrapEl);
instance.hasImage;    // boolean — true when an image (new or existing) is shown
instance.destroy();
```

### Events

| Event | `detail` | Fired when |
|-------|----------|-----------|
| `pajak:avatar:change` | `{ file: File \| null }` | A new file is selected (`file` is the `File` object) or the image is cleared (`null`) |

---

## Image Grid

A thumbnail grid for multiple image uploads. Images appear immediately on selection. Works with standard form submission.

### Pre-populated images (edit forms)

```php
use Pajak\Ui\Common\Dto\UploadedFile;

$images = [
    new UploadedFile(id: 10, name: 'receipt_1.jpg', url: '/storage/receipts/1.jpg'),
    new UploadedFile(id: 11, name: 'receipt_2.jpg', url: '/storage/receipts/2.jpg'),
];
```

```blade
<x-pajak-form::image-grid name="receipts" :images="$images" />
```

Removed images get a `<input name="receipts_delete[]" value="{id}">` hidden input. Kept ones stay as `<input name="receipts_existing[]" value="{id}">`.

### Usage

```blade
{{-- Empty grid --}}
<x-pajak-form::image-grid name="photos" label="Photos" />

{{-- Edit form --}}
<x-pajak-form::image-grid
    name="receipts"
    label="Receipt photos"
    :images="$existingImages"
    :error="$errors->first('receipts')"
/>
```

### Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | `string` | — | Input name (new files submitted as `name[]`) |
| `images` | `iterable<UploadedFile>` | `[]` | Pre-populated existing images (`url` required) |
| `label` | `?string` | `null` | Label text |
| `state` | `State` | `State::Default` | Explicit state |
| `disabled` | `bool` | `false` | Hides the add tile and all delete buttons |
| `id` | `?string` | `null` | Overrides the HTML `id` |
| `error` | `?string` | `null` | Error message |
| `accept` | `?string` | `'image/*'` | `accept` attribute on the hidden file input |

### JS API

```ts
import { PajakImageGrid } from 'vendor/pajak/ui/js/form/form';

PajakImageGrid.initAll();
const instance = PajakImageGrid.init(wrapEl);
instance.destroy();
```

### Events

| Event | `detail` | Fired when |
|-------|----------|-----------|
| `pajak:imagegrid:add` | `{ files: File[] }` | New images are added |
| `pajak:imagegrid:remove` | `{ id: string, type: 'new' \| 'existing' }` | An image is removed |

---

## Request handling

Use the `HasFileUploads` trait in your `FormRequest` to extract uploaded files, deletion IDs, and kept IDs cleanly.

```php
use Pajak\Ui\Form\Concerns\HasFileUploads;

class StoreDocumentsRequest extends FormRequest
{
    use HasFileUploads;
}
```

### Available methods

| Method | Returns | Use with |
|--------|---------|---------|
| `uploadedFiles(string $name)` | `Collection<UploadedFile>` | Dropzone, image-grid |
| `deletedFileIds(string $name)` | `Collection<string\|int>` | Dropzone, image-grid |
| `keptFileIds(string $name)` | `Collection<string\|int>` | Dropzone, image-grid |
| `uploadedAvatar(string $name)` | `?UploadedFile` | Avatar |
| `avatarDeleted(string $name)` | `bool` | Avatar |

### Example — Dropzone

```php
// Controller
public function update(StoreDocumentsRequest $request, Project $project): RedirectResponse
{
    foreach ($request->uploadedFiles('documents') as $file) {
        $project->addDocument($file);
    }

    foreach ($request->deletedFileIds('documents') as $id) {
        $project->removeDocument((int) $id);
    }

    return redirect()->back();
}
```

### Example — Avatar

```php
public function update(UpdateProfileRequest $request, User $user): RedirectResponse
{
    if ($request->avatarDeleted('avatar')) {
        $user->deleteAvatar();
    } elseif ($file = $request->uploadedAvatar('avatar')) {
        $user->updateAvatar($file);
    }

    return redirect()->back();
}
```

### Example — Image Grid

```php
public function update(UpdateReceiptsRequest $request, Expense $expense): RedirectResponse
{
    foreach ($request->deletedFileIds('receipts') as $id) {
        $expense->removeReceipt((int) $id);
    }

    foreach ($request->uploadedFiles('receipts') as $file) {
        $expense->addReceipt($file);
    }

    return redirect()->back();
}
```
