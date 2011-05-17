<?php
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