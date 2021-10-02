<?php

use Illuminate\Support\Str;
use IsaEken\ThemeSystem\Exceptions\ThemeNotExistsException;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

function makeTestingTheme(): string {
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
