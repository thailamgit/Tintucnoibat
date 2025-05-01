<?php
 
/**
 * Adds Newsis_Social_Icons_Widget widget.
 * 
 * @package Newsis
 * @since 1.0.0
 */
class Newsis_Social_Icons_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'newsis_social_icons_widget',
            esc_html__( 'Newsis : Social Icons', 'newsis' ),
            array( 'description' => __( 'The list of social icons.', 'newsis' ) )
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
        $icon_inherit_color = isset( $instance['icon_inherit_color'] ) ? $instance['icon_inherit_color'] : '';

        echo wp_kses_post($before_widget);
            if ($widget_title ): ?>
                <h2 class="widget-title">
                    <span><?php echo esc_html($widget_title); ?></span>
                </h2>
            <?php endif; ?>
            <div class="social-block-widget newsis-card<?php if( $icon_inherit_color ) echo esc_attr( ' global-color-icon' ); ?>">
                <?php newsis_customizer_social_icons(); ?>
            </div>
    <?php
        echo wp_kses_post($after_widget);
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
                    'default'   => esc_html__( 'Find Me On', 'newsis' )
                ),
                array(
                    'name'      => 'icon_inherit_color',
                    'type'      => 'checkbox',
                    'title'     => esc_html__( 'Inherit global default social icons color', 'newsis' ),
                    'default'   => true
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
    ?>
            <div class="refer-note">
                <p>
                    <?php esc_html_e( 'Manage social icons from customizer ', 'newsis' ); ?>
                    <a href="<?php echo esc_url(admin_url( 'customize.php?autofocus[control]=social_icons' )); ?>" target="_blank"><?php esc_html_e( 'go to manage social icons', 'newsis' ); ?></a>
                </p>
            </div>
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
} // class Newsis_Social_Icons_Widget