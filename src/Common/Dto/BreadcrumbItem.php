<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Dto;

final readonly class BreadcrumbItem
{
    public function __construct(
        public string $label,
        public ?string $href = null,
        public bool $isHome = false,
    ) {
    }

    public static function home(string $href = '/'): self
    {
        return new self('Home', $href, true);
    }

    public static function link(string $label, string $href): self
    {
        return new self($label, $href);
    }

    public static function current(string $label): self
    {
        return new self($label);
    }
}
