	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class('item'); ?>>	

				<?php edit_post_link('Edit this','<div class="editlink"><span>','</span></div>'); ?>	
			
				<?php get_template_part( 'itemhead', 'simple'); ?>	

				<div class="itemtext">
					<?php 
						if ( is_archive() or is_search() ) {
							the_excerpt();
						} else { 
							show_mediagalleries();
							the_content("Continue reading...");
						}				
					?>			
				</div>
			</div>
					
		<?php endwhile; ?>		

		<!-- NAVIGATION -->
		<?php get_template_part( 'navigate', 'simple' ); ?>

	<?php else : ?>
			<div class="item">
				<?php get_template_part( 'nothing' ); ?>
			</div>
	<?php endif; ?>