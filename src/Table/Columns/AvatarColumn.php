<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Columns;

use Closure;

class AvatarColumn extends TextColumn
{
    protected ?string $subtitleKey = null;

    protected ?string $imageKey = null;

    protected ?Closure $nameResolver = null;

    protected string|Closure|null $initialsSource = null;

    public function subtitle(string $key): static
    {
        $this->subtitleKey = $key;

        return $this;
    }

    public function image(string $key): static
    {
        $this->imageKey = $key;

        return $this;
    }

    public function name(Closure $resolver): static
    {
        $this->nameResolver = $resolver;

        return $this;
    }

    public function initials(string|Closure $keyOrResolver): static
    {
        $this->initialsSource = $keyOrResolver;

        return $this;
    }

    public function subtitleKey(): ?string
    {
        return $this->subtitleKey;
    }

    public function imageKey(): ?string
    {
        return $this->imageKey;
    }

    /**
     * @param array<string, mixed>|object $row
     */
    public function resolveName(array|object $row): string
    {
        if ($this->nameResolver !== null) {
            return (string) ($this->nameResolver)($row);
        }

        return (string) $this->rowValue($row, $this->key());
    }

    /**
     * @param array<string, mixed>|object $row
     */
    public function resolveInitials(array|object $row): string
    {
        if ($this->initialsSource instanceof Closure) {
            return (string) ($this->initialsSource)($row);
        }

        if (is_string($this->initialsSource)) {
            return (string) $this->rowValue($row, $this->initialsSource);
        }

        return $this->initialsFromName($this->resolveName($row));
    }

    public function cellView(): string
    {
        return 'pajak::table.cells.avatar';
    }

    private function initialsFromName(string $name): string
    {
        $words = preg_split('/\s+/u', trim($name), -1, PREG_SPLIT_NO_EMPTY) ?: [];

        $initials = match (count($words)) {
            0 => '',
            1 => mb_substr($words[0], 0, 2),
            default => mb_substr($words[0], 0, 1) . mb_substr($words[array_key_last($words)], 0, 1),
        };

        return mb_strtoupper($initials);
    }
}
