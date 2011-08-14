<?php
/****
 * functions/functions-posttype-swatch.php
 *
 * CUSTOM POST TYPE FOR SWATCHES
 */


function get_swatch_items()
{
    global $wp_query, $post, $paged;

    $queryargs = array(
        'post_type' => 'swatch',
        'posts_per_page' => 100
        //'post_status' => 'future'
    );

    $markup = array("entry_wrapper" => "entry",
                    "entry_image" => "entry_image",
                    "entry_content_box" => "entry_content_box",
                    "entry_content" => "entry_content"
    );

    $options = array(
        "type_of_content" => "false", // false = content will not be shown | 'the_excerpt' = get_the_excerpt() | 'the_content' = get_the_content();
        "type_of_media" => "first", // false = image will not be shown | 'featured' = featured mediaattachment | everything else defaultd to the first image, bseed on image order
        "mediasize" => "thumbnail",
        "hyperlink_target" => "linktoself",
        "hyperlink_enable" => true,
        "media_has_hyperlink" => true,
        "image_after_title" => true,
        "title_format" => "a", // false = title will not be shown | 'a' = hyperlink will wrap title | 'tagname' = tagname will wrap title, <tagname>title</tagname>
        "wrapper_class_counter" => false,
        "filtername" => "buildmarkup_from_query_action"
    );

    // Use a filter to insert Postmeta data, Slips it in our custom query function
    // add_filter('buildmarkup_from_query_action', 'get_list_upcoming_events_filter');			// Use a filter to insert Postmeta data, Slips it in our custom query function


    $portfoliio_items = buildmarkup_from_query($queryargs, $options, $markup);
    return $portfoliio_items;
}


// Gets a list of the Taxonomy assiociated with the swatch posttype
function get_swatch_filter($term = 'grouping')
{
    global $wp_query, $post, $paged;

    $output = "\n" . '<ul id="portfolio-filter">';
    $output .= "\n" . '<li><a href="#all" title=""><span>All</span></a></li>';

    $groupname = get_terms($term, "orderby=none");

    foreach ($groupname as $item) {
        $output .= "\n" . '<li class="' . $item->slug . '"><a class="sortby" href="#' . $item->slug . '" title="" rel="' . $item->slug . '"><span>' . $item->name . '</span></a></li>';
    }
    $output .= "\n" . '</ul>';

    return $output;

}


function get_swatch_thumbs($atts = null)
{

    global $wp_query, $post, $paged, $post_count;


    $defaults = array(
        'querytype' => 'posttype',
        'post_type' => 'swatch',
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'filterterm' => 'family'
    );
    $atts = wp_parse_args($atts, $defaults);
    extract($atts, EXTR_SKIP);
    $query_args = build_query_array($atts);

    $temp = $wp_query;
    $wp_query = null;

    $wp_query = new WP_Query();
    $wp_query->query($query_args);

    $counter = 0;
    // Loop Through the results and produce XHMTL
    while ($wp_query->have_posts()) : $wp_query->the_post();


                                      $counter++;

        // Grab the tags, unlinked, with spaces as seperator
                                      $grabtags = get_the_terms($post->ID, $filterterm);
                                      $tags = "";
                                      if ($grabtags) {
                                          foreach ($grabtags as $thetags) {
                                              $tags .= " " . $thetags->slug;
                                          }
                                          $tags = strtolower(substr($tags, 1));
                                      }

                                      $listclass = getpagenamebyid($post->post_parent);
                                      $slidereturn .= "\n" . '<li id="num' . $counter . '" rel="' . $post->post_name . '" class="' . $tags . '">';
                                      $slidereturn .= "\n" . '<a href="' . get_permalink($post->ID) . '">';
                                      if ($counter < 0) :
                                          $slidereturn .= get_first_image($post->ID, "thumbnail"); // Change from thumbnail if you want to make it different
                                      else :
                                          $slidereturn .= get_first_image($post->ID);
                                      endif;
                                      $slidereturn .= "<span>" . $post->post_title . "</span>";
                                      $slidereturn .= "\n" . '</a>';
                                      $slidereturn .= "\n" . '</li>';

    endwhile;


    $wp_query = null;
    $wp_query = $temp;
    wp_reset_query();

    $slidereturn = xtag('ul', $slidereturn, "id=portfolio_preview_list");
    $slidereturn = xtag('div', $slidereturn, "id=portfolio_previewer");

    $slidereturn = xtag("div", get_swatch_filter($filterterm), "id=portfolio_preview_filter") . $slidereturn;

    return $slidereturn;


}


/**************************************************************
Finds all the Children, and Grabs all of their content
 **************************************************************/
function get_swatch_content($args = null)
{
    global $wp_query, $post, $paged;

    // Extract Media Settings
    $defaults = array(
        'imagesize' => 'large',
        'orderby' => 'menu_order',
        'enablethumbpreview' => false,
        'enablenextprev' => true
    );
    $args = wp_parse_args($args, $defaults);


    echo "\n" . '<div id="portfolio_page_post_content" class="current">';
    echo $post->post_content;
    echo '<hr/>';
    echo "\n" . '</div>';

    echo "\n" . '<div class="portfolio_content">';


    // [------------------------ Step 1/3 ]
    $temp = $wp_query;
    $wp_query = null;

    // Run a New Query, Adjust According if we decide to write a ShortCode Version of mediaLibrary function
    $wp_query = new WP_Query();
    $wp_query->query(array(
                          "post_type" => "swatch",
                          "orderby" => $orderby,
                          "order" => "ASC",
                          "showposts" => "100",
                     ));


    // Loop Through our Query
    while ($wp_query->have_posts()) : $wp_query->the_post();
                                      echo "\n" . '<div class="portfolio_item ' . $post->post_name . '">';

                                      echo extractMedia($args);
                                      echo '<div class="post_content">' . '<h3>' . $post->post_title . '</h3>' . wpautop($post->post_content) . '</div>';

                                      echo "\n" . '</div>';
    endwhile;


    // Restore to the regular Query
    $wp_query = null;
    $wp_query = $temp;

    echo "</div>";
    wp_reset_query();

}


// Initiate the plugin
add_action("init", "mantone_swatch_init");
function mantone_swatch_init()
{
    global $mantone_swatch;
    $mantone_swatch = new create_swatch();
}


class create_swatch
{
    // Build an array to store our custom fields data
    var $meta_key = "_fsl_swatchdata";

    var $meta_fields = array(
        "team_members",
    );

    var $thelabel = "Swatch";

    //////////// Class Definition
    function create_swatch()
    {

        $labelname = $this->thelabel;

        // Set the Labels for our new Custom Post Type
        $labels = array(
            'name' => _x($labelname . '', 'post type general name'),
            'singular_name' => _x($labelname, 'post type singular name'),
            'add_new' => _x('Add', $labelname),
            'add_new_item' => __("Add $labelname"),
            'edit_item' => __("Edit $labelname"),
            'edit' => _x('Edit', $labelname),
            'new_item' => __("New $labelname"),
            'view_item' => __("View $labelname"),
            'search_items' => __("Search $labelname"),
            'not_found' => __("No $labelname found"),
            'not_found_in_trash' => __("$labelname found in Trash"),
            'view' => __("View $labelname"),
            'parent' => ''
        );

        // See http://codex.wordpress.org/Function_Reference/register_post_type
        register_post_type('swatch', array(
                                          'labels' => $labels,
                                          'description' => 'Swatch',
                                          'public' => true,
                                          'show_ui' => true, // UI in admin panel
                                          '_builtin' => false, // It's a custom post type, not built in
                                          '_edit_link' => 'post.php?post=%d',
                                          'capability_type' => 'page',
                                          'hierarchical' => true,
                                          'rewrite' => array("slug" => "swatch"), // Permalinks
                                          'query_var' => "swatch", // This goes to the WP_Query schema
                                          //'taxonomies' => array('category'), 				// defined elsewehre - see register_taxonomy_for_object_type()
                                          'menu_position' => 5, // (5) Below Post (10) Below Media (20) Below Pages
                                          'menu_icon' => get_stylesheet_directory_uri() . '/images/admin/swatch.png',
                                          'supports' => array('title',
                                                              'editor',
                                              //'comments',
                                                              'thumbnail',
                                              //'custom-fields'	// Let's use custom fields for debugging purposes only
                                              //'trackbacks',
                                              //'revisions',
                                                              'author',
                                                              'excerpt',
                                                              'page-attributes'
                                          )
                                     )

        );

        register_taxonomy(
            'pigment', // Name of new taxonomy
            'swatch', // The post type that will use this taxonomy
            array(
                 'hierarchical' => false,
                 'labels' => array(
                     'name' => 'Pigment',
                     'search_items' => 'Search Pigment',
                     'popular_items' => 'Popular Pigment',
                     'add_new_item' => 'Add new Pigment',
                     'all_items' => 'All Colors',
                     'separate_items_with_commas' => 'Seperate Pigment with commas',
                     'choose_from_most_used' => 'Select from popular Pigments'
                 ),
                 'query_var' => true,
                 'rewrite' => true
            )
        );

        register_taxonomy(
            'family', // Name of new taxonomy
            'swatch', // The post type that will use this taxonomy
            array(
                 'hierarchical' => true,
                 'label' => 'Family',
                 'query_var' => true,
                 'rewrite' => true
            )
        );

        // Admin interface init
        add_action("admin_init", array(&$this, "admin_init"));
        // add_action("template_redirect", array(&$this, 'template_redirect'));	// No need to Redirect, single-posttypeslug.php

        // Insert post hook
        add_action("wp_insert_post", array(&$this, "wp_insert_post"), 10, 2);

    }


    //////////// Class Functions

    // Setup meta boxes
    function admin_init()
    {

        // Custom Columns for Swatch admin page, makes sure capability_type matches with proper
        // manage_posts_custom_column or manaage_pages_custom_column, or the overview page will not
        // fill out the data
        add_action("manage_pages_custom_column", array(&$this, "swatch_custom_columns"), 10, 2);
        add_filter("manage_edit-swatch_columns", array(&$this, "swatch_edit_columns"));

    }

    // Insert New Columns
    function swatch_edit_columns($cols)
    {

        $new_columns = array(
            'family' => __('Family'),
            'pigment' => __('Pigment')
        );

        $cols = array_merge($cols, $new_columns);

        return $cols;
    }


    // Pull Values for New Columns
    function swatch_custom_columns($column, $post_id)
    {
        global $post;

        switch ($column) {
            case "family":
                echo get_the_term_list($post->ID, 'family', '', ', ', '');
                break;
            case "pigment":
                echo get_the_term_list($post->ID, 'pigment', '', ', ', '');
                break;
        }
    }


    // When a post is inserted or updated
    function wp_insert_post($post_id, $post = null)
    {
        if ($post->post_type == "swatch") {

            $saved_data = get_post_meta($post_id, $this->meta_key, true);
            $new_data = array(); // Build an array to save in the meta...We are only going to use one wp_postmeta entry.

            // Loop through our fields, grab the equivalent $_POST value and build array to pass onto wp_postmeta entry
            foreach ($this->meta_fields as $key) { // Loop through the POST data

                if (isset($_POST[$key])) {
                    $value = @$_POST[$key];

                    if (!empty($value)) {
                        $new_data = array_merge($new_data, array($key => $value));
                    }

                }


            }

            // Can't find a better way to limit this
            // To the Add and Edit Swatch Page
            // The Bulk Edit pages don't cary this form POST variable
            if (isset($_POST['post_ID'])) {
                $merged_data = wp_parse_args($new_data, $saved_data); // Merge default args with those passed on by the function call
                update_post_meta($post_id, $this->meta_key, $new_data);
            }

        }
    }


}


?>