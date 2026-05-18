<?php

declare(strict_types=1);

namespace Pajak\Ui\Form\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Repeater extends Component
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $label = null,
        public readonly int $min = 0,
        public readonly ?int $max = null,
        public readonly int $count = 1,
        public readonly ?string $addLabel = null,
        public readonly ?string $removeLabel = null,
    ) {
    }

    public function resolvedAddLabel(): string
    {
        return $this->addLabel ?? __('pajak::ui.form.repeater.add');
    }

    public function resolvedRemoveLabel(): string
    {
        return $this->removeLabel ?? __('pajak::ui.form.repeater.remove');
    }

    public function replaceIndex(string $html, int|string $index): string
    {
        return preg_replace_callback(
            '/(?<= )(name|id|for)="([^"]*)"/',
            function (array $m) use ($index): string {
                $attr = $m[1];
                $val = str_replace(['__INDEX__', '__NAME__'], [(string) $index, $this->name], $m[2]);

                if ($attr === 'name' && $val !== '' && !str_contains($val, '[')) {
                    return sprintf('%s="%s[%s][%s]"', $attr, $this->name, $index, $val);
                }

                if ($val !== '' && !str_contains($val, '[')) {
                    return sprintf('%s="%s_%s_%s"', $attr, $this->name, $index, $val);
                }

                return sprintf('%s="%s"', $attr, $val);
            },
            $html,
        ) ?? $html;
    }

    public function render(): View
    {
        return view('pajak::form.repeater', ['replaceIndex' => $this->replaceIndex(...)]);
    }
}
