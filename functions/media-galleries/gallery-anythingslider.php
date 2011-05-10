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


/**************************************************************
 JQUERY FOR ANYTHINGSLIDER
**************************************************************/
function anythingslider_jquery() {
	
	// WE HAVE TO GET THE POST ID, BECAUSE THE REDIRECT DOESN'T PICKUP THE POST META DATA
	$jqpostid = get_query_var('jqpostid');
	$meta = get_post_meta($jqpostid, THEMECUSTOMMETAKEY, true);	

	// ---------------------------		
	if($meta["gallery_imagesize"] != "" ){	
		$width = get_option($meta["gallery_imagesize"].'_size_w');
		$height = get_option($meta["gallery_imagesize"].'_size_h');	
	} else {
		$width = 540;
		$height = 360;
	}
	
	
	$autoplay = checkbox_truefalse($meta["gallery_autoplay"]);		
	$buildArrows = checkbox_truefalse($meta["gallery_enablenextprev"]);			
	$delay = set_default_value( $meta["gallery_transitiondelay"], 2500 );	
	
	
if($meta["gallery_type"] == "anythingslider" ) :
print <<<END
	$(function(){
		
		$('.post-{$jqpostid} #slider').anythingSlider({

					
				// Appearance
				width               	: {$width},      		// 	IF RESIZECONTENT IS FALSE, THIS IS THE DEFAULT WIDTH IF PANEL SIZE IS NOT DEFINED
				height              	: {$height},     		// 	IF RESIZECONTENT IS FALSE, THIS IS THE DEFAULT HEIGHT IF PANEL SIZE IS NOT DEFINED
				resizeContents   : false,      			// If true, solitary images/objects in the panel will expand to fit the viewport
				tooltipClass		: 'tooltip', 				// Class added to navigation & start/stop button (text copied to title if it is hidden by a negative text indent)
				theme               : 'default', 			// Theme name
				themeDirectory	: 'css/theme-{themeName}.css', // Theme directory & filename {themeName} is replaced by the theme value above

				// Navigation
				startPanel          : 1,         // This sets the initial panel
				hashTags			: true,      // Should links change the hashtag in the URL?
				infiniteSlides      : true,      // if false, the slider will not wrap
				enableKeyboard	: true,      					// if false, keyboard arrow keys will not work for the current panel.
				buildArrows			: {$buildArrows},		// If true, builds the forwards and backwards buttons
				toggleArrows			: false,     // If true, side navigation arrows will slide out on hovering & hide @ other times
				buildNavigation		: true,      // If true, builds a list of anchor links to link to each panel
				enableNavigation   : true,      // if false, navigation links will still be visible, but not clickable.
				toggleControls		: false,     // if true, slide in controls (navigation + play/stop button) on hover and slide change, hide @ other times
				appendControlsTo    	: null,      // A HTML element (jQuery Object, selector or HTMLNode) to which the controls will be appended if not null
				navigationFormatter	: formatText, // Format navigation labels with text
				forwardText         		: "&raquo;", // Link text used to move the slider forward (hidden by CSS, replaced with arrow image)
				backText					: "&laquo;", // Link text used to move the slider back (hidden by CSS, replace with arrow image)

				// Slideshow options
				enablePlay			: {$autoplay},      // if false, the play/stop button will still be visible, but not clickable.
				autoPlay				: {$autoplay},      // This turns off the entire slideshow FUNCTIONALY, not just if it starts running or not
				autoPlayLocked      : false,     // If true, user changing slides will not stop the slideshow
				startStopped        	: false,     // If autoPlay is on, this can force it to start stopped
				pauseOnHover        	: true,      // If true & the slideshow is active, the slideshow will pause on hover
				resumeOnVideoEnd	: true,      // If true & the slideshow is active & a youtube video is playing, it will pause the autoplay until the video is complete
				stopAtEnd				: false,     // If true & the slideshow is active, the slideshow will stop on the last page. This also stops the rewind effect when infiniteSlides is false.
				playRtl					: false,     // If true, the slideshow will move right-to-left
				startText				: "Start",   // Start button text
				stopText				: "Stop",    // Stop button text
				delay					: {$delay},      // How long between slideshow transitions in AutoPlay mode (in milliseconds)
				resumeDelay         : 15000,     // Resume slideshow after user interaction, only if autoplayLocked is true (in milliseconds).
				animationTime       : 600,       // How long the slideshow transition takes (in milliseconds)
				easing					: "swing",   // Anything other than "linear" or "swing" requires the easing plugin

				// Callbacks - removed from options to reduce size - they still work

				// Interactivity
				clickArrows         : "click",         // Event used to activate arrow functionality (e.g. "click" or "mouseenter")
				clickControls       : "click focusin", // Events used to activate navigation control functionality
				clickSlideshow      : "click",         // Event used to activate slideshow play/stop button

				// Misc options
				addWmodeToObject		: "opaque", // If your slider has an embedded object, the script will automatically add a wmode parameter with this setting
				maxOverallWidth			: 32766     // Max width (in pixels) of combined sliders (side-to-side); set to 32766 to prevent problems with Opera	
		});	
		

		
		
	});
	
	function formatText(index, panel) {
		  return index + "";
	}	
	
END;
endif;
}
add_action('fdt_print_dyanmic_galleries_js','anythingslider_jquery');











?>