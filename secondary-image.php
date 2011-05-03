<div id="secondary">

	<div class="secondary-featured">
		<?php dynamicsidebar("Secondary Featured"); ?>
	</div>

		
	
	<!-- Image Attactment Navigation  -->
	<div id="imagenav">	
		<div class="left"><?php ps_previous_image_link( '<div>%link</div><span class="clearfix">Prev Image</span>' ) ?></div>
		<div class="right"><?php ps_next_image_link( '<div>%link</div><span class="clearfix">Next Image</span>' ) ?></div>
		<?php
			$theparent_title = get_the_title($post->post_parent);
			$theparent_permalink = get_permalink($post->post_parent);;
		?>
		<hr/>
		Image was posted in:<a class="backto" href="<?php echo $theparent_permalink; ?>">&quot;<?php echo $theparent_title; ?>&quot;</a>
		<hr/>
		<?php echo do_shortcode('[gallery id="'.$post->post_parent.'" size="thumbnail" columns="4"]'); ?>			
	</div>

	<?php comments_template(); ?>	
	
</div>