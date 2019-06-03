<?php

namespace Globalis\WP\Cubi;

add_action('login_head', function () {
    $logo_url = esc_url(home_url('/logo.png'));
    ?>
    <style type="text/css" media="screen">
        .login h1 { width: 100%; height: 100%; background-image: url('<?= $logo_url ?>'); background-position: center top; background-repeat: no-repeat; background-size: contain; }
        .login h1 a { background-image: none; display: block; width: 100%; min-height: 100px; }
        .login .message { margin-top: 20px; }
    </style>
    <?php
}, 10);

add_filter('login_headerurl', function ($url) {
    return esc_url(home_url('/'));
}, 10, 1);

add_filter('login_headertext', function ($title) {
     return get_bloginfo('name');
}, 10, 1);
