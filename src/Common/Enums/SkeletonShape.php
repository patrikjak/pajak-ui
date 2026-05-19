<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums;

enum SkeletonShape: string
{
    case Line = 'line';
    case LineSm = 'line-sm';
    case LineLg = 'line-lg';
    case Circle = 'circle';
    case Rect = 'rect';
    case Pill = 'pill';
}
