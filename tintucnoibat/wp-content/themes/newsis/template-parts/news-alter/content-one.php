<?php
/**
 * Template part for displaying block content in alter block
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Newsis
 */
use Newsis\CustomizerDefault as NI;
?>
<article class="alter-item<?php if(!has_post_thumbnail()) { echo esc_attr(' no-feat-img');} ?>">
    <div class="blaze_box_wrap newsis-card">
        <figure class="post-thumb-wrap">
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                <?php
                    if( has_post_thumbnail() ) { 
                        the_post_thumbnail($args->imageSize, array(
                            'title' => the_title_attribute(array(
                                'echo'  => false
                            ))
                        ));
                    }
                ?>
            </a>
            <?php if( $args->categoryOption && $args->featuredPosts ) newsis_get_post_categories( get_the_ID(), 2 ); ?>
        </figure>
        <div class="post-element">
            <h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
            <div class="post-meta">
                <?php if( $args->authorOption ) newsis_posted_by(); ?>
                <?php if( $args->dateOption ) newsis_posted_on(); ?>
                <?php
                    if( $args->commentOption ) {
                        $website_comments_before_icon = NI\newsis_get_customizer_option( 'website_comments_before_icon' );
                        if( $website_comments_before_icon['type'] == 'none' ) {
                    ?>
                            <span class="post-comment"><?php echo absint( get_comments_number() ); ?></span>
                    <?php
                        } else {
                    ?>
                            <span class="post-comment <?php echo esc_attr( $website_comments_before_icon['value'] ); ?>"><?php echo absint( get_comments_number() ); ?></span>
                    <?php
                        }
                    }
                ?>
            </div>
            
            <?php
                if( $args->excerptOption ):
                    $excerptLength = isset( $options->excerptLength ) ? $options->excerptLength: 10; 
                    echo '<div class="post-excerpt">' .esc_html( wp_trim_words( wp_strip_all_tags( get_the_excerpt() ), $args->excerptLength ) ). '</div>';
                endif;
                do_action( 'newsis_section_block_view_all_hook', array(
                    'option'    => isset( $args->buttonOption ) ? $args->buttonOption : false
                ));
            ?>
        </div>
    </div>
</article>