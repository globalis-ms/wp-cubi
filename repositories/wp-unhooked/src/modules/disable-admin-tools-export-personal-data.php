<?php

namespace Globalis\WP\WPUnhooked;

add_action('admin_menu', function () {
    remove_submenu_page('tools.php', 'export-personal-data.php');
});

add_action('admin_init', function () {
        global $pagenow;

    if ("export-personal-data.php" === $pagenow) {
        wp_safe_redirect(admin_url());
        exit;
    }
});
