<div class="itemhead">	
	<?php edit_post_link('Edit','<div class="editlink"><span>','</span></div>'); ?>				
	<h2>
		<a href="<?php the_permalink() ?>" rel="bookmark" title='<?php printf(__('Permanent Link to "%s"',TEXTDOMAIN), strip_tags(get_the_title())) ?>'><?php the_title(); ?></a>
	</h2>

	<?php thefdt_get_item_meta("head"); ?>
</div>

