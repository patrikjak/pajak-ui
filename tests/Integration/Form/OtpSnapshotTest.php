<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Common\Enums\Size;
use Pajak\Ui\Tests\Integration\TestCase;

final class OtpSnapshotTest extends TestCase
{
    public function testDefaultOtp(): void
    {
        $html = (string) $this->blade('<x-pajak-form::otp name="code" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testOtpWithLengthLabelAndSize(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::otp name="code" :length="4" label="PIN" :size="$size" />',
            ['size' => Size::Lg],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testOtpWithError(): void
    {
        $html = (string) $this->blade('<x-pajak-form::otp name="code" error="The code is invalid." />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testNonNumericOtp(): void
    {
        $blade = '<x-pajak-form::otp name="recovery" :numeric="false" :autosubmit="true" id="recovery-code" />';
        $html = (string) $this->blade($blade);

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testAutofocusedAndDisabledOtp(): void
    {
        $html = (string) $this->blade('<x-pajak-form::otp name="code" :autofocus="true" :disabled="true" />');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
