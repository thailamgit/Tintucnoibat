<?php
/**
 * Adds Newsis_Posts_List_Widget widget.
 * 
 * @package Newsis
 * @since 1.0.0
 */
class Newsis_Posts_List_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'newsis_posts_list_widget',
            esc_html__( 'Newsis : Posts List', 'newsis' ),
            array( 'description' => __( 'A collection of posts from specific category displayed in list layout.', 'newsis' ) )
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
        $posts_count = isset( $instance['posts_count'] ) ? $instance['posts_count'] : 3;
        $posts_cat = isset( $instance['posts_cat'] ) ? $instance['posts_cat'] : true;
        $posts_excerpt = isset( $instance['posts_excerpt'] ) ? $instance['posts_excerpt'] : false;
        $posts_excerpt_count = isset( $instance['posts_excerpt_count'] ) ? $instance['posts_excerpt_count'] : 10;
        $widget_layout = isset( $instance['widget_layout'] ) ? $instance['widget_layout'] : 'layout-one';
        echo wp_kses_post($before_widget);
            if ( ! empty( $widget_title ) ) {
                echo $before_title . $widget_title . $after_title;
            }
    ?>
            <div class="posts-wrap posts-list-wrap feature-post-block <?php echo esc_attr( $widget_layout ); ?>">
                <?php
                    $post = new WP_Query( 
                        array( 
                            'cat'    => absint( $posts_category ),
                            'posts_per_page' => absint( $posts_count ),
                            'ignore_sticky_posts'    => true
                        )
                    );
                    if( $post->have_posts() ) :
                        $delay = 0;
                        while( $post->have_posts() ) : $post->the_post();
                            $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'newsis-list' );
                            $categories = get_the_category();
                    ?>
                            <div class="post-item format-standard newsis-category-no-bk newsis-card">
                                <div class="post_thumb_image post-thumb <?php if( !$thumbnail_url ) echo esc_attr('no-feat-img'); ?>">
                                    <figure class="post-thumb">
                                        <?php if( $thumbnail_url ) : ?>
                                            <a href="<?php the_permalink(); ?>">
                                                <img src="<?php echo esc_url( $thumbnail_url ); ?>" loading="<?php newsis_lazy_load_value(); ?>">
                                            </a>
                                        <?php endif; ?>
                                    </figure>
                                </div>
                                <div class="post-content-wrap card__content">
                                    <?php
                                        if( $posts_cat ) {
                                            echo '<div class="bmm-post-cats-wrap bmm-post-meta-item post-categories">';
                                                $count = 0;
                                                foreach( $categories as $cat ) {
                                                    echo '<h5 class="card__content-category cat-item cat-' .esc_attr( $cat->cat_ID ). '"><a href="' .esc_url(get_term_link( $cat->cat_ID )). '">' .esc_html( $cat->name ). '</a></h5>';
                                                    if( $count > 0 ) break;
                                                    $count++;
                                                }
                                            echo '</div>';
                                        }
                                        ?>
                                        <div class="newsis-post-title card__content-title post-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </div>
                                    <?php
                                        if( $posts_excerpt ) {
                                            echo '<div class="post-content card__content-info">' .esc_html( wp_trim_words( get_the_excerpt(), $posts_excerpt_count ) ). '</div>';
                                        }
                                    ?>
                                </div>
                            </div>
                    <?php
                        $delay += 100;
                        endwhile;
                        wp_reset_postdata();
                    endif;
                ?>
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
                    'default'   => esc_html__( 'Trending News', 'newsis' )
                ),
                array(
                    'name'      => 'posts_category',
                    'type'      => 'select',
                    'title'     => esc_html__( 'Categories', 'newsis' ),
                    'description'=> esc_html__( 'Choose the category to display list of posts', 'newsis' ),
                    'options'   => $categories_options
                ),
                array(
                    'name'      => 'posts_count',
                    'type'      => 'number',
                    'title'     => esc_html__( 'Number of posts to show', 'newsis' ),
                    'default'   => 3
                ),
                array(
                    'name'      => 'posts_cat',
                    'type'      => 'checkbox',
                    'title'     => esc_html__( 'Show post categories', 'newsis' ),
                    'default'   => true
                ),
                array( 
                    'name'      => 'posts_excerpt',
                    'type'      => 'checkbox',
                    'title'     => esc_html__( 'Show post excerpt content', 'newsis' ),
                    'default'   => false
                ),
                array(
                    'name'      => 'posts_excerpt_count',
                    'type'      => 'number',
                    'title'     => esc_html__( 'Excerpt Length', 'newsis' ),
                    'default'   => 10
                ),
                array(
                    'name'      => 'widget_layout',
                    'type'      => 'select',
                    'title'     => esc_html__( 'Layouts', 'newsis' ),
                    'options'   => array(
                        'layout-one'    => esc_html__( 'Layout One', 'newsis' ),
                        'layout-two'    => esc_html__( 'Layout Two', 'newsis' )
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
 
} // class Newsis_Posts_List_Widget