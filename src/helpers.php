<?php

use IsaEken\ThemeSystem\ThemeSystem;

if (! function_exists('theme_system')) {
    /**
     * Get theme system instance.
     *
     * @return ThemeSystem
     */
    function theme_system(): ThemeSystem
    {
        return app(ThemeSystem::class);
    }
}

if (! function_exists('theme_path')) {
    /**
     * Get the path to the theme folder.
     *
     * @param  string  $path
     *
     * @return string|false
     */
    function theme_path(string $path = ''): string|false
    {
        return realpath(theme_system()->getThemesDirectory() . DIRECTORY_SEPARATOR . $path);
    }
}

if (! function_exists('theme_asset')) {
    /**
     * Generate the URL to an application asset.
     *
     * @param  string  $path
     * @param  bool|null  $secure
     * @return string
     */
    function theme_asset(string $path, bool|null $secure = null): string
    {
        return app('theme.url')->asset($path, $secure);
    }
}

if (! function_exists('theme_secure_asset')) {
    /**
     * Generate the URL to a secure asset.
     *
     * @param  string  $path
     * @return string
     */
    function theme_secure_asset(string $path): string
    {
        return theme_asset($path, true);
    }
}
