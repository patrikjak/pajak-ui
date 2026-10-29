<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\Size;

final class Otp extends Component
{
    public function __construct(
        public readonly string $name,
        public readonly int $length = 6,
        public readonly ?string $label = null,
        public readonly ?string $error = null,
        public readonly bool $autofocus = false,
        public readonly bool $disabled = false,
        public readonly Size $size = Size::Md,
        public readonly bool $autosubmit = false,
        public readonly bool $numeric = true,
        public readonly ?string $id = null,
    ) {
    }

    public function inputId(): string
    {
        return $this->id ?? $this->name;
    }

    public function labelId(): string
    {
        return sprintf('%s-label', $this->inputId());
    }

    public function boxId(int $position): string
    {
        return sprintf('%s-digit-%d', $this->inputId(), $position);
    }

    public function labelText(): string
    {
        return $this->label ?? __('pajak::ui.form.otp.label');
    }

    public function boxLabel(int $position): string
    {
        return __('pajak::ui.form.otp.digit', ['number' => $position, 'total' => $this->boxCount()]);
    }

    public function boxCount(): int
    {
        return max(1, $this->length);
    }

    /**
     * @return array<int, int>
     */
    public function positions(): array
    {
        return range(1, $this->boxCount());
    }

    public function hasError(): bool
    {
        return $this->error !== null && $this->error !== '';
    }

    public function render(): View
    {
        return view('pajak::form.otp');
    }
}
