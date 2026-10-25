<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\Size;
use Pajak\Ui\Common\Enums\State;

final class ColorPicker extends Component
{
    private const string HEX_PATTERN = '^#[0-9A-Fa-f]{6}$';

    /**
     * @param list<string> $swatches
     */
    public function __construct(
        public readonly string $name,
        public readonly ?string $label = null,
        public readonly ?string $value = null,
        public readonly ?string $placeholder = '#RRGGBB',
        public readonly Size $size = Size::Md,
        public readonly bool $disabled = false,
        public readonly ?string $id = null,
        public readonly ?string $error = null,
        public readonly array $swatches = [],
        public readonly bool $inline = false,
    ) {
    }

    public function hexPattern(): string
    {
        return self::HEX_PATTERN;
    }

    public function inputId(): string
    {
        return $this->id ?? $this->name;
    }

    public function resolvedState(): State
    {
        return filled($this->error) ? State::Error : State::Default;
    }

    public function hasValidValue(): bool
    {
        return $this->isHex($this->value);
    }

    public function swatchColor(): ?string
    {
        return $this->hasValidValue() ? strtoupper((string) $this->value) : null;
    }

    /**
     * @return list<string>
     */
    public function presets(): array
    {
        return array_values(array_map(
            static fn (string $color): string => strtoupper($color),
            array_filter($this->swatches, fn (mixed $color): bool => $this->isHex($color)),
        ));
    }

    public function render(): View
    {
        return view('pajak::form.color-picker');
    }

    private function isHex(mixed $color): bool
    {
        return is_string($color) && preg_match(sprintf('/%s/', self::HEX_PATTERN), $color) === 1;
    }
}
