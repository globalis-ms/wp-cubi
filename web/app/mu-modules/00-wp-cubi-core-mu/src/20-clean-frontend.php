<?php

namespace Globalis\WP\Cubi;

remove_action('wp_enqueue_scripts', 'wp_common_block_scripts_and_styles');
remove_action('admin_enqueue_scripts', 'wp_common_block_scripts_and_styles');
remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles' );
remove_action('wp_footer', 'wp_enqueue_global_styles', 1);
