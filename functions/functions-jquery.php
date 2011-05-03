<?php
/**************************************************************
 INTIATE FRAMEWORK JQUERY SETUP
**************************************************************/
if (!function_exists('init_jquery')) {
	function init_jquery() {
		add_action('init', 'init_jquery_google');
		#	add_action('init', 'init_jquery_local');	
		add_action('init', 'register_jquery_plugins');
		add_action('template_redirect', 'enqueue_jquery_plugins'); 	
	}
}
init_jquery();

		

		

/**************************************************************
 USE GOOGLE'S JQUERY SCRIPT
**************************************************************/
if (!function_exists('init_jquery_google')) {
	function init_jquery_google() {
		if(!is_admin()):
			wp_deregister_script( 'jquery' );
			wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js', false, 1.4);
		endif;
	}
}


/**************************************************************
 USE LOCAL JQUERY SCRIPT
**************************************************************/
if (!function_exists('init_jquery_local')) {
	function init_jquery_local() {
		if(!is_admin()):
			$src = get_stylesheet_directory_uri();
			wp_deregister_script( 'jquery' );
			wp_register_script( 'jquery', $src.'/js/jquery142.min.js', false, 1.4);
		endif;
	}
}



/**************************************************************
 REGISTER SCRIPTS
**************************************************************/
if (!function_exists('register_jquery_plugins')) {
	function register_jquery_plugins() {
		$src = get_stylesheet_directory_uri();
		
		wp_register_script('hoverintent', 	$src."/js/jquery.hoverIntent.js", false, '5', false);
		wp_register_script('mousewheel', 	$src."/js/jquery.mousewheel.js", false, '3.0.4', false);
		wp_register_script('easing', 		$src."/js/jquery.easing.1.2.js", false, '1.1.2', false);	

		wp_register_script('cufon', 		$src."/js/cufon-yui.js", false, '1.09', false);
		
		wp_register_script('superfish', 	$src."/js/superfish.js", false, '1.4.8', false);	
		wp_register_script('supersubs', 	$src."/js/supersubs.js", false, '0.2b', false);
		
	#	wp_register_script('crossslide', 	$src."/js/jquery.cross-slide.js", false, '0.3.3', false);
		wp_register_script('jcycle',		$src."/js/jquery.cycle.all.js", false, '2.99', false);

		wp_register_script('filterable', 	$src."/js/filterable.js", false, '', false);
		wp_register_script('scrollto', 		$src."/js/jquery.scrollTo.js", false, '1.4.2', false);
		wp_register_script('localscroll', 	$src."/js/jquery.localscroll.js", false, '1', false);
		wp_register_script('serialscroll', 	$src."/js/jquery.serialScroll.js", false, '1.4.2', false);
		wp_register_script('smoothdiv', 	$src."/js/jquery.smoothdivscroll.js", false, '0.8', false);

		wp_register_script('anythingslider',$src."/js/jquery.anythingslider.js", false, '1.4', false);	
		wp_register_script('anythingsliderfx',$src."/js/jquery.anythingslider.fx.js", false, '1.4', false);		
		wp_register_script('jscrollpane',$src."/js/jquery.jscrollpane.min.js", false, '2.0', false);	
		
		wp_register_script('fancytransitions', 	$src."/js/jquery.fancytransitions.1.8.js", false, '1.8', false);
		wp_register_script('coinslider', 	$src."/js/jquery.coinslider.min.js", false, '1.0', false);

		wp_register_script('orbit', 	$src."/js/jquery.orbit.js", false, '1.1', false);	
		
		wp_register_script('fancybox', 		$src."/js/jquery.fancybox-1.3.1.js", false, '1.31', false);	
		wp_register_script('qtip',		 	$src."/js/jquery.qtip.js", false, '1.0.0r3', false);		

		wp_register_script('jqueryscripts',	 $src."/js/jqueryscripts.js", false, '1', false);		
		
	}
}



/**************************************************************
 ENQUEUE SCRIPTS
**************************************************************/
if (!function_exists('enqueue_jquery_plugins')) {
	function enqueue_jquery_plugins() {
		global $wp_scripts, $post;
		
			$meta = get_post_meta($post->ID, THEMECUSTOMMETAKEY, true);		
		
		#	LOAD JQUERY 
			wp_enqueue_script('jquery');
			
		#	INTERFACE BEHAVIORS - DEPENDANCIES
			use_wp_enqueue( 'hoverintent', true );				
			use_wp_enqueue( 'mousewheel', true );				
			use_wp_enqueue( 'easing', true );					
			
		#	FONT LOAD
				use_wp_enqueue( 'cufon', false );
			
		#	MENU
			$load = false;	
				use_wp_enqueue( 'superfish', $load );
				use_wp_enqueue( 'supersubs', $load );	

		#	IMAGE GALLERY PLUGINS
			$load = false;	
				use_wp_enqueue( 'crossslide', $load );				// - 	http://tobia.github.com/crossslide/

			$load = false;	
				use_wp_enqueue( 'jcycle', $load );							// - 	http://jquery.malsup.com/cycle/

			if( is_page_template( 'page_portfoliomaker.php') || is_page_template( 'page_portfoliomakermodal.php') || (is_single() && ('portfolio' == get_post_type()))	)
				use_wp_enqueue( 'portfoliomaker', true  );					
							
			$load = false;	
				use_wp_enqueue( 'serialscroll', $load );
				
			$load = false;		
				use_wp_enqueue( 'smoothdiv', $load );	

			$load = false;		
				use_wp_enqueue( 'anythingslider', $load  );	
			
			$load = false;		
				use_wp_enqueue( 'fancytransitions', $load);
				
			$load = false;					
				use_wp_enqueue( 'coinslider', $load);
			
			$load = false;		
				use_wp_enqueue( 'orbit', $load);

		#	UI ENHANCEMENT
			$load = false;
				use_wp_enqueue( 'fancybox', $load );
			
			$load = false;
				use_wp_enqueue( 'qtip', $load );		
			
			$load = false;
				use_wp_enqueue( 'lazyload', $load );		
		
		#	JSCROLLPANE
			$load = false;
				use_wp_enqueue( 'jscrollpane', $load );
				
		#	CUSTOM
			$load = false;
				use_wp_enqueue( 'customthemejquery', $load  );
	}	
}


/**************************************************************
 FDT HELPER FUNCTION
 RUN wp_enqueue_script WHEN $check is TRUE
**************************************************************/
function use_wp_enqueue( $scriptname, $check = false) {
	if( $check )
		wp_enqueue_script( $scriptname );
}


/**************************************************************
 FDT HELPER FUNCTION
 RUN wp_enqueue_script WHEN $check is TRUE
**************************************************************/
function usagecheck_wp_enqueue( $scriptname, $check = false) {
	if( $check )
		wp_enqueue_script( $scriptname );
}
		
		
		
/**************************************************************
 http://www.alivethemes.com/how-to-easily-enqueue-scripts-in-wordpress-with-aframeworks-specially-made-function-called-loader/
**************************************************************/		
function loader()
	{
		// Load Scripts
		$dirs = defined( 'WP_ADMIN' ) ? array( 'inc', 'js' ) : array( 'inc', 'js' );

		if( defined( 'WP_ADMIN' ) && $_REQUEST['page'] != 'aPanel' )
			return;

		foreach ( (array) $dirs as $dir )
		{
			$path = dirname( __FILE__ ) . ( defined( 'WP_ADMIN' ) ? '/admin' : '' ) . "/$dir";

			if ( is_dir( $path ) && $handle = opendir( $path ))
			{
				if ( $dir == 'js' )
				{
					while ( $file = readdir( $handle ) )
					{
						if( !in_array( $file, array('.', '..', 'addtoany-page.js' )))
						{
							wp_register_script( $file, get_bloginfo('template_directory') . ( defined( 'WP_ADMIN' ) ? '/admin' : '' ) . "/$dir/$file" );
							wp_enqueue_script( $file );
						}
					}
				}
				else
				{
					while ( $file = readdir( $handle ))
						if( !in_array( $file, array('.', '..' )))
							require_once $path . "/$file";
				}
				closedir( $handle );
			}
		}
	}
		
		
		
		
		
?>