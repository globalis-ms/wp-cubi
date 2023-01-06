<?php

use function Globalis\WP\Cubi\add_action;

require_once __DIR__ . "/src/helpers.php";

add_action('muplugins_loaded', function () {

    if (!defined('WP_UNHOOKED_CONFIG') || !is_array(WP_UNHOOKED_CONFIG)) {
        return;
    }

    foreach (WP_UNHOOKED_CONFIG as $module => $load) {
        if ($load) {
            require_once __DIR__ . "/src/modules/" . $module . ".php";
        }
    }
});
