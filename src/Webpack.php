<?php

namespace IsaEken\ThemeSystem;

use Illuminate\Support\Str;

class Webpack
{
    public const ResourceRootVariableName = 'themeResourceRoot';

    public const PublicPathVariableName = 'themePublicPath';

    /**
     * @param  string  $path
     * @param  string|array  $excludes
     *
     * @return string
     */
    private static function simplifyPath(string $path, string|array $excludes = []): string
    {
        return Str::of($path)->remove($excludes)->replace('\\', '/')->trim('/');
    }

    /**
     * @return string
     */
    public static function defaultWebpackPath(): string
    {
        $path = static::simplifyPath(theme_system()->getDefaultThemeDirectory(), base_path());
        if ($path == 'resources/views') {
            $path = 'resources';
        }

        return $path . '/webpack.mix.js';
    }

    /**
     * @return string
     */
    public static function signature(): string
    {
        $package = ThemeSystem::PackageName;
        $version = ThemeSystem::Version;

        return "// $package ($version)";
    }

    /**
     * @return string
     */
    public static function notice(): string
    {
        return "// AUTOGENERATED FILE. DO NOT CHANGE THIS !!";
    }

    /**
     * @return string
     */
    public static function generateDefault(): string
    {
        $var_resource_root = static::ResourceRootVariableName;
        $var_public_path = static::PublicPathVariableName;

        $resources_path = 'resources';
        $public_path = theme_system()->getPublicDirectory();

        $theme_root_path = static::simplifyPath(theme_system()->getThemesDirectory(), base_path());
        $default_webpack_target_path = static::defaultWebpackPath();

        $signature = static::signature();
        $notice = static::notice();

        return <<<CONTENTS
$notice
$signature

let theme = process.env.npm_config_theme;

if (theme) {
    global.$var_resource_root = './$theme_root_path/' + theme + '/$resources_path/';
    global.$var_public_path = './$public_path/' + theme + '/';

    const { mix } = require('./$theme_root_path/' + theme + '/webpack.mix.js');
    mix.setPublicPath($var_public_path);
}
else {
    theme = 'default';
    require('./$default_webpack_target_path');
}
CONTENTS;
    }

    /**
     * @return string
     */
    public static function generateTheme(): string
    {
        $var_resource_root = static::ResourceRootVariableName;

        return <<<CONTENTS
const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    .js($var_resource_root + '/js/app.js', 'js')
    .postCss($var_resource_root + '/css/app.css', 'css', [
        //
    ]);

exports.mix = mix;

CONTENTS;
    }
}
