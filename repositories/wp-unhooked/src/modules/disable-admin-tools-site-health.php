<?php

namespace Globalis\WP\WPUnhooked;

add_action('admin_menu', function () {
    remove_submenu_page('tools.php', 'site-health.php');
});

add_action('admin_init', function () {
        global $pagenow;

    if ("site-health.php" === $pagenow) {
        wp_safe_redirect(admin_url());
        exit;
    }
});
