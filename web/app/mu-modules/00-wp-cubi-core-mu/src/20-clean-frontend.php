<?php

namespace Globalis\WP\Cubi;

remove_action('embed_head', 'locale_stylesheet', 30);
remove_action('wp_head', 'locale_stylesheet');
remove_action('wp_loaded', '_custom_header_background_just_in_time');
remove_action('wp_head', '_custom_logo_header_styles');
