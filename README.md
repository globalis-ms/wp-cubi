# [wp-cubi](https://github.com/globalis-ms/wp-cubi/)

[![Build Status](https://travis-ci.org/globalis-ms/wp-cubi.svg?branch=master)](https://travis-ci.org/globalis-ms/wp-cubi)
[![Latest Stable Version](https://poser.pugx.org/globalis/wp-cubi/v/stable)](https://packagist.org/packages/globalis/wp-cubi)
[![License](https://poser.pugx.org/globalis/wp-cubi/license)](https://github.com/globalis-ms/wp-cubi/blob/master/LICENSE.md)

WordPress modern stack for developers

[![wp-cubi](https://github.com/wp-globalis-tools/wp-cubi-logo/raw/master/wp-cubi-500x175.jpg)](https://github.com/globalis-ms/wp-cubi/)


## Overview

wp-cubi provides a modern stack and project structure to make professional web applications with WordPress.

Built with [Composer](http://getcomposer.org) dependency manager and [Robo](http://robo.li/) task runner.

**Note: wp-cubi is under active development and is not a final product yet. You should not use it if you don't know PHP development and WordPress basics.**


## Features

### General

* Environment-specific configuration
* Command-line administration with [wp-cli](http://wp-cli.org/)
* Optimized .htaccess generation (inspired by [html5-boilerplate](https://github.com/h5bp/server-configs-apache))
* Gitflow integration with Robo commands
* Automated `no-index` on non-production stages with [wpg-disallow-indexing](https://github.com/wp-globalis-tools/wpg-disallow-indexing)

### Security

* Better password encryption with [wp-password-bcrypt](https://github.com/roots/wp-password-bcrypt)
* Deactivation of REST API and XML-RPC by default with [wpg-security](https://github.com/wp-globalis-tools/wpg-security)

### Performance

* Standalone image / uploads minification plugin with [globalis/wp-cubi-imagemin](https://github.com/globalis-ms/wp-cubi-imagemin)

### Debug and monitoring

* Standalone mail-trapping with [wpg-mail-trapping](https://github.com/wp-globalis-tools/wpg-mail-trapping)
* Debug and monitoring plugin suite with [query-monitor](https://fr.wordpress.org/plugins/query-monitor/) and [wp-crontrol](https://fr.wordpress.org/plugins/wp-crontrol/)

### Logs

* Logging system with [inpsyde/wonolog](https://github.com/inpsyde/Wonolog) and [monolog](https://github.com/Seldaek/monolog)

### wp-admin enhancement

* Cleaner wp-admin with [soberwp/intervention](https://github.com/soberwp/intervention)
* Environment info-box in admin-bar with [wpg-environment-info](https://github.com/wp-globalis-tools/wpg-environment-info)

### Additional functions

* Collection of simple WordPress-friendly functions with [globalis/wp-cubi-helpers](https://github.com/globalis-ms/wp-cubi-helpers)


## Requirements

* [PHP](http://php.net/) >= 5.6
* [Composer](http://getcomposer.org)
* [Git](https://git-scm.com/)


## Installation

1. Create a new project: `composer create-project globalis/wp-cubi your-project && cd your-project`

2. Run installation command and answer the questions: `./vendor/bin/robo install`

3. Setup WordPress database: `./vendor/bin/robo wp:init`


## Commands

### wp-cli

* `./vendor/bin/wp <command>` (see [complete list](https://developer.wordpress.org/cli/commands/))

### Coding standards

* `./vendor/bin/phpcs [directory]` : Check coding standards (see [`./phpcs.xml`](https://github.com/globalis-ms/wp-cubi/blob/master/phpcs.xml))
* `./vendor/bin/phpcbf [directory]` : Fix coding standards auto-fixable violations

### Robo

* `./vendor/bin/robo config`
* `./vendor/bin/robo install`
* `./vendor/bin/robo install:packages`
* `./vendor/bin/robo build`
* `./vendor/bin/robo build:htaccess`
* `./vendor/bin/robo clean`
* `./vendor/bin/robo clean:git`
* `./vendor/bin/robo clean:files`
* `./vendor/bin/robo wp:generate-salt-keys`
* `./vendor/bin/robo wp:init`
* `./vendor/bin/robo wp:db-create`
* `./vendor/bin/robo wp:core:install`
* `./vendor/bin/robo wp:update-language`
* `./vendor/bin/robo wp:update-timezone`
* `./vendor/bin/robo feature:start <feature-name>`
* `./vendor/bin/robo feature:finish <feature-name>`
* `./vendor/bin/robo hotfix:start [--semversion <version>]`
* `./vendor/bin/robo hotfix:finish [--semversion <version>]`
* `./vendor/bin/robo release:start [--semversion <version>]`
* `./vendor/bin/robo release:finish [--semversion <version>]`


## Deployment

In future releases, wp-cubi will come with pre-configured deployment tasks. For now, you can write your own deployment command, editing `./RoboFile.php`.


## WordPress plugins

wp-cubi handles WordPress plugin dependencies (including [wordpress.org](https://wordpress.org/) plugins) through Composer.

If you want to use plugins that are not available through [wordpress.org](https://wordpress.org/) or a public Composer repository, you have two options:

1. (easier) Manually add the plugin in your `./web/app/modules/` directory, then whitelist it in your `./gitignore` file
2. (recommanded) Create a [private Composer repository](https://getcomposer.org/doc/articles/handling-private-packages-with-satis.md) to host your plugin


## Logs

wp-cubi comes with [inpsyde/wonolog](https://github.com/inpsyde/Wonolog), which allows to log anything with a single line of code, and automatically writes logs for multiple events like PHP errors, DB errors, HTTP API errors, `wp_mail()` errors, and 404 errors.

Basic configuration is possible in wp-cubi `./config/application.php` and `./config/environments/` files, where you can change the maximum number of log files and the log level.

For advanced configuration (adding channels or handlers), you can edit `./web/app/mu-modules/00-wp-cubi-wonolog.php` (see [inpsyde/wonolog documentation](https://inpsyde.github.io/Wonolog/) and [monolog documentation](https://github.com/Seldaek/monolog/tree/master/doc))
