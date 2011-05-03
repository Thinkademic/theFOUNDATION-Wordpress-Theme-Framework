<?php
if (have_posts()) : 
		while (have_posts()) : the_post(); 
				the_content("Continue reading...");
		endwhile; 
endif; 					
?>