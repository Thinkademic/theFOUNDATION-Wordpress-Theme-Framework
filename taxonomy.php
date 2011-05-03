<?php get_header(); ?>



<body <?php body_id(); ?> <?php body_class(); ?>>
<div id="wrapper">
<?php get_template_part('slide', 'top'); ?>

<div id="layout"><div id="taxonomy_template_default">

<!-- Masthead -->
<?php get_template_part( 'masthead'); ?>
	<!-- Content is King -->
	<div id="content">

		<div id="topfeatures">		
		</div>

		<?php 	
		global $query_string;
		query_posts($query_string . "&posts_per_page=1&order=ASC");
	//	echo $query_string;
	//	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	//	query_posts($query_string .'posts_per_page=4&paged='.$paged);
		?>

		<hr>
		<div id="collectionnav">
			<div class="collectiontitle">
				<?php $titleterm = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); ?>
				<h4 class="specialfont"><?php echo $titleterm->name; ?> Collection</h3>				
			</div>
			<div class="flipper">
				<?php wp_pagenavi(); ?>
			</div>
		</div>
		</hr>

		
		<div id="primary">

			<?php if (have_posts()) : ?>
				<?php while (have_posts()) : the_post(); ?>
					
					<div class="productinfo">
						<div class="stylenum">
							<?php echo retrieve_title( "span" ); ?>
							<ul>
								<li>STYLENUM: <?php echo strip_tags(get_the_term_list($post->ID, 'stylenum', '', ', ','')); ?></li>
								<li>SUG RETAIL: $<?php echo strip_tags(get_the_term_list($post->ID, 'cost', '', ', ','')); ?></li>
							</ul>
						</div>
					</div>
				
					<div class="productimages">
					

							<?php 
							
								$primaryimage = retrieve_media( "modelimage", "medium", false, 0 );
								$secondaryimage = retrieve_media( "modelimage", "medium", false, 1 );
						
								$primaryimage_lg = retrieve_media( "modelimage", "large", false, 0 );
								$secondaryimage_lg = retrieve_media( "modelimage", "large", false, 1 );						
														
								if($secondaryimage) {
									echo xtag( "div", $primaryimage, "class=supplemental" ); 
									
									echo '
									<div id="wrap">
										<div id="small">
											'.$secondaryimage.'
										</div>
										<div id="mover">
											<div id="overlay-thing"></div>
											<div id="large">
												'.$secondaryimage_lg.'
											</div>
										</div>
									</div>								
									';
	
									
								} else {
									
									echo '
									<div id="wrap">
										<div id="small">
											'.$primaryimage.'
										</div>
										<div id="mover">
											<div id="overlay-thing"></div>
											<div id="large">
												'.$primaryimage_lg.'
											</div>
										</div>
									</div>								
									';

									
								}
							?>


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
		
			<div id="collectionlist">
				<span>View Other Collections:</span>
				<?php 
					$thecollections = get_terms( 'collections' ); 
				#	print_r($thecollections);
					
				$counter = 0;
				foreach ($thecollections as $item) {
					if($counter > 0) {
						echo ", ";
					}
					$hreflink = "http://mytallulah.com/collections/".$item->slug;
					echo xtag( "a", $item->name, "href=".$hreflink );
					$counter++;
				}	
				
				?>
				<?php #echo get_the_term_list( $post->ID, 'collections', '', ', ', '' ); ?>
			</div>
		
		
					<?php #print_r( get_defined_vars() ); ?>
		<!--
		<div id="entirecollection">
			<h3>View Other Pieces in Collection</h3>
			<?php #echo get_product_items(); ?>
		</div>
		-->
		
		</div>
		
	</div>

</div></div>

<!-- Footer -->
<?php get_footer(); ?>


<div id="bottomslide"></div>
</div>
<?php wp_footer(); ?>
</body>
</html>
