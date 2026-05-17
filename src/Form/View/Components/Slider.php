<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Slider extends Component
{
    public function __construct(
        public readonly string $name,
        public readonly float $min = 0,
        public readonly float $max = 100,
        public readonly float $step = 1,
        public readonly float $value = 0,
        public readonly bool $disabled = false,
        public readonly bool $range = false,
        public readonly ?float $valueMax = null,
        public readonly bool $showBubble = false,
        public readonly ?string $id = null,
        public readonly ?string $label = null,
        public readonly ?string $error = null,
        public readonly ?string $suffix = null,
    ) {
    }

    public function inputId(): string
    {
        return $this->id ?? $this->name;
    }

    public function resolvedValueMax(): float
    {
        return $this->valueMax ?? $this->max;
    }

    public function fillPercent(): float
    {
        return $this->fillMinPercent();
    }

    public function fillMinPercent(): float
    {
        if ($this->max === $this->min) {
            return 0;
        }

        return ($this->value - $this->min) / ($this->max - $this->min) * 100;
    }

    public function fillMaxPercent(): float
    {
        if ($this->max === $this->min) {
            return 100;
        }

        return ($this->resolvedValueMax() - $this->min) / ($this->max - $this->min) * 100;
    }

    public function render(): View
    {
        return view('pajak::form.slider');
    }
}
