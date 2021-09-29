<?php

use IsaEken\ThemeSystem\ThemeSystem;

if (!function_exists('theme_system')) {
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

if (!function_exists('theme_path')) {
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
