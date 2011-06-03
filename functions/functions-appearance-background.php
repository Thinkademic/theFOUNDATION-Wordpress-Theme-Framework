<?php
/****
 * functions/functions-appearance-background.php
 *
 * Place functions that relate to a website's background image in this file
 */


/**
 * ENABLE CUSTOM BACKGROUNDS
 */
if (of_get_option('enable_wordpress_background', false))
    add_custom_background();

?>