<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\MessageType;

final class FieldMessage extends Component
{
    public function __construct(public readonly MessageType $type = MessageType::Error)
    {
    }

    public function render(): View
    {
        return view('pajak::form.field-message');
    }
}
