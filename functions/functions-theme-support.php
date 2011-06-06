<?php
/****
 * functions/functions-theme-support.php
 *
 * Contains the implementation of Wordpress add_theme_support() function
 */


/**
 * Enable Post Thumbnail Support
 */
add_theme_support('post-thumbnails');


/**
 * Enable Automatic Feed Links
 */
add_theme_support('automatic-feed-links');


/**
 * DEFAULT SETUP POST FORMATS
 */
add_theme_support('post-formats', array(
                                       'aside',
                                       'chat',
                                       'gallery',
                                       'image',
                                       'link',
                                       'quote',
                                       'status',
                                       'video',
                                       'audio')
);





?>