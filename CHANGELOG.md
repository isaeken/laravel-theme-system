# Changelog

All notable changes to `laravel-theme-system` will be documented in this file.

## 2.0.0 - 2021-09-29

- updated laravel version to ``^8.62``
- updated php version to ``^8.0``
- used ``spatie/laravel-package-tools`` for skeleton.
- removed migrations
- removed example theme
- removed controllers
- removed models
- removed routes
- removed old helpers
- added new config file
- created ThemeSystem class
- register ThemeSystem as singleton to laravel application in service provider
- added ``theme_system()`` helper
- added ``theme_path`` helper
- override UrlGenerator
- override FileViewFinder
- added ThemeNotExistsException
- added PublishCommand
- added tests

## 2.1 - 2021-10-03

- added circleci config
- updated issue templates
- updated badges in readme
- added create command
- added initialize command
- added publish command
- added custom webpack support for all themes

## 2.2 - 2021-10-03

- refactored create command to make command
- added make method in ThemeSystem class
- added theme middleware
