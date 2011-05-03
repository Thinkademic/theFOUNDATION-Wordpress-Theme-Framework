<?php

add_action("init", "post_meta_init");
function post_meta_init() { 
	global $post_meta; 
	$post_meta = new create_post_meta(); 
}



class create_post_meta {
	
	var $meta_key = "thefdt_metakey";	
	var $meta_fields = array(
		"price",
		"link_title",
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
		echo form_textinput( $this->meta_key, 'link_title', 'Affiliate Link Title' );
		echo form_textinput( $this->meta_key, 'link_url', 'Affiliate Link Url' );		
		echo form_textinput( $this->meta_key, 'content_source_title', 'Content Source Title' );
		echo form_textinput( $this->meta_key, 'contenturl', 'Content Url' );
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