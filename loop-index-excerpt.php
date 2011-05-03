	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class('item'); ?>>

				<?php get_template_part( 'itemhead', 'index-excerpt'); ?>	

				<div class="itemtext">			
					<?php
						the_excerpt();
					?>
				</div>

			</div>
					
		<?php endwhile; ?>		

	<?php else : ?>
			<div class="item">
				<?php get_template_part( 'nothing' ); ?>
			</div>
	<?php endif; ?>