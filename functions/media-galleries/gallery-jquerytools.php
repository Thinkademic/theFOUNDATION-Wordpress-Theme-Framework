<?php
/**
 *  REGISTER SCRIPTS FOR jquerytools
 */
function jquerytools_register_script()
{
    $src = get_stylesheet_directory_uri();

    wp_register_script('jquerytoolsgallery', 'http://cdn.jquerytools.org/1.2.5/all/jquery.tools.min.js', array('jquery'), '2.99', false);
}

add_action('template_redirect', 'jquerytools_register_script');


/**
 * REGISTER STYLE FOR JYCLE
 *
 */
function jquerytools_register_style()
{
    global $posts;

    wp_register_style('jquerytools', get_stylesheet_directory_uri() . '/css/media-galleries/' . 'jquerytools.css');

    // FIND THE CURRENT TEMPLATE AND CHECK IF MEDIA GALLERIES ARE ENABLED
    $current_template = thefdt_get_current_template();
    $content_display = of_get_option($current_template . "_content", array(
                                                                          'show_mediagalleries' => false
                                                                     )
    );

    foreach ($posts as $post) {
        $meta = get_post_meta($post->ID, THEMECUSTOMMETAKEY, true);

        if ($meta["gallery_type"] == "jquerytoolsgallery" && $content_display['show_mediagalleries']):
            wp_enqueue_style('jquerytools');
        endif;
    }
}


?>