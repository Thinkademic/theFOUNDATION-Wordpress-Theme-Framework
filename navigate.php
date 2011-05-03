<?php 
/**
*	#http://codex.wordpress.org/Function_Reference/paginate_links
*/
if(true) {
	global $wp_rewrite;			
	$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;

	$pagination = array(
		'base' => @add_query_arg('paged','%#%'),
		'format' => '',
		'total' => $wp_query->max_num_pages,
		'current' => $current,
		'show_all' => true,
		'type' => 'plain',
		'prev_next' => true,
		'prev_text' => __('&laquo; Older'),
		'next_text' => __('Newer &raquo;'),
		'end_size' => 2,
		'mid_size' => 4,	
		);

		if( $wp_rewrite->using_permalinks() )
			$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg('s',get_pagenum_link(1) ) ) . 'page/%#%/', 'paged');

		if( !empty($wp_query->query_vars['s']) )
			$pagination['add_args'] = array('s'=>get_query_var('s'));

		echo xtag( 'div', paginate_links($pagination), 'class=postnav'); 		

}

?>
