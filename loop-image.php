<?php thefdt_loop_header(); ?>

	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>

            <?php echo wp_get_attachment_image( $post->ID, 'fullsize' ); ?>

		<?php endwhile; ?>

	<?php else : ?>
			<div class="item">
				<?php get_template_part( 'nothing' ); ?>
			</div>
	<?php endif; ?>

<?php thefdt_loop_footer(); ?>