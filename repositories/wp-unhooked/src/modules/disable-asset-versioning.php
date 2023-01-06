<?php

namespace Globalis\WP\WPUnhooked;

// @see https://github.com/roots/soil/blob/main/src/Modules/DisableAssetVersioningModule.php

add_filter('script_loader_src', __NAMESPACE__ . '\\disable_assets_versionning');
add_filter('style_loader_src', __NAMESPACE__ . '\\disable_assets_versionning');

function disable_assets_versionning($url)
{
    return $url ? esc_url(remove_query_arg('ver', $url)) : false;
}
