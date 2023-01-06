<?php

namespace Globalis\WP\WPUnhooked;

add_action('admin_menu', function () {
    remove_submenu_page('tools.php', 'export.php');
});

add_action('admin_init', function () {
        global $pagenow;

    if ("export.php" === $pagenow) {
        wp_safe_redirect(admin_url());
        exit;
    }
});
