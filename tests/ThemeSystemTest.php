<?php

use Illuminate\Support\Str;
use IsaEken\ThemeSystem\Exceptions\ThemeNotExistsException;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

it('default theme name', function () {
    assertEquals(theme_system()->getCurrentTheme(), 'default');
});

it('change to not exists theme', function () {
    theme_system()->setTheme('not-exists-theme');
})->throws(ThemeNotExistsException::class);

it('change theme', function () {
    assertEquals(theme_system()->getCurrentTheme(), 'default');

    $themeDirectory = theme_system()->getThemesDirectory() . '/testing';
    if (!is_dir($themeDirectory)) {
        mkdir(theme_system()->getThemesDirectory() . '/testing', recursive: true);
    }

    theme_system()->setTheme('testing');
    assertEquals(theme_system()->getCurrentTheme(), 'testing');
});

it('get theme asset', function () {
    theme_system()->setTheme('testing');
    assertTrue(Str::contains(asset('image.jpg'), 'testing'));
});
