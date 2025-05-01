<?php
/**
 * Inner sections hooks and functions
 * 
 * @package Newsis
 * @since 1.0.0
 */
use Newsis\CustomizerDefault as NI;
if( ! function_exists( 'newsis_single_related_posts' ) ) :
    /**
     * Single related posts
     * 
     * @package Newsis
     */
    function newsis_single_related_posts() {
        if( get_post_type() != 'post' ) return;
        $single_post_related_posts_option = NI\newsis_get_customizer_option( 'single_post_related_posts_option' );
        if( ! $single_post_related_posts_option ) return;
        $related_posts_title = NI\newsis_get_customizer_option( 'single_post_related_posts_title' );
        $post_count = 3;

        $related_posts_args = array(
            'posts_per_page'   => absint( $post_count ),
            'post__not_in'  => array( get_the_ID() ),
            'ignore_sticky_posts'    => true
        );
        $current_post_categories = get_the_category(get_the_ID());
        if( $current_post_categories ) :
            foreach( $current_post_categories as $current_post_cat ) :
                $query_cats[] =  $current_post_cat->term_id;
            endforeach;
            $related_posts_args['category__in'] = $query_cats;
        endif;
        $related_posts = new WP_Query( $related_posts_args );
        if( ! $related_posts->have_posts() ) return;
  ?>
            <div class="single-related-posts-section-wrap layout--list<?php if( NI\newsis_get_customizer_option( 'single_post_related_posts_popup_option' ) ) echo esc_attr( ' related_posts_popup' ); ?>">
                <div class="single-related-posts-section">
                    <a href="javascript:void(0);" class="related_post_close">
                        <i class="fas fa-times-circle"></i>
                    </a>
                    <?php
                        if( $related_posts_title ) echo '<h2 class="newsis-block-title"><span>' .esc_html( $related_posts_title ). '</span></h2>';
                            echo '<div class="single-related-posts-wrap">';
                                while( $related_posts->have_posts() ) : $related_posts->the_post();
                            ?>
                                <article post-id="post-<?php the_ID(); ?>" <?php post_class('newsis-card'); ?>>
                                    <?php if( has_post_thumbnail() ) : ?>
                                        <figure class="post-thumb-wrap <?php if(!has_post_thumbnail()){ echo esc_attr('no-feat-img');} ?>">
                                            <?php newsis_post_thumbnail(); ?>
                                        </figure>
                                    <?php endif; ?>
                                    <div class="post-element">
                                        <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                        <div class="post-meta">
                                            <?php
                                                newsis_posted_by(); // author
                                                newsis_posted_on(); // date
                                                newsis_comments_number();   // comments
                                            ?>
                                        </div>
                                    </div>
                                </article>
                            <?php
                                endwhile;
                            echo '</div>';
                    ?>
                </div>
            </div>
    <?php
    }
endif;
add_action( 'newsis_single_post_append_hook', 'newsis_single_related_posts' );

if( ! function_exists( 'newsis_main_banner_related_posts_html' ) ) :
    /**
     * Single related posts
     * 
     * @package Newsis
     */
    function newsis_main_banner_related_posts_html($excludePostId) {
        $main_banner_related_posts_option = NI\newsis_get_customizer_option( 'main_banner_related_posts_option' );
        if( ! $main_banner_related_posts_option ) return;
        $main_banner_related_posts_numbers = NI\newsis_get_customizer_option( 'main_banner_related_posts_numbers' );
        $related_posts_args = array(
            'posts_per_page'   => absint( $main_banner_related_posts_numbers ),
            'post__not_in'  => array( $excludePostId ),
            'ignore_sticky_posts'    => true
        );
        $current_post_categories = get_the_category($excludePostId);
        if( $current_post_categories ) :
            foreach( $current_post_categories as $current_post_cat ) :
                $query_cats[] =  $current_post_cat->term_id;
            endforeach;
            $related_posts_args['category__in'] = $query_cats;
        endif;
        $related_posts = new WP_Query( $related_posts_args );
        if( ! $related_posts->have_posts() ) return;
  ?>
            <div class="main-banner-related-posts-section-wrap">
                <ul class="related-posts-wrap">
                    <?php
                        while( $related_posts->have_posts() ) : $related_posts->the_post();
                    ?>
                        <li class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                    <?php
                        endwhile;
                    ?>
                </ul>
            </div>
    <?php
    }
endif;
add_action( 'newsis_main_banner_post_append_hook', 'newsis_main_banner_related_posts_html', 10, 1 );