<?php

/**
 * Plugin Name:         wp-cubi-admin-bar
 * Plugin URI:          https://github.com/globalis-ms/wp-cubi/blob/master/web/app/mu-modules/10-wp-cubi-admin-bar
 * Description:         Add application, system and environment informations to your admin bar
 * Author:              Pierre Dargham, Globalis Media Systems
 * Author URI:          https://www.globalis-ms.com/
 * License:             GPL2
 */

require_once __DIR__ . '/src/AdminBar.php';

new \Globalis\WP\Cubi\AdminBar();
