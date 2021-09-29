<?php

namespace IsaEken\ThemeSystem\Illuminate;

class FileViewFinder extends \Illuminate\View\FileViewFinder
{
    /**
     * Get the fully qualified location of the view.
     *
     * @param  string  $name
     *
     * @return string
     */
    public function find($name)
    {
        if (isset($this->views[$name])) {
            return $this->views[$name];
        }

        if ($this->hasHintInformation($name = trim($name))) {
            return $this->views[$name] = $this->findNamespacedView($name);
        }

        return $this->views[$name] = $this->findInPaths($name, theme_system()->resolvePaths());
    }
}
