<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use IsaEken\ThemeSystem\Exceptions\ThemeNotExistsException;
use IsaEken\ThemeSystem\ThemeSystem;
use IsaEken\ThemeSystem\Webpack;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

function makeTestingTheme(): string
{
    $themeDirectory = theme_system()->getThemesDirectory() . '/testing';
    if (! is_dir($themeDirectory)) {
        mkdir(theme_system()->getThemesDirectory() . '/testing', recursive: true);
    }

    return $themeDirectory;
}

it('default theme name', function () {
    assertEquals(theme_system()->getCurrentTheme(), 'default');
});

it('change to not exists theme', function () {
    theme_system()->setTheme('not-exists-theme');
})->throws(ThemeNotExistsException::class);

it('change theme', function () {
    makeTestingTheme();

    assertEquals(theme_system()->getCurrentTheme(), 'default');

    theme_system()->setTheme('testing');
    assertEquals(theme_system()->getCurrentTheme(), 'testing');
});

it('get theme asset', function () {
    makeTestingTheme();

    theme_system()->setTheme('testing');
    assertTrue(Str::contains(asset('image.jpg'), 'testing'));
});

it('works publish command', function () {
    $name = Str::of(Str::random())->lower()->snake('-')->__toString();
    if (! is_dir(theme_system()->getThemesDirectory() . "/$name/public")) {
        mkdir(theme_system()->getThemesDirectory() . "/$name/public", recursive: true);
    }

    Artisan::call(ThemeSystem::CommandPrefix . 'publish');
    assertTrue(file_exists(public_path($name)));
});

it('works create command', function () {
    $name = Str::of(Str::random())->lower()->snake('-')->__toString();
    Artisan::call(ThemeSystem::CommandPrefix . 'create ' . $name);
    assertTrue(theme_system()->isExists($name));
});

it('works initialize command', function () {
    Artisan::call(ThemeSystem::CommandPrefix . 'init');
    assertTrue(file_exists(base_path(Webpack::defaultWebpackPath())));
});
