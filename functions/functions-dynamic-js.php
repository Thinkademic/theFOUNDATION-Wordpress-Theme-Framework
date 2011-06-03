<?php
/****
 * functions/functions-dynamic-css.php
 *
 * A SERIES OF FUNCTIONS THAT ENABLES DYNAMIC INSERTION OF JS
 */


/**
 * ADD NEW QUERY VAR TO WORDPRESS INTERNAL
 * QUERY VAR LIST, WILL BE USED TO SETUP DYNAMIC JS PAGE
 */
add_filter('query_vars', 'add_new_var_to_wp_js');
function add_new_var_to_wp_js($public_query_vars)
{
    $public_query_vars[] = 'jqids';
    return $public_query_vars;
}


/**
 * REDIRECT TEMPLATE FILE WHENEVER QUERY VALUE IS PRESENT
 * TO A DYNAMICALLY GENERATRED FILE
 */
add_action('template_redirect', 'dynamic_js_display');
function dynamic_js_display()
{
    $jqids = get_query_var('jqids');

    if ($jqids != '') {
        #include_once (STYLESHEETPATH  . '/js/jquery.php');		// CHILDTHEMEFOLDER/js/jquery.php
        include_once (TEMPLATEPATH . '/js/jquery.php'); // PARENTTHEMEFOLDER/js/jquery.php
        exit;
    }
}


/**
 * ADD REWRITE RULES
 */
add_action('init', 'flush_rewrite_rules');
function custom_js_add_rewrite_rules($wp_rewrite)
{
    $new_rules = array(
        'custom/themeoptions/js/(.+?).js' => 'index.php?jqids=' . $wp_rewrite->preg_index(1), // Regex Match letters only
    );
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}

add_action('generate_rewrite_rules', 'custom_js_add_rewrite_rules');


/**
 * DYNAMICALLY ENQUEUE JS CONDITIONALLY
 */
add_action("wp_head", "add_dynamic_js", 21);
function add_dynamic_js()
{
    global $posts, $wp_scripts;

    $vers = '1.0'; // SET SCRIPT VERSION NUMBER
    do_action('fdt_enqueue_dynamic_js'); // DEFINE HOOK

    $permalinkon = !is_null(get_option('permalink_structure')) ? true
            : false; // DETERMINE IF FRIENDLY PERMALINKS ARE BEING USED

    #$permalinkon = false;


    $postid_string = "";
    foreach ($posts as $post) {
        $meta = get_post_meta($post->ID, THEMECUSTOMMETAKEY, true);

        if ($meta["gallery_type"] != "") :
            wp_enqueue_script($meta["gallery_type"]);
        endif;

        $postid_string .= $post->ID . "-";
    }

    // ENQUEUE JQUERY SCRIPTS FROM THEME OPTIONS SETTINGS
    $postid_string = substr($postid_string, 0, -1);


    if ($permalinkon) :
        wp_enqueue_script("custom", get_home_url() . '/custom/themeoptions/js/' . $postid_string . '.js', false, false, true);
    else :
        wp_enqueue_script("custom", get_home_url() . '/?jqids=' . $postid_string, false, $vers, true);
    endif;

    echo "\n<!-- JS GENERATED SCRIPTS -->\n";
    wp_print_scripts();

}


/*
 *	AUTO ENQUEUE JS FILES LOCATED IN FOLDER
 * 
 * @TODO format code
 * @TODO Improve security and Error handling
 */
function enqueue_js_from_folder()
{
    $js_folder = STYLESHEETPATH . '/js/load';

    if (is_dir($js_folder)) {
        if ($directory = opendir($js_folder)) {
            while (($file = readdir($directory)) !== false) {
                if (stristr($file, ".js") !== false) {
                    wp_register_script($file, get_stylesheet_directory_uri() . '/js/load/' . $file);
                    wp_enqueue_script($file);
                }
            }
        }
    }

}

add_action('fdt_enqueue_dynamic_js', 'enqueue_js_from_folder');
?>