<div id="masthead">
	<?php dynamicsidebar("Masthead Before", '<div id="mastheadbefore">', '</div>'); ?>
	<div id="mastheaditems">
	
	<?php wp_nav_menu( 
		array( 
			'theme_location' => 'primary-menu',
			'container' => 'div',
			'container_id' => 'sitenav',
			'menu_class' => 'sf-menu',
			'menu_id' => 'primary-menu'
		) 
	); ?>

	
	<div id="mastline">
		<h1><a href="<?php echo home_url(); ?>"><span><?php bloginfo('name'); ?></span></a></h1>
		<p class="description">
		<span class="thedate"><?php echo date('l, F jS, Y'); ?>  | </span> 
		<?php bloginfo('description'); ?> 
		</p>
	</div>
	
	</div>
	<?php dynamicsidebar("Masthead After", '<div id="mastheadafter">', '</div>'); ?>
	
	
</div>