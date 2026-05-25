<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Enums\Email\EmailAccent;

final class EmailInfocardRow extends Component
{
    public function __construct(
        public readonly string $label,
        public readonly string $value,
        public readonly ?EmailAccent $accent = null,
    ) {
    }

    public function render(): View
    {
        return view('pajak::common.email-infocard-row');
    }
}
