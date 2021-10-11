<?php

namespace IsaEken\ThemeSystem\Commands;

use Illuminate\Console\Command;
use IsaEken\ThemeSystem\ThemeSystem;

class PublishCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = ThemeSystem::CommandPrefix . 'publish
                {--relative : Create the symbolic link using relative paths}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish assets for themes.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $symlink = ! str_contains(ini_get('disable_functions'), 'symlink');

        if (! $symlink) {
            $this->warn('The "symlink" function is disabled. Asset folders will be copied.');
        }

        foreach (theme_system()->findThemes() as $theme) {
            theme_system()->publish(
                name: $theme->name,
                symlink: $symlink,
                relative: $this->option('relative'),
            );
        }

        $this->info('The themes are published.');
    }
}
