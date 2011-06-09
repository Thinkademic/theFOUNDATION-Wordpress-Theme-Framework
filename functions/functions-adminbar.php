<?php
/****
 * functions/functions-admin-bar.php
 *
 * @ref http://codex.wordpress.org/Function_Reference/show_admin_bar
 *
 * PLACE CODE RELATED TO THE WORDPRESS ADMIN BAR IN THIS FILE
 */

/**
 * theFOUNDATION ADMIN BAR LINKS
 *
 * @PLUGGABLE
 */
if (!function_exists('fdt_admin_bar_menu')) {
	function fdt_admin_bar_menu() {
		global $wp_admin_bar;
		if ( !is_super_admin() || !is_admin_bar_showing() )
			return;

		$menu_id = 'fdt_custom_admin_menu';	
		$htmlstyle = 	'<span style="display: block; line-height: 1em; padding: 0px 10px 5px 15px; font-size: 12px; color: black;">';
			
		$wp_admin_bar->add_menu( array(
			'id' => $menu_id,
			'title' => __( 'theFOUNDATION :-)'),
			'href' => FALSE ) 
		);
		
		$wp_admin_bar->add_menu( array(
			'parent' => $menu_id,
			'title' => __( 'Learn About...'),
			'href' => 'learn.thefoundationthemes.com',
			"meta" => array(
					'html' => $htmlstyle.'theFOUNDATION framework</span>', 			#	HTML to go after the link.
					'target' => '_blank', 					#	link target
					'class' => 'fdt_admin_support' 	#	CSS class for the link
				)
			) 
		);
		
		$wp_admin_bar->add_menu( array(
			'parent' => $menu_id,
			'title' => __( 'Get Support...'),
			'href' => 'support.thefoundationthemes.com' ,
			"meta" => array(
					'html' => $htmlstyle.__( 'from theFOUNDATION community forum').'</span>', 			#	HTML to go after the link.
					'target' => '_blank', 					#	link target
					'class' => 'fdt_admin_support'		#	CSS class for the link
				)
			) 
		);	

		$wp_admin_bar->add_menu( array(
			'parent' => $menu_id,
			'title' => __( 'Explore...'),
			'href' => 'explore.thefoundationthemes.com ',			
			"meta" => array(
					'html' => $htmlstyle.__( 'theFOUNDATION approved child themes').'</span>', 			#	HTML to go after the link.
					'target' => '_blank', 					#	link target
					'class' => 'fdt_admin_support'		#	CSS class for the link
				)
			) 
		);	

		$wp_admin_bar->add_menu( array(
			'parent' => $menu_id,
			'title' => __( 'Request...'),
			'href' => 'request.thefoundationthemes.com' ,			
			"meta" => array(
					'html' => $htmlstyle.__( 'customization from a listed developer').'</span>', 			#	HTML to go after the link.
					'target' => '_blank', 					#	link target
					'class' => 'fdt_admin_support'		#	CSS class for the link
				)
			) 
		);	

		$wp_admin_bar->add_menu( array(
			'parent' => $menu_id,
			'title' => __( 'Report...'),
			'href' => 'report.thefoundationthemes.com' ,			
			"meta" => array(
					'html' => $htmlstyle.__( 'a bug that needs to be fixed').'</span>', 			#	HTML to go after the link.
					'target' => '_blank', 					#	link target
					'class' => 'fdt_admin_support'		#	CSS class for the link
				)
			) 
		);	
		
		$wp_admin_bar->add_menu( array(
			'parent' => $menu_id,
			'title' => __( 'Watch...'),
			'href' => 'watch.thefoundationthemes.com' ,			
			"meta" => array(
					'html' => $htmlstyle.__( 'theFOUNDATION Weekly').'</span>', 			#	HTML to go after the link.
					'target' => '_blank', 					#	link target
					'class' => 'fdt_admin_support'		#	CSS class for the link
				)
			) 
		);	
		
		$wp_admin_bar->add_menu( array(
			'parent' => $menu_id,
			'title' => __( 'Spread...'),
			'href' => 'spread.thefoundationthemes.com' ,			
			"meta" => array(
					'html' => $htmlstyle.__( 'your love for theFOUNDATION').'</span>', 			#	HTML to go after the link.
					'target' => '_blank', 					#	link target
					'class' => 'fdt_admin_support'		#	CSS class for the link
				)
			) 
		);	

		$wp_admin_bar->add_menu( array(
			'parent' => $menu_id,
			'title' => __( "Hide"),
			'href' => '#' ,			
			"meta" => array(
					'html' => $htmlstyle.__( 'this menu :-(').'</span>', 			#	HTML to go after the link.
					'target' => '_blank', 					#	link target
					'class' => 'fdt_admin_support'		#	CSS class for the link
				)
			) 
		);	
		
		
		
	}
}
add_action('admin_bar_menu', 'fdt_admin_bar_menu', 999);
?>