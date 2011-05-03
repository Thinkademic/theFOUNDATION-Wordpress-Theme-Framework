<?php
/**************************************************************
	ADD NEW QUERY VAR TO WORDPRESS INTERNAL 
	QUERY VAR LIST WILL BE USED TO SETUP DYNAMIC CSS PAGE
**************************************************************/	
add_filter('query_vars', 'add_new_var_to_wp');
function add_new_var_to_wp($public_query_vars) {
	$public_query_vars[] = 'dynamic';
	$public_query_vars[] = 'csspostid';		
	return $public_query_vars;
}


/**************************************************************
	REDIRECT TEMPLATE FILE WHENEVER QUERY VALUE IS PRESENT
**************************************************************/	
add_action('template_redirect', 'dynamic_css_display');
function dynamic_css_display(){
	$css = get_query_var('dynamic');
	$themeoptions = get_query_var('csspostid');
		
	if ($css == 'css' || $themeoptions == 'custom' ){
		// include_once (STYLESHEETPATH  . '/css/style.php');
		include_once (TEMPLATEPATH  . '/css/style.php');			// PARENTTHEMEFOLDER/js/jquery.php
		exit;
	}
}


/**************************************************************
 ADD CSS REWRITE RULLES
**************************************************************/
add_action('init', 'flush_rewrite_rules'); 													// FLUSH RULES IF YOU ADD NEW REWRITE RULES
function custom_css_add_rewrite_rules( $wp_rewrite ) {
    $new_rules = array( 
        'dynamic/themeoptions/([a-zA-Z0-9]+).css' => 'index.php?dynamic=themeoptions&csspostid=' . $wp_rewrite->preg_index(1),			// Regex Match letters only
        'dynamic/css/(\d+).css' => 'index.php?dynamic=css&csspostid=' . $wp_rewrite->preg_index(1)														// Regex Match numbers only
    );
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
add_action('generate_rewrite_rules', 'custom_css_add_rewrite_rules');


/**************************************************************
	DYANMICALLY ENQUEUE CSS CONDITIONALLY
**************************************************************/
add_action("wp_head","add_dynamic_css", 20);
function add_dynamic_css() {
   global $posts, $wp_scripts;
	
	// DEFINE HOOK
	do_action('fdt_enqueue_dynamic_css');
   
	// DETERMINE IF PERMALINKS IS SET
	$permalinkon = !is_null(get_option('permalink_structure')) ? true : false ;
	// WRITE CODE TO ACCOUNT FOR THE FOLLOWING CASES :: 
	// using_index_permalinks() 
	// using_mod_rewrite_permalinks 
	// using_permalinks() 
	
	 // TURN OFF PERMALINKS FOR NOW, GETTING A 301 REDIRECT ERORR MAKES IT SLOWER
	#$permalinkon  = false;
	
	// ENQUEUE CSS FROM THEME OPTIONS
	if($permalinkon) :
		wp_enqueue_style( "dynamiccss$post->ID" , get_home_url().'/dynamic/themeoptions/custom.css' , false, "2", "screen");	
	else :
		wp_enqueue_style( "dynamiccss$post->ID" , get_home_url().'/?dynamic=themeoptions&csspostid=custom', false, "1", "screen");
	endif;


	// ENQUEUE CSS FROM DYNAMIC GALLERIES
	foreach ($posts as $post) {	
		$meta = get_post_meta($post->ID, THEMECUSTOMMETAKEY, true);
		
		// CHECK IF WE ARE USING A GALLERY
        if ( $meta["gallery_type"] != "" ) :	
			if($permalinkon) :
				wp_enqueue_style( "dynamiccss$post->ID" , get_home_url().'/dynamic/css/'.$post->ID.'.css' , false, "1", "screen");	
			else :
				wp_enqueue_style( "dynamiccss$post->ID" , get_home_url().'/?dynamic=css&csspostid='.$post->ID, false, "1", "screen");
			endif;
		endif;
	}

	echo "\n".'<!-- CSS Generated from Theme Options  -->'."\n";
	wp_print_styles();

}




?>