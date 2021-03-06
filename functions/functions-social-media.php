<?php
/****
 * functions/functions-social-media.php
 *
 * A SET OF FUNCTION TO HANDLE SOCIAL MEDIA INTEGRATION
 */

/**
 * ADD TO OPTIONS FRAMEWORK
 *
 * @param $options
 */
function social_med_box_count_filter($options)
{

    $add = array(
        'social_media_box_count' => 'Social Media Box Count'
    );

    $merged = array_merge($options, $add);
    return $merged;

}
if( of_get_option('enable_social_media_box_count', false) != false )
    add_filter('build_option_meta_array', social_med_box_count_filter);


/*
* ECHO SOCIAL MEDIA BOX COUNTS
*
* BOX COUNT STYLE SOCIAL MEDIA BUTTONS ARE SQUARE STYLE
* BUTTONS THAT SHOW A NUMERIC COUNT OF CLICKS USED
* TO PROMOTE A LINK ON A SOCIAL NETWORK SITE
*/
function thefdt_social_media_box_count()
{
    echo get_social_media_box_count_meta();
}


/*
* RETRIEVED ALL SOCIAL MEDIA BOX COUNTS
*
* SUPPORTED NETWORKS
* FACEBOOK | TWITTER | DIGG | GOOGLE PLUS ONE
*
* @TODO: MAKE IT SORTABLE BASED ON AN ARRAY
*/
function get_social_media_box_count_meta($networks = null)
{
    $networks = array(
        'facebook' => 'facebook',
        'twitter' => 'twitter',
        'google_plus_one' => 'google_plus_one'
        // 'digg' => 'digg'
    );

    $box_count = "";
    foreach ($networks as $name => $value) {
        $function_name = "get_" . $name . "_box_count";
        $box_count .= $function_name();
    }

    // DOUBLE WRAP WITH DIVS AND CLASSES FOR STYLING
    $box_count = xtag("div", $box_count, "class=social_media_box_count");
    $box_count = xtag("div", $box_count, "class=social_media_box_count_wrapper");

    return $box_count;
}

/*
* FACEBOOK BOX COUNT
*/
function get_facebook_box_count()
{

    $permalink = urlencode(get_permalink($post->ID));
    $facebook = '<iframe src="http://www.facebook.com/plugins/like.php?href=' . $permalink . '&amp;layout=box_count&amp;show_faces=false&amp;width=60&amp;action=like&amp;colorscheme=light&amp;font=arial" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:60px; height:65px; padding:0 4px;"></iframe>';
    $facebook = xtag("div", $facebook, "class=facebook_box_count");

    return $facebook;

}

/*
 * TWITTER BOX COUNT
 */
function get_twitter_box_count()
{
    global $post;

    $permalink = get_permalink($post->ID);
    $post_title = get_the_title($post->ID);
    $short_link = wp_get_shortlink($post->ID);
    $username = of_get_option('social_media_twitter_profile', '');
    $text = "I'd sport it...would you?" . $shortlink;

    return '
			<div class="twitter_box_count">
			  <div class="twiter_box_shield"></div>
			  <a href="http://twitter.com/share" class="twitter-share-button"
				 data-via="' . $username . '"
				 data-text="' . $text . '"
				 data-url="' . $short_link . '"
				 data-counturl=' . $permalink . '"
				 data-count="vertical">Tweet</a>
			</div>	
	';

}

/*
 * DIGG BOX COUNT
 */
function get_digg_box_count()
{
    global $post;

    $permalink = get_permalink($post->ID);
    $post_title = get_the_title($post->ID);
    $short_link = wp_get_shortlink($post->ID);
    $username = of_get_option('social_media_twitter_profile', '');
    $text = "I'd sport it...would you?" . $shortlink;

    return '<a class="DiggThisButton DiggMedium" href="' . $permalink . '"></a>';

}


/*
 * GOOGLE PLUS ONE BOX COUNT
 */
function get_google_plus_one_box_count()
{
    global $post;

    $permalink = get_permalink($post->ID);
    $post_title = get_the_title($post->ID);
    $short_link = wp_get_shortlink($post->ID);
    $username = of_get_option('social_media_twitter_profile', '');
    $text = "I'd sport it...would you?" . $shortlink;


    return '
    <div class="google_box_count">
        <g:plusone size="tall" href="'.$permalink.'"></g:plusone>
    </div>';
}



/*
 * TWITTER ENQUEUE SCRIPT
 */
function twitter_box_count_js_enqueue()
{
    wp_register_script('twitter', 'http://platform.twitter.com/widgets.js', false, '1');
    if( of_get_option( 'enable_social_media_box_count', false)) :
        wp_enqueue_script('twitter');
    endif;
}

add_action('wp_head', 'twitter_box_count_js_enqueue', 10);

/*
 * GOOGLE PLUS ONE ENQUEUE SCRIPT
 */
function google_plus_one_js_enqueue()
{
    wp_register_script('googleplusone', 'https://apis.google.com/js/plusone.js', false, '1');
    if( of_get_option( 'enable_social_media_box_count', false)) :
        wp_enqueue_script('googleplusone');
    endif;
}

add_action('wp_head', 'google_plus_one_js_enqueue', 10);

/*
 * DIGG ENQUEUE SCRIPT
 */
function digg_box_count_js_enqueue()
{

if( of_get_option( 'enable_social_media_box_count', false)) :
    print <<<END

/* DIGG BOX COUNT */
(function() {
	var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
	s.type = 'text/javascript';
	s.async = true;
	s.src = 'http://widgets.digg.com/buttons.js';
	s1.parentNode.insertBefore(s, s1);
})();

END;

endif;


}
add_action('fdt_print_dynamic_js', 'digg_box_count_js_enqueue');




?>