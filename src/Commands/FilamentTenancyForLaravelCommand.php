<?php

namespace Saasykit\FilamentTenancyForLaravel\Commands;

use Illuminate\Console\Command;

class FilamentTenancyForLaravelCommand extends Command
{
    public $signature = 'filament-tenancy-for-laravel';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
