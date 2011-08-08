<?php
/****
 * functions/functions-posttype-product.php
 *
 * CUSTOM POST TYPE FOR PRODUCTS
 */



/**************************************************************

 Append Event Post type with custom fields from in post_meta 
 Info, associated Taxonimies and relevant Media.
 
**************************************************************/
add_action ( 'the_content', 'append_product_posttype' );		
function append_product_posttype($content) {
	global $wp; 
	
	if( get_post_type() == 'event' ) {
		$productpost = xtag("div", get_the_content(), "id=productdescription");
		$productpost .= retrieve_single_product_details();
		$productpost .= get_jcyclegallery("imagesize=medium");
		$productpost = xtag( "div", $productpost, "id=productpost" );
		
		return $productpost;
	} else {
		return $content;
	}
}




/**************************************************************

 Alter the posts_per_page setting for our custom post type
 taxonomy template view.  The taxonomy-term.php template will
 only display one item at a time, this allows us to simulate
 next / previous features of a single product view.
 
 Consder finding a better solution. Preferably a next/prev function
 that works within the single-custompostype.php template
 
**************************************************************/

function mytest_parse_request( $wp ) { 
        global $wp_query; 
        $wp_query->is_main_loop = true; 
        return $wp; 
} 
#add_action( 'parse_request', 'mytest_parse_request' );

function mytest_pre_get_posts( $query ) { 
  if ( isset($query->is_main_loop) && $query->is_main_loop && is_tax() ) { 
    $query->set( 'posts_per_page', 1 ); 
    $wp_query->is_main_loop = false; 
  } 
  return $query; 
} 
#add_action( 'pre_get_posts', 'mytest_pre_get_posts' );


	
	
	
	
	
	
	
	
	
	

	

/**************************************************************

 Template Functions
 
**************************************************************/
function single_product_details($args = null){
	echo retrieve_single_product_details($args);
}



function retrieve_single_product_details($args = null){
	global $wp_query, $post, $paged;

	# - FUNCTION DISPLAY OPTIONS
		$args_default = array (
								"show_collection" => true,
								"show_stylenum" => true,
								"show_color" => true,
								"show_cost" => true,
								"show_material" => true,
								"show_finish" => true								
							);						
		$args = wp_parse_args( $args, $args_default );	
		extract($args);

	# -	ACCESS DATA FROM POST_META TABLE
		$post_meta = get_post_meta($post->ID, "whiteboard_product", true);
		$product_height = $post_meta['product_height'];
		$product_width = $post_meta['product_width'];
		$product_depth = $post_meta['product_depth'];
		$product_seatheight = $post_meta['product_seatheight'];

	# - ACCESS DATA FROM ASSOCIATED TAXONOMIES
		$collection = retrieve_term_as_list('taxonomy=collection');
		$stylenum =	"#SSF".retrieve_term_as_list('taxonomy=stylenum');
		$material =	retrieve_term_as_list('taxonomy=material');
		$finish =	retrieve_term_as_list('taxonomy=finish');		
		$color = retrieve_term_as_list('taxonomy=color');
		$cost = retrieve_term_as_list('taxonomy=cost');	
	
	# - WRAP DIVS + TITLES
		$collection = xtag('div', $collection, 'class=collection', '<h4>Collection</h4>');
		$stylenum = xtag('div', $stylenum, 'class=stylenumber', '<h4>Style Number</h4>');
		$material = xtag('div', $material, 'class=material', '<h4>Material</h4>');
		$finish = xtag('div', $finish, 'class=finish', '<h4>Finish</h4>');		
		$color = xtag('div', $color, 'class=color', '<h4>Color</h4>');
		$cost = xtag('div', $cost, 'class=cost', '<h4>Cost</h4>');
		$product_height = xtag('span', $product_height, '', 'H: ', ' in');
		$product_width = xtag('span', $product_width, '', 'W: ', ' in');
		$product_depth = xtag('span', $product_depth, '', 'D: ', ' in');
		$product_seatheight = xtag('span', $product_seatheight,  '',  'SH:', ' in');

		$dimentions = xtag('div', $product_height.$product_width.$product_depth.$product_seatheight, '', '<h4>Dimensions</h4>');
	
	
	# - BUILD AGG	
	$xhtml = "
		$collection
		$stylenum
		$material
		$finish
		$color
		$dimentions
		$cost	
	";
	
	
	$xhtml = xtag( "div", $xhtml, "class=productdetails");
	
	return $xhtml;
}


function retrieve_single_product_details_filter() {

	global $wp_query, $post, $paged;

	// Function options
	$args = array (
		"show_collection" => true,
		"show_stylenum" => true,
		"show_color" => true,
		"show_cost" => true,
		"show_material" => true
	);				
	
	$details .= retrieve_single_product_details( $args );
	return $details;
}




















function archive_product(){
	echo retrieve_archive_product();
}


function retrieve_archive_product(){

}

	
function build_archive_product() {
	global $wp_query, $post, $paged;

	
	$collection = get_query_var('collection');

	
	$queryargs = array (
					'post_type' => 'product',
					'collection' => $collection,
				);

	$markup = array (	"entry" => "entry",
						"entry_image" => "entry_image",
						"entry_content_box" => "entry_content_box",
						"entry_content" => "entry_content"
					);				

	$options = array (
						"type_of_content" => "false",			// false = content will not be shown | 'the_excerpt' = get_the_excerpt() | 'the_content' = get_the_content();
						"selected_image" => "first",			// false = image will not be shown | 'featured' = featured mediaattachment | everything else defaultd to the first image, bseed on image order
						"mediasize" => "thumbnail",
						"medialinkactive" => true,
						"image_after_title" => true,
						"title_markup" => "a",					// false = title will not be shown | 'a' = hyperlink will wrap title | 'tagname' = tagname will wrap title, <tagname>title</tagname>
						"add_class_counter" => false,
						"filtername" => "buildmarkup_from_query_action"
					);
					
	// Use a filter to insert Postmeta data, Slips it in our custom query function
	add_filter('buildmarkup_from_query_action', 'retrieve_single_product_details_filter');			// Use a filter to insert Postmeta data, Slips it in our custom query function	

	
	$items = buildmarkup_from_query( $queryargs , $options, $markup); 
	return $items;
}


function get_product_items() {
	global $wp_query, $post, $paged;

	$queryargs = array (
					'post_type' => 'product', 
					'posts_per_page' => 100
			 		//'post_status' => 'future'
				);

	$markup = array (	"entry_wrapper" => "entry",
						"entry_image" => "entry_image",
						"entry_content_box" => "entry_content_box",
						"entry_content" => "entry_content"
					);				

	$options = array (
						"type_of_content" => "false",			// false = content will not be shown | 'the_excerpt' = get_the_excerpt() | 'the_content' = get_the_content();
						"type_of_media" => "first",			// false = image will not be shown | 'featured' = featured mediaattachment | everything else defaultd to the first image, bseed on image order
						"mediasize" => "minilarge",
						"hyperlink_target" => "linktoparent",
						"hyperlink_enable" => true,						
						"media_has_hyperlink" => true,
						"image_after_title" => true,
						"title_format" => "a",					// false = title will not be shown | 'a' = hyperlink will wrap title | 'tagname' = tagname will wrap title, <tagname>title</tagname>
						"wrapper_class_counter" => false,
						"filtername" => "buildmarkup_from_query_action"
					);
					
	// Use a filter to insert Postmeta data, Slips it in our custom query function
	// add_filter('buildmarkup_from_query_action', 'get_list_upcoming_events_filter');			// Use a filter to insert Postmeta data, Slips it in our custom query function	

	
	$portfoliio_items = buildmarkup_from_query( $queryargs , $options, $markup); 
	return $portfoliio_items;
}





	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
/**************************************************************

 INITIATE OUR CUSTOM POSTTYPE CLASS
 
**************************************************************/
add_action("init", "mantone_product_init");
function mantone_product_init() { 
	global $mantone_product; 
	$mantone_product = new create_product(); 
}







/**************************************************************
 
 CUSTOM POSTTYPE CLASS DEFINITION
 
**************************************************************/
class create_product {
	// Build an array to store our custom fields data
	
	var $meta_key = "whiteboard_product";	
	
	var $meta_fields = array(
		"product_width",
		"product_height",
		"product_depth",
		"product_seatheight"
	);

	var $thelabel = "Product";

	//////////// Class Definition
	function create_product() {

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
		register_post_type('product', array(
			'labels' => $labels,
			'description' => 'A product for display.',			
			'public' => true,
			'show_ui' => true,						// UI in admin panel
			'_builtin' => false,					// It's a custom post type, not built in
			'_edit_link' => 'post.php?post=%d',
			'capability_type' => 'page',
			'hierarchical' => false,
			'rewrite' => array("slug" => "product"),	// Permalinks
			'query_var' => "product",				// This goes to the WP_Query schema
			//'taxonomies' => array('category'), 				// defined elsewehre - see register_taxonomy_for_object_type()
			'menu_position' => 5,					// (5) Below Post (10) Below Media (20) Below Pages
			'menu_icon' => get_stylesheet_directory_uri() . '/images/admin/product.png',
			'has_archive' => true,
            'supports' => array('title',
								'editor',
								//'comments',
								'thumbnail',	
								//'custom-fields'	// Let's use custom fields for debugging purposes only
								//'trackbacks',
								//'revisions',
								'author',
								'excerpt',
								'page-attributes' 
								) 
			)

		);
					
			
		register_taxonomy(
			'cost', 							// Name of new taxonomy
			'product', 							// The post type that will use this taxonomy
			array(
				'hierarchical' => false,  
				'labels' => array(
					'name' => 'Cost',
					'search_items' => 'Search Cost Number',
					'popular_items' => 'Popular Cost Number',
					'add_new_item' => 'Add new Cost Number',
					'all_items' => 'All Cost Number',
					'separate_items_with_commas' => 'Seperate Cost with commas',
					'choose_from_most_used' => 'Select from Cost'
				) ,
				'query_var' => true, 
				'rewrite' => true 
				)
			);			

		register_taxonomy(
			'stylenum', 							// Name of new taxonomy
			'product', 							// The post type that will use this taxonomy
			array(
				'hierarchical' => false,  
				'labels' => array(
					'name' => 'Style Number',
					'search_items' => 'Search Style Number',
					'popular_items' => 'Popular Style Number',
					'add_new_item' => 'Add new Style Number',
					'all_items' => 'All Style Number',
					'separate_items_with_commas' => 'Seperate Style Number with commas',
					'choose_from_most_used' => 'Select from Style Number'
				) ,
				'query_var' => true, 
				'rewrite' => true 
				)
			);			
			
			
		register_taxonomy(
			'collection', 								// Name of new taxonomy
			'product', 								// The post type that will use this taxonomy
			array(
				'hierarchical' => true,  
				'labels' => array(
					'name' => 'Collection',
					'search_items' => 'Search Collection',
					'popular_items' => 'Popular Collection',
					'add_new_item' => 'Add new Collection name',
					'all_items' => 'All Collections',
					'separate_items_with_commas' => 'Seperate Collection with commas',
					'choose_from_most_used' => 'Select from Collection'
				) ,
				'query_var' => true, 
				'rewrite' => true 
				)
			);	

		register_taxonomy(
			'type', 								// Name of new taxonomy
			'product', 								// The post type that will use this taxonomy
			array(
				'hierarchical' => true,  
				'labels' => array(
					'name' => 'Type',
					'search_items' => 'Search Type',
					'popular_items' => 'Popular Types',
					'add_new_item' => 'Add new Type name',
					'all_items' => 'All Types',
					'separate_items_with_commas' => 'Seperate Types with commas',
					'choose_from_most_used' => 'Select from Types'
				) ,
				'query_var' => true, 
				'rewrite' => true 
				)
			);				
			
		register_taxonomy(
			'finish', 								// Name of new taxonomy
			'product', 									// The post type that will use this taxonomy
			array(
				'hierarchical' => true,  
				'labels' => array(
					'name' => 'Finish',
					'search_items' => 'Search Finishes',
					'popular_items' => 'Popular Finishes',
					'add_new_item' => 'Add new Finish',
					'all_items' => 'All Finishes',
					'separate_items_with_commas' => 'Seperate Finishes with commas',
					'choose_from_most_used' => 'Select from Finishes'
				) ,
				'query_var' => true, 
				'rewrite' => true 
				)
			);				
			
			
		register_taxonomy(
			'color', 									// Name of new taxonomy
			'product', 									// The post type that will use this taxonomy
			array(
				'hierarchical' => true,  
				'labels' => array(
					'name' => 'Color',
					'search_items' => 'Search Colors',
					'popular_items' => 'Popular Colors',
					'add_new_item' => 'Add new Color Option',
					'all_items' => 'All Availiable Colors',
					'separate_items_with_commas' => 'Seperate Colors with commas',
					'choose_from_most_used' => 'Select from Colors'
				) ,
				'query_var' => true, 
				'rewrite' => true 
				)
			);
			
		register_taxonomy(
			'material', 									// Name of new taxonomy
			'product', 									// The post type that will use this taxonomy
			array(
				'hierarchical' => true,  
				'labels' => array(
					'name' => 'Material',
					'search_items' => 'Search Materials',
					'popular_items' => 'Popular Materials',
					'add_new_item' => 'Add new Material Option',
					'all_items' => 'All Availiable Materials',
					'separate_items_with_commas' => 'Seperate Materials with commas',
					'choose_from_most_used' => 'Select from Materials'
				) ,
				'query_var' => true, 
				'rewrite' => true 
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

		// CUSTOM COLUMNS FOR PRODUCT ADMIN PAGE
		add_action("manage_posts_custom_column",   array(&$this, "product_custom_columns") , 10 ,2);
		add_filter("manage_edit-product_columns", array(&$this, "product_edit_columns") );	
		
		// CUSTOM META BOXES FOR THEME LAYOUT OPTIONS
		add_meta_box("thefoundation_productdimensions", 
					 "PRODUCT DIMENSIONS", 
					 array(&$this, "add_gallerylayoutoptions_forpage"), 
					 "product", 
					 "side", 
					 "low"
		);		
		
		
	
	}

	
	// Insert New Columns
	function product_edit_columns($cols){
		
		$new_columns = array (
			'collection' =>	__('Collection'),
			'stylenum' => __('Style Number'),			
			'type' =>	__('Type'),			
			'material' => __('Material'),
			'finish' => __('Finish'),			
			'color' => __('Color'),			
			'cost' => __('Cost'),
			'height' => __('Height'),
			'width' => __('Width'),			
			'depth' => __('Depth'),
			'seatheight' => __('Seat Height'),			
			
		);
		
		$cols = array_merge( $cols, $new_columns);

		return $cols;
	}
	

	// Pull Values for New Columns
	function product_custom_columns($column, $post_id){
	  global $post;

	 $postmeta_field = get_post_meta($post->ID, "whiteboard_product", true);	
	
	
	 
	  switch ($column) {
		case "collection":
		  echo get_the_term_list($post->ID, 'collection', '', ', ','');
		  break;
		case "type":
		  echo get_the_term_list($post->ID, 'type', '', ', ','');
		  break;	  		  
		case "stylenum":
		  echo get_the_term_list($post->ID, 'stylenum', '', ', ','');
		  break;	  
		case "material":
		  echo get_the_term_list($post->ID, 'material', '', ', ','');
		  break;	
		case "finish":
		  echo get_the_term_list($post->ID, 'finish', '', ', ','');
		  break;	
		case "color":
		  echo get_the_term_list($post->ID, 'color', '', ', ','');
		  break;			  
		case "cost":
		  echo get_the_term_list($post->ID, 'cost', '', ', ','');
		  break;		
		case "height":
		  echo $postmeta_field["product_height"];  
		  break;
		case "width":
		  echo $postmeta_field["product_width"]; 
		  break;		  
		case "depth":
		  echo $postmeta_field["product_depth"];
		  break;		
		case "seatheight":
		  echo $postmeta_field["product_seatheight"];  	  
		  break;		  
		  
	  }
	}
	
	////////////------------------------------------ Build the XHMTL boxes for our meta boxes, used by admin_init()
	function add_gallerylayoutoptions_forpage()
	{
			global $post;
			$custom = get_post_custom($post->ID);
			$saved_meta_fields = get_post_meta( $post->ID,  $this->meta_key,  true );	
		
			// WIDTH
			$output .= "<p><strong>Width</strong></p>";					
			$output .= form_textinput( $this->meta_key, 'product_width', '' );			
			
			// HEIGHT
			$output .= "<p><strong>Height</strong></p>";					
			$output .= form_textinput( $this->meta_key, 'product_height', '' );						

			// DEPTH
			$output .= "<p><strong>Depth</strong></p>";					
			$output .= form_textinput( $this->meta_key, 'product_depth', '' );						
			
			// SEAT HEIGHT
			$output .= "<p><strong>Seat Height</strong></p>";					
			$output .= form_textinput( $this->meta_key, 'product_seatheight', '' );						
		
	
			echo $output;	  	
	}

	
		
	
	// When a post is inserted or updated
	function wp_insert_post($post_id, $post = null ) {
		if ($post->post_type == "product") {
		
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
			// To the Add and Edit Product Page
			// The Bulk Edit pages don't cary this form POST variable 
			if(isset($_POST['post_ID'])) {
				$merged_data = wp_parse_args( $new_data, $saved_data );	// Merge default args with those passed on by the function call
				update_post_meta($post_id, $this->meta_key, $new_data);
			}				

		}
	}


}








?>