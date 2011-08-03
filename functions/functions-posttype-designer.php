<?php
/****
 * functions/functions-posttype-designer.php
 *
 * CUSTOM POST TYPE FOR A LIST OF DESIGNERS
 */


/**
 * APPEND POST TYPE WITH CUSTOM FIELDS FROM IN POST_META
 * INFO, ASSOCIATED TAXONOMIES AND RELEVANT MEDIA.
 */
add_action('the_content', 'append_designer_posttype');
function append_designer_posttype($content)
{
    global $wp;

    if (get_post_type() == 'event') {
        $designerpost = xtag("div", get_the_content(), "id=designer-description");
        $designerpost .= retrieve_single_designer_details();
        $designerpost .= get_jcyclegallery("imagesize=medium");
        $designerpost = xtag("div", $designerpost, "id=designerpost");

        return $designerpost;
    } else {
        return $content;
    }
}


/**
 * TEMPLATE FUNCTIONS
 *
 * @param null $args
 */
function single_designer_details($args = null)
{
    echo retrieve_single_designer_details($args);
}


function retrieve_single_designer_details($args = null)
{
    global $wp_query, $post, $paged;

    # - FUNCTION DISPLAY OPTIONS
    $args_default = array(
        "show_link" => true
    );
    $args = wp_parse_args($args, $args_default);
    extract($args);

    # - ACCESS DATA FROM POST_META TABLE
    $post_meta = get_post_meta($post->ID, "foundation_designer", true);
    $link = $post_meta['designer_link'];

    # - ACCESS DATA FROM ASSOCIATED TAXONOMIES
    #$collection = retrieve_term_as_list('taxonomy=collection');

    # - WRAP DIVS + TITLES
    #$collection = xtag('div', $collection, 'class=collection', '<h4>Collection</h4>');


    # - BUILD AGG
    $xhtml = "
		$link
	";


    $xhtml = xtag("div", $xhtml, "class=designer-details");

    return $xhtml;
}


function retrieve_single_designer_details_filter()
{

    global $wp_query, $post, $paged;

    // Function options
    $args = array(
        "show_link" => true
    );

    $details .= retrieve_single_designer_details($args);
    return $details;
}


function archive_designer()
{
    echo retrieve_archive_designer();
}


function retrieve_archive_designer()
{
}


function get_designer_items()
{
    global $wp_query, $post, $paged;

    $queryargs = array(
        'post_type' => 'designer',
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
        "mediasize" => "squaremedium",
        "hyperlink_target" => "linktoparent",
        "hyperlink_enable" => false,
        "media_has_hyperlink" => false,
        "image_after_title" => false,
        "title_format" => "false", // false = title will not be shown | 'a' = hyperlink will wrap title | 'tagname' = tagname will wrap title, <tagname>title</tagname>
        "wrapper_class_counter" => false,
        "filtername" => "buildmarkup_from_query_action"
    );

    // Use a filter to insert Postmeta data, Slips it in our custom query function
    add_filter('buildmarkup_from_query_action', 'retrieve_single_designer_details_filter'); // Use a filter to insert Postmeta data, Slips it in our custom query function


    $portfoliio_items = buildmarkup_from_query($queryargs, $options, $markup);
    return $portfoliio_items;
}


/**
 * INITIATE OUR CUSTOM POSTTYPE CLASS
 */
add_action("init", "mantone_designer_init");
function mantone_designer_init()
{
    global $mantone_designer;
    $mantone_designer = new create_designer();
}


/**
 * CUSTOM POSTTYPE CLASS DEFINITION
 */
class create_designer
{
    // Build an array to store our custom fields data

    var $meta_key = "foundation_designer";

    var $meta_fields = array(
        "designer_link"
    );

    var $thelabel = "Designer";

    //////////// Class Definition
    function create_designer()
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
        register_post_type('designer', array(
                                            'labels' => $labels,
                                            'description' => 'A Designer for display.',
                                            'public' => true,
                                            'show_ui' => true, // UI in admin panel
                                            '_builtin' => false, // It's a custom post type, not built in
                                            '_edit_link' => 'post.php?post=%d',
                                            'capability_type' => 'page',
                                            'hierarchical' => false,
                                            'rewrite' => array("slug" => "designer"), // Permalinks
                                            'query_var' => "designer", // This goes to the WP_Query schema
                                            //'taxonomies' => array('category'), 				// defined elsewehre - see register_taxonomy_for_object_type()
                                            'menu_position' => 5, // (5) Below Post (10) Below Media (20) Below Pages
                                            'menu_icon' => get_stylesheet_directory_uri() . '/images/admin/member.png',
                                            'has_archive' => true,
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


        /*
          register_taxonomy(
              'stylenum', 							// Name of new taxonomy
              'designer', 							// The post type that will use this taxonomy
              array(
                  'hierarchical' => false,
                  'labels' => array(
                      'name' => 'Style Number',
                      'search_items' => 'Search Style Number',
                      'popular_items' => 'Popular Style Number',
                      'add_new_item' => 'Add new Style Number',
                      'all_items' => 'All Style Number',
                      'separate_items_with_commas' => 'Seperate Style Number with commas',
                      'choose_from_most_used' => 'Select from Style Number'
                  ) ,
                  'query_var' => true,
                  'rewrite' => true
                  )
              );


          register_taxonomy(
              'collection', 								// Name of new taxonomy
              'designer', 								// The post type that will use this taxonomy
              array(
                  'hierarchical' => true,
                  'labels' => array(
                      'name' => 'Collection',
                      'search_items' => 'Search Collection',
                      'popular_items' => 'Popular Collection',
                      'add_new_item' => 'Add new Collection name',
                      'all_items' => 'All Collections',
                      'separate_items_with_commas' => 'Seperate Collection with commas',
                      'choose_from_most_used' => 'Select from Collection'
                  ) ,
                  'query_var' => true,
                  'rewrite' => true
                  )
              );
          */


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

        // CUSTOM COLUMNS FOR PRODUCT ADMIN PAGE
        add_action("manage_posts_custom_column", array(&$this, "designer_custom_columns"), 10, 2);
        add_filter("manage_edit-designer_columns", array(&$this, "designer_edit_columns"));

        // CUSTOM META BOXES FOR THEME LAYOUT OPTIONS
        add_meta_box("thefoundation_designermeta",
                     "Additional Designer  Information",
                     array(&$this, "add_metafields_forpage"),
                     "designer",
                     "side",
                     "low"
        );


    }


    // INSERT NEW COLUMNS
    function designer_edit_columns($cols)
    {

        $new_columns = array(
            'designer_link' => __('Designer Link')
        );

        $cols = array_merge($cols, $new_columns);

        return $cols;
    }


    // PULL VALUES FOR NEW COLUMNS
    function designer_custom_columns($column, $post_id)
    {
        global $post;

        $postmeta_field = get_post_meta($post->ID, $this->meta_key, true);

        switch ($column) {
            case "designer_link":
                echo $postmeta_field["designer_link"];
                break;
        }
    }

    // Build the XHMTL boxes for our meta boxes, used by admin_init()
    function add_metafields_forpage()
    {
        global $post;
        $custom = get_post_custom($post->ID);
        $saved_meta_fields = get_post_meta($post->ID, $this->meta_key, true);

        // DESIGNER LINK
        $output .= "<p><strong>Link</strong></p>";
        $output .= form_textinput($this->meta_key, 'designer_link', '');


        echo $output;
    }


    // WHEN A POST IS INSERTED OR UPDATED
    function wp_insert_post($post_id, $post = null)
    {
        if ($post->post_type == "designer") {

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
            // To the Add and Edit Product Page
            // The Bulk Edit pages don't cary this form POST variable
            if (isset($_POST['post_ID'])) {
                $merged_data = wp_parse_args($new_data, $saved_data); // Merge default args with those passed on by the function call
                update_post_meta($post_id, $this->meta_key, $new_data);
            }

        }
    }


}


?>