<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Columns\Concerns;

use BackedEnum;
use Closure;

trait HasDisplayValue
{
    /**
     * @var array<int|string, string>|Closure|null
     */
    protected array|Closure|null $labelMap = null;

    protected ?Closure $valueResolver = null;

    /**
     * @param array<int|string, string>|Closure $labels
     */
    public function labels(array|Closure $labels): static
    {
        $this->labelMap = $labels;

        return $this;
    }

    public function value(Closure $resolver): static
    {
        $this->valueResolver = $resolver;

        return $this;
    }

    /**
     * @param array<string, mixed>|object $row
     */
    public function resolveValue(array|object $row): mixed
    {
        if ($this->valueResolver !== null) {
            return ($this->valueResolver)($row);
        }

        return is_array($row) ? ($row[$this->key()] ?? '') : ($row->{$this->key()} ?? '');
    }

    /**
     * @param array<string, mixed>|object $row
     */
    public function labelFor(mixed $value, array|object $row = []): string
    {
        if ($this->labelMap instanceof Closure) {
            return (string) ($this->labelMap)($value, $row);
        }

        return $this->labelMap[self::mapKey($value)] ?? self::mapKey($value);
    }

    protected static function mapKey(mixed $value): string
    {
        return match (true) {
            is_bool($value) => $value ? '1' : '0',
            $value instanceof BackedEnum => (string) $value->value,
            default => (string) $value,
        };
    }
}
