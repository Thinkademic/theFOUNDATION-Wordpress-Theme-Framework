<?php 
if (!is_search()) { $search_text = 'Search Site'; } 
else { $search_text = "$s"; }
?>
<div id='searchbox'>
<form method="get" id="searchform" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<ul>
	<li>
		<label for="search-text"><span>Search</span></label>
		<input type="text" class="swap_value" value="SEARCH" name="s" id="searchinput" />
		<input type="image" src="<?php echo get_stylesheet_directory_uri(); ?>/images/searchbutton/search.png" id="searchsubmit" alt="Go" />
		<!--<input type="submit" id="searchsubmit" value="GO" /> -->
	</li>
	</ul>
</form>
</div>
