<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pajak\Ui\Common\Dto\UploadedFile;
use Pajak\Ui\Common\Enums\State;

final class Dropzone extends Component
{
    /**
     * @var UploadedFile[]
     */
    public readonly array $files;

    /**
     * @param iterable<UploadedFile> $files
     */
    public function __construct(
        public readonly string $name,
        public readonly bool $multiple = true,
        iterable $files = [],
        public readonly ?string $label = null,
        public readonly State $state = State::Default,
        public readonly bool $disabled = false,
        public readonly ?string $id = null,
        public readonly ?string $error = null,
        public readonly ?string $accept = null,
    ) {
        $this->files = is_array($files) ? $files : iterator_to_array($files);
    }

    public function inputId(): string
    {
        return $this->id ?? $this->name;
    }

    public function resolvedState(): State
    {
        return $this->error !== null ? State::Error : $this->state;
    }

    public function formatSize(int $bytes): string
    {
        if ($bytes < 1024) {
            return sprintf('%d B', $bytes);
        }

        if ($bytes < 1_048_576) {
            return sprintf('%.1f KB', $bytes / 1024);
        }

        return sprintf('%.1f MB', $bytes / 1_048_576);
    }

    public function render(): View
    {
        return view('pajak::form.dropzone');
    }
}
