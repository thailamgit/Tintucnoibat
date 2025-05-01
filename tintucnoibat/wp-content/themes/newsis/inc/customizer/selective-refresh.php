<?php
/**
 * Includes functions for selective refresh
 * 
 * @package Newsis
 * @since 1.0.0
 */
use Newsis\CustomizerDefault as NI;
if( ! function_exists( 'newsis_customize_selective_refresh' ) ) :
    /**
     * Adds partial refresh for the customizer preview
     * 
     */
    function newsis_customize_selective_refresh( $wp_customize ) {
        if ( ! isset( $wp_customize->selective_refresh ) ) return;
        // top header show hide
        $wp_customize->selective_refresh->add_partial(
            'top_header_option',
            array(
                'selector'        => '#masthead .top-header',
                'render_callback' => 'newsis_top_header_html'
            )
        );
        // top header social icons show hide
        $wp_customize->selective_refresh->add_partial(
            'top_header_social_option',
            array(
                'selector'        => '#masthead .top-header .social-icons-wrap',
                'render_callback' => 'newsis_top_header_social_part_selective_refresh'
            )
        );
        // header off canvas show hide
        $wp_customize->selective_refresh->add_partial(
            'header_off_canvas_option',
            array(
                'selector'        => '#masthead .sidebar-toggle-wrap',
                'render_callback' => 'newsis_header_sidebar_toggle_part_selective_refresh'
            )
        );
        // header search icon show hide
        $wp_customize->selective_refresh->add_partial(
            'header_search_option',
            array(
                'selector'        => '#masthead .search-wrap',
                'render_callback' => 'newsis_header_search_part_selective_refresh'
            )
        );
        // theme mode toggle show hide
        $wp_customize->selective_refresh->add_partial(
            'header_theme_mode_toggle_option',
            array(
                'selector'        => '#masthead .mode_toggle_wrap',
                // 'render_callback' => 'newsis_header_theme_mode_icon_part_selective_refresh'
            )
        );
        // site title
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            array(
                'selector'        => '.site-title a',
                'render_callback' => 'newsis_customize_partial_blogname',
            )
        );
        // site description
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector'        => '.site-description',
                'render_callback' => 'newsis_customize_partial_blogdescription',
            )
        );
        
        // social icons target attribute
        $wp_customize->selective_refresh->add_partial(
            'social_icons_target',
            array(
                'selector'        => '.top-header .social-icons-wrap',
                'render_callback' => 'newsis_customizer_social_icons',
            )
        );

        // social icons
        $wp_customize->selective_refresh->add_partial(
            'social_icons',
            array(
                'selector'        => '.top-header .social-icons-wrap',
                'render_callback' => 'newsis_customizer_social_icons',
            )
        );

        // post read more button label
        $wp_customize->selective_refresh->add_partial(
            'global_button_label',
            array(
                'selector'        => 'article .post-link-button',
                'render_callback' => 'newsis_customizer_read_more_button',
            )
        );
        
        // post read more button label
        $wp_customize->selective_refresh->add_partial(
            'global_button_icon_picker',
            array(
                'selector'        => 'article .post-link-button',
                'render_callback' => 'newsis_customizer_read_more_button',
            )
        );

        // scroll to top icon picker
        $wp_customize->selective_refresh->add_partial(
            'stt_icon_picker',
            array(
                'selector'        => '#newsis-scroll-to-top',
                'render_callback' => 'newsis_customizer_stt_button',
            )
        );

        // ticker news title icon
        $wp_customize->selective_refresh->add_partial(
            'ticker_news_title_icon',
            array(
                'selector'        => '.ticker-news-wrap .ticker_label_title',
                'render_callback' => 'newsis_customizer_ticker_label',
            )
        );

        // ticker news title
        $wp_customize->selective_refresh->add_partial(
            'ticker_news_title',
            array(
                'selector'        => '.ticker-news-wrap .ticker_label_title',
                'render_callback' => 'newsis_customizer_ticker_label',
            )
        );

        // newsletter icon picker
        $wp_customize->selective_refresh->add_partial(
            'newsletter_icon_picker',
            array(
                'selector'        => '.newsletter-element',
                'render_callback' => 'newsis_customizer_newsletter_button_label',
            )
        );

        // newsletter label
        $wp_customize->selective_refresh->add_partial(
            'newsletter_label',
            array(
                'selector'        => '.newsletter-element',
                'render_callback' => 'newsis_customizer_newsletter_button_label',
            )
        );

        // random news icon picker
        $wp_customize->selective_refresh->add_partial(
            'random_news_icon_picker',
            array(
                'selector'        => '.random-news-element',
                'render_callback' => 'newsis_customizer_random_news_button_label',
            )
        );

        // random news label
        $wp_customize->selective_refresh->add_partial(
            'random_news_label',
            array(
                'selector'        => '.random-news-element',
                'render_callback' => 'newsis_customizer_random_news_button_label',
            )
        );

        // single post related posts option
        $wp_customize->selective_refresh->add_partial(
            'single_post_related_posts_option',
            array(
                'selector'        => '.single-related-posts-section-wrap',
                'render_callback' => 'newsis_single_related_posts',
            )
        );
        
        // footer option
        $wp_customize->selective_refresh->add_partial(
            'footer_option',
            array(
                'selector'        => 'footer .main-footer',
                'render_callback' => 'newsis_footer_sections_html',
                'container_inclusive'=> true
            )
        );

        // footer column option
        $wp_customize->selective_refresh->add_partial(
            'footer_widget_column',
            array(
                'selector'        => 'footer .main-footer',
                'render_callback' => 'newsis_footer_sections_html',
            )
        );

        // bottom footer option
        $wp_customize->selective_refresh->add_partial(
            'bottom_footer_option',
            array(
                'selector'        => 'footer .bottom-footer',
                'render_callback' => 'newsis_bottom_footer_sections_html',
            )
        );

        // bottom footer menu option
        $wp_customize->selective_refresh->add_partial(
            'bottom_footer_menu_option',
            array(
                'selector'        => 'footer .bottom-footer .bottom-menu',
                'render_callback' => 'newsis_bottom_footer_menu_part_selective_refresh',
            )
        );

        // bottom footer menu option
        $wp_customize->selective_refresh->add_partial(
            'bottom_footer_social_option',
            array(
                'selector'        => 'footer .bottom-footer .social-icons-wrap',
                'render_callback' => 'newsis_botttom_footer_social_part_selective_refresh',
            )
        );

        // custom button label
        $wp_customize->selective_refresh->add_partial(
            'header_custom_button_label',
            array(
                'selector'        => '.header-custom-button',
                'render_callback' => 'newsis_custom_button_selective_refresh',
            )
        );

        // custom button icon picker
        $wp_customize->selective_refresh->add_partial(
            'header_custom_button_icon_picker',
            array(
                'selector'        => '.header-custom-button',
                'render_callback' => 'newsis_custom_button_selective_refresh',
            )
        );
    }
    add_action( 'customize_register', 'newsis_customize_selective_refresh' );
endif;

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function newsis_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function newsis_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

// global button label
function newsis_customizer_read_more_button() {
    $global_button_label = NI\newsis_get_customizer_option( 'global_button_label' );
    $global_button_icon_picker = NI\newsis_get_customizer_option( 'global_button_icon_picker' );
    return ( esc_html( $global_button_label ) . '<i class="' .esc_attr( $global_button_icon_picker['value'] ). '"></i>' );
}

// scroll to top button label
function newsis_customizer_stt_button() {
    $stt_icon_picker = NI\newsis_get_customizer_option( 'stt_icon_picker' );
    if( $stt_icon_picker['value'] == 'fa-solid fa-ban' ) return;
    return( '<span class="icon-holder"><i class="' .esc_attr( $stt_icon_picker['value'] ). '"></i></span>' );
}

// ticker label latest tab
function newsis_customizer_ticker_label() {
    $ticker_news_title = NI\newsis_get_customizer_option( 'ticker_news_title' );
    $ticker_news_title_icon = NI\newsis_get_customizer_option( 'ticker_news_title_icon' );
    $partial_string = '';
    if( $ticker_news_title_icon['type'] == 'icon' ) $partial_string =  '<span class="icon"><i class="' .esc_attr( $ticker_news_title_icon['value'] ). '"></i></span>';
    return ( $partial_string .= '<span class="ticker_label_title_string">' .esc_html( $ticker_news_title ). '</span>' );
}

// newsletter button label
function newsis_customizer_newsletter_button_label() {
    $newsletter_icon_picker = NI\newsis_get_customizer_option( 'newsletter_icon_picker' );
    $newsletter_label = NI\newsis_get_customizer_option( 'newsletter_label' );
    ob_start();
        if( isset( $newsletter_icon_picker['value'] ) ) echo '<span class="title-icon"><i class="' .esc_attr( $newsletter_icon_picker['value'] ). '"></i></span>';
        if( isset( $newsletter_label ) ) echo '<span class="title-text">' .esc_html( $newsletter_label ). '</span>';
    $content = ob_get_clean();
    return $content;
}

// random news button label
function newsis_customizer_random_news_button_label() {
    $random_news_label = NI\newsis_get_customizer_option( 'random_news_label' );
    $random_news_icon_picker = NI\newsis_get_customizer_option( 'random_news_icon_picker' );
    ob_start();
        if( isset( $random_news_icon_picker['value'] ) ) echo '<span class="title-icon"><i class="' .esc_attr( $random_news_icon_picker['value'] ). '"></i></span>';
        if( isset( $random_news_label ) ) echo '<span class="title-text">' .esc_html( $random_news_label ). '</span>';
    $content = ob_get_clean();
    return $content;
}

// top header social icons part
function newsis_top_header_social_part_selective_refresh() {
    if( ! NI\newsis_get_customizer_option( 'top_header_social_option' ) ) return;
    ?>
       <div class="social-icons-wrap">
          <?php newsis_customizer_social_icons(); ?>
       </div>
    <?php
}

function newsis_header_sidebar_toggle_part_selective_refresh() {
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
             <div class="newsis-container">
               <div class="row">
                 <?php dynamic_sidebar( 'off-canvas-sidebar' ); ?>
               </div>
             </div>
           </div>
       </div>
    <?php
}

function newsis_header_search_part_selective_refresh() {
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

// bottom footer menu part
function newsis_bottom_footer_menu_part_selective_refresh() {
    if( ! NI\newsis_get_customizer_option( 'bottom_footer_menu_option' ) ) return;
    ?>
       <div class="bottom-menu">
          <?php
          if( has_nav_menu( 'menu-3' ) ) :
             wp_nav_menu(
                array(
                   'theme_location' => 'menu-3',
                   'menu_id'        => 'bottom-footer-menu',
                   'depth' => 1
                )
             );
             else :
                if ( is_user_logged_in() && current_user_can( 'edit_theme_options' ) ) {
                   ?>
                      <a href="<?php echo esc_url( admin_url( '/nav-menus.php?action=locations' ) ); ?>"><?php esc_html_e( 'Setup Bottom Footer Menu', 'newsis' ); ?></a>
                   <?php
                }
             endif;
          ?>
       </div>
    <?php
 }

// bottom footer social icons part
function newsis_botttom_footer_social_part_selective_refresh() {
    if( ! NI\newsis_get_customizer_option( 'bottom_footer_social_option' ) ) return;
    ?>
       <div class="social-icons-wrap">
          <?php newsis_customizer_social_icons(); ?>
       </div>
    <?php
}

 // custom button selective refresh
 function newsis_custom_button_selective_refresh() {
    $header_custom_button_label = NI\newsis_get_customizer_option( 'header_custom_button_label' );
    $header_custom_button_icon_picker = NI\newsis_get_customizer_option( 'header_custom_button_icon_picker' );
    return ( 
        '<span class="icon"><i class="' .esc_attr( $header_custom_button_icon_picker['value'] ). '"></i></span><span class="ticker_label_title_string">'. esc_attr( $header_custom_button_label ) .'</span>'
    );
 }