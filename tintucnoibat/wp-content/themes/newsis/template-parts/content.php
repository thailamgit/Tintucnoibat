<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Newsis
 */
use Newsis\CustomizerDefault as NI;
$archive_post_element_order = $args['archive_post_element_order'];
$archive_post_meta_order = $args['archive_post_meta_order'];
$archive_page_category_option = $args['archive_page_category_option'];
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
    <div class="blaze_box_wrap newsis-card">
    	<figure class="post-thumb-wrap <?php if(!has_post_thumbnail()){ echo esc_attr('no-feat-img');} ?>">
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                <?php
                    if( has_post_thumbnail() ) { 
                        the_post_thumbnail( 'newsis-list', array(
                            'title' => the_title_attribute(array(
                                'echo'  => false
                            ))
                        ));
                    }
                ?>
            </a>
            <?php if( $archive_page_category_option ) newsis_get_post_categories(get_the_ID(), 2); ?>
        </figure>
        <div class="post-element">
            <?php
                foreach( $archive_post_element_order as $element_order ) :
                    if( $element_order['option'] ) {
                        switch( $element_order['value'] ) {
                            case 'title': ?> <h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                            <?php
                                break;
                            case 'meta': ?> 
                                        <div class="post-meta">
                                            <?php
                                                foreach( $archive_post_meta_order as $meta_order ) :
                                                    if( $meta_order['option'] ) {
                                                        switch( $meta_order['value'] ) {
                                                            case 'author': newsis_posted_by();
                                                                        break;
                                                            case 'date': newsis_posted_on();
                                                                        break;
                                                            case 'comments': newsis_comments_number();
                                                                        break;
                                                            case 'read-time': $website_read_time_before_icon = NI\newsis_get_customizer_option( 'website_read_time_before_icon' );
                                                                                if( $website_read_time_before_icon['type'] == 'none' ) {
                                                                                    echo '<span class="read-time">' .newsis_post_read_time( get_the_content() ). ' ' .esc_html__( 'mins', 'newsis' ). '</span>';
                                                                                } else {
                                                                                    echo '<span class="read-time ' .esc_attr( $website_read_time_before_icon['value'] ). '">' .newsis_post_read_time( get_the_content() ). ' ' .esc_html__( 'mins', 'newsis' ). '</span>';
                                                                                }
                                                                        break;
                                                            default: '';
                                                        }
                                                    }
                                                endforeach;
                                            ?>
                                        </div>
                            <?php
                                        break;
                                case 'excerpt': ?> <div class="post-excerpt"><?php the_excerpt(); ?></div>
                                        <?php
                                                break;
                                case 'button':
                                                do_action( 'newsis_section_block_view_all_hook', array(
                                                    'option'    => $element_order['option']
                                                ));
                                                break;
                            default: '';
                        }
                    }
                endforeach;
            ?>
        </div>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->