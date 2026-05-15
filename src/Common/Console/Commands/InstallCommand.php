<?php

declare(strict_types=1);

namespace Pajak\Ui\Common\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * @var string
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $signature = 'install:pajak-ui';

    /**
     * @var string
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $description = 'Install pajak/ui package';

    public function handle(): void
    {
        $this->call('vendor:publish', ['--tag' => 'pajak-ui-config', '--force' => true]);
        $this->call('vendor:publish', ['--tag' => 'pajak-ui-assets', '--force' => true]);
    }
}
