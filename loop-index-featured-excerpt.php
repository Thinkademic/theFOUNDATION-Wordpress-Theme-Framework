<?php thefdt_loop_header(); ?>

	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class('item'); ?>>

				<?php get_template_part( 'itemhead', 'index-featured-excerpt'); ?>	
				<div class="itemtext">			
					<?php
						if( $wp_query->current_post == 0 )
							the_content();
						else
							the_excerpt();
					?>
				</div>
				<?php get_template_part( 'itemfoot', 'index-featured-excerpt'); ?>	

			</div>
					
		<?php endwhile; ?>		

	<?php else : ?>
			<div class="item">
				<?php get_template_part( 'nothing' ); ?>
			</div>
	<?php endif; ?>
	
<?php thefdt_loop_footer(); ?>