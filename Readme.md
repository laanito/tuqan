# Tuqan

[![CodeFactor](https://www.codefactor.io/repository/github/laanito/tuqan/badge/master)](https://www.codefactor.io/repository/github/laanito/tuqan/overview/master) [![GitHub license](https://img.shields.io/github/license/laanito/tuqan.svg)](https://github.com/laanito/tuqan/blob/master/LICENSE.md) [![GitHub issues](https://img.shields.io/github/issues/laanito/tuqan.svg)](https://github.com/laanito/tuqan/issues)

This is an old ISO9001 ISO14001 app that I had from around 2005.
My plan is refactoring this to new technologies From old PHP 5.1 to PHP 7
From custom AJAX framework to modern PHP frameworks
From PEAR libraries to composer


## Current Status

This is far from usable at this point without knowledge of the app I will work to have an usable version soon
So far:
 * Dependencies are moved to composer
 * Localization handled with gettext
 * Auth class moved from unsupported PEAR/AUTH to jasny/auth
 * Classes adapted to PSR
 * Added a minimal routing with phroute
 
 
 ## Things to do right now
 
* Remove PEAR dependencies in-code (including files in distribuition)
* Create clean Database for install
* Move from PEAR/DB to PDO
* Move FROM PEAR/QuickForm to PHP-form-bulder
* Use Bootstrap
