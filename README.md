
# Laravel Theme System

![Laravel Theme System](https://banners.beyondco.de/Laravel%20Theme%20System.png?theme=light&packageManager=composer%20require&packageName=isaeken/laravel-theme-system&pattern=architect&style=style_1&description=Make%20multiple%20themes%20for%20your%20Laravel%20application&md=1&showWatermark=1&fontSize=100px&images=https://laravel.com/img/logomark.min.svg)

[![Latest Version](https://img.shields.io/github/v/tag/isaeken/laravel-theme-system?sort=semver&label=version)](https://packagist.org/packages/isaeken/laravel-theme-system)
![CircleCI](https://img.shields.io/circleci/build/github/isaeken/laravel-theme-system)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/isaeken/laravel-theme-system/Check%20&%20fix%20styling?label=code%20style)](https://github.com/isaeken/laravel-theme-system/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/isaeken/laravel-theme-system.svg?style=flat-square)](https://packagist.org/packages/isaeken/laravel-theme-system)

## Installation and setup

### Installation

You can install the package via composer:

```bash
composer require isaeken/laravel-theme-system
```

### Setup

You can publish the config file with:

```bash
php artisan vendor:publish --provider="IsaEken\ThemeSystem\ThemeSystemServiceProvider" --tag="theme-system-config"
```

Run the following command in the terminal for initializing:

````shell
php artisan themes:init
````

## Usage

### Change the theme in runtime

````php
theme_system()->setTheme('your-theme-name');
````

### Get current theme name

````php
theme_system()->getCurrentTheme();
````

### Creating theme

Run the following command in the terminal.

````shell
php artisan make:theme your-theme-name
````

Change theme in PHP or application config.

### Webpack

> Do not change the main ``webpack.mix.js`` file.

A special ``"webpack.mix.js"`` file is created for each theme.

The ``"webpack.mix.js"`` file of the default theme is in the ``"resources"`` folder.

You can continue to use the ``"webpack.mix.js"`` as normal in the default theme.

However, in themes you should use it as in the example.

````js
const mix = require('laravel-mix');

mix
    .js(themeResourceRoot + '/js/app.js', 'js')
    .postCss(themeResourceRoot + '/css/app.css', 'css', [
        //
    ]);

exports.mix = mix;
````

### Middleware to set a theme

Register ``ThemeMiddleware`` in ``app\Http\Kernel.php``:

````php
protected $routeMiddleware = [
    // ...
    'theme' => \IsaEken\ThemeSystem\Http\Middlewares\ThemeMiddleware::class,
];
````

Example usages:

````php
Route::group(['middleware' => 'theme:your-theme-name'], function () {
    // ...
});

Route::get('/hello-world', fn () => 'Hello World!')->middleware('theme:your-theme-name');
````

### If you need to advanced methods, see ThemeSystem class.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Isa Eken](https://github.com/isaeken)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
