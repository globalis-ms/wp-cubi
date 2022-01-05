<?php

namespace Globalis\WP\Cubi;

if (!defined('WP_CUBI_DISALLOW_WEAK_PASSWORDS') || !WP_CUBI_DISALLOW_WEAK_PASSWORDS) {
    return;
}

add_filter('admin_head', __NAMESPACE__ . '\\hide_confirm_use_of_weak_password', 10);
add_filter('login_head', __NAMESPACE__ . '\\hide_confirm_use_of_weak_password', 10);

function hide_confirm_use_of_weak_password()
{
    ?><style type="text/css">.pw-weak { display: none !important;}</style><?php
}
