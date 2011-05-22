<?php



/**************************************************************
 CHANGE ALL PARENT THEMES FOLDER NAME TO CHILD THEME'S 
 CURRENT DIRECTORY NAME
 
 [01-19-2011] CONSIDER DEPRECATING
**************************************************************/
#add_action('template_directory_uri', 'tboc_template_directory_uri');
function tboc_template_directory_uri($template) {
      $container_directory = "/".basename(dirname(__FILE__));
      $template = str_replace('/thefoundation',$container_directory,$template);
       return $template;
}




# 01.01-2011
# functions-media.php 
/**************************************************************
 GRAB MEDIA ELEMENTS FROM ALL POST/PAGES, AND PRODUCES
 A BASIC GALLERY.
 
 NOTES ::
 CONSIDER REMOVING THIS FUNCTION
**************************************************************/
function mediaLibrary() {
		global $wp_query, $post, $paged;


		// [------------------------ Step 1/3 ---]
		//
		// Store $wp_query in $temp var, some functions like 'next_posts_link()' look for $wp_query
		// We could have given it another name, but these functions would not work correctly
		// Make sure to return $temp var, back to $wp_query once we are done
		$temp = $wp_query;
		$wp_query= null;	

		// Run a New Query, Adjust According if we decide to write a ShortCode Version of mediaLibrary function
		$wp_query = new WP_Query();

		$wp_query->query(array(
			//"showposts"=>-1,
			"what_to_show"=>"posts",
			"post_status"=>"inherit",
			"post_type"=>"attachment",
			//"orderby" => "ID ASC, menu_order ASC",
			//"orderby"=>"parent , menu_order , ID ",
			"orderby"=>"parent DESC",
			"showposts"=>"15",
			"paged"=>$paged,
			"post_mime_type"=>"image/jpeg,image/gif,image/jpg,image/png"));


		// Spit out some Pagination
		if(function_exists('wp_page_numbers')) { wp_page_numbers(); }

		// Settings	are similar to [Gallery] ShortCode
		$size = 'thumbnail';
		$itemtag = 'dl';
		$icontag = 'dt';
		$captiontag = 'dd';
		$columns = 5;
		$itemwidth = $columns > 0 ? floor ( 100/$columns ) : 100;	

		// Start XHTML outout
		$output = "
			<!-- see gallery_shortcode() in wp-includes/media.php -->
			<div class='gallery'>";


		while ($wp_query->have_posts()) : $wp_query->the_post();	

			// Generate Image Gallery

				$link = wp_get_attachment_link( $ID, $size, true );

				// Borrowed from GalleryShortCode Plugin
				$output .= "<".$itemtag." class='gallery-item' style='width:".$itemwidth."%'>";
				$output .= "
					<".$icontag." class='gallery-icon'>
						".$link."
					</{$icontag}>";
				if ( $captiontag && trim ( $post->post_excerpt ) ) {
					$output .= "
						<".$captiontag." class='gallery-caption'>
						".$post->post_excerpt."
						</".$captiontag.">";
				}
				$output .= "</".$itemtag.">";
				if ( $columns > 0 && ++$i % $columns == 0 )
					$output .= '<br style="clear: both" />';					


		endwhile; 

		$output .= "
				<br style='clear: both;' />
			</div>\n";
		// $output .= get_next_posts_link();
		// $output .= get_previous_posts_link();	

		echo $output;

		$wp_query = null; $wp_query = $temp;

}



# 01.01-2011
# functions-media.php 
/**********************************************************************************
 WORDPRESS 2.7 PLACESS CSS FOR GALLERY INSIDE THE BODY, 
 REFER TO GALLERY-SHORTCODE-STYLE-TO-HEAD.PHP
 THIS FUNCTION WILL AUTO AUTOMATICALLY ADD SHORTCODE TO THE CONTENT, 
 AND THAT IN TURN WILL USE WORDPRESS NATIVE GALLERY FEATURE.
**********************************************************************************/
//add_filter('the_content', 'insertGallery');
function insertGallery($content) {
	global $post;

	$columns = 3;
	if(is_page()){
		$columns = 5;
	}

	if(!is_category() && !has_tag('feature photo') ) {
		return $content.do_shortcode('[gallery id="'.$post->ID.'" size="thumbnail" columns="'.$columns.'"]');
	} else {
		return $content;
	}
}





/**************************************************************
	TEMPLATE TAGS - COUNTS HOW MANY DAYS AWAY A POST IS
**************************************************************/
function get_days_away() {
	global $post;
	
	$days = time_since( time(), abs(strtotime($post->post_date_gmt . " GMT")));
	
	if($days) {
		$days .= " away";
	} else {
		$days = get_days_ago()." ago";
	}
	
	return $days;
}

/**************************************************************
	TEMPLATE TAGE - COUTNS HOW MANY DAYS SINCE POST
	WAS PUBLISHED
**************************************************************/
function get_days_ago() {
	global $post;

	$days = time_since( abs(strtotime($post->post_date_gmt . " GMT")));	
	
	return $days;
}


/**************************************************************
	HUMAN READABLE TIME SINCE FUNCTION
	CONSIDER USING INSTEAD
	http://codex.wordpress.org/Function_Reference/human_time_diff
**************************************************************/
function time_since($older_date, $newer_date = false) {

	#	ARRAY OF TIME PERIOD CHUNKS

	$chunks = array(
		array(60 * 60 * 24 * 365 , 'year'),
		array(60 * 60 * 24 * 30 , 'month'),
		array(60 * 60 * 24 * 7, 'week'),
		array(60 * 60 * 24 , 'day'),
		array(60 * 60 , 'hour'),
		array(60 , 'minute'),
	);
	
	# 	$newer_date WILL EQUAL FALSE IF WE WANT TO KNOW THE TIME ELAPSED BETWEEN A DATE AND THE CURRENT TIME
	# 	$newer_date WILL HAVE A VALUE IF WE WANT TO WORK OUT TIME ELAPSED BETWEEN TWO KNOWN DATES
	#	$newer_date = ($newer_date == false) ? (time()+(60*60*get_settings("gmt_offset"))) : $newer_date;
	$newer_date = $newer_date == false ? time() : $newer_date;

	#	DIFFERENCE IN SECONDS
	$since = $newer_date - $older_date;
	if($since < 0 ) {
		return false;
	}

	#	WE ONLY WANT TO OUTPUT TWO CHUNKS OF TIME HERE, EG:
	#	x years, xx months
	#	x days, xx hours
	#	SO THERE'S ONLY TWO BITS OF CALCULATION BELOW:

	#	step one: the first chunk
	for ($i = 0, $j = count($chunks); $i < $j; $i++) {
		$seconds = $chunks[$i][0];
		$name = $chunks[$i][1];

		#	finding the biggest chunk (if the chunk fits, break)
		if (($count = floor($since / $seconds)) != 0) {
			break;
			}
		}

	#	SET OUTPUT VAR
	$output = ($count == 1) ? '1 '.$name : "$count {$name}s";


	#	STEP TWO: THE SECOND CHUNK
	if ($i + 1 < $j) {
		$seconds2 = $chunks[$i + 1][0];
		$name2 = $chunks[$i + 1][1];

		if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0)	{

			// ADD TO OUTPUT VAR
			$output .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}s";
			}
	}

	return $output;

}



/**************************************************************
	CONSTRUCT XHTML TAGS USING A FUNCTION, $ARS IS A QUERY STRING
	THAT WILL BE EXTRACTED.
**************************************************************/
function xtag( $tag = "div", $content, $args = null, $precontent = "", $postcontent = "" ) {

	if($tag) {
	
	//	GET XHTML ELEMENT ATTRIBUTES
		$defaults = array(
			'id' => "",
			'class' => "",
			'href' => "",
			'title' => "",
			'rel' => "",			
		);
		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );
		
		
		if( $content == "" ) :
			return false;
		endif;

		$divxhmtl = "";
		if($id != "") :
			$id = ' id="'.$id.'"';
		endif;

		if($class != "") :
			$class = ' class="'.$class.'"';
		endif;
		
		if($href != "") :
			$href = ' href="'.$href.'"';
		endif;
		
		if($title != "") :
			$title = ' title="'.$title.'"';
		endif;

		if($rel != "") :
			$rel = ' rel="'.$rel.'"';
		endif;
		

		if($content != "") :
			$divxhtml  = "\n".'<'.$tag.$id.$class.$href.$title.$rel.'>';
			$divxhtml .= "\n\t".$precontent.$content.$postcontent;
			$divxhtml .= "\n"."</$tag>";
		endif;	
		
		
		return $divxhtml;
	} else {
		return $content;
	}
}








?>