<?php

/**
 * EMBED CHECK
 */
function embed_jcyclegallery() {
        if (function_exists('show_jcyclegallery'))
            show_jcyclegallery();
}
add_action('fdt_show_media_galleries', 'embed_jcyclegallery');


/**
 * JCYCLE - TEMPLATE TAG
 */
function show_jcyclegallery()
{
    global $post;

    $meta = get_post_meta($post->ID, THEMECUSTOMMETAKEY, true);

    if ($meta["gallery_type"] == "jcyclegallery") :

        $atts = fdt_postmeta_gallery_array();
        echo get_jcyclegallery($atts);

    endif;
}

/**
 * JCYCLE - SHORT CODE
 *
 */
add_shortcode('jcyclegallery', 'jcyclegallery_shortcodehandler');
add_shortcode('mediagallery', 'jcyclegallery_shortcodehandler');

function jcyclegallery_shortcodehandler($atts, $content = null)
{
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

    $gallery = get_jcyclegallery($atts);
    return $gallery . $content;
}

/**
 * JCYCLE - ECHO FUNCTION
 */
function jcyclegallery($atts = null)
{
    global $post;
    echo get_jcyclegallery($atts);
}


/**
 * JCYCLE
 */
function get_jcyclegallery($atts = null)
{
    global $wp_query, $post, $paged;

    $return = extractMedia($atts);

    return $return;
}

/**
 * JCYCLE - QUERY CALL
 */
function extractMedia($atts)
{
    global $wp_query, $post, $paged, $post_count;

    return jcycle_extractMedia($atts);
}

/**
 * JCYCLE - QUERY CALL
 */
function jcycle_extractMedia($atts)
{
    global $wp_query, $post, $paged, $post_count;

    $defaults = array(
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
    $atts = wp_parse_args($atts, $defaults);
    extract($atts, EXTR_SKIP);
    $query_args = build_query_array($atts);

    $temp = $wp_query;
    $wp_query = null;
    $wp_query = new WP_Query();
    $wp_query->query($query_args);

    $query_count = sizeof($wp_query->posts);


    #	SETTINGS FOR RETRIEVE_HEADLINE()
    $headline_settings = array(
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
    if ($hyperlink_placement != 'image' && $hyperlink_enable == true) {
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

                                      if ($counter == 1 && $contains_video == true) {
                                          $asset = $video_asset;
                                      } else {
                                          $media = retrieve_media($querytype, $imagesize, $hyperlink_target, $hyperlink_enable);
                                          $asset_caption = retrieve_headline($headline_settings);
                                          $asset_content = xtag("div", retrieve_content($retrieve_content), "class=asset_content");
                                          $asset .= "\n" . "<div class='asset'>" . $media . $asset_caption . $asset_content . "</div>";
                                      }

                                      $thumbnav .= '<li><a href="#">' . retrieve_media($querytype, "mini" . $imagesize) . '</a></li>';
    endwhile;
    $output .= "<div class='media_assets'>" . $videoassets . $asset . "</div>";


    //	MEDIA CONTROLS
    if ($enablenextprev) {
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
				' . $playpausoptions . '
			</div>
			';

        $simplemediacontrolxhtml = '
			<div class="simplemedia_controls">
				<a href="#" class="prev"><span>Prev</span></a><span class="jcyclecounter"></span><a href="#" class="next"><span>Next</span></a>
				' . $playpausoptions . '
			</div>
			';
    }


    //	THUMBNAIL NAVIGATOR
    if ($enablethumbpreview == true && $query_count > 1) {

        if ($thumbpreviewposition == 'left' || $thumbpreviewposition == 'right') {

            $thumbnav = "\n" . '
				<div class="jcyclethumbs">
					
					<div class="jcyclethumbs_' . $imagesize . '">
						' . $simplemediacontrolxhtml . '
						<ul class="thumbnav">
						' . $thumbnav . '
						</ul>
					</div>
				</div>	
				';

        } else {
            $thumbnav = "\n" . '
				<hr /><!-- Needed to fix strange float/height issue -->
				<div id="thumbnavscrollerable">
					<div class="scrollingHotSpotLeft"></div>
					<div class="scrollingHotSpotRight"></div>
					<div class="media_thumbs media_thumbs_' . $imagesize . '">
						<ul class="thumbnav">
						' . $thumbnav . '
						</ul>
					</div>
				</div>	
				';
        }

    } else {
        $thumbnav = "";
    }


    //	SHOW MEDIA CONTROL IF THERE IS MORE THEN ONE MEDIA ASSET
    if ($query_count > 1)
        $output = $mediacontrolxhtml . $output;

    //	THUMBNAIL POSITION
    if ($thumbpreviewposition == 'bottom' || $thumbpreviewposition == 'right') {
        $output = '<div class="galsize_' . $imagesize . '"><div class="media_grouping">' . $output . '</div>' . $thumbnav . '</div>';
    } else {
        $output = '<div class="galsize_' . $imagesize . '">' . $thumbnav . '<div class="media_grouping">' . $output . '</div></div>';
    }

    //	WRAP WITH DIVS
    $output = xtag('div', $output, 'id=' . $post->post_name);
    $output = xtag('div', $output, 'class=jcyclegallery', '', '<hr />');

    $wp_query = null;
    $wp_query = $temp;
    wp_reset_query();
    return $output;

}


/**
 * BUILD JCYCLE JQUERY
 */
function build_jcycle_jquery($atts = null)
{

    if ($atts == null)
        return;

    extract($atts, EXTR_SKIP);

    print <<<END

jQuery(document).ready(function($) {

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


/**
 * JQUERY JCYCLE ACTION FUNCTION
 */
function jcycle_jquery()
{

    $pass_postid = explode("-", get_query_var('jqids'));
    $more_jquery_functions = false;

    foreach ($pass_postid as $key => $postid) {

        $meta = get_post_meta($postid, THEMECUSTOMMETAKEY, true);
        $fx = set_default_value($meta["jcyclegallery_effect"], "none");
        $autoplay = checkbox_truefalse_string($meta["gallery_autoplay"]);
        if ($autoplay == 'false')
            $delay = 0;
        else
            $delay = set_default_value($meta["gallery_transitiondelay"], 2500);

        $atts = array(
            'postid' => $postid,
            'meta' => $meta,
            'fx' => $fx,
            'delay' => $delay,
            'autoplay' => $autoplay
        );

        if ($meta["gallery_type"] == "jcyclegallery"):
            build_jcycle_jquery($atts);
            $more_jquery_functions = true;
        endif;

    }


    if ($more_jquery_functions) :
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

add_action('fdt_print_dynamic_js', 'jcycle_jquery');


/**
 * BUILD DYNAMIC CSS FOR JCYCLE
 *
 * @param null $atts
 * @return
 */
function build_css_jcycle($atts = null)
{

    $thumbnail = 340;
    $medium = 580;
    $large = 940;

    if ($atts == null)
        return;

    extract($atts, EXTR_SKIP);

    print <<<END

/*-------- Jcycle Gallery --------*/
.jcyclegallery
{
	position: relative;
	line-height: 12px;
}
.jcyclegallery HR
{
	display: block;
	height: 0;
	margin: 0;
	padding: 0;
}
.jcyclegallery .flashvideo
{
	height: 520px;
	clear: both;
}
.media_grouping
{
	text-align: center;
	float: left;
	display: block;
	background: #979797;
}
.galsize_thumbnail .media_grouping
{
	width: {$thumbnail}px;
}
.galsize_medium .media_grouping
{
	width: {$medium}px;
}
.galsize_large .media_grouping
{
	width: {$large}px;
	background: #000000;
}
.jcyclegallery .media_assets .asset
{
	display: block;
	width: 100%;
}
.jcyclegallery .media_assets .asset_caption
{
	color: #0AF;
	margin: 0 auto !important;
	padding: 10px;
	background: #000000;
	text-align: center;
}
.jcyclegallery .media_assets .asset_content
{
	background: #000000;
	color: #AEAEAE;
	text-align: left;
	padding: 0 15px 10px;
}
.galsize_large .media_assets .asset IMG
{
	display: block;
	margin: 0 auto;
}
/*-------- Jcycle Thumbs/Navigator--------*/
.jcyclethumbs
{
	float: left;
}
.jcyclethumbs UL.thumbnav
{
	position: relative;
	width: auto;
	float: left;
	margin: 0;
}
.jcyclethumbs UL.thumbnav LI
{
	position: relative;
	clear: none;
	float: left;
	display: inline-block;
	border-bottom: none !important;
	list-style: none inside;
	margin: 0px 4px 4px;
}
.jcyclethumbs UL.thumbnav LI IMG
{
	width: 65px;
	height: 40px;
	padding: 3px;
	background: #CACACA;
	border: 1px solid #898989;
	/*+border-radius:2px;*/
	-moz-border-radius: 2px;
	-webkit-border-radius: 2px;
	-khtml-border-radius: 2px;
	border-radius: 2px 2px 2px 2px;
}
.jcyclethumbs UL.thumbnav LI IMG:hover
{
	background: #787878;
	/*+opacity:100%;*/
	filter: alpha(opacity=100);
	-ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=100);
	-moz-opacity: 1;
	opacity: 1;
	border: 1px solid #333333;
}
.jcyclethumbs UL.thumbnav A
{
	position: relative;
	clear: none;
	outline: none;
}
.galsize_thumbnail .jcyclethumbs
{
	float: left;
	width: {$thumbnail}px;
	/*[disabled]margin:0 10px 0 20px;*/
}
.galsize_thumbnail .jcyclethumbs .simplemedia_controls
{
	/*+placement:shift -14px 0px;*/
	position: relative;
	left: -14px;
	top: 0px;
}
.galsize_thumbnail .jcyclethumbs UL.thumbnav
{
	margin: 10px 0;
}
.galsize_thumbnail .jcyclethumbs UL.thumbnav LI
{
	position: relative;
	clear: none;
	float: left;
	display: inline-block;
	border-bottom: none !important;
	list-style: none inside;
	margin: 0px 6px 4px;
}
.galsize_medium .jcyclethumbs
{
	float: left;
	margin: 5px;
	width: 330px;
}
.galsize_medium .jcyclethumbs .simplemedia_controls
{
	/*+placement:shift -12px -4px;*/
	position: relative;
	left: -12px;
	top: -4px;
}
.galsize_large .jcyclethumbs UL.thumbnav
{
	margin: 5px 25px;
	/*+placement:shift;*/
	position: relative;
	left: 0;
	top: 0;
}
.galsize_large .jcyclethumbs
{
	margin: 10px 0;
	padding-bottom: 10px;
	background: #A7A7A7;
	/*+border-radius:5px;*/
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	-khtml-border-radius: 5px;
	border-radius: 5px 5px 5px 5px;
}
.galsize_large .jcyclethumbs UL.thumbnav
{
	margin: 5px 25px;
	/*+placement:shift;*/
	position: relative;
	left: 0;
	top: 0;
}
.galsize_large .jcyclethumbs .simplemedia_controls
{
	margin: 7px 30px 0 0;
	/*+placement:shift;*/
	position: relative;
	left: 0;
	top: 0;
}
/*-------- Jcycle Gallery Media Controls --------*/
.simplemedia_controls
{
	padding: 5px 0px 5px 0;
	/*[empty]background:;*/
	text-align: right;
}
.simplemedia_controls SPAN
{
	display: inline-block;
	color: #7E7E7E;
	padding: 6px 13px;
	font-size: 11px;
	background: transparent url(./../images/greysquare.png);
	/*+text-shadow:0 -1px 2px #333;*/
	-moz-text-shadow: 0 -1px 2px #333;
	-webkit-text-shadow: 0 -1px 2px #333;
	-o-text-shadow: 0 -1px 2px #333;
	text-shadow: 0 -1px 2px #333;
	/*+box-shadow:0px 0px 12px rgba(0, 0, 0, 0.45);*/
	-moz-box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.45);
	-webkit-box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.45);
	-o-box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.45);
	box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.45);
	border-bottom: 1px solid #666;
	border-right: 1px solid #222;
	/*[disabled]height:40px;*/
}
.simplemedia_controls .jcyclecounter
{
}
.simplemedia_controls .prev SPAN
{
	/*+border-radius:5px 0 0 5px;*/
	-moz-border-radius: 5px 0 0 5px;
	-webkit-border-radius: 5px 0 0 5px;
	-khtml-border-radius: 5px 0 0 5px;
	border-radius: 5px 0 0 5px;
}
.simplemedia_controls .next SPAN
{
	/*+border-radius:0px 5px 5px 0px;*/
	-moz-border-radius: 0px 5px 5px 0px;
	-webkit-border-radius: 0px 5px 5px 0px;
	-khtml-border-radius: 0px 5px 5px 0px;
	border-radius: 0px 5px 5px 0px;
}
.simplemedia_controls SPAN:HOVER
{
	color: #9AB4CC;
}
.media_controls SPAN
{
	color: #FFFFFF;
}
.media_controls
{
	z-index: 1000;
	position: relative;
	top: 40px;
	width: 100%;
	height: 40px;
	margin: -40px 0 0;
	/*[disabled]line-height:38px;*/
	/*[disabled]background:transparent;*/
}
.media_controls .prev
{
	display: block;
	float: left;
	text-align: left;
	width: 50%;
	height: 40px;
	outline: none;
	overflow: hidden;
}
.media_controls .next
{
	float: left;
	overflow: hidden;
	display: block;
	outline: none;
	width: 50%;
	height: 40px;
	text-align: right;
	z-index: 2000;
}
.media_controls .play, .media_controls .pause
{
	overflow: hidden;
	display: block;
	float: left;
	outline: none;
	margin-top: 0;
	height: 40px;
	width: 50%;
	margin-left: 25%;
	margin-top: -40px;
	z-index: 2000;
}
.media_controls SPAN
{
	display: inline-block;
	color: #E6E6E6;
	background: #333;
	padding: 6px 13px;
	margin: 6px;
	font-size: 11px;
	/*+border-radius:5px;*/
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	-khtml-border-radius: 5px;
	border-radius: 5px 5px 5px 5px;
	/*+text-shadow:0 -1px 2px #333;*/
	-moz-text-shadow: 0 -1px 2px #333;
	-webkit-text-shadow: 0 -1px 2px #333;
	-o-text-shadow: 0 -1px 2px #333;
	text-shadow: 0 -1px 2px #333;
	/*+box-shadow:0px 0px 12px rgba(0, 0, 0, 0.45);*/
	-moz-box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.45);
	-webkit-box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.45);
	-o-box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.45);
	box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.45);
	/*[disabled]height:40px;*/
}
.media_controls SPAN:hover
{
	padding: 5px 12px;
	color: #0AF;
	margin: 7px;
}



/*SMOOTH SCROLLER*/
DIV.smooth_thumbnail
{
	position: relative;
	overflow: hidden;
	height: 359px;
	/*+placement:shift;*/
	position: relative;
	left: 0;
	top: 0;
}
DIV.smooth_medium
{
	position: relative;
	overflow: hidden;
	height: 320px;
}
DIV.smooth_large
{
	position: relative;
	overflow: hidden;
	height: {$medium}px;
}
DIV.scrollingHotSpotLeft
{
/*The hotspots have a minimum width of 100 pixels and if there is room the will growand occupy 15% of the scrollable area (30% combined). Adjust it to your own taste.*/
	min-width: 75px;
	width: 10%;
	height: 100%;
/*There is a big background image and it's used to solve some problems I experiencedin Internet Explorer 6.*/
	background-repeat: repeat;
	background-position: center center;
	position: absolute;
	z-index: 200;
	left: 0;
/*The first url is for Firefox and other browsers, the second is for Internet Explorer*/
}
DIV.scrollingHotSpotLeftVisible
{
	background-color: #FFF;
	background-repeat: no-repeat;
	opacity: 0.05;
/*Standard CSS3 opacity setting*/
	-moz-opacity: 0.05;
/*Opacity for really old versions of Mozilla Firefox (0.9 or older)*/
	filter: alpha(opacity = 05);
/*Opacity for Internet Explorer.*/
	zoom: 1;
/*Trigger "hasLayout" in Internet Explorer 6 or older versions*/
}
DIV.scrollingHotSpotRight
{
	min-width: 75px;
	width: 10%;
	height: 100%;
	background-repeat: repeat;
	background-position: center center;
	position: absolute;
	z-index: 200;
	right: 0;
}
DIV.scrollingHotSpotRightVisible
{
	background-color: #FFF;
	background-repeat: no-repeat;
	opacity: 0.05;
	filter: alpha(opacity = 05);
	-moz-opacity: 0.05;
	zoom: 1;
}
DIV.scrollWrapper, DIV.media_thumbs
{
	position: relative;
	overflow: hidden;
	width: 100%;
	height: 100%;
}
DIV.scrollableArea, UL.thumbnav
{
	position: relative;
	width: auto;
	height: 100%;
	margin: 0;
}
#makeMeScrollable
{
	width: 100%;
	position: relative;
	overflow: hidden;
	/*+placement:shift;*/
	position: relative;
	left: 0;
	top: 0;
}
#makeMeScrollable A
{
	position: relative;
	float: left;
	margin: 0;
	padding: 0;
}
#makeMeScrollable DIV.scrollableArea IMG
{
	position: relative;
	float: left;
	margin: 0;
	padding: 0;
}
#thumbnavscrollerable
{
	width: 100%;
	height: 130px;
	position: relative;
	background: #010101;
	z-index: 50000;
}
.galsize_thumbnail #thumbnavscrollerable
{
	height: 62px;
	width: {$thumbnail}px;
}
.galsize_medium #thumbnavscrollerable
{
	height: 92px;
	width: {$medium}px;
}
.galsize_large #thumbnavscrollerable
{
	height: 112px;
	width: {$large}px;
}
#thumbnavscrollerable DIV.media_thumbs
{
	position: relative;
	overflow: hidden;
	width: 100%;
	height: 100%;
	/*+placement:shift;*/
	position: relative;
	left: 0;
	top: 0;
}
#thumbnavscrollerable UL.thumbnav
{
	position: relative;
	width: auto;
	float: left;
	margin: 0;
}
#thumbnavscrollerable UL.thumbnav LI
{
	position: relative;
	clear: none;
	float: left;
	display: inline-block;
	border-bottom: none !important;
	list-style: none inside;
}
#thumbnavscrollerable UL.thumbnav A
{
	position: relative;
	clear: none;
	outline: none;
}
.galsize_medium UL.thumbnav IMG
{
	position: relative;
	margin: 0;
	clear: none;
}
.galsize_thumbnail UL.thumbnav IMG
{
	position: relative;
	margin: 0;
	clear: none;
	padding: 6px 7px 6px 0;
}
.galsize_medium UL.thumbnav IMG
{
	padding: 9px 9px 9px 0;
}
.galsize_large UL.thumbnav IMG
{
	position: relative;
	margin: 0;
	clear: none;
	padding: 8px 8px 8px 0;
}


END;


}


/**
 * CSS JCYCLE ACTION FUNCTION
 */
function css_jcycle()
{
    $atts = array(
        'width' => 100
    );

    build_css_jcycle($atts);
}

add_action('fdt_print_dynamic_css', 'css_jcycle');


/**
 *  REGISTER SCRIPTS FOR JCYCLE
 */
function jcycle_register_script()
{
    $src = get_stylesheet_directory_uri();

    wp_register_script('smoothdiv', $src . '/js/jquery.smoothdivscroll.js', false, '0.8', false);
    wp_register_script('jcyclegallery', $src . '/js/jquery.cycle.all.js', array('smoothdiv'), '2.99', false);
}

add_action('template_redirect', 'jcycle_register_script');


/**
 * REGISTER STYLE FOR JYCLE
 *
 */
function jcycle_register_style()
{
    global $posts;

    wp_register_style('jcycle', get_stylesheet_directory_uri() . '/css/media-galleries/' . 'jcycle.css');

    // FIND THE CURRENT TEMPLATE AND CHECK IF MEDIA GALLERIES ARE ENABLED
    $current_template = thefdt_get_current_template();
    $content_display = of_get_option($current_template . "_content", array(
                                                                          'show_mediagalleries' => false
                                                                     )
    );

    foreach ($posts as $post) {
        $meta = get_post_meta($post->ID, THEMECUSTOMMETAKEY, true);

        if ($meta["gallery_type"] == "jcyclegallery" && $content_display['show_mediagalleries']):
            wp_enqueue_style('jcycle');
        endif;
    }
}

add_action('template_redirect', 'jcycle_register_style');
?>