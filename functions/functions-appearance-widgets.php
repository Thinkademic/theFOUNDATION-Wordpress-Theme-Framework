<?php
/****
 * functions/functions-appearance-widgets.php
 *
 * PLACE FUNCTIONS TO ADD WIDGETS OR REMOVE WIDGETS IN THIS FILE
 */

/**
 * theFOUNDATION CONTACT INFO WIDGET
 */
add_action('widgets_init', 'load_fdt_contact_info_widget');

function load_fdt_contact_info_widget()
{
    register_widget('widget_fdt_contact_info');
}

class widget_fdt_contact_info extends WP_Widget
{

    /* DEFAULT SETTINGS FOR OUR WIDGET */
    function widget_fdt_contact_info()
    {

        /* WIDGET SETTINGS. */
        $widget_ops = array('classname' => 'contact-info', 'description' => 'Add Contact Information');

        /* WIDGET CONTROL SETTINGS. */
        $control_ops = array('width' => 330, 'height' => 350, 'id_base' => 'contact-info-widget');

        /* CREATE THE WIDGET. */
        $this->WP_Widget('contact-info-widget', 'Contact Info', $widget_ops, $control_ops);
    }

    /**
     * OUTPUT WIDGET CONTENT
     */
    function widget($args, $instance)
    {
        extract($args);

        /* OUR VARIABLES FROM THE WIDGET SETTINGS. */
        $title = apply_filters('widget_title', $instance['title']);
        $byline = $instance['tags'];

        /* BEFORE WIDGET (DEFINED BY THEMES). */
        echo $before_widget;
        echo "
				<div>
					$title	
					$byline
				</div>
			";


        /* After widget (defined by themes). */
        echo $after_widget;
    }

    /**
     * UPDATE THE WIDGET SETTINGS.
     */
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags($new_instance['title']);
        $instance['byline'] = strip_tags($new_instance['byline']);
        $instance['extratext'] = $new_instance['extratext'];

        return $instance;
    }


    /**
     * Displays the widget settings controls on the widget panel.
     * Make use of the get_field_id() and get_field_name() function
     * when creating your form elements. This handles the confusing stuff.
     */
    function form($instance)
    {

        /* Set up some default widget settings. */
        $defaults = array(
            'title' => '',
            'byline' => '',
            'extratext' => ''
        );


        $instance = wp_parse_args((array)$instance, $defaults);


        echo '
			<!-- Widget Title: Text Input -->
			<p>
				<label for="' . $this->get_field_id('title') . '">Title</label><br />
				<input id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" value="' . $instance['title'] . '" style="display:block; width:320px;">
			</p>

			<!-- Additional Text -->
			<p>
				<label for="' . $this->get_field_id('byline') . '">Byline</label><br />
				<input id="' . $this->get_field_id('byline') . '" name="' . $this->get_field_name('byline') . '" value="' . $instance['byline'] . '" style="display:block; width:320px;">
			</p>		
			
			<!-- Set SlideScroler Slide Size: Select Box -->
			<p>
				<label for="' . $this->get_field_id('extratext') . '" style="display:block; width:100%;">Contact Info</label>
				<textarea  id="' . $this->get_field_id('extratext') . '" name="' . $this->get_field_name('extratext') . '"  rows="4" style="clear: both;width:330px;" >' . $instance['extratext'] . '</textarea>
			</p>		
		';


    }


}

?>