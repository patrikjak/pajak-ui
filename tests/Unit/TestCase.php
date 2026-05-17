<?php

declare(strict_types=1);

namespace Pajak\Ui\Tests\Unit;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Pajak\Ui\UiServiceProvider;

class TestCase extends OrchestraTestCase
{
    /**
     * @inheritDoc
     */
    protected function getPackageProviders($app): array
    {
        return [UiServiceProvider::class];
    }
}
