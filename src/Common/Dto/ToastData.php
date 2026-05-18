<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Dto;

use Pajak\Ui\Common\Enums\ToastType;

final readonly class ToastData
{
    public function __construct(
        public ToastType $type,
        public string $title,
        public ?string $message = null,
    ) {
    }

    public static function success(string $title, ?string $message = null): self
    {
        return new self(ToastType::Success, $title, $message);
    }

    public static function error(string $title, ?string $message = null): self
    {
        return new self(ToastType::Error, $title, $message);
    }

    public static function warning(string $title, ?string $message = null): self
    {
        return new self(ToastType::Warning, $title, $message);
    }

    public static function info(string $title, ?string $message = null): self
    {
        return new self(ToastType::Info, $title, $message);
    }
}
