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

    public function testIndeterminateCheckboxRendersDataAttribute(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::checkbox name="a" value="1" label="A" :indeterminate="true" />',
        );

        $this->assertStringContainsString('data-pajak-indeterminate', $html);
    }

    public function testCheckboxGroupWithSelectAll(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak-form::checkbox-group label="Users" select-all="Select all">
                <x-pajak-form::checkbox name="perms[]" value="view" label="View" />
                <x-pajak-form::checkbox name="perms[]" value="edit" label="Edit" />
            </x-pajak-form::checkbox-group>
            BLADE,
        );

        $this->assertStringContainsString('data-pajak-checkbox-group', $html);
        $this->assertStringContainsString('role="group"', $html);
        $this->assertStringContainsString('pajak-checkbox-group__label">Users', $html);
        $this->assertStringContainsString('data-pajak-checkbox-select-all', $html);
        $this->assertStringContainsString('Select all', $html);
        $this->assertSame(3, substr_count($html, 'type="checkbox"'));
    }

    public function testCheckboxGroupWithoutSelectAllOmitsToggle(): void
    {
        $html = (string) $this->blade(
            <<<'BLADE'
            <x-pajak-form::checkbox-group>
                <x-pajak-form::checkbox name="perms[]" value="view" label="View" />
            </x-pajak-form::checkbox-group>
            BLADE,
        );

        $this->assertStringNotContainsString('data-pajak-checkbox-select-all', $html);
    }
}
