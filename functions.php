<?php
/**************************************************************
 [-01] SETUP UNIVERSAL CONTANTS IF THEY HAVE NOTE BEEN
  SET BY CHILD THEMES
**************************************************************/
define( 'TEXTDOMAIN', 'thefdt' );
define( 'THEMECUSTOMMETAKEY', '_fsl_media_options' );
if ( ! isset( $content_width ) ) $content_width = 540;		// SHOULD BE OVER WRITTEN IN CHILD THEMES

/**************************************************************
 [00] SANDBOX FUNCTIONS :: SEE FILE COMMENTS
**************************************************************/
require_once(TEMPLATEPATH . '/functions/functions-sandbox.php');

/**************************************************************
 [01] PARENT THEME APPEARANCE FEATURES
**************************************************************/
require_once(TEMPLATEPATH . '/functions/functions-appearance-themeoptions.php');			// WORKS WITH OPTIONS FRAMEWORK BY DEVINSAYS
require_once(TEMPLATEPATH . '/functions/functions-appearance-sidebars.php');
require_once(TEMPLATEPATH . '/functions/functions-appearance-header.php');
require_once(TEMPLATEPATH . '/functions/functions-appearance-widgets.php');
require_once(TEMPLATEPATH . '/functions/functions-appearance-menu.php');
require_once(TEMPLATEPATH . '/functions/functions-appearance-background.php');

/**************************************************************
 [01] CUSTOM POST TYPES CUSTOM POST TYPES LOADED FROM PARENT THEME 
 ENABLED IN ADMIN > APPEARANCE > THEME OPTIONS > CUSTOM POST TYPE
**************************************************************/
if( of_get_option( 'enable_custom_posttype_event', false ) == true )
	require_once(TEMPLATEPATH . '/functions/functions-posttype-event.php');
if( of_get_option( 'enable_custom_posttype_portfolio', false ) == true )
	require_once(TEMPLATEPATH . '/functions/functions-posttype-portfolio.php');
if( of_get_option( 'enable_custom_posttype_designer', false ) == true )
	require_once(TEMPLATEPATH . '/functions/functions-posttype-designer.php');
if( of_get_option( 'enable_custom_posttype_swatch', false ) == true )
	require_once(TEMPLATEPATH . '/functions/functions-posttype-swatch.php');
if( of_get_option( 'enable_custom_posttype_product', false ) == true)
	require_once(TEMPLATEPATH . '/functions/functions-posttype-product.php');
if( of_get_option( 'enable_custom_posttype_post', false ) == true)
	require_once(TEMPLATEPATH . '/functions/functions-posttype-post.php');
if( of_get_option( 'enable_custom_posttype_dictionary', false ) == true )
	require_once(TEMPLATEPATH . '/functions/functions-posttype-dictionary.php');


/**************************************************************
 [03] ENQUEUE JQUERY + JQUERY LIBRARIES
**************************************************************/
require_once(TEMPLATEPATH . '/functions/functions-jquery.php');


/**************************************************************
 [04] ENABLE DYANMIC GENERATRED JS + CSS
**************************************************************/
require_once(TEMPLATEPATH . '/functions/functions-dynamic-js.php');
require_once(TEMPLATEPATH . '/functions/functions-dynamic-css.php');


/**************************************************************
 [05] MEDIA FUNCTIONS
**************************************************************/
require_once(TEMPLATEPATH . '/functions/functions-media-galleries.php');
require_once(TEMPLATEPATH . '/functions/functions-media-formats.php');


/**************************************************************
 [07] DEPRECATED FRAME WORK FUNCTIONS
**************************************************************/
require_once(TEMPLATEPATH . '/functions/functions-deprecated.php');


/**************************************************************
 [08] REMOVE WORDPRESS DEFAULT FEATURES
**************************************************************/
require_once(TEMPLATEPATH . '/functions/functions-remove-features.php');


/**************************************************************
 [09] ADMIN DASHBOARD SETTINGS
**************************************************************/
require_once(TEMPLATEPATH . '/functions/functions-dashboard.php');

/**************************************************************
 [10] FONTS
**************************************************************/
require_once(TEMPLATEPATH . '/functions/functions-fonts.php');

/**************************************************************
 [11] ADMIN BAR
**************************************************************/
require_once(TEMPLATEPATH . '/functions/functions-adminbar.php');


/**************************************************************
 FOUNDATION THEME SETUP
 01.06.2011
**************************************************************/
if ( function_exists('thefdt_install_options') ) { thefdt_install_options(); }
thefdt_setup();
function thefdt_setup() {
		
		add_theme_support( 'post-thumbnails' );	
																		
	#	AUTOMATIC FEED LINKS
		add_theme_support('automatic-feed-links');	

	#	THEME IMAGE FORMATS
		setup_theme_image_formats();
	
	#	COPIED FROM THE WEB SOMEWHERE, HAS TO DO WITH GETTING OTHER POST TYPES TO SHOW UP IN THE QUERY ON THE HOME PAGE
		#add_filter( "pre_get_posts", "my_get_posts" );
		function my_get_posts( $query ) {
			if ( is_home() || is_feed() )
			#  ADDING ADDITIONAL POST TYPES WILL MAKE IT SHOW UP
			$query->set( "post_type", array( 'post' ) );

			return $query;
		}
	
}




/**************************************************************
 ADMIN ENHANCEMENT :: ADD IMAGE PREVIEW COLUMNS
 REF:  http://wpengineer.com/display-post-thumbnail-post-page-overview
**************************************************************/
add_filter( 'manage_posts_columns', 'add_image_preview_column' );
add_action( 'manage_posts_custom_column', 'add_image_value', 10, 2 );

add_filter( 'manage_pages_columns', 'add_image_preview_column' );
add_action( 'manage_pages_custom_column', 'add_image_value', 10, 2 );

function add_image_preview_column($cols) {
	$cols['thumbnail'] = __('Images');
	return $cols;
} 

function add_image_value($column_name, $post_id) {

	$thumbnail_size = "minithumbnail";

	if ( 'thumbnail' == $column_name ) :
	
		#	IMAGE FROM GALLERY
			$attachments = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC') );
			
			$thumb = "";
			foreach ( $attachments as $attachment_id => $attachment ) {
				$img = wp_get_attachment_image( $attachment_id, $thumbnail_size , true );
				$urlbase = home_url();
				$thumb .= '<a href="'.$urlbase.'\wp-admin\media.php?attachment_id='.$attachment->ID.'&action=edit" title="'.$attachment->post_title.'"\>'.$img.'</a>';	
			}

			
			if ( isset($thumb) && $thumb )
				echo $thumb;
			else
				echo __('None');

		
	endif;
}



/**************************************************************
 ADD ADDITIONAL USER PROFILE FIELDS
**************************************************************/
add_filter('user_contactmethods','add_more_contactmethods',10,1);
function add_more_contactmethods( $contactmethods ) {

    #	REMOVE UNWANTED FIELDS
		unset($contactmethods['aim']);
		unset($contactmethods['jabber']);
		unset($contactmethods['yim']);

    #	ADD TWITTER
		$contactmethods['twitter'] = 'Twitter';

    #	ADD PHONE
		$contactmethods['phone'] = 'Phone Number';

    #	ADD FAX
		$contactmethods['fax'] = 'Fax Number';

    #	ADD ADDRESS
		$contactmethods['address1'] = 'Address Line 1';
		$contactmethods['address2'] = 'Address Line 2';
		$contactmethods['city'] = 'City';
		$contactmethods['state'] = 'State/Province';
		$contactmethods['country'] = 'Country';	
		$contactmethods['zip'] = 'Zip Code';


    return $contactmethods;
}



/**************************************************************
	TEMPLATE TAG - SITE CONTACT INFORMATION
**************************************************************/
function get_site_contactinfo( $userprofile = "admin"){

	$userprofile = get_the_author_meta( "ID", $userprofile ); 
		
	$phone = xtag( "span", get_the_author_meta("phone", $userprofile), "class=phone", "", "<br />" );	
	$fax = xtag( "span", get_the_author_meta("fax", $userprofile), "class=fax", "", "<br />" );	
	
	$address1 = xtag( "span", get_the_author_meta("address1", $userprofile), "class=address1", "", "<br />" );
	$address2 = xtag( "span", get_the_author_meta("address2", $userprofile), "class=address2", "", "<br />" );
	$city = xtag( "span", get_the_author_meta("city", $userprofile), "class=city", "", ", " );
	$state = xtag( "span", get_the_author_meta("state", $userprofile), "class=state");
	$country = xtag( "span", get_the_author_meta("country", $userprofile), "class=country", ", ", "" );	
	$zip = xtag( "span", get_the_author_meta("zip", $userprofile),  "class=zip", "", "<br />" ); 	
	
	$emailaddress = get_the_author_meta("user_email", $userprofile);
	$email = xtag( "a", $emailaddress,  "class=email&href=mailto:$emailaddress", "", "<br />" ); 	

	
	$contactinfo = "
		$address1
		$address2
		$city
		$state
		$country	
		$zip
		$phone
		$fax
		$email
	";
	
	$contactinfo = xtag( "div", $contactinfo, "class=contactinfo" );
	
	return $contactinfo;
	
}




/***************************************************************
 FUNCTION MY_OEMBED_WMODE
 FIX OEMBED WINDOW MODE FOR FLASH OBJECTS 
 AND ADDS A WRAPPER DIV
***************************************************************/
add_filter('embed_oembed_html', 'my_oembed_wmode', 1);
function my_oembed_wmode( $embed ) {
    if ( strpos( $embed, '<param' ) !== false ) {
        $embed = str_replace( '<embed', '<embed wmode="transparent" ', $embed );
        $embed = preg_replace( '/param>/', 'param><param name="wmode" value="transparent" />', $embed, 1);
		$embed = '<div class="oembed">'.$embed.'</div>';
    }
    return $embed;
}





/**************************************************************
	SETS A DEFAULT VALUE IF PASSED VALUE IS EMPTY 
**************************************************************/
function set_default_value( $val, $defaultvalue) {
	if($val == "") {
		$val = $defaultvalue;
	}
	return $val;
}






/************************************************************** 

	THE FOUNDATION THEME FRAMEWORK DEPENDS ON CUSTOM 
	FUNCTIONS FOR OUTPUTING HTML. 
	
	THESE HELPER FUNCTIONS HELP CREATE CLEAN ERROR 
	FREE XTHML

**************************************************************/

/**************************************************************
	CONSTRUCT XHTML TAGS USING A FUNCTION
**************************************************************/
function xhtmltag( $tag = "div", $content, $class = "", $id = "" ) {
	
	if( $content == "" ) :
		return null;
	endif;

	$divxhmtl = "";
	 if($id != "") :
		$id = ' id="'.$id.'"';
	 endif;
	 
	 if($class != "") :
		$class = ' class="'.$class.'"';
	 endif;
	 
	 if($content != "") :
		$divxhtml  = '<'.$tag.$id.$class.'>';
		$divxhtml .= "\n\t".$content;
		$divxhtml .= "\n"."</$tag>"."\n";
	 endif;	 		 
	return $divxhtml;
}

/**************************************************************
	CONSTRUCT XHTML TAGS USING A FUNCTION, $ARS IS A QUERY STRING
	THAT WILL BE EXTRACTED.
**************************************************************/
function xtag( $tag = "div", $content, $args = null, $precontent = "", $postcontent = "" ) {

	if($tag) {
	
	//	GET XHTML ELEMENT ATTRIBUTES
		$defaults = array(
			'id' => "",
			'class' => "",
			'href' => "",
			'title' => "",
			'rel' => "",			
		);
		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );
		
		
		if( $content == "" ) :
			return false;
		endif;

		$divxhmtl = "";
		if($id != "") :
			$id = ' id="'.$id.'"';
		endif;

		if($class != "") :
			$class = ' class="'.$class.'"';
		endif;
		
		if($href != "") :
			$href = ' href="'.$href.'"';
		endif;
		
		if($title != "") :
			$title = ' title="'.$title.'"';
		endif;

		if($rel != "") :
			$rel = ' rel="'.$rel.'"';
		endif;
		

		if($content != "") :
			$divxhtml  = "\n".'<'.$tag.$id.$class.$href.$title.$rel.'>';
			$divxhtml .= "\n\t".$precontent.$content.$postcontent;
			$divxhtml .= "\n"."</$tag>";
		endif;	
		
		
		return $divxhtml;
	} else {
		return $content;
	}
}


/**************************************************************
	GRABS AN ATTRIBUTE FROM VALID XHTML TAG
**************************************************************/
function xattribute($attrib, $tag){
	$re = '/' . preg_quote($attrib) . '=([\'"])?((?(1).+?|[^\s>]+))(?(1)\1)/is';
	if (preg_match($re, $tag, $match)) {
		return urldecode($match[2]);
	}
	return false;
}






/**************************************************************
	UI - CREATE A SELECTBOX
**************************************************************/
function form_selectbox( $meta_key = "", $customfieldname, $labeldecription, $options = null, $keyaslabel = true ) {
	global $post;

	
	if($meta_key == "") {
		$custom = get_post_custom($post->ID);				// Grab all Custom Fields from Post into array
		$selectbox_value = $custom[$customfieldname][0];		// Grab specific value needed
	} else {
		$post_meta_field = get_post_meta($post->ID, $meta_key, true);
		$selectbox_value = $post_meta_field[$customfieldname];						// Grabs a specific Custom Field from the Post
	}
	
	
	// MAPPING, ARRAY KEY BECOMES LABEL, ARRAY VALUE MAPS TO OPTION TAG'S VALUE ATTRIBUTE
	foreach ( $options as $key => $optionvalue ){		// Build our XHTML <option> tag
		$label  = ($optionvalue == "")  ? "None" : $key;
		$theoptions .= "\n".'<option name="'.$customfieldname.'"  value="'.$optionvalue.'" '.selected( $selectbox_value, $optionvalue, false ).'>'.ucfirst($label).'</option>';	
	}

	$xhtml = '											
		  <p>	
			<label for="'.$customfieldname.'">'.$labeldecription.'</label>
			<select name="'.$customfieldname.'"> 
			'.$theoptions.'
			</select>

		  </p>
	';

	return $xhtml;	
}



/**************************************************************
	UI - CREATES RADIO INPUTS 
**************************************************************/
function form_radio( $meta_key = "", $customfieldname, $labeldecription, $options = null ) {
	global $post;
	
	if($meta_key == "") {
		$custom = get_post_custom($post->ID);										// Grab all Custom Fields from Post into array
		$select_value = $custom[$customfieldname][0];							// Grab specific value needed
	} else {
		$post_meta_field = get_post_meta($post->ID, $meta_key, true);
		$select_value = $post_meta_field[$customfieldname];					// Grabs a specific Custom Field from the Post
	}
	
	
	foreach ( $options as $key => $optionvalue ){									// Build our XHTML <option> tag	inputs	
		$isitchecked = checked( $select_value, $optionvalue, false );
		$theoptions .= "\n".'<input name="'.$customfieldname.'" type="radio" '.$isitchecked.' value="'.$optionvalue.'" />'.'<label>'.$key.'</label><br />';
	}


	$xhtml .= '
			<p>
				<label>'.$labeldecription.'</label><br />
				'.$theoptions.'	
			</p>
	';

	return $xhtml;	

}


/**************************************************************
	UI - CREATES CHECKBOX INPUT
**************************************************************/
function form_checkbox( $meta_key = "", $customfieldname, $labeldecription, $options = null ) {
	global $post;
	
	if($meta_key == "") {
		$custom = get_post_custom($post->ID);									// Grab all Custom Fields from Post into array
		$check_value = $custom[$customfieldname][0];							// Grab specific value needed
	} else {
		$post_meta_field = get_post_meta($post->ID, $meta_key, true);		
		$check_value = $post_meta_field[$customfieldname];						// Grabs a specific Custom Field from the Post
	}
	
	
	$inputvalue = checked( $check_value, 'on', false );
	$xhtml .=
		 '<p>
			<label>'.$labeldecription.'</label>
			<input name="'.$customfieldname.'" type="checkbox" '.$inputvalue.' />
		  </p>';

	return $xhtml;	

}


/**************************************************************
	UI - CREATES TEXT INPUT
**************************************************************/
function form_textinput( $meta_key = "", $customfieldname, $labeldecription, $options = null ) {
	global $post;
	
	if($meta_key == "") {
		$custom = get_post_custom($post->ID);									// Grab all Custom Fields(post_meta table entry) from Post
		$inputvalue = $custom[$customfieldname][0];								// Grab specific value needed
	} else {
		$post_meta_field = get_post_meta($post->ID, $meta_key, true);	
		$inputvalue = $post_meta_field[$customfieldname];						// Grabs a specific Custom Field from the Post
	}
	
	
	$xhtml .= '
		  <p>
			<label>'.$labeldecription.'</label>
			<input name="'.$customfieldname.'" value="'.$inputvalue.'" />
		  </p>';		  

	return $xhtml;	

}





/**************************************************************
	TT - CREATES THE XHTML FOR DATE INFORMATION
**************************************************************/
function retrieve_datebox_markup() {
	global $post;

	$datebox = '
	<div class="datebox" title="'.get_the_time('F jS, Y').'">
		<span class="dayname">'.get_the_time('D').'</span>
		<span class="theMonth">'.get_the_time('M').'</span>
		<span class="theDay">'.get_the_time('j').'</span>
		<span class="theYear">'.get_the_time('Y').'</span>
	</div>
	';
	
	return $datebox;
}

 






/**************************************************************
  TT - FOUDATION RETRIEVE TAXONOMY LIST
**************************************************************/
function retrieve_taxonomy_list( $taxonomy = 'category' ) {

	$taxonomy_li = wp_list_categories( array (
				'show_count'  => 0,
				'title_li' => '',
				'echo' => false,
				'taxonomy'  => $taxonomy			
			)	
	);
	
	$output =  '
			<div id="listing-'.$taxonomy.'" class="listing">
				<h3>Categories</h3>
				<ul>
					'.$taxonomy_li.'
				</ul>
			</div>
			
	';		
	
	return $output;
}





/**************************************************************
 CUSTOM TEMPLATE TAG FOR POST CUSTOM FIELDS
**************************************************************/
function get_custom_field_value($szKey, $bPrint = false) {
	global $post;
	$szValue = get_post_meta($post->ID, $szKey, true);
	if ( $bPrint == false ) return $szValue; else echo $szValue;
}


/**************************************************************
 GET ID BY PAGE NAME
 CONSIDER :: http://codex.wordpress.org/Function_Reference/get_page_by_title
**************************************************************/
function getidbypagename($page_name, $returntype = "OBJECT" ) {
	global $wpdb;
	$page_name_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$page_name."'");
	return $page_name_id;
	
}


/**************************************************************
 GET PAGE NAME BY ID
**************************************************************/
function getpagenamebyid($idnum) {
	global $wpdb;
	$id = $wpdb->get_var("SELECT post_name FROM $wpdb->posts WHERE ID = '".$idnum."'");
	return $id;
	
}


/**************************************************************
 GET ID BY POST NAME
 CONSIDER :: http://codex.wordpress.org/Function_Reference/get_page_by_title
**************************************************************/
	function get_page_by_post_name($post_name, $output = OBJECT, $post_type = 'page' ) {
	global $wpdb;

    $page = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s", $post_name, $post_type ) );
	
	if ( $page )
		return get_page($page, $output);

	return null;
}



/**************************************************************
 CHECK IF CURRENT PAGE IN THE QUERY OBJECT IS A SUBPAGE
 CONDITIONAL TAGS
**************************************************************/
function is_subpage() {
	global $post;                                					// load details about this page
		if ( is_page() && $post->post_parent ) {      	// test to see if the page has a parent
			   $parentID = $post->post_parent;        	// the ID of the parent is this
			   return $parentID;                      			// return the ID
		} else {                                      					// there is no parent so...
			   return false;                          				// ...the answer to the question is false
		};
};




/**************************************************************
 CHECK IF PAGE/POST HAS VIDEO
  CONDITIONAL TAGS
**************************************************************/
function has_video($check) {
		$attachments = get_children ( array ( 'post_parent' => $check , 'post_status' => 'inherit' , 'post_type' => 'attachment' , 'post_mime_type' => 'video') );
		if ( !empty( $attachments ) ) { 
			return true; 
		} else {
			return false;
		}
}





/***********************************************************
 MAKE SURE FLV SHOW UP AS A VIDEO MIME TYPE
***********************************************************/
add_filter("ext2type", "FLV_Hack_ext2type", 10, 1);
add_filter("upload_mimes", "FLV_Hack_upload_mimes", 10, 1);
function FLV_Hack_ext2type($filters) {
	$filters["video"][] = "flv";
	return $filters;
}
function FLV_Hack_upload_mimes($mimes) {
	$mimes["flv"] = "video/x-flv";
	return $mimes;
}





/********************************************************
 GET FIRST FUNCTIONS
********************************************************/
function get_first_image($post_ID, $size = "thumbnail", $num = 0){
	$thumbargs = array(
		'post_type' => 'attachment',
		'post_mime_type' => 'image',
		'post_status' => null,
		'post_parent' => $post_ID,
		'orderby'=>'menu_order',
		'order' => 'ASC'
	);
	$thumbs = get_posts($thumbargs);
	if ($thumbs) {
		return wp_get_attachment_image( $thumbs[$num]->ID, $size, false );
	} else {
		return false;
	}
}

function get_first_image_url($post_ID, $num = 0 ){
	$thumbargs = array(
		'post_type' => 'attachment',
		'post_mime_type' => 'image',
		'post_status' => null,
		'post_parent' => $post_ID,
		'orderby'=>'menu_order',
		'order' => 'ASC'
	);
	$thumbs = get_posts($thumbargs);
	if ($thumbs) {
		$num = 0;
		return wp_get_attachment_url( $thumbs[$num]->ID );
	} else {
		return false;
	}
}

function get_first_image_attachment_link($post_ID, $num = 0 ){
	$thumbargs = array(
		'post_type' => 'attachment',
		'post_mime_type' => 'image',
		'post_status' => null,
		'post_parent' => $post_ID,
		'orderby'=>'menu_order',
		'order' => 'ASC'
	);
	$thumbs = get_posts($thumbargs);
	if ($thumbs) {
		$num = 0;
		return get_attachment_link( $thumbs[$num]->ID );
	} else {
		return false;
	}
}

function get_first_video_url($post_ID, $num = 0){
	$thumbargs = array(
		'post_type' => 'attachment',
		'post_mime_type' => 'video',
		'post_status' => null,
		'post_parent' => $post_ID,
		'orderby'=>'menu_order',
		'order' => 'ASC'
	);
	$thumbs = get_posts($thumbargs);
	
	if ($thumbs) {
		$num = 0;
		return wp_get_attachment_url( $thumbs[$num]->ID );
	} else {
		return false;
	}
}



/************************************************************************
 TT - BODY ID CLASS
************************************************************************/
if (!function_exists('body_id')) {
	function body_id() {
		$idname = get_stylesheet();
		echo 'id="'.$idname.'"';
	}
}




/************************************************************************
 TT -  GRAB A POST"S TAXONOMY TERMS ( EDIT WORDING ON DESCRIPTION)
************************************************************************/
function retrieve_term_as_list( $args ) {
	global $wp_query, $post, $paged;
	
	$args_default = array(
	  'taxonomy'    => $taxonomy,
	  'before'      => '',
	  'sep'   		=> ', ',
	  'after'  		=> '',
	);	
		
	$args = wp_parse_args( $args, $args_default );	
	extract($args);

	$term_list = get_the_term_list( $post->ID, $taxonomy, $before, $sep, $after );
	$term_list = strip_tags($term_list);
	
	return $term_list;
}

function retreive_term_as_obj($term){
	$terms = get_the_terms($post->ID, 'custom taxonomy name');
	#print_r($terms);

	foreach ($terms as $taxindex => $taxitem) {
		$termlist .=  '<li>' . $taxitem->name . '</li>';
	}
	
	return $termlist;
}





/******************************
 [TT] - SELECT CONTENT TO RETURN
******************************/
function retrieve_content($type = "content") {
	global $wp_query, $post, $paged, $post_count;

	switch($type) {
		case "":
			$content = "";
			break;	
		case false:
			$content = "";
			break;
		case "post_excerpt":
			$content = $post->post_excerpt;			
			break;
		case "post_content":
			$content = $post->post_content;
			break;
		case "the_excerpt":
			$content = get_the_excerpt();
			break;
		case "the_excerpt_filtered":
			$content = apply_filters('the_excerpt', get_the_excerpt());
			break;			
		case "the_content";
			$content = get_the_content();
		case "the_content_filtered";
			$content = apply_filters('the_content', get_the_content());		
			break;		
		default:
			$content = get_the_content();
	}

	return $content;

}



/**************************************************************
 [SC] PAGE CONTENT - SHORT CODE
**************************************************************/
add_shortcode('retrievecontentbytitle', 'retrieve_content_by_title_shortcodehandler');
function retrieve_content_by_title_shortcodehandler($atts, $content = null) {
	global $post;

	$atts = shortcode_atts(array(
		'format' => 'post_content',
		'page_title' => '',
		'output' => 'OBJECT',
		'post_type' => 'page'
	), $atts);
	extract( $atts, EXTR_SKIP );	
	
	$page_content = retrieve_content_by_title( $format, $page_title, $output, $post_type);
	return $page_content.$content;
	
}

/**************************************************************
 [TT] SELECT CONTENT TO RETURN BY TITLE
**************************************************************/
function retrieve_content_by_title($format = "content", $page_title, $output = "OBJECT", $post_type = "page" ) {
	global $wp_query, $post, $paged, $post_count;
	
	$the_page = get_page_by_post_name( $page_title, $output, $post_type);
	$the_page_content = $the_page->post_content;								
	$the_page_excerpt = $the_page->the_page_excerpt;								
	
	switch( $format ) {
		case "":
			$content = "";
			break;	
		case false:
			$content = "";
			break;
		case "the_excerpt":
			$content = the_page_excerpt();
			break;
		case "the_excerpt_filtered":
			$content = apply_filters('the_excerpt', $the_page_excerpt);
			break;			
		case "the_content";
			$content = $the_page_content ;
		case "the_content_filtered";
			$content = apply_filters('the_content', $the_page_content);		
			break;		
		default:
			$content = $the_page_content;
	}

	return $content;

}

/* ***************************************************************
	WRAPPER FUNCTION, emulates the_title() 
	http://codex.wordpress.org/Template_Tags/the_title
	WE NEED A WRAPPER FUNCTION IN CASES WHERE WE DO NOT 
	WANT TO DISPLAY THE TITILE WITHIN OUR STRUCTURED MARKUP
*************************************************************** */
function retrieve_title( $args = null ) {
	global $wp_query, $post, $paged, $post_count;

	$defaults = array (
		'format' => 'a',
		'imagesize' => 'thumbnail',
		'imagenumber' => 0
	);
	// Parse incomming $args into an array and merge it with $defaults
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );	
	
	switch($format) {
		case false:
			$title = "";
			break;
		case "notitle":
			$title = "";
			break;
		case "titleonly":
			$title = $post->post_title;
			break;			
		case "linktoparent":
			if($post->post_parent != '') :
				$title = '<a href="'.get_permalink($post->post_parent).'">'.$post->post_title.'</a>';
			else :
				$title = '<a href="'.get_permalink($post->ID).'"><span>'.$post->post_title.'</span></a>';
			endif;
			break;
		case "linktoself":
			$title = '<a href="'.get_permalink($post->ID).'"><span>'.$post->post_title.'</span></a>';
			break;
			
		/*
		case "imagelinktoparent":			
			$image = get_first_image($post->ID, $imagesize, $imagenumber);
			if($post->post_parent != '') :
				$title = '<a href="'.get_permalink($post->post_parent).'">'.$image.'</a>';
			else :
				$title = '<a href="'.get_permalink($post->ID).'">'.$image.'</a>';
			endif;	
			break;	
		case "imagelinktoself":
			$image = get_first_image($post->ID, $imagesize, $imagenumber);
			$title = '<a href="'.get_permalink($post->ID).'">'.$image.'</a></h3>';
			break;
		*/
		case "a":
			$title = '<a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'"><span>'.$post->post_title.'</span></a>';
			break;
		default:
			$title = xtag( $format, $post->post_title );
	}

	if($title != "") :
		$title = xtag("h3", $title );
	endif;
	
	return $title;
}


function retrieve_new_title( $args = null ) {
	global $wp_query, $post, $paged, $post_count;

	$defaults = array (
		'format' => 'h3',
		'attributes' => '',
		'use_image' => false,
		'use_span' => true,		
		'imagesize' => 'thumbnail',
		'imagenumber' => 0,
		'enable_title' => true,
		'hyperlink_enable' => true,		
		'hyperlink_target' => 'linktoself'
		
	);
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

}



/* ***************************************************************
	ARTICLES HAVE HEADLINES THAT PROVIDE A TEXTUAL OR VISUAL
	PREVIEW OF THE CONTENTS.  THIS FUNCTION CREATES OPTIONS FOR
	CREATING HEADLINES, THAT INCLUDES OPTIONS FOR THE_TITLE, AND IMAGES
	ASSOCIATED WITH THE POST.
	04.12.2011
*************************************************************** */
function retrieve_headline( $args ) {
	global $wp_query, $post, $paged, $post_count;

	$defaults = array (
		'format' => 'h3',
		'attributes' => '',
		'use_image' => false,
		'use_span' => true,		
		'imagesize' => 'thumbnail',
		'imagenumber' => 0,
		'enable_title' => true,
		'hyperlink_enable' => true,		
		'hyperlink_target' => 'linktoself'
		
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );	
	
	if($enable_title):
		$title = $post->post_title;
	endif;	
	
	if($use_span):
		$title = xtag("span", $title);
	endif;

	if($use_image):
		$image = get_first_image($post->ID, $imagesize, $imagenumber);	
		$title = $title.$image;
	endif;	
	
	
	if($hyperlink_enable):
		switch($hyperlink_target) {
			
			case "link_to_file" :
				if($post->post_type != 'attachment') :
					$hreflink = get_first_image_url( $post->ID);	
					$title = xtag('a', $title, "href=$hreflink" );	
				else:
					$imagesrc = wp_get_attachment_image_src( $post->ID, $imagesize, false );
					$hreflink = $imagesrc[0];				
					$title = xtag('a', $title, "href=$hreflink" );	
				endif;
				break;
			
			case "link_to_attachment_page" :
				if($post->post_type != 'attachment') :
					$hreflink = get_first_image_attachment_link( $post->ID);	
					$title = xtag('a', $title, "href=$hreflink" );	
				else:
					$hreflink = get_attachment_link( $post->ID );		
					$title = xtag('a', $title, "href=$hreflink" );	
				endif;
				break;				
				
			case "link_to_parent" :	
				if($post->post_parent != '') :
					$hreflink = get_permalink($post->post_parent);
				else :
					$hreflink = get_permalink($post->ID);
				endif;		
				$title = xtag('a', $title, "href=$hreflink" );				
				break;

			// DEPRECATED SINCE 4.13.2011
			case "linktoself" :
				if($post->post_type != 'attachment') :
					$hreflink = get_first_image_url( $post->ID);	
					$title = xtag('a', $title, "href=$hreflink" );	
				else:
					$imagesrc = wp_get_attachment_image_src( $post->ID, $imagesize, false );
					$hreflink = $imagesrc[0];				
					$title = xtag('a', $title, "href=$hreflink" );	
				endif;
				break;
			
			// DEPRECATED SINCE 4.13.2011
			case "linktoparent" :	
				if($post->post_parent != '') :
					$hreflink = get_permalink($post->post_parent);
				else :
					$hreflink = get_permalink($post->ID);
				endif;		
				$title = xtag('a', $title, "href=$hreflink" );				
				break;		
				
			case false:
				// Do Nothing		
				break;
		}
	endif;
	
	
	if($title != "") :
		$title = xtag($format, $title, $attributes );
	endif;
	
	
	
	return $title;
}


function retrieve_media( $querytype = "default", $imagesize = "thumbnail", $hyperlink_target = false, $hyperlink_enable = false, $hyperlink_imagesize = null ) {

	global $wp_query, $post, $paged, $post_count;


	// IMAGE RETRIEVAL FUNCTION IS BASED ON TYPE OF QUERY
	switch($querytype) {
		case false:
			$image = "";
			break;
		case "attachment":	// THE QUERY OBJECT CONTAINS MEDIA ATTACHMENTS
			$image = wp_get_attachment_image( $post->ID, $imagesize, false );
			break;						
		default:
			$indexpos = 0;
			if ( has_post_thumbnail($post->ID) ) {
				// the current post has a thumbnail
				$image = get_the_post_thumbnail( $post->ID, $imagesize );
			} else {
				$image = get_first_image($post->ID, $imagesize, $indexpos ); 
			}			
	}

	if(is_null($hyperlink_imagesize)) :
		$hyperlink_imagesize = $imagesize;
	endif;
	
	switch($hyperlink_target) {		
		case "linktoself" :
			$imagesrc = wp_get_attachment_image_src( $post->ID, $hyperlink_imagesize, false );
			$href = $imagesrc[0];
			if($href == "")
				$href = get_permalink($post->ID);			
			break;
		case "linktoparent" :
			if($post->post_parent != '') :
				$href = get_permalink($post->post_parent);
			else :
				$href = get_permalink($post->ID);
			endif;		
			break;						
		default:
			// DO NOTHING
	}
	
	if($hyperlink_enable) {
		$image = xtag("a", $image, "href=".$href."&title=".$post->post_title );
	}
	
	return $image;


}


function retrieve_video( $atts = null ) {

	global $wp_query, $post, $paged, $post_count;

	
	$default = array(
		'querytype' => 'attachment',
		'imagesize' => 'thumbnail'
	);
	
	$atts = wp_parse_args( $atts, $default );	// Merge default args with those passed on by the function call	
	extract( $atts, EXTR_SKIP );	
	
	// Calculate Video Size
	$widthparam = $imagesize.'_size_w';
	
	$saved_options = get_option('FlashVideoPlayerPlugin_PlayerOptions');
	$wh_ratio = $saved_options['Video Size']['height']['v'] / $saved_options['Video Size']['width']['v'];	
	

	$videowidth = get_option($widthparam);
	$videoheight = round( $videowidth * $wh_ratio );


	switch($querytype) {
		case false:
			$image = "";
			break;
		case "attachment":	// THE QUERY OBJECT CONTAINS MEDIA ATTACHMENTS
			$firstimageurl = get_first_image_url( $targetid );			
			$videourl = get_first_video_url( $targetid );			
			break;						
		default:
			$firstimageurl = get_first_image_url($post->ID);		
			$videourl = get_first_video_url($post->ID); 
	}  

	
	if($videourl):
		$video = FlashVideoPlayerPlugin_parsecontent('[flashvideo file='.$videourl.' image='.$firstimageurl.' width='.$videowidth.' height='.$videoheight.' /]');
	endif;
	
	return $video;


}




function build_query_array( $atts ) {
	global $post;
	
	// http://codex.wordpress.org/Function_Reference/query_posts
	$default = array(
		'targetid' => $post->ID,
		'querytype' => 'default',	
	);
	
	$atts = wp_parse_args( $atts, $default );
	extract( $atts, EXTR_SKIP );

	switch($querytype) {
		case "attachment":
			$query = array(
				'post_parent' => $targetid,
				'post_status' => 'null',
				'post_type'=> 'attachment',
				'order' => 'ASC',
				'orderby' => 'menu_order',
				'showposts' => '100',
				'post_mime_type' => 'image/jpeg,image/gif,image/jpg,image/png' 					
			);
			break;			
		case "category":
			$query = array(	
				'category_name' => $category,
				'post_status' => 'publish'	,
				'order' => 'ASC',
				'orderby' => 'menu_order',
				'showposts' => '100',					
			);
			break;
		case "tag":
			$query = array(
				"tag" => $tag,
				'post_status' => 'publish'	,
				'order' => 'ASC',
				'orderby' => 'menu_order',
				'showposts' => '100',				
			);
			break;
		case "posttype":
			$query = array(
				'post_type'=> $post_type,
				'post_status' => 'publish'	,
				'order' => 'ASC',
				'orderby' => 'menu_order',
				'showposts' => '100',				
			);
			break;			
		case "posttype_category":
			$query = array(
				$category.'_category' => 'video',			
				'post_status' => 'publish'	,
				'order' => 'ASC',
				'orderby' => 'menu_order',
				'showposts' => '100',				
			);
			break;
		case "related_by_term":
			# [12.15.2010]	http://codesnipp.it/code/1403 
			$query = array(
				'tag__in' => $tag_in_array,
				'post__not_in' => $post_not_in_array,
				'showposts' =>1,
				'caller_get_posts' => 1		
			);
			break;
		case "term":
			$query = array(
				$termname => $term		
			);
			break;
		default:
			$query = array(
				'post_type' => 'post',
				'post_status' => 'publish'	,				
				'showposts' => 0,
			);
	}

	
	/***
	shortcode_atts() to filter out non query variables
	The first paramter is array of accepted vars
	***/
	$query = shortcode_atts( $query, $atts );	
	
	return $query;

}


/**************************************************************
	BUILD MARKUP FROM WP QUERY
	
	FUNCTIONS USES 3 SETS OF OPTIONS
	
	1 - DISPLAY
	2 - MARKUP
	3 - WPQUERY
	
**************************************************************/
function buildmarkup_from_query( $queryargs , $optionargs, $markup = null, $return_as_array = false ) {
		global $wp_query, $post, $paged, $post_count;

	
	#	DISPLAY OPTIONS	
		$optionargs_default = array (
								"type_of_content" => false,			// false = content will not be shown | 'the_excerpt' = get_the_excerpt() | 'the_content' = get_the_content();
								"type_of_media" => false,			// false = image will not be shown | 'featured' = featured mediaattachment | everything else defaultd to the first image, bseed on image order
								"mediasize" => "thumbnail",			// Displays the first availiable image that is attacted to page/post
								"hyperlink_target" => "linktoself",
								"hyperlink_enable" => false,
								"hyperlink_imagesize" => null,
								"media_has_hyperlink" => false,			// Hyperlink to the page/post that the attach media is associated 
								"image_after_title" => false,
								"title_format" => "a",				// false = title will not be shown | 'a' = hyperlink will wrap title | 'tagname' = tagname will wrap title, <tagname>title</tagname>
								"wrapper_class_counter" => false,
								"filtername" => false,
								'enable_unique_class_name' => true
							);
		$optionargs = wp_parse_args( $optionargs, $optionargs_default );	// Merge default args with those passed on by the function call
		extract($optionargs);												// Turn $optionargs array into userable variables
		
		
		

	#	MARKUP OPTIONS
		$markup_default = array (
								"entry_wrapper" => "entry",
								"entry_image" => "entry_image",
								"entry_content_wrapper" => "entry_content_wrapper",
								"entry_content" => "entry_content",
								"entry_title_wrapper" => "entry_title_wrapper"
							);				
		$markup = wp_parse_args( $markup, $markup_default );	// Merge default args with those passed on by the function call
		extract($markup);
		
		
				
	#	QUERY OPTIONS
		$queryargs_default = array ( "showposts"=>"100", "orderby" => "menu_order", "order" => "ASC" );				// Default Query Options
		$queryargs = wp_parse_args( $queryargs, $queryargs_default );												// Merge default args with those passed on by the function call

		$temp = $wp_query;							// Preserve Orginal Query
		$wp_query = null;								// Set var Object to Null
		$wp_query = new WP_Query();					// Create New Query Object
		$wp_query->query( $queryargs );				// Run a New Query



	#	THE LOOP	
		if ($wp_query->have_posts()) :
			$xhtmlmarkup = "";	
			$counter = 0;			
				
		#	LOOP THROUGH QUERY
			while ($wp_query->have_posts()) : $wp_query->the_post();
				$counter++;
				
				$title = retrieve_title("format=$title_format");																												// Grabs the title, formated according to type
				$content = retrieve_content($type_of_content);																											// Grabs the content according to desired format
				$image = retrieve_media( $type_of_media, $mediasize, $hyperlink_target, $hyperlink_enable, $hyperlink_imagesize ); 		// Grabs the proper image 
									
				if($image != "") { $image_before = xtag( "div", $image, "class=$entry_image" ); }					
				if($image_after_title) { $image_after = $image_before; $image_before = ""; }
				if($wrapper_class_counter) { $entry_counter = ' num_'.$counter; } 					// Include a class that provides unique numeric name	
				if($enable_unique_class_name) { $unique_class_name = $post->post_name; }
				if($filtername != false) { $text = ""; $filter = apply_filters($filtername, $text); } 		// Apply filter if specified
				if($title != "") { $title = xtag( "div", $title, "class=".$entry_title_wrapper ); }
				
				
				
				$create_xhtml = 
				'
					<div class="'.$entry_wrapper.$entry_counter.'" rel="'.$unique_class_name.'">
						'.$image_before.'
						<div class="'.$entry_content_wrapper.'">
							'.$title.$image_after.'																		
							<div class="'.$entry_content.'">
							'.$filter.$content.'
							</div>
						</div>
					</div>
				';
				
				$xhtml_entry_markup_array[] = $create_xhtml;
				$xhtml_entry_markup .= $create_xhtml;
				
			endwhile;
			
			if($return_as_array) :
				$thereturn = $xhtml_entry_markup_array;
			else :
				$thereturn = $xhtml_entry_markup;
			endif;	
		
		else :
			$thereturn = false;
		endif;

		
		
	//	RESET WORDPRESS QUERY OBJECT
		$wp_query = null; $wp_query = $temp; wp_reset_query();	
		return $thereturn;

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