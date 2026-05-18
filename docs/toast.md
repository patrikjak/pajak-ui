# Toast

Transient notification overlay that auto-dismisses after a configurable duration.

> All components support dark mode — see [dark-mode.md](dark-mode.md).

## Assets

### Pre-built (no build step required)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/main.js') }}"></script>
```

Or use the toast-only bundles:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/toast-standalone.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/toast.js') }}"></script>
```

### Source import (recommended for production)

After publishing sources (`php artisan vendor:publish --tag=pajak-ui-sources`):

```scss
@use 'vendor/pajak/ui/css/toast/toast';
```

```ts
import { PajakToast } from 'vendor/pajak/ui/js/toast/toast';
```

---

## Server-side: `ToastData` DTO

Return a `ToastData` instance nested under a `toast` key in any `JsonResponse`. The JS automatically reads it after a successful or failed async form submission.

```php
use Pajak\Ui\Common\Dto\ToastData;
use Illuminate\Http\JsonResponse;

return new JsonResponse([
    'toast' => ToastData::success('Saved!', 'Changes have been applied.'),
]);
```

### Factory methods

| Method | `ToastType` | Use for |
|--------|------------|---------|
| `ToastData::success($title, $message)` | `Success` | Confirmed action |
| `ToastData::error($title, $message)` | `Error` | Failed action |
| `ToastData::warning($title, $message)` | `Warning` | Caution / partial success |
| `ToastData::info($title, $message)` | `Info` | Neutral information |

`$message` is optional in all factory methods.

### `ToastType` enum

```php
use Pajak\Ui\Common\Enums\ToastType;

ToastType::Success  // 'success'
ToastType::Error    // 'error'
ToastType::Warning  // 'warning'
ToastType::Info     // 'info'
```

---

## Form integration

Any form using `data-pajak-form` automatically shows a toast when the JSON response contains a `toast` key — no extra configuration needed.

```html
<form data-pajak-form action="/settings" method="POST">
    <!-- ... -->
</form>
```

To suppress the automatic toast on a specific form, add `data-no-toast`:

```html
<form data-pajak-form data-no-toast action="/search" method="GET">
    <!-- ... -->
</form>
```

---

## JS API

`PajakToast` is available globally via `window.Pajak.PajakToast` and as a named export from all bundles.

### `PajakToast.show(options)`

```ts
import { PajakToast } from 'vendor/pajak/ui/js/toast/toast';

PajakToast.show({
    type: 'success',     // 'success' | 'error' | 'warning' | 'info'
    title: 'Saved!',
    message: 'Optional description.',  // optional
    duration: 4000,      // ms before auto-dismiss, default 4000
});
```

Returns the toast `id` string (useful for manual dismissal).

### `PajakToast.dismiss(id)`

Immediately removes the toast with the given `id`.

### `PajakToast.dismissAll()`

Immediately removes all visible toasts.

