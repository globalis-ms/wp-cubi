<?php

namespace Globalis\WP\WPUnhooked;

add_action('admin_head-index.php', function () {
    get_current_screen()->remove_help_tabs();
});
