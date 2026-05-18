<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Common\Enums\MessageType;
use Pajak\Ui\Tests\Integration\TestCase;

final class FieldMessageSnapshotTest extends TestCase
{
    public function testErrorMessage(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::field-message :type="$type">This field is required</x-pajak-form::field-message>',
            ['type' => MessageType::Error],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testHintMessage(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::field-message :type="$type">Enter your full name</x-pajak-form::field-message>',
            ['type' => MessageType::Hint],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testSuccessMessage(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::field-message :type="$type">Looks good!</x-pajak-form::field-message>',
            ['type' => MessageType::Success],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
