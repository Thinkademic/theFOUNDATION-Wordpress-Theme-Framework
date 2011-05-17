<?php
/**************************************************************
 DEFAULT IMAGE FORMAT SETUP
**************************************************************/
if(!function_exists('setup_theme_image_formats')) {
	function setup_theme_image_formats() {

		#	UPDATE WORDPRESS OPTIONS UPON ACTIVATION
			//	http://codex.wordpress.org/Option_Reference
			if ( is_admin() && isset($_GET['activated'] )  && $pagenow == 'themes.php' ) {
				update_option( 'thumbnail_size_w',  '340' );
				update_option( 'thumbnail_size_h', '225' );
				update_option( 'thumbnail_crop', 1 );
				
				update_option( 'medium_size_w', '580' );
				update_option( 'medium_size_h', '580' );
				
				update_option( 'large_size_w', '940' );
				update_option( 'large_size_h', '520' );				
			}			

			

		#	CHANGE THE CROP OPTIONS
			if(	false === get_option("medium_crop")	)
				add_option("medium_crop", "0");
			else
				update_option("medium_crop", "0");
				
			if(false === get_option("large_crop"))
				add_option("large_crop", "1");
			else
				update_option("large_crop", "1");				

			
		#	CUSTOM THUMBSIZE SETTINGS	
			add_image_size( "minithumbnail", 85, 50, true );		// DIMENSION SIZE FOR 'MINITHUMBNAIL'
			add_image_size( "minimedium", 130, 75, true );			// DIMENSION SIZE FOR 'MINIMEDIUM'
			add_image_size( "minilarge", 170, 95, true );				// DIMENSION SIZE FOR 'MINILARGE'
			add_image_size( "headerlogo", 180, 180, true );			// DIMENSION SIZE FOR 'HEADERLOGO'
			#add_image_size( "squarethumbnail", 50, 50, true );		// DIMENSION SIZE FOR 'SQAURETHUMBNAIL'
			#add_image_size( "squaremedium", 80, 80, true );		// DIMENSION SIZE FOR 'SQUAREMEDIUM'
			#add_image_size( "squarelarge", 160, 160, true );			// DIMENSION SIZE FOR 'SQUARELARGE'
	}
}
?>