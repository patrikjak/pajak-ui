<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Columns\Concerns;

trait HasColorMap
{
    /**
     * @var array<string, string>
     */
    protected array $colorMap = [];

    /**
     * @param array<string, string> $colors
     */
    public function colors(array $colors): static
    {
        $this->colorMap = $colors;

        return $this;
    }

    public function colorFor(mixed $value): ?string
    {
        return $this->colorMap[(string) $value] ?? null;
    }
}
