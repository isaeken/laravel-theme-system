<?php

namespace IsaEken\ThemeSystem\Illuminate;

use Illuminate\Support\Str;

class UrlGenerator extends \Illuminate\Routing\UrlGenerator
{
    /**
     * Generate the URL to an application asset.
     *
     * @param  string  $path
     * @param  bool|null  $secure
     *
     * @return string
     */
    public function asset($path, $secure = null)
    {
        if ($this->isValidUrl($path)) {
            return $path;
        }

        $root = $this->assetRoot ?: $this->formatRoot($this->formatScheme($secure));

        $theme = Str::snake(theme_system()->getCurrentTheme(), '-');
        if ($theme === 'default') {
            $theme = '';
        }

        return $this->removeIndex($root) . '/' . trim($theme . '/' . $path, '/');
    }

    /**
     * Generate the URL to an asset from a custom root domain such as CDN, etc.
     *
     * @param  string  $root
     * @param  string  $path
     * @param  bool|null  $secure
     * @return string
     */
    public function assetFrom($root, $path, $secure = null)
    {
        $root = $this->formatRoot($this->formatScheme($secure), $root);
        $theme = Str::snake(theme_system()->getCurrentTheme(), '-');
        if ($theme === 'default') {
            $theme = '';
        }

        return $this->removeIndex($root).'/'.trim($theme . '/' . $path, '/');
    }
}
