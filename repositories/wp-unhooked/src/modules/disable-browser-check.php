<?php

namespace Globalis\WP\WPUnhooked;

disable_browser_check();

/*
 * Disable dashboard browse-happy requests / widget
 */
function disable_browser_check()
{
    if (is_blog_admin() && is_array($_SERVER) && isset($_SERVER['HTTP_USER_AGENT']) && !empty($_SERVER['HTTP_USER_AGENT'])) {
        add_filter('pre_site_transient_browser_' . md5($_SERVER['HTTP_USER_AGENT']), '__return_empty_array');
    }
}
