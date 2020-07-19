<?php
/**
 * Laravel Theme System
 * @author İsa Eken <hello@isaeken.com.tr>
 * @license MIT
 */

namespace IsaEken\ThemeSystem;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Class ThemeSystemServiceProvider
 * @package IsaEken\ThemeSystem
 */
class ThemeSystemServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }

    public function register()
    {
        require_once __DIR__.'/Support/Helpers.php';

        Blade::if('theme', function ($key) {
            return ThemeSystem::theme()->setting($key) == true;
        });

        Blade::if('page', function ($page) {
            $isCurrentRoute = Route::getCurrentRoute()->getName() == $page;
            if (!$isCurrentRoute) $isCurrentRoute = Route::getCurrentRoute()->getActionName() == $page;
            return $isCurrentRoute;
        });
    }

    public function provides()
    {
        return [ 'themesystem' ];
    }
}
