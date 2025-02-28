<?php

namespace Saasykit\FilamentTenancyForLaravel;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Saasykit\FilamentTenancyForLaravel\Commands\FilamentTenancyForLaravelCommand;
use Saasykit\FilamentTenancyForLaravel\Testing\TestsFilamentTenancyForLaravel;

class FilamentTenancyForLaravelServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-tenancy-for-laravel';

    public static string $viewNamespace = 'filament-tenancy-for-laravel';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations();
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-tenancy-for-laravel/{$file->getFilename()}"),
                ], 'filament-tenancy-for-laravel-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentTenancyForLaravel);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'saasykit/filament-tenancy-for-laravel';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('filament-tenancy-for-laravel', __DIR__ . '/../resources/dist/components/filament-tenancy-for-laravel.js'),
            Css::make('filament-tenancy-for-laravel-styles', __DIR__ . '/../resources/dist/filament-tenancy-for-laravel.css'),
            Js::make('filament-tenancy-for-laravel-scripts', __DIR__ . '/../resources/dist/filament-tenancy-for-laravel.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentTenancyForLaravelCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_filament-tenancy-for-laravel_table',
        ];
    }
}
