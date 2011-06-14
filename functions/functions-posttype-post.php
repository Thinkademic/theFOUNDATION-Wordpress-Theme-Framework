<?php
/****
 * functions/functions-posttype-post.php
 *
 * ADD/REMOVE ADDITIONAL FEATURES FOR POST TYPE 'POST'
 */


add_action("init", "post_meta_init");
function post_meta_init() { 
	global $post_meta; 
	$post_meta = new create_post_meta(); 
}

function affiliate_info() {
	echo get_affiliate_info_meta();
}

function affiliate_info_filter( $options ){

	$add = array(
        'content_source_info' => 'Source Attribution',
		'affiliate_info' => 'Affiliate Info',
	);
	
	return array_merge( $options, $add );

}
add_filter( 'build_option_meta_array', affiliate_info_filter );




function get_content_source_info_meta( $args = null ){
  	global $wp_query, $post, $paged;

	$args_default = array (
							"show_price" => true,
							"show_link_url" => true,
							"show_content_source_title" => true,
							"show_content_source_url" => true,
							"show_imagesource_source_title" => true,
							"show_imagesource_source_url" => true
						);

	$args = wp_parse_args( $args, $args_default );
	extract($args);

	# - EVENT DATA IS STORED IN POST_META TABLE
	$post_info_array = get_post_meta($post->ID, "thefdt_metakey", true);

	if(!is_array($post_info_array))
		return;

	# - SEE CLASS DEFINITION FOR VARIABLE NAMES
	extract($post_info_array);

    $content_source = "";
	if($show_content_source_title && $show_content_source_url) :
		if($content_source_url) :
            $content_source = '<a href="'.$content_source_url.'" target="_blank" class="content_source" title="Content Source">Content Via : '. $content_source_title . "</a>";
		else :

		endif;
	else :

	endif;

    $image_source = "";
	if($show_imagesource_source_title && $show_imagesource_source_url) :
		if($imagesource_source_url) :
            $image_source = '<a href="'.$imagesource_source_url.'" target="_blank" class="image_source" title="Image Source">Images Via : '. $imagesource_source_title . "</a>";
		else :

		endif;
	else :

	endif;


	# - COMBINE ALL DETAILS
	$post_info = "
		$content_source
		$image_source
	";

	# - ADD HTML DIV WRAPPER CLASS
	$post_info = xtag("div", $post_info, "class=post_info_content_source");

	return $post_info;

}



function get_affiliate_info_meta( $args = null ){

	global $wp_query, $post, $paged;	
	
	# - FUNCTION DISPLAY OPTIONS
	$args_default = array (
							"show_price" => true,
							"show_link_url" => true,
							"show_content_source_title" => true,
							"show_content_source_url" => true,
							"show_imagesource_source_title" => true,
							"show_imagesource_source_url" => true
						);

	$args = wp_parse_args( $args, $args_default );	
	extract($args);	
	
	# - EVENT DATA IS STORED IN POST_META TABLE
	$post_info_array = get_post_meta($post->ID, "thefdt_metakey", true);	
	
	if(!is_array($post_info_array))
		return;

	# - SEE CLASS DEFINITION FOR VARIABLE NAMES
	extract($post_info_array);


	if($show_price && $link_url) :	
		if($price) :
			$link_url = "<a href='".$link_url."' target='_blank' class='link_url' title='BUY'>I'D BUY IT -  $price</a>";
		else :
			$link_url = "<a href='".$link_url."' target='_blank' class='link_url' title='BUY'>I'D BUY IT</a>";
		endif;		
	else :	
		$link_url = xtag("div", $price, 'class=price');
	endif;		

	
	# - COMBINE ALL DETAILS
	$post_info = "
		$link_url
	";
	
	# - ADD XHTML DIV WRAPPER CLASS
	$post_info = xtag("div", $post_info, "class=post_info");
	
	return $post_info;

}


class create_post_meta {
	
	var $meta_key = "thefdt_metakey";	
	var $meta_fields = array(
		"price",
		"link_url",
		"content_source_title",
		"content_source_url",
		"imagesource_source_title",
		"imagesource_source_url"
	);

	
	function create_post_meta() {
		// ADMIN INTERFACE INIT
		add_action("admin_init", array(&$this, "admin_init"));	
		
		// INSERT POST HOOK
		add_action("wp_insert_post", array(&$this, "wp_insert_post"), 10, 2);			
	}
	
	
	// SETUP META BOXES
	function admin_init() 
	{
		// CUSTOM META BOXES FOR THE EDIT PODCAST SCREEN
		add_meta_box("thefdt_metabox-productinfo", "Product Info", array(&$this, "product_info"), "post", "normal", "low");
	}


	// Build the XHMTL boxes for our meta boxes, used by admin_init()
	function product_info()
	{
		echo form_textinput( $this->meta_key, 'price', 'Price' );
		echo form_textinput( $this->meta_key, 'link_url', 'Affiliate Link Url' );
		echo form_textinput( $this->meta_key, 'content_source_title', 'Content Source Title' );
		echo form_textinput( $this->meta_key, 'content_source_url', 'Content Url' );
		echo form_textinput( $this->meta_key, 'imagesource_source_title', 'Image Source Title' );
		echo form_textinput( $this->meta_key, 'imagesource_source_url', 'Image Url' );
	}




	// When a post is inserted or updated
	function wp_insert_post($post_id, $post = null ) {
		if ($post->post_type == "post") {
		
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
			
			// Can't find a better way to limit this
			// To the Add and Edit POST Page
			// The Bulk Edit pages don't cary this form POST variable 
			if(isset($_POST['post_ID'])) {
				$merged_data = wp_parse_args( $new_data, $saved_data );	// Merge default args with those passed on by the function call
				update_post_meta($post_id, $this->meta_key, $new_data);
			}				

		}
	}
	
	

}





?>