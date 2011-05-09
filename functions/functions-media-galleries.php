<?php

/* =============================================
===============================================

	functions.media-galleries.php
	File Version 1.2011.02.10
	
	Compiled by: BaoQuoc Doan
	Website: http://www.foodshelterlove.com

	Note: 
	Functions found in this file produce the xhtml
	for the jquery gallieries that are supported.
	The actually js files are either linked to using
	functiosn-jquery.php or dynamically generated.
	
	SUPPORTED JQUERY GALLERY
	1. [jcyclegallery] [mediagallery]
	2. [anythingslidergallery]
	3. [smoothscrollergallery]
	4. [nivogallery]
	
===============================================
=============================================== */
				
		




		
		
/**************************************************************
 TEMPLATE TAG
 
 DISPLAY SELECTED GALLERY ASSOCIATED WITH 
 POST/PAGE/CUSTOM POST TYPE
**************************************************************/
function show_mediagalleries( $targetid = null ) {
	
	if (function_exists('show_jcyclegallery'))
		show_jcyclegallery();	
	
	if (function_exists('show_anythingslider'))
		show_anythingslider();	
	
	if (function_exists('show_foundation_gallery'))
		show_foundation_gallery();
		
	if (function_exists('show_nivoslider'))
		show_nivoslider();		
}




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






















/**************************************************************
 JCYCLE - TEMPLATE TAG
**************************************************************/
function show_jcyclegallery() {
	global $post;
	
	$meta = get_post_meta($post->ID, THEMECUSTOMMETAKEY, true);
	
	if( $meta["gallery_type"] == "jcyclegallery" ) :
	
		$atts = postmeta_gallery_array();
		echo get_jcyclegallery( $atts);			

	endif;
}

/**************************************************************
 JCYCLE - SHORT CODE
**************************************************************/
add_shortcode('jcyclegallery', 'jcyclegallery_shortcodehandler');
add_shortcode('mediagallery', 'jcyclegallery_shortcodehandler');

function jcyclegallery_shortcodehandler($atts, $content = null) {
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

	$gallery = get_jcyclegallery( $atts );
	return $gallery.$content;
}

/**************************************************************
 JCYCLE - ECHO FUNCTION
**************************************************************/
function jcyclegallery( $atts = null) {
	global $post;
	echo get_jcyclegallery( $atts);
}

/**************************************************************
 JCYCLE - XHTML WRAPPER FUNCTION
**************************************************************/
function get_jcyclegallery( $atts = null ) {
		global $wp_query, $post, $paged;
	
			$return = extractMedia( $atts );

		return $return;
}

/**************************************************************
 JCYCLE - QUERY CALL
**************************************************************/
function extractMedia( $atts ) {
		global $wp_query, $post, $paged, $post_count;
		
		return jcycle_extractMedia( $atts );
}

/**************************************************************
 JCYCLE - QUERY CALL
**************************************************************/
function jcycle_extractMedia( $atts ) {
		global $wp_query, $post, $paged, $post_count;

		$defaults = array (
			'targetid' => $post->ID,
			'querytype' => 'attachment',
			'imagesize' => 'medium',
			'orderby' => 'menu_order',
			'aligment' => '',
			'enablethumbpreview' => false,
			'thumbpreviewposition' => 'bottom',
			'enablenextprev' => false,
			'enable_title' => true,
			'hyperlink_target' => 'linktoself',
			'hyperlink_enable' => false,
			'hyperlink_placement' => 'title',
			'retrieve_content' => ""
		);
		$atts = wp_parse_args( $atts, $defaults );
		extract( $atts, EXTR_SKIP );					
		$query_args = build_query_array($atts);
		
		$temp = $wp_query;
		$wp_query= null;	
		$wp_query = new WP_Query();
		$wp_query->query($query_args);
		
		$query_count = sizeof( $wp_query->posts );


		#	SETTINGS FOR RETRIEVE_HEADLINE()
		$headline_settings = array (
			'format' => 'div',
			'attributes' => 'class=asset_caption',
			'use_image' => false,
			'use_span' => false, 
			'imagesize' => $imagesize,
			'imagenumber' => 0,
			'enable_title' => $enable_title,
			'hyperlink_enable' => $hyperlink_enable,
			'hyperlink_target' => $hyperlink_target
		);	
		
		
		
		#	IMAGE HYPERLINK CHECK
		if($hyperlink_placement != 'image' && $hyperlink_enable == true) {
			$hyperlink_enable = false;		
		}		
		

		
		#	CHECK FOR VIDEO 
		$contains_video = false;
		if (has_video($post->ID)) {
			$video_asset .= "<div class='asset video'>";				
			$video_asset .= retrieve_video($atts);
			$video_asset .= "</div>";
			
			$contains_video = true;			
		}


		
		#	THE LOOP
		$counter = 0;
		while ($wp_query->have_posts()) : $wp_query->the_post();
			$counter++;
			
			if( $counter == 1 && $contains_video == true ) {
				$asset = $video_asset;
			} else {
				$media = retrieve_media( $querytype, $imagesize, $hyperlink_target, $hyperlink_enable );
				$asset_caption = retrieve_headline($headline_settings);
				$asset_content = xtag( "div", retrieve_content($retrieve_content), "class=asset_content" );
				$asset .= "\n"."<div class='asset'>".$media.$asset_caption.$asset_content."</div>";				
			}	
		
			$thumbnav .= '<li><a href="#">'.retrieve_media( $querytype, "mini".$imagesize ).'</a></li>';
		endwhile;
		$output .= "<div class='media_assets'>".$videoassets.$asset."</div>"; 

		

		//	MEDIA CONTROLS
		if( $enablenextprev ) {
			// Show Play Pause on Galleries Image Only Galleries
			if ($contains_video) {
				$playpausoptions = '
				<a href="#" class="pause"><span>Pause</span></a>
				<a href="#" class="play"><span>Play</span></a>			
				';
			}
				
			$mediacontrolxhtml = '
			<div class="media_controls">
				<a href="#" class="prev"><span>Prev</span></a>
				<a href="#" class="next"><span>Next</span></a>
				'.$playpausoptions.'
			</div>
			';
			
			$simplemediacontrolxhtml = '
			<div class="simplemedia_controls">
				<a href="#" class="prev"><span>Prev</span></a><span class="jcyclecounter"></span><a href="#" class="next"><span>Next</span></a>
				'.$playpausoptions.'
			</div>
			';			
		}

		
		
		
		//	THUMBNAIL NAVIGATOR		
		if( $enablethumbpreview == true && $query_count > 1  ) {
		
			if( $thumbpreviewposition == 'left' || $thumbpreviewposition == 'right' ) {

				$thumbnav = "\n".'
				<div class="jcyclethumbs">
					
					<div class="jcyclethumbs_'.$imagesize.'">
						'.$simplemediacontrolxhtml.'
						<ul class="thumbnav">
						'.$thumbnav.'
						</ul>
					</div>
				</div>	
				';	
				
			} else {
				$thumbnav = "\n".'
				<hr /><!-- Needed to fix strange float/height issue -->
				<div id="thumbnavscrollerable">
					<div class="scrollingHotSpotLeft"></div>
					<div class="scrollingHotSpotRight"></div>
					<div class="media_thumbs media_thumbs_'.$imagesize.'">
						<ul class="thumbnav">
						'.$thumbnav.'
						</ul>
					</div>
				</div>	
				';				
			} 
			
		} else {
			$thumbnav = "";
		}
	
	
		//	SHOW MEDIA CONTROL IF THERE IS MORE THEN ONE MEDIA ASSET
		if( $query_count > 1 )
			$output = $mediacontrolxhtml.$output;

		//	THUMBNAIL POSITION	
		if( $thumbpreviewposition == 'bottom' || $thumbpreviewposition == 'right' ) {
			$output = '<div class="galsize_'.$imagesize.'"><div class="media_grouping">'.$output.'</div>'.$thumbnav.'</div>';
		} else {
			$output = '<div class="galsize_'.$imagesize.'">'.$thumbnav.'<div class="media_grouping">'.$output.'</div></div>';
		}
			
		//	WRAP WITH DIVS
		$output = xtag( 'div', $output, 'id='.$post->post_name );								
		$output = xtag( 'div', $output, 'class=jcyclegallery', '', '<hr />');				
					
		$wp_query = null; $wp_query = $temp;
		wp_reset_query();
		return $output;

}







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














/**************************************************************
 FANCYTRANSITIONS - TEMPLATE TAG
**************************************************************/
function show_fancytransitions() {
	global $post;
	
	$meta = get_post_meta($post->ID, THEMECUSTOMMETAKEY, true);

	if( $meta["gallery_type"] == "fancytransitions" ):
			
		$atts = postmeta_gallery_array();			
		echo fancytransitions( $atts );		
	endif;
}

/**************************************************************
 FANCYTRANSITIONS - SHORT CODE
**************************************************************/
add_shortcode('fancytransitions', 'fancytransitions_shortcodehandler');

function fancytransitions_shortcodehandler($atts, $content = null) {

	$atts = shortcode_atts(array(
		"imagesize" => 'thumbnail',
		"orderby" => 'menu_order'
	), $atts);

	$gallery = get_fancytransitions( $atts );
	return $gallery.$content;
}



/**************************************************************
 FANCYTRANSITIONS - ECHO FUNCTION
**************************************************************/
function fancytransitions( $atts = null ) {
	echo get_fancytransitions( $atts );
}


/**************************************************************
 FANCYTRANSITIONS - XHMTL WRAPPER FUNCTION
**************************************************************/
function get_fancytransitions( $atts = null ) {
		global $wp_query, $post, $paged;

		$return = "\n".'<div id="fancytransitions">';
			$return .= fancytransitions_extractMedia( $atts );
		$return .= "</div><hr />";

		return $return;
}

/**************************************************************
 FANCYTRANSITIONS
**************************************************************/
function fancytransitions_extractMedia( $atts = null ) {
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























/**************************************************************
 ORBIT SLIDER - SHORTCODE FUNCTION
**************************************************************/
function show_orbit() {
	global $post;
	
	$meta = get_post_meta($post->ID, THEMECUSTOMMETAKEY, true);

	if( $meta["gallery_type"] == "orbit" ){
		$atts = postmeta_gallery_array();	
		echo orbit( $atts );		
	}

}


/**************************************************************
 ORBIT SLIDER - SHORTCODE FUNCTION
**************************************************************/
add_shortcode('orbitgallery', 'orbit_shortcodehandler');
function orbit_shortcodehandler($atts, $content = null) {

	$atts = shortcode_atts(array(
		'imagesize' => 'thumbnail',
		'orderby' => 'menu_order'
	), $atts);

	$gallery = get_orbit( $atts);
	return $gallery.$content;
}


/**************************************************************
 ORBIT SLIDER - ECHO FUNCTION
**************************************************************/
function orbit( $atts = null ){
	echo get_orbit( $atts );
}


/**************************************************************
 ORBIT SLIDER - XHTML WRAPPER FUNCTION
**************************************************************/
function get_orbit( $atts = null ) {
	global $wp_query, $post, $paged, $post_count;


	$content = orbit_extractMedia($atts);

	return $content;
}

/**************************************************************
 ORBIT SLIDER 

 REFERNCE LINK
 http://www.zurb.com/playground/orbit-jquery-image-slider
**************************************************************/
function orbit_extractMedia( $atts = null ){
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
			
			$title = $post->post_title;
			$content =  retrieve_content($retrieve_content);
			
			$caption = xtag( 'span', $title, "class=orbit-caption&id=".$post->post_name);
			
			$slidereturn .= "
				$image
			";
			
			$captionreturn .= $caption;
			
			
		endwhile;

		$slidereturn = xtag('div', $slidereturn, "id=orbit");
		
		$wp_query = null; $wp_query = $temp;
		wp_reset_query();
		return $slidereturn;

}














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
















/**************************************************************
 ADDS OUR GALLERY OPTION META BOX TO 
 ADMIN EDIT POST/PAGES/CUSTOM POSTTYPES
**************************************************************/
add_action("init", "create_layout_options");
if(!function_exists('create_layout_options')) {
	function create_layout_options() { 
		global $mantone_events; 
		$mantone_events = new create_layout_options(); 
	}
}



/**************************************************************
 UNIVERSAL OPTIONS ARRAY
**************************************************************/
function postmeta_gallery_array( $targetid = null ) {
	global $post;

	if( $targetid == null )	
		$targetid = $post->ID;			
	
	$meta = get_post_meta($targetid, THEMECUSTOMMETAKEY, true);	

	// Default Attribute Values															
	$imagesize = set_default_value($meta["gallery_imagesize"], "thumbnail");
	$alignment = $meta["gallery_align"];
	$orderby = $meta["gallery_orderby"];
	$enablenextprev = $meta["gallery_enablenextprev"];
	$enablethumbpreview = $meta["gallery_thumbnailpreview"];
	$thumbpreviewposition = set_default_value($meta["gallery_thumbpreviewposition"], 'bottom');
	$retrieve_content = $meta["gallery_display_retrieve_content"];
	$autoplay = $meta["gallery_display_autoplay"];
	$source = $meta["gallery_source"];
	$sourceinfo = $meta["gallery_source_textinfo"];
	$enable_title = $meta["gallery_title_enable"];
	$hyperlink_enable = $meta["gallery_hyperlink_enable"];
	$hyperlink_placement = $meta["gallery_hyperlink_placement"];
	$hyperlink_target = $meta["gallery_hyperlink_target"];	
	
	
	$post_type = "attachment";		// FIGURE OUT WHAT KIND OF QUERY WE ARE GOING TO CONSTRUCT
	$querytype = "attachment";		// FIGURE OUT WHAT KIND OF QUERY WE ARE GOING TO CONSTRUCT
	
	if($source == "attachments_bypagename") {
		$querytype = "attachment";
		$post_type = "page";
		$name = $sourceinfo;
		$title = get_page_by_title($name, "OBJECT");
		if( is_null($title) ) {
			$querytype = 'default';
		} else {
			$targetid = $title->ID;		
		}
	}

	if($source == "attachments_bypostname") {
		$querytype = "attachment";
		$post_type = "post";		
		$name = $sourceinfo;
		$title = get_page_by_title($name, "OBJECT", 'post');
		if( is_null($title) ) {
			$querytype = 'default';
		} else {
			$targetid = $title->ID;		
		}	
	}		
	
	if($source == "attachments_byposttype") {
		$querytype = "posttype";
		$post_type = $sourceinfo;		
	}	

	if($source == "attachments_bytagslug") {
		$querytype = "tag";
		$post_type = "post";	
		$tag_slug = $sourceinfo;		

	}	
	
	if($source == "attachments_bycategoryname") {
		$querytype = "category";
		$post_type = "post";	
		$category_name = $sourceinfo;		

	}		
	
			
	// Final Attribute Values
	$atts = array (
		"post_type" => $post_type,
		"category" => $category_name,
		"tag" => $tag_slug,
		"querytype" => $querytype,		
		"targetid" => $targetid,
		"imagesize" => $imagesize,
		"alignment" => $alignment,
		"orderby" => $orderby,
		"retrieve_content" => $retrieve_content,
		"enablenextprev" => $enablenextprev,			
		"enablethumbpreview" => $enablethumbpreview,
		"thumbpreviewposition" => $thumbpreviewposition,
		"enable_title" => $enable_title,
		"hyperlink_enable" => $hyperlink_enable,
		"hyperlink_placement" => $hyperlink_placement,
		"hyperlink_target" => $hyperlink_target
	);	
	
	return $atts;
	
}







	

function gallery_dropdown( $meta_key = "", $customfieldname, $labeldecription ) {

	global $_wp_additional_image_sizes;

	$get_sizes = get_intermediate_image_sizes();
	$custom_sizes = array();

	
	// CREATE ARRAY FOR OUR form_selectbox() FUNCTION
	// MAPPING, ARRAY KEY BECOMES LABEL, ARRAY VALUE MAPS TO OPTION TAG'S VALUE ATTRIBUTE
	// PERHAPS CONSIDER using http://www.php.net/manual/en/function.array-flip.php
	foreach ($get_sizes as $key => $value) {
		
		$size_width = get_option($value.'_size_w');
		$size_height = get_option($value.'_size_h');	
	
		if( ! is_numeric($size_width) )
			$size_width = $_wp_additional_image_sizes[$value]['width'];
		
		if( ! is_numeric($size_height) )
			$size_height = $_wp_additional_image_sizes[$value]['height'];	
		
		$dimensions = " - $size_width (w) x $size_height (h)";
	
		$custom_sizes[$value.$dimensions] = $value;
	}	
	
	
	$default_options = 	array (
		'None' => '',
		'Thumbnail' => 'thumbnail', 
		'Medium' => 'medium',
		'Large' => 'large',
	);
	
	
	$none_options = 	array (
		'None' => ''
	);	
	
	
	$custom_sizes = array_merge($none_options, $custom_sizes);	
	$custom_sizes = apply_filters("intermediate_image_sizes", $custom_sizes);

	
	
	return form_selectbox( $meta_key, $customfieldname, $labeldecription, $default_options, false );
}			
				

								
				
/**************************************************************
	MEDIA GALLERY LAYOUT OPTIONS
**************************************************************/

class create_layout_options {

	# BUILD AN ARRAY TO STORE OUR CUSTOM FIELDS DATA	
	var $meta_key = THEMECUSTOMMETAKEY;
	var $meta_fields = array(
		"gallery_source",
		"gallery_source_textinfo",
		"gallery_type",
		"gallery_location",
		"gallery_imagesize",
		"gallery_orderby",
		"gallery_enablenextprev",
		"gallery_thumbnailpreview",
		"gallery_autoplay",		
		"gallery_title_enable",
		"gallery_display_retrieve_content",
		"gallery_hyperlink_enable",		
		"gallery_hyperlink_placement",
		"gallery_hyperlink_target",
		"gallery_display_autoplay",
		"gallery_thumbpreviewposition",
		"gallery_alignment",
		"gallery_transitiondelay",		
		
		# jcycle specific options
		"jcyclegallery_effect",
		
		# fancy transition specific optinos
		"nivoslider_effect",
		"nivoslider_boxcol",
		"nivoslider_boxrows",
		
		# fancy transition specific optinos
		"fancytransitions_effect",
		"fancytransitions_position",
		"fancytransitions_direction",
		"fancytransitions_delay",
		
		# anythingslider specific options
		'anythingslider_width',
		'anythingslider_height',
		'anythingslider_resizecontents',
		'anythingslider_startpanel',
		'anythingslider_hastags',
		'anythingslider_buildarrows',
		'anythingslider_navigationformatter',
		'anythingslider_fowardtext',
		'anythingslider_backtext',
		'anythingslider_autoplay',
		'anythingslider_startstopped',
		'anythingslider_pauseonhover',
		'anythingslider_resumeonvideoend',
		'anythingslider_stopatend',
		'anythingslider_playrtl',
		'anythingslider_starttext',
		'anythingslider_stoptext',
		'anythingslider_delay',
		'anythingslider_animationTime',
		'easing'

		
	);
	

	# -- Class Definition & Function Calls
	function create_layout_options() {

		// Admin interface init
		add_action("admin_init", array(&$this, "admin_init"));

		// Update Post functions
		add_action("wp_insert_post", array(&$this, "wp_insert_post"), 10, 2);			

	}



	# -- Class Functions
	function admin_init() {
		// Custom meta boxes for Theme Layout Options
		add_meta_box(
			"fslmeta_themelayoutoptions_forpage", 
			"Dynamic Gallery Options", 
			array(&$this, "add_gallerylayoutoptions_forpage"), 
			"page", 
			"side", 
			"low"
		);
		// Custom meta boxes for Theme Layout Options
		add_meta_box(
			"fslmeta_themelayoutoptions_forpage", 
			"Gallery Options", 
			array(&$this, "add_gallerylayoutoptions_forpage"), 
			"post", 
			"side", 
			"low"
		);		

	}
	

	# -- Build the XHMTL boxes for our meta boxes, used by admin_init()
	function add_gallerylayoutoptions_forpage()
	{
			global $post;
			$custom = get_post_custom($post->ID);
			$saved_meta_fields = get_post_meta( $post->ID, THEMECUSTOMMETAKEY, true );	
	

			//	GALLERY TYPE
			$output .= "<p><strong>SET GALLERY PLUGIN</strong></p>";
			$selectoptions = array (
				'None' => '',
				'Jcycle Gallery' => 'jcyclegallery',				
				'Anything Slider' => 'anythingslider',
				'Nivo Slider' => 'nivoslider'
				// 'Fancy Transitions' => 'fancytransitions',
				// 'Orbit' => 'orbit',
			);			
			$output .= form_selectbox( $this->meta_key, 'gallery_type', '', $selectoptions );			
					
			# --- SLIDE SOURCE SOURCE
			$output .= "<p><strong>SET IMAGE ATTACHMENT SOURCE</strong></p>";
			$selectoptions = array (
			   'from this Post/Page' => 'attachment',
			   'from Pages by Title Name' => 'attachments_bypagename',
			   'from Post by Title Name' => 'attachments_bypostname',			   
			   'from Post by Category Name' => 'attachments_bycategoryname',
			   'from Post by Tag Slug' => 'attachments_bytagslug',			   
			   'from Custom Post Type' => 'attachments_byposttype'
			);			
			$output .= form_selectbox( $this->meta_key, 'gallery_source', '', $selectoptions );						
			$output .= form_textinput( $this->meta_key, 'gallery_source_textinfo', '' );			
			
					
			# --- IMAGE SIZE
			$output .= "<p><strong>SET IMAGESIZE</strong></p>";
			$output .= gallery_dropdown( $this->meta_key, 'gallery_imagesize', '' );
	
					
			# --- PLACEMENT LOCATION
			// $output .= "<br /><p><strong>SET PLACEMENT</strong></p>";
			$selectoptions = array (
			   'Masthead' => 'masthead',
			   'Primary Content' => 'primary',
			   'Secondary Content' => 'secondary',
			);			
			//	$output .= form_selectbox( $this->meta_key, 'gallery_location', '', $selectoptions );	

			
			# --- CSS ALIGNMENT
			//$output .= "<br /><p><strong>Alignment</strong></p>";
			$selectoptions = array (
			   'None' => '',
			   'left' => 'left',
			   'right' => 'right',
			   'center' => 'center'
			);			
			//$output .= form_selectbox( $this->meta_key, 'gallery_alignment', '', $selectoptions );	
			
			
			# --- ORDER SLIDE BY
			$output .= "<p><strong>ORDER BY</strong></p>";
			$selectoptions = array (
			   'none' => 'none',
			   'Menu Order' => 'menu_order',
			   'Author' => 'author',
			   'Date' => 'date',
			   'Title' => 'title',
			   'Last Modified' => 'modified',
			   'Parent' => 'parent',
			   'ID' => 'ID',
			   'Random' => 'rand',
			   'Meta Value' => 'meta_value',
			   'Comment Count' => 'comment_count'
			);			
			$output .= form_selectbox( $this->meta_key, 'gallery_orderby', '', $selectoptions );			
			
			
			# --- NAVIGATION CONTROL OPTIONS
			$output .= "<br /><p><strong>NAVIGATION CONTROL OPTIONS</strong></p>";
			$output .= form_checkbox( $this->meta_key, 'gallery_enablenextprev', 'Next/Prev Controls' );
			$output .= form_checkbox( $this->meta_key, 'gallery_autoplay', 'Autoplay' );
			$output .= form_checkbox( $this->meta_key, 'gallery_thumbnailpreview', 'Show Thumbnail Preview' );
			$selectoptions = array (
			   'Bottom' => 'bottom',
			   'Top' => 'top',			   
			   'Left' => 'left',
			   'Right' => 'right'
			);			
			#$output .= form_selectbox( $this->meta_key, 'gallery_thumbpreviewposition', 'Set Thumbnail Location', $selectoptions );	
			




			
			
			
			# --- TITLE OPTIONS
			$output .= "<br /><p><strong>TITLE DISPLAY OPTIONS</strong></p>";			
			$output .= form_checkbox( $this->meta_key, 'gallery_title_enable', 'Show Title' );			

			# --- CONTENT OPTIONS			
			$radiooptions = array (
				'None' => '',
				'Post Excerpt' => 'post_excerpt', 								// AS ENTERED					
				'Excerpt' => 'the_excerpt', 											// SANITIZED
				'Full Excerpt Filtered' => 'the_excerpt_filtered',		// SANITIZED AND FILTERED
				'Post Content' => 'post_content',								// AS ENTERED IN MYSQL
				'Full Content' =>'the_content',									// SANITIZED
				'Full Content Filtered' => 'the_content_filtered',		// SANITIZED AND FILTERED
			);	
			$output .= form_radio( $this->meta_key, 'gallery_display_retrieve_content', '<strong>CONTENT DISPLAY OPTIONS</strong>', $radiooptions );

			
			# --- ENABLE HYPERLINK				
			$output .= "<p><strong>ENABLE HYPERLINK</strong></p>";		
			$output .= form_checkbox( $this->meta_key, 'gallery_hyperlink_enable', 'ENABLE HYPERLINK' );			
					
			
			# --- HYPERLINK TARGET
			$output .= "<p><strong>HYPERLINK LINKS TO</strong></p>";			
			$radiooptions = array (
			   # "Link to Image Source" => "linktoself",							// Link to Image Source
			   # "Link to Image's Parent" => "linktoparent",			   		// Link to Image of Attached Paged
			   "Image File" => "link_to_file",									// Link to Image file
			   "Image Page" => "link_to_attachment_page",					// Link to Image Page				   
			   "Image Parent" => "link_to_parent",			// Link to Image of Attached Paged
			);			
			$output .= form_selectbox( $this->meta_key, 'gallery_hyperlink_target', '', $radiooptions );			
			
			
			# --- HYPERLINK PLACEMENT			
			$radiooptions = array (
			   'Image' => 'image',			
			   'Title' => 'title'
			);			
			$output .= form_radio( $this->meta_key, 'gallery_hyperlink_placement', '<strong>HYPERLINK WRAPS AROUND</strong>', $radiooptions );

			# --- GALLERY TRANSITION
			$output .= 
			'<br /><p>
				<strong>Transitions Options.</strong>
			 </p>';				
			$output .= form_textinput( $this->meta_key, 'gallery_transitiondelay', 'Overide Delay of 2500ms' );	
			
			

			
			
			
			
			
			
			
			
			
			# --- FANCY TRANSITION OPTIONS
			if( $saved_meta_fields["gallery_type"] == "fancytransitions" ) :
				$output .= 
				'<hr /><p><br />
					<strong>Fancy Transitions Options.</strong>
				 </p>';				
				$selectoptions = array (
				   'wave' => 'wave',
				   'zipper' => 'zipper',
				   'curtain' => 'curtain',
				);			
				$output .= form_selectbox( $this->meta_key, 'fancytransitions_effect', 'Select Effect:', $selectoptions );
				$selectoptions = array (
				   'top' => 'top',
				   'bottom' => 'bottom',
				   'curtain' => 'curtain',
				   'alternate' => 'alternate'
				);			
				$output .= form_selectbox( $this->meta_key, 'fancytransitions_position', 'Vertical Transition', $selectoptions );
				$selectoptions = array (
				   'left' => 'left',
				   'right' => 'right',
				   'alternate' => 'alternate',
				   'random' => 'random',
				   'fountain' => 'fountain',
				   'fountain alternate' => 'fountainAlternate'			   
				);			
				$output .= form_selectbox( $this->meta_key, 'fancytransitions_direction', 'Horizontal Transition', $selectoptions );
				$output .= form_textinput( $this->meta_key, 'fancytransitions_delay', 'Overide Delay of 6000ms' );		
			endif;
			
			
			
			
			# --- NIVOSLIDER TRANSITION OPTIONS
			if( $saved_meta_fields["gallery_type"] == "nivoslider" ) :
				$output .= 
				'<hr /><p><br />
					<strong>Nivoslider Options.</strong>
				 </p>';				
				$selectoptions = array (
					'fade' => 'fade', 
					'fold' => 'fold', 
					'sliceDown' => 'sliceDown', 
					'sliceDownLeft' => 'sliceDownLeft', 
					'sliceUp' => 'sliceUp', 
					'sliceUpLeft' => 'sliceUpLeft', 
					'sliceUpDown' => 'sliceUpDown', 
					'sliceUpDownLeft' => 'sliceUpDownLeft', 
					'slideInRight' => 'slideInRight', 
					'slideInLeft' => 'slideInLeft', 
					'boxRandom' => 'boxRandom', 
					'boxRain' => 'boxRain', 
					'boxRainReverse' => 'boxRainReverse', 
					'boxRainGrow' => 'boxRainGrow', 
					'boxRainGrowReverse' => 'boxRainGrowReverse',
					'random' => 'random'
				);				
				$output .= form_selectbox( $this->meta_key, 'nivoslider_effect', 'Select Effect:<br />', $selectoptions );	
				$output .= form_textinput( $this->meta_key, 'nivoslider_boxcol', 'Box Col Number<br />' );
				$output .= form_textinput( $this->meta_key, 'nivoslider_boxrows', 'Box Row Number<br />' );						
			endif;			
			
			
			# --- CYCLE TRANSITION OPTIONS
			if( $saved_meta_fields["gallery_type"] == "jcyclegallery" ) :
				$output .= 
				'<hr /><p><br />
					<strong>Jcycle Transitions Options.</strong>
				 </p>';				
				$selectoptions = array (
					'none' => 'none', 
					'blindX' => 'blindX', 
					'blindY' => 'blindY', 
					'blindZ' =>'blindZ', 
					'cover' => 'cover', 
					'curtainX' => 'curtainX', 
					'curtainY' => 'curtainY', 
					'fade' => 'fade', 
					'fadeZoom' => 'fadeZoom', 
					'growX' => 'growX', 
					'growY' => 'growY', 
					'scrollUp' => 'scrollUp', 
					'scrollDown' => 'scrollDown', 
					'scrollLeft' => 'scrollLeft', 
					'scrollRight' => 'scrollRight', 
					'scrollHorz' => 'scrollHorz', 
					'scrollVert' => 'scrollVert', 
					'shuffle' => 'shuffle', 
					'slideX' => 'slideX', 
					'slideY' => 'slideY', 
					'toss' => 'toss', 
					'turnUp' => 'turnUp', 
					'turnDown' => 'turnDown', 
					'turnLeft' => 'turnLeft', 
					'turnRight' => 'turnRight', 
					'uncover' => 'uncover', 
					'wipe' => 'wipe', 
					'zoom' => 'zoom' 
				);				
				$output .= form_selectbox( $this->meta_key, 'jcyclegallery_effect', 'Select Effect:', $selectoptions );			
			endif;		
		
		
			# --- ANYTHING SLIDER OPTIONS
			if( $saved_meta_fields["gallery_type"] == "anythingslider" ) :
				$output .= 
				'<hr /><p><br />
					<strong>Jcycle Transitions Options.</strong>
				 </p>';				
				$selectoptions = array (
					'none' => 'none', 
				);				
				$output .= form_selectbox( $this->meta_key, 'jcyclegallery_effect', 'Select Effect:', $selectoptions );			
			endif;				
			
			
			echo $output;	  	
	}



	# -- WHEN A POST IS INSERTED OR UPDATED
	function wp_insert_post($post_id, $post = null ) {
		if ($post->post_type == "page" || $post->post_type == "post" ) {
		
			$saved_data = get_post_meta($post_id, $this->meta_key, true);	
			$new_data = array();													// Build an array to save in the meta...We are only going to use one wp_postmeta entry.		
			
				// Loop through our fields, grab the equivalent $_POST value and build array to pass onto wp_postmeta entry 
				foreach ($this->meta_fields as $key) {							// Loop through the POST data
					
					if(isset($_POST[$key])) {
						$value = @$_POST[$key];

						if(!empty($value)) {
							$new_data = array_merge( $new_data, array( $key => $value ) );
						}
						
					}
					
					
					
				}
			
			# --- CAN'T FIND A BETTER WAY TO LIMIT THIS TO THE ADMIN ADD AND EDIT PAGES
			# --- ALSO THE BULK EDIT PAGES DON'T CARY THE FORM POST VARIABLE 
			if(isset($_POST['post_ID'])) {
				$merged_data = wp_parse_args( $new_data, $saved_data );	// Merge default args with those passed on by the function call
				update_post_meta($post_id, $this->meta_key, $new_data);
			}				

		}
	}

}





?>