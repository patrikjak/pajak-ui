<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Dto\UploadedFile;
use Pajak\Ui\Common\Enums\State;

final class ImageGrid extends Component
{
    /**
     * @var UploadedFile[]
     */
    public readonly array $images;

    /**
     * @param iterable<UploadedFile> $images
     */
    public function __construct(
        public readonly string $name,
        iterable $images = [],
        public readonly ?string $label = null,
        public readonly State $state = State::Default,
        public readonly bool $disabled = false,
        public readonly ?string $id = null,
        public readonly ?string $error = null,
        public readonly ?string $accept = 'image/*',
    ) {
        $this->images = is_array($images) ? $images : iterator_to_array($images);
    }

    public function inputId(): string
    {
        return $this->id ?? $this->name;
    }

    public function resolvedState(): State
    {
        return $this->error !== null ? State::Error : $this->state;
    }

    public function render(): View
    {
        return view('pajak::form.image-grid');
    }
}
