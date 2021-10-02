<?php

namespace IsaEken\ThemeSystem\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use IsaEken\ThemeSystem\ThemeSystem;
use IsaEken\ThemeSystem\Webpack;

class InitializeCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = ThemeSystem::CommandPrefix . 'init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the theme system.';

    /**
     * Create a new console command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        if ($this->isInstalled()) {
            $this->setHidden(true);
        }
    }

    /**
     * Check theme system installed.
     *
     * @return bool
     */
    public function isInstalled(): bool
    {
        if (file_exists(base_path('webpack.mix.js'))) {
            $content = file_get_contents(base_path('webpack.mix.js'));
            if (Str::startsWith($content, Webpack::notice())) {
                return true;
            }
        }

        return false;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        if (!$this->isInstalled()) {
            if (!file_exists(base_path('webpack.mix.js'))) {
                File::put(base_path('webpack.mix.js'), '');
            }

            if (file_exists(base_path('webpack.mix.js')) && !file_exists(base_path(Webpack::defaultWebpackPath()))) {
                $this->info('Copying "webpack.mix.js" to ' . base_path(Webpack::defaultWebpackPath()));
                File::copy(base_path('webpack.mix.js'), base_path(Webpack::defaultWebpackPath()));
            }
        }
        else {
            $this->error('Theme system already initialized.');
            return;
        }

        File::delete(base_path('webpack.mix.js'));
        File::put(base_path('webpack.mix.js'), Webpack::generateDefault());
        $this->info('Created main "webpack.mix.js".');

        foreach (theme_system()->findThemes() as $theme) {
            if (!File::exists("$theme->directory/webpack.mix.js")) {
                File::put("$theme->directory/webpack.mix.js", Webpack::generateTheme());
                $this->info("Created webpack config for theme: $theme->name");
            }
        }

        Artisan::call(ThemeSystem::CommandPrefix . 'publish');
        $this->info('Themes published.');
    }
}
