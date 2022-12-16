# [wp-cubi](https://github.com/globalis-ms/wp-cubi/)

[![Build Status](https://travis-ci.com/globalis-ms/wp-cubi.svg?branch=master)](https://travis-ci.com/globalis-ms/wp-cubi)
[![Latest Stable Version](https://poser.pugx.org/globalis/wp-cubi/v/stable)](https://packagist.org/packages/globalis/wp-cubi)
[![License](https://poser.pugx.org/globalis/wp-cubi/license)](https://github.com/globalis-ms/wp-cubi/blob/master/LICENSE.md)

WordPress modern stack for developers

[![wp-cubi](https://github.com/globalis-ms/wp-cubi/raw/master/.resources/wp-cubi-500x175.jpg)](https://github.com/globalis-ms/wp-cubi/)


## Overview

wp-cubi provides a modern stack and project structure to make professional web applications with WordPress.

Built with [Composer](http://getcomposer.org) dependency manager and [Robo](http://robo.li/) task runner.


## Features

* Environment-specific configuration
* Automated `no-index` and mail-trapper on development / staging environments
* Command-line administration with [wp-cli](http://wp-cli.org/)
* Monitoring tools ([query-monitor](https://wordpress.org/plugins/query-monitor/), [wp-crontrol](https://wordpress.org/plugins/wp-crontrol/), [user-switching](https://wordpress.org/plugins/user-switching/), [wp-cubi-admin-bar](https://github.com/globalis-ms/wp-cubi/tree/master/web/app/mu-modules/10-wp-cubi-admin-bar))
* Cleaner wp-admin dashboard with [soberwp/intervention](https://github.com/soberwp/intervention)
* Gitflow integration with Robo commands
* Optimized `.htaccess` generation
* Logging system with [inpsyde/wonolog](https://github.com/inpsyde/Wonolog) and [monolog](https://github.com/Seldaek/monolog)
* Standalone image minification plugin with [globalis/wp-cubi-imagemin](https://github.com/globalis-ms/wp-cubi-imagemin)
* Additional functions with [globalis/wp-cubi-helpers](https://github.com/globalis-ms/wp-cubi-helpers)
* SEO friendly, with [The SEO Framework](https://wordpress.org/plugins/autodescription/) plugin and [roots/soil](https://github.com/roots/soil) DOM optimizations
* Lighter and faster than a default WordPress application, by disabling things we don't use from core
* Comments disabled by default


## Security optimizations

* Separated web root folder
* `.htaccess` security directives
* Deactivation of REST API and `xmlrpc.php` unless explicitly activated
* Better password encryption with [roots/wp-password-bcrypt](https://github.com/roots/wp-password-bcrypt)
* Anti brute-force protection on `wp-login.php` with [Limit Login Attempts Reloaded](https://wordpress.org/plugins/limit-login-attempts-reloaded/)


## Requirements

* [PHP](http://php.net/) 8.0 (work in progress: PHP 8.1, PHP 8.2)
* [Composer](http://getcomposer.org)
* [Git](https://git-scm.com/)


## Installation

1. Create a new project: `composer create-project --remove-vcs globalis/wp-cubi your-project && cd your-project`
2. Run installation command and answer the questions: `./vendor/bin/robo install --setup-wordpress`
3. Access your new site administration: `/wpcb/wp-admin/`


## Configuration

* Login page logo: Replace `./web/logo.png` with your application logo (or edit [`00-wp-cubi-core-mu/20-wp-login.php`](https://github.com/globalis-ms/wp-cubi/blob/master/web/app/mu-modules/00-wp-cubi-core-mu/src/20-wp-login.php))
* If your application use a SMTP server for outgoing emails, configure it in `config/local.php`
* Image minification: Configure [globalis/wp-cubi-imagemin](https://github.com/globalis-ms/wp-cubi-imagemin) to enable a meaningfull level of uploads / image minification


## Commands

### wp-cli

* `./vendor/bin/wp <command>` (see [complete list](https://developer.wordpress.org/cli/commands/))

### Coding standards

* `./vendor/bin/phpcs [directory]` : Check coding standards (see [`./phpcs.xml`](https://github.com/globalis-ms/wp-cubi/blob/master/phpcs.xml))
* `./vendor/bin/phpcbf [directory]` : Fix coding standards auto-fixable violations

### Robo

* `./vendor/bin/robo install [--setup-wordpress]`
* `./vendor/bin/robo configure`
* `./vendor/bin/robo build`
* `./vendor/bin/robo build:composer`
* `./vendor/bin/robo build:config`
* `./vendor/bin/robo build:htaccess`
* `./vendor/bin/robo wp:language-install [<language>] [--activate]`
* `./vendor/bin/robo wp:language-update [<language>] [--activate]`
* `./vendor/bin/robo wp:update-timezone`
* `./vendor/bin/robo feature:start <feature-name>`
* `./vendor/bin/robo feature:finish <feature-name>`
* `./vendor/bin/robo hotfix:start [--semversion=<version>]`
* `./vendor/bin/robo hotfix:finish [--semversion=<version>]`
* `./vendor/bin/robo release:start [--semversion=<version>]`
* `./vendor/bin/robo release:finish [--semversion=<version>]`
* `./vendor/bin/robo deploy <environment> <version> [--ignore-assets] [--ignore-composer]`
* `./vendor/bin/robo deploy:setup <environment>`
* `./vendor/bin/robo media:dump <environment> [--delete]`
* `./vendor/bin/robo media:push <environment> [--delete]`


## WordPress plugins

wp-cubi handles WordPress plugin dependencies (including [wordpress.org](https://wordpress.org/) plugins) through Composer.

If you want to use plugins that are not available through [wordpress.org](https://wordpress.org/) or a public Composer repository, you have two options:

1. **Simplest:** Manually add the plugin in your `./web/app/modules/` directory, then whitelist it in your `./gitignore` file
2. **Recommanded:** Create a [private Composer repository](https://getcomposer.org/doc/articles/handling-private-packages-with-satis.md) to host your plugin


## Logs

wp-cubi comes with [inpsyde/wonolog](https://github.com/inpsyde/Wonolog), which allows to log anything with a single line of code, and automatically writes logs for multiple events like PHP errors, DB errors, HTTP API errors, `wp_mail()` errors, and 404 errors.

Basic configuration is possible in wp-cubi [`./config/application.php`](https://github.com/globalis-ms/wp-cubi/blob/master/config/application.php) and [`./config/environments/`](https://github.com/globalis-ms/wp-cubi/tree/master/config/environments) files, where you can change the maximum number of log files and the log level.

For advanced configuration (adding channels or handlers), you can edit [`./web/app/mu-modules/00-wp-cubi-core-mu/src/00-wonolog.php`](https://github.com/globalis-ms/wp-cubi/blob/master/web/app/mu-modules/00-wp-cubi-core-mu/src/00-wonolog.php) (see [inpsyde/wonolog documentation](https://inpsyde.github.io/Wonolog/) and [monolog documentation](https://github.com/Seldaek/monolog/tree/master/doc))


## Deploys

wp-cubi provides a basic deploy command `./vendor/bin/robo deploy` that builds the application and deploys it with `rsync`.

You can build your own deploy method using [Deployer](https://deployer.org/), [Capistrano](https://capistranorb.com/) or any other tool by editing [`./RoboFile.php`](https://github.com/globalis-ms/wp-cubi/blob/master/RoboFile.php).


## Block Editor (previously known as Gutenberg)

The Block Editor, existing since WordPress version 5.0 is disabled by default. If you want to use it, edit your `./config/application.php` file, and turn constants `WP_CUBI_ENABLE_BLOCK_EDITOR` and `WP_CUBI_ENABLE_REST_API` to `true`.
