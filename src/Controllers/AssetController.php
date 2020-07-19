<?php
/**
 * Laravel Theme System
 * @author İsa Eken <hello@isaeken.com.tr>
 * @license MIT
 */

namespace IsaEken\ThemeSystem\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use IsaEken\ThemeSystem\ThemeSystem;

/**
 * Class AssetController
 * @package IsaEken\ThemeSystem\Controllers
 */
class AssetController extends Controller
{
    /**
     * @param Request $request
     * @param string $asset
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $asset)
    {
        $themeName = ThemeSystem::theme()->name;
        abort_if($themeName == null, 404);

        $path = base_path('/themes/'.$themeName.'/assets/'.$asset);
        abort_if(!file_exists($path), 404);

        $cacheName = 'isaeken.themesystem.'.$themeName.'.'.$asset;
        if (ThemeSystem::$cacheAssets)
        {
            if (Cache::has($cacheName)) return Cache::get($cacheName);
        }

        $file = File::get($path);
        $mime = mime_content_type($path);

        switch (File::extension($path))
        {
            case 'css':
                $mime = 'text/css';
                break;

            case 'js':
                $mime = 'application/javascript';
                break;
        }

        if (ThemeSystem::$compressAssets)
        {
            switch ($mime)
            {
                case 'text/css':
                    $file = minifyCSS($file);
                    break;
            }
        }

        $response = Response::make($file, 200);
        $response->header('Content-Type', $mime);

        if (ThemeSystem::$cacheAssets)
        {
            $response->header('pragma', 'private');
            $response->header('Cache-Control', ' private, max-age=86400');
            Cache::put($cacheName, $response);
        }

        return $response;
    }
}
