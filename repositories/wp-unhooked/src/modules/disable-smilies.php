<?php

namespace Globalis\WP\WPUnhooked;

add_filter('pre_option_use_smilies', '__return_zero');
remove_action('init', 'smilies_init', 5);
remove_filter('the_content', 'convert_smilies', 20);
