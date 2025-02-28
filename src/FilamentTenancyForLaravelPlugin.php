<?php

namespace Saasykit\FilamentTenancyForLaravel;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Saasykit\FilamentTenancyForLaravel\Middleware\InitializeTenancyByUuid;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class FilamentTenancyForLaravelPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-tenancy-for-laravel';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->middleware([
                PreventAccessFromCentralDomains::class,
            ])
            ->middleware([
                'universal',
                InitializeTenancyByUuid::class, // todo make this configurable
                PreventAccessFromCentralDomains::class,
            ], isPersistent: true);

        //        $domains = tenant()?->domains()->pluck('domain') ?? [];
        //        $panel->domains($domains);

    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
