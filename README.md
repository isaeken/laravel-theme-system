# Laravel Theme System

[![Latest Version on Packagist](https://img.shields.io/packagist/v/isaeken/laravel-theme-system.svg?style=flat-square)](https://packagist.org/packages/isaeken/laravel-theme-system)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/isaeken/laravel-theme-system/run-tests?label=tests)](https://github.com/isaeken/laravel-theme-system/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/isaeken/laravel-theme-system/Check%20&%20fix%20styling?label=code%20style)](https://github.com/isaeken/laravel-theme-system/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/isaeken/laravel-theme-system.svg?style=flat-square)](https://packagist.org/packages/isaeken/laravel-theme-system)

## Installation

You can install the package via composer:

```bash
composer require isaeken/laravel-theme-system
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="IsaEken\ThemeSystem\ThemeSystemServiceProvider" --tag="theme-system-config"
```

This is the contents of the published config file:

```php
// config for IsaEken/ThemeSystem
return [
    /*
    |--------------------------------------------------------------------------
    | Enable the Theme System
    |--------------------------------------------------------------------------
    */

    'enable' => true,

    /*
    |--------------------------------------------------------------------------
    | Enable fallback
    |--------------------------------------------------------------------------
    |
    | When you enable this, the files that are not found will be taken from
    | the default theme.
    |
    */

    'fallback_enable' => true,

    /*
    |--------------------------------------------------------------------------
    | Enable assets
    |--------------------------------------------------------------------------
    |
    | Activate it and the "asset" function will change and generate the
    | address of the theme's shared folder.
    */

    'assets' => true,

    /*
    |--------------------------------------------------------------------------
    | Default theme name. (default)
    |--------------------------------------------------------------------------
    */

    'theme' => null,

    /*
    |--------------------------------------------------------------------------
    | Theme name regex
    |--------------------------------------------------------------------------
    */

    'name_regex' => '/(.[a-zA-Z0-9-_]+)/',

    /*
    |--------------------------------------------------------------------------
    | Public assets folder for themes
    |--------------------------------------------------------------------------
    */

    'public_directory' => 'public',

    /*
    |--------------------------------------------------------------------------
    | Theme installation directory
    |--------------------------------------------------------------------------
    */

    'themes_directory' => resource_path('themes'),
];
```

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

Create ``themes`` directory in to ``resources`` path.

Create your theme in ``resources/themes/your-theme-name``.

Add assets to ``resources/themes/your-theme-name/public/image.jpg``

Add views to ``resources/themes/your-theme-name``

Change theme in php or application config.

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
