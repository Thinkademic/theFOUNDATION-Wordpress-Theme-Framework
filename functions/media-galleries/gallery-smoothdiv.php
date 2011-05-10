<?php


/**************************************************************
 SMOOTHDIV - TEMPLATE TAG
**************************************************************/
function show_smoothscroller() {
	global $post;
	
	$meta = get_post_meta($post->ID, THEMECUSTOMMETAKEY, true);

	if( $meta["gallery_type"] == "smoothdiv" ):
				
		$atts = postmeta_gallery_array();		
		echo get_smoothscroller( $atts );
	endif;
	
}

/**************************************************************
 SMOOTHDIV - SHORT CODE
**************************************************************/
add_shortcode('smoothscrollergallery', 'smoothscroller_shortcodehandler');
function smoothscroller_shortcodehandler($atts, $content = null) {

	$atts = shortcode_atts(array(
		'imagesize' => 'thumbnail',
		'orderby' => 'menu_order'
	), $atts);

	$gallery = smoothscroller( $atts );
	return $gallery.$content;
}

/**************************************************************
 SMOOTHDIV - ECHO FUNCTION
**************************************************************/
function smoothscroller( $atts = null ) {
	echo get_smoothscroller( $atts);
}

/**************************************************************
 SMOOTHDIV - XHTML WRAPPER FUNCTION
**************************************************************/
function get_smoothscroller( $atts = null ) {
	global $wp_query, $post, $paged, $post_count;

	$defaults = array (
		'imagesize' => 'thumbnail'		
	);
	$atts = wp_parse_args( $atts, $defaults );
	extract( $atts, EXTR_SKIP );
	

	$content =  
	'
	<div class="smooth_'.$imagesize.'">
	<div id="makeMeScrollable">
		<div class="scrollingHotSpotLeft"></div>
		<div class="scrollingHotSpotRight"></div>
		<div class="scrollWrapper">
			<div class="scrollableArea">
	';
	
	$content .= smoothscroller_extractMedia($atts);

	$content .=
	'	
			</div>	
		</div>
	</div>
	</div>
	';	

	return $content;
}

/**************************************************************
 SMOOTHDIV
**************************************************************/
function smoothscroller_extractMedia( $atts = null ){
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
			$outputslide .= ''.retrieve_media( $querytype, $imagesize, $hyperlink_target, $hyperlink_enable ).'';			
		endwhile;


		$wp_query = null; $wp_query = $temp;
		wp_reset_query();
		return $outputslide;

}


?>