<?php

/**************************************************************
 NIVOSLIDER - TEMPLATE TAG
**************************************************************/
function show_nivoslider() {
	global $post;
	
	$meta = get_post_meta($post->ID, THEMECUSTOMMETAKEY, true);

	if( $meta["gallery_type"] == "nivoslider" ):	
		$atts = postmeta_gallery_array();			
		echo nivoslider( $atts );		
	endif;
}

/**************************************************************
 NIVOSLIDER - SHORT CODE
**************************************************************/
add_shortcode('nivoslider', 'nivoslider_shortcodehandler');

function nivoslider_shortcodehandler($atts, $content = null) {

	$atts = shortcode_atts(array(
		"imagesize" => 'thumbnail',
		"orderby" => 'menu_order'
	), $atts);

	$gallery = get_nivoslider( $atts );
	return $gallery.$content;
}



/**************************************************************
 NIVOSLIDER - ECHO FUNCTION
**************************************************************/
function nivoslider( $atts = null ) {
	echo get_nivoslider( $atts );
}


/**************************************************************
 NIVOSLIDER - XHMTL WRAPPER FUNCTION
**************************************************************/
function get_nivoslider( $atts = null ) {
		global $wp_query, $post, $paged;

		$return = "\n".'<div class="nivoslider">';
			$return .= nivoslider_extractMedia( $atts );
		$return .= "</div>";

		return $return;
}

/**************************************************************
 NIVOSLIDER
**************************************************************/
function nivoslider_extractMedia( $atts = null ) {
		global $wp_query, $post, $paged, $post_count;

		$defaults = array (
			'targetid' => $post->ID,
			'querytype' => 'attachment',
			'imagesize' => 'medium',
			'orderby' => 'menu_order'
		);
		$atts = wp_parse_args( $atts, $defaults );					
		extract( $atts, EXTR_SKIP );
		$query_args = build_query_array($atts);		
		
		$temp = $wp_query;
		$wp_query= null;	

		$wp_query = new WP_Query();
		$wp_query->query($query_args);

		while ($wp_query->have_posts()) : $wp_query->the_post();	
			$slidereturn .= 
			'
				'.retrieve_media( $querytype, $imagesize ).'			
			';
		endwhile;

		$wp_query = null; $wp_query = $temp;
		wp_reset_query();
		return $slidereturn;

}



?>