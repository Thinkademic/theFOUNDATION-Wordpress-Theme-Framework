<?php
/**
 * HOOK INTO show_media_gallery
 * HELPS US EMBED OUR GALLERY VIA THE ADMIN POST EDIT SCREEN
 */
function embed_jquerytools_scrollable()
{
    if (function_exists('embed_jquerytools_scrollable'))
        show_jquerytools_scrollable();
}

add_action('fdt_show_media_galleries', 'show_jquerytools_scrollable');

/**
 * JQUERYTOOLS SCROLLABLE - TEMPLATE TAG
 */
function show_jquerytools_scrollable()
{
    global $post;

    $meta = get_post_meta($post->ID, THEMECUSTOMMETAKEY, true);

    if ($meta["gallery_type"] == "jquerytools_scrollable"):
        $atts = fdt_postmeta_gallery_array();
        echo jquerytools_scrollable($atts);
    endif;
}

/**
 * JQUERYTOOLS SCROLLABLE - SHORT CODE
 */
add_shortcode('jquerytools_scrollable', 'jquerytools_scrollable_shortcodehandler');
function jquerytools_scrollable_shortcodehandler($atts, $content = null)
{

    $atts = shortcode_atts(array(
                                "imagesize" => 'thumbnail',
                                "orderby" => 'menu_order'
                           ), $atts);

    $gallery = get_jquerytools_scrollable($atts);
    return $gallery . $content;
}


/**
 * JQUERYTOOLS SCROLLABLE - ECHO FUNCTION
 *
 * @param null $atts
 */
function jquerytools_scrollable($atts = null)
{
    echo get_jquerytools_scrollable($atts);
}


/**
 * JQUERYTOOLS SCROLLABLE - XHMTL WRAPPER FUNCTION
 *
 * @param null $atts
 * @return string
 */
function get_jquerytools_scrollable($atts = null)
{
    global $wp_query, $post, $paged;

    $meta = get_post_meta($post->ID, THEMECUSTOMMETAKEY, true);
    $size = $meta["gallery_imagesize"];
    if ($size == '')
        return;

    $nav = '
    <!-- "Previous" action -->
    <div class="flownav">
        <!-- "Previous" action -->
        <div class="prev">&laquo; Previous</div>

        <!-- "Next" action -->
        <div class="next">Next &raquo;</div>
    </div>
    ';


    $return = "\n" . '<div id="flowpanes"><div class="items">';
    $return .= jquerytools_scrollable_extractMedia($atts);
    $return .= "</div></div>";

    $return = xtag("div", $nav.$return, "id=jquerytools-scrollable");

    return $return;
}


/**
 * jquerytools_scrollable
 *
 * @param null $atts
 * @return string
 */
function jquerytools_scrollable_extractMedia($atts = null)
{
    global $wp_query, $post, $paged, $post_count;

    $defaults = array(
        'targetid' => $post->ID,
        'querytype' => 'attachment',
        'imagesize' => 'medium',
        'orderby' => 'menu_order'
    );
    $atts = wp_parse_args($atts, $defaults);
    extract($atts, EXTR_SKIP);
    $query_args = build_query_array($atts);

    $temp = $wp_query;
    $wp_query = null;

    $wp_query = new WP_Query();
    $wp_query->query($query_args);

    while ($wp_query->have_posts()) : $wp_query->the_post();
                                      $slidereturn .= '
                <div>
				' . retrieve_media($querytype, $imagesize) . '
                </div>
			';
    endwhile;

    $wp_query = null;
    $wp_query = $temp;
    wp_reset_query();
    return $slidereturn;

}


/**
 * BUILD THE JS JQUERY FOR SCROLLABLE
 *
 * @param null $atts
 * @return
 */
function build_jquery_jquerytools_scrollable($atts = null)
{

    if ($atts == null)
        return;

    extract($atts, EXTR_SKIP);

    print <<<END

/*
 *	jquerytools_scrollable
 *
 *  @link http://flowplayer.org/tools/index.html
 */


jQuery(document).ready(function($) {

	// select #flowplanes and make it scrollable. use circular and navigator plugins
	$("#flowpanes").scrollable({ circular: true, mousewheel: true }){$autoplay}.navigator({

		// select #flowtabs to be used as navigator
		navi: "#flowtabs",

		// select A tags inside the navigator to work as items (not direct children)
		naviItem: 'a',

		// assign "current" class name for the active A tag inside navigator
		activeClass: 'current',

		// make browser's back button work
		history: true

	});

});
END;
}

/**
 *    JQUERY FOR jquerytools_scrollable
 */
function jquery_jquerytools_scrollable()
{
    $pass_postid = explode("-", get_query_var('jqids'));

    foreach ($pass_postid as $key => $postid) {

        // Grabs the meta info assocated with thefdt_gallery
        $meta = get_post_meta($postid, THEMECUSTOMMETAKEY, true);

        $buildarrows = checkbox_truefalse_string($meta["gallery_enablenextprev"]);
        // $delay = set_default_value($meta["jquerytools_scrollable_delay"], 2500);
        // $effect = set_default_value($meta["jquerytools_scrollable_effect"], "fade");

        if ($meta["gallery_imagesize"] != "") :
            $width = get_option($meta["gallery_imagesize"] . '_size_w');
            $height = get_option($meta["gallery_imagesize"] . '_size_h');
        else:
            $width = get_option('medium_size_w');
            $height = get_option('medium_size_h');
        endif;

        $autoplay = checkbox_truefalse_string($meta["gallery_autoplay"]);
        if ($autoplay == 'false')
            $autoplay = '';
        else
            $autoplay = '.autoscroll({ autoplay: false })';

        $atts = array(
            'postid' => $postid,
            'meta' => $meta,
            'fx' => $fx,
            'delay' => $delay,
            'autoplay' => $autoplay,
            'effect' => $effect,
            'buildarrows' => $buildarrows,
            'width' => $width,
            'height' => $height
        );

        if ($meta["gallery_type"] == "jquerytools_scrollable"):
            build_jquery_jquerytools_scrollable($atts);
            $more_jquery_functions = true;
        endif;

    }

}

add_action('fdt_print_dynamic_js', 'jquery_jquerytools_scrollable');


/**
 * BUILD CSS FOR DYNAMIC CSS FILE
 *
 * @param null $atts
 * @return
 *
 */
function build_css_jquerytools_scrollable($atts = null)
{

    $STYLESHEETURI = get_stylesheet_directory_uri();

    if ($atts == null)
        return;

    extract($atts, EXTR_SKIP);

    print <<<END


/*------ JQUERYTOOLS SCROLLABLE -------*/
#jquerytools-scrollable
{
	width: 980px;
	margin: 0 auto;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-o-user-select: none;
	user-select: none;
}
/*------ MAIN DIVS*/
#flowpanes
{
	position: relative;
	/*[disabled]overflow:hidden;*/
	clear: both;
	width: 100%;
	height: 440px;
}
#flowpanes .items
{
	width: 20000em;
	position: absolute;
	clear: both;
	margin: 0;
	padding: 0;
}
#flowpanes .less, #flowpanes .less A
{
	color: #999 !important;
	font-size: 11px;
}
#flowpanes DIV
{
	float: left;
	display: block;
	width: 980px;
	height: 440px;
	background: #D4D4D4;
	font-size: 14px;
}
#flowpanes .items DIV
{
}
/*------ Tabs*/
#flowtabs
{
/*dimensions*/
	width: 995px;
	margin: 0 !important;
	padding: 0;
/*IE6 specific branch (prefixed with "_")*/
	-margin-bottom: -2px;
}
#flowtabs LI
{
	float: left;
	margin: 0;
	padding: 0;
	text-indent: 0;
	list-style-type: none;
}
#flowtabs LI A
{
	display: block;
	float: left;
	height: 50px;
	width: 80px;
	line-height: 14px !important;
	padding: 10px 0px 0px;
	margin: 0px;
	color: #FFF;
	font-size: 11px;
	line-height: 1em !important;
	text-align: center;
	text-decoration: none;
	background: #DF5077;
	margin-right: 10px !important;
	margin-bottom: 10px;
}
#flowtabs A:hover
{
	color: #E9A;
}
#flowtabs A.current
{
	cursor: default;
	color: #E9A;
	line-height: 34px;
}
#flowpanes DIV H2
{
	font-weight: normal;
	color: #DDD;
	letter-spacing: 1px;
	margin: 10px 0 0;
	font-size: 22px;
}
#flowpanes A
{
	color: #CC9;
	font-size: 14px;
}
#flowpanes P, #flowpanes SAMP
{
	color: #CCC;
}
#flowpanes .narrow
{
	padding-right: 160px;
}
/*------ PREV/NEXT NAV*/
.flownav
{
	/*[disabled]+placement:anchor-top-left 0px 0px;*/
	/*[disabled]margin-left:50%;*/
	/*[disabled]z-index:10000;*/
	/*[disabled]width:0px;*/
	/*[disabled]height:0px;*/
}
.flownav DIV
{
	/*[disabled]cursor:pointer;*/
	/*[disabled]display:block;*/
	/*[disabled]width:50px;*/
	/*[disabled]float:left;*/
	/*[disabled]height:440px;*/
	/*[disabled]text-indent:-9999px;*/
}
.flownav DIV:hover
{
	/*[disabled]+opacity:50%;*/
}
.flownav DIV.prev
{
	/*[disabled]+placement:anchor-top-left -540px 113px;*/
	/*[disabled]background:url(http://placehold.it/50x50/EE94AD/000000/&text=<) no-repeat 1px 189px;*/
}
.flownav DIV.next
{
	/*[disabled]+placement:anchor-top-left 490px 113px;*/
	/*[disabled]background:url(http://placehold.it/50x50/EE94AD/000000/&text=>) no-repeat 0px 189px;*/
}
/*------ PREV/NEXT NAV*/
.flownav
{
	z-index: 10000;
	width: 940px;
	height: 0px;
	/*+placement:displace 0px 0px;*/
	position: absolute;
	margin-left: 0px;
	margin-top: 0px;
}
.flownav DIV
{
	cursor: pointer;
	display: block;
	width: 50px;
	float: left;
	height: 439px;
	text-indent: -9999px;
}
.flownav DIV.prev
{
	background: url(http://placehold.it/50x50/EE94AD/000000/&text=<) no-repeat 1px 189px;
	float: left;
}
.flownav DIV.next
{
	background: url(http://placehold.it/50x50/EE94AD/000000/&text=>) no-repeat 0px 189px;
	float: right;
}
.flownav DIV:hover
{
	/*+opacity:50%;*/
	filter: alpha(opacity=50);
	-ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=50);
	-moz-opacity: 0.5;
	opacity: 0.5;
}
/*------ FLOWPANES BASE -------*/
.ie7_html #flowpanes
{
	overflow: hidden;
}




END;

}


/**
 * CSS FOR jquerytools_scrollable
 */
function css_jquerytools_scrollable()
{

    $pass_postid = explode("-", get_query_var('cssids'));

    foreach ($pass_postid as $key => $postid) {
        $meta = get_post_meta($postid, THEMECUSTOMMETAKEY, true);

        if ($meta["gallery_imagesize"] != "") {
            $width = get_option($meta["gallery_imagesize"] . '_size_w');
            $height = get_option($meta["gallery_imagesize"] . '_size_h');
        } else {
            $width = get_option('medium_size_w');
            $height = get_option('medium_size_h');
        }

        $atts = array(
            'width' => $width,
            'height' => $height
        );

        if ($meta["gallery_type"] == "jquerytools_scrollable"):
            build_css_jquerytools_scrollable($atts);
        endif;

    }
}

add_action('fdt_print_dynamic_css', 'css_jquerytools_scrollable');


/**
 *  REGISTER SCRIPTS FOR jquerytools_scrollable
 */
function jquerytools_scrollable_register_script()
{
    $src = get_stylesheet_directory_uri();
    wp_register_script('jquerytools_scrollable', $src . "/js/jquerytools.js", false, '1.2.5', false);
}

add_action('template_redirect', 'jquerytools_scrollable_register_script');


/**
 * REGISTER STYLE FOR jquerytools_scrollable
 *
 * @TODO: PAIR UP EQUIVALENT CSS FILE FOR jquerytools_scrollable
 */
function jquerytools_scrollable_register_style()
{
    global $posts;

    wp_register_style('jquerytools_scrollable', get_stylesheet_directory_uri() . '/css/media-galleries/' . 'jquerytools_scrollable.css');

    foreach ($posts as $post) {
        $meta = get_post_meta($post->ID, THEMECUSTOMMETAKEY, true);

        if ($meta["gallery_type"] == "jquerytools_scrollable"):
            wp_enqueue_style('jquerytools_scrollable');
        endif;
    }

}

add_action('template_redirect', 'jquerytools_scrollable_register_style');

?>