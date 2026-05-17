<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\Concerns;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

/**
 * @phpstan-require-extends Request
 */
trait HasFileUploads
{
    /**
     * @return Collection<int, UploadedFile>
     */
    public function uploadedFiles(string $name): Collection
    {
        assert($this instanceof Request);

        $files = $this->file($name);

        if ($files === null) {
            return new Collection();
        }

        return new Collection(is_array($files) ? array_values(array_filter($files)) : [$files]);
    }

    /**
     * IDs of pre-populated files the user explicitly removed ({name}_delete[]).
     *
     * @return Collection<int, string|int>
     */
    public function deletedFileIds(string $name): Collection
    {
        assert($this instanceof Request);

        return new Collection($this->input(sprintf('%s_delete', $name), []));
    }

    /**
     * IDs of pre-populated files the user kept ({name}_existing[]).
     *
     * @return Collection<int, string|int>
     */
    public function keptFileIds(string $name): Collection
    {
        assert($this instanceof Request);

        return new Collection($this->input(sprintf('%s_existing', $name), []));
    }

    /**
     * @return Collection<string, Collection<int, UploadedFile>>
     */
    public function allUploadedFiles(): Collection
    {
        assert($this instanceof Request);

        return new Collection(
            array_map(
                static fn (mixed $files): Collection => new Collection(
                    is_array($files) ? array_values(array_filter($files)) : [$files],
                ),
                $this->allFiles(),
            ),
        );
    }

    public function uploadedAvatar(string $name): ?UploadedFile
    {
        assert($this instanceof Request);

        return $this->file($name);
    }

    public function avatarDeleted(string $name): bool
    {
        assert($this instanceof Request);

        return $this->boolean(sprintf('%s_delete', $name));
    }
}
