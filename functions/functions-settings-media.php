<?php
/****
 * functions/functions-settings-media.php
 *
 * SETUP MEDIA SIZES
 */


/**
 * SETS MEDIA SIZES IF NOT ALREADY SETUP
 */
if (!function_exists('thefdt_settings_media')) {
    function thefdt_settings_media()
    {
        global $pagenow;

        if (is_admin() && isset($_GET['activated']) && $pagenow == "themes.php") {
            update_option('thumbnail_size_w', '340');
            update_option('thumbnail_size_h', '225');
            update_option('thumbnail_crop', 1);

            update_option('medium_size_w', '580'); // ~ 16 to 9 :: 580 > 325 :: FOR LANDSCAPE
            update_option('medium_size_h', '380'); // ~  4 to 3  :: 280 > 380 :: FOR PORTRAITE

            update_option('large_size_w', '940'); // ~ 16 to 9 :: 940 > 530
            update_option('large_size_h', '520'); // ~ 4 to 3 :: 520 > 390

            #header( 'Location: '.admin_url().'admin.php?page=my_theme' ) ;			// RELOCATE PAGE AFTER ACTIVATION
        }

        #	SETUP DEFAULT CROP OPTIONS ON DEFAULT WORDPRESS MEDIA SIZES
        if (false === get_option("medium_crop"))
            add_option("medium_crop", "0");
        else
            update_option("medium_crop", "0");

        if (false === get_option("large_crop"))
            add_option("large_crop", "1");
        else
            update_option("large_crop", "1");

        #	ESTABLISH CUSTOM THUMBSIZE SIZES
        add_image_size("minithumbnail", 85, 50, true); // DIMENSION SIZE FOR 'MINITHUMBNAIL'
        add_image_size("minimedium", 130, 75, true); // DIMENSION SIZE FOR 'MINIMEDIUM'
        add_image_size("minilarge", 170, 95, true); // DIMENSION SIZE FOR 'MINILARGE'
        add_image_size("headerlogo", 180, 180, true); // DIMENSION SIZE FOR 'HEADERLOGO'
        #add_image_size( "squarethumbnail", 50, 50, true );		// DIMENSION SIZE FOR 'SQAURETHUMBNAIL'
        #add_image_size( "squaremedium", 80, 80, true );		// DIMENSION SIZE FOR 'SQUAREMEDIUM'
        #add_image_size( "squarelarge", 160, 160, true );			// DIMENSION SIZE FOR 'SQUARELARGE'
    }
}
thefdt_settings_media();
?>