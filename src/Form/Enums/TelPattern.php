<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\Enums;

enum TelPattern: string
{
    case Sk = '(\+421|0)[0-9\s]{9,12}';
    case Cz = '(\+420|0)[0-9\s]{9,12}';
    case International = '\+[1-9][0-9\s\-]{6,18}';

    public function placeholder(): string
    {
        return match ($this) {
            self::Sk => '+421 912 345 678',
            self::Cz => '+420 601 234 567',
            self::International => '+1 555 000 0000',
        };
    }
}
