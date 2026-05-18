<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Tests\Integration\TestCase;

final class CheckboxSnapshotTest extends TestCase
{
    public function testDefaultCheckbox(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::checkbox name="agree" value="1" label="I agree to the terms" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testCheckedCheckbox(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::checkbox name="agree" value="1" label="I agree to the terms" :checked="true" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
