# Table

A server-driven AJAX data table with a PHP builder API. Consumers define columns, filters, actions, and bulk actions entirely in PHP; Blade renders the output. All interactive state (search, sort, filters, pagination, column visibility) is managed in the browser without full-page reloads.

> All components support dark mode — see [dark-mode.md](dark-mode.md).

## Assets

### Pre-built (no build step required)

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/main.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/main.js') }}"></script>
```

Or use the table-only bundles:

```html
<link rel="stylesheet" href="{{ asset('vendor/pajak/ui/table-standalone.css') }}">
<script type="module" src="{{ asset('vendor/pajak/ui/table.js') }}"></script>
```

### Source import (recommended for production)

```bash
php artisan vendor:publish --tag=pajak-ui-sources
```

```scss
@use 'vendor/pajak/ui/css/table/table';
```

```ts
import { PajakTable } from 'vendor/pajak/ui/js/table/table';
PajakTable.initAll();
```

---

## Quick start

```php
// In your controller
use Pajak\Ui\Table\Table;
use Pajak\Ui\Table\Columns\TextColumn;
use Pajak\Ui\Table\Columns\StatusColumn;
use Pajak\Ui\Table\Columns\AmountColumn;
use Pajak\Ui\Table\Columns\DateColumn;
use Pajak\Ui\Table\Filters\TextFilter;
use Pajak\Ui\Table\Filters\SelectFilter;
use Pajak\Ui\Table\Actions\LinkAction;
use Pajak\Ui\Table\Actions\ConfirmAction;
use Pajak\Ui\Common\Enums\Method;
use Pajak\Ui\Table\Pagination\EloquentPaginator;

$table = Table::make('invoices')
    ->dataUrl(route('invoices.table'))
    ->columns([
        TextColumn::make('number')->label('Number')->sortable(),
        StatusColumn::make('status')->label('Status')->colors([
            'paid' => 'green',
            'pending' => 'yellow',
            'cancelled' => 'red',
        ]),
        AmountColumn::make('amount')->label('Amount')->sortable(),
        DateColumn::make('issued_at')->label('Date')->sortable(),
    ])
    ->filters([
        TextFilter::make('number')->label('Number'),
        SelectFilter::make('status')->label('Status')->options([
            'paid' => 'Paid',
            'pending' => 'Pending',
            'cancelled' => 'Cancelled',
        ]),
    ])
    ->actions([
        LinkAction::make('view')
            ->label('View')
            ->url(fn($row) => route('invoices.show', $row->id)),
        ConfirmAction::make('delete')
            ->label('Delete')
            ->url(fn($row) => route('invoices.destroy', $row->id))
            ->method(Method::Delete)
            ->danger()
            ->confirmTitle('Delete invoice?')
            ->confirmMessage('This will permanently remove the invoice.'),
    ])
    ->searchable();

$paginator = new EloquentPaginator(Invoice::paginate(15));
```

```blade
<x-pajak-table::table :table="$table" :paginator="$paginator" />
```

---

## AJAX endpoint

The table POSTs to `data-url` with a JSON body representing the current state. Your controller should return the updated `<tbody>` HTML (or a partial view).

```php
// routes/web.php
Route::post('invoices/table', [InvoiceController::class, 'tableData'])->name('invoices.table');

// InvoiceController.php
use Pajak\Ui\Table\Services\TableRequestParser;
use Pajak\Ui\Table\Services\TableFilters;

public function tableData(Request $request, TableRequestParser $parser): Response
{
    $tableRequest = $parser->parse($request);

    $query = Invoice::query();

    if ($tableRequest->search) {
        $query->where('number', 'like', "%{$tableRequest->search}%");
    }

    if ($tableRequest->sort) {
        $query->orderBy($tableRequest->sort->column, $tableRequest->sort->direction->value);
    }

    $paginator = new EloquentPaginator($query->paginate(15, page: $tableRequest->page));

    return response(view('invoices._table-body', compact('table', 'paginator')));
}
```

---

## Builder API

### `Table::make(string $name)`

Creates a table builder. `$name` is used as a unique identifier for sessionStorage state and HTML `id`.

| Method | Description |
|--------|-------------|
| `->columns(array $columns)` | Set column definitions |
| `->filters(array $filters)` | Set filter definitions |
| `->actions(array $actions)` | Set per-row actions |
| `->bulkActions(array $actions)` | Set bulk actions (shown in bulk bar) |
| `->dataUrl(string $url)` | AJAX endpoint for table interactions |
| `->heading(string $heading)` | Optional heading shown above the toolbar |
| `->searchable()` | Show the search input |
| `->selectable()` | Enable row checkboxes (auto-enabled when `bulkActions` are set) |
| `->columnVisibility()` | Show the column visibility toggle button in the toolbar |
| `->perPageOptions(array $options)` | Show a per-page selector with the given options (e.g. `[10, 25, 50]`) |

---

## Column types

All columns are created with `ColumnType::make(string $key)` where `$key` is the row attribute to read.

### Shared fluent methods

| Method | Description |
|--------|-------------|
| `->label(string $label)` | Column header label |
| `->sortable()` | Enable sort arrows |
| `->hidden()` | Hide column by default (user can reveal via column toggle) |

### TextColumn

Plain text cell. Reads `$row->key` or `$row['key']`.

```php
TextColumn::make('email')->label('Email')->sortable()
```

### StatusColumn

Colored rounded pill.

```php
StatusColumn::make('status')->label('Status')->colors([
    'active' => 'green',   // green | red | yellow | blue | gray
    'inactive' => 'gray',
])
```

### TwoLineColumn

Bold primary text + muted secondary text.

```php
TwoLineColumn::make('client')->label('Client')->secondary('nip')
```

`->secondary(string $key)` — row key for the secondary/muted line.

### BadgeColumn

Small uppercase chip with optional color.

```php
BadgeColumn::make('type')->label('Type')->colors(['invoice' => 'blue'])
```

### AvatarColumn

Circle avatar (image or initials) + name + optional subtitle.

```php
AvatarColumn::make('name')->label('Client')
    ->image('avatar_url')
    ->subtitle('email')
```

| Method | Description |
|--------|-------------|
| `->image(string $key)` | Row key for image URL (initials shown when null/empty) |
| `->subtitle(string $key)` | Row key for subtitle text |

### DateColumn

Formatted date using `Carbon::parse()`.

```php
DateColumn::make('issued_at')->label('Date')->format('d/m/Y')
```

| Method | Description |
|--------|-------------|
| `->format(string $format)` | PHP date format string (default: `d.m.Y`) |

### AmountColumn

Sign-aware monetary display. Expects a `Money` value object — currency symbol, placement, and decimals come from the VO itself.

```php
AmountColumn::make('amount')->label('Amount')->sortable()
```

Negative values are styled red. See [Money value object](#money-value-object).

---

## Filter types

All filters are created with `FilterType::make(string $key)` and support `->label(string $label)`.

### TextFilter

Text input with operator dropdown (Contains / Does not contain / Equals / Does not equal).

```php
TextFilter::make('number')->label('Invoice number')
```

### SelectFilter

Multi-select checkboxes.

```php
SelectFilter::make('status')->label('Status')->options([
    'paid' => 'Paid',
    'pending' => 'Pending',
])
```

`->options(array<string, string> $options)` — value → label map.

### DateFilter

From/to date pickers using `<x-pajak-calendar::date-picker range>`.

```php
DateFilter::make('issued_at')->label('Issue date')
```

### NumberFilter

Min/max numeric inputs.

```php
NumberFilter::make('amount')->label('Amount')
```

---

## Action types

### LinkAction

Renders as `<a href>`.

```php
LinkAction::make('view')
    ->label('View')
    ->url(fn($row) => route('invoices.show', $row->id))
    ->visibleIf(fn($row) => $row->isPublished())
```

### FormAction

Submits a hidden form (CSRF included). Appears in the overflow `…` menu by default.

```php
FormAction::make('archive')
    ->label('Archive')
    ->url(fn($row) => route('invoices.archive', $row->id))
    ->method(Method::Post)
```

### ConfirmAction

Opens a confirm dialog before submitting.

```php
ConfirmAction::make('delete')
    ->label('Delete')
    ->url(fn($row) => route('invoices.destroy', $row->id))
    ->method(Method::Delete)
    ->danger()
    ->confirmTitle('Delete invoice?')
    ->confirmMessage('This cannot be undone.')
    ->confirmButton('Yes, delete')
```

### ModalAction

Opens an existing `<x-pajak::modal>` by ID.

```php
ModalAction::make('edit')
    ->label('Edit')
    ->modalId(fn($row) => "edit-invoice-{$row->id}")
```

### Shared action fluent methods

| Method | Description |
|--------|-------------|
| `->label(string $label)` | Button label |
| `->danger()` | Red danger styling |
| `->visibleIf(Closure $fn)` | Conditional visibility per row (`fn($row): bool`) |
| `->inlineOnly()` | Show only as inline button (not in overflow menu) |
| `->overflowOnly()` | Show only in the `…` overflow menu |

---

## Pagination adapters

### EloquentPaginator

Wraps a Laravel `LengthAwarePaginator`.

```php
use Pajak\Ui\Table\Pagination\EloquentPaginator;

$paginator = new EloquentPaginator(Invoice::paginate(15));
```

### ArrayPaginator

Paginates a PHP array or `Collection`.

```php
use Pajak\Ui\Table\Pagination\ArrayPaginator;

$paginator = ArrayPaginator::fromArray($items, perPage: 15, page: 1);
$paginator = ArrayPaginator::fromCollection($collection, perPage: 15, page: 1);
```

---

## Money value object

`Pajak\Ui\Common\ValueObject\Money` represents a monetary value stored as minor units (e.g. cents).

```php
use Pajak\Ui\Common\ValueObject\Money;

$price = new Money(10050);                                              // 100.50 €
$price = new Money(10050, '$', 100, true);                             // $ 100.50
$price = new Money(-2550);                                              // −25.50 €
```

| Param | Type | Default | Description |
|-------|------|---------|-------------|
| `$minorUnits` | `int` | — | Amount in smallest currency unit |
| `$currency` | `string` | `'€'` | Currency symbol |
| `$multiplier` | `int` | `100` | Minor units per major unit |
| `$currencyBefore` | `bool` | `false` | Put symbol before the amount |

`__toString()` returns the formatted string. Negative values use `−` (minus sign, not hyphen).

---

## Server-side services

### `TableRequestParser`

Parses an incoming `Request` (JSON body or GET params) into a `TableRequest` DTO.

```php
use Pajak\Ui\Table\Services\TableRequestParser;

$tableRequest = (new TableRequestParser())->parse($request);
// $tableRequest->search, ->sort, ->filters, ->page, ->visibleColumns
```

### `TableFilters`

Applies filters to an in-memory `Collection` (useful when not using Eloquent).

```php
use Pajak\Ui\Table\Services\TableFilters;

$filtered = (new TableFilters())->apply($items, $tableRequest->filters);
```

---

## Extending with custom column/filter types

Implement the interface and return a custom view partial:

```php
use Pajak\Ui\Table\Contracts\TableColumn;

class RatingColumn implements TableColumn
{
    public function __construct(private string $columnKey) {}

    public function key(): string { return $this->columnKey; }
    public function getLabel(): string { return 'Rating'; }
    public function isSortable(): bool { return false; }
    public function isHidden(): bool { return false; }
    public function cellView(): string { return 'my-app::table.cells.rating'; }
}
```

```php
use Pajak\Ui\Table\Contracts\TableFilter;

class RelationFilter implements TableFilter
{
    public function key(): string { return 'relation'; }
    public function getLabel(): string { return 'Related to'; }
    public function editorPartial(): string { return 'my-app::table.filters.relation'; }
}
```

---

## JS API

```ts
import { PajakTable } from 'vendor/pajak/ui/js/table/table';

// Initialize all tables on the page
PajakTable.initAll();

// Or initialize a single wrapper element
PajakTable.initTable(document.querySelector('[data-pajak-table]'));
```

### Events

The table dispatches a custom event when a bulk action button is clicked:

```js
document.addEventListener('pajak:table:bulk-action', (event) => {
    const { action, ids, tableName } = event.detail;
    // action: string — the action key
    // ids: string[] — selected row IDs
    // tableName: string — table name
});
```

---

## Translations

All strings are in `lang/en/table.php` under the `pajak::table` namespace. Publish to override:

```bash
php artisan vendor:publish --tag=pajak-ui-translations
```
