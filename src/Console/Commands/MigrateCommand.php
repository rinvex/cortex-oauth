<?php

declare(strict_types=1);

namespace Cortex\Oauth\Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Rinvex\Oauth\Console\Commands\MigrateCommand as BaseMigrateCommand;

#[AsCommand(name: 'cortex:migrate:oauth')]
class MigrateCommand extends BaseMigrateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:migrate:oauth {--f|force : Force the operation to run when in production.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Cortex OAuth Tables.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        parent::handle();

        $path = config('cortex.oauth.autoload_migrations') ?
            realpath(__DIR__.'/../../../database/migrations') :
            $this->laravel->databasePath('migrations/cortex/oauth');

        if (file_exists($path)) {
            $this->call('migrate', [
                '--step' => true,
                '--path' => $path,
                '--realpath' => true,
                '--force' => $this->option('force'),
            ]);
        } else {
            $this->warn('No migrations found! Consider publish them first: <fg=green>php artisan cortex:publish:oauth</>');
        }

        $this->line('');
    }
}
