<?php
/**
 * News Carousel template two
 * 
 * @package Newsis
 * @since 1.0.0
 */
use Newsis\CustomizerDefault as NI;
extract( $args );
?>
<div id="<?php echo esc_attr( $uniqueID . ' ' . $options->blockId ); ?>" class="news-carousel <?php echo esc_attr( 'layout--' . $options->layout ); ?>">
    <?php
        do_action( 'newsis_section_block_view_all_hook', array(
            'option'=> isset( $options->viewallOption ) ? $options->viewallOption : false,
            'classes' => 'view-all-button',
            'link'  => isset( $options->viewallUrl ) ? $options->viewallUrl : '',
            'text'  => false
        ));

        $view_allclass = 'viewall_disabled';
        if( $options->viewallOption == 1){
            $view_allclass = 'viewall_enabled';
        }

        if( $options->title ) :
    ?>
            <h2 class="newsis-block-title">
                <span><?php echo esc_html( $options->title ); ?></span>
            </h2>
    <?php
        endif;
    ?>
    <div class="news-carousel-post-wrap <?php echo esc_attr($view_allclass); ?>" data-dots="<?php echo esc_attr( newsis_bool_to_string( $options->dots ) ); ?>" data-loop="<?php echo esc_attr( newsis_bool_to_string( $options->loop ) ); ?>" data-arrows="<?php echo esc_attr( newsis_bool_to_string( $options->arrows ) ); ?>" data-auto="<?php echo esc_attr( newsis_bool_to_string( $options->auto ) ); ?>" data-columns="<?php if( isset($options->columns) ) { echo absint( $options->columns ); } else { echo absint(1); }; ?>">
        <?php
            $post_query = new WP_Query( $post_args );
            if( $post_query -> have_posts() ) :
                while( $post_query -> have_posts() ) : $post_query -> the_post();
                ?>
                    <article class="carousel-item <?php if(!has_post_thumbnail()){ echo esc_attr('no-feat-img');} ?>">
                        <div class="blaze_box_wrap newsis-card">
                            <figure class="post-thumb-wrap">
                                
                                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                    <?php
                                        if( has_post_thumbnail() ) : 
                                            the_post_thumbnail((property_exists( $options, 'imageSize' ) ? $options->imageSize : 'newsis-list'), array(
                                                'title' => the_title_attribute(array(
                                                    'echo'  => false
                                                ))
                                            ));
                                        endif;
                                    ?>
                                    <div class="thumb-overlay"></div>
                                </a>
                                <div class="post-element">
                                    <?php if( $options->categoryOption ) newsis_get_post_categories( get_the_ID(), 2 ); ?>
                                    <div class="post-element-inner">
                                        <h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                                        <div class="post-meta">
                                            <?php if( $options->authorOption ) newsis_posted_by(); ?>
                                            <?php if( $options->dateOption ) newsis_posted_on(); ?>
                                            <?php if( $options->commentOption ) newsis_comments_number(); ?>
                                        </div>
                                    </div>
                                </div>
                            </figure>
                        </div>
                    </article>
                <?php
                endwhile;
            endif;
        ?>
    </div>
</div>