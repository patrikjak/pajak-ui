<?php

declare(strict_types=1);

namespace Pajak\Ui\Table\Actions;

use Pajak\Ui\Common\Enums\Method;
use Pajak\Ui\Table\Enums\ActionPosition;

class FormAction extends LinkAction
{
    protected Method $httpMethod = Method::Post;

    protected bool $async = false;

    public function method(Method $method): static
    {
        $this->httpMethod = $method;

        return $this;
    }

    public function httpMethod(): Method
    {
        return $this->httpMethod;
    }

    public function async(): static
    {
        $this->async = true;

        return $this;
    }

    public function isAsync(): bool
    {
        return $this->async;
    }

    /**
     * @return array<int, ActionPosition>
     */
    public function position(): array
    {
        return [ActionPosition::Overflow];
    }
}
