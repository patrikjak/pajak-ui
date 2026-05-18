<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Tests\Integration\TestCase;

final class RadioSnapshotTest extends TestCase
{
    public function testDefaultRadio(): void
    {
        $html = (string) $this->blade('<x-pajak-form::radio name="color" value="red" label="Red" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testCheckedRadio(): void
    {
        $html = (string) $this->blade('<x-pajak-form::radio name="color" value="red" label="Red" :checked="true" />');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
