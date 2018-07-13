<?php

namespace Globalis\WP\Cubi;

add_filter('pre_option_upload_path', function () {
    return WP_UPLOADS_DIR;
});
add_filter('pre_option_upload_url_path', function () {
    return WP_UPLOADS_URL;
});
