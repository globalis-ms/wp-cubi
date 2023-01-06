<?php

namespace Globalis\WP\WPUnhooked;

add_action('admin_menu', function () {
    remove_submenu_page('tools.php', 'import.php');
});

add_action('admin_init', function () {
        global $pagenow;

    if ("import.php" === $pagenow) {
        wp_safe_redirect(admin_url());
        exit;
    }
});
