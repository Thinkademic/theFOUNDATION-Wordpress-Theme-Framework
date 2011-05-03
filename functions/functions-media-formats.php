<?php
/**************************************************************
 DEFAULT IMAGE FORMAT SETUP
**************************************************************/
if(!function_exists('setup_theme_image_formats')) {
	function setup_theme_image_formats() {

		#	UPDATE WORDPRESS OPTIONS UPON ACTIVATION
			//	http://codex.wordpress.org/Option_Reference
		#	if ( is_admin() && isset($_GET['activated'] )  && $pagenow == 'themes.php' ) {
				update_option( 'thumbnail_size_w',  '360' );
				update_option( 'thumbnail_size_h', '240' );
				update_option( 'thumbnail_crop', 1 );
				
				update_option( 'medium_size_w', '540' );
				update_option( 'medium_size_h', '360' );
				
				update_option( 'large_size_w', '880' );
				update_option( 'large_size_h', '540' );				
		#	}			

			

		#	CHANGE THE CROP OPTIONS
			if(	false === get_option("medium_crop")	)
				add_option("medium_crop", "0");
			else
				update_option("medium_crop", "0");
				
			if(false === get_option("large_crop"))
				add_option("medium_crop", "0");
			else
				update_option("large_crop", "0");			

			
		#	CUSTOM THUMBSIZE SETTINGS	
			add_image_size( "minithumbnail", 85, 50, true );		// DIMENSION SIZE FOR THUMBNAIL SIZE 	:: 360 X 240
			add_image_size( "minimedium", 130, 75, true );			// DIMENSION SIZE FOR MEDIUM 			:: 540 X 360
			add_image_size( "minilarge", 170, 95, true );				// DIMENSION SIZE FOR LARGE IMAGES 		:: 880 X 540
			add_image_size( "headerlogo", 180, 180, true );			// DIMENSION SIZE FOR HEADER IMAGES
			add_image_size( "squarethumbnail", 50, 50, true );		// DIMENSION SIZE FOR LARGE IMAGES 		:: 880 X 540
			add_image_size( "squaremedium", 80, 80, true );		// DIMENSION SIZE FOR LARGE IMAGES 		:: 880 X 540
			add_image_size( "squarelarge", 160, 160, true );			// DIMENSION SIZE FOR LARGE IMAGES 		:: 880 X 540		
			#add_image_size( "medium", 580, 480, true );			// DIMENSION SIZE FOR LARGE IMAGES 		:: 880 X 540
	}
}
?>