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
}
