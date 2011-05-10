<?php
	
/*
 *	CUSTOMPOSTTYPE INIT
 */
add_action("init", "thefdt_dictionary_init");
function thefdt_dictionary_init() { 
	global $thefdt_dictionary; 
	$thefdt_dictionary = new create_dictionary(); 
}


/*
 *	CUSTOMPOSTTYPE PORTFOLIO CLASS DEFINITION
 */
class create_dictionary {


	//	IF YOU NEED A METABOXES, THEN BUILD AN
	//	BUILD AN ARRAY TO STORE OUR  DATA IN ONE CUSTOM FIELD
	var $meta_key = "_thefdt_dictionary";	
	var $meta_fields = array(
		"dictionary_meta", 				// EXAMPLE FIELD
	);

	
	
	
	var $thelabel = "Dictionary";

	// CLASS DEFINITION
	function create_dictionary() {

		$labelname = $this->thelabel;	

		// Set the Labels for our new Custom Post Type
		$labels = array(
			'name' => _x( $labelname.'', 'post type general name'),
			'singular_name' => _x( $labelname , 'post type singular name'),
			'add_new' => _x('Add', $labelname),
			'add_new_item' => __("Add $labelname"),
			'edit_item' => __("Edit $labelname"),
			'edit' => _x('Edit', $labelname),
			'new_item' => __("New $labelname"),
			'view_item' => __("View $labelname"),
			'search_items' => __("Search $labelname"),
			'not_found' =>  __("No $labelname found"),
			'not_found_in_trash' => __("$labelname found in Trash"), 
			'view' =>  __("View $labelname"),
			'parent' => ''
		);			

		// See http://codex.wordpress.org/Function_Reference/register_post_type
		register_post_type('dictionary', array(
			'labels' => $labels,
			'description' => 'A dictionary contains professional work.',			
			'public' => true,
			'show_ui' => true,											// UI in admin panel
			'_builtin' => false,											// It's a custom post type, not built in
			'_edit_link' => 'post.php?post=%d',
			'capability_type' => 'page',
			'hierarchical' => true,
			'rewrite' => array("slug" => "dictionary"),			// Permalinks
			'query_var' => "dictionary",								// This goes to the WP_Query schema
			//'taxonomies' => array('category'), 				// defined elsewehre - see register_taxonomy_for_object_type()
			'menu_position' => 5,										// (5) Below Post (10) Below Media (20) Below Pages
			'menu_icon' => get_stylesheet_directory_uri() . '/images/admin/dictionary.png',
			'supports' => array('title', 
								'editor',
								//'comments',
								'thumbnail',	
								//'custom-fields'	// Let's use custom fields for debugging purposes only
								//'trackbacks',
								'revisions',
								'author',
								'excerpt',
								'page-attributes',
								'post-formats'
								) 
			)

		);
		

		// Admin interface init
		add_action("admin_init", array(&$this, "admin_init"));
		// add_action("template_redirect", array(&$this, 'template_redirect'));	// No need to Redirect, single-posttypeslug.php

		// Insert post hook
		add_action("wp_insert_post", array(&$this, "wp_insert_post"), 10, 2);			

	}





	//////////// Class Functions

	// Setup meta boxes
	function admin_init() {

		// Custom Columns for Dictionary admin page, makes sure capability_type matches with proper
		// manage_posts_custom_column or manage_pages_custom_column, or the overview page will not
		// fill out the data
		add_action("manage_pages_custom_column",   array(&$this, "dictionary_custom_columns") , 10 ,2);		
		add_filter("manage_edit-dictionary_columns", array(&$this, "dictionary_edit_columns") );	
		add_filter("manage_edit-dictionary_sortable_columns", array(&$this, "dictionary_column_register_sortable"), 10, 2 );
		add_filter("dictionary_orderby", array(&$this, "dictionary_column_orderby"), 10, 2);								
	}
		

	
	// INSERT NEW COLUMNS FOR ADMIN SIDE
	function dictionary_edit_columns($cols){

		$new_columns = array (
			'grouping' =>	__('Grouping'),
			'client' => __('Client'),	
			'credits' => __('Credit'),			
		);
		
		$cols = array_merge( $cols, $new_columns);

		return $cols;
	}
	

	// PULL VALUES FOR NEW COLUMNS
	function dictionary_custom_columns($column, $post_id){
	  global $post;
	 
	  switch ($column) {
		case "grouping":
		  echo get_the_term_list($post->ID, 'grouping', '', ', ','');
		  break;
		case "client":
		  echo get_the_term_list($post->ID, 'client', '', ', ','');
		  break;
		case "credits":
		  echo get_the_term_list($post->ID, 'credits', '', ', ','');
		  break;		  
	  }
	}
	
	
	// REGISTER THE COLUMN AS SORTABLE
	function dictionary_column_register_sortable($cols) {

		$new_columns = array (
			'grouping' =>	__('Grouping'),
			'client' => __('Client'),	
			'credits' => __('Credit'),
			);
		
		$cols = array_merge( $cols, $new_columns);

		return $cols;
	}

	
	// ENABLE SORTING ON COLUMNS
	function dictionary_column_orderby($orderby, $wp_query){
		global $wpdb;
	 
	  switch (@$wp_query->query['orderby']) {
		case "grouping":
			$orderby = "(SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $wpdb->posts.ID AND meta_key = 'grouping') " . $wp_query->get('order');
		  break;
		case "client":
			$orderby = "(SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $wpdb->posts.ID AND meta_key = 'client') " . $wp_query->get('order');
		  break;
		case "credits":
			$orderby = "(SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $wpdb->posts.ID AND meta_key = 'credits') " . $wp_query->get('order');
		  break;		  
	  }
 
	  return $orderby;
	}


	
	// WHEN A POST IS INSERTED OR UPDATED

	function wp_insert_post($post_id, $post = null ) {
		if ($post->post_type == "dictionary") {
		
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
			// To the Add and Edit Dictionary Page
			// The Bulk Edit pages don't cary this form POST variable 
			if(isset($_POST['post_ID'])) {
				$merged_data = wp_parse_args( $new_data, $saved_data );	// Merge default args with those passed on by the function call
				update_post_meta($post_id, $this->meta_key, $new_data);
			}				

		}
	}


}




?>