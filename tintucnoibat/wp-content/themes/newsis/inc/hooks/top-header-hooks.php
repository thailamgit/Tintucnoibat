<?php
/**
 * Top Header hooks and functions
 * 
 * @package Newsis
 * @since 1.0.0
 */
use Newsis\CustomizerDefault as NI;
   if( ! function_exists( 'newsis_top_header_date_time_part' ) ) :
      /**
       * Top header menu element
      * 
      * @since 1.0.0
      */
      function newsis_top_header_date_time_part() {
      if( ! NI\newsis_get_customizer_option( 'top_header_date_time_option' ) ) return;
      ?>
         <div class="top-date-time">
            <div class="top-date-time-inner">
              <span class="time"></span>
              <span class="date"><?php echo date_i18n(get_option('date_format'), current_time('timestamp')); ?></span>
              
            </div>
         </div>
      <?php
      }
      add_action( 'newsis_top_header_hook', 'newsis_top_header_date_time_part', 10 );
   endif;

 if( ! function_exists( 'newsis_top_header_ticker_news_part' ) ) :
    /**
     * Top header menu element
     * 
     * @since 1.0.0
     */
    function newsis_top_header_ticker_news_part() {
      if( ! NI\newsis_get_customizer_option( 'top_header_ticker_news_option' ) || NI\newsis_get_customizer_option('top_header_right_content_type') != 'ticker-news' ) return;
      $ticker_args['posts_per_page'] = 4;
      $top_header_ticker_news_categories = json_decode( NI\newsis_get_customizer_option( 'top_header_ticker_news_categories' ) );
      if( NI\newsis_get_customizer_option( 'top_header_ticker_news_date_filter' ) != 'all' ) $ticker_args['date_query'] = newsis_get_date_format_array_args(NI\newsis_get_customizer_option( 'top_header_ticker_news_date_filter' ));
      if( $top_header_ticker_news_categories ) $ticker_args['cat'] = newsis_get_categories_for_args($top_header_ticker_news_categories);
      $top_header_ticker_news_posts = json_decode(NI\newsis_get_customizer_option( 'top_header_ticker_news_posts' ));
      if( $top_header_ticker_news_posts ) {
         $ticker_args['post__in'] = newsis_get_post_id_for_args($top_header_ticker_news_posts);
      }
      ?>
         <div class="top-ticker-news">
            <ul class="ticker-item-wrap">
               <?php
                  if( isset( $ticker_args ) ) :
                     $ticker_args['ignore_sticky_posts'] = true;
                     $ticker_query = new WP_Query( $ticker_args );
                     if( $ticker_query->have_posts() ) :
                        while( $ticker_query->have_posts() ) : $ticker_query->the_post();
                        ?>
                           <li class="ticker-item"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2></li>
                        <?php
                        endwhile;
                        wp_reset_postdata();
                     endif;
                  endif;
               ?>
            </ul>
			</div>
      <?php
    }
    add_action( 'newsis_top_header_hook', 'newsis_top_header_ticker_news_part', 10 );
 endif;

 if( ! function_exists( 'newsis_top_header_menu_part' ) ) :
   /**
    * Top header menu element
    * 
    * @since 1.0.0
    */
   function newsis_top_header_menu_part() {
     if( ! NI\newsis_get_customizer_option( 'top_header_menu_option' ) || NI\newsis_get_customizer_option('top_header_right_content_type') != 'nav-menu' ) return;
     ?>
        <div class="top-nav-menu">
            <?php
               wp_nav_menu(
                     array(
                        'theme_location' => 'menu-1',
                        'menu_id'        => 'top-menu',
                        'depth'  => 1
                     )
               );
            ?>
        </div>
     <?php
   }
   add_action( 'newsis_top_header_hook', 'newsis_top_header_menu_part', 10 );
endif;

add_action( 'newsis_top_header_hook', function() {
   if( ! NI\newsis_get_customizer_option( 'header_newsletter_option' ) && ! NI\newsis_get_customizer_option( 'header_random_news_option' ) ) return;
   echo '<div class="top-header-nrn-button-wrap">';
}, 18 ); // newsletter wrapper open
add_action( 'newsis_top_header_hook', function() {
   if( ! NI\newsis_get_customizer_option( 'header_newsletter_option' ) && ! NI\newsis_get_customizer_option( 'header_random_news_option' ) ) return;
   echo '</div><!-- .top-header-nrn-button-wrap -->';
}, 22 ); // newsletter wrapper end