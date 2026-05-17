<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\Method;
use Pajak\Ui\Common\Enums\Size;
use Pajak\Ui\Form\Enums\FormLayout;

final class Form extends Component
{
    public function __construct(
        public readonly string $action,
        public readonly Method $method = Method::Post,
        public readonly ?string $submitText = null,
        public readonly Size $submitSize = Size::Md,
        public readonly FormLayout $layout = FormLayout::Stacked,
        public readonly ?string $redirect = null,
        public readonly bool $hideSubmit = false,
    ) {
    }

    public function resolvedSubmitText(): string
    {
        return $this->submitText ?? __('pajak::ui.form.submit');
    }

    public function render(): View
    {
        return view('pajak::form.form');
    }
}
