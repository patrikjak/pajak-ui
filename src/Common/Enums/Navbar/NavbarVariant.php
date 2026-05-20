<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums\Navbar;

enum NavbarVariant: string
{
    case Standard = 'standard';
    case Underline = 'underline';
    case Dark = 'dark';
    case Split = 'split';
    case Stacked = 'stacked';
    case Mobile = 'mobile';
}
