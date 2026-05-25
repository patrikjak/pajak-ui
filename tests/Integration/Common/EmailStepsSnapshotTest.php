<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Tests\Integration\TestCase;

final class EmailStepsSnapshotTest extends TestCase
{
    public function testEmailSteps(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::email-steps>
                <x-pajak::email-step :number="1" title="Create account" />
                <x-pajak::email-step :number="2" title="Verify email" />
            </x-pajak::email-steps>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDefaultEmailStep(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::email-step :number="1" title="Create account" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testEmailStepWithDescription(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::email-step :number="2" title="Verify email"'
            . ' description="Check your inbox for the confirmation link." />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDoneEmailStep(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::email-step :number="1" title="Account created" :done="true" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
