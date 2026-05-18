<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Tests\Integration\TestCase;

final class FormSnapshotTest extends TestCase
{
    public function testDefaultForm(): void
    {
        $html = (string) $this->blade('<x-pajak-form::form action="/submit"></x-pajak-form::form>');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
