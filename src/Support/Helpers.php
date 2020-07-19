<?php
/**
 * Laravel Theme System
 * @author İsa Eken <hello@isaeken.com.tr>
 * @license MIT
 */

use Illuminate\Support\Facades\Route;
use IsaEken\ThemeSystem\ThemeSystem;

if (!function_exists('theme_asset'))
{
    /**
     * Get url of client theme asset.
     *
     * @param string $asset
     * @return string
     */
    function theme_asset(string $asset) : string
    {
        return url('/assets/'.$asset);
    }
}

if (!function_exists('theme_setting'))
{
    /**
     * Get setting of client theme.
     *
     * @param string $key
     * @return mixed
     */
    function theme_setting(string $key)
    {
        return ThemeSystem::theme()->setting($key);
    }
}

if (!function_exists('theme_detail'))
{
    /**
     * Get detail of client theme.
     *
     * @param string $key
     * @return mixed
     */
    function theme_detail(string $key)
    {
        return ThemeSystem::theme()->detail($key);
    }
}

if (!function_exists('ispage'))
{
    /**
     * Check current page is $page.
     *
     * @param string $page
     * @return bool
     */
    function ispage(string $page) : bool
    {
        $isCurrentRoute = Route::getCurrentRoute()->getName() == $page;
        if (!$isCurrentRoute) $isCurrentRoute = Route::getCurrentRoute()->getActionName() == $page;
        return $isCurrentRoute;
    }
}

if (!function_exists('minifyCSS'))
{
    /**
     * Minify StyleSheet
     *
     * @param string $css
     * @return string
     */
    function minifyCSS(string $css) : string
    {
        $css = preg_replace('/\/\*((?!\*\/).)*\*\//', '', $css);
        $css = preg_replace('/\s{2,}/', ' ', $css);
        $css = preg_replace('/\s*([:;{}])\s*/', '$1', $css);
        $css = preg_replace('/;}/', '}', $css);
        return $css;
    }
}

if (!function_exists('minifyJS'))
{
    /**
     * Minify JavaScript
     *
     * @param string $javascript
     * @return string
     */
    function minifyJS(string $javascript) : string
    {
        return preg_replace(array("/\s+\n/", "/\n\s+/", "/ +/"), array("\n", "\n ", " "), $javascript);
    }
}
