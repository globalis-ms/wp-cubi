<?php

namespace Globalis\WP\WPUnhooked;

add_action('admin_menu', function () {
    remove_submenu_page('themes.php', 'themes.php');
});

add_action('admin_init', function () {
        global $pagenow;

    if ("themes.php" === $pagenow) {
        wp_safe_redirect(admin_url());
        exit;
    }
});
