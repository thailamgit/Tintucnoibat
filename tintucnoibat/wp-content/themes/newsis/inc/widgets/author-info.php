<?php
/**
 * Adds Newsis_Author_Info_Widget widget.
 * 
 * @package Newsis
 * @since 1.0.0
 */
class Newsis_Author_Info_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'newsis_author_info_widget',
            esc_html__( 'Newsis : Author Info', 'newsis' ),
            array( 'description' => __( 'The information of  in detail author.', 'newsis' ) )
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
        $author_name = isset( $instance['author_name'] ) ? $instance['author_name'] : '';
        $author_image = isset( $instance['author_image'] ) ? $instance['author_image'] : '';
        $author_tag = isset( $instance['author_tag'] ) ? $instance['author_tag'] : '';
        $author_desc = isset( $instance['author_desc'] ) ? $instance['author_desc'] : '';
        $author_url = isset( $instance['author_url'] ) ? $instance['author_url'] : '';
        $widget_layout = isset( $instance['widget_layout'] ) ? $instance['widget_layout'] : 'layout-two';
        
        echo wp_kses_post( $before_widget );
            if ( ! empty( $widget_title ) ) {
                echo wp_kses_post( $before_title ) . esc_html( $widget_title ) . wp_kses_post( $after_title );
            }
    ?>
            <div class="post-card author-wrap newsis-card <?php echo esc_html( $widget_layout ); ?>">
                <div class="bmm-author-thumb-wrap">
                    <figure class="post-thumb post-thumb">
                        <?php                            
                            if( $author_image ) :
                                ?>
                                    <a href="<?php echo esc_url( $author_url ); ?>">
                                        <img src="<?php echo esc_url( wp_get_attachment_image_url( $author_image, 'newsis-list' ) ); ?>" loading="<?php newsis_lazy_load_value(); ?>">
                                    </a>
                                <?php 
                            endif;
                        ?> 
                    </figure>
                    <?php
                        if( $author_tag ) echo '<span class="author-tag">' .esc_html( $author_tag ). '</span>';
                    ?> 
                </div>
                <div class="author-content-wrap">
                    <?php
                        if( $author_name ) :
                    ?>
                            <h2 class="author-name"><a href="<?php echo esc_url( $author_url ); ?>"><?php echo esc_html( $author_name ); ?></a></h2>
                    <?php
                        endif;
                        if( $author_desc ) :
                    ?>
                            <div class="author-desc"><?php echo esc_html( $author_desc ); ?></div>
                    <?php
                        endif;
                    ?>
                </div>
            </div>
    <?php
        echo wp_kses_post( $after_widget );
    }

    /**
     * Widgets fields
     * 
     */
    function widget_fields() {
        return array(
                array(
                    'name'      => 'widget_title',
                    'type'      => 'text',
                    'title'     => esc_html__( 'Widget Title', 'newsis' ),
                    'description'=> esc_html__( 'Add the widget title here', 'newsis' ),
                    'default'   => esc_html__( 'Author Info', 'newsis' )
                ),
                array(
                    'name'      => 'author_name',
                    'type'      => 'text',
                    'title'     => esc_html__( 'Author Name', 'newsis' ),
                    'default'   => esc_html__( 'Author Name', 'newsis' )
                ),
                array(
                    'name'      => 'author_image',
                    'type'      => 'upload',
                    'title'     => esc_html__( 'Author Image', 'newsis' )
                ),
                array(
                    'name'      => 'author_tag',
                    'type'      => 'text',
                    'title'     => esc_html__( 'Author Tag', 'newsis' ),
                    'default'   => esc_html__( 'Writer', 'newsis' )
                ),
                array(
                    'name'      => 'author_url',
                    'type'      => 'url',
                    'title'     => esc_html__( 'Author URL', 'newsis' ),
                ),
                array(
                    'name'      => 'author_desc',
                    'type'      => 'textarea',
                    'title'     => esc_html__( 'Description', 'newsis' ),
                    'default'   => esc_html__( 'Lorem ipsum is simply dummy text', 'newsis' )
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
 
} // class Newsis_Author_Info_Widget