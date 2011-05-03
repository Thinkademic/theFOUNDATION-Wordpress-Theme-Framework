<?php
/**************************************************************
 SETUP UP HEADER STYLE FOR OUR THEME FRAMEWORK,
 OTHERWISE KNOWN AS LOGO
**************************************************************/	
if (!function_exists('theme_header_setup')) {
	function theme_header_setup() {
		define( 'HEADER_TEXTCOLOR', 'ffffff' );
		define( 'HEADER_IMAGE', get_stylesheet_directory_uri() .'/images/logo/logo_default.png' ); 			// 	%S IS THE TEMPLATE DIR URI
		define( 'HEADER_IMAGE_WIDTH', apply_filters( 'foundation_header_image_width', 180) );
		define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'foundation_header_image_height',	180) );

		define( 'NO_HEADER_TEXT', true );																											//		DONTS SUPPORT TEXT COLOR CHANGE
		add_custom_image_header( 'enable_header_style', 'enable_admin_header_style' );								// 	SETUP HEADER STYLE SHEET FUNCTION
		
		register_default_headers( 																														//		REGISTER HEADERS
			array (
						'default' => array (
							'url' => '%s/images/header/header_default.png',
							'thumbnail_url' => '%s/images/header/header_default.png',
							'description' => __( 'MUSEUM', 'thefoundation' )
							),
						'bardeaux' => array (
							'url' => '%s/images/logo/logo_bardeaux.png',
							'thumbnail_url' => '%s/images/logo/logo_bardeaux.png',
							'description' => __( 'Bardeaux', 'thefoundation' )
							)					
			) 
		);
	
	
	}
}
theme_header_setup();



/**************************************************************
 OUTPUT CSS
**************************************************************/	
if (!function_exists('enable_header_style')) {
	function enable_header_style() {
		echo '	
		<style type="text/css">
			#mastline h1 {
				background: url( "'.get_header_image().'");
				height: '.HEADER_IMAGE_HEIGHT.'px;
				width: '.HEADER_IMAGE_WIDTH.'px;			
			}
		</style>
		';
	}
}

/**************************************************************
 OUTPUT ADMIN CSS
**************************************************************/	
if (!function_exists('enable_admin_header_style')) {
	function enable_admin_header_style() {
		echo '
		<style type="text/css">
			#headimg {
				height: '.HEADER_IMAGE_HEIGHT.'px;
				width: '.HEADER_IMAGE_WIDTH.'px;
			}
			.default-header {
				width: 100%;
			}
		</style>
		';
	}
}

?>