<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Actions;

use Closure;
use Pajak\Ui\Table\Contracts\TableAction;
use Pajak\Ui\Table\Enums\ActionPosition;

class LinkAction implements TableAction
{
    protected string $actionLabel;

    protected bool $danger = false;

    /**
     * @var array<int, ActionPosition>
     */
    protected array $actionPosition = [ActionPosition::Inline, ActionPosition::Overflow];

    protected ?Closure $urlResolver = null;

    protected ?Closure $visibilityResolver = null;

    final public function __construct(protected string $actionKey)
    {
        $this->actionLabel = $actionKey;
    }

    public static function make(string $key): static
    {
        return new static($key);
    }

    public function label(string $label): static
    {
        $this->actionLabel = $label;

        return $this;
    }

    public function url(Closure $resolver): static
    {
        $this->urlResolver = $resolver;

        return $this;
    }

    public function danger(): static
    {
        $this->danger = true;

        return $this;
    }

    public function inlineOnly(): static
    {
        $this->actionPosition = [ActionPosition::Inline];

        return $this;
    }

    public function overflowOnly(): static
    {
        $this->actionPosition = [ActionPosition::Overflow];

        return $this;
    }

    public function visibleIf(Closure $resolver): static
    {
        $this->visibilityResolver = $resolver;

        return $this;
    }

    public function resolveUrl(mixed $row): ?string
    {
        if ($this->urlResolver === null) {
            return null;
        }

        return ($this->urlResolver)($row);
    }

    public function key(): string
    {
        return $this->actionKey;
    }

    public function getLabel(): string
    {
        return $this->actionLabel;
    }

    /**
     * @return array<int, ActionPosition>
     */
    public function position(): array
    {
        return $this->actionPosition;
    }

    public function isDanger(): bool
    {
        return $this->danger;
    }

    public function isVisibleFor(mixed $row): bool
    {
        if ($this->visibilityResolver === null) {
            return true;
        }

        return (bool) ($this->visibilityResolver)($row);
    }
}
