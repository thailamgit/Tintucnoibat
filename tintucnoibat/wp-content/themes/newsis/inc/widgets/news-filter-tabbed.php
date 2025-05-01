<?php
/**
 * Adds Newsis_News_Filter_Tabbed_Widget widget.
 * 
 * @package Newsis
 * @since 1.0.0
 */
class Newsis_News_Filter_Tabbed_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'newsis_news_filter_tabbed_widget',
            esc_html__( 'Newsis : News Filter - Tabbed', 'newsis' ),
            array( 'description' => __( 'A collection of tabbed news from specific time frame.', 'newsis' ) )
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
        $first_tab_title = isset( $instance['first_tab_title'] ) ? esc_html( $instance['first_tab_title'] ) : '';
        $first_tab_count = isset( $instance['first_tab_count'] ) ? $instance['first_tab_count'] : 6;
        $first_tab_posts = isset( $instance['first_tab_posts'] ) ? $instance['first_tab_posts'] : 'all';
        $second_tab_title = isset( $instance['second_tab_title'] ) ? esc_html( $instance['second_tab_title'] ) : '';
        $second_tab_count = isset( $instance['second_tab_count'] ) ? $instance['second_tab_count'] : 6;
        $second_tab_posts = isset( $instance['second_tab_posts'] ) ? $instance['second_tab_posts'] : 'all';
        $third_tab_title = isset( $instance['third_tab_title'] ) ? esc_html( $instance['third_tab_title'] ) : '';
        $third_tab_count = isset( $instance['third_tab_count'] ) ? $instance['third_tab_count'] : 6;
        $third_tab_posts = isset( $instance['third_tab_posts'] ) ? $instance['third_tab_posts'] : 'all';

        echo wp_kses_post($before_widget);
            ?>
            <div class="newsis-news-filter-tabbed-widget-tabs-wrap">
                <ul class="widget-tabs">
                    <li class="widget-tab active" tab-item="first"><?php echo esc_html( $first_tab_title ); ?></li>
                    <li class="widget-tab" tab-item="second"><?php echo esc_html( $second_tab_title ); ?></li>
                    <li class="widget-tab" tab-item="third"><?php echo esc_html( $third_tab_title ); ?></li>
                </ul>
                <div class="tabs-content-wrap">
                    <div class="tabs-content first show">
                        <?php
                            $first_post_args = array(
                                'post_type' => 'post',
                                'posts_per_page' => absint($first_tab_count),
                                'ignore_sticky_posts'    => true
                            );
                            if( $first_tab_posts != 'all' ) $first_post_args['date_query'] = newsis_get_date_format_array_args($first_tab_posts);
                            $first_post_query = new WP_Query( $first_post_args );
                            if( $first_post_query->have_posts() ) :
                                $delay = 0;
                                while( $first_post_query->have_posts() ) : $first_post_query->the_post();
                                $tab_id = get_the_ID();
                                ?>
                                    <article class="post-item newsis-card <?php if(!has_post_thumbnail()){ echo esc_attr('no-feat-img');} ?>">
                                        <figure class="post-thumb">
                                            <?php if( has_post_thumbnail() ): ?>
                                                <a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url('newsis-thumb'); ?>"/></a>
                                            <?php endif; ?>
                                        </figure>
                                        <div class="post-element">
                                            <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                            <div class="post-meta">
                                                <?php newsis_posted_on(); ?>
                                            </div>
                                        </div>
                                    </article>
                                <?php
                                    $delay += 100;
                                endwhile;
                                wp_reset_postdata();
                            endif;
                        ?>
                    </div>
                    <div class="tabs-content second">
                        <?php
                            $second_post_args = array(
                                'post_type' => 'post',
                                'posts_per_page' => absint($second_tab_count),
                                'ignore_sticky_posts'    => true
                            );
                            if( $second_tab_posts != 'all' ) $second_post_args['date_query'] = newsis_get_date_format_array_args($second_tab_posts);
                            $second_post_query = new WP_Query( $second_post_args );
                            if( $second_post_query->have_posts() ) :
                                $delay = 0;
                                while( $second_post_query->have_posts() ) : $second_post_query->the_post();
                                $tab_id = get_the_ID();
                                ?>
                                    <article class="post-item newsis-card <?php if(!has_post_thumbnail()){ echo esc_attr('no-feat-img');} ?>">
                                        <figure class="post-thumb">
                                            <?php if( has_post_thumbnail() ): ?>
                                                <a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url('newsis-thumb'); ?>"/></a>
                                            <?php endif; ?>
                                        </figure>
                                        <div class="post-element">
                                            <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                            <div class="post-meta">
                                                <?php newsis_posted_on(); ?>
                                            </div>
                                        </div>
                                    </article>
                                <?php
                                $delay += 100;
                                endwhile;
                                wp_reset_postdata();
                            endif;
                        ?>
                    </div>
                    <div class="tabs-content third">
                        <?php
                            $third_post_args = array(
                                'post_type' => 'post',
                                'posts_per_page' => absint($third_tab_count),
                                'ignore_sticky_posts'    => true
                            );
                            if( $third_tab_posts != 'all' ) $third_post_args['date_query'] = newsis_get_date_format_array_args($third_tab_posts);
                            $third_post_query = new WP_Query( $third_post_args );
                            if( $third_post_query->have_posts() ) :
                                $delay = 0;
                                while( $third_post_query->have_posts() ) : $third_post_query->the_post();
                                $tab_id = get_the_ID();
                                ?>
                                    <article class="post-item newsis-card <?php if(!has_post_thumbnail()){ echo esc_attr('no-feat-img');} ?>">
                                        <figure class="post-thumb">
                                            <?php if( has_post_thumbnail() ): ?>
                                                <a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url('newsis-thumb'); ?>"/></a>
                                            <?php endif; ?>
                                        </figure>
                                        <div class="post-element">
                                            <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                            <div class="post-meta">
                                                <?php newsis_posted_on(); ?>
                                            </div>
                                        </div>
                                    </article>
                                <?php
                                $delay += 100;
                                endwhile;
                                wp_reset_postdata();
                            endif;
                        ?>
                    </div>
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
        $posts_array_choices = apply_filters( 'newsis_get_choices_array_filter', array(
            'all'   => esc_html__('All', 'newsis' ),
			'last-seven-days'   => esc_html__('Last 7 days', 'newsis' ),
            'today' => esc_html__('Today', 'newsis' ),
			'this-week' => esc_html__('This Week', 'newsis' ),
			'last-week' => esc_html__('Last Week', 'newsis' ),
            'this-month'    => esc_html__('This Month', 'newsis' ),
			'last-month'    => esc_html__('Last Month', 'newsis' )
		));
        return array(
                array(
                    'name'      => 'first_tab_heading',
                    'type'      => 'heading',
                    'label'     => esc_html__( 'First Tab', 'newsis' )
                ),
                array(
                    'name'      => 'first_tab_title',
                    'type'      => 'text',
                    'title'     => esc_html__( '1st Tab', 'newsis' ),
                    'default'   => esc_html__( 'This Week', 'newsis' )
                ),
                array(
                    'name'      => 'first_tab_posts',
                    'type'      => 'select',
                    'title'     => esc_html__( 'Posts to display', 'newsis' ),
                    'default'   => 'this-week',
                    'options'   => $posts_array_choices
                ),
                array(
                    'name'      => 'first_tab_count',
                    'type'      => 'number',
                    'title'     => esc_html__( 'No. of posts to display in first tab', 'newsis' ),
                    'default'   => 6
                ),
                array(
                    'name'      => 'second_tab_heading',
                    'type'      => 'heading',
                    'label'     => esc_html__( 'Second Tab', 'newsis' )
                ),
                array(
                    'name'      => 'second_tab_title',
                    'type'      => 'text',
                    'title'     => esc_html__( '2nd Tab', 'newsis' ),
                    'default'   => esc_html__( 'Last Week', 'newsis' )
                ),
                array(
                    'name'      => 'second_tab_posts',
                    'type'      => 'select',
                    'title'     => esc_html__( 'Posts to display', 'newsis' ),
                    'default'   => 'last-week',
                    'options'   => $posts_array_choices
                ),
                array(
                    'name'      => 'second_tab_count',
                    'type'      => 'number',
                    'title'     => esc_html__( 'No. of posts to display in second tab', 'newsis' ),
                    'default'   => 6
                ),
                array(
                    'name'      => 'third_tab_heading',
                    'type'      => 'heading',
                    'label'     => esc_html__( 'Third Tab', 'newsis' )
                ),
                array(
                    'name'      => 'third_tab_title',
                    'type'      => 'text',
                    'title'     => esc_html__( '3rd Tab', 'newsis' ),
                    'default'   => esc_html__( 'Last Month', 'newsis' )
                ),
                array(
                    'name'      => 'third_tab_posts',
                    'type'      => 'select',
                    'title'     => esc_html__( 'Posts to display', 'newsis' ),
                    'default'   => 'last-month',
                    'options'   => $posts_array_choices
                ),
                array(
                    'name'      => 'third_tab_count',
                    'type'      => 'number',
                    'title'     => esc_html__( 'No. of posts to display in third tab', 'newsis' ),
                    'default'   => 6
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
 
} // class Newsis_News_Filter_Tabbed_Widget