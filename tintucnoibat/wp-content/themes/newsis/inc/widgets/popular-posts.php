<?php
/**
 * Adds Newsis_Popular_Posts_Widget widget.
 * 
 * @package Newsis
 * @since 1.0.0
 */
class Newsis_Popular_Posts_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'newsis_popular_posts_widget',
            esc_html__( 'Newsis : Popular Posts Slider', 'newsis' ),
            array( 'description' => __( 'A collection of popular posts from specific category.', 'newsis' ) )
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        extract( $args );
        $widget_title = isset( $instance['widget_title'] ) ? $instance['widget_title'] : '';
        $posts_category = isset( $instance['posts_category'] ) ? $instance['posts_category'] : '';
        $slider_auto = isset( $instance['slider_auto'] ) ? $instance['slider_auto'] : true;
        $slider_arrows = isset( $instance['slider_arrows'] ) ? $instance['slider_arrows'] : true;
        $slider_loop = isset( $instance['slider_loop'] ) ? $instance['slider_loop'] : true;
        $slide_direction = isset( $instance['slide_direction'] ) ? $instance['slide_direction'] : 'vertical';
        
        echo wp_kses_post($before_widget);
            // Slider direction
            $newsis_slider = 'newsis_vertical_slider';
            if( $slide_direction == 'horizontal' ) $newsis_slider = 'newsis_horizontal_slider';
            ?>
            <div class="newsis-widget-popular-posts <?php echo esc_attr($newsis_slider); ?> <?php if(empty($widget_title)) echo esc_attr('no_heading_widget');?>">
                <?php if ($widget_title ): ?>
                    <h2 class="widget-title">
                        <span><?php echo esc_html($widget_title); ?></span>
                    </h2>
                <?php endif; ?>
                <div class="popular-posts-wrap" data-auto="<?php echo esc_attr( json_encode( $slider_auto ) ); ?>" data-arrows="<?php echo esc_attr( json_encode( $slider_arrows ) ); ?>" data-loop="<?php echo esc_attr( json_encode( $slider_loop ) ); ?>" data-vertical="<?php echo esc_attr( $slide_direction ); ?>">
                    <?php
                        $popular_posts_args = array( 
                            'numberposts' => -1,
                            'cat' => absint( $posts_category )
                        );
                        if( empty( $posts_category ) ) $popular_posts_args['numberposts'] = 8;
                        $popular_posts = get_posts( $popular_posts_args );
                        if( $popular_posts ) :
                            $total_posts = sizeof($popular_posts);
                            foreach( $popular_posts as $popular_post_key => $popular_post ) :
                                $popular_post_id  = $popular_post->ID;
                                if( $slide_direction == 'horizontal' ) {
                                    if( ( $popular_post_key % 4 ) == 0 ) echo '<div class="newsis-slick-slide-wrap">';
                                }
                            ?>
                                    <article class="post-item newsis-card newsis-category-no-bk <?php if(!has_post_thumbnail($popular_post_id)){ echo esc_attr('no-feat-img');} ?>">
                                        <figure class="post-thumb">
                                            <span class="post-count"><?php echo absint( $popular_post_key+1 ); ?></span>
                                            <?php if( has_post_thumbnail($popular_post_id) ): ?> 
                                                <a href="<?php echo esc_url(get_the_permalink($popular_post_id)); ?>">
                                                    <img src="<?php echo esc_url( get_the_post_thumbnail_url($popular_post_id, 'newsis-grid') ); ?>"/>
                                                </a>
                                            <?php endif; ?>
                                        </figure>
                                        <div class="post-element">
                                            <h2 class="post-title"><a href="<?php the_permalink($popular_post_id); ?>"><?php echo wp_kses_post( get_the_title($popular_post_id) ); ?></a></h2>
                                            <div class="post-meta">
                                                <?php newsis_get_post_categories($popular_post_id,2); ?>
                                            </div>
                                        </div>
                                    </article>
                            <?php
                            if( $slide_direction == 'horizontal' ) {
                                if( ( $popular_post_key % 4 ) == 3 || ( $popular_post_key + 1 ) == $total_posts ) echo '</div><!-- .newsis-slick-slide-wrap -->';
                            }
                            endforeach;
                        endif;
                    ?>
                </div>
            </div>
    <?php
        echo wp_kses_post($after_widget);
    }

    /**
     * Widgets fields
     * 
     */
    function widget_fields() {
        $categories = get_categories();
        $categories_options[''] = esc_html__( 'Select category', 'newsis' );
        foreach( $categories as $category ) :
            $categories_options[$category->term_id] = $category->name. ' (' .$category->count. ') ';
        endforeach;
        return array(
                array(
                    'name'      => 'widget_title',
                    'type'      => 'text',
                    'title'     => esc_html__( 'Widget Title', 'newsis' ),
                    'description'=> esc_html__( 'Add the widget title here', 'newsis' ),
                    'default'   => esc_html__( 'Popular News', 'newsis' )
                ),
                array(
                    'name'      => 'posts_category',
                    'type'      => 'select',
                    'title'     => esc_html__( 'Categories', 'newsis' ),
                    'description'=> esc_html__( 'Choose the category to display for popular posts', 'newsis' ),
                    'options'   => $categories_options
                ),
                array(
                    'name'      => 'slider_auto',
                    'type'      => 'checkbox',
                    'title'     => esc_html__( 'Enable slider item to auto slide', 'newsis' ),
                    'default'   => true
                ),
                array(
                    'name'      => 'slider_arrows',
                    'type'      => 'checkbox',
                    'title'     => esc_html__( 'Show slider controller arrows', 'newsis' ),
                    'default'   => true
                ),
                array(
                    'name'      => 'slider_loop',
                    'type'      => 'checkbox',
                    'title'     => esc_html__( 'Enable infinite loop', 'newsis' ),
                    'default'   => true
                ),
                array(
                    'name'      => 'slide_direction',
                    'type'      => 'select',
                    'title'     => esc_html__( 'Slide Direction', 'newsis' ),
                    'options'   => array(
                        'vertical'    => esc_html__( 'Vertical', 'newsis' ),
                        'horizontal'    => esc_html__( 'Horizontal', 'newsis' )
                    )
                )
            );
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $widget_fields = $this->widget_fields();
        foreach( $widget_fields as $widget_field ) :
            if ( isset( $instance[ $widget_field['name'] ] ) ) {
                $field_value = $instance[ $widget_field['name'] ];
            } else if( isset( $widget_field['default'] ) ) {
                $field_value = $widget_field['default'];
            } else {
                $field_value = '';
            }
            newsis_widget_fields( $this, $widget_field, $field_value );
        endforeach;
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
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $widget_fields = $this->widget_fields();
        if( ! is_array( $widget_fields ) ) {
            return $instance;
        }
        foreach( $widget_fields as $widget_field ) :
            $instance[$widget_field['name']] = newsis_sanitize_widget_fields( $widget_field, $new_instance );
        endforeach;

        return $instance;
    }
 
} // class Newsis_Popular_Posts_Widget