<?php

declare(strict_types=1);

namespace Pajak\Ui;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Pajak\Ui\Common\Console\Commands\InstallCommand;

final class UiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerComponentNamespaces();

        $this->publishAssets();
        $this->publishViews();
        $this->publishConfig();
        $this->publishTranslations();

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'pajak');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'pajak');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadCommands();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/pajak-ui.php', 'pajak-ui');
    }

    private function registerComponentNamespaces(): void
    {
        Blade::componentNamespace('Pajak\\Ui\\Common\\View', 'pajak');
        Blade::componentNamespace('Pajak\\Ui\\Form\\View\\Components', 'pajak-form');
        Blade::componentNamespace('Pajak\\Ui\\Calendar\\View\\Components', 'pajak-calendar');
    }

    private function publishAssets(): void
    {
        $this->publishes(
            [
                __DIR__ . '/../public' => public_path('vendor/pajak/ui'),
            ],
            'pajak-ui-assets',
        );

        $this->publishes(
            [
                __DIR__ . '/../resources/assets/css' => resource_path('css/vendor/pajak/ui'),
                __DIR__ . '/../resources/assets/js' => resource_path('js/vendor/pajak/ui'),
            ],
            'pajak-ui-sources',
        );
    }

    private function publishViews(): void
    {
        $this->publishes(
            [
                __DIR__ . '/../resources/views' => resource_path('views/vendor/pajak'),
            ],
            'pajak-ui-views',
        );
    }

    private function publishConfig(): void
    {
        $this->publishes(
            [
                __DIR__ . '/../config/pajak-ui.php' => config_path('pajak-ui.php'),
            ],
            'pajak-ui-config',
        );
    }

    private function publishTranslations(): void
    {
        $this->publishes(
            [
                __DIR__ . '/../lang' => lang_path('vendor/pajak'),
            ],
            'pajak-ui-translations',
        );
    }

    private function loadCommands(): void
    {
        $this->commands([
            InstallCommand::class,
        ]);
    }
}
