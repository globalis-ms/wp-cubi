<?php

namespace Globalis\WP\Cubi;

add_action('admin_bar_menu', function () {
    remove_action('admin_bar_menu', 'wp_admin_bar_search_menu', 4);
    remove_action('admin_bar_menu', 'wp_admin_bar_recovery_mode_menu', 8);
    remove_action('admin_bar_menu', 'wp_admin_bar_sidebar_toggle', 0);
    remove_action('admin_bar_menu', 'wp_admin_bar_wp_menu', 10);
    remove_action('admin_bar_menu', 'wp_admin_bar_my_sites_menu', 20);
    remove_action('admin_bar_menu', 'wp_admin_bar_edit_site_menu', 40);
    remove_action('admin_bar_menu', 'wp_admin_bar_customize_menu', 40);
    remove_action('admin_bar_menu', 'wp_admin_bar_updates_menu', 50);
    remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    remove_action('admin_bar_menu', 'wp_admin_bar_new_content_menu', 70);
    // remove_action( 'admin_bar_menu', 'wp_admin_bar_my_account_menu', 0 );
    // remove_action( 'admin_bar_menu', 'wp_admin_bar_site_menu', 30 );
    // remove_action( 'admin_bar_menu', 'wp_admin_bar_my_account_item', 7 );
}, 0);

add_action('wp_before_admin_bar_render', function () {
    if (!isset($GLOBALS['wp_admin_bar'])) {
        return;
    }
    $howdy_str_replace = str_replace(' %s', '', __('Howdy, %s'));
    $acc_title = str_replace($howdy_str_replace, '', $GLOBALS['wp_admin_bar']->get_node('my-account')->title);
    $GLOBALS['wp_admin_bar']->add_node(['id' => 'my-account', 'title' => $acc_title]);
});

add_action('admin_init', function () {
    remove_action('admin_print_scripts-index.php', 'wp_localize_community_events');
});

add_filter('pre_option_intervention_admin', '__return_empty_array');

add_filter('sober/intervention/return', function ($path) {
    return __DIR__ . '/20-clean-wp-admin-intervention.php';
});
