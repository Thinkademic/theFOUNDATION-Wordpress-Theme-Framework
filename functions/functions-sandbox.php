<?php
/* =============================================

	functions-sandbox.php

	Having a file to hastily add or edit functions can be helpful.
	Especially if you are writing new code or pasting in
	snippets you have found handy.  It's good to try it out
	here, and if it works out then think about formating
	the code based on code style guidelines.
	
	
	SOME USE CASES:
	
	+	TESTING NEW FUNCTIONS
	+	PENDING REMOVAL FROM CORE

	============================================= */



/**************************************************************
	[FUNCTION CATEGORY]	

	[DESCRIPTION OF FUNCTION]
	
	REF - [hyperlink or text]
	
	NOTES
	[MM.DD.YYY] [NAME] [EMAIL]
	+ [TEXT FOR NOTE LINE 01]
	+ [TEXT FOR NOTE LINE 02]
**************************************************************/


/**************************************************************
	http://www.wprecipes.com/how-to-show-the-home-link-on-wp_nav_menu-default-fallback-
**************************************************************/
function homelink_for_menufallback($args) {
	$args['show_home'] = true;
	return $args;
}
add_filter('wp_page_menu_args', 'homelink_for_menufallback');




/**************************************************************
	DATE 1/12/2011
	REF http://www.wprecipes.com/how-to-automatically-add-a-search-field-to-your-navigation-menu
**************************************************************/
#add_filter('wp_nav_menu_items','add_search_box', 10, 2);
function add_search_box($items, $args) {
	$searchform = get_search_form(false);
	$items .= '<li>' . $searchform . '</li>';
	
	return $items;
}


/**************************************************************
 ADD BROWSER DETECTION TO BODY CLASS FUNCTION
 REF:  http://www.nathanrice.net/blog/browser-detection-and-the-body_class-function/
**************************************************************/
//	add_filter('body_class','browser_body_class');
function browser_body_class($classes) {
	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

	if($is_lynx) $classes[] = 'lynx';
	elseif($is_gecko) $classes[] = 'gecko';
	elseif($is_opera) $classes[] = 'opera';
	elseif($is_NS4) $classes[] = 'ns4';
	elseif($is_safari) $classes[] = 'safari';
	elseif($is_chrome) $classes[] = 'chrome';
	elseif($is_IE) $classes[] = 'ie';
	else $classes[] = 'unknown';

	if($is_iphone) $classes[] = 'iphone';
	return $classes;
}



/**************************************************************
 FOUNDATION DEFAULT SIDEBAR SETUP
 DECPRECATED - CHECK IF EXIST
**************************************************************/
function foundation_sidebar() {
	$output = retrieve_taxonomy_list();
	echo $output;
}

/**************************************************************
 CUSTOM TEMPLATE TAG FOR SIDEBARS
 CONSIDER APPLYING
 http://codex.wordpress.org/Function_Reference/get_template_part
**************************************************************/
function dynamicsidebar($sidebar_name, $before = '', $after = '')
{
		if ( function_exists('dynamic_sidebar') ) {
			echo $before;		
				dynamic_sidebar($sidebar_name);
			echo $after;
		}

}


/***********************************************************
 MOST WORDPRESS USERS ARE USING CUSTOM FIELDS TO DISPLAY 
 THUMBS ON THEIR BLOG HOMEPAGE. IT IS A GOOD IDEA, BUT DO 
 YOU KNOW THAT WITH A SIMPLE PHP FUNCTION, YOU CAN GRAB 
 THE FIRST IMAGE FROM THE POST, AND DISPLAY IT.
***********************************************************/
function find_img_inside_postcontent() {
  global $post, $posts;

  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [0];

  if(empty($first_img)){ //Defines a default image
	$first_img = "/images/default.jpg";
  }
  return $first_img;
}


/************************************************************************
 FUNCTIONS FOR IMAGE PREVIOUS AND IMAGE NEXT
 MAKE SURE IT FACTORS IN THE FIRST AND LAST IMAGE
*************************************************************************/
function ps_previous_image_link( $f ) {
	$i = ps_adjacent_image_link( true );
	if ( $i ) {
		echo str_replace("%link", $i, $f);
	}
}

function ps_next_image_link( $f ) {
	$i = ps_adjacent_image_link( false );
	if ( $i ) {
		echo str_replace("%link", $i, $f);
	}
}

function ps_adjacent_image_link($prev = true) {
	global $post;
	$post = get_post($post);
	$attachments = array_values(get_children(Array('post_parent' => $post->post_parent,
		  'post_type' => 'attachment',
		  'post_mime_type' => 'image',
		  'order' => 'ASC',
		  'orderby' => 'menu_order ASC, ID ASC')));

	foreach ( $attachments as $k => $attachment ) {
		if ( $attachment->ID == $post->ID ) {
			break;
		}
	}

	$k = $prev ? $k - 1 : $k + 1;

	if ( isset($attachments[$k]) ) {
		return wp_get_attachment_link($attachments[$k]->ID, 'thumbnail', true);
	}
	else {
		return false;
	}
}

/************************************************************************
 THIS CODE ADDS A NEW COLUMN TO THE MEDIA LIBRARY PAGE ALLOWING YOU TO RE-ATTACH IMAGES
*************************************************************************/
add_filter("manage_upload_columns", 'upload_columns');
add_action("manage_media_custom_column", 'media_custom_columns', 0, 2);
function upload_columns($columns) {
    unset($columns['parent']);
    $columns['better_parent'] = "Parent";
    return $columns;
}
function media_custom_columns($column_name, $id) {
    $post = get_post($id);
    if($column_name != 'better_parent')
        return;
        if ( $post->post_parent > 0 ) {
            if ( get_post($post->post_parent) ) {
                $title =_draft_or_post_title($post->post_parent);
            }
            ?>
            <strong><a href="<?php echo get_edit_post_link( $post->post_parent ); ?>"><?php echo $title ?></a></strong>, <?php echo get_the_time(__('Y/m/d')); ?>
            <br />
            <a class="hide-if-no-js" onclick="findPosts.open('media[]','<?php echo $post->ID ?>');return false;" href="#the-list"><?php _e('Re-Attach', TEXTDOMAIN); ?></a>
            <?php
        } else {
            ?>
            <?php _e('(Unattached)'); ?><br />
            <a class="hide-if-no-js" onclick="findPosts.open('media[]','<?php echo $post->ID ?>');return false;" href="#the-list"><?php _e('Attach', TEXTDOMAIN); ?></a>
            <?php
        }
}


  

/**************************************************************
 [06] ADD CUSTOM POST TYPES TO THE 'RIGHT NOW' DASHBOARD WIDGET
 http://wordpress.stackexchange.com/questions/1567/best-collection-of-code-for-your-functions-php-file
**************************************************************/
function all_settings_link() {
	add_theme_page(__('All Settings'), __('All Settings'), 'administrator', 'options.php');
}
add_action('admin_menu', 'all_settings_link');


/**************************************************************
	CONSTRUCT XHTML TAGS USING A FUNCTION, $ARS IS A QUERY STRING
	THAT WILL BE EXTRACTED.
**************************************************************/
function itag( $tag = "img", $content, $args = null, $precontent = "", $postcontent = "" ) {

	if($tag == "img" && $content != "") {
	
		$defaults = array(
			'id' => "",
			'class' => "",
			'title' => "",
			'alt' => "",
			'src' => ""
		);
		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		 $divxhmtl = "";
		
		 if($id != "") :
			$id = ' id="'.$id.'"';
		 endif;
		 
		 if($class != "") :
			$class = ' class="'.$class.'"';
		 endif;

		 if($alt != "") :
			$alt = ' alt="'.$alt.'"';
		 endif;
		 
		 if($content != "") :
			$src = ' src="'.$content.'"';
		 endif;		 
		 			 
		$divxhtml  = "\n".'<'.$tag.$id.$class.$src.$alt.' />';
		
		return $divxhtml;
	} else {
		return $content;
	}
}







function socialmedia_profiles() {

	$profiles = array(

	array(
			'name' => "Facebook",
			'href' => "http://www.facebook.com/pages/Los-Angeles-CA/FOODSTORY/121934961176624",		
			'hrefclass' => "facebook",
			'hreftitle' => "Join Our Facebook Page" ,
			'iconfolder' => "/images/followicons/",
			'iconfilename' => "facebook",		
			'iconimgtype' => "png" ),	
	array(
			'name' => "Twitter",
			'href' => "http://www.twitter.com/foodstory_japan",		
			'hrefclass' => "twitter",
			'hreftitle' => "Follow Us on Twitter" ,
			'iconfolder' => "/images/followicons/",
			'iconfilename' => "twitter",		
			'iconimgtype' => "png" ),	
	array(
			'name' => "Youtube",
			'href' => "http://www.youtube.com/dailyFOODSTORY",		
			'hrefclass' => "youtube",
			'hreftitle' => "Join our Youtube Channel" ,
			'iconfolder' => "/images/followicons/",
			'iconfilename' => "youtube",		
			'iconimgtype' => "png" )	
	);	
	
	echo get_socialmedia($profiles);

}

function get_socialmedia( $profiles = null ) {

	if($profiles) {
		$smedia .= "";

		foreach ( $profiles as $key ) {
			$smedia .= get_socialprofile( $key );
		}

		$smedia = xtag("div", $smedia, "id=followme");
		$smedia = xtag("div", $smedia, "id=socialmedia");

		return $smedia;
	}
}


function get_socialprofile( $args = null ) {

	$defaults = array(
		'name' => "",
		'href' => "",		
		'hrefclass' => "",
		'hreftitle' => "" ,
		'iconfolder' => "/images/followicons/",
		'iconfilename' => "",		
		'iconimgtype' => "png"
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );


	$imgsrc = get_stylesheet_directory_uri().$iconfolder.$iconfilename.".".$iconimgtype;
	$hrefcontent = itag("img", $imgsrc, "alt=".$hreftitle).xtag("span", $name);
	$link = xtag( "a", $hrefcontent, 'href='.$href.'&class='.$hrefclass.'&title='.$hreftitle );
	$profile = xtag("div", $link, "class=socialprofile");
	
	
	
	$socialprofile = '
		<div class="socialprofile">
			<a class="'.$hrefclass.'" href="'.$href.'" title="'.$hreftitle.'">
			<img '.$imgsrc.'/><span>'.$name.'</span></a>
		</div>
	';
		
	return $profile;	
		
}










?>