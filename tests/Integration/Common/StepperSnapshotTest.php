<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\Stepper\StepperStepState;
use Pajak\Ui\Common\Enums\Stepper\StepperVariant;
use Pajak\Ui\Tests\Integration\TestCase;

final class StepperSnapshotTest extends TestCase
{
    public function testHorizontalStepper(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::stepper>
                <x-pajak::stepper-step :step="1" title="Identity" description="Verified" :state="$done" />
                <x-pajak::stepper-step :step="2" title="Income" :state="$done" />
                <x-pajak::stepper-step :step="3" title="Deductions" description="Add reliefs" :state="$active" />
                <x-pajak::stepper-step :step="4" title="Review" :last="true" />
            </x-pajak::stepper>
            BLADE,
            ['done' => StepperStepState::Done, 'active' => StepperStepState::Active],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testPillStepper(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::stepper :variant="$variant">
                <x-pajak::stepper-step :step="1" title="Identity" :state="$done" :variant="$variant" />
                <x-pajak::stepper-step :step="2" title="Income" :state="$done" :variant="$variant" />
                <x-pajak::stepper-step :step="3" title="Deductions" :state="$active" :variant="$variant" />
                <x-pajak::stepper-step :step="4" title="Review" :variant="$variant" :last="true" />
            </x-pajak::stepper>
            BLADE,
            [
                'variant' => StepperVariant::Pill,
                'done' => StepperStepState::Done,
                'active' => StepperStepState::Active,
            ],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testVerticalStepper(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak::stepper :variant="$variant">
                <x-pajak::stepper-step
                    :step="1"
                    title="Verify identity"
                    description="Confirmed via Profil Zaufany."
                    :state="$done"
                    :variant="$variant"
                />
                <x-pajak::stepper-step
                    :step="2"
                    title="Add deductions"
                    description="Review your reliefs."
                    :state="$active"
                    :variant="$variant"
                >
                    Your largest unclaimed relief: Home Office.
                </x-pajak::stepper-step>
                <x-pajak::stepper-step
                    :step="3"
                    title="Review"
                    :state="$upcoming"
                    :variant="$variant"
                    :last="true"
                />
            </x-pajak::stepper>
            BLADE,
            [
                'variant' => StepperVariant::Vertical,
                'done' => StepperStepState::Done,
                'active' => StepperStepState::Active,
                'upcoming' => StepperStepState::Upcoming,
            ],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testBarStepper(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::stepper :variant="$variant" :current="3" :total="5" label="Deductions" />',
            ['variant' => StepperVariant::Bar],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDoneStep(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::stepper-step :step="1" title="Identity" :state="$state" />',
            ['state' => StepperStepState::Done],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testActiveStep(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::stepper-step :step="2" title="Income" :state="$state" />',
            ['state' => StepperStepState::Active],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
