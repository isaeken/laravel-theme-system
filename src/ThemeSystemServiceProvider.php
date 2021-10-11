<?php

namespace IsaEken\ThemeSystem;

use IsaEken\ThemeSystem\Commands\InitializeCommand;
use IsaEken\ThemeSystem\Commands\MakeCommand;
use IsaEken\ThemeSystem\Commands\PublishCommand;
use IsaEken\ThemeSystem\Illuminate\FileViewFinder;
use IsaEken\ThemeSystem\Illuminate\UrlGenerator;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ThemeSystemServiceProvider extends PackageServiceProvider
{
    public array $singletons = [];

    public function __construct($app)
    {
        parent::__construct($app);

        $this->singletons['view.finder'] = function ($app) {
            return new FileViewFinder($app['files'], app(ThemeSystem::class)->resolvePaths());
        };

        $this->singletons['url'] = function ($app) {
            return new UrlGenerator(
                app('router')->getRoutes(),
                app('request')
            );
        };
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('theme-system')
            ->hasConfigFile()
            ->hasCommands(
                PublishCommand::class,
                InitializeCommand::class,
                MakeCommand::class,
            )
            ->hasMigrations(
                'create_choose_themes_table',
            );
    }

    public function registeringPackage()
    {
        require_once __DIR__ . '/helpers.php';
    }

    public function packageBooted()
    {
        $this->app->singleton(ThemeSystem::class, function ($app) {
            return new ThemeSystem();
        });

        $this->app->singleton('theme.view.finder', $this->singletons['view.finder']);

        $this->app->singleton('theme.url', $this->singletons['url']);

        if (config('theme-system.enable')) {
            /** @var ThemeSystem $themeSystem */
            $themeSystem = app(ThemeSystem::class);
            $themeSystem->setTheme(config('theme-system.theme'));

            $this->app->singleton('view.finder', $this->singletons['view.finder']);

            if ($themeSystem->isAssetsEnabled()) {
                $this->app->singleton('url', $this->singletons['url']);
            }
        }
    }
}
