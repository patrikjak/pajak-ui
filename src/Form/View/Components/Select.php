<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\State;
use Traversable;

final class Select extends Component
{
    /** @var array<int|string, string|array<int|string, string>> */
    public readonly array $options;

    /**
     * @param iterable<int|string, string|iterable<int|string, string>> $options
     */
    public function __construct(
        public readonly string $name,
        public readonly mixed $value = null,
        public readonly ?string $placeholder = null,
        public readonly bool $disabled = false,
        public readonly bool $multiple = false,
        public readonly bool $searchable = false,
        public readonly ?string $searchPlaceholder = null,
        iterable $options = [],
        public readonly State $state = State::Default,
        public readonly ?string $id = null,
        public readonly ?string $error = null,
        public readonly ?string $label = null,
    ) {
        $this->options = $options instanceof Traversable ? iterator_to_array($options) : (array) $options;
    }

    public function labelText(): ?string
    {
        return $this->label;
    }

    public function inputId(): string
    {
        return $this->id ?? $this->name;
    }

    public function resolvedState(): State
    {
        return $this->error !== null ? State::Error : $this->state;
    }

    public function resolvedPlaceholder(): string
    {
        return $this->placeholder ?? __('pajak::ui.form.select.placeholder');
    }

    public function resolvedSearchPlaceholder(): string
    {
        return $this->searchPlaceholder ?? __('pajak::ui.form.select.search_placeholder');
    }

    public function render(): View
    {
        return view('pajak::form.select');
    }
}
