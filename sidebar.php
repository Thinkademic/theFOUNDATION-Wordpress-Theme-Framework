<?php
	if (is_active_sidebar("sidebar-featured"))
		get_sidebar('featured');
	
	if ( is_active_sidebar("sidebar-primary") )	
		get_sidebar('primary');

	if (is_active_sidebar("sidebar-secondary"))		
		get_sidebar('secondary');
?>