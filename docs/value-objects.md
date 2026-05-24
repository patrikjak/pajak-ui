# Value Objects

Value objects live in `src/Common/ValueObject/` and provide behaviour on top of primitive data.

---

## Money

Represents a monetary amount stored in minor units (e.g. cents).

### Usage

```php
use Pajak\Ui\Common\ValueObject\Money;

$price = new Money(1999);          // 19.99 €
$price = new Money(1999, '$', 100, true);  // $ 19.99
```

### Constructor parameters

| Parameter | Type | Default | Description |
|---|---|---|---|
| `$minorUnits` | `int` | — | Amount in the smallest currency unit (e.g. cents) |
| `$currency` | `string` | `'€'` | Currency symbol displayed next to the formatted value |
| `$multiplier` | `int` | `100` | Number of minor units per major unit; determines decimal places via `log10($multiplier)` |
| `$currencyBefore` | `bool` | `false` | When `true`, the symbol appears before the number (`$ 19.99`); otherwise after (`19.99 €`) |

### Computed properties

| Property | Type | Description |
|---|---|---|
| `$decimals` | `int` | Decimal places, derived from `log10($multiplier)` |

### Methods

| Method | Return | Description |
|---|---|---|
| `toFloat()` | `float` | Converts minor units to a float (`minorUnits / multiplier`) |
| `__toString()` | `string` | Formats with symbol, sign, and `number_format`; uses `−` (minus sign U+2212) for negative values |

### Examples

```php
(string) new Money(1999);               // "19.99 €"
(string) new Money(-500);               // "−5.00 €"
(string) new Money(1999, '$', 100, true); // "$ 19.99"
(string) new Money(10000, '¥', 1);      // "10000 ¥"  (0 decimal places)
```

### Usage in AmountColumn

`Money` is the expected value type for `AmountColumn`:

```php
TextColumn::make('price')
    ->label('Price')
// or
AmountColumn::make('price')  // renders via (string) cast → Money::__toString()
```
