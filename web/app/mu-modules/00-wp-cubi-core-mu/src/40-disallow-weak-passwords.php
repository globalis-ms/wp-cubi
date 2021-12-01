<?php

namespace Globalis\WP\Cubi;

if (!defined('WP_CUBI_DISALLOW_WEAK_PASSWORDS') || !WP_CUBI_DISALLOW_WEAK_PASSWORDS) {
    return;
}

add_filter('admin_head', function () {
    ?><style type="text/css">.pw-weak { display: none !important;}</style><?php
}, 10);