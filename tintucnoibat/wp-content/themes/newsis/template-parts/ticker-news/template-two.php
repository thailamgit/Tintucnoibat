<?php
/**
 * Ticker news template two
 * 
 * @package Newsis
 * @since 1.0.0
 */
use Newsis\CustomizerDefault as NI;
$ticker_query = new WP_Query( $args );
if( $ticker_query->have_posts() ) :
    while( $ticker_query->have_posts() ) : $ticker_query->the_post();
    ?>
        <li class="ticker-item newsis-card">
            <figure class="feature_image">
                <?php
                    if( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                            <?php
                                the_post_thumbnail('newsis-thumb', array(
                                            'title' => the_title_attribute(array(
                                                'echo'  => false
                                            ))
                                        ));
                                    ?>
                        </a>
                <?php 
                    endif;
                ?>
            </figure>
            <div class="title-wrap">
                <h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                <?php newsis_posted_on(); ?>
            </div>
        </li>
    <?php
    endwhile;
    wp_reset_postdata();
endif;