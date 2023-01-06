<?php

namespace Globalis\WP\WPUnhooked;

// @see https://github.com/globalis-ms/wp-cubi/blob/1.4.0/web/app/mu-modules/00-wp-cubi-core-mu/src/10-security-rest-api.php
// @see https://github.com/roots/soil/blob/main/src/Modules/DisableRestApiModule.php
// @see https://github.com/vincentorback/clean-wordpress-admin/blob/master/rest-api.php

remove_action('init', 'rest_api_init');
remove_action('parse_request', 'rest_api_loaded');
remove_action('auth_cookie_malformed', 'rest_cookie_collect_status');
remove_action('auth_cookie_expired', 'rest_cookie_collect_status');
remove_action('auth_cookie_bad_username', 'rest_cookie_collect_status');
remove_action('auth_cookie_bad_hash', 'rest_cookie_collect_status');
remove_action('auth_cookie_valid', 'rest_cookie_collect_status');
remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('template_redirect', 'rest_output_link_header', 11);
add_filter('rest_enabled', '__return_false');
add_filter('rest_jsonp_enabled', '__return_false');
