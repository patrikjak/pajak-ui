<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Tests\Integration\TestCase;

final class HiddenSnapshotTest extends TestCase
{
    public function testDefaultHidden(): void
    {
        $html = (string) $this->blade('<x-pajak-form::hidden name="token" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testHiddenWithValue(): void
    {
        $html = (string) $this->blade('<x-pajak-form::hidden name="token" value="abc123" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testHiddenWithIntegerValue(): void
    {
        $html = (string) $this->blade('<x-pajak-form::hidden name="user_id" :value="42" />');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
