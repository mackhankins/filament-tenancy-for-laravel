<?php

namespace Saasykit\FilamentTenancyForLaravel\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class FilamentTenancyForLaravelInstaller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filament-tenancy-for-laravel:run-installer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install filament tenancy for laravel package.';

    private array $ignoreList = [
        'Tenant.php',
    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment('Installing saasykit/filament-tenancy-for-laravel...');

        $this->callSilent('optimize:clear');

        $this->callSilent('vendor:publish', [
            '--provider' => 'Saasykit\FilamentTenancyForLaravel\FilamentTenancyForLaravelServiceProvider',
            '--tag' => 'filament-tenancy-for-laravel-config',
        ]);
        $this->info('✔️  Created config/tenancy.php');

        $this->call('vendor:publish', [
            '--provider' => 'Saasykit\FilamentTenancyForLaravel\FilamentTenancyForLaravelServiceProvider',
            '--tag' => 'filament-tenancy-for-laravel-migrations',
        ]);

        $this->info('✔️  Added migrations.');

        if (! file_exists(base_path('routes/tenant.php'))) {
            $this->callSilent('vendor:publish', [
                '--provider' => 'Saasykit\FilamentTenancyForLaravel\FilamentTenancyForLaravelServiceProvider',
                '--tag' => 'filament-tenancy-for-laravel-routes',
            ]);
            $this->info('✔️  Created routes/tenant.php');
        } else {
            $this->info('Found routes/tenant.php.');
        }

        $this->callSilent('vendor:publish', [
            '--provider' => 'Saasykit\FilamentTenancyForLaravel\FilamentTenancyForLaravelServiceProvider',
            '--tag' => 'filament-tenancy-for-laravel-providers',
        ]);
        $this->info('✔️  Created TenancyServiceProvider.php');

        if (! is_dir(database_path('migrations/tenant'))) {
            mkdir(database_path('migrations/tenant'));
            $this->info('✔️  Created database/migrations/tenant folder.');
        }

        $this->handleCentralDatabaseModels();

        if ($this->confirm('Do you want to run the migrations now?', true)) {
            $this->call('migrate');
        }

        $this->comment('✨️ Installed successfully.');
    }

    protected function handleCentralDatabaseModels(): void
    {
        if (! $this->confirm('Do you want to modify eligible models to use the CentralConnection trait (this is recommended)?', true)) {
            $this->info('Skipping model modification.');

            return;
        }

        // Locate all models
        $modelsDirectory = app_path('Models');
        $files = $this->getPhpFiles($modelsDirectory);

        // Process each model file
        foreach ($files as $file) {
            if ($this->shouldIgnore($file)) {
                $this->info('Ignoring: ' . basename($file));

                continue;
            }
            $this->modifyModelFile($file);
        }

        $this->info('✔️  All applicable models have been updated.');
    }

    private function getPhpFiles($directory)
    {
        $files = [];
        if (! is_dir($directory)) {
            return $files;
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
        $phpFiles = new RegexIterator($iterator, '/\.php$/');

        foreach ($phpFiles as $file) {
            $files[] = $file->getPathname();
        }

        return $files;
    }

    private function shouldIgnore($filePath): bool
    {
        return in_array(basename($filePath), $this->ignoreList);
    }

    private function modifyModelFile($filePath)
    {
        $content = file_get_contents($filePath);
        $updated = false;

        // Add "use Stancl\Tenancy\Database\Concerns\CentralConnection;" if missing
        if (! str_contains($content, 'use Stancl\Tenancy\Database\Concerns\CentralConnection;')) {
            $content = preg_replace('/<\?php\s+namespace [^;]+;/', "$0\n\nuse Stancl\\Tenancy\\Database\\Concerns\\CentralConnection;", $content, 1);
            $updated = true;
        }

        // Inject "use CentralConnection;" inside the class
        $pattern = '/class\s+\w+\s+extends\s+\w+(\s+implements\s+[\w, ]+)?\s*{/';
        if (preg_match($pattern, $content) && ! str_contains($content, 'use CentralConnection;')) {
            $content = preg_replace($pattern, "$0\n    use CentralConnection;", $content, 1);
            $updated = true;
        }

        // Save changes if modified
        if ($updated) {
            file_put_contents($filePath, $content);
            $this->info('Updated: ' . basename($filePath));
        }
    }
}
