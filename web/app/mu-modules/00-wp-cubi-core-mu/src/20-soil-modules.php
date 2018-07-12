<?php

namespace Globalis\WP\Cubi;

add_action('after_setup_theme', function () {
    add_theme_support('soil-relative-urls');
    add_theme_support('soil-clean-up');
    add_theme_support('soil-nice-search');
    add_theme_support('soil-disable-trackbacks');
    add_theme_support('soil-jquery-cdn');
    //add_theme_support('soil-disable-asset-versioning');
    //add_theme_support('soil-js-to-footer');
    //add_theme_support('soil-nav-walker');
    //add_theme_support('soil-google-analytics', 'UA-XXXXX-Y');
}, 10);
