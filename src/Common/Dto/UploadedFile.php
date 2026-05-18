<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Dto;

final readonly class UploadedFile
{
    public function __construct(
        public string|int $id,
        public string $name,
        public ?int $size = null,
        public ?string $url = null,
    ) {
    }
}
