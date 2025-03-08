<?php

declare(strict_types=1);

namespace Saasykit\FilamentTenancyForLaravel\Resolvers;

use Stancl\Tenancy\Contracts\Domain;
use Stancl\Tenancy\Contracts\Tenant;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedOnDomainException;
use Stancl\Tenancy\Resolvers\Contracts\CachedTenantResolver;

class DomainTenantResolver extends CachedTenantResolver
{
    /**
     * The model representing the domain that the tenant was identified on.
     *
     * @var Domain
     */
    public static $currentDomain;

    /** @var bool */
    public static $shouldCache = false;

    /** @var int */
    public static $cacheTTL = 3600; // seconds

    /** @var string|null */
    public static $cacheStore = null; // default

    public function resolveWithoutCache(...$args): Tenant
    {
        $domain = $args[0];

        /** @var Tenant|null $tenant */
        $tenant = config('tenancy.tenant_model')::query()
            ->where('domain', $domain)
            ->first();
        
        if ($tenant) {
            $this->setCurrentDomain($tenant, $domain);

            return $tenant;
        }

        throw new TenantCouldNotBeIdentifiedOnDomainException($args[0]);
    }

    public function resolved(Tenant $tenant, ...$args): void
    {
        $this->setCurrentDomain($tenant, $args[0]);
    }

    protected function setCurrentDomain(Tenant $tenant, string $domain): void
    {
        static::$currentDomain = $domain;
    }

    public function getArgsForTenant(Tenant $tenant): array
    {
        return [$tenant->domain];
    }
}
