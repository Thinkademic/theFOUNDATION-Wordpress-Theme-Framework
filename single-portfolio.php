<?php get_header(); ?>





<body <?php body_id(); ?> <?php body_class(); ?>>
<div id="wrapper">


<div id="layout"><div id="single_portfolio">

<!-- Masthead -->
<?php #include(TEMPLATEPATH . '/masthead.php'); ?>

	<!-- Content is King -->
	<div id="content">

		<div id="primary">

		
			<?php if (have_posts()) : ?>
				<?php while (have_posts()) : the_post(); ?>
					<div id="post-<?php the_ID(); ?>" class="item">

						<div class="itemtext">
							<?php 					
							
								jcyclegallery("imagesize=large");

								the_content("Continue reading...");
							?>						
						</div>
					</div>
							
				<?php endwhile; ?>		

			<?php else : ?>
					<div class="item">
							<div class="itemhead">	
								<h3 class="center">We Searched but Found Nothing :-(</h3>
							</div>

							<div class="itemtext">
								<p>Perhaps you can try a different search term</p>
							</div>
					</div>
			<?php endif; ?>			
		
		
		</div>
		
	</div>

</div></div>




</div>
<?php wp_footer(); ?>
</body>
</html>