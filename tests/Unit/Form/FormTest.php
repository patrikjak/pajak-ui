<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Form;

use Pajak\Ui\Common\Enums\Method;
use Pajak\Ui\Form\View\Components\Form;
use Pajak\Ui\Tests\Unit\TestCase;

final class FormTest extends TestCase
{
    public function testResolvedSubmitTextUsesExplicitValue(): void
    {
        $form = new Form('/submit', Method::Post, 'Save changes');

        $this->assertSame('Save changes', $form->resolvedSubmitText());
    }

    public function testResolvedSubmitTextFallsBackToTranslation(): void
    {
        $form = new Form('/submit');

        $this->assertSame(__('pajak::ui.form.submit'), $form->resolvedSubmitText());
    }
}
