<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Tests\Integration\TestCase;

final class RepeaterSnapshotTest extends TestCase
{
    public function testDefaultRepeater(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::repeater name="items">'
            . '<x-pajak-form::input name="title" /></x-pajak-form::repeater>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testRepeaterWithLabel(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::repeater name="items" label="Items">'
            . '<x-pajak-form::input name="title" /></x-pajak-form::repeater>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testRepeaterWithMultipleRows(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::repeater name="items" :count="3">'
            . '<x-pajak-form::input name="title" /></x-pajak-form::repeater>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testRepeaterWithMinConstraint(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::repeater name="items" :min="1">'
            . '<x-pajak-form::input name="title" /></x-pajak-form::repeater>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testRepeaterWithMaxConstraint(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::repeater name="items" :max="5">'
            . '<x-pajak-form::input name="title" /></x-pajak-form::repeater>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testRepeaterWithMinAndMaxConstraints(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::repeater name="items" :min="1" :max="3">'
            . '<x-pajak-form::input name="title" /></x-pajak-form::repeater>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testRepeaterWithCustomLabels(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::repeater name="items" add-label="Add item" remove-label="Remove item">'
            . '<x-pajak-form::input name="title" /></x-pajak-form::repeater>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
