<?php
/**
 * Laravel Theme System
 * @author İsa Eken <hello@isaeken.com.tr>
 * @license MIT
 */

namespace IsaEken\ThemeSystem;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\View\View;
use IsaEken\ThemeSystem\Models\Theme;
use IsaEken\ThemeSystem\Models\User;
use IsaEken\ThemeSystem\Models\UserTheme;

/**
 * Class ThemeSystem
 * @package IsaEken\ThemeSystem
 */
class ThemeSystem
{
    /**
     * @param string $directory
     * @return bool
     */
    private static function rmdir(string $directory) : bool
    {
        if (is_dir($directory))
        {
            $objects = scandir($directory);
            foreach ($objects as $object)
            {
                if ($object != '.' && $object != '..')
                {
                    if (filetype($directory.'/'.$object) == 'dir')
                        self::rmdir($directory.'/'.$object);
                    else
                        unlink($directory.'/'.$object);
                }
            }
            reset($objects);
            return rmdir($directory);
        }
        return false;
    }

    /**
     * @var bool $compressAssets
     */
    public static $compressAssets = false;

    /**
     * @var bool $cacheAssets
     */
    public static $cacheAssets = true;

    /**
     * @return Theme|null
     */
    public static function theme() : ?Theme
    {
        if (Auth::check())
        {
            $user = User::where('id', Auth::id())->first();
            $theme = $user->theme()->first();
            if ($theme != null) return Theme::where('id', $theme->theme_id)->where('state', 'active')->first();
        }
        if (Cookie::has('isaeken_app_theme'))
        {
            $theme = Theme::where('state', 'active')->where('id', Cookie::get('isaeken_app_theme'))->limit(1)->first();
            if ($theme != null) return $theme;
        }
        $theme = Theme::where('default', true)->where('state', 'active')->limit(1)->first();
        abort_if($theme == null, 500, __('Missing default theme!'));
        return $theme;
    }

    /**
     * @param Theme $theme
     */
    public static function change(Theme $theme) : void
    {
        if ($theme == null) throw new \InvalidArgumentException();
        if (Auth::check())
        {
            $userTheme = UserTheme::where('user_id', Auth::id())->first();
            if ($userTheme == null)
                UserTheme::create([
                    'user_id' => Auth::id(),
                    'theme_id' => $theme->id,
                ]);
            else
            {
                $userTheme->theme_id = $theme->id;
                $userTheme->save();
            }
        }
        else
        {
            Cookie::queue('isaeken_app_theme', $theme->id, 2628000);
        }
    }

    /**
     * @param $view
     * @param array $data
     * @param array $mergeData
     * @return Application|Factory|View
     */
    public static function view($view, $data = [], $mergeData = [])
    {
        $theme = self::Theme();
        if ($theme != null)
        {
            \view()->addNamespace(
                $theme->name,
                base_path('themes/'.$theme->name.'/views')
            );
            if (\view()->exists($theme->name.'::'.$view)) return \view($theme->name.'::'.$view, $data, $mergeData);
            return \view($view, $data, $mergeData);
        }
        return \view($view, $data, $mergeData);
    }

    /**
     * @param string $themeName
     * @return bool
     */
    public static function isInstalled(string $themeName) : bool
    {
        $theme = Theme::where('name', $themeName)->first();
        if ($theme == null) return false;
        if (is_dir(base_path('/themes/'.$themeName))) return true;
        return false;
    }

    /**
     * @param string $themeFile
     * @param bool $setDefault
     * @return object
     */
    public static function install(string $themeFile, bool $setDefault = false) : object
    {
        if (!is_dir(base_path('/themes'))) mkdir(base_path('/themes'));
        if (!is_dir(base_path('/themes/tmp'))) mkdir(base_path('/themes/tmp'));

        $tempDirectory = base_path('/themes/tmp');
        $tempThemeDirectory = $tempDirectory.'/'.Str::random();
        $themeName = null;
        $paths = array(
            'views' => 'dir',
            'assets' => 'dir',
            'details.json' => 'file',
        );
        $detailProperties = array(
            'name',
            'version',
            'app-version',
        );
        $details = new \StdClass;
        $settings = new \StdClass;

        /**
         * Check $themeFile is exists
         */
        if (!file_exists($themeFile)) return (object) array( 'valid' => false, 'installed' => false, 'message' => "File '{$themeFile}' not exists" );

        /**
         * Initialize archive
         */
        $zip = new \ZipArchive;

        /**
         * Try to open archive
         */
        $res = $zip->open($themeFile);
        if ($res !== true) return (object) array( 'valid' => false, 'installed' => false, 'message' => "Failed to open file: '{$themeFile}'" );

        /**
         * Create temporary directory
         */
        if (!is_dir($tempDirectory)) mkdir($tempDirectory);
        if (is_dir($tempThemeDirectory)) rmdir($tempThemeDirectory);
        mkdir($tempThemeDirectory);

        /**
         * Extract theme to temporary directory
         */
        $zip->extractTo($tempThemeDirectory);

        /**
         * Close archive
         */
        $zip->close();

        /**
         * Check theme files
         */
        foreach ($paths as $path => $type)
        {
            if ($type == 'dir')
            {
                if (!is_dir($tempThemeDirectory.'/'.$path))
                    return (object) array( 'valid' => false, 'installed' => false, 'message' => "Directory not exists in theme: ".'/'.$path );
            }
            else if ($type == 'file')
            {
                if (!file_exists($tempThemeDirectory.'/'.$path))
                    return (object) array( 'valid' => false, 'installed' => false, 'message' => "File not exists in theme: ".'/'.$path );
            }
        }

        /**
         * Set variables
         */

        $details = json_decode(file_get_contents($tempThemeDirectory.'/details.json'), false);
        $settings = (
            file_exists($tempThemeDirectory.'/settings.json') ?
            json_decode(file_get_contents($tempThemeDirectory.'/settings.json'), false) : null
        );

        /**
         * Check details
         */
        foreach ($detailProperties as $property)
            if (!isset($details->${$property}) || empty($details->${$property}))
                return (object) array(
                    'valid' => false,
                    'message' => "'${$property}' property not defined in 'details.json'"
                );

        /**
         * Create theme name
         */
        $themeName = Str::slug($details->name, '_');

        /**
         * Check theme is already installed
         */
        if (
            is_dir(base_path('/themes/'.$themeName)) ||
            Theme::where('name', $themeName)->first() != null
        ) return (object) array( 'valid' => true, 'installed' => false, 'message' => 'Theme already installed' );

        /**
         * Install theme
         */
        $theme = Theme::create([
            'state' => 'active',
            'default' => false,
            'name' => $themeName,
            'details' => $details,
            'settings' => $settings,
        ]);
        if ($setDefault) self::setDefault($theme);
        rename($tempThemeDirectory, base_path('/themes/'.$themeName));

        return (object) array(
            'valid' => true,
            'installed' => true,
            'message' => 'Theme is installed.'
        );
    }

    /**
     * @param string $themeName
     * @param bool $withFiles
     * @param bool $forceDelete
     * @return bool
     */
    public static function uninstall(string $themeName, bool $withFiles = false, bool $forceDelete = false) : bool
    {
        /**
         * Check theme is installed
         */
        if (!self::isInstalled($themeName)) return false;

        /**
         * Get theme in database
         */
        $theme = Theme::where('name', $themeName)->first();

        /**
         * if force delete enabled just add deleted flag to theme else delete from database
         */
        if ($forceDelete) $theme->delete();
        else
        {
            $theme->state = 'deleted';
            $theme->save();
        }

        /**
         * check if delete with files
         */
        if ($withFiles)
        {
            /**
             * try to delete theme files
             */
            if (self::rmdir(base_path('/themes/'.$themeName))) return true;
            else return false;
        }
        return true;
    }

    /**
     * @param Theme $theme
     */
    public static function setDefault(Theme $theme) : void
    {
        if ($theme == null) throw new \InvalidArgumentException();
        Theme::where('id', $theme->id)->get()->each(function ($theme) {
            $theme->update([ 'default' => false ]);
        });
        $theme->update([ 'default' => true ]);
    }
}
