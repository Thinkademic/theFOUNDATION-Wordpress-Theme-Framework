<?php
/****
 * functions/functions-theme-support.php
 *
 * Contains the implementation of Wordpress add_theme_support() function
 * @link http://codex.wordpress.org/Function_Reference/add_theme_support
 */


/**
 * Enable Post Thumbnail Support
 *
 * @note THIS FUNCTION SHOULD BE USED JUST ONCE, IF CALLED MORE THEN ONCE,
 * THEN THE LAST CALLED VERSION WILL BE APPLIED, BY DEFAULT IT WILL BE APPLIED ALL CUSTOM POST TYPES
 *
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