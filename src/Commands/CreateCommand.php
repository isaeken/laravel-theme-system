<?php

namespace IsaEken\ThemeSystem\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use IsaEken\ThemeSystem\ThemeSystem;
use IsaEken\ThemeSystem\Webpack;

class CreateCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = ThemeSystem::CommandPrefix . 'create {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new theme.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $name = $this->argument('name');
        if (theme_system()->isExists($name)) {
            $this->error("The theme '$name' is exists.");
            return;
        }

        $this->info('Creating theme...');

        $dir = theme_system()->getThemesDirectory() . '/' . $name;

        File::makeDirectory($dir . '/public', recursive: true);
        File::makeDirectory($dir . '/resources/css', recursive: true);
        File::makeDirectory($dir . '/resources/js', recursive: true);
        File::put($dir . '/webpack.mix.js', Webpack::generateTheme());
        File::put($dir . '/resources/css/app.css', '');
        File::put($dir . '/resources/js/app.js', '');

        Artisan::call(ThemeSystem::CommandPrefix . 'publish');

        $this->info("Theme created: $name");
    }
}
