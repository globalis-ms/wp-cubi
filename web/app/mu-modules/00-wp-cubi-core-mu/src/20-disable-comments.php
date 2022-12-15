<?php

namespace Globalis\WP\Cubi;

if (defined('WP_CUBI_ENABLE_COMMENTS') && WP_CUBI_ENABLE_COMMENTS) {
    return;
}

add_filter('wp_count_comments', function ($default) {
    return (object) [
        'approved' => 0,
        'awaiting_moderation' => 0,
        'moderated' => 0,
        'spam' => 0,
        'trash' => 0,
        'post-trashed' => 0,
        'total_comments' => 0,
        'all' => 0,
    ];
});

class DisableComments
{
    /**
     * Remove
     */
    public static function init()
    {
        $self = new self();

        remove_action('sanitize_comment_cookies', 'sanitize_comment_cookies');
        add_filter('rest_endpoints', [$self, 'filterRestEndpoints']);
        add_action('wp_loaded', [$self, 'removePostTypesSupport']);
        add_action('admin_init', [$self, 'adminMenuRedirect']);
        add_action('admin_head-index.php', [$self, 'dashboard']);
        add_filter('comments_open', '__return_false', 10, 1);
        add_filter('pings_open', '__return_false', 10, 1);
        add_filter('post_comments_feed_link', '__return_false', 10, 1);
        add_filter('comments_link_feed', '__return_false', 10, 1);
        add_filter('comment_link', '__return_false', 10, 1);
        add_filter('get_comments_number', '__return_false', 10, 2);
        add_filter('feed_links_show_comments_feed', '__return_false');
        add_action('wp_enqueue_scripts', function () {
            wp_deregister_script('comment-reply');
        }, 99);
        remove_action('wp_head', 'feed_links_extra', 3);
        add_action('wp_before_admin_bar_render', function () {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('comments');
        });

        add_action('admin_menu', function () {
            remove_menu_page('edit-comments.php');
        });
    }

    /**
     * Disable support for comments and trackbacks in post types
     */
    public function removePostTypesSupport()
    {
        $post_types = get_post_types();

        foreach ($post_types as $post_type) {
            if (post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    }

    /**
     * Redirect any user trying to access comments page
     */
    public function adminMenuRedirect()
    {
        global $pagenow;

        if ($pagenow === 'edit-comments.php') {
            wp_safe_redirect(admin_url());
            exit;
        }
    }

    /**
     * Remove the comments endpoint for the REST API
     */
    public function filterRestEndpoints($endpoints)
    {
        unset($endpoints['comments']);
        return $endpoints;
    }

    /**
     * Dashboard
     */
    public function dashboard()
    {
        echo '
        <style>
            #dashboard_right_now .comment-count,
            #dashboard_right_now .comment-mod-count,
            #latest-comments,
            #welcome-panel .welcome-comments {
                display: none !important;
            }
        </style>';
    }
}

DisableComments::init();
