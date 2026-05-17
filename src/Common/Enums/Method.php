<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums;

enum Method: string
{
    case Get = 'GET';
    case Post = 'POST';
    case Put = 'PUT';
    case Patch = 'PATCH';
    case Delete = 'DELETE';

    public function htmlMethod(): string
    {
        return match ($this) {
            self::Get, self::Post => $this->value,
            default => self::Post->value,
        };
    }

    public function needsSpoofing(): bool
    {
        return match ($this) {
            self::Put, self::Patch, self::Delete => true,
            default => false,
        };
    }
}
