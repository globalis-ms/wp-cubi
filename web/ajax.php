<?php

function fix_ajax_server_globals_for_wp()
{
    $vars = [
        "REQUEST_URI",
        "SCRIPT_NAME",
        "SCRIPT_FILENAME",
        "PHP_SELF",
    ];

    foreach ($vars as $key) {
        if (isset($_SERVER[$key])) {
            $_SERVER[$key] = str_replace('/ajax.php', '/wpcb/wp-admin/admin-ajax.php', $_SERVER[$key]);
        }
    }
}

fix_ajax_server_globals_for_wp();

include __DIR__ . '/wpcb/wp-admin/admin-ajax.php';
