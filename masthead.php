<div id="masthead">
	<?php dynamicsidebar("Masthead Before", '<div id="mastheadbefore">', '</div>'); ?>

	<div id="mastheaditems">
		<?php 
		/* ************************************************** 
		http://codex.wordpress.org/Function_Reference/wp_nav_menu
		
		NAVIGATION MENU'S ARE DECLARED IN THE FUNCTIONS FOLDER
			wp-content/themes/thefoundation_child/functions/functions-appearance-menu.php
		
		By Default The Child Theme Template will create a 
		Theme Location Menu that matches the name of the
		Folder Name, that can be used for the masthead
		
		The paramter for wp_nav_menu() is can be a little tricky...
		
		the 'theme_location' paramter
			will apply the menu of choice as selected in the Menu Theme Location dropdown box, if none is selected it will default to the fuction 
			defined in the 'fallback_cb' paramater. 
			
		the 'menu' paramter
			will search for a menu by the same 'name/id/slug', it will use the first availiable menu if it nothing is found.  If nothing else found after that, 
			then it will use the function as defined in the fallback_cb 'paramter'.  However it will not apply the container parameter,
			and the classes and id associated with the container if 'fallback_cb' is used
		
		When the 'theme_location' and 'menu' paramter are both applied
			It will ignore the 'menu' parameter logic, and follow the 'theme_location' logic
		
		************************************************** */
		$themefoldername = get_stylesheet();        # USE THE THEMES' FOLDER NAME AS THE PREFIX FOR THE MENU LOCATION NAME
		$location = $themefoldername."-nav";		# THE LOCATION NAME, USE THIS NAME WITH THE wp_nav_menu() function
		wp_nav_menu( 
			array( 
				'theme_location'  => $location,     # DISPLAYS A CUSTOM SELECTED MENU FOR THE SPECIFICED LOCATION, SHOW EMPTY IF NOT IS SELECTED
				//'menu'          => $location,     # THE id, slug, name OF THE CUSTOM MENU AS DEFINED IN ADMIN > APPEARANCE > MENU.
				'container'       => 'div',         # CONTAINER TAG, NOT APPLIED WHEN 'fallback_cb' FUNCTION IS USED
				'container_id'    => $location,     # CONTAINTER ID, NOT APPLIED WHEN 'fallback_cb' FUNCTION IS USED
				'container_class' => 'sitenav',     # CONTAINTER CLASS, NOT APPLIED WHEN 'fallback_cb' FUNCTION IS USED
				'menu_id'         => '',                # UL ID
				'menu_class'      => 'masthead-menu',   # UL CLASS
				'fallback_cb'     => null,              # DOES NOT WORK WITH THE 'theme_location' parameter
				'depth'           => 0                  # DEPTH OF MENU, '0' VALUE MEANS ALL
			) 
		); 		
		
		wp_nav_menu( 
			array( 
				'theme_location'  => 'mastlinetop-nav',     # DISPLAYS A CUSTOM SELECTED MENU FOR THE SPECIFICED LOCATION, SHOW EMPTY IF NOT IS SELECTED
				'container'       => 'div',                 # CONTAINER TAG, NOT APPLIED WHEN 'fallback_cb' FUNCTION IS USED
				'container_id'    => 'mastlinetop-nav',     # CONTAINTER ID, NOT APPLIED WHEN 'fallback_cb' FUNCTION IS USED
				'container_class' => 'sitenav',             # CONTAINTER CLASS, NOT APPLIED WHEN 'fallback_cb' FUNCTION IS USED
				'menu_id'         => '',					# UL ID
				'menu_class'      => 'masthead-menu',       # UL CLASS
				'fallback_cb'     => null,                  # DOES NOT WORK WITH THE 'theme_location' parameter
				'depth'           => 0                      # DEPTH OF MENU, '0' VALUE MEANS ALL
			) 
		); 	
		?>

		<div id="mastline">
			<h1><a href="<?php echo home_url(); ?>"><span><?php bloginfo('name'); ?></span></a></h1>
			<p class="description">
				<span class="thedate"><?php echo date('l, F jS, Y'); ?></span>
				<span class="thedescription"><?php bloginfo('description'); ?></span> 
			</p>
		</div>
		
		<?php
			wp_nav_menu( 
				array( 
					'theme_location' => 'mastlinebottom-nav',    # DISPLAYS A CUSTOM SELECTED MENU FOR THE SPECIFICED LOCATION, SHOW EMPTY IF NOT IS SELECTED
					'container' => 'div',                        # CONTAINER TAG, NOT APPLIED WHEN 'fallback_cb' FUNCTION IS USED
					'container_id' => 'mastlinebottom-nav',      # CONTAINTER ID, NOT APPLIED WHEN 'fallback_cb' FUNCTION IS USED
					'container_class' => 'sitenav',              # CONTAINTER CLASS, NOT APPLIED WHEN 'fallback_cb' FUNCTION IS USED
					'menu_id' => '',                             # UL ID
					'menu_class' => 'masthead-menu',             # UL CLASS
					'fallback_cb'  => null,                      # DOES NOT WORK WITH THE 'theme_location' parameter
					'depth' => 0                                 # DEPTH OF MENU, '0' VALUE MEANS ALL
				) 
			); 	
		?>	
		
	</div>
	
	
	<?php dynamicsidebar("Masthead After", '<div id="mastheadafter">', '</div>'); ?>
</div>