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





/**************************************************************
 JQUERY FOR NIVOSLIDER
**************************************************************/
function jquery_nivoslider() {
	//We have to get the Post ID, because the redirect does'nt pickup the post meta data
	$jqpostid = get_query_var('jqpostid');
	$meta = get_post_meta($jqpostid, THEMECUSTOMMETAKEY, true);	
	
	
	// ---------------------------	
	$delay = set_default_value( $meta["nivoslider_delay"], 2500 );	
	$effect = set_default_value( $meta["nivoslider_effect"], "fade" );	
	if($meta["gallery_imagesize"] != "" ){	
		$width = get_option($meta["gallery_imagesize"].'_size_w');
		$height = get_option($meta["gallery_imagesize"].'_size_h');	
	} else {
		$width = 540;
		$height = 360;
	}
	
	$buildArrows = checkbox_truefalse($meta["gallery_enablenextprev"]);	
	$delay = set_default_value( $meta["gallery_transitiondelay"], 2500 );	

	$autoplay = checkbox_truefalse($meta["gallery_autoplay"]);	
	if ($autoplay == 'false')
		$autoplay = '$(".post-'.$jqpostid.' .nivoslider").data("nivoslider").stop(); //Stop the Slider';
	else
		$autoplay = "";
	
if($meta["gallery_type"] == "nivoslider" ) :		
print <<<END
$(window).load(function() {

	$('.post-{$jqpostid} .nivoslider').nivoSlider({
        effect:'{$effect}', 							// Specify sets like: 'fold,fade,sliceDown'
        slices:5, 										// For slice animations
        boxCols: 8, 									// For box animations
        boxRows: 4, 									// For box animations
        animSpeed:500,							// Slide transition speed
        pauseTime:{$delay}, 						// How long each slide will show
        startSlide:0, 									// Set starting Slide (0 index)
        directionNav:{$buildArrows},			// Next & Prev navigation
        directionNavHide:true, 					// Only show on hover
        controlNav:true, 							// 1,2,3... navigation
        controlNavThumbs:false, 				// Use thumbnails for Control Nav
        controlNavThumbsFromRel:false, 	// Use image rel for thumbs
        controlNavThumbsSearch: '.jpg', 	// Replace this with...
        controlNavThumbsReplace: '_thumb.jpg', 			// ...this in thumb Image src
        keyboardNav:true, 											// Use left & right arrows
        pauseOnHover:true, 											// Stop animation while hovering
        manualAdvance:true	, 										// Force manual transitions
        captionOpacity:0.8,											// Universal caption opacity
        prevText: 'Prev', 												// Prev directionNav text
        nextText: 'Next', 												// Next directionNav text
        beforeChange: function(){},									// Triggers before a slide transition
        afterChange: function(){}, 									// Triggers after a slide transition
        slideshowEnd: function(){}, 									// Triggers after all slides have been shown
        lastSlide: function(){}, 											// Triggers when last slide is shown
        afterLoad: function(){}											// Triggers when slider has loaded
    });
	
	{$autoplay}

});
END;
endif;
}
add_action('fdt_print_dyanmic_galleries_js','jquery_nivoslider');










/**************************************************************
 CSS FOR NIVOSLIDER
**************************************************************/
function css_nivoslider() {

	$csspostid = get_query_var('csspostid');
	$meta = get_post_meta($csspostid, THEMECUSTOMMETAKEY, true);	
	
	$STYLESHEETPATH = get_stylesheet_directory_uri();;
	
	if($meta["gallery_imagesize"] != "" ){	
		$width = get_option($meta["gallery_imagesize"].'_size_w');
		$height = get_option($meta["gallery_imagesize"].'_size_h');	
	} else {
		$width = 540;
		$height = 360;
	}	

	
if($meta["gallery_type"] == "nivoslider" ) :
print <<<END
	/* The Nivo Slider styles */
	.nivoslider {
		margin-top: 30px;
		margin-bottom: 30px;
		position:relative;
	}
	.nivoslider img {
		position:absolute;
		top:0px;
		left:0px;
	}
	/* If an image is wrapped in a link */
	.nivoslider a.nivo-imageLink {
		position:absolute;
		top:0px;
		left:0px;
		width:100%;
		height:100%;
		border:0;
		padding:0;
		margin:0;
		z-index:6;
		display:none;
	}
	/* The slices and boxes in the Slider */
	.nivo-slice {
		display:block;
		position:absolute;
		z-index:5;
		height:100%;
	}
	.nivo-box {
		display:block;
		position:absolute;
		z-index:5;
	}
	
	
	/* Caption styles */
	.nivo-caption {
		position:absolute;
		left:0px;
		bottom:0px;
		background:#000;
		color:#fff;
		width:100%;
		z-index:8;
	}
	.nivo-caption p {
		padding:10px;
		margin:0;
	}
	.nivo-caption a {
		display:inline !important;
	}
	.nivo-html-caption {
		display:none;
	}
	
	/* Direction nav styles (e.g. Next & Prev) */
	.nivo-directionNav a {
		position:absolute;
		top:45%;
		z-index:9;
		cursor:pointer;
		background: url({$STYLESHEETPATH}/images/gradient/black50.png) repeat top left;
		display: block;
		padding: 5px 10px;
		color: #fff;
	}

	.nivo-directionNav a:hover {
	    background: #000;
		color: #ccc;
	}	
	
	.nivo-prevNav {
		left:0px;
	}
	.nivo-nextNav {
		right:0px;
	}
	
	
	
	/* Control  BULLET NAV STYLES (e.g. 1,2,3...) */
	.nivoslider .nivo-controlNav
	{
		position: absolute;
		right: 0;
		bottom:  -22px;
		text-align: center !important;
		width: 100%;
	}
	.nivoslider .nivo-controlNav A
	{
		display: inline-block;
		width: 22px;
		height: 22px;
		background: url({$STYLESHEETPATH}/images/bullets/black.png) no-repeat center center;
		text-indent: -9999px;
		border: 0;
		margin-right: 3px;
		cursor: pointer;
		
	}
	.nivoslider .nivo-controlNav A.active, 	.nivoslider .nivo-controlNav A:hover
	{
		background: url({$STYLESHEETPATH}/images/bullets/green.png) no-repeat center center;
	}

	/* Control nav styles (e.g. 1,2,3...) */
	#slider3 .nivo-controlNav
	{
		position: absolute;
		left: 185px;
		bottom: -70px;
	}
	#slider3 .nivo-controlNav A
	{
		display: inline;
	}
	#slider3 .nivo-controlNav IMG
	{
		display: inline;
		position: relative;
		margin-right: 10px;
		-moz-box-shadow: 0px 0px 5px #333;
		-webkit-box-shadow: 0px 0px 5px #333;
		box-shadow: 0px 0px 5px #333;
	}
	#slider3 .nivo-controlNav A.active IMG
	{
		border: 1px solid #000;
	}
	

END;
endif;
}
add_action('fdt_print_dyanmic_galleries_css','css_nivoslider');





?>