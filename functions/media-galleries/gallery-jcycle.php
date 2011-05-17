<?php

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
 
**************************************************************/
function build_jcycle_jquery( $atts = null ){

if($atts == null)
return;

extract( $atts, EXTR_SKIP );	

print <<<END

$(function(){

	/*****************************
		JCYCLE FOR {$postid} 
	*****************************/
	$('.post-{$postid} .jcyclegallery').each(function(i) {

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
	$(".post-{$postid} div#thumbnavscrollerable").smoothDivScroll({
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


	
END;


}









/**************************************************************
 GENERATE JCYCLE
**************************************************************/
function jcycle_jquery() {

	$pass_postid = explode( "-", get_query_var('jqids') );
	$more_jquery_functions = false;
	
	foreach ($pass_postid as $key => $postid) {

		$meta = get_post_meta($postid, THEMECUSTOMMETAKEY, true);
		$fx = set_default_value( $meta["jcyclegallery_effect"], "none" );	
		$autoplay = checkbox_truefalse($meta["gallery_autoplay"]);	
		if ($autoplay == 'false')
			$delay = 0;
		else
			$delay = set_default_value( $meta["gallery_transitiondelay"], 2500 );	
				
		$atts = array( 
			'postid' => $postid,
			'meta' => $meta,
			'fx' => $fx,
			'delay' => $delay,
			'autoplay' => $autoplay
			);
			
		if($meta["gallery_type"] == "jcyclegallery" ):
			build_jcycle_jquery($atts);
			$more_jquery_functions = true;
		endif;	
	
	}
	
	
if( $more_jquery_functions ) :	
print <<<END

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
endif;
}
add_action('fdt_print_dynamic_js','jcycle_jquery');








?>