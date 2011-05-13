<?php
/*
*	LOAD MEDIA GALLERIES
*/
require_once(TEMPLATEPATH . '/functions/media-galleries/gallery-thefoundation.php');
require_once(TEMPLATEPATH . '/functions/media-galleries/gallery-jcycle.php');
require_once(TEMPLATEPATH . '/functions/media-galleries/gallery-nivoslider.php');
require_once(TEMPLATEPATH . '/functions/media-galleries/gallery-anythingslider.php');
require_once(TEMPLATEPATH . '/functions/media-galleries/gallery-smoothdiv.php');




/**************************************************************
 TEMPLATE TAG
 
 DISPLAY SELECTED GALLERY ASSOCIATED WITH 
 POST/PAGE/CUSTOM POST TYPE
 
 THE GALLERY IS SELECTED USING A METABOX
**************************************************************/
function show_mediagalleries( $targetid = null ) {
	
	if (function_exists('show_jcyclegallery'))
		show_jcyclegallery();	
	
	if (function_exists('show_anythingslider'))
		show_anythingslider();	
	
	if (function_exists('show_foundation_gallery'))
		show_foundation_gallery();
		
	if (function_exists('show_nivoslider'))
		show_nivoslider();		
}





/**************************************************************
 ADDS OUR GALLERY OPTION META BOX TO 
 ADMIN EDIT POST/PAGES/CUSTOM POSTTYPES
**************************************************************/
add_action("init", "create_layout_options");
if(!function_exists('create_layout_options')) {
	function create_layout_options() { 
		global $mantone_events; 
		$mantone_events = new create_layout_options(); 
	}
}



/**************************************************************
 UNIVERSAL OPTIONS ARRAY, WILL BE USED BY ALL GALLERIES
**************************************************************/
function postmeta_gallery_array( $targetid = null ) {
	global $post;

	if( $targetid == null )	
		$targetid = $post->ID;			
	
	$meta = get_post_meta($targetid, THEMECUSTOMMETAKEY, true);	

	// Default Attribute Values															
	$imagesize = set_default_value($meta["gallery_imagesize"], "thumbnail");
	$alignment = $meta["gallery_align"];
	$orderby = $meta["gallery_orderby"];
	$enablenextprev = $meta["gallery_enablenextprev"];
	$enablethumbpreview = $meta["gallery_thumbnailpreview"];
	$thumbpreviewposition = set_default_value($meta["gallery_thumbpreviewposition"], 'bottom');
	$retrieve_content = $meta["gallery_display_retrieve_content"];
	$autoplay = $meta["gallery_display_autoplay"];
	$source = $meta["gallery_source"];
	$sourceinfo = $meta["gallery_source_textinfo"];
	$enable_title = $meta["gallery_title_enable"];
	$hyperlink_enable = $meta["gallery_hyperlink_enable"];
	$hyperlink_placement = $meta["gallery_hyperlink_placement"];
	$hyperlink_target = $meta["gallery_hyperlink_target"];	
	
	
	$post_type = "attachment";		// FIGURE OUT WHAT KIND OF QUERY WE ARE GOING TO CONSTRUCT
	$querytype = "attachment";		// FIGURE OUT WHAT KIND OF QUERY WE ARE GOING TO CONSTRUCT
	
	if($source == "attachments_bypagename") {
		$querytype = "attachment";
		$post_type = "page";
		$name = $sourceinfo;
		$title = get_page_by_title($name, "OBJECT");
		if( is_null($title) ) {
			$querytype = 'default';
		} else {
			$targetid = $title->ID;		
		}
	}

	if($source == "attachments_bypostname") {
		$querytype = "attachment";
		$post_type = "post";		
		$name = $sourceinfo;
		$title = get_page_by_title($name, "OBJECT", 'post');
		if( is_null($title) ) {
			$querytype = 'default';
		} else {
			$targetid = $title->ID;		
		}	
	}		
	
	if($source == "attachments_byposttype") {
		$querytype = "posttype";
		$post_type = $sourceinfo;		
	}	

	if($source == "attachments_bytagslug") {
		$querytype = "tag";
		$post_type = "post";	
		$tag_slug = $sourceinfo;		

	}	
	
	if($source == "attachments_bycategoryname") {
		$querytype = "category";
		$post_type = "post";	
		$category_name = $sourceinfo;		

	}		
	
			
	// FINAL ATTRIBUTE VALUES
	$atts = array (
		"post_type" => $post_type,
		"category" => $category_name,
		"tag" => $tag_slug,
		"querytype" => $querytype,		
		"targetid" => $targetid,
		"imagesize" => $imagesize,
		"alignment" => $alignment,
		"orderby" => $orderby,
		"retrieve_content" => $retrieve_content,
		"enablenextprev" => $enablenextprev,			
		"enablethumbpreview" => $enablethumbpreview,
		"thumbpreviewposition" => $thumbpreviewposition,
		"enable_title" => $enable_title,
		"hyperlink_enable" => $hyperlink_enable,
		"hyperlink_placement" => $hyperlink_placement,
		"hyperlink_target" => $hyperlink_target
	);	
	
	return $atts;
	
}







	
/*
*	CREATE A DROPDOWN LISTING OF POSSIBLE IMAGE SIZES
*
*	TODO: INCORPORATE CUSTOM SIZES
*/
function gallery_dropdown( $meta_key = "", $customfieldname, $labeldecription ) {

	global $_wp_additional_image_sizes;

		$get_sizes = get_intermediate_image_sizes();
		$custom_sizes = array();

		/*
		*	CREATE ARRAY FOR OUR form_selectbox() FUNCTION
		*	MAPPING, ARRAY KEY BECOMES LABEL, 
		*	ARRAY VALUE MAPS TO OPTION TAG'S VALUE ATTRIBUTE
		*	PERHAPS CONSIDER using 
		*	http://www.php.net/manual/en/function.array-flip.php
		*/
		foreach ($get_sizes as $key => $value) {
			
			$size_width = get_option($value.'_size_w');
			$size_height = get_option($value.'_size_h');	
		
			if( ! is_numeric($size_width) )
				$size_width = $_wp_additional_image_sizes[$value]['width'];
			
			if( ! is_numeric($size_height) )
				$size_height = $_wp_additional_image_sizes[$value]['height'];	
			
			$dimensions = " - $size_width (w) x $size_height (h)";
		
			$custom_sizes[$value.$dimensions] = $value;
		}	
		

		// SBUILD DEFAULT AND CUSTOM SIZE ARRAY
		$none_options = 	array (
			'None' => ''
		);
		$custom_sizes = array_merge($none_options, $custom_sizes);	
		$custom_sizes = apply_filters("intermediate_image_sizes", $custom_sizes);
	
	
	$default_options = 	array (
		'None' => '',
		'Thumbnail' => 'thumbnail', 
		'Medium' => 'medium',
		'Large' => 'large',
	);
	
	return form_selectbox( $meta_key, $customfieldname, $labeldecription, $default_options, false );
}			
				

								
				
/*
*	MEDIA GALLERY LAYOUT OPTIONS CLASS
*	
*	WRITE A MORE EXTENSIBLE CLASS
*	AND PACKAGE AS A PLUGIN
*
*/
class create_layout_options {

	# BUILD AN ARRAY TO STORE OUR CUSTOM FIELDS DATA	
	var $meta_key = THEMECUSTOMMETAKEY;
	var $meta_fields = array(
		"gallery_source",
		"gallery_source_textinfo",
		"gallery_type",
		"gallery_location",
		"gallery_imagesize",
		"gallery_orderby",
		"gallery_enablenextprev",
		"gallery_thumbnailpreview",
		"gallery_autoplay",		
		"gallery_title_enable",
		"gallery_display_retrieve_content",
		"gallery_hyperlink_enable",		
		"gallery_hyperlink_placement",
		"gallery_hyperlink_target",
		"gallery_display_autoplay",
		"gallery_thumbpreviewposition",
		"gallery_alignment",
		"gallery_transitiondelay",		
		
		# jcycle specific options
		"jcyclegallery_effect",
		
		# fancy transition specific optinos
		"nivoslider_effect",
		"nivoslider_boxcol",
		"nivoslider_boxrows",
		
		# fancy transition specific optinos
		"fancytransitions_effect",
		"fancytransitions_position",
		"fancytransitions_direction",
		"fancytransitions_delay",
		
		# anythingslider specific options
		'anythingslider_width',
		'anythingslider_height',
		'anythingslider_resizecontents',
		'anythingslider_startpanel',
		'anythingslider_hastags',
		'anythingslider_buildarrows',
		'anythingslider_navigationformatter',
		'anythingslider_fowardtext',
		'anythingslider_backtext',
		'anythingslider_autoplay',
		'anythingslider_startstopped',
		'anythingslider_pauseonhover',
		'anythingslider_resumeonvideoend',
		'anythingslider_stopatend',
		'anythingslider_playrtl',
		'anythingslider_starttext',
		'anythingslider_stoptext',
		'anythingslider_delay',
		'anythingslider_animationTime',
		'easing'

		
	);
	

	# -- Class Definition & Function Calls
	function create_layout_options() {

		// Admin interface init
		add_action("admin_init", array(&$this, "admin_init"));

		// Update Post functions
		add_action("wp_insert_post", array(&$this, "wp_insert_post"), 10, 2);			

	}



	# -- Class Functions
	function admin_init() {
		// Custom meta boxes for Theme Layout Options
		add_meta_box(
			"fslmeta_themelayoutoptions_forpage", 
			"Dynamic Gallery Options", 
			array(&$this, "add_gallerylayoutoptions_forpage"), 
			"page", 
			"side", 
			"low"
		);
		// Custom meta boxes for Theme Layout Options
		add_meta_box(
			"fslmeta_themelayoutoptions_forpage", 
			"Gallery Options", 
			array(&$this, "add_gallerylayoutoptions_forpage"), 
			"post", 
			"side", 
			"low"
		);		

	}
	

	# -- Build the XHMTL boxes for our meta boxes, used by admin_init()
	function add_gallerylayoutoptions_forpage()
	{
			global $post;
			$custom = get_post_custom($post->ID);
			$saved_meta_fields = get_post_meta( $post->ID, THEMECUSTOMMETAKEY, true );	
	

			//	GALLERY TYPE
			$output .= "<p><strong>SET GALLERY PLUGIN</strong></p>";
			$selectoptions = array (
				'None' => '',
				'Jcycle Gallery' => 'jcyclegallery',				
				'Anything Slider' => 'anythingslider',
				'Nivo Slider' => 'nivoslider'
				// 'Fancy Transitions' => 'fancytransitions',
				// 'Orbit' => 'orbit',
			);			
			$output .= form_selectbox( $this->meta_key, 'gallery_type', '', $selectoptions );			
					
			# --- SLIDE SOURCE SOURCE
			$output .= "<p><strong>SET IMAGE ATTACHMENT SOURCE</strong></p>";
			$selectoptions = array (
			   'from this Post/Page' => 'attachment',
			   'from Pages by Title Name' => 'attachments_bypagename',
			   'from Post by Title Name' => 'attachments_bypostname',			   
			   'from Post by Category Name' => 'attachments_bycategoryname',
			   'from Post by Tag Slug' => 'attachments_bytagslug',			   
			   'from Custom Post Type' => 'attachments_byposttype'
			);			
			$output .= form_selectbox( $this->meta_key, 'gallery_source', '', $selectoptions );						
			$output .= form_textinput( $this->meta_key, 'gallery_source_textinfo', '' );			
			
					
			# --- IMAGE SIZE
			$output .= "<p><strong>SET IMAGESIZE</strong></p>";
			$output .= gallery_dropdown( $this->meta_key, 'gallery_imagesize', '' );
	
					
			# --- PLACEMENT LOCATION
			// $output .= "<br /><p><strong>SET PLACEMENT</strong></p>";
			$selectoptions = array (
			   'Masthead' => 'masthead',
			   'Primary Content' => 'primary',
			   'Secondary Content' => 'secondary',
			);			
			//	$output .= form_selectbox( $this->meta_key, 'gallery_location', '', $selectoptions );	

			
			# --- CSS ALIGNMENT
			//$output .= "<br /><p><strong>Alignment</strong></p>";
			$selectoptions = array (
			   'None' => '',
			   'left' => 'left',
			   'right' => 'right',
			   'center' => 'center'
			);			
			//$output .= form_selectbox( $this->meta_key, 'gallery_alignment', '', $selectoptions );	
			
			
			# --- ORDER SLIDE BY
			$output .= "<p><strong>ORDER BY</strong></p>";
			$selectoptions = array (
			   'none' => 'none',
			   'Menu Order' => 'menu_order',
			   'Author' => 'author',
			   'Date' => 'date',
			   'Title' => 'title',
			   'Last Modified' => 'modified',
			   'Parent' => 'parent',
			   'ID' => 'ID',
			   'Random' => 'rand',
			   'Meta Value' => 'meta_value',
			   'Comment Count' => 'comment_count'
			);			
			$output .= form_selectbox( $this->meta_key, 'gallery_orderby', '', $selectoptions );			
			
			
			# --- NAVIGATION CONTROL OPTIONS
			$output .= "<br /><p><strong>NAVIGATION CONTROL OPTIONS</strong></p>";
			$output .= form_checkbox( $this->meta_key, 'gallery_enablenextprev', 'Next/Prev Controls' );
			$output .= form_checkbox( $this->meta_key, 'gallery_autoplay', 'Autoplay' );
			$output .= form_checkbox( $this->meta_key, 'gallery_thumbnailpreview', 'Show Thumbnail Preview' );
			$selectoptions = array (
			   'Bottom' => 'bottom',
			   'Top' => 'top',			   
			   'Left' => 'left',
			   'Right' => 'right'
			);			
			#$output .= form_selectbox( $this->meta_key, 'gallery_thumbpreviewposition', 'Set Thumbnail Location', $selectoptions );	
			




			
			
			
			# --- TITLE OPTIONS
			$output .= "<br /><p><strong>TITLE DISPLAY OPTIONS</strong></p>";			
			$output .= form_checkbox( $this->meta_key, 'gallery_title_enable', 'Show Title' );			

			# --- CONTENT OPTIONS			
			$radiooptions = array (
				'None' => '',
				'Post Excerpt' => 'post_excerpt', 								// AS ENTERED					
				'Excerpt' => 'the_excerpt', 											// SANITIZED
				'Full Excerpt Filtered' => 'the_excerpt_filtered',		// SANITIZED AND FILTERED
				'Post Content' => 'post_content',								// AS ENTERED IN MYSQL
				'Full Content' =>'the_content',									// SANITIZED
				'Full Content Filtered' => 'the_content_filtered',		// SANITIZED AND FILTERED
			);	
			$output .= form_radio( $this->meta_key, 'gallery_display_retrieve_content', '<strong>CONTENT DISPLAY OPTIONS</strong>', $radiooptions );

			
			# --- ENABLE HYPERLINK				
			$output .= "<p><strong>ENABLE HYPERLINK</strong></p>";		
			$output .= form_checkbox( $this->meta_key, 'gallery_hyperlink_enable', 'ENABLE HYPERLINK' );			
					
			
			# --- HYPERLINK TARGET
			$output .= "<p><strong>HYPERLINK LINKS TO</strong></p>";			
			$radiooptions = array (
			   # "Link to Image Source" => "linktoself",							// Link to Image Source
			   # "Link to Image's Parent" => "linktoparent",			   		// Link to Image of Attached Paged
			   "Image File" => "link_to_file",									// Link to Image file
			   "Image Page" => "link_to_attachment_page",					// Link to Image Page				   
			   "Image Parent" => "link_to_parent",			// Link to Image of Attached Paged
			);			
			$output .= form_selectbox( $this->meta_key, 'gallery_hyperlink_target', '', $radiooptions );			
			
			
			# --- HYPERLINK PLACEMENT			
			$radiooptions = array (
			   'Image' => 'image',			
			   'Title' => 'title'
			);			
			$output .= form_radio( $this->meta_key, 'gallery_hyperlink_placement', '<strong>HYPERLINK WRAPS AROUND</strong>', $radiooptions );

			# --- GALLERY TRANSITION
			$output .= 
			'<br /><p>
				<strong>Transitions Options.</strong>
			 </p>';				
			$output .= form_textinput( $this->meta_key, 'gallery_transitiondelay', 'Overide Delay of 2500ms' );	
			
			

			
			
			
			
			
			
			
			
			
			# --- FANCY TRANSITION OPTIONS
			if( $saved_meta_fields["gallery_type"] == "fancytransitions" ) :
				$output .= 
				'<hr /><p><br />
					<strong>Fancy Transitions Options.</strong>
				 </p>';				
				$selectoptions = array (
				   'wave' => 'wave',
				   'zipper' => 'zipper',
				   'curtain' => 'curtain',
				);			
				$output .= form_selectbox( $this->meta_key, 'fancytransitions_effect', 'Select Effect:', $selectoptions );
				$selectoptions = array (
				   'top' => 'top',
				   'bottom' => 'bottom',
				   'curtain' => 'curtain',
				   'alternate' => 'alternate'
				);			
				$output .= form_selectbox( $this->meta_key, 'fancytransitions_position', 'Vertical Transition', $selectoptions );
				$selectoptions = array (
				   'left' => 'left',
				   'right' => 'right',
				   'alternate' => 'alternate',
				   'random' => 'random',
				   'fountain' => 'fountain',
				   'fountain alternate' => 'fountainAlternate'			   
				);			
				$output .= form_selectbox( $this->meta_key, 'fancytransitions_direction', 'Horizontal Transition', $selectoptions );
				$output .= form_textinput( $this->meta_key, 'fancytransitions_delay', 'Overide Delay of 6000ms' );		
			endif;
			
			
			
			
			# --- NIVOSLIDER TRANSITION OPTIONS
			if( $saved_meta_fields["gallery_type"] == "nivoslider" ) :
				$output .= 
				'<hr /><p><br />
					<strong>Nivoslider Options.</strong>
				 </p>';				
				$selectoptions = array (
					'fade' => 'fade', 
					'fold' => 'fold', 
					'sliceDown' => 'sliceDown', 
					'sliceDownLeft' => 'sliceDownLeft', 
					'sliceUp' => 'sliceUp', 
					'sliceUpLeft' => 'sliceUpLeft', 
					'sliceUpDown' => 'sliceUpDown', 
					'sliceUpDownLeft' => 'sliceUpDownLeft', 
					'slideInRight' => 'slideInRight', 
					'slideInLeft' => 'slideInLeft', 
					'boxRandom' => 'boxRandom', 
					'boxRain' => 'boxRain', 
					'boxRainReverse' => 'boxRainReverse', 
					'boxRainGrow' => 'boxRainGrow', 
					'boxRainGrowReverse' => 'boxRainGrowReverse',
					'random' => 'random'
				);				
				$output .= form_selectbox( $this->meta_key, 'nivoslider_effect', 'Select Effect:<br />', $selectoptions );	
				$output .= form_textinput( $this->meta_key, 'nivoslider_boxcol', 'Box Col Number<br />' );
				$output .= form_textinput( $this->meta_key, 'nivoslider_boxrows', 'Box Row Number<br />' );						
			endif;			
			
			
			# --- CYCLE TRANSITION OPTIONS
			if( $saved_meta_fields["gallery_type"] == "jcyclegallery" ) :
				$output .= 
				'<hr /><p><br />
					<strong>Jcycle Transitions Options.</strong>
				 </p>';				
				$selectoptions = array (
					'none' => 'none', 
					'blindX' => 'blindX', 
					'blindY' => 'blindY', 
					'blindZ' =>'blindZ', 
					'cover' => 'cover', 
					'curtainX' => 'curtainX', 
					'curtainY' => 'curtainY', 
					'fade' => 'fade', 
					'fadeZoom' => 'fadeZoom', 
					'growX' => 'growX', 
					'growY' => 'growY', 
					'scrollUp' => 'scrollUp', 
					'scrollDown' => 'scrollDown', 
					'scrollLeft' => 'scrollLeft', 
					'scrollRight' => 'scrollRight', 
					'scrollHorz' => 'scrollHorz', 
					'scrollVert' => 'scrollVert', 
					'shuffle' => 'shuffle', 
					'slideX' => 'slideX', 
					'slideY' => 'slideY', 
					'toss' => 'toss', 
					'turnUp' => 'turnUp', 
					'turnDown' => 'turnDown', 
					'turnLeft' => 'turnLeft', 
					'turnRight' => 'turnRight', 
					'uncover' => 'uncover', 
					'wipe' => 'wipe', 
					'zoom' => 'zoom' 
				);				
				$output .= form_selectbox( $this->meta_key, 'jcyclegallery_effect', 'Select Effect:', $selectoptions );			
			endif;		
		
		
			# --- ANYTHING SLIDER OPTIONS
			if( $saved_meta_fields["gallery_type"] == "anythingslider" ) :
				$output .= 
				'<hr /><p><br />
					<strong>Jcycle Transitions Options.</strong>
				 </p>';				
				$selectoptions = array (
					'none' => 'none', 
				);				
				$output .= form_selectbox( $this->meta_key, 'jcyclegallery_effect', 'Select Effect:', $selectoptions );			
			endif;				
			
			
			echo $output;	  	
	}



	# -- WHEN A POST IS INSERTED OR UPDATED
	function wp_insert_post($post_id, $post = null ) {
		if ($post->post_type == "page" || $post->post_type == "post" ) {
		
			$saved_data = get_post_meta($post_id, $this->meta_key, true);	
			$new_data = array();													// Build an array to save in the meta...We are only going to use one wp_postmeta entry.		
			
				// Loop through our fields, grab the equivalent $_POST value and build array to pass onto wp_postmeta entry 
				foreach ($this->meta_fields as $key) {							// Loop through the POST data
					
					if(isset($_POST[$key])) {
						$value = @$_POST[$key];

						if(!empty($value)) {
							$new_data = array_merge( $new_data, array( $key => $value ) );
						}
						
					}
					
				}
			
			# --- CAN'T FIND A BETTER WAY TO LIMIT THIS TO THE ADMIN ADD AND EDIT PAGES
			# --- ALSO THE BULK EDIT PAGES DON'T CARY THE FORM POST VARIABLE 
			if(isset($_POST['post_ID'])) {
				$merged_data = wp_parse_args( $new_data, $saved_data );	// Merge default args with those passed on by the function call
				update_post_meta($post_id, $this->meta_key, $new_data);
			}				

		}
	}

}





?>