<?php
/**
 * Main Banner template four
 * 
 * @package Newsis
 * @since 1.0.0
 */
use Newsis\CustomizerDefault as NI;
$slider_args = $args['slider_args'];
?>
<div class="main-banner-wrap">
    <div class="main-banner-slider newsis-card">
        <?php
            $slider_query = new WP_Query( $slider_args );
            if( $slider_query -> have_posts() ) :
                while( $slider_query -> have_posts() ) : $slider_query -> the_post();
                ?>
                    <article class="slide-item<?php if(!has_post_thumbnail()){ echo esc_attr(' no-feat-img');} ?>">
                        <figure class="post-thumb">
                            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                <?php 
                                    if( has_post_thumbnail()) { 
                                        the_post_thumbnail('newsis-featured', array(
                                            'title' => the_title_attribute(array(
                                                'echo'  => false
                                            ))
                                        ));
                                    }
                                ?>
                            </a>
                        </figure>
                        <?php newsis_get_post_categories( get_the_ID(), 2 ); ?>
                        <div class="post-element">
                            <div class="post-meta">
                                <?php newsis_posted_on(); ?>
                            </div>
                            <h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                            <?php
                                /**
                                 * hook - newsis_main_banner_post_append_hook
                                 * 
                                 */
                                do_action('newsis_main_banner_post_append_hook', get_the_ID());
                            ?>
                        </div>
                    </article>
                <?php
                endwhile;
            endif;
        ?>
    </div>
</div>

<div class="main-banner-block-posts banner-trailing-posts">
    <?php
        $main_banner_block_posts_to_include = json_decode( NI\newsis_get_customizer_option( 'main_banner_block_posts_to_include' ) );
        $main_banner_block_posts_categories = json_decode( NI\newsis_get_customizer_option( 'main_banner_block_posts_categories' ) );
        $main_banner_block_posts_order_by = NI\newsis_get_customizer_option( 'main_banner_block_posts_order_by' );
        $blockPostsOrderArray = explode( '-', $main_banner_block_posts_order_by );
        $block_posts_args = array(
            'numberposts' => 4,
            'order' => esc_html( $blockPostsOrderArray[1] ),
            'orderby' => esc_html( $blockPostsOrderArray[0] ),
            'cat' => newsis_get_categories_for_args( $main_banner_block_posts_categories )
        );
        if( ! empty( $main_banner_block_posts_to_include ) ) $block_posts_args['post__in'] = newsis_get_post_id_for_args( $main_banner_block_posts_to_include ); 
        $block_posts = get_posts( $block_posts_args );
        if( $block_posts ) :
            foreach( $block_posts as $popular_post ) :
                $popular_post_id  = $popular_post->ID;
            ?>
                    <article class="post-item newsis-card<?php if(!has_post_thumbnail($popular_post_id)){ echo esc_attr(' no-feat-img');} ?>">
                        <figure class="post-thumb">
                            <?php if( has_post_thumbnail($popular_post_id) ): ?>
                                <a href="<?php echo esc_url(get_the_permalink($popular_post_id)); ?>" title="<?php the_title_attribute(['post' => $popular_post_id]); ?>">
                                    <img src="<?php echo esc_url( get_the_post_thumbnail_url($popular_post_id, 'newsis-featured') ); ?>" alt="<?php echo esc_attr( get_post_meta( get_post_thumbnail_id($popular_post_id), '_wp_attachment_image_alt', true ) ); ?>"/>
                                </a>
                            <?php endif; ?>
                        </figure>
                        <div class="post-meta">
                            <?php newsis_get_post_categories( $popular_post_id, 2 ); ?>
                        </div>
                        <div class="post-element">
                            <h2 class="post-title"><a href="<?php the_permalink($popular_post_id); ?>"><?php echo wp_kses_post( get_the_title($popular_post_id) ); ?></a></h2>
                        </div>
                    </article>
            <?php
            endforeach;
        endif;
    ?>
</div>