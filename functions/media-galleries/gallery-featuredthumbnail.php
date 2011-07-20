<?php
/**
 * EMBED CHECK
 */
function embed_anythingslider() {
        if (function_exists('show_featured_thumbnail'))
            show_featured_thumbnail();
}
add_action('fdt_show_media_galleries', 'embed_anythingslider');

/**
 * SHOWS THE FEATURED THUMBNAIL, OTHERWISE DISPLAYS FIRST IMAGE
 *
 * @TODO ADD SUPPORT FOR MEDIA SIZES
 */
function show_featured_thumbnail() {
    global $post;

	$meta = get_post_meta($post->ID, THEMECUSTOMMETAKEY, true);

	if( $meta["gallery_type"] == "featuredthumbnail" ):
        $featured_image = get_the_post_thumbnail($post->ID, 'medium', array('class' => 'none'));

        if ($featured_image) :
            echo '<div class="featured-image">' . $featured_image . "</div>";
        else :
            echo '<div class="first-image">' . get_first_image($post->ID, 'medium') . "</div>";
        endif;
    endif;

}

