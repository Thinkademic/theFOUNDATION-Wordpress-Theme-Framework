<?php get_header(); ?>





<body <?php body_id(); ?> <?php body_class(); ?>>
<div id="wrapper">
<?php get_template_part('slide', 'top'); ?>

<div id="layout"><div id="single_product">

<!-- Masthead -->
<?php get_template_part( 'masthead'); ?>

	<!-- Content is King -->
	<div id="content">

		<div id="topfeatures">	
		</div>

		<div id="primary">
			<?php get_template_part( 'loop', 'single'); ?>
		</div>
		
	</div>

</div></div>

<!-- Footer -->
<?php get_footer(); ?>

<?php get_template_part( 'slide', 'bottom'); ?>
</div>
<?php wp_footer(); ?>
</body>
</html>