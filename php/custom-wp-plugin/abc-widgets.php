<?php

class abc_Widget extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
        parent::__construct(
            'abc_widget', // Base ID
            __('abc Upcoming Events', 'widget_domain'), // Name
            array('description' => __('abc Upcoming events widget', 'widget_domain'),) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        $params = array('category_name'=>'upcoming events','order'=>'desc','posts_per_page'=>4);
        $abc_query  = new WP_Query($params);

        if($abc_query->have_posts())
        {
            $content = "";
            while($abc_query->have_posts()):

                $abc_query->the_post();
                ?>
                <div class="upcoming_events">
                    <h4><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h4>

                </div>
                <?php

            endwhile;
            wp_reset_postdata();
        }


        // _e($content, 'widget_domain');
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : __('New title', 'widget_domain');
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>
    <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

        return $instance;
    }

} // class Foo_Widget

// Register and load the widget
function abc_load_widget()
{
    register_widget('abc_Widget');
}

add_action('widgets_init', 'abc_load_widget');
