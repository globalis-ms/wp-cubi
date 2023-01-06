<?php

namespace Globalis\WP\WPUnhooked;

remove_filter('wp_title', 'capital_P_dangit', 11);
remove_filter('the_title', 'capital_P_dangit', 11);
remove_filter('the_content', 'capital_P_dangit', 11);
remove_filter('widget_text_content', 'capital_P_dangit', 11);
remove_filter('comment_text', 'capital_P_dangit', 31);
