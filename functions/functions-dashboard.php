<?php
/****
 * functions/functions-appearance-dashboard.php
 *
 * PLACE FUNCTIONS THAT RELATE TO WORDPRESS ADMIN'S DASHBOARD IN THIS FILE
 */

/**
 * DASHBOARD SETTINGS
 */
add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');
function my_custom_dashboard_widgets() {
	global $wp_meta_boxes;
   
	#unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	#unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	#unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);

	#unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);		 			//		Right Now - Comments, Posts, Pages at a glance
	#unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);		//		Recent Comments
	#unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);			//		Incoming Links
	#unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);						//		Plugins - Popular, New and Recently updated Wordpress Plugins

	#unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);							//		Wordpress Development Blog Feed
	#unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);						//		Other Wordpress News Feed	
						
	#unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);					//		Quick Press Form
	#unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']); 					//		Recent Drafts List	   
   
	wp_add_dashboard_widget('custom_help_widget', 'Help and Support', 'custom_dashboard_help');
}
 
function custom_dashboard_help() {
	echo '<p>You are currently using a Foundation Framework Theme, developed by Mantone</p>';
}

/************************************************************** 
 CUSTOMIZE ADMIN FOOTER TEXT
**************************************************************/
function custom_admin_footer($content) {
        echo $content.' | theFoundation Theme Framework';
} 
add_filter('admin_footer_text', 'custom_admin_footer');


/**************************************************************
 [06] ADD CUSTOM POST TYPES TO THE 'RIGHT NOW' DASHBOARD WIDGET
 http://wordpress.stackexchange.com/questions/1567/best-collection-of-code-for-your-functions-php-file
**************************************************************/
function wph_right_now_content_table_end() {

	$args = array(
		'public' => true ,
		'_builtin' => false
	);
	$output = 'object';
	$operator = 'and';

	$post_types = get_post_types( $args , $output , $operator );

	foreach( $post_types as $post_type ) {
	  $num_posts = wp_count_posts( $post_type->name );
	  $num = number_format_i18n( $num_posts->publish );
	  $text = _n( $post_type->labels->singular_name, $post_type->labels->name , intval( $num_posts->publish ) );
	  if ( current_user_can( 'edit_posts' ) ) {
	   $num = "<a href='edit.php?post_type=$post_type->name'>$num</a>";
	   $text = "<a href='edit.php?post_type=$post_type->name'>$text</a>";
	  }

        if( $post_type->name != 'optionsframework') :
         echo '<tr><td class="first b b-' . $post_type->name . '">' . $num . '</td>';
         echo '<td class="t ' . $post_type->name . '">' . $text . '</td></tr>';
        endif;
	}
 
 
	$taxonomies = get_taxonomies( $args , $output , $operator ); 
	 foreach( $taxonomies as $taxonomy ) {
		  $num_terms  = wp_count_terms( $taxonomy->name );
		  $num = number_format_i18n( $num_terms );
		  $text = _n( $taxonomy->labels->singular_name, $taxonomy->labels->name , intval( $num_terms ));
		  if ( current_user_can( 'manage_categories' ) ) {
			   $num = "<a href='edit-tags.php?taxonomy=$taxonomy->name'>$num</a>";
			   $text = "<a href='edit-tags.php?taxonomy=$taxonomy->name'>$text</a>";
		  }
		  echo '<tr><td class="first b b-' . $taxonomy->name . '">' . $num . '</td>';
		  echo '<td class="t ' . $taxonomy->name . '">' . $text . '</td></tr>';
	 }
}
add_action( 'right_now_content_table_end' , 'wph_right_now_content_table_end' );

?>