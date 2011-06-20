<?php
/****
 * functions/functions-sandbox.php
 *
 * CUSTOM POST TYPE FOR EVENTS
 *
 * Having a file to hastily add or edit functions can be helpful.
 * Especially if you are writing new code or pasting in
 * snippets you have found handy.  It's good to try it out
 * here, and if it works out then think about formatting
 * the code based on code style guidelines.
 *
 */


/**
 * SubScript and SupScript Buttons
 *
 * @REF ttp://wpsnipp.com/index.php/functions-php/add-subscript-and-supscript-buttons-to-editor/
 */
function enable_more_buttons($buttons) {
  $buttons[] = 'sub';
  $buttons[] = 'sup';
  return $buttons;
}
add_filter("mce_buttons_3", "enable_more_buttons");

/**
 * ADD DROPDOWN BOX FOR SHORTCODES
 *
 * @REF http://wpsnipp.com/index.php/functions-php/add-custom-media_buttons-for-shortcode-selection/
 */
add_action('media_buttons', 'add_sc_select', 11);
function add_sc_select()
{
    global $shortcode_tags;
    /* ------------------------------------- */
    /* enter names of shortcode to exclude bellow */
    /* ------------------------------------- */
    $exclude = array("wp_caption", "embed");
    echo '&nbsp;<select id="sc_select"><option>Shortcode</option>';
    foreach ($shortcode_tags as $key => $val) {
        if (!in_array($key, $exclude)) {
            $shortcodes_list .= '<option value="[' . $key . '][/' . $key . ']">' . $key . '</option>';
        }
    }
    echo $shortcodes_list;
    echo '</select>';
}

add_action('admin_head', 'button_js');
function button_js()
{
    echo '<script type="text/javascript">
	jQuery(document).ready(function(){
	   jQuery("#sc_select").change(function() {
			  send_to_editor(jQuery("#sc_select :selected").val());
        		  return false;
		});
	});
	</script>';
}


/**
 * GOOGLE DOC SHORT CODE VIEWER
 *
 * @REF http://wpsnipp.com/index.php/functions-php/new-google-docs-shortcode-for-psd-ai-svg-and-more/
 *
 * @param $atts
 * @param null $content
 * @return string
 */
function wps_viewer($atts, $content = null) {
	extract(shortcode_atts(array(
		"href" => 'http://',
		"class" => ''
	), $atts));
	return '<a href="http://docs.google.com/viewer?url='.$href.'" class="'.$class.' icon">'.$content.'</a>';
}
add_shortcode("doc", "wps_viewer");




/**
 * Move Author Meta Box into Publish Meta Box
 *
 * @ref http://wpsnipp.com/index.php/functions-php/move-author-metabox-options-to-publish-post-metabox/
 */
add_action('admin_menu', 'remove_author_metabox');
add_action('post_submitbox_misc_actions', 'move_author_to_publish_metabox');
function remove_author_metabox()
{
    remove_meta_box('authordiv', 'post', 'normal');
}

function move_author_to_publish_metabox()
{
    global $post_ID;
    $post = get_post($post_ID);
    echo '<div id="author" class="misc-pub-section" style="border-top-style:solid; border-top-width:1px; border-top-color:#EEEEEE; border-bottom-width:0px;">Author: ';
    post_author_meta_box($post);
    echo '</div>';
}


/**
 * Redirect Category Template to Single Post Template if only one entry
 *
 * @Ref http://wpsnipp.com/index.php/cat/redirect-to-a-single-post-if-one-post-in-category-or-tag/
 * @return void
 */
function redirect_to_post()
{
    global $wp_query;
    if (is_archive() && $wp_query->post_count == 1) {
        the_post();
        $post_url = get_permalink();
        wp_redirect($post_url);
    }
}

add_action('template_redirect', 'redirect_to_post');


/**
 * ADD NO FOLLOW TO THE CONTENT AND EXCERPT
 *
 * @LINK http://wpsnipp.com/index.php/functions-php/nofollow-external-links-only-the_content-and-the_excerpt/
 */
function my_nofollow($content)
{
    return preg_replace_callback('/<a[^>]+/', 'my_nofollow_callback', $content);
}

function my_nofollow_callback($matches)
{
    $link = $matches[0];
    $site_link = get_bloginfo('url');
    if (strpos($link, 'rel') === false) {
        $link = preg_replace("%(href=\S(?!$site_link))%i", 'rel="nofollow" $1', $link);
    } elseif (preg_match("%href=\S(?!$site_link)%i", $link)) {
        $link = preg_replace('/rel=\S(?!nofollow)\S*/i', 'rel="nofollow"', $link);
    }
    return $link;
}

add_filter('the_content', 'my_nofollow');
add_filter('the_excerpt', 'my_nofollow');

/**
 * HOME MENU FALL BACK
 *
 * @LINK http://www.wprecipes.com/how-to-show-the-home-link-on-wp_nav_menu-default-fallback
 *
 * @param $args
 * @return
 */
function homelink_for_menufallback($args)
{
    $args['show_home'] = true;
    return $args;
}

add_filter('wp_page_menu_args', 'homelink_for_menufallback');


/**
 * ADD SEARCH BOX TO ADMIN BAR
 *
 * @REF http://www.wprecipes.com/how-to-automatically-add-a-search-field-to-your-navigation-menu
 *
 * @param $items
 * @param $args
 * @return string
 */
function add_search_box($items, $args)
{
    $searchform = get_search_form(false);
    $items .= '<li>' . $searchform . '</li>';

    return $items;
}
#add_filter('wp_nav_menu_items','add_search_box', 10, 2);




/**
 * ADD BROWSER DETECTION TO BODY CLASS FUNCTION
 *
 * @REF http://www.nathanrice.net/blog/browser-detection-and-the-body_class-function/
 */
function browser_body_class($classes)
{
    global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

    if ($is_lynx) $classes[] = 'lynx';
    elseif ($is_gecko) $classes[] = 'gecko';
    elseif ($is_opera) $classes[] = 'opera';
    elseif ($is_NS4) $classes[] = 'ns4';
    elseif ($is_safari) $classes[] = 'safari';
    elseif ($is_chrome) $classes[] = 'chrome';
    elseif ($is_IE) $classes[] = 'ie';
    else $classes[] = 'unknown';

    if ($is_iphone) $classes[] = 'iphone';
    return $classes;
}
//	add_filter('body_class','browser_body_class');


/**
 * CUSTOM TEMPLATE TAG FOR SIDEBARS
 *
 * @LINK http://codex.wordpress.org/Function_Reference/get_template_part
 * 
 * @param $sidebar_name
 * @param string $before
 * @param string $after
 */
function dynamicsidebar($sidebar_name, $before = '', $after = '')
{
    if (function_exists('dynamic_sidebar')) {
        echo $before;
        dynamic_sidebar($sidebar_name);
        echo $after;
    }

}


/***********************************************************
MOST WORDPRESS USERS ARE USING CUSTOM FIELDS TO DISPLAY
THUMBS ON THEIR BLOG HOMEPAGE. IT IS A GOOD IDEA, BUT DO
YOU KNOW THAT WITH A SIMPLE PHP FUNCTION, YOU CAN GRAB
THE FIRST IMAGE FROM THE POST, AND DISPLAY IT.
 ***********************************************************/
function find_img_inside_postcontent()
{
    global $post, $posts;

    $first_img = '';
    ob_start();
    ob_end_clean();
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    $first_img = $matches [1] [0];

    if (empty($first_img)) { //Defines a default image
        $first_img = "/images/default.jpg";
    }
    return $first_img;
}


/************************************************************************
FUNCTIONS FOR IMAGE PREVIOUS AND IMAGE NEXT
MAKE SURE IT FACTORS IN THE FIRST AND LAST IMAGE
 *************************************************************************/
function ps_previous_image_link($f)
{
    $i = ps_adjacent_image_link(true);
    if ($i) {
        echo str_replace("%link", $i, $f);
    }
}

function ps_next_image_link($f)
{
    $i = ps_adjacent_image_link(false);
    if ($i) {
        echo str_replace("%link", $i, $f);
    }
}

function ps_adjacent_image_link($prev = true)
{
    global $post;
    $post = get_post($post);
    $attachments = array_values(get_children(Array('post_parent' => $post->post_parent,
                                                  'post_type' => 'attachment',
                                                  'post_mime_type' => 'image',
                                                  'order' => 'ASC',
                                                  'orderby' => 'menu_order ASC, ID ASC')));

    foreach ($attachments as $k => $attachment) {
        if ($attachment->ID == $post->ID) {
            break;
        }
    }

    $k = $prev ? $k - 1 : $k + 1;

    if (isset($attachments[$k])) {
        return wp_get_attachment_link($attachments[$k]->ID, 'thumbnail', true);
    }
    else {
        return false;
    }
}

/************************************************************************
THIS CODE ADDS A NEW COLUMN TO THE MEDIA LIBRARY PAGE ALLOWING YOU TO RE-ATTACH IMAGES
 *************************************************************************/
add_filter("manage_upload_columns", 'upload_columns');
add_action("manage_media_custom_column", 'media_custom_columns', 0, 2);
function upload_columns($columns)
{
    unset($columns['parent']);
    $columns['better_parent'] = "Parent";
    return $columns;
}

function media_custom_columns($column_name, $id)
{
    $post = get_post($id);
    if ($column_name != 'better_parent')
        return;
    if ($post->post_parent > 0) {
        if (get_post($post->post_parent)) {
            $title = _draft_or_post_title($post->post_parent);
        }
        ?>
    <strong><a
            href="<?php echo get_edit_post_link($post->post_parent); ?>"><?php echo $title ?></a></strong>, <?php echo get_the_time(__('Y/m/d')); ?>
    <br/>
    <a class="hide-if-no-js" onclick="findPosts.open('media[]','<?php echo $post->ID ?>');return false;"
       href="#the-list"><?php _e('Re-Attach', TEXTDOMAIN); ?></a>
    <?php

    } else {
        ?>
    <?php _e('(Unattached)'); ?><br/>
    <a class="hide-if-no-js" onclick="findPosts.open('media[]','<?php echo $post->ID ?>');return false;"
       href="#the-list"><?php _e('Attach', TEXTDOMAIN); ?></a>
    <?php

    }
}


/**************************************************************
[06] ADD CUSTOM POST TYPES TO THE 'RIGHT NOW' DASHBOARD WIDGET
http://wordpress.stackexchange.com/questions/1567/best-collection-of-code-for-your-functions-php-file
 **************************************************************/
function all_settings_link()
{
    add_theme_page(__('All Settings'), __('All Settings'), 'administrator', 'options.php');
}

add_action('admin_menu', 'all_settings_link');


/**************************************************************
CONSTRUCT XHTML TAGS USING A FUNCTION, $ARS IS A QUERY STRING
THAT WILL BE EXTRACTED.
 **************************************************************/
function itag($tag = "img", $content, $args = null, $precontent = "", $postcontent = "")
{

    if ($tag == "img" && $content != "") {

        $defaults = array(
            'id' => "",
            'class' => "",
            'title' => "",
            'alt' => "",
            'src' => ""
        );
        $args = wp_parse_args($args, $defaults);
        extract($args, EXTR_SKIP);

        $divxhmtl = "";

        if ($id != "") :
            $id = ' id="' . $id . '"';
        endif;

        if ($class != "") :
            $class = ' class="' . $class . '"';
        endif;

        if ($alt != "") :
            $alt = ' alt="' . $alt . '"';
        endif;

        if ($content != "") :
            $src = ' src="' . $content . '"';
        endif;

        $divxhtml = "\n" . '<' . $tag . $id . $class . $src . $alt . ' />';

        return $divxhtml;
    } else {
        return $content;
    }
}


function socialmedia_profiles()
{

    $profiles = array(

        array(
            'name' => "Facebook",
            'href' => "http://www.facebook.com/pages/Los-Angeles-CA/FOODSTORY/121934961176624",
            'hrefclass' => "facebook",
            'hreftitle' => "Join Our Facebook Page",
            'iconfolder' => "/images/followicons/",
            'iconfilename' => "facebook",
            'iconimgtype' => "png"),
        array(
            'name' => "Twitter",
            'href' => "http://www.twitter.com/foodstory_japan",
            'hrefclass' => "twitter",
            'hreftitle' => "Follow Us on Twitter",
            'iconfolder' => "/images/followicons/",
            'iconfilename' => "twitter",
            'iconimgtype' => "png"),
        array(
            'name' => "Youtube",
            'href' => "http://www.youtube.com/dailyFOODSTORY",
            'hrefclass' => "youtube",
            'hreftitle' => "Join our Youtube Channel",
            'iconfolder' => "/images/followicons/",
            'iconfilename' => "youtube",
            'iconimgtype' => "png")
    );

    echo get_socialmedia($profiles);

}

function get_socialmedia($profiles = null)
{

    if ($profiles) {
        $smedia .= "";

        foreach ($profiles as $key) {
            $smedia .= get_socialprofile($key);
        }

        $smedia = xtag("div", $smedia, "id=followme");
        $smedia = xtag("div", $smedia, "id=socialmedia");

        return $smedia;
    }
}


function get_socialprofile($args = null)
{

    $defaults = array(
        'name' => "",
        'href' => "",
        'hrefclass' => "",
        'hreftitle' => "",
        'iconfolder' => "/images/followicons/",
        'iconfilename' => "",
        'iconimgtype' => "png"
    );
    $args = wp_parse_args($args, $defaults);
    extract($args, EXTR_SKIP);


    $imgsrc = get_stylesheet_directory_uri() . $iconfolder . $iconfilename . "." . $iconimgtype;
    $hrefcontent = itag("img", $imgsrc, "alt=" . $hreftitle) . xtag("span", $name);
    $link = xtag("a", $hrefcontent, 'href=' . $href . '&class=' . $hrefclass . '&title=' . $hreftitle);
    $profile = xtag("div", $link, "class=socialprofile");


    $socialprofile = '
		<div class="socialprofile">
			<a class="' . $hrefclass . '" href="' . $href . '" title="' . $hreftitle . '">
			<img ' . $imgsrc . '/><span>' . $name . '</span></a>
		</div>
	';

    return $profile;

}


/************************************************************************
THE EXISTING THE_CATEGORY() FUNCTION HAS NO WAY OF TELLING HOW MANY
CATEGORIES THERE ARE, AND SO CAN'T DO SOMETHING FANCY LIKE INSERTING THE
WORD "AND" BETWEEN THE PENULTIMATE (SECOND-TO-LAST) AND ULTIMATE CATEGORIES.

EXAMPLE:
SINGLE CATEGORY: CATEGORY1
TWO CATEGORIES: CATEGORY1 AND CATEGORY 2
THREE CATEGORIES: CATEGORY1, CATEGORY2 AND CATEGORY3
FOUR CATEGORIES: CATEGORY 1, CATEGORY 2, CATEGORY 3 AND CATEGORY 4

REFERENCE ::
Link: http://txfx.net/2004/07/22/wordpress-conversational-categories/
 ************************************************************************/
function the_nice_category($normal_separator = ', ', $penultimate_separator = ' and ')
{
    echo  get_the_nice_category($normal_sperator, $penultimate_separator);
}

function get_the_nice_category($normal_separator = ', ', $penultimate_separator = ' and ')
{
    $categories = get_the_category();

    if (empty($categories)) {
        _e('Uncategorized');
        return;
    }

    $thelist = '';
    $i = 1;
    $n = count($categories);
    foreach ($categories as $category) {
        $category->cat_name = $category->cat_name;
        if (1 < $i && $i != $n) $thelist .= $normal_separator;
        if (1 < $i && $i == $n) $thelist .= $penultimate_separator;
        $thelist .= '<a href="' . get_category_link($category->cat_ID) . '" title="' . sprintf(__("View all posts in %s"), $category->cat_name) . '">' . $category->cat_name . '</a>';
        ++$i;
    }
    return apply_filters('the_category', $thelist, $normal_separator);
}


// WE USED THESE FUNTIONS IN THE HORIZONTAL SCROLL PAGE
function get_attachment_items($slip_content = null)
{
    global $wp_query, $post, $paged;

    $postmeta_gallery_field = get_post_meta($post->ID, THEMECUSTOMMETAKEY, true);
    $orderby = $postmeta_gallery_field["gallery_orderby"];

    $queryargs = array(
        //	'post_type' => 'portfolio',
        'posts_per_page' => 100,
        //	'post_status' => 'future'
        'post_parent' => $post->ID,
        "post_status" => "null",
        "post_type" => "attachment",
        "orderby" => $orderby,
        "order" => "ASC",
        "showposts" => "100",
        "post_mime_type" => "image/jpeg,image/gif,image/jpg,image/png"
    );

    $markup = array("entry_wrapper" => "portfolio_item",
                    "entry_image" => "portfolio_image",
                    "entry_content_wrapper" => "portfolio_content_wrapper",
                    "entry_content" => "portfolio_content"
    );

    $options = array(
        "type_of_content" => false, // SEE retrieve_content()
        "type_of_media" => "attachment", // SEE retrieve_media()
        "mediasize" => "medium",
        "hyperlink_target" => "linktoself",
        "hyperlink_enable" => true,
        "media_has_hyperlink" => "true",
        "image_after_title" => true,
        "title_format" => "notitle", // false = title will not be shown | 'a' = hyperlink will wrap title | 'tagname' = tagname will wrap title, <tagname>title</tagname>
        "wrapper_class_counter" => true,
        "filtername" => "buildmarkup_from_query_action"
    );

    // Use a filter to insert Postmeta data, Slips it in our custom query function
    // add_filter('buildmarkup_from_query_action', 'get_list_upcoming_events_filter');			// Use a filter to insert Postmeta data, Slips it in our custom query function


    $attachment_items = buildmarkup_from_query($queryargs, $options, $markup, true);

    if (isset($slip_content)) {
        foreach ($slip_content as $num => $content) { // LOOP THROUGH THE POST DATA
            $attachment_items = slip_array_element($attachment_items, $content, $num);
        }
    }

    foreach ($attachment_items as $item) { // Loop through the POST data
        $output .= $item;
    }

    return $output;
}


function slip_array_element($target, $slip_content, $slip_num)
{
    $counter = 0;

    foreach ($target as $item) { // Loop through the POST data
        $counter++;

        // Slip in Content Between
        if ($slip_num == $counter) {
            $output[] = $slip_content;
        }

        $output[] = $item;
    }

    return $output;
}


?>