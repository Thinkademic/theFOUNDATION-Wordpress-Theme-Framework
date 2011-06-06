<?php
/**
 * ANYTHING SLIDER - TEMPLATE TAG
 */
function show_anythingslider() {
	global $post;
	
	$meta = get_post_meta($post->ID, THEMECUSTOMMETAKEY, true);

	if( $meta["gallery_type"] == "anythingslider" ):
		$atts = postmeta_gallery_array();			
		echo get_anythingslider( $atts );
	endif;
	
}

/**
 * ANYTHING SLIDER - SHORT CODE FUNCTION
 */
add_shortcode('anythingslidergallery', 'anythingslider_shortcodehandler');
function anythingslider_shortcodehandler($atts, $content = null) {

	$atts = shortcode_atts(array(
		'imagesize' => 'thumbnail',
		'orderby' => 'menu_order'
	), $atts);

	$gallery = get_anythingslider( $atts);
	return $gallery.$content;
}

/**
 * ANYTHING SLIDER - ECHO FUNCTION
 *
 * @param null $atts
 */
function anythingslider( $atts = null ){
	echo get_anythingslider( $atts );
}

/**
 * ANYTHING SLIDER - HTML WRAPPER FUNCTION
 *
 * @param null $atts
 * @return string
 */
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

/**
 * ANYTHING SLIDER
 *
 * @TODO : CHANGE CAMELCASE ON FUNCTION NAME TO LOWERCASE
 * @param null $atts
 * @return string
 */
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

/**
 * BUILD CSS FOR ANYTHINGSLIDER
 *
 * @param null $atts
 * @return
 *
 */
function build_css_anythingslider($atts = null)
{
    $STYLESHEETURI = get_stylesheet_directory_uri();

    if ($atts == null)
        return;

    extract($atts, EXTR_SKIP);

    print <<<END
    
/*ANYTHING SLIDER*/
DIV.anythingSlider
{
	position: relative;
	margin: 0 auto;
	padding: 0;
	margin-bottom: 35px;
	background: #101010;
	border: 1px solid #717171;
}
DIV.as-box
{
	background: #101010;
	padding: 10px 3px;
}
DIV.as-title
{
	padding: 0;
	margin: 0;
	/*+placement:shift;*/
	position: relative;
	left: 0;
	top: 0;
}
DIV.as-title H3
{
	color: #FFF;
	font-size: 19px;
	line-height: 1;
	margin: 0;
	text-indent: 5px;
}
DIV.as-content
{
	font-size: 12px;
	padding: 0 5px;
	color: #968D8D;
}
DIV.anythingSlider .anythingWindow
{
	overflow: hidden;
	position: relative;
	width: 100%;
	height: 100%;
}
UL.anythingBase
{
	background: #101010;
	list-style: none;
	position: absolute;
	top: 0;
	left: 0;
	margin: 0;
	padding: 0;
}
UL.anythingBase LI.panel
{
	background: transparent;
	display: block;
	float: left;
	padding: 0;
	margin: 0;
}
DIV.anythingSlider LI
{
	clear: none !important;
}
DIV.anythingSlider .arrow
{
	top: 50%;
	position: absolute;
	display: block;
	z-index: 10000000;
}
DIV.anythingSlider .arrow A
{
	display: block;
	height: 120px;
	margin: -60px 0 0;
	width: 45px;
	text-align: center;
	outline: 0;
	background: url({$STYLESHEETURI}/images/anythingslider/arrows-default.png) no-repeat;
	text-indent: -9999px;
}
DIV.anythingSlider .forward
{
	right: 0;
	/*[empty]background:;*/
}
DIV.anythingSlider .back
{
	left: 0;
}
DIV.anythingSlider .forward A
{
	background-position: -1px -39px;
}
DIV.anythingSlider .back A
{
	background-position: -88px -40px;
}
DIV.anythingSlider .forward A:hover, DIV.anythingSlider .forward A.hover
{
	background-position: 11px -245px;
}
DIV.anythingSlider .back A:hover, DIV.anythingSlider .back A.hover
{
	background-position: -101px -244px;
}
DIV.anythingSlider .anythingControls
{
	outline: 0;
	background: url({$STYLESHEETURI}/images/gradient/black70.png);
	margin: 0;
	padding: 5px 0px;
	width: 100%;
	/*+placement:anchor-bottom-left 0px -28px;*/
	position: absolute;
	left: 0px;
	bottom: -28px;
}
DIV.anythingSlider.activeSlider .thumbNav A
{
}
DIV.anythingSlider.activeSlider .thumbNav A.cur
{
	color: #1A1A1A;
}
DIV.anythingSlider .thumbNav
{
	width: 90%;
	margin: 0 auto 0 5px;
}
DIV.anythingSlider .thumbNav LI
{
	display: inline;
	height: 18px;
}
DIV.anythingSlider .thumbNav A
{
	display: inline-block;
	text-decoration: none;
	padding: 3px 3px 3px 9px;
	height: 18px;
	text-align: center;
	color: #FFFFFF;
	outline: none;
	width: 4px;
	height: 12px;
	text-indent: -9999em;
	background: url({$STYLESHEETURI}/images/anythingslider/button.png) no-repeat center;
}
DIV.anythingSlider .thumbNav A:hover
{
	background: url({$STYLESHEETURI}/images/anythingslider/buttonon.png) no-repeat center;
}
DIV.anythingSlider.activeSlider .thumbNav A.cur
{
	background: url({$STYLESHEETURI}/images/anythingslider/buttonon.png) no-repeat center;
}
DIV.anythingSlider.rtl .thumbNav A
{
	float: right;
}
DIV.anythingSlider.rtl .thumbNav
{
	float: right;
}
DIV.anythingSlider .start-stop
{
	padding: 5px;
	background: #69BD1B;
	color: #444;
	font-size: 10px;
	margin: 2%;
	line-height: 6px;
	font-style: italic;
	height: 8px;
	text-align: center;
	text-decoration: none;
	z-index: 100;
	width: 5%;
	outline: 0;
	/*+border-radius:3px;*/
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	-khtml-border-radius: 3px;
	border-radius: 3px 3px 3px 3px;
	/*+placement:anchor-top-right -5px 5px;*/
	position: absolute;
	right: -5px;
	top: 5px;
}
DIV.anythingSlider .start-stop.playing
{
	background: #B28;
	color: #FFFFFF;
}
DIV.anythingSlider .start-stop:hover, DIV.anythingSlider .start-stop.hover
{
	color: #333;
}
DIV.anythingSlider .start-stop:hover, DIV.anythingSlider .start-stop.hover
{
	background-image: none;
}


END;

}

/**
 * CSS ANYTHINGSLIDER ACTION FUNCTION
 *
 * @NOTE NOT GOING TO USED DYNAMICALLY GENERATED CSS FOR ANYTHINGSLIDER
 */
function css_anythingslider()
{
    $atts = array(
        'width' => 100
    );

    #build_css_anythingslider($atts);
}
add_action('fdt_print_dynamic_css', 'css_anythingslider');

/**
 * BUILD_ANYTHINGSLIDER_JQUERY
 * 
 * @param null $atts
 * @return
 */
function build_anythingslider_jquery( $atts = null ){

	if($atts == null)
		return;

	extract( $atts, EXTR_SKIP );	

print <<<END

/*****************************
	ANYTHINGSLIDER FOR {$postid} 
*****************************/

jQuery(document).ready(function($) {
	
	$('.post-{$postid} #slider').anythingSlider({
			
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
		buildArrows			: {$buildarrows},		// If true, builds the forwards and backwards buttons
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

END;
}

/**
 * JQUERY FOR ANYTHINGSLIDER
 */
function anythingslider_jquery() {

	$pass_postid = explode( "-", get_query_var('jqids') );
	
	$more_jquery_functions = false;
	foreach ($pass_postid as $key => $postid) {

		// WE HAVE TO GET THE POST ID, BECAUSE THE REDIRECT DOESN'T PICKUP THE POST META DATA
		$meta = get_post_meta($postid, THEMECUSTOMMETAKEY, true);	

		// ---------------------------		
		if($meta["gallery_imagesize"] != "" ){	
			$width = get_option($meta["gallery_imagesize"].'_size_w');
			$height = get_option($meta["gallery_imagesize"].'_size_h');	
		} else {
			$width = get_option('medium_size_w');
			$height = get_option('medium_size_h');
		}
		
		
		$autoplay = checkbox_truefalse($meta["gallery_autoplay"]);		
		$buildarrows = checkbox_truefalse($meta["gallery_enablenextprev"]);			
		$delay = set_default_value( $meta["gallery_transitiondelay"], 2500 );	
		
		$atts = array(
				'postid' => $postid,
				'width' => $width,
				'height' => $height,
				'autoplay' => $autoplay,
				'buildarrows' => $buildarrows,
				'delay' => $delay	
			);

		if($meta["gallery_type"] == "anythingslider" ) :
			build_anythingslider_jquery($atts);
			$more_jquery_functions = true;
		endif;
	
	}
	


if( $more_jquery_functions ) :
print <<<END

function formatText(index, panel) {
	  return index + "";
}	

END;
endif;




}
add_action('fdt_print_dynamic_js','anythingslider_jquery');

/**
 *  REGISTER SCRIPTS FOR ANYTHING SLIDER
 */
function anythingslider_register_script()
{
    $src = get_stylesheet_directory_uri();
    wp_register_script('anythingslider', $src . "/js/jquery.anythingslider.js", array('jquery', 'anythingsliderfx'), '1.4', false);
    wp_register_script('anythingsliderfx', $src . "/js/jquery.anythingslider.fx.js", false, '1.4', false);
}
add_action('template_redirect', 'anythingslider_register_script');

/**
 *  REGISTER STYLE FOR ANYTHING SLIDER
 */
function anythingslider_register_style()
{
    wp_register_style('anythingslider', get_stylesheet_directory_uri() . '/css/media-galleries/' . 'anythingslider.css');
    wp_enqueue_style('anythingslider');
}
add_action('template_redirect', 'anythingslider_register_style');
?>