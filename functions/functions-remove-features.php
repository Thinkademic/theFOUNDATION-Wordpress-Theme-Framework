<?php
/**
 * functions/functions-remove-features.php
 *
 * CODE TO REMOVE WORDPRESS DEFAULT FEATURES
 */


/**
 *
 * REMOVE ADMIN MENUS
 *
 */
function remove_admin_menus()
{
    remove_menu_page('edit.php'); // Posts
    remove_menu_page('upload.php'); // Media
    remove_menu_page('link-manager.php'); // Links
    remove_menu_page('edit-comments.php'); // Comments
    remove_menu_page('edit.php?post_type=page'); // Pages
    remove_menu_page('plugins.php'); // Plugins
    remove_menu_page('themes.php'); // Appearance
    remove_menu_page('users.php'); // Users
    remove_menu_page('tools.php'); // Tools
    remove_menu_page('options-general.php'); // Settings
}

#add_action('admin_menu', 'remove_admin_menus');


/**
 * REMOVE META BOXES FROM DEFAULT POSTS SCREEN
 */
function remove_default_post_screen_metaboxes()
{
    remove_meta_box('postcustom', 'post', 'normal'); // Custom Fields Metabox
    remove_meta_box('postexcerpt', 'post', 'normal'); // Excerpt Metabox
    remove_meta_box('commentstatusdiv', 'post', 'normal'); // Comments Metabox
    remove_meta_box('trackbacksdiv', 'post', 'normal'); // Talkback Metabox
    remove_meta_box('slugdiv', 'post', 'normal'); // Slug Metabox
    remove_meta_box('authordiv', 'post', 'normal'); // Author Metabox
}

#add_action('admin_menu','remove_default_post_screen_metaboxes');


/**
 * REMOVE HEADER HTML OUTPUT
 */
function remove_header_info()
{
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'start_post_rel_link');
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'adjacent_posts_rel_link');
}

add_action('init', 'remove_header_info');


/**
 * REMOVE DEFAULT WIDGETS
 */
function unregister_default_wp_widgets()
{
    #unregister_widget('WP_Widget_Pages');
    #unregister_widget('WP_Widget_Calendar');
    #unregister_widget('WP_Widget_Archives');
    #unregister_widget('WP_Widget_Links');
    #unregister_widget('WP_Widget_Meta');
    #unregister_widget('WP_Widget_Search');
    #unregister_widget('WP_Widget_Text');
    #unregister_widget('WP_Widget_Categories');
    #unregister_widget('WP_Widget_Recent_Posts');
    #unregister_widget('WP_Widget_Recent_Comments');
    #unregister_widget('WP_Widget_RSS');
    #unregister_widget('WP_Widget_Tag_Cloud');
}

add_action('widgets_init', 'unregister_default_wp_widgets', 1);


/**
 * REMOVE SELF PING
 *
 * @param $links
 */
function no_self_ping(&$links)
{
    $home = home_url();
    foreach ($links as $l => $link)
        if (0 === strpos($link, $home))
            unset($links[$l]);
}

add_action('pre_ping', 'no_self_ping');


/**
 * REMOVE SEARCH FEATURES
 * REF : http://wpengineer.com/1042/disable-wordpress-search/
 *
 * @param $query
 * @param bool $error
 */
# add_action( 'parse_query', 'fb_filter_query' );
# add_filter( 'get_search_form', create_function( '$a', "return null;" ) );
function fb_filter_query($query, $error = true)
{

    if (is_search()) {
        $query->is_search = false;
        $query->query_vars[s] = false;
        $query->query[s] = false;

        # TO ERROR
        if ($error == true)
            $query->is_404 = true;
    }
}


/**************************************************************
LIMiTS REVISIONS
 **************************************************************/
define('WP_POST_REVISIONS', 15); // LIMIITS REVISIONS
define('WP_POST_REVISIONS', true);




