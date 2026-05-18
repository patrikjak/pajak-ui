<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\Enums;

enum FormLayout: string
{
    case Stacked = 'stacked';
    case Inline = 'inline';
    case Sectioned = 'sectioned';
}
