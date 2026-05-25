<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Integration\Common;

use Pajak\Ui\Common\Enums\TooltipPlacement;
use Pajak\Ui\Tests\Integration\TestCase;

final class TooltipSnapshotTest extends TestCase
{
    public function testDefaultTooltip(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::tooltip text="Upload document"><button>Upload</button></x-pajak::tooltip>',
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testTopPlacement(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::tooltip text="Save draft" :placement="$placement"><button>Save</button></x-pajak::tooltip>',
            ['placement' => TooltipPlacement::Top],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testRightPlacement(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::tooltip text="Save draft" :placement="$placement"><button>Save</button></x-pajak::tooltip>',
            ['placement' => TooltipPlacement::Right],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testBottomPlacement(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::tooltip text="Delete" :placement="$placement"><button>Delete</button></x-pajak::tooltip>',
            ['placement' => TooltipPlacement::Bottom],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }

    public function testLeftPlacement(): void
    {
        $html = (string) $this->blade(
            '<x-pajak::tooltip text="Settings" :placement="$placement"><button>Settings</button></x-pajak::tooltip>',
            ['placement' => TooltipPlacement::Left],
        );

        $this->assertMatchesHtmlSnapshot($html);
    }
}
