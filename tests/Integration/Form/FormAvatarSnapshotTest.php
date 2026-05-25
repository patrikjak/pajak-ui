<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Form;

use Pajak\Ui\Tests\Integration\TestCase;

final class FormAvatarSnapshotTest extends TestCase
{
    public function testDefaultAvatar(): void
    {
        $html = (string) $this->blade('<x-pajak-form::avatar name="profile_photo" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testAvatarWithLabel(): void
    {
        $html = (string) $this->blade('<x-pajak-form::avatar name="profile_photo" label="Profile photo" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testAvatarWithInitials(): void
    {
        $html = (string) $this->blade('<x-pajak-form::avatar name="profile_photo" initials="JD" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testAvatarWithExistingImage(): void
    {
        $html = (string) $this->blade(
            '<x-pajak-form::avatar name="profile_photo" src="/uploads/avatar.jpg" />',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testAvatarWithError(): void
    {
        $html = (string) $this->blade('<x-pajak-form::avatar name="profile_photo" error="Photo is required" />');

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testDisabledAvatar(): void
    {
        $html = (string) $this->blade('<x-pajak-form::avatar name="profile_photo" :disabled="true" />');

        $this->assertMatchesHtmlSnapshot($html);
    }
}
