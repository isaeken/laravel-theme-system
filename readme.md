# Laravel Theme System
In this repository, you can find the theme system for the framework PHP, Laravel 7

## Installation
- firstly run ```composer require isaeken/laravel-theme-system``` in your project folder

- migrate new tables to your database using ```php artisan migrate```

- create a theme
  ````
  .
  ├── assets            <- Your asset folder
  │   ├── css
  │   │   └── app.css
  │   └── js
  │       └── app.js
  ├── views             <- Your view folder
  │   └── home.blade.php
  ├── details.json      <- Your theme details
  └── settings.json     <- Your theme settings
  ````
  - in details.json
      ````json
      {
          "name": "Your Theme Name",
          "description": "This is your first!",
          "version": "1.0",
          "authors": [
              {
                  "name": "İsa Eken",
                  "email": "hello@isaeken.com.tr"
              }
          ]
      }
      ````
   - *Note: You can blank to ``settings.json`` but this file is required.*
   - Convert to ``zip`` these files.

- create themes folder to application base path and create a basic theme
  
  *files will look something like this:*
  ````
  .
  ├── app
  ├── bootstrap
  ├── config
  ├── themes                    <- Your theme folder
  │   ├── default theme
  │   │   ├── assets            <- Your asset folder
  │   │   │   ├── css
  │   │   │   │   └── app.css
  │   │   │   └── js
  │   │   │       └── app.js
  │   │   ├── views             <- Your view folder
  │   │   │   └── home.blade.php
  │   │   ├── details.json      <- Your theme details
  │   │   └── settings.json     <- Your theme settings
  ├── vendor
  └── .env
  ````
- Install your default theme to your project using php
    ````php
    /**
     * Install a theme file to project
     * @param string $themeFile
     * @param bool $setDefault
     * @return object
     */
    \IsaEken\ThemeSystem\ThemeSystem::install('/your/theme/file.zip', true);
    ````

## Usage
### Show a view from controller
````php
<?php
namespace App\Http\Controllers;
use IsaEken\ThemeSystem\ThemeSystem;

class HomeController extends Controller
{
    public function index()
    {
        $yourData = time();
        $yourSecondData = date(time());
        return ThemeSystem::view('index', compact('yourData', 'yourSecondData'));
    }
}
````

### Change theme for client
````php
$theme = \IsaEken\ThemeSystem\Models\Theme::where('name', 'theme_name')->first();
\IsaEken\ThemeSystem\ThemeSystem::change($theme);
````

### Check theme is installed
````php
if (\IsaEken\ThemeSystem\ThemeSystem::isInstalled('theme_name')) echo 'installed';
else echo 'not installed';
````

### Change default theme
````php
$theme = \IsaEken\ThemeSystem\Models\Theme::where('name', 'theme_name')->first();
\IsaEken\ThemeSystem\ThemeSystem::setDefault($theme);
````

### Uninstall theme
````php
/**
 * @param string $themeName
 * @param bool $withFiles = false
 * @param bool $forceDelete = false
 * @return bool
 */
\IsaEken\ThemeSystem\ThemeSystem::uninstall('theme_name', true, true);
````

### Get current theme for client
````php
/**
 * @return \IsaEken\ThemeSystem\Models\Theme|null
 */
\IsaEken\ThemeSystem\ThemeSystem::theme();
````

## Helpers
````php
/**
 * Get url of client theme asset.
 *
 * @param string $asset
 * @return string
 */
theme_asset('css/app.css');

/**
 * Get setting of client theme.
 *
 * @param string $key
 * @return mixed
 */
theme_setting('key.in.settings.json');

/**
 * Get detail of client theme.
 *
 * @param string $key
 * @return mixed
 */
theme_detail('name');

/**
 * Check current page is $page.
 *
 * @param string $page
 * @return bool
 */
ispage('your.route.name');

/**
 * Minify StyleSheet
 *
 * @param string $css
 * @return string
 */
minifyCSS(<<<EOF
body {
    background-color: red;
}
EOF
);

/**
 * Minify JavaScript
 *
 * @param string $javascript
 * @return string
 */
minifyJS(<<<EOF
$(document).ready(() => {
    console.log('Document is ready!');
});
EOF
);
````

## Blade helpers
````blade
@themeSetting('key.in.settings.json')
    YES
@else
    NO
@endif

@page('your.route.name')
    current page is first page
@elsepage('your.second.route.name')
    current page is second page
@else
    unknown page
@endpage
````

## Other
````php
/**
 * Set caching for assets is enabled or disabled. (Recommended for production)
 */
\IsaEken\ThemeSystem\ThemeSystem::$cacheAssets = false;

/**
 * Set compress assets is enabled or disabled. (If enabled check your assets running correctly!)
 */
\IsaEken\ThemeSystem\ThemeSystem::$compressAssets = false;
````
