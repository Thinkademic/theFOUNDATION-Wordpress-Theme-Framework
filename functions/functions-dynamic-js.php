<?php
/**************************************************************
	ADD NEW QUERY VAR TO WORDPRESS INTERNAL 
	QUERY VAR LIST, WILL BE USED TO SETUP DYNAMIC JS PAGE
**************************************************************/
add_filter('query_vars', 'add_new_var_to_wp_js');
function add_new_var_to_wp_js($public_query_vars) {
	$public_query_vars[] = 'dynamic';
	$public_query_vars[] = 'jqpostid';	
	return $public_query_vars;
}


/**************************************************************
	REDIRECT TEMPLATE FILE WHENEVER QUERY VALUE IS PRESENT
	TO A DYNAMICALLY GENERATRED FILE
**************************************************************/	
add_action('template_redirect', 'dynamic_js_display');
function dynamic_js_display(){
	$js = get_query_var('dynamic');
	$themeoptions = get_query_var('jqpostid');
	
	if ($js == 'js'  || $themeoptions == 'custom' ){
		#include_once (STYLESHEETPATH  . '/js/jquery.php');		// CHILDTHEMEFOLDER/js/jquery.php
		include_once (TEMPLATEPATH  . '/js/jquery.php');			// PARENTTHEMEFOLDER/js/jquery.php
		exit;
	}
}


/**************************************************************
 ADD REWRITE RULES
**************************************************************/
add_action('init', 'flush_rewrite_rules');
function custom_js_add_rewrite_rules( $wp_rewrite ) {
    $new_rules = array( 
        'dynamic/themeoptions/([a-zA-Z0-9]+).js' => 'index.php?dynamic=themeoptions&jqpostid=' . $wp_rewrite->preg_index(1),					// Regex Match letters only	
        'dynamic/js/(\d+).js' => 'index.php?dynamic=js&jqpostid=' . $wp_rewrite->preg_index(1),
    );
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
add_action('generate_rewrite_rules', 'custom_js_add_rewrite_rules');



/**************************************************************
	DYANMICALLY ENQUEUE JS CONDITIONALLY
**************************************************************/
add_action("wp_head","add_dynamic_js", 20);
function add_dynamic_js() {
   global $posts, $wp_scripts;

	// SET SCRIPT VERSION NUMBER
	$vers = '1.0';

	// DEFINE HOOK	
	do_action('fdt_enqueue_dynamic_js');

	// DETERMINE IF FRIENDLY PERMALINKS ARE BEING USED
	$permalinkon = !is_null(get_option('permalink_structure')) ? true : false ;
	
	 // TURN OFF PERMALINKS FOR NOW, GETTING A 301 REDIRECT ERORR MAKES IT SLOWER
	#$permalinkon  = false;
	
	// ENQUEUE JQUERY SCRIPTS FROM THEME OPTIONS SETTINGS
	if($permalinkon) :
		wp_enqueue_script( "dynamiccss$post->ID", get_home_url().'/dynamic/themeoptions/custom.js', false, $vers, true);	
	else :
		wp_enqueue_script( "dynamiccss$post->ID", get_home_url().'/?dynamic=themeoptions&jqpostid=custom', false, $vers, true);
	endif;
	
	
	foreach ($posts as $post) {	
		$meta = get_post_meta($post->ID, THEMECUSTOMMETAKEY, true);

        if ( $meta["gallery_type"] == "jcyclegallery" ) :	
			if($permalinkon) 
				wp_enqueue_script( "dynamicjs$post->ID" , get_home_url().'/dynamic/js/'.$post->ID.'.js' , array('jcycle'), $vers, true);			
			else
				wp_enqueue_script( "dynamicjs$post->ID" , get_home_url().'/?dynamic=js&jqpostid='.$post->ID, array('jcycle'), $vers, true);
		endif;
		
        if ( $meta["gallery_type"] == "anythingslider" ) :	
			if($permalinkon) 
				wp_enqueue_script( "dynamicjs$post->ID" , get_home_url().'/dynamic/js/'.$post->ID.'.js' , array('anythingslider', 'anythingsliderfx'), $vers, true);			
			else
				wp_enqueue_script( "dynamicjs$post->ID" , get_home_url().'/?dynamic=js&jqpostid='.$post->ID, array('anythingslider', 'anythingsliderfx'), $vers, true);
		endif;

        if ( $meta["gallery_type"] == "nivoslider" ) :	
			if($permalinkon) 
				wp_enqueue_script( "dynamicjs$post->ID" , get_home_url().'/dynamic/js/'.$post->ID.'.js' , array('nivoslider'), $vers, true);			
			else
				wp_enqueue_script( "dynamicjs$post->ID" , get_home_url().'/?dynamic=js&jqpostid='.$post->ID, array('nivoslider'), $vers, true);
		endif;			
	}



	echo "\n<!-- JS GENERATED SCRIPTS -->\n";	
	wp_print_scripts();

}

?>