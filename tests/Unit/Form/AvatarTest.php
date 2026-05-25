<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit\Form;

use Pajak\Ui\Common\Enums\State;
use Pajak\Ui\Form\View\Components\Avatar;
use Pajak\Ui\Tests\Unit\TestCase;

final class AvatarTest extends TestCase
{
    public function testInputIdFallsBackToName(): void
    {
        $avatar = new Avatar(name: 'profile_photo');

        $this->assertSame('profile_photo', $avatar->inputId());
    }

    public function testInputIdUsesExplicitId(): void
    {
        $avatar = new Avatar(name: 'profile_photo', id: 'my-avatar');

        $this->assertSame('my-avatar', $avatar->inputId());
    }

    public function testResolvedStateReturnsErrorWhenErrorSet(): void
    {
        $avatar = new Avatar(name: 'profile_photo', error: 'Photo is required');

        $this->assertSame(State::Error, $avatar->resolvedState());
    }

    public function testResolvedStateReturnsStatePropWhenNoError(): void
    {
        $avatar = new Avatar(name: 'profile_photo', state: State::Success);

        $this->assertSame(State::Success, $avatar->resolvedState());
    }

    public function testResolvedStateDefaultsToDefaultWhenNoErrorAndNoState(): void
    {
        $avatar = new Avatar(name: 'profile_photo');

        $this->assertSame(State::Default, $avatar->resolvedState());
    }

    public function testResolvedAcceptDefaultsToImages(): void
    {
        $avatar = new Avatar(name: 'profile_photo');

        $this->assertSame('image/*', $avatar->resolvedAccept());
    }

    public function testResolvedAcceptUsesExplicitValue(): void
    {
        $avatar = new Avatar(name: 'profile_photo', accept: 'image/jpeg,image/png');

        $this->assertSame('image/jpeg,image/png', $avatar->resolvedAccept());
    }

    public function testHasImageReturnsFalseWhenNoSrc(): void
    {
        $avatar = new Avatar(name: 'profile_photo');

        $this->assertFalse($avatar->hasImage());
    }

    public function testHasImageReturnsTrueWhenSrcProvided(): void
    {
        $avatar = new Avatar(name: 'profile_photo', src: '/uploads/avatar.jpg');

        $this->assertTrue($avatar->hasImage());
    }
}
