<?php

namespace IsaEken\ThemeSystem;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use IsaEken\ThemeSystem\Exceptions\ThemeNotExistsException;

class ThemeSystem
{
    private string|null $theme = null;

    public const PackageName = 'isaeken/laravel-theme-system';

    public const Version = 'v2.3';

    public const CommandPrefix = 'themes:';

    /**
     * Check the Theme System is enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return config('theme-system.enable', true) ?? true;
    }

    /**
     * Check fallback is enabled.
     *
     * @return bool
     */
    public function isFallbackEnabled(): bool
    {
        return config('theme-system.fallback_enable', true) ?? true;
    }

    /**
     * Check assets is enabled.
     *
     * @return bool
     */
    public function isAssetsEnabled(): bool
    {
        return config('theme-system.assets', true) ?? true;
    }

    /**
     * Find themes.
     *
     * @return array
     */
    public function findThemes(): array
    {
        $themes = [];
        foreach (scandir(theme_path()) as $path) {
            if ($path == '.' || $path == '..') {
                continue;
            }

            if ($this->isExists($path)) {
                $themes[] = (object) [
                    'directory' => theme_path($path),
                    'name' => $path,
                    'publicName' => Str::snake($path, '-'),
                ];
            }
        }

        return $themes;
    }

    /**
     * Get public directory name.
     *
     * @return string
     */
    public function getPublicDirectory(): string
    {
        return config('theme-system.public', 'public') ?? 'public';
    }

    /**
     * Get a themes directory.
     *
     * @return string
     */
    public function getThemesDirectory(): string
    {
        return config('theme-system.themes_directory', resource_path('themes')) ?? resource_path('themes');
    }

    /**
     * Get current theme name.
     *
     * @return string
     */
    public function getCurrentTheme(): string
    {
        $theme = $this->theme;

        if ($theme == 'default' || $theme === null) {
            return 'default';
        }

        if (mb_strlen($theme) > 0) {
            return $theme;
        }

        return $this->getDefaultTheme();
    }

    /**
     * Get default theme name.
     *
     * @return string
     */
    public function getDefaultTheme(): string
    {
        return config('theme-system.theme', 'default') ?? 'default';
    }

    /**
     * Get current theme directory.
     *
     * @param  bool|null  $fallback
     *
     * @return string
     */
    public function getCurrentThemeDirectory(bool|null $fallback = null): string
    {
        if ($fallback === null) {
            $fallback = $this->isFallbackEnabled();
        }

        $theme = $this->getCurrentTheme();

        if ($theme === 'default') {
            return $this->getDefaultThemeDirectory();
        }

        if (! $this->isExists($theme)) {
            throw_unless($fallback, ThemeNotExistsException::class, $theme);

            return $this->getDefaultThemeDirectory();
        }

        return theme_path($theme);
    }

    /**
     * Get default theme directory.
     *
     * @return string
     */
    public function getDefaultThemeDirectory(): string
    {
        return app('config')['view.paths'][0] ?? resource_path('views');
    }

    /**
     * Change current theme.
     *
     * @param  string|null  $name
     *
     * @return $this
     */
    public function setTheme(string|null $name): static
    {
        if ($name == 'default' || $name == null) {
            $this->theme = $this->getDefaultTheme();
        } elseif ($this->isExists($name)) {
            $this->theme = $name;
        } else {
            throw new ThemeNotExistsException($name);
        }

        return $this;
    }

    /**
     * Check the theme is exists.
     *
     * @param  string  $name
     *
     * @return bool
     */
    public function isExists(string $name): bool
    {
        return
            theme_path($name) !== false &&
            preg_match(config('theme-system.name_regex', '/(.[a-zA-Z0-9-_]+)/'), $name) !== false;
    }

    /**
     * Make a new theme if not exists.
     *
     * @param  string  $name
     */
    public function make(string $name): void
    {
        if ($this->isExists($name)) {
            return;
        }

        $directory = $this->getThemesDirectory() . "/$name";

        $directories = [
            "$directory/public",
            "$directory/resources/css",
            "$directory/resources/js",
        ];

        $files = [
            "$directory/webpack.mix.js" => Webpack::generateTheme(),
            "$directory/resources/css/app.css" => "",
            "$directory/resources/js/app.js" => "",
        ];

        foreach ($directories as $directory) {
            File::makeDirectory($directory, recursive: true);
        }

        foreach ($files as $file => $contents) {
            File::put($file, $contents);
        }
    }

    /**
     * Resolve theme directories.
     *
     * @return array
     */
    public function resolvePaths(): array
    {
        $paths = [];
        $paths[] = $this->getCurrentThemeDirectory(false);
        if ($this->isFallbackEnabled()) {
            $paths[] = $this->getDefaultThemeDirectory();
        }

        return $paths;
    }
}
