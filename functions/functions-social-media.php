<?php
/**
 * SOCIAL MEDIA FUNCTIONS
 */


 
/*
 *	ECHO SOCIAL MEDIA BOX COUNTS
 */
function thefdt_social_media_box_count() {
	echo get_thefdt_social_media_box_count();
}
 
/*
 * RETREIVED ALL SOCIAL MEDIA BOX COUNTS
 */ 
function get_thefdt_social_media_box_count() {
	
	$box_count .= get_facebook_box_count();
	$box_count .= get_twitter_box_count();
	
	$box_count = xtag("div", $box_count, "class=social_media_box_count");
 
	return $box_count;
}
 
 
/*
 * FACEBOOK BOX COUNT
 */
function get_facebook_box_count() {

	$permalink = urlencode(get_permalink($post->ID));

	return '<iframe src="http://www.facebook.com/plugins/like.php?href='.$permalink.'&amp;layout=box_count&amp;show_faces=false&amp;width=60&amp;action=like&amp;colorscheme=light&amp;font=arial" scrolling="no" frameborder="1" allowTransparency="true" style="border:none; overflow:hidden; width:60px; height:65px;"></iframe>';
		
}

/*
 * TWITTER BOX COUNT
 */
function get_twitter_box_count() {
	global $post, $twitter_active;

 	$permalink = urlencode(get_permalink($post->ID));
	$post_title = get_the_title($post->ID);
	$short_link = urlencode(get_permalink($post->ID));
	$username = "twitteruser";
	$text = 'Checking out this page about Tweet Buttons';
	
	if(!$twitter_active) :
	 $script = '<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>';
	 $twitter_active = false;
	else :
	 $twitter_active = true;
	endif;
	
	return '
	
			<div>
			  <a href="http://twitter.com/share" class="twitter-share-button"
				 data-counturl="'.$post_title.'"
				 data-via="'. $username .'"
				 data-text="'. $text .'"
				 data-url="'. $short_link .'"
				 data-counturl='. $permalink .'"
				 data-count="vertical">Tweet</a>
			</div>	
	';
 
}

/*
 * TWITTER ENQUEUE SCRIPT
 */
function twitter_box_count_enqueue(){
	wp_register_script( 'twitter', 'http://platform.twitter.com/widgets.js', false, '1');
	wp_enqueue_script( 'twitter' );
}
add_action('wp_head', 'twitter_box_count_enqueue', 10);



?>