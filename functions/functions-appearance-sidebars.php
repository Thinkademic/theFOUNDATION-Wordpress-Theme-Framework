<?php
/**
 * FRAMEWORK SIDEBARS IMPLEMENTATION
 *
 * @PLUGGABLE
 */
if (!function_exists('framework_register_sidebars')) {
    function framework_register_sidebars()
    {

        register_sidebar(array(
                              'name' => 'Masthead Before',
                              'id' => 'masthead-before',
                              'before_widget' => '<div id="%1$s" class="widget %2$s">',
                              'after_widget' => '</div>',
                              'before_title' => '<h3>',
                              'after_title' => '</h3>'
                         ));

        register_sidebar(array(
                              'name' => 'Masthead After',
                              'id' => 'masthead-after',
                              'before_widget' => '<div id="%1$s" class="widget %2$s">',
                              'after_widget' => '</div>',
                              'before_title' => '<h3>',
                              'after_title' => '</h3>'
                         ));

        register_sidebar(array(
                              'name' => 'Content Featured',
                              'id' => 'content-featured',
                              'before_widget' => '<div id="%1$s" class="widget %2$s">',
                              'after_widget' => '</div>',
                              'before_title' => '<h3>',
                              'after_title' => '</h3>'
                         ));

        register_sidebar(array(
                              'name' => 'Primary Featured',
                              'id' => 'primary-featured',
                              'before_widget' => '<div id="%1$s" class="widget %2$s">',
                              'after_widget' => '</div>',
                              'before_title' => '<h3>',
                              'after_title' => '</h3>'
                         ));

        register_sidebar(array(
                              'name' => 'Sidebar Featured',
                              'id' => 'sidebar-featured',
                              'before_widget' => '<div id="%1$s" class="widget %2$s">',
                              'after_widget' => "</div>",
                              'before_title' => '<h3>',
                              'after_title' => '</h3>'
                         ));

        register_sidebar(array(
                              'name' => 'Sidebar Primary',
                              'id' => 'sidebar-primary',
                              'before_widget' => '<div id="%1$s" class="widget %2$s">',
                              'after_widget' => "</div>",
                              'before_title' => '<h3>',
                              'after_title' => '</h3>'
                         ));

        register_sidebar(array(
                              'name' => 'Sidebar Secondary',
                              'id' => 'sidebar-secondary',
                              'before_widget' => '<div id="%1$s" class="widget %2$s">',
                              'after_widget' => "</div>",
                              'before_title' => '<h3>',
                              'after_title' => '</h3>'
                         ));

        register_sidebars(4, array(
                                  'name' => 'Footer Column %d',
                                  'before_widget' => '<div id="%1$s" class="widget %2$s">',
                                  'after_widget' => "</div>",
                                  'before_title' => '<h3>',
                                  'after_title' => "</h3>"
                             ));


    }
}


?>