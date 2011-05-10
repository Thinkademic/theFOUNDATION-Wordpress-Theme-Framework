<?php

/**************************************************************
 FOUNDATION GALLERY - TEMPLATE TAG
**************************************************************/
function show_foundation_gallery() {
}


/**************************************************************
 FOUNDATION GALLERY - SHORT CODE
**************************************************************/
add_shortcode('gallery', 'foundation_gallery_shortcodehandler');
function foundation_gallery_shortcodehandler($atts, $content = null) {
	global $post;

	$atts = shortcode_atts(array(
		'querytype' => 'attachment',
		'targetid' => $post->ID,
		'imagesize' => 'medium',
		'thumbnavsize' => 'minimedium',			
		'enablethumbpreview' => true,
		'enablenextprev' => false,
		'orderby' => 'menu_order'	
	), $atts);

	$gallery = get_foundation_gallery( $atts );
	return $gallery.$content;
}

/**************************************************************
 FOUNDATION GALLERY - ECHO FUNCTION
**************************************************************/
function foundation_gallery( $atts = null) {
	global $post;
	echo get_foundation_gallery( $atts);
}

/**************************************************************
 FOUNDATION GALLERY - XHTML WRAPPER FUNCTION
**************************************************************/
function get_foundation_gallery( $atts = null ) {
		global $wp_query, $post, $paged;
	
			$return = foundation_gallery_extractMedia( $atts );

		return $return;
}

/**************************************************************
 THEFOUNDATION GALLERY
**************************************************************/
function foundation_gallery_extractMedia( $atts = null ){
		global $wp_query, $post, $paged, $post_count;

		$defaults = array (
			'targetid' => $post->ID,
			'querytype' => 'attachment',		
			'imagesize' => 'thumbnail',
			'enable_title' => false,
			'hyperlink_target' => 'linktoself',
			'hyperlink_enable' => false,
			'hyperlink_placement' => 'title'			
		);
		$atts = wp_parse_args( $atts, $defaults );
		extract( $atts, EXTR_SKIP );			
		$query_args = build_query_array($atts);	
		
		$temp = $wp_query;
		$wp_query= null;	

		$wp_query = new WP_Query();
		$wp_query->query($query_args);
					
				
		while ($wp_query->have_posts()) : $wp_query->the_post();
			$image = retrieve_media( $querytype, $imagesize, $hyperlink_target, $hyperlink_enable );			
			$caption = retrieve_content('post_excerpt');
			$caption = xtag( 'div', $caption, 'class=wp-caption');
			
			$outputslide .= $image.$caption;
		endwhile;

		$outputslide = xtag( 'div', $outputslide, "class=thefoundation-gallery");

		$wp_query = null; $wp_query = $temp;
		wp_reset_query();
		return $outputslide;

}

?>