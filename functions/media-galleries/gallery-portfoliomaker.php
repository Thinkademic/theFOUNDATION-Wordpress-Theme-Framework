<?php
/*
 *  functions/media-galleries/gallery-porfoliomaker.php
 *
 *  A Custom Media Gallery Solution
 */


/*
 * CREATE THE CSS FOR PORTFOLIO MAKER
 *
 * @param null $atts
 * @return
 *
 * @TODO PULL IN IMAGE SIZES DYNAMICALLY
 */
function build_css_portfoliomaker($atts = null)
{
    $thumbnail = 340;
    $medium = 580;
    $large = 940;

    if ($atts == null)
        return;

    extract($atts, EXTR_SKIP);

print <<<END
END;


}


/*
 * CSS JCYCLE FUNCTION
 */
function css_portfoliomaker()
{
    $atts = array(
        'width' => 100
    );

    build_css_portfoliomaker($atts);
}



/*
 *  REGISTER SCRIPTS FOR PORTFOLIO MAKER
 */
function portfoliomaker_register_script()
{
    $src = get_stylesheet_directory_uri();
    wp_register_script('filterable', $src . "/js/filterable.js", false, '', false);
    wp_register_script('fancybox', $src . "/js/jquery.fancybox-1.3.1.js", false, '1.31', false);
    wp_register_script('scrollto', $src . "/js/jquery.scrollTo.js", false, '1.4.2', false);
    wp_register_script('localscroll', $src . "/js/jquery.localscroll.js", false, '1', false);
    wp_register_script('serialscroll', $src . "/js/jquery.serialScroll.js", false, '1.4.2', false);
    wp_register_script('portfoliomaker', $src . "/js/jquery.portfoliomaker.js", array('jcyclegallery', 'filterable', 'scrollto', 'localscroll', 'serialscroll', 'fancybox'), '1', false);
      
    if (is_page_template('page_portfoliomaker.php') || is_page_template('page_portfoliomakermodal.php') || (is_single() && ('portfolio' == get_post_type()))) :
      wp_enqueue_script('portfoliomaker');
    endif;

}
add_action('template_redirect', 'portfoliomaker_register_script');


/*
 *  REGISTER STYLE FOR PORTFOLIO MAKER
 */
function portfoliomaker_register_style()
{
    wp_register_style('portfoliomaker', get_stylesheet_directory_uri() . '/css/' . 'porfoliomaker.css');
  
    if (is_page_template('page_portfoliomaker.php') || is_page_template('page_portfoliomakermodal.php') || (is_single() && ('portfolio' == get_post_type()))) :
      wp_enqueue_style('portfoliomaker');
    endif;

}
add_action('template_redirect', 'portfoliomaker_register_style');






/*
 * CSS JCYCLE FUNCTION
 */
if (is_page_template()){
    add_action('fdt_print_dynamic_css', 'css_portfoliomaker');
} else {

}
?>