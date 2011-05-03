	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class('item'); ?>>

				<?php get_template_part( 'itemhead', 'image' ); ?>	

				<div class="itemtext">
						<p class="attachment"><a href="<?php echo wp_get_attachment_url($post->ID); ?>"><?php echo wp_get_attachment_image( $post->ID, 'medium' ); ?></a></p>						
						<div class="image-caption"><?php if ( !empty($post->post_excerpt) ) the_excerpt(); // this is the "caption" ?></div>
				</div>
	
			</div>
					
		<?php endwhile; ?>		

	<?php else : ?>
			<div class="item">
				<?php get_template_part( 'nothing' ); ?>
			</div>
	<?php endif; ?>