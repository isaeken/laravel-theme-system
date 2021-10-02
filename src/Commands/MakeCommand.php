<?php

namespace IsaEken\ThemeSystem\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use IsaEken\ThemeSystem\ThemeSystem;

class MakeCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'make:theme {name}';

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

        theme_system()->make($name);
        Artisan::call(ThemeSystem::CommandPrefix . 'publish');
        $this->info("Theme created: $name");
    }
}
