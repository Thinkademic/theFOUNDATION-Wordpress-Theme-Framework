<?php


/**************************************************************
 ANYTHING SLIDER - TEMPLATE TAG
**************************************************************/
function show_anythingslider() {
	global $post;
	
	$meta = get_post_meta($post->ID, THEMECUSTOMMETAKEY, true);

	if( $meta["gallery_type"] == "anythingslider" ):
		$atts = postmeta_gallery_array();			
		echo get_anythingslider( $atts );
	endif;
	
}

/**************************************************************
 ANYTHING SLIDER - SHORTCODE FUNCTION
**************************************************************/
add_shortcode('anythingslidergallery', 'anythingslider_shortcodehandler');
function anythingslider_shortcodehandler($atts, $content = null) {

	$atts = shortcode_atts(array(
		'imagesize' => 'thumbnail',
		'orderby' => 'menu_order'
	), $atts);

	$gallery = get_anythingslider( $atts);
	return $gallery.$content;
}


/**************************************************************
 ANYTHING SLIDER - ECHO FUNCTION
**************************************************************/
function anythingslider( $atts = null ){
	echo get_anythingslider( $atts );
}


/**************************************************************
 ANYTHING SLIDER - XHTML WRAPPER FUNCTION
**************************************************************/
function get_anythingslider( $atts = null ) {
	global $wp_query, $post, $paged, $post_count;

	$content = 
	'
        <div class="thewrapper_anythingslider">        
			<div class="anything_wrapper">
				<ul id="slider">
	';
		$content .= anythingslider_extractMedia($atts);
		$content .=
	'
				</ul>
			</div>
        </div>		
	';	

	return $content;
}

/**************************************************************
 ANYTHING SLIDER
**************************************************************/
function anythingslider_extractMedia( $atts = null ){
		global $wp_query, $post, $paged, $post_count;

		$defaults = array (
			'targetid' => $post->ID,
			'querytype' => 'attachment',		
			'imagesize' => 'thumbnail',
			'retrieve_content' => false,
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

		
		$headline_settings = array (
			'format' => 'h3',
			'use_image' => false,
			'use_span' => false,
			'imagesize' => $imagesize,
			'imagenumber' => 0,
			'enable_title' => $enable_title,
			'hyperlink_enable' => $hyperlink_enable,
			'hyperlink_target' => $hyperlink_target
		);
		
		// Image Hyperlink Check
		if($hyperlink_placement != 'image' && $hyperlink_enable == true) {
			$hyperlink_enable = false;		
		}
		
		while ($wp_query->have_posts()) : $wp_query->the_post();
	
			$image = retrieve_media( $querytype, $imagesize, $hyperlink_target, $hyperlink_enable );
			$title = retrieve_headline($headline_settings);
			$content =  retrieve_content($retrieve_content);
			
			$image = xtag( 'div', $image, "class=as-image" );
			$title = xtag( 'div', $title, "class=as-title" );
			$content = xtag( 'div', $content, "class=as-content" );
			
			$box = xtag( 'div', $title.$content, "class=as-box" );
			
			$slidereturn .= 
			"
				<li>
					$image
					$box
				</li>
			";
		endwhile;

	
		
		$wp_query = null; $wp_query = $temp;
		wp_reset_query();
		return $slidereturn;

}
?>