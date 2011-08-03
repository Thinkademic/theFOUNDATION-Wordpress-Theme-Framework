<div id="footbox">
	<div id="footer">
		<?php
		wp_nav_menu( 
			array( 
				'theme_location' => 'footertop-nav',				# DISPLAYS A CUSTOM SELECTED MENU FOR THE SPECIFICED LOCATION, SHOW EMPTY IF NOT IS SELECTED
				'container' => 'div',							# CONTAINER TAG, NOT APPLIED WHEN 'fallback_cb' FUNCTION IS USED
				'container_id' => 'footertop',					# CONTAINTER ID, NOT APPLIED WHEN 'fallback_cb' FUNCTION IS USED
				'container_class' => '',						# CONTAINTER CLASS, NOT APPLIED WHEN 'fallback_cb' FUNCTION IS USED				
				'menu_id' => 'footertop-nav',						# UL ID			
				'menu_class' => 'footer-menu',					# UL CLASS
				'fallback_cb'  => null,		# DOES NOT WORK WITH THE 'theme_location' parameter
				'depth' => 0										# DEPTH OF MENU, '0' VALUE MEANS ALL
			) 
		); 
		?>

        <div id="primary-footer">
            <div class="col1">
                <?php
                    // Footer Col 1
                    if ( !function_exists('dynamic_sidebar')	|| !dynamic_sidebar('Footer Column 1') ) :
                    endif;
                ?>
            </div>
        </div>

        <div id="secondary-footer">
            <div>
                <div class="col2">
                    <?php
                        // Footer Col 2
                        if ( !function_exists('dynamic_sidebar')	|| !dynamic_sidebar('Footer Column 2') ) :
                        endif;
                    ?>
                </div>
                <div class="col3">
                    <?php
                        // Footer Col 3
                        if ( !function_exists('dynamic_sidebar')	|| !dynamic_sidebar('Footer Column 3') ) :
                        endif;
                    ?>
                </div>
                <div class="col4">
                    <?php
                        // Footer Col 4
                        if ( !function_exists('dynamic_sidebar')	|| !dynamic_sidebar('Footer Column 4') ) :
                        endif;
                    ?>
                </div>
            </div>
        </div>
		
		<?php
		wp_nav_menu( 
			array( 
				'theme_location' => 'footerbottom-nav',				# DISPLAYS A CUSTOM SELECTED MENU FOR THE SPECIFICED LOCATION, SHOW EMPTY IF NOT IS SELECTED
				'container' => 'div',							# CONTAINER TAG, NOT APPLIED WHEN 'fallback_cb' FUNCTION IS USED
				'container_id' => 'footerbottom',					# CONTAINTER ID, NOT APPLIED WHEN 'fallback_cb' FUNCTION IS USED
				'container_class' => '',						# CONTAINTER CLASS, NOT APPLIED WHEN 'fallback_cb' FUNCTION IS USED				
				'menu_id' => 'footerbottom-nav',						# UL ID			
				'menu_class' => 'footer-menu',					# UL CLASS
				'fallback_cb'  => null,		# DOES NOT WORK WITH THE 'theme_location' parameter
				'depth' => 0										# DEPTH OF MENU, '0' VALUE MEANS ALL
			) 
		); 
		?>
	</div>
</div>
<!-- 
Debug Info
<?php echo get_num_queries(); ?> queries.
<?php timer_stop(1); ?> seconds.
-->