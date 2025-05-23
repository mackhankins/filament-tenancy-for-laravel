<?php

namespace Saasykit\FilamentTenancyForLaravel;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Saasykit\FilamentTenancyForLaravel\Middleware\InitializeTenancyByUuid;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class FilamentTenancyForLaravelPlugin implements Plugin
{
    protected string $identificationMiddleware = InitializeTenancyByUuid::class;

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
                $this->identificationMiddleware,
                PreventAccessFromCentralDomains::class,
            ], isPersistent: true);
    }

    public function boot(Panel $panel): void {}

    public function identificationMiddleware(string $identificationMiddleware): static
    {
        $this->identificationMiddleware = $identificationMiddleware;

        return $this;
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
