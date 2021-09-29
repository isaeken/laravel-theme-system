<?php

namespace IsaEken\ThemeSystem;

use IsaEken\ThemeSystem\Commands\PublishCommand;
use IsaEken\ThemeSystem\Illuminate\FileViewFinder;
use IsaEken\ThemeSystem\Illuminate\UrlGenerator;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ThemeSystemServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('theme-system')
            ->hasConfigFile()
            ->hasCommand(PublishCommand::class);
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

        if (config('theme-system.enable')) {
            /** @var ThemeSystem $themeSystem */
            $themeSystem = app(ThemeSystem::class);
            $themeSystem->setTheme(config('theme-system.theme'));

            $this->app->bind('view.finder', function ($app) use ($themeSystem) {
                return new FileViewFinder($app['files'], $themeSystem->resolvePaths());
            });

            if ($themeSystem->isAssetsEnabled()) {
                $this->app->singleton('url', function ($app) {
                    return new UrlGenerator(
                        app('router')->getRoutes(),
                        app('request')
                    );
                });
            }
        }
    }
}
