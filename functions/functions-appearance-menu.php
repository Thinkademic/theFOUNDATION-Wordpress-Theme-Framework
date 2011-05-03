<?php

/**************************************************************
 REGISTER CUSTOM WORDPRESS MENU
**************************************************************/
add_action( 'init', 'register_theme_menu' );
if (!function_exists('register_theme_menu')) {
	function register_theme_menu() {
		register_nav_menu( 'primary-menu', __( 'Primary Menu' ) );
		register_nav_menu( 'footer-menu', __( 'Footer Menu' ) );
	}
}



?>