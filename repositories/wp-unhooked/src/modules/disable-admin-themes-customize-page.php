<?php

namespace Globalis\WP\WPUnhooked;

add_action('admin_init', function () {
        global $pagenow;

    if ("customize.php" === $pagenow) {
        wp_safe_redirect(admin_url());
        exit;
    }
});
