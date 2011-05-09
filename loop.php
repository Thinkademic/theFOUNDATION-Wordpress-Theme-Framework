<?php
/*
*	THE MOST BASIC LOOP
*	THE DEFAULT TEMPLATE IS INDEX.PHP, WHICH USES 'loop-index.php'
*	THIS LOOP WILL MOST LIKELY NEVER BE USED UNLESS SPECIFICALLY CALLED
*	BY A PAGE TEMPLATE. :: get_tempalte_part("loop");
*/
if (have_posts()) : 
		while (have_posts()) : the_post(); 
				the_content("Continue reading...");
		endwhile; 
endif; 					
?>