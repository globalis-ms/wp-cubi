<?php

namespace Globalis\WP\WPUnhooked;

add_action('admin_menu', function () {
    remove_submenu_page('tools.php', 'tools.php');
});

add_action('admin_init', function () {
        global $pagenow;

    if ("tools.php" === $pagenow) {
        wp_safe_redirect(admin_url());
        exit;
    }
});
