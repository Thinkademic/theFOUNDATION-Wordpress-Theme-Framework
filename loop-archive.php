<?php thefdt_loop_header(); ?>
				
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class('item'); ?>>

				<?php get_template_part( 'itemhead', 'archive'); ?>	
				<div class="itemtext">
					<?php
						show_mediagalleries();
						the_content("Continue reading...");
						wp_link_pages();
					?>
				</div>
				<?php get_template_part( 'itemfoot', 'archive'); ?>					
				
			</div>
		<?php endwhile; ?>		

	<?php else : ?>
			<div class="item">
				<?php get_template_part( 'nothing' ); ?>
			</div>
	<?php endif; ?>
	
<?php thefdt_loop_footer(); ?>			