<?php 
header('Content-type: text/javascript');   
header("Cache-Control: must-revalidate"); 
$offset = 72000 ; 
$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT"; 
header($ExpStr);



if( get_query_var('dynamic') == 'themeoptions' ) {
	do_action('fdt_print_dynamic_themeoptions_js');
} else {
	do_action('fdt_print_dyanmic_galleries_js');
	nivoslider_jquery();
	jcycle_jquery();
	anythingslider_jquery();
	smoothdiv_jquery();
}






function nivoslider_jquery() {
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
	
	if($meta["gallery_type"] == "nivoslider" ){		
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

	}



}



function fancytransition_jquery() {
	//We have to get the Post ID, because the redirect does'nt pickup the post meta data
	
	$jqpostid = get_query_var('jqpostid');
	$csoptions = get_post_meta($jqpostid, THEMECUSTOMMETAKEY, true);	
	
	
	// ---------------------------	
	$delay = set_default_value( $csoptions["fancytransitions_delay"], 2500 );	
	$csoptions["fancytransitions_enablenextprev"] = checkbox_truefalse($csoptions["fancytransitions_enablenextprev"]);	
	if($csoptions["gallery_imagesize"] != "" ){	
		$ftwidth = get_option($csoptions["gallery_imagesize"].'_size_w');
		$ftheight = get_option($csoptions["gallery_imagesize"].'_size_h');	
	} else {
		$ftwidth = 540;
		$ftheight = 360;
	}

	
	$position = $csoptions["fancytransitions_position"];
	$direction = $csoptions["fancytransitions_direction"];	
	$navigation = checkbox_truefalse($csoptions["gallery_enablenextprev"]);		
	
	

	if($csoptions["gallery_type"] == "fancytransitions" ){		
print <<<END
	$(function(){
		
		$("#fancytransitions").jqFancyTransitions({
			effect: '{$csoptions["fancytransitions_effect"]}',	// wave, zipper, curtain
			width: {$ftwidth},															// width of panel
			height: {$ftheight},														// height of panel
			strips: 16,																		// number of strips
			delay: {$delay},																// delay between images in ms
			stripDelay: 20,																// delay beetwen strips in ms
			titleOpacity: 0.7,															// opacity of title
			titleSpeed: 1000, 															// speed of title appereance in ms
			position: '{$position}', 												// top, bottom, alternate, curtain
			direction: '{$direction}', 												// left, right, alternate, random, fountain, fountainAlternate
			navigation: {$navigation}, 											// prev and next navigation buttons
			links: false 																	// show images as links
		}); 	
	
		// NAVIGATION FADEIN ON HOVER
			$('#fancytransitions').hoverIntent(
				function() { $('.ft-next, .ft-prev').fadeIn(); },
				function() { $('.ft-next, .ft-prev').fadeOut(); }
			);
	

	});
END;

	}



}



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
	
	
if($meta["gallery_type"] == "anythingslider" ){	
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
}


}









function smoothdiv_jquery() {

	$jqpostid = get_query_var('jqpostid');		// WE HAVE TO GET THE POST ID, BECAUSE THE REDIRECT DOESN'T PICKUP THE POST META DATA
	$meta = get_post_meta($jqpostid, THEMECUSTOMMETAKEY, true);	
	
	if($meta["gallery_type"] == "smoothdiv" ){		
	
print <<<END
	$(function(){
	
	/*****************************
		SMOOTH DIV
	*****************************/
	$("div#makeMeScrollable").smoothDivScroll({
		scrollingHotSpotLeft:	"div.scrollingHotSpotLeft",				// The identifier for the hotspot that triggers scrolling left.
		scrollingHotSpotRight:	"div.scrollingHotSpotRight",		// The identifier for the hotspot that triggers scrolling right.
		scrollWrapper:	"div.scrollWrapper",					// The identifier of the wrapper element that surrounds the scrollable area.
		scrollableArea:	"div.scrollableArea",					// The identifier of the actual element that is scrolled left or right.	
		scrollingSpeed: 12, 
		mouseDownSpeedBooster: 3, 
		// autoScroll: "onstart", 
		autoScrollDirection: "endlessloop", 
		autoScrollSpeed: 2, 
		visibleHotSpots: "always", 
		hotSpotsVisibleTime: 9
		}
	);
	
	$('div#makeMeScrollable IMG').removeAttr("title");	
	$('div#makeMeScrollable IMG A').removeAttr("title");	


	});
END;

	}


}



function jcycle_jquery() {

	$jqpostid = get_query_var('jqpostid');		// WE HAVE TO GET THE POST ID, BECAUSE THE REDIRECT DOESN'T PICKUP THE POST META DATA
	$meta = get_post_meta($jqpostid, THEMECUSTOMMETAKEY, true);
	$fx = set_default_value( $meta["jcyclegallery_effect"], "none" );	

	$delay = set_default_value( $meta["gallery_transitiondelay"], 2500 );	
	$autoplay = checkbox_truefalse($meta["gallery_autoplay"]);
	if ($autoplay == 'false') {
		$delay = 0;
	}
	
if($meta["gallery_type"] == "jcyclegallery" ){
print <<<END
	$(function(){

			/*****************************
				JCYCLE
			*****************************/
			$('.post-{$jqpostid} .jcyclegallery').each(function(i) {

				// GRAB ID TO USE AS A IDENTIFIER
				var target = $(this).children().attr('id');

				// MEDIA CONTROLS HOVER EFFECT
				$('#'+target+' .media_grouping').hoverIntent(
					function() { $('#'+target+' .media_controls').fadeIn(); },
					function() { $('#'+target+' .media_controls').fadeOut(); }  
				);

				////// JCYCLE PLULGIN STUFF
				// Hide all the controls
				$('#'+target+' .media_controls').hide();
				$('#'+target+' .pause').click(function() { $('.'+target+' .media_assets').cycle('pause'); return false; });
				$('#'+target+' .play').click(function() { $('.'+target+' .media_assets').cycle('resume'); return false; });


				// INITIATE JCYCLE SETTINGS
				$('#'+target+' .media_assets', this).cycle({
					fx:     '{$fx}', 
					speed:   1400,
					next:   '#'+target+' .next, #'+target+' img',
					prev:   '#'+target+' .prev',
					timeout: {$delay},
					nowrap: 0,
					containerResize: 0,
					before: adjustHeight,
					after: jcyclecounter,
					pager:  '#'+target+' .thumbnav',
					pagerAnchorBuilder: function(idx, slide) { 
						// return selector string for existing anchor 
						return '#'+target+' .thumbnav li:eq(' + idx + ') a IMG'; 
						// return '#'+target+' .thumbnav li:eq(' + idx + ') a'; 						
					} 			
					
				});

				// REMOVE IMAGE AND LINK TITLE ATTRIBUTES
				$('#'+target+' .media_assets IMG', this).removeAttr("title");
				$('#'+target+' .media_assets IMG A', this).removeAttr("title");
				$('#'+target+' .jcyclethumbs IMG', this).removeAttr("title");
				$('#'+target+' .jcyclethumbs IMG A', this).removeAttr("title");
				
				// HIDE PAUSE/PLAY
				$('#'+target+' .pause').hide();
				
				// EVENT TRIGGERS FOR CLICKING ON PLAY AND PAUSE
				$('#'+target+' .play').click(function(){
					$('#'+target+' .media_assets').cycle('resume'); 
					$('#'+target+' .media_controls').fadeOut(); 
					$(this).hide();
					$('#'+target+' .pause').show(); 			
				});		
				$('#'+target+' .pause').click(function(){  
					$('#'+target+' .media_assets').cycle('pause'); 
					$('#'+target+' .media_controls').fadeOut(); 
					$(this).hide();
					$('#'+target+' .play').show();			
				});

				
				// INITIATE JCYCLE WITH PAUSE
				// $('#'+target+' .media_assets').cycle('pause');							
			});


			/*****************************
				SMOOTH DIV THUMBNAV
				FOR JCYCLE GALLERY
			*****************************/	
			$(".post-{$jqpostid} div#thumbnavscrollerable").smoothDivScroll({
				scrollingHotSpotLeft:	"div.scrollingHotSpotLeft",		// The identifier for the hotspot that triggers scrolling left.
				scrollingHotSpotRight:	"div.scrollingHotSpotRight",	// The identifier for the hotspot that triggers scrolling right.
				scrollWrapper:	"div.media_thumbs",						// The identifier of the wrapper element that surrounds the scrollable area.
				scrollableArea:	"ul.thumbnav",							// The identifier of the actual element that is scrolled left or right.	
				scrollingSpeed: 12, 
				mouseDownSpeedBooster: 3, 
				// autoScroll: "onstart", 
				autoScrollDirection: "endlessloop", 
				autoScrollSpeed: 2, 
				visibleHotSpots: "always", 
				hotSpotsVisibleTime: 9
				}
			);	

			$('div#thumbnavscrollerable IMG').removeAttr("title");	
			$('div#thumbnavscrollerable IMG A').removeAttr("title");		
			
			$(window).resize(function() {
					$("div#makeMeScrollable").smoothDivScroll({
						scrollingHotSpotLeft:	"div.scrollingHotSpotLeft",		// The identifier for the hotspot that triggers scrolling left.
						scrollingHotSpotRight:	"div.scrollingHotSpotRight",	// The identifier for the hotspot that triggers scrolling right.
						scrollWrapper:	"div.scrollWrapper",					// The identifier of the wrapper element that surrounds the scrollable area.
						scrollableArea:	"div.scrollableArea",					// The identifier of the actual element that is scrolled left or right.	
						scrollingSpeed: 12, 
						mouseDownSpeedBooster: 3, 
						// autoScroll: "onstart", 
						autoScrollDirection: "endlessloop", 
						autoScrollSpeed: 2, 
						visibleHotSpots: "always", 
						hotSpotsVisibleTime: 9
						}
					);			
			});	
	
	});
	
	
	function jcyclecounter(curr,next,opts) {
		var caption = (opts.currSlide + 1) + ' / ' + opts.slideCount;
		$('.jcyclecounter').html(caption);
	}
		
		
	//	WORKS WITH JCYCLE, DETECTS CONTAINER ITEM HEIGHT AND ADJUST ENTIRE CONTAINER ACCORDINGLY
	function adjustHeight(curr, next, opts, fwd) {
		
		// GET THE HEIGHT OF THE CURRENT SLIDE
		var ht = $(this).height();
		var wt = $(this).width();
		
		//	SET THE CONTAINER'S HEIGHT TO THAT OF THE CURRENT SLIDE		
		$(this).parent().stop().animate({ height: ht }, 400);	
	}
		
END;
}


}





function checkbox_truefalse($input) {

	if($input == "" ){
		$output = "false";
	} else {
		$output = "true";
	}	

	return $output;
}

?>