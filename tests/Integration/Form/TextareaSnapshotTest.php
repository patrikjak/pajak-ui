<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Tests\Integration\TestCase;

final class TextareaSnapshotTest extends TestCase
{
    public function testDefaultTextarea(): void
    {
        $html = (string) $this->blade('<x-pajak-form::textarea name="bio" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testTextareaWithError(): void
    {
        $html = (string) $this->blade('<x-pajak-form::textarea name="bio" error="Bio is required" />');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
