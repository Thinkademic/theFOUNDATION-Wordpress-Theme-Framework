<?php
/****
 * functions/functions-posttype-event.php
 *
 * CUSTOM POST TYPE FOR EVENTS
 */

/**
 * APPEND EVENT POST TYPE WITH CUSTOM FIELDS FROM IN POST_META
 * INFO AND ASSOCIATED TAXONOMIES
 */
add_action('the_content', 'append_event_posttype'); // Auto Enter Event information on event posttype pages.
function append_event_posttype($content)
{
    global $wp;

    if (get_post_type() == 'event') {
        #if ($wp->query_vars["post_type"] == "event") {					// Appends Event information to custom posttype event
        $eventpost = get_the_event() . xtag("div", get_the_content(), "id=eventdescription");
        $eventpost = xtag("div", $eventpost, "id=eventpost");
        return $eventpost;
    } else {
        return $content;
    }
}


/**
 * Event Query Adjustment
 *
 * Alter Query so that we make sure we are gettin future events.
 * Work around for not being able to use post_status = 'future'
 * Because wordpress wont' make those visible to readers with out
 * proper roles
 * 
 * @param string $where
 * @return string
 */
function future_event_filter($where = '')
{
    $where .= " AND post_date > '" . date('Y-m-d', strtotime('0 days')) . "'";

    return $where;
}

function past_event_filter($where = '')
{
    $where .= " AND post_date < '" . date('Y-m-d', strtotime('0 days')) . "'";

    return $where;
}


/**
 * Work around for not being able to use post_status = 'future'
 * Because wordpress wont' make those visible to readers with
 * out proper permissions
 */
add_filter('wp_insert_post_data', 'edit_event_posttype', 600, 2);
function edit_event_posttype($data, $postarr)
{
    global $post;

    $posttypecheck = get_post_type($post);

    if ($posttypecheck == "event") {
        if ($data['post_status'] == 'future') {
            $data['post_status'] = 'publish'; // change the post from "Scheduled" to "Published"
        }
        return $data;
    }

    return $data;
}


/**
 * Template Functions
 */
function the_event()
{
    echo get_the_event();
}


/**
 * Grabs the Event
 */
function get_the_event($args = null)
{
    global $wp_query, $post, $paged;


    # - FUNCTION DISPLAY OPTIONS
    $args_default = array(
        "showdate" => true,
        "showtime" => true,
        "showtimediff" => true,
        "showaddress" => true,
        "showcontact" => true,
        "showcost" => true,
        "showmap" => true,
        "showlocation" => true,
        "titledate" => "When",
        "titleaddress" => "Where",
        "titlecontact" => "Contact",
        "titlecost" => "Ticket Info",
        "titlemarkuptag" => "h4"
    );
    $args = wp_parse_args($args, $args_default);
    extract($args);


    # - EVENT DATA IS STORED IN POST_META TABLE
    $eventdata = get_post_meta($post->ID, "_fsl_eventdata", true);


    # - SEE CLASS DEFINTION FOR VARIABLE NAMES
    extract($eventdata);


    # - BUILD DATE INFO XHTML
    if ($showtime) :
        if ($showdate) :
            $date_box = retrieve_datebox_markup(); // We are using post date instead
            $time .= xtag("li", $date_box, "class=eventdate");
        endif;

        if ($showtimediff) :
            $timespan = get_days_away();
            $time .= xtag("li", $timespan, "class=timediff");
        endif;

        $endingtime .= xtag("span", $timeend, "class=timeend", '<span class="timelabel">To: </span>');

        if ($endingtime) {
            $begintime .= xtag("span", $timestart, "class=timestart", '<span class="timelabel">From: </span>');
        } else {
            $begintime .= xtag("span", $timestart, "class=timestart", '<span class="timelabel">Starts at: </span>');
        }

        $time .= xtag("li", $begintime . $endingtime, "class=thetime", "");

        if ($time) :
            $time = xtag("ul", $time);
            $time = xtag($titlemarkuptag, $titledate) . $time;
            $time = xtag("div", $time, "class=event_when");
        endif;
    endif;


    # - BUILD ADDRESS INFO XHTML
    if ($showaddress) :
        $line1 .= xtag("span", $addressline1, "class=address1");
        $line1 .= xtag("span", $addressline2, "class=address2");
        $addy .= xtag("li", $line1, "class=streetaddress");

        $line2 .= xtag("span", $city, "class=city");
        $line2 .= xtag("span", $state, "class=state");
        $line2 .= xtag("span", $zip, "class=zip");
        $addy .= xtag("li", $line2, "class=citystatezip", "");

        if ($addy) :
            $addy = xtag("ul", $addy);
            $addy = xtag($titlemarkuptag, $titleaddress) . $addy;
            $addy = xtag("div", $addy, "class=event_where");
        endif;
    endif;


    # - BUILD COST INFO XHTML
    if ($showcost) :
        $cost .= xtag("li", $price, "", "Price: $");
        if ($pricelink):
            $pricelink = xtag("a", "Buy Ticket", "href=" . $pricelink);
            $cost .= xtag("li", $pricelink, "class=pricelink");
        endif;

        if ($cost) :
            $cost = xtag("ul", $cost);
            $cost = xtag($titlemarkuptag, $titlecost) . $cost;
            $cost = xtag("div", $cost, "class=event_cost");
        endif;
    endif;


    # - BUILD CONTACT INFO XHTML
    if ($showcontact) :
        $contact .= xtag("li", $phone1, "class=phone1");
        $contact .= xtag("li", $phone2, "class=phone2");
        $contact .= xtag("li", $web1, "class=web1");
        $contact .= xtag("li", $web2, "class=web2");

        if ($contact):
            $contact = xtag("ul", $contact);
            $contact = xtag($titlemarkuptag, $titlecontact) . $contact;
            $contact = xtag("div", $contact, "class=event_contact");
        endif;
    endif;


    # - INTERGRATE MAPPRESS
    # -	http://wordpress.org/extend/plugins/mappress-google-maps-for-wordpress
    if ($showmap) :
        $locationmap .= do_shortcode("[mappress]");
        $locationmap = xtag("div", $locationmap, "class=event_map");
    endif;


    # - COMBINE ALL DETAILS
    $eventdetails = "
		$time
		$addy
		$cost
		$contact
		$locationmap
	";


    # - ADD XHTML DIV WRAPPER CLASS
    $eventdetails = xtag("div", $eventdetails, "class=eventdetails");


    return $eventdetails;
}


function list_upcoming_events()
{
    echo get_upcoming_events();
}

function get_upcoming_events()
{
    add_filter('posts_where', 'future_event_filter');
    $events = get_list_of_events();
    remove_filter('posts_where', 'future_event_filter');

    return $events;
}

function list_previous_events()
{
    echo get_previous_events();
}

function get_previous_events()
{
    add_filter('posts_where', 'past_event_filter');
    $events = get_list_of_events();
    remove_filter('posts_where', 'past_event_filter');

    return $events;
}

function get_list_of_events()
{
    global $wp_query, $post, $paged;

    $queryargs = array(
        'post_type' => 'event',
        'posts_per_page' => 10,
        'orderby' => 'date'
        //'post_status' => 'future'
    );

    $markup = array("entry_wrapper" => "entry",
                    "entry_image" => "entry_image",
                    "entry_content_box" => "entry_content_box",
                    "entry_content" => "entry_content"
    );

    $options = array(
        "type_of_content" => "the_excerpt", // false = content will not be shown | 'the_excerpt' = get_the_excerpt() | 'the_content' = get_the_content();
        "type_of_media" => "first", // false = image will not be shown | 'featured' = featured mediaattachment | everything else defaultd to the first image, bseed on image order
        "mediasize" => "thumbnail",
        "hyperlink_target" => "linktoself",
        "hyperlink_enable" => true,
        "media_has_hyperlink" => false,
        "image_after_title" => true,
        "title_format" => "a", // false = title will not be shown | 'a' = hyperlink will wrap title | 'tagname' = tagname will wrap title, <tagname>title</tagname>
        "markup_wrapper_class_counter" => true,
        "filtername" => "buildmarkup_from_query_action"
    );

    // Use a filter to insert Postmeta data, Slips it in our custom query function
    add_filter('buildmarkup_from_query_action', 'get_list_upcoming_events_filter'); // Use a filter to insert Postmeta data, Slips it in our custom query function


    $upcoming_events = buildmarkup_from_query($queryargs, $options, $markup);
    return $upcoming_events;
}


// Slips into our query function our event postmeta data in a nicely displayed format
// Use this for our Events template page
function get_list_upcoming_events_filter()
{

    global $wp_query, $post, $paged;

    // Function options
    $eventdetail_settings = array(
        "showdate" => true,
        "showtime" => true,
        "showtimediff" => false,
        "showaddress" => true,
        "showcontact" => false,
        "showcost" => false,
        "showmap" => false,
        "showlocation" => true,
        "titledate" => "",
        "titleaddress" => "",
        "titlecontact" => "",
        "titlecost" => "",
        "titlemarkuptag" => ""
    );

    $eventdetails .= get_the_event($eventdetail_settings);

    return $eventdetails;
}


/**************************************************************

INITIATE OUR CUSTOM POSTTYPE CLASS

 **************************************************************/
add_action("init", "mantone_events_init");
function mantone_events_init()
{
    global $mantone_events;
    $mantone_events = new create_Events();
}


/**************************************************************

CUSTOM POSTTYPE CLASS DEFINITION

 **************************************************************/
class create_Events
{
    // Build an array to store our custom fields data

    var $meta_key = "_fsl_eventdata";

    var $meta_fields = array(
        "addressline1",
        "addressline2",
        "city",
        "state",
        "zip",
        "phone1",
        "phone2",
        "web1",
        "web2",
        "price",
        "pricelink",
        "datestart",
        "dateend",
        "timestart",
        "timeend"
    );

    var $thelabel = "Event";


    //////////// Class Definition
    function create_Events()
    {

        $labelname = $this->thelabel;

        // Set the Labels for our new Custom Post Type
        $labels = array(
            'name' => _x($labelname . 's', 'post type general name'),
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
        register_post_type('event', array(
                                         'labels' => $labels,
                                         'description' => 'An event is content that captures detials related to a planned happenning.',
                                         'public' => true,
                                         'show_ui' => true, // UI in admin panel
                                         '_builtin' => false, // It's a custom post type, not built in
                                         '_edit_link' => 'post.php?post=%d',
                                         'capability_type' => 'page',
                                         'hierarchical' => false,
                                         'rewrite' => array("slug" => "event"), // Permalinks
                                         'query_var' => "events", // This goes to the WP_Query schema
                                         // 'taxonomies' => array() 				// defined elsewehre - see register_taxonomy_for_object_type()
                                         'menu_position' => 5, // (5) Below Post (10) Below Media (20) Below Pages
                                         'menu_icon' => get_stylesheet_directory_uri() . '/images/admin/calendar.gif',
                                         'has_archive' => true,
                                         'supports' => array('title',
                                                             'editor',
                                                            //'comments',
                                                             'thumbnail',
                                                            //'custom-fields'	// Let's use custom fields for debugging purposes only
                                                            //'trackbacks',
                                                            //'revisions',
                                                             'author',
                                                             'excerpt'
                                                            //'page-attributes'
                                         )
                                    )
        );

        // Admin interface init
        add_action("admin_init", array(&$this, "admin_init"));
        //add_action("template_redirect", array(&$this, 'template_redirect'));	// No need to redirect since WP 3.0 allows for single-posttype.php


        // Register a new custom taxonomy
        // See http://codex.wordpress.org/Function_Reference/register_taxonomy
        /*
          register_taxonomy(
              "Places",
              array(	"event"),
              array(	"hierarchical" => true,
                      "label" => "Events Tax",
                      "singular_label" => "Events Tax",
                      "rewrite" => true
              )
          );
          */

        // Adds an already registered taxonomy to an (custom) object type
        // See http://codex.wordpress.org/Function_Reference/register_taxonomy_for_object_type
        // register_taxonomy_for_object_type('post_tag', 'event');

        // Insert post hook
        add_action("wp_insert_post", array(&$this, "wp_insert_post"), 10, 2);

    }


    //////////// Class Functions

    // Setup meta boxes
    function admin_init()
    {
        // Custom meta boxes for the edit podcast screen
        add_meta_box("fslmeta_metabox-location", "Location", array(&$this, "location_options"), "event", "normal", "low");
        add_meta_box("fslmeta_metabox-when", "Time", array(&$this, "date_options"), "event", "side", "low");
        add_meta_box("fslmeta_metabox-contact", "Contact & Info", array(&$this, "contact_options"), "event", "side", "low");
        add_meta_box("fslmeta_metabox-cost", "Cost", array(&$this, "cost_options"), "event", "side", "low");

    }

    // Build the XHMTL boxes for our meta boxes, used by admin_init()
    function cost_options()
    {
        echo form_textinput($this->meta_key, 'price', 'Cost: $ ');
        echo form_textinput($this->meta_key, 'pricelink', 'http://');

    }

    // Build the XHMTL boxes for our meta boxes, used by admin_init()
    function date_options()
    {
        // echo form_textinput( $this->meta_key, 'datestart', 'Start Date' );
        // echo form_textinput( $this->meta_key, 'dateend', 'End Date' );
        echo form_textinput($this->meta_key, 'timestart', 'Start Time');
        echo form_textinput($this->meta_key, 'timeend', 'End Time');
    }

    // Build the XHMTL boxes for our meta boxes, used by admin_init()
    function contact_options()
    {
        echo form_textinput($this->meta_key, 'phone1', 'Phone #1');
        echo form_textinput($this->meta_key, 'phone2', 'Phone #2');
        echo form_textinput($this->meta_key, 'web1', 'http://');
        echo form_textinput($this->meta_key, 'web2', 'http://');

    }

    // Build the XHMTL boxes for our meta boxes, used by admin_init()
    function location_options()
    {
        echo form_textinput($this->meta_key, 'addressline1', 'Address Line 1: ');
        echo form_textinput($this->meta_key, 'addressline2', 'Address Line 2: ');
        echo form_textinput($this->meta_key, 'city', 'City');
        echo form_textinput($this->meta_key, 'state', 'State');
        echo form_textinput($this->meta_key, 'zip', 'Zip Code');

    }

    // Checks for Custom Template
    function template_redirect()
    {
        global $wp;

        if ($wp->query_vars["post_type"] == "event") {
            if (have_posts()) {
                include(TEMPLATEPATH . '/event.php'); // Let's look for the property.php template file in the current theme
                die();
            } else {
                $wp_query->is_404 = true;
            }
        }
    }

    // When a post is inserted or updated
    function wp_insert_post($post_id, $post = null)
    {
        if ($post->post_type == "event") {

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
            // To the Add and Edit Event Page
            // The Bulk Edit pages don't cary this form POST variable
            if (isset($_POST['post_ID'])) {
                $merged_data = wp_parse_args($new_data, $saved_data); // Merge default args with those passed on by the function call
                update_post_meta($post_id, $this->meta_key, $new_data);
            }

        }
    }


}


?>