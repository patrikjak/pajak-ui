<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Form;

use Pajak\Ui\Form\View\Components\Otp;
use Pajak\Ui\Tests\Unit\TestCase;

final class OtpTest extends TestCase
{
    public function testDefaults(): void
    {
        $otp = new Otp('code');

        $this->assertSame(6, $otp->boxCount());
        $this->assertTrue($otp->numeric);
        $this->assertFalse($otp->autosubmit);
        $this->assertFalse($otp->hasError());
    }

    public function testInputIdFallsBackToName(): void
    {
        $this->assertSame('code', (new Otp('code'))->inputId());
        $this->assertSame('verify', (new Otp('code', id: 'verify'))->inputId());
    }

    public function testBoxIdsAndLabelId(): void
    {
        $otp = new Otp('code', id: 'verify');

        $this->assertSame('verify-label', $otp->labelId());
        $this->assertSame('verify-digit-3', $otp->boxId(3));
    }

    public function testLabelTextFallsBackToTranslation(): void
    {
        $this->assertSame(__('pajak::ui.form.otp.label'), (new Otp('code'))->labelText());
    }

    public function testLabelTextUsesExplicitLabel(): void
    {
        $this->assertSame('Authenticator code', (new Otp('code', label: 'Authenticator code'))->labelText());
    }

    public function testBoxLabelNamesPositionAndTotal(): void
    {
        $otp = new Otp('code', 4);

        $this->assertSame(__('pajak::ui.form.otp.digit', ['number' => 2, 'total' => 4]), $otp->boxLabel(2));
    }

    public function testPositionsFollowLength(): void
    {
        $this->assertSame([1, 2, 3, 4], (new Otp('code', 4))->positions());
    }

    public function testLengthIsClampedToAtLeastOneBox(): void
    {
        $otp = new Otp('code', 0);

        $this->assertSame(1, $otp->boxCount());
        $this->assertSame([1], $otp->positions());
    }

    public function testHasErrorIgnoresEmptyMessage(): void
    {
        $this->assertFalse((new Otp('code', error: ''))->hasError());
        $this->assertTrue((new Otp('code', error: 'Invalid code'))->hasError());
    }
}
