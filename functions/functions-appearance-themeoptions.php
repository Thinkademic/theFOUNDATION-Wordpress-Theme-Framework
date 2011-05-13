<?php







/*	****** ****** ****** ****** ****** ****** ****** ****** ****** ****** ****** ******
*
*	READ OPTIONS AND PREP FOR THEME USAGE
*
****** ****** ****** ****** ****** ****** ****** ****** ****** ****** ****** ****** */

/*	
*	ALTERNATIVE LAYOUT STYLESHEETS READER
*/
function find_alternative_styles() {

	$alt_stylesheet_path = STYLESHEETPATH. '/css/styles';
	$alt_stylesheets = array();
	if ( is_dir($alt_stylesheet_path) ) {
		if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) { 
			while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
				if(stristr($alt_stylesheet_file, ".css") !== false) {
					$alt_stylesheets[$alt_stylesheet_file] = $alt_stylesheet_file;
				}
			}    
		}
	}

	return $alt_stylesheets;
}

/*	
*	FIND LAYOUTS 
*	NEEDS TO WORK
*/
function find_layouts() {

	$alt_stylesheet_path = STYLESHEETPATH. '/css/styles';
	$alt_stylesheets = array();
	if ( is_dir($alt_stylesheet_path) ) {
		if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) { 
			while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
				if(stristr($alt_stylesheet_file, ".css") !== false) {
					$key = strtolower(str_replace('.', '', $alt_stylesheet_file));
					$alt_stylesheets[$key] = $alt_stylesheet_file;
				}
			}    
		}
	}

	return $alt_stylesheets;
}

/*
*	DETERMINE CURRENT TEMPLATE AND USE SELECTED LAYOUT 
*/
function layout_for_current_template(){

		// GRABS THE CURRENT TEMPLATE NAME
		$current_template = thefdt_get_current_template();

		// BASED ON CURRENT TEMPLATE FIND PROPER LAYOUT
		$layout = of_get_option('template_'.$current_template, 'layout-p.css' ); 
		
		return $layout;
}





/*	
*	ENQUEUE STYLES SHEETS
*/
function enqueue_alternative_stylesheets() {
	global $data;	

	$alt_styles_path = get_stylesheet_directory_uri() . '/css/styles/';
		$alt_style = of_get_option( 'alt_stylesheet', 'default.css' ); 
		
		wp_register_style('alt_style',  $alt_styles_path . $alt_style);
		wp_enqueue_style('alt_style');

}
add_action('fdt_enqueue_dynamic_css', 'enqueue_alternative_stylesheets');


/*	
*	ENQUEUE OUR SELECTED LAYOUTS FOR OUR TEMPLATES
*/
function enqueue_template_layout() {
	global $data, $content_width;	

	// LAYOUTS PATH
	$layout_path = get_stylesheet_directory_uri() . '/css/layouts/';
	
		$layout_file_name = layout_for_current_template();
		
		// SET UP A DEFAULT LAYOUT
		if ($layout == '') 
			$layout = 'default.css';

		// CHANGE THE OEMBED SIZES, CURRENTLY WE DISABLE
		// SETTINGS -> MEDIA -> EMBED -> MAX WIDTH
		// AND ALTER THE CONTENT WIDTH
		if($layout_file_name == 'layout-p.css' || $layout_file_name == 'layout-ts-p.css' || $layout_file_name == 'layout-p-bs.css' ) {
			# $content_width = of_get_option(  '$set_content_primary_width', '200' ) + of_get_option(  'set_content_secondary_width', '200' );		
		}

		// REGISTER & ENQUEUE STYLE
		wp_register_style('layout', $layout_path . $layout_file_name );
		wp_enqueue_style('layout');
}
add_action('fdt_enqueue_dynamic_css', 'enqueue_template_layout');











/*
* OUTPUT CUFON RULES TO OUR DYNAMICALLY GENERATED JS FILE
* MAYBE APPLY FILTER ON RULES,
*/
function enable_cufon_rules() {
	$enable_cufon_support = of_get_option('enable_cufon_support', false );
	$cufon_rules = of_get_option('cufon_rules', FALSE);
	
	if( $enable_cufon_support && $cufon_rules ):
		echo htmlspecialchars_decode($cufon_rules, ENT_QUOTES);
	endif;
}
add_action('fdt_print_dynamic_themeoptions_js', 'enable_cufon_rules');	

/*	
*	FIND CUFON FONT-FAMILY NAMES
*/
function find_cufon_fonts() {

	$cufon_path = STYLESHEETPATH. '/fonts/cufon';
	$cuffon_fonts = array();
	if ( is_dir($cufon_path) ) {
		if ($cufon_dir = opendir($cufon_path) ) { 
			while ( ($cufon_file = readdir($cufon_dir)) !== false ) {
				if(stristr($cufon_file, ".js") !== false) {
					$key = strtolower(str_replace('.', '', $cufon_file));
					
						$file_content = file_get_contents($cufon_path."/".$cufon_file); //open file and read
						$delimeterLeft = 'font-family":"';
						$delimeterRight = '"';
						$font_name = read_font_name($file_content, $delimeterLeft, $delimeterRight, $debug = false);

						$cuffon_fonts[$key] = $font_name;
				}
			}    
		}
	}


	return $cuffon_fonts;
}

/*	
*	FIND CUFON FONTS FILENAME
*/
function find_cufon_fonts_filename() {

	$cufon_path = STYLESHEETPATH. '/fonts/cufon';
	$cuffon_fonts = array();
	if ( is_dir($cufon_path) ) {
		if ($cufon_dir = opendir($cufon_path) ) { 
			while ( ($cufon_file = readdir($cufon_dir)) !== false ) {
				if(stristr($cufon_file, ".js") !== false) {
					$key = strtolower(str_replace('.', '', $cufon_file));
					$cuffon_fonts[$key] = $cufon_file;
				}
			}    
		}
	}


	return $cuffon_fonts;
}

/*	
*	ENQUEUE CUFON FONTS
*/
function enqueue_cufon_fonts() {

	$font_array = find_cufon_fonts();
	$font_array_filename = find_cufon_fonts_filename();
	
	$cufon_font_path = get_stylesheet_directory_uri() . '/fonts/cufon/';
	$cufon_font_files = of_get_option( 'cufon_font_files', false ); 

		if($cufon_font_files) {
			foreach ($cufon_font_files as $key => $value) {
				if($value) {
					wp_register_script($key,  $cufon_font_path . $font_array_filename[$key], array('cufon'), "3.1");
					wp_enqueue_script($key);	
				}	
			}
		
		}
		
}
add_action('fdt_enqueue_dynamic_js', 'enqueue_cufon_fonts');

/*	
*	ADMIN - ENQUEUE CUFON FONTS
*/
function admin_enqueue_cufon_fonts() {
	
	$cufon_font_path = get_stylesheet_directory_uri() . '/fonts/cufon/';
	$cufon_font_files = find_cufon_fonts_filename(); 

		if($cufon_font_files) {
			foreach ($cufon_font_files as $key => $value) {
				if($value) {
					wp_register_script($key,  $cufon_font_path . $value, array('cufon'), "3.1");
					wp_enqueue_script($key);	
				}	
			}
		
		}
		
		
}
add_action('admin_print_scripts', 'admin_enqueue_cufon_fonts');


/*	
*	ADMIN - FIND FONTS AND PRINT CUFON SCRIPT
*/
function write_cufon_for_admin() {

	$themename = get_theme_data(STYLESHEETPATH . '/style.css');
	$themename = $themename['Name'];
	$themename = preg_replace("/\W/", "", strtolower($themename) );
						
	$font_array = find_cufon_fonts();
	$cufon_font_files = find_cufon_fonts_filename(); 

		if($cufon_font_files) {
			foreach ($cufon_font_files as $key => $value) {
				if($value) {
					$selector = "#".$themename."-".cufon_font_files."-".$key." + label";
					$font_family = $font_array[$key];
					write_cufon_script($selector, $font_family);	
				}	
			}
		
		}
		
}

 
/*	
*	ADMIN - PRINT CUFON SCRIPT
*/
function write_cufon_script( $selector, $font_family ){
print <<<END

	Cufon.replace('{$selector}', { fontFamily: '{$font_family}', hover: true });	
END;
}


/*
*	ADMIN - FIND FONT-FAMILY NAME
*/
function read_font_name($inputStr, $delimeterLeft, $delimeterRight, $debug = false) {
 
	$posLeft = strpos($inputStr, $delimeterLeft);
	if ($posLeft === false) :
		if ($debug)
			echo "Warning: left delimiter '{$delimeterLeft}' not found";
		return false;
	endif;
 
	$posLeft += strlen($delimeterLeft);
	$posRight = strpos($inputStr, $delimeterRight, $posLeft);
 
	if ($posRight === false) :
		if ($debug) 
				echo "Warning: right delimiter '{$delimeterRight}' not found";
		return false;
	endif;
 
	return substr($inputStr, $posLeft, $posRight - $posLeft);
} 

 
 
 
 
 
 


/*
*	GET THE CURRENT TEMPLATE BEING USED
*	AND LOAD IT INTO A GLOBAL VARIALBLE
*	
*	CODEX: http://wordpress.stackexchange.com/questions/10537/get-name-of-the-current-template-file
*/
function var_template_include( $current ){
	// Take a peek at the Current Template location
	// Parse it and load it into a global variable
	// For later usage.
	
	$basename = basename($current);
	$templatename = substr($basename, 0,strrpos($basename,'.')); 
    $GLOBALS['current_theme_template'] = $templatename;
	
    return $current;
}
add_filter( 'template_include', 'var_template_include', 1000 );

/*
*	FUNCTION TO GET THE THE CURRENT THEME TEMPLATE
*	ONLY AVAILIABE AFTER THE TEMPLATE IS SET.
*	WILL NOT BE ACCESSIBLE TO ANY FILTERS OR HOOKS THAT
*	OCCUR BEFORE THE TEMPLATE IS SET.
*/
function thefdt_get_current_template( $echo = false ) {
    if( !isset( $GLOBALS['current_theme_template'] ) ) {
		return false;
    } if( $echo ) {
		echo $GLOBALS['current_theme_template'];
    } else {
        return  $GLOBALS['current_theme_template'];
	}	
}
	
	







	
/*
*	TT - POST HEADER ACTION HOOK
*/
function thefdt_loop_header() {
	do_action("thefdt_loop_header");
}

/*
*	RETRIEVE THE POST HEADER
*	LABEL/TEXT THAT GOES BEFORE THE LOOP
*	WHILE DISPLAYING THE INDEX/HOME/FRONT-PAGE TEMPLATES
*/
function thefdt_get_loop_header() {
	
	// FIND THE CURRENT TEMPLATE
	$current_template = thefdt_get_current_template();	
	$headertext = of_get_option(  $current_template."_loop_header" , '' );
	
	$headertext = xtag( 'h2', $headertext, 'id=headline' );
	
	echo $headertext;
}
add_action('thefdt_loop_header', 'thefdt_get_loop_header');



/*
*	[TT] CONTENT LOOP ACTION HOOK
*/
function thefdt_loop_content() {
	do_action("thefdt_loop_content");
}


/*
*	CONTENT LOOP FUNCTION
*/
function thefdt_get_loop_content() {

	// FIND THE CURRENT TEMPLATE
	$current_template = thefdt_get_current_template();

	// GRAB THE RIGHT OPTION VALUE
	$content_display = of_get_option(  $current_template."_content" , array ( 
						'show_mediagalleries' => true,				
						'the_post_thumbnail' => true,
						'the_content' => true,
						'the_excerpt' => true
						)
					);
					
	print_r($current_display);				
					
	if( $content_display['show_mediagalleries'])
		show_mediagalleries();
	
	if( $content_display['the_post_thumbnail'])	
		the_post_thumbnail('medium', array('class' => 'alignleft'));
	
	if( $content_display['the_content'])		
		the_content();
		
	if( $content_display['the_excerpt'])		
		the_excerpt();
		

		
		
	wp_link_pages();
}
add_action('thefdt_loop_content', 'thefdt_get_loop_content');







/*
*	POST FOOTER ACTION HOOK
*/
function thefdt_loop_footer() {
	do_action("thefdt_loop_footer");
}

/*
*	RETRIEVE THE POSTS FOOTER
*	LABEL/TEXT THAT GOES BEFORE THE LOOP
*	WHILE DISPLAYING THE INDEX/HOME/FRONT-PAGE TEMPLATES
*/
function thefdt_get_loop_footer() {
	
	// FIND THE CURRENT TEMPLATE
	$current_template = thefdt_get_current_template();	
	$foottext = of_get_option(  $current_template."_loop_footer" , '' );
	
	$foottext = xtag( 'span', $foottext, 'id=footline' );
	
	echo $foottext;
}
add_action('thefdt_loop_footer', 'thefdt_get_loop_footer');









/*
*	RETRIEVE THE ITEM META
*	THIS FUNCTION IS USED BY itemhead.php & itemfoot.php
*/
function thefdt_get_item_meta( $location = "head"){

	$current_template = thefdt_get_current_template();
	$meta_display_key = $current_template.'_item'.$location.'_meta';
	$meta_display = of_get_option( $meta_display_key , array ( 
						'author' => false,
						'date' => false,
						'time' => false,
						'comments' => false,
						'category' => false,
						'tag' => false						
					)
			);

			
	// LOOP THROUGH ARRAY AND CALL CORRESPONDING FUNCTION
	// IF WE EDIT THE ARRAY ORDER, THEN WE CAN EDIT THE OUTPUT ORDER
	// FIGURE OUT HOW TO DO THIS LATER
	$meta = "";		
	foreach ($meta_display as $key => $value) {
		if($value){
			$function_name = "get_".$key."_meta";
			$meta = $meta.$function_name();
		}
	}

	$post_meta = xtag( 'div', $meta, "class=metadata");
	$post_meta = apply_filters( 'thefdt_get_posts_meta_'.$location , $post_meta );
	echo $post_meta;
}


/*
*	RETURNS THE DATE META INFORMATION
*/
function get_date_meta(){
		$date_meta = get_the_date();
		$date_meta = xtag('span', $date_meta, 'class=date-meta');
		
		$date_meta = apply_filters('date_meta', $date_meta);
		return $date_meta;
}


/*
*	RETURNS THE DATE META INFORMATION
*/
function get_time_meta(){
		$time_meta = get_the_time();
		$time_meta = xtag('span', $time_meta, 'class=time-meta');
		
		$time_meta = apply_filters('date_meta', $time_meta);
		return $time_meta;
}


/*
*	RETURNS AUTHOR META
*/
function get_author_meta() {
		$author_meta_format = 	__('by %s', TEXTDOMAIN );
		$author_meta = sprintf( $author_meta_format , '<a href="' . get_author_posts_url(get_the_author_meta( 'ID' )) . '">' . get_the_author() . '</a>' ); 	
		$author_meta = xtag('span', $author_meta, 'class=author-meta' );
		
		$author_meta = apply_filters('thefdt_author_meta', $author_meta);
		return $author_meta;
}



/*
*	RETURNS COMMENTS META
*/
function get_comments_meta() {
	// CAPTURE ECHO OUTPUT
	ob_start();

			comments_popup_link( 
				'<span class="no-comments-meta">'.__('0 Comments',TEXTDOMAIN).'</span>', 
				'<span class="one-comments-meta">'.__('1 Comment',TEXTDOMAIN).'</span>', 
				'<span class="many-comments-meta">% '.__('Comments',TEXTDOMAIN).'</span>', 
				'comments-meta', 
				'<span class="closed-comments-meta">'.__(' Comments Closed',TEXTDOMAIN).'</span>'
			); 
		
		$comment_meta = ob_get_contents();
	ob_end_clean();
	
	$comment_meta = apply_filters('thefdt_comment_link', $comment_meta);
	return $comment_meta;
}


/*
*	RETURNS TAGS META
*/
function get_tag_meta(){
		$tags_meta_format = __('Tags: %s', TEXTDOMAIN );
		if( $tag_list =  get_the_tag_list( '', ', ' ) )
			$tags_meta = sprintf( $tags_meta_format, $tag_list );
		$tags_meta = xtag('span', $tags_meta, 'class=tags-meta');
		
		$tags_meta = apply_filters('thefdt_tags_meta', $tags_meta);
		return $tags_meta;
}


/*
*	RETURNS CATEGORY META
*/
function get_category_meta(){
		$category_meta_format = __('Category %s', TEXTDOMAIN );
		$category_meta = sprintf( $category_meta_format, get_the_nice_category(', ', ' &amp; ' ) );
		$category_meta = xtag('span', $category_meta, 'class=category-meta');
		
		$category_meta = apply_filters('thefdt_category_meta', $category_meta);
		return $category_meta;
}










/*
* OUTPUT JQUERY FOR SUCKERFISH DROP DOWNS
*/
function enable_suckerfish_dropdown() {
	$enable_dropdown = of_get_option('enable_suckerfish_dropdown', false );
	if( $enable_dropdown ){

print <<<END
	$(function(){
		$(".masthead-menu").superfish(); 
	});
	
END;

	}
}
add_action('fdt_print_dynamic_themeoptions_js', 'enable_suckerfish_dropdown');


/*
*	JQUERY FOR POST EDIT LINKS
*/
function thefdt_post_edit_links() {

print <<<END

	$(function(){
		/*
		* ADMIN EDIT LINKS
		*/
		$(".editlink").hide();
		$(".itemhead").hoverIntent(
				function() { 
					$(".editlink",this).fadeIn();
				},
				function() { 
					$(".editlink",this).hide(); 
				}
		);
	});
	
END;
}
add_action('fdt_print_dynamic_themeoptions_js', 'thefdt_post_edit_links');







/*
*	OPTIONS FRAMEWORK JQUERY
*/
function optionsframework_custom_scripts() { ?>

<!-- functions-appearance-options.php -->
<script type="text/javascript">

jQuery(document).ready(function() {


	/* BODY FONT OPTIONS :: APPEARANCE > THEMEOPTIONS > TYPOGRAPHY */
	jQuery('#section-body_font_css .heading').hide();

	jQuery('#enable_body_font_css').click(function() {
  		jQuery('#section-body_font_css').fadeToggle(400);
	});

	if (jQuery('#enable_body_font_css:checked').val() !== undefined) {
		jQuery('#section-body_font_css').show();
	} else {
		jQuery('#section-body_font_css').hide();	
	}

	/* CUFON FONT OPTIONS  :: APPEARANCE > THEMEOPTIONS > TYPOGRAPHY */
	jQuery('#section-cufon_font_files .heading').hide();
	jQuery('#section-cufon_rules .heading').hide();		

	jQuery('#enable_cufon_support').click(function() {
  		jQuery('#section-cufon_font_files').fadeToggle(400);
  		jQuery('#section-cufon_rules').fadeToggle(400);
	});

	if (jQuery('#enable_cufon_support:checked').val() !== undefined) {
		jQuery('#section-cufon_font_files').show();
		jQuery('#section-cufon_rules').show();		
	} else {
		jQuery('#section-cufon_font_files').hide();
		jQuery('#section-cufon_rules').hide();		
	}
	
	/* HYPERLINK COLORS :: APPEARANCE > THEMEOPTIONS > HYPERLINKS */
	jQuery('#section-body_href_link_value .heading').hide();
	jQuery('#section-body_href_visited_value .heading').hide();
	jQuery('#section-body_href_hover_value .heading').hide();
	jQuery('#section-body_href_active_value .heading').hide();	
		
	jQuery('#enable_body_href').click(function() {
		jQuery('#section-body_href_link_value').fadeToggle(400);
		jQuery('#section-body_href_visited_value').fadeToggle(400);
		jQuery('#section-body_href_hover_value').fadeToggle(400);
		jQuery('#section-body_href_active_value').fadeToggle(400);
	});

	if (jQuery('#enable_body_href:checked').val() !== undefined) {
		jQuery('#section-body_href_link_value').show();
		jQuery('#section-body_href_visited_value').show();
		jQuery('#section-body_href_hover_value').show();
		jQuery('#section-body_href_active_value').show();		
	} else {
		jQuery('#section-body_href_link_value').hide();
		jQuery('#section-body_href_visited_value').hide();
		jQuery('#section-body_href_hover_value').hide();
		jQuery('#section-body_href_active_value').hide();	
	}

	
	/* TOGGLE INFO HEADING P */
	jQuery('#of-nav a').click(function() {
  			jQuery('#of_container #content .group .section-info P').show();
	});

	jQuery('#of_container #content .group .section-info').click(function() {
  			jQuery('p', this).slideToggle();
	});
	
});


	<?php write_cufon_for_admin();	 ?>
	
</script>
 
<?php
}
if ( function_exists( 'of_get_option' ) ) {
	add_action('optionsframework_custom_scripts', 'optionsframework_custom_scripts');
}




/*
*	OUTPUT CSS RULES FOR BODY FONT
*/
function body_font_css_output() {

$typography = of_get_option('body_font_css');
$enable = of_get_option('enable_body_font_css', false);

if ($enable) :
	$fontsize = $typography['size'];
	$fontface = $typography['face'];
	$fontstyle = $typography['style'];
	$fontcolor = $typography['color'];

print <<<END

	BODY {
		font: {$fontstyle} {$fontsize} {$fontface};
		color: {$fontcolor};
	}
END;
endif;
}
add_action('fdt_print_dyanmic_css','body_font_css_output');



/*
*	OUTPUT CSS RULES FOR HREF
*/
function body_href_link_css_output() {
$link = of_get_option( 'body_href_link_value', '#333333' );
$visited = of_get_option( 'body_href_visited_value', '#333333' );
$hover = of_get_option( 'body_href_hover_value', '#333333' );
$active = of_get_option( 'body_href_active_value', '#333333' );

print <<<END
	BODY A:link {
		color: {$link};
	}
	BODY A:visited {
		color: {$visited};
	}
	BODY A:hover {
		color: {$hover};
	}
	BODY A:active 	{
		color: {$active};
	}
END;
}
if( of_get_option('enable_body_href', false ) )
	add_action('fdt_print_dyanmic_css','body_href_link_css_output');


	
	
// GOOGLE WEBFONT LOAD
function gfonts_api() {

	$gf1 = 'Tangerine';
	
	$addfont = <<<ADDFONTS

<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js'></script>
<script type='text/javascript'>WebFont.load({ google: {families: [ '$gf1' ]}})</script>
<style type='text/css'>.itemtext {font-family: '$gf1', serif;}</style>

ADDFONTS;

	echo $addfont;
} 
#add_action('wp_head','gfonts_api');









?>