<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Enums\ErrorPage;

enum ErrorPageCode: int
{
    case NotFound = 404;
    case ServerError = 500;
    case Forbidden = 403;
    case Unauthorized = 401;
    case ServiceUnavailable = 503;

    public function colorKey(): string
    {
        return match ($this) {
            self::NotFound, self::ServiceUnavailable => 'blue',
            self::ServerError => 'red',
            self::Forbidden => 'amber',
            self::Unauthorized => 'neutral',
        };
    }

    public function pillLabel(): string
    {
        return match ($this) {
            self::NotFound => 'Error 404',
            self::ServerError => 'Error 500',
            self::Forbidden => 'Error 403',
            self::Unauthorized => 'Error 401',
            self::ServiceUnavailable => 'Maintenance',
        };
    }

}
