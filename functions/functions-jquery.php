<?php
/****
 * functions/functions-jquery.php
 *
 * FUNCTIONS THAT INITIATE JQUERY SCRIPTS
 */


/**
 * INTIATE FRAMEWORK JQUERY SETUP
 */
if (!function_exists('init_jquery')) {
    function init_jquery()
    {
        add_action('init', 'init_jquery_google');
        #	add_action('init', 'init_jquery_local');
        add_action('init', 'register_jquery_plugins');
        add_action('template_redirect', 'enqueue_jquery_plugins');
    }
}
init_jquery();


/**
 * USE GOOGLE'S JQUERY SCRIPT
 *
 * @PLUGGABLE
 */
if (!function_exists('init_jquery_google')) {
    function init_jquery_google()
    {
        if (!is_admin()):
            wp_deregister_script('jquery');
            wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js', false, 1.4);
        endif;
    }
}


/**
 * USE LOCAL JQUERY SCRIPT
 */
if (!function_exists('init_jquery_local')) {
    function init_jquery_local()
    {
        if (!is_admin()):
            $src = get_stylesheet_directory_uri();
            wp_deregister_script('jquery');
            wp_register_script('jquery', $src . '/js/jquery142.min.js', false, 1.4);
        endif;
    }
}


/**
 * REGISTER SCRIPTS
 */
if (!function_exists('register_jquery_plugins')) {
    function register_jquery_plugins()
    {
        $src = get_stylesheet_directory_uri();

        wp_register_script('hoverintent', $src . "/js/jquery.hoverIntent.js", false, '5', false);
        wp_register_script('mousewheel', $src . "/js/jquery.mousewheel.js", false, '3.0.4', false);
        wp_register_script('easing', $src . "/js/jquery.easing.1.2.js", false, '1.1.2', false);

        wp_register_script('cufon', $src . "/js/cufon-yui.js", false, '1.09', false);

        wp_register_script('superfish', $src . "/js/superfish.js", false, '1.4.8', false);
        wp_register_script('supersubs', $src . "/js/supersubs.js", false, '0.2b', false);

        #	wp_register_script('crossslide', 	$src."/js/jquery.cross-slide.js", false, '0.3.3', false);
        wp_register_script('jcyclegallery', $src . "/js/jquery.cycle.all.js", false, '2.99', false);

        wp_register_script('filterable', $src . "/js/filterable.js", false, '', false);
        wp_register_script('scrollto', $src . "/js/jquery.scrollTo.js", false, '1.4.2', false);
        wp_register_script('localscroll', $src . "/js/jquery.localscroll.js", false, '1', false);
        wp_register_script('serialscroll', $src . "/js/jquery.serialScroll.js", false, '1.4.2', false);
        wp_register_script('smoothdiv', $src . "/js/jquery.smoothdivscroll.js", false, '0.8', false);

        wp_register_script('anythingslider', $src . "/js/jquery.anythingslider.js", false, '1.4', false);
        wp_register_script('anythingsliderfx', $src . "/js/jquery.anythingslider.fx.js", false, '1.4', false);
        wp_register_script('jscrollpane', $src . "/js/jquery.jscrollpane.min.js", false, '2.0', false);

        wp_register_script('fancytransitions', $src . "/js/jquery.fancytransitions.1.8.js", false, '1.8', false);
        wp_register_script('coinslider', $src . "/js/jquery.coinslider.min.js", false, '1.0', false);

        wp_register_script('orbit', $src . "/js/jquery.orbit.js", false, '1.1', false);

        wp_register_script('fancybox', $src . "/js/jquery.fancybox-1.3.1.js", false, '1.31', false);
        wp_register_script('qtip', $src . "/js/jquery.qtip.js", false, '1.0.0r3', false);

        wp_register_script('jqueryscripts', $src . "/js/jqueryscripts.js", false, '1', false);

    }
}


/**
 * FDT HELPER FUNCTION
 * RUN wp_enqueue_script WHEN $check is TRUE
 *
 * @param $scriptname
 * @param bool $check
 */
function use_wp_enqueue($scriptname, $check = false)
{
    if ($check)
        wp_enqueue_script($scriptname);
}


/**
 * FDT HELPER FUNCTION
 * RUN wp_enqueue_script WHEN $check is TRUE
 *
 * @param $scriptname
 * @param bool $check
 *
 */
function usagecheck_wp_enqueue($scriptname, $check = false)
{
    if ($check)
        wp_enqueue_script($scriptname);
}


?>