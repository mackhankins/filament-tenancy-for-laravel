<?php

namespace Saasykit\FilamentTenancyForLaravel;

use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Saasykit\FilamentTenancyForLaravel\Commands\FilamentTenancyForLaravelInstaller;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentTenancyForLaravelServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-tenancy-for-laravel';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */

        $this->publishes([
            __DIR__ . '/../stubs/TenancyServiceProvider.stub.php' => app_path('Providers/TenancyServiceProvider.php'),
        ], 'providers');

        $this->publishes([
            __DIR__ . '/../config/tenancy.php' => config_path('tenancy.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../routes/tenant.php' => base_path('routes/tenant.php'),
        ], 'routes');

        $this->commands([
            FilamentTenancyForLaravelInstaller::class,
        ]);

        $package->name(static::$name)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publish('providers')
                    ->publish('config')
                    ->publish('routes');
            });
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

        ];
    }
}
