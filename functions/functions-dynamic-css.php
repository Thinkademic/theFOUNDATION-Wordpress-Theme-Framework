<?php
/****
 * functions/functions-dynamic-css.php
 *
 * A SERIES OF FUNCTIONS THAT ENABLES DYNAMIC INSERTION OF CSS
 */


/**
 * ADD NEW QUERY VAR TO WORDPRESS INTERNAL
 * QUERY VAR LIST WILL BE USED TO SETUP DYNAMIC CSS PAGE
 */
add_filter('query_vars', 'add_new_var_to_wp');
function add_new_var_to_wp($public_query_vars)
{
    $public_query_vars[] = 'cssids';
    return $public_query_vars;
}


/**
 * REDIRECT TEMPLATE FILE WHENEVER QUERY VALUE IS PRESENT
 * TO A DYNAMICALLY GENERATED FILE
 */
add_action('template_redirect', 'dynamic_css_display');
function dynamic_css_display()
{
    $cssids = get_query_var('cssids');

    if ($cssids != '') {
        // include_once (STYLESHEETPATH  . '/css/style.php');
        include_once (TEMPLATEPATH . '/css/style.php'); // PARENTTHEMEFOLDER/js/jquery.php
        exit;
    }
}


/**
 * ADD CSS REWRITE RULLES
 */
add_action('init', 'flush_rewrite_rules'); // FLUSH RULES IF YOU ADD NEW REWRITE RULES
function custom_css_add_rewrite_rules($wp_rewrite)
{
    $new_rules = array(
        'custom/themeoptions/css/(.+?).css' => 'index.php?cssids=' . $wp_rewrite->preg_index(1), // Regex Match letters only
    );
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}

add_action('generate_rewrite_rules', 'custom_css_add_rewrite_rules');


/**
 * DYNAMICALLY ENQUEUE CSS CONDITIONALLY
 */
add_action("wp_head", "add_dynamic_css", 20);
function add_dynamic_css()
{
    global $posts, $wp_scripts;

	$rewrite_rules = $GLOBALS['wp_rewrite']->wp_rewrite_rules();

    $vers = '1.0'; // SET SCRIPT VERSION NUMBER
    do_action('fdt_enqueue_dynamic_css'); // DEFINE HOOK

    $permalinkon = !is_null(get_option('permalink_structure')) ? true : false; // DETERMINE IF PERMALINKS IS SET
    // WRITE CODE TO ACCOUNT FOR THE FOLLOWING CASES ::    1) using_index_permalinks()   2) using_mod_rewrite_permalinks   3) using_permalinks()

    $postid_string = "";
    foreach ($posts as $post) {
        $meta = get_post_meta($post->ID, THEMECUSTOMMETAKEY, true);

        $postid_string .= $post->ID . "-";
    }

    // ENQUEUE CSS FROM THEME OPTIONS
    $postid_string = substr($postid_string, 0, -1);
    if ($rewrite_rules) :
        wp_enqueue_style("custom", get_home_url() . '/custom/themeoptions/css/' . $postid_string . '.css', false, $vers, 'screen');
    else :
        wp_enqueue_style("custom", get_home_url() . '/?cssids=' . $postid_string, false, $vers, 'screen');
    endif;

    echo "\n" . '<!-- CSS Generated from Theme Options  -->' . "\n";
    wp_print_styles();
}


/*
* AUTO ENQUEUE CSS FILES LOCATED IN FOLDER
*
* @TODO Format code
* @TODO Improve security and Error handling
*/
function enqueue_css_from_folder()
{
    $js_folder = STYLESHEETPATH . '/css/load';

    if (is_dir($js_folder)) {
        if ($directory = opendir($js_folder)) {
            while (($file = readdir($directory)) !== false) {
                if (stristr($file, ".css") !== false) {
                    wp_register_style($file, get_stylesheet_directory_uri() . '/css/load/' . $file);
                    wp_enqueue_style($file);
                }
            }
        }
    }

}

add_action('fdt_enqueue_dynamic_css', 'enqueue_css_from_folder');

?>