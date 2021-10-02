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
                {--relative : Create the symbolic link using relative paths}
                {--force : Recreate existing symbolic links}';

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
        foreach ($this->links() as $link => $target) {
            $link = public_path($link);

            if (file_exists($link) && ! $this->isRemovableSymlink($link, $this->option('force'))) {
                $this->error("The [$link] link already exists.");

                continue;
            }

            if (is_link($link)) {
                $this->laravel->make('files')->delete($link);
            }

            if ($this->option('relative')) {
                $this->laravel->make('files')->relativeLink($target, $link);
            } else {
                $this->laravel->make('files')->link($target, $link);
            }

            $this->info("The [$link] link has been connected to [$target].");
        }

        $this->info('The links have been created.');
    }

    /**
     * Get the theme directories.
     *
     * @return array
     */
    protected function links(): array
    {
        $paths = [];
        foreach (theme_system()->findThemes() as $theme) {
            $directory = $theme->directory . '/' . theme_system()->getPublicDirectory();

            if (! is_dir($directory)) {
                continue;
            }

            $paths[$theme->publicName] = $directory;
        }

        return $paths;
    }

    /**
     * Determine if the provided path is a symlink that can be removed.
     *
     * @param  string  $link
     * @param  bool  $force
     *
     * @return bool
     */
    protected function isRemovableSymlink(string $link, bool $force): bool
    {
        return is_link($link) && $force;
    }
}
