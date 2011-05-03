<?php
/**************************************************************
 CURRENT THE FRAMEWORK SUPPORTS TWO FONT METHODS
 
	1)	FONTFACE 
	2) CUFON
	
**************************************************************/

/**************************************************************
 LOAD CUFON FONTS
**************************************************************/
if (!function_exists('load_cufonfonts')) {
	function load_cufonfonts() {
	   # echo "\n<!-- Cufon Fonts -->";
	   # echo "\n<script type='text/javascript' src='".get_stylesheet_directory_uri()."/fonts/museo.font.js'></script>";	
	   # echo "\n<script type='text/javascript' src='".get_stylesheet_directory_uri()."/fonts/league_gothic.js'></script>";						
	}
}
add_action("wp_head","load_cufonfonts", 20);


/**************************************************************
 LOAD FONTFACE
**************************************************************/
if (!function_exists('load_fontface')) {
	function load_fontface() {
		# echo "\n<!-- Cufon Fonts -->";	
		# echo "\n<link rel='stylesheet' type='text/css' href='".get_stylesheet_directory_uri()."/fonts/fontface/nobile/stylesheet.css'/>";
		# echo "\n<link rel='stylesheet' type='text/css' href='".get_stylesheet_directory_uri()."/fonts/fontface/titillium/stylesheet.css'/>";
	}
}
add_action("wp_head","load_fontface", 20);








?>