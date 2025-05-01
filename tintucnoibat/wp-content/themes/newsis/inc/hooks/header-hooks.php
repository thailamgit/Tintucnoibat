<?php
/**
 * Header hooks and functions
 * 
 * @package Newsis
 * @since 1.0.0
 */
use Newsis\CustomizerDefault as NI;

if( ! function_exists( 'newsis_header_social_part' ) ) :
    /**
     * header social element
     * 
     * @since 1.0.0
     */
    function newsis_header_social_part() {
    $top_header_social_icons_hover_animation = NI\newsis_get_customizer_option( 'top_header_social_icons_hover_animation' );
    $elementClass = 'social-icons-wrap';
    if( $top_header_social_icons_hover_animation ) $elementClass .= ' newsis-show-hover-animation';
      ?>
         <div class="<?php echo esc_html( $elementClass ); ?>">
            <?php
               if( NI\newsis_get_customizer_option( 'top_header_social_option' ) ) {
                  newsis_customizer_social_icons();
               }
            ?>
         </div>
      <?php
    }
    add_action( 'newsis_header__site_branding_section_hook', 'newsis_header_social_part', 5 );
 endif;

 if( ! function_exists( 'newsis_header_site_branding_part' ) ) :
    /**
     * Header site branding element
     * 
     * @since 1.0.0
     */
     function newsis_header_site_branding_part() {
         ?>
            <div class="site-branding">
                <?php
                    the_custom_logo();
                    if ( is_front_page() && is_home() ) :
                ?>
                        <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                <?php
                    else :
                ?>
                        <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                <?php
                    endif;
                    $newsis_description = get_bloginfo( 'description', 'display' );
                    if ( $newsis_description || is_customize_preview() ) :
                ?>
                    <p class="site-description"><?php echo $newsis_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                <?php endif; ?>
            </div><!-- .site-branding -->
         <?php
     }
    add_action( 'newsis_header__site_branding_section_hook', 'newsis_header_site_branding_part', 10 );
 endif;

 if( ! function_exists( 'newsis_header_ads_banner_part' ) ) :
    /**
     * Header ads banner element
     * 
     * @since 1.0.0
     */
     function newsis_header_ads_banner_part() {
        if( ! NI\newsis_get_multiselect_tab_option( 'header_ads_banner_responsive_option' ) ) return;
        $header_ads_banner_type = NI\newsis_get_customizer_option( 'header_ads_banner_type' );
        if( $header_ads_banner_type == 'none' ) return;
        $header_ads_banner_custom_image = NI\newsis_get_customizer_option( 'header_ads_banner_custom_image' );
        $header_ads_banner_custom_url = NI\newsis_get_customizer_option( 'header_ads_banner_custom_url' );
        if( ! empty( $header_ads_banner_custom_image ) ) :
            ?>
                <div class="ads-banner">
                    <a href="<?php echo esc_url( $header_ads_banner_custom_url ); ?>" target="_blank">
                        <img src="<?php echo wp_get_attachment_url( $header_ads_banner_custom_image ); ?>">
                    </a>
                </div><!-- .ads-banner -->
            <?php
        endif;
     }
    add_action( 'newsis_after_header_hook', 'newsis_header_ads_banner_part', 10 );
 endif;

 if( ! function_exists( 'newsis_header_newsletter_part' ) ) :
    /**
     * Header newsletter element
     * 
     * @since 1.0.0
     */
     function newsis_header_newsletter_part() {
        if( ! NI\newsis_get_customizer_option( 'header_newsletter_option' ) ) return;
        $newsletter_label = NI\newsis_get_customizer_option( 'newsletter_label' );
        $newsletter_icon_picker = NI\newsis_get_customizer_option( 'newsletter_icon_picker' );
        $header_newsletter_redirect_href_target = NI\newsis_get_customizer_option( 'header_newsletter_redirect_href_target' );
        $header_newsletter_redirect_href_link = NI\newsis_get_customizer_option( 'header_newsletter_redirect_href_link' );
        $header_newsletter_show_border = NI\newsis_get_customizer_option( 'header_newsletter_show_border' );
        $header_newsletter_show_hover_animation = NI\newsis_get_customizer_option( 'header_newsletter_show_hover_animation' );
        $elementClass = 'newsletter-element';
        if( $header_newsletter_show_border ) $elementClass .= ' newsis-show-border';
        if( $header_newsletter_show_hover_animation ) $elementClass .= ' newsis-show-hover-animation';
        ?>
            <div class="<?php echo esc_html( $elementClass ); ?>" <?php if( isset($newsletter_label) && !empty($newsletter_label) ) echo 'title="' . esc_attr( $newsletter_label ) . '"'; ?>>
                <a href="<?php echo esc_url( $header_newsletter_redirect_href_link ); ?>" target="<?php echo esc_attr( $header_newsletter_redirect_href_target ); ?>">
                    <?php
                        if( isset( $newsletter_icon_picker['value'] ) && ! empty( $newsletter_icon_picker['value'] ) ) echo '<span class="title-icon"><i class="' .esc_attr( $newsletter_icon_picker['value'] ). '"></i></span>';
                        if( isset( $newsletter_label ) && !empty( isset( $newsletter_label ) ) ) echo '<span class="title-text">' .esc_html( $newsletter_label ). '</span>';
                    ?>
                </a>
            </div><!-- .newsletter-element -->
        <?php
     }
    add_action( 'newsis_header__site_branding_section_hook', function() {
        if( ! NI\newsis_get_customizer_option( 'header_newsletter_option' ) && ! NI\newsis_get_customizer_option( 'header_random_news_option' ) ) return;
        echo '<div class="header-right-button-wrap">';
    }, 29 );
    add_action( 'newsis_header__site_branding_section_hook', 'newsis_header_newsletter_part', 30 );
 endif;

 if( ! function_exists( 'newsis_header_random_news_part' ) ) :
    /**
     * Header random news element
     * 
     * @since 1.0.0
     */
     function newsis_header_random_news_part() {
        if( ! NI\newsis_get_customizer_option( 'header_random_news_option' ) ) return;
        $random_news_icon_picker = NI\newsis_get_customizer_option( 'random_news_icon_picker' );
        $random_news_label = NI\newsis_get_customizer_option( 'random_news_label' );
        $header_random_news_redirect_href_target = NI\newsis_get_customizer_option( 'header_random_news_redirect_href_target' );
        $button_url = newsis_get_random_news_url();
        ?>
            <div class="random-news-element" <?php if( isset( $random_news_label ) && !empty( $random_news_label ) ) echo 'title="' . esc_attr( $random_news_label ) . '"'; ?>>
                <a href="<?php echo esc_url($button_url); ?>" target="<?php echo esc_attr( $header_random_news_redirect_href_target ); ?>">
                    <?php
                        if( isset( $random_news_icon_picker['value'] ) && !empty( $random_news_icon_picker['value'] ) ) echo '<span class="title-icon"><i class="' .esc_attr( $random_news_icon_picker['value'] ). '"></i></span>';
                        if( isset( $random_news_label ) && !empty( $random_news_label ) ) echo '<span class="title-text">' .esc_html( $random_news_label ). '</span>';
                    ?>
                </a>
            </div><!-- .random-news-element -->
        <?php
     }
    add_action( 'newsis_header__site_branding_section_hook', 'newsis_header_random_news_part', 30 );
    add_action( 'newsis_header__site_branding_section_hook', function() {
        if( ! NI\newsis_get_customizer_option( 'header_newsletter_option' ) && ! NI\newsis_get_customizer_option( 'header_random_news_option' ) ) return;
        echo '</div><!-- .header-right-button-wrap -->';
    }, 31 );
 endif;

 if( ! function_exists( 'newsis_header_sidebar_toggle_part' ) ) :
    /**
     * Header off canvas element
     * 
     * @since 1.0.0
     */
     function newsis_header_sidebar_toggle_part() {
         if( ! NI\newsis_get_customizer_option( 'header_off_canvas_option' ) ) return;
         ?>
            <div class="sidebar-toggle-wrap">
                <a class="off-canvas-trigger" href="javascript:void(0);">
                    <div class="newsis_sidetoggle_menu_burger">
                      <span></span>
                      <span></span>
                      <span></span>
                  </div>
                </a>
                <div class="sidebar-toggle hide">
                <span class="off-canvas-close"><i class="fas fa-times"></i></span>
                  <div class="newsis-container">
                    <div class="row">
                      <?php dynamic_sidebar( 'off-canvas-sidebar' ); ?>
                    </div>
                  </div>
                </div>
            </div>
         <?php
     }     
    add_action( 'newsis_header__menu_section_hook', 'newsis_header_sidebar_toggle_part', 40 );
 endif;

 if( ! function_exists( 'newsis_header_menu_part' ) ) :
    /**
     * Header menu element
     * 
     * @since 1.0.0
     */
    function newsis_header_menu_part() {
      ?>
        <nav id="site-navigation" class="main-navigation <?php echo esc_attr( 'hover-effect--' . NI\newsis_get_customizer_option( 'header_menu_hover_effect' ) ); ?>">
            <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                <div id="newsis_menu_burger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <span class="menu_txt"><?php esc_html_e( 'Menu', 'newsis' ); ?></span></button>
            <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'menu-2',
                        'menu_id'        => 'header-menu',
                    )
                );
            ?>
        </nav><!-- #site-navigation -->
      <?php
    }
    add_action( 'newsis_header__menu_section_hook', 'newsis_header_menu_part', 30 );
 endif;

 if( ! function_exists( 'newsis_header_search_part' ) ) :
   /**
    * Header search element
    * 
    * @since 1.0.0
    */
    function newsis_header_search_part() {
        if( ! NI\newsis_get_customizer_option( 'header_search_option' ) ) return;
        ?>
            <div class="search-wrap">
                <button class="search-trigger">
                    <i class="fas fa-search"></i>
                </button>
                <div class="search-form-wrap hide">
                    <?php echo get_search_form(); ?>
                </div>
                <div class="search_close_btn hide"><i class="fas fa-times"></i></div>
            </div>
        <?php
    }
    add_action( 'newsis_header__menu_section_hook', 'newsis_header_search_part', 50 );
endif;

if( ! function_exists( 'newsis_header_theme_mode_icon_part' ) ) :
    /**
     * Header theme mode element
     * 
     * @since 1.0.0
     */
     function newsis_header_theme_mode_icon_part() {
        if( ! NI\newsis_get_customizer_option( 'header_theme_mode_toggle_option' ) ) return;
        $theme_mode = array_key_exists( 'themeMode', $_COOKIE ) ? $_COOKIE['themeMode'] : '';
        $newsis_dark_mode = ( $theme_mode == 'dark' ) ? true : false;
        ?>
            <div class="blaze-switcher-button<?php echo $newsis_dark_mode ? ' active' : ''; ?>">
                <div class="blaze-switcher-button-inner-left"></div>
                <div class="blaze-switcher-button-inner"></div>
            </div>
        <?php
     }
    add_action( 'newsis_header__menu_section_hook', 'newsis_header_theme_mode_icon_part', 60 );
 endif;

 if( ! function_exists( 'newsis_header_custom_button_part' ) ) :
    /**
     * Header theme mode element
     * 
     * @since 1.0.0
     */
     function newsis_header_custom_button_part() {
        if( ! NI\newsis_get_customizer_option( 'theme_header_custom_button_option' ) ) return;
        $header_custom_button_redirect_href_link = NI\newsis_get_customizer_option( 'header_custom_button_redirect_href_link' );
        $header_custom_button_label = NI\newsis_get_customizer_option( 'header_custom_button_label' );
        $header_custom_button_icon_picker = NI\newsis_get_customizer_option( 'header_custom_button_icon_picker' );
        $elementClass = 'header-custom-button';
        ?>
            <a class="<?php echo esc_attr( $elementClass ); ?>" href="<?php echo esc_url($header_custom_button_redirect_href_link); ?>" target="_blank">
                <?php if( $header_custom_button_icon_picker['type'] == 'icon' && $header_custom_button_icon_picker['value'] != "fas fa-ban" ) : ?>
                    <span class="icon">
                        <i class="<?php echo esc_attr( $header_custom_button_icon_picker['value'] ); ?>"></i>
                    </span>
                <?php endif;
                if( $header_custom_button_label ) :
                ?>
                    <span class="ticker_label_title_string"><?php echo esc_html( $header_custom_button_label ); ?></span>
                <?php endif; ?>
            </a>
        <?php
     }
    add_action( 'newsis_header__menu_section_hook', 'newsis_header_custom_button_part', 70 );
 endif;

 if( ! function_exists( 'newsis_ticker_news_part' ) ) :
    /**
     * Ticker news element
     * 
     * @since 1.0.0
     */
     function newsis_ticker_news_part() {
        $ticker_news_visible = NI\newsis_get_customizer_option( 'ticker_news_visible' );
        if( $ticker_news_visible === 'none' ) return;
        if( $ticker_news_visible === 'front-page' && ! is_front_page() ) {
            return;
        } else if( $ticker_news_visible === 'innerpages' && is_front_page()  ) {
            return;
        }        
        $ticker_news_order_by = NI\newsis_get_customizer_option( 'ticker_news_order_by' );
        $orderArray = explode( '-', $ticker_news_order_by );
        $ticker_args = array(
            'order' => esc_html( $orderArray[1] ),
            'orderby' => esc_html( $orderArray[0] )
        );
        $ticker_args['posts_per_page'] = 8;
        $ticker_news_categories = json_decode( NI\newsis_get_customizer_option( 'ticker_news_categories' ) );
        if( NI\newsis_get_customizer_option( 'ticker_news_date_filter' ) != 'all' ) $ticker_args['date_query'] = newsis_get_date_format_array_args(NI\newsis_get_customizer_option( 'ticker_news_date_filter' ));
        if( $ticker_news_categories ) $ticker_args['cat'] = newsis_get_categories_for_args($ticker_news_categories);
        $ticker_news_posts = json_decode(NI\newsis_get_customizer_option( 'ticker_news_posts' ));
        if( $ticker_news_posts ) $ticker_args['post__in'] = newsis_get_post_id_for_args($ticker_news_posts);
         ?>
            <div class="ticker-news-wrap newsis-ticker layout--two">
                <?php
                    $ticker_news_title = NI\newsis_get_customizer_option( 'ticker_news_title' );
                    $ticker_news_title_icon = NI\newsis_get_customizer_option( 'ticker_news_title_icon' );
                ?>
                <?php if( $ticker_news_title_icon['type'] != 'none' || $ticker_news_title ) : ?>
                    <div class="ticker_label_title ticker-title newsis-ticker-label">
                        <?php 
                            if( $ticker_news_title_icon['type'] == 'icon' ) echo '<span class="icon"><i class="' .esc_attr( $ticker_news_title_icon['value'] ). '"></i></span>';
                            if( $ticker_news_title ) :
                            ?>
                                <span class="ticker_label_title_string"><?php echo esc_html( $ticker_news_title ); ?></span>
                            <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="newsis-ticker-box">
                  <?php
                    $newsis_direction = 'left';
                    $newsis_dir = 'ltr';
                    if( is_rtl() ){
                      $newsis_direction = 'right';
                      $newsis_dir = 'ltr';
                    }
                  ?>

                    <ul class="ticker-item-wrap" direction="<?php echo esc_attr($newsis_direction); ?>" dir="<?php echo esc_attr($newsis_dir); ?>">
                        <?php get_template_part( 'template-parts/ticker-news/template', 'two', $ticker_args ); ?>
                    </ul>
                </div>
            </div>
         <?php
     }
    add_action( 'newsis_after_header_hook', 'newsis_ticker_news_part', 10 );
 endif;