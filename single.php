<?php get_header(); ?>





<body <?php body_id(); ?> <?php body_class(); ?>>
<div id="wrapper">
<?php get_template_part( 'slide', 'top'); ?>	

<div id="layout"><div id="single">

<!-- Masthead -->
<?php get_template_part( 'masthead'); ?>

	<!-- Content is King -->
	<div id="content">

		<?php dynamicsidebar( 'Content Featured', '<div id="content-featured">', '</div>'); ?>
		
		<div id="primary">
			<?php get_template_part( 'navigate', 'single'); ?>		

			
			<?php get_template_part( 'loop', 'single'); ?>
			
			<div id="comments_section">
				<?php comments_template(); ?>
			</div>
			
		</div>
		

		<?php get_template_part( 'secondary', 'single'); ?>		
				
	</div>

</div></div>

<!-- Footer -->
<?php get_footer(); ?>

<?php get_template_part( 'slide', 'bottom'); ?>	
</div>
<?php wp_footer(); ?> 
</body>
</html>