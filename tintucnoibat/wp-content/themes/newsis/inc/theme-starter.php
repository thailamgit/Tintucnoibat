<?php
/**
 * Includes theme defaults and starter functions
 * 
 * @package Newsis
 * @since 1.0.0
 */
 namespace Newsis\CustomizerDefault;

 if( !function_exists( 'newsis_get_customizer_option' ) ) :
    /**
     * Gets customizer "theme_mods" value
     * 
     * @package Newsis
     * @since 1.0.0
     * 
     */
    function newsis_get_customizer_option( $key ) {
        return get_theme_mod( $key, newsis_get_customizer_default( $key ) );
    }
 endif;

 if( !function_exists( 'newsis_get_multiselect_tab_option' ) ) :
    /**
     * Gets customizer "multiselect combine tab" value
     * 
     * @package Newsis
     * @since 1.0.0
     */
    function newsis_get_multiselect_tab_option( $key ) {
        $value = newsis_get_customizer_option( $key );
        if( !$value["desktop"] && !$value["tablet"] && !$value["mobile"] ) return apply_filters( "newsis_get_multiselect_tab_option", false );
        return apply_filters( "newsis_get_multiselect_tab_option", true );
    }
 endif;

 if( !function_exists( 'newsis_get_customizer_default' ) ) :
    /**
     * Gets customizer "theme_mods" value
     * 
     * @package Newsis
     * @since 1.0.0
     */
    function newsis_get_customizer_default($key) {
        $array_defaults = apply_filters( 'newsis_get_customizer_defaults', array(
            'theme_color'   => '#448bef',
            'site_background_color'  => json_encode(array(
                'type'  => 'solid',
                'solid' => '#F0F1F2',
                'gradient'  => null
            )),
            'global_button_icon_picker' => [
                'type'  => 'icon',
                'value' => 'fas fa-angle-right',
            ],
            'global_button_label'   =>  esc_html__( 'Read More', 'newsis' ),
            'global_button_typo'    => array(
                'font_family'   => array( 'value' => 'Noto Sans JP', 'label' => 'Noto Sans JP' ),
                'font_weight'   => array( 'value' => '500', 'label' => 'Medium 500' ),
                'font_size'   => array(
                    'desktop' => 14,
                    'tablet' => 14,
                    'smartphone' => 14
                ),
                'line_height'   => array(
                    'desktop' => 21,
                    'tablet' => 21,
                    'smartphone' => 21
                ),
                'letter_spacing'   => array(
                    'desktop' => 0,
                    'tablet' => 0,
                    'smartphone' => 0
                ),
                'text_transform'    => 'unset',
                'text_decoration'    => 'none',
            ),
            'global_button_font_size'    => array(
                'desktop'   => 10,
                'tablet'    => 10,
                'smartphone'    => 10
            ),
            'preloader_option'  => false,
            'preloader_type'  => 5,
            'website_layout'    => 'full-width--layout',
            'website_content_layout'    => 'boxed--layout',
            'website_block_title_layout'    => 'layout-four',
            'website_date_before_icon' => [
                'type'  => 'icon',
                'value' => 'far fa-calendar',
            ],
            'website_author_before_icon' => [
                'type'  => 'icon',
                'value' => 'far fa-user-circle',
            ],
            'website_comments_before_icon' => [
                'type'  => 'icon',
                'value' => 'far fa-comment',
            ],
            'website_read_time_before_icon' => [
                'type'  => 'icon',
                'value' => 'fas fa-clock',
            ],
            'card_settings_option'  =>  true,
            'card_box_shadow_control'   =>  array(
                'option'    => 'adjust',
                'hoffset'   => 0,
                'voffset'   => 0,
                'blur'  => 4,
                'spread'    => 0,
                'type'  => 'outset',
                'color' => 'rgb(0 0 0 / 8%)'
            ),
            'card_hover_box_shadow'   =>  array(
                'option'    => 'adjust',
                'hoffset'   => -2,
                'voffset'   => 6,
                'blur'  => 15,
                'spread'    => 0,
                'type'  => 'outset',
                'color' => 'rgb(0 0 0 / 20%)'
            ),
            'frontpage_sidebar_layout'  => 'right-sidebar',
            'frontpage_sidebar_sticky_option'    => false,
            'archive_sidebar_layout'    => 'right-sidebar',
            'archive_sidebar_sticky_option'    => false,
            'single_sidebar_layout' => 'right-sidebar',
            'single_sidebar_sticky_option'    => false,
            'page_sidebar_layout'   => 'right-sidebar',
            'page_sidebar_sticky_option'    => false,
            'preset_color_1'    => '#64748b',
            'preset_color_2'    => '#27272a',
            'preset_color_3'    => '#ef4444',
            'preset_color_4'    => '#eab308',
            'preset_color_5'    => '#84cc16',
            'preset_color_6'    => '#22c55e',
            'preset_color_7'    => '#06b6d4',
            'preset_color_8'    => '#0284c7',
            'preset_color_9'    => '#6366f1',
            'preset_color_10'    => '#84cc16',
            'preset_color_11'    => '#a855f7',
            'preset_color_12'    => '#f43f5e',
            'preset_gradient_1'   => 'linear-gradient( 135deg, #485563 10%, #29323c 100%)',
            'preset_gradient_2' => 'linear-gradient( 135deg, #FF512F 10%, #F09819 100%)',
            'preset_gradient_3'  => 'linear-gradient( 135deg, #00416A 10%, #E4E5E6 100%)',
            'preset_gradient_4'   => 'linear-gradient( 135deg, #CE9FFC 10%, #7367F0 100%)',
            'preset_gradient_5' => 'linear-gradient( 135deg, #90F7EC 10%, #32CCBC 100%)',
            'preset_gradient_6'  => 'linear-gradient( 135deg, #81FBB8 10%, #28C76F 100%)',
            'preset_gradient_7'   => 'linear-gradient( 135deg, #EB3349 10%, #F45C43 100%)',
            'preset_gradient_8' => 'linear-gradient( 135deg, #FFF720 10%, #3CD500 100%)',
            'preset_gradient_9'  => 'linear-gradient( 135deg, #FF96F9 10%, #C32BAC 100%)',
            'preset_gradient_10'   => 'linear-gradient( 135deg, #69FF97 10%, #00E4FF 100%)',
            'preset_gradient_11' => 'linear-gradient( 135deg, #3C8CE7 10%, #00EAFF 100%)',
            'preset_gradient_12'  => 'linear-gradient( 135deg, #FF7AF5 10%, #513162 100%)',
            'post_title_hover_effects'  => 'two',
            'site_image_hover_effects'  => 'none',
            'post_block_hover_effects'  => 'one',
            'site_breadcrumb_option'    => true,
            'site_breadcrumb_type'  => 'default',
            'site_breadcrumb_hook_on'   => 'main_container',
            'site_schema_ready' => true,
            'site_date_format'  => 'default',
            'site_date_to_show' => 'published',
            'site_title_hover_textcolor'=> '#448bef',
            'site_description_color'    => '#5c5c5c',
            'homepage_content_order'    => array( 
                array( 'value'  => 'full_width_section', 'option'   => false ),
                array( 'value'  => 'leftc_rights_section', 'option'    => false ),
                array( 'value'   => 'lefts_rightc_section', 'option' => false ),
                array( 'value'   => 'latest_posts', 'option'    => true ),
                array( 'value' => 'bottom_full_width_section', 'option'  => true )
            ),
            'newsis_site_logo_width'    => array(
                'desktop'   => 230,
                'tablet'    => 200,
                'smartphone'    => 200
            ),
            'site_title_typo'    => array(
                'font_family'   => array( 'value' => 'Frank Ruhl Libre', 'label' => 'Frank Ruhl Libre' ),
                'font_weight'   => array( 'value' => '500', 'label' => 'Medium 500' ),
                'font_size'   => array(
                    'desktop' => 45,
                    'tablet' => 43,
                    'smartphone' => 40
                ),
                'line_height'   => array(
                    'desktop' => 45,
                    'tablet' => 42,
                    'smartphone' => 40,
                ),
                'letter_spacing'   => array(
                    'desktop' => 0,
                    'tablet' => 0,
                    'smartphone' => 0
                ),
                'text_transform'    => 'unset',
                'text_decoration'    => 'none',
            ),
            'site_tagline_typo'    => array(
                'font_family'   => array( 'value' => 'Noto Sans JP', 'label' => 'Noto Sans JP' ),
                'font_weight'   => array( 'value' => '400', 'label' => 'Light 400' ),
                'font_size'   => array(
                    'desktop' => 14,
                    'tablet' => 14,
                    'smartphone' => 14
                ),
                'line_height'   => array(
                    'desktop' => 15,
                    'tablet' => 15,
                    'smartphone' => 15
                ),
                'letter_spacing'   => array(
                    'desktop' => 0,
                    'tablet' => 0,
                    'smartphone' => 0
                ),
                'text_transform'    => 'capitalize',
                'text_decoration'    => 'none',
            ),
            'top_header_option' => true,
            'top_header_date_time_option'   => true,
            'top_header_right_content_type' => 'ticker-news',
            'top_header_menu_option' => true,
            'top_header_ticker_news_option' => true,
            'top_header_ticker_news_categories' => '[]',
            'top_header_ticker_news_posts' => '[]',
            'top_header_ticker_news_date_filter' => 'all',
            'top_header_social_option'  => true,
            'top_header_social_icons_hover_animation'  => true,            
            'top_header_background_color_group' => json_encode(array(
                'type'  => 'solid',
                'solid' => '#ffff',
                'gradient'  => null
            )),
            'header_custom_button_icon_picker' => [
                'type'  => 'icon',
                'value' => 'fab fa-youtube',
            ],
            'header_custom_button_label'   =>  esc_html__( 'Live Now', 'newsis' ),
            'header_custom_button_redirect_href_link'  => '',
            'custom_button_icon_size'   => array(
                'desktop' => 11,
                'tablet' => 11,
                'smartphone' => 11
            ),
            'custom_button_text_typo'    => array(
                'font_family'   => array( 'value' => 'Noto Sans JP', 'label' => 'Noto Sans JP' ),
                'font_weight'   => array( 'value' => '500', 'label' => 'Medium 500' ),
                'font_size'   => array(
                    'desktop' => 13,
                    'tablet' => 13,
                    'smartphone' => 13
                ),
                'line_height'   => array(
                    'desktop' => 30,
                    'tablet' => 30,
                    'smartphone' => 30
                ),
                'letter_spacing'   => array(
                    'desktop' => 0,
                    'tablet' => 0,
                    'smartphone' => 0
                ),
                'text_transform'    => 'unset',
                'text_decoration'    => 'none',
            ),
            'theme_header_live_search_option'   => true,
            'theme_header_live_search_button_label'  => esc_html__( 'View all results', 'newsis' ),
            'theme_header_live_search_button_target'    => '_blank',
            'header_newsletter_option'   => true,
            'newsletter_icon_picker' => [
                'type'  => 'icon',
                'value' => 'fas fa-ban',
            ],
            'newsletter_label'   =>  esc_html__( 'Subscribe', 'newsis' ),
            'header_newsletter_redirect_href_target'    => '_blank',
            'header_newsletter_redirect_href_link'  => '',
            'header_newsletter_show_border' =>  true,
            'header_newsletter_show_hover_animation' =>  false,
            'header_random_news_option'   => true,
            'random_news_icon_picker' => [
                'type'  => 'icon',
                'value' => 'fas fa-random',
            ],
            'random_news_label'   =>  esc_html__( 'Random News', 'newsis' ),
            'header_random_news_redirect_href_target'    => '_blank',
            'header_ads_banner_responsive_option'  => array(
                'desktop'   => true,
                'tablet'   => true,
                'mobile'   => true
            ),
            'header_ads_banner_type'    => 'custom',
            'header_ads_banner_custom_image'  => '',
            'header_ads_banner_custom_url'  => '',
            'header_off_canvas_option'  => false,
            'header_search_option'  => false,
            'header_theme_mode_toggle_option'  => true,
            'theme_header_sticky'  => false,
            'theme_header_sticky_on_scroll_down'  => false,
            'theme_header_custom_button_option'  => false,
            'header_width_layout'   => 'contain',
            'header_vertical_padding'   => array(
                'desktop' => 30,
                'tablet' => 20,
                'smartphone' => 20
            ),
            'header_off_canvas_toggle_color' => array( 'color' => '#fff', 'hover' => '#fff' ),
            'header_background_color_group' => json_encode(array(
                'type'  => 'solid',
                'solid' => '',
                'gradient'  => null,
                'image'     => array( 'media_id' => 0, 'media_url' => '' )
            )),
            'header_menu_hover_effect'  => 'none',
            'header_menu_color'    => array( 'color' => '#fff', 'hover' => '#fff' ),
            'header_active_menu_color'  => "#fff",
            'header_menu_background_color_group' => json_encode(array(
                'type'  => 'solid',
                'solid' => '#000',
                'gradient'  => null
            )),
            'header_mobile_menu_button_color'  => "#fff",
            'header_mobile_menu_text_color'  => "#fff",
            'header_mobile_menu_background_color'  => json_encode(array(
                'type'  => 'solid',
                'solid' => '#000',
                'gradient'  => null
            )),
            'header_menu_typo'    => array(
                'font_family'   => array( 'value' => 'Noto Sans JP', 'label' => 'Noto Sans JP' ),
                'font_weight'   => array( 'value' => '500', 'label' => 'Medium 500' ),
                'font_size'   => array(
                    'desktop' => 16,
                    'tablet' => 16,
                    'smartphone' => 16
                ),
                'line_height'   => array(
                    'desktop' => 24,
                    'tablet' => 24,
                    'smartphone' => 24,
                ),
                'letter_spacing'   => array(
                    'desktop' => 0,
                    'tablet' => 0,
                    'smartphone' => 0
                ),
                'text_transform'    => 'capitalize',
                'text_decoration'    => 'none',
            ),
            'header_sub_menu_typo'    => array(
                'font_family'   => array( 'value' => 'Noto Sans JP', 'label' => 'Noto Sans JP' ),
                'font_weight'   => array( 'value' => '500', 'label' => 'Bold 500' ),
                'font_size'   => array(
                    'desktop' => 14,
                    'tablet' => 14,
                    'smartphone' => 14
                ),
                'line_height'   => array(
                    'desktop' => 24,
                    'tablet' => 24,
                    'smartphone' => 24,
                ),
                'letter_spacing'   => array(
                    'desktop' => 0,
                    'tablet' => 0,
                    'smartphone' => 0
                ),
                'text_transform'    => 'capitalize',
                'text_decoration'    => 'none',
            ),
            'social_icons_target' => '_blank',
            'social_icons' => json_encode(array(
                array(
                    'icon_class'    => 'fab fa-facebook-f',
                    'icon_url'      => '',
                    'item_option'   => 'show'
                ),
                array(
                    'icon_class'    => 'fab fa-instagram',
                    'icon_url'      => '',
                    'item_option'   => 'show'
                ),
                array(
                    'icon_class'    => 'fa-brands fa-x-twitter',
                    'icon_url'      => '',
                    'item_option'   => 'show'
                ),
                array(
                    'icon_class'    => 'fab fa-google-wallet',
                    'icon_url'      => '',
                    'item_option'   => 'show'
                ),
                array(
                    'icon_class'    => 'fab fa-youtube',
                    'icon_url'      => '',
                    'item_option'   => 'show'
                )
            )),
            'ticker_news_width_layout'  => 'global',
            'ticker_news_visible'   => 'none',
            'ticker_news_order_by'  => 'date-desc',
            'ticker_news_categories' => '[]',
            'ticker_news_posts' => '[]',
            'ticker_news_date_filter' => 'all',
            'ticker_news_title_icon' => [
                'type'  => 'icon',
                'value' => 'fas fa-dot-circle'
            ],
            'ticker_news_title' => esc_html__( 'Headlines', 'newsis' ),
            'main_banner_option'    => true,
            'main_banner_layout'    => 'four',
            'main_banner_slider_order_by'   => 'date-desc',
            'main_banner_slider_categories' => '[]',
            'main_banner_posts' => '[]',
            'main_banner_date_filter' => 'all',
            'main_banner_related_posts_option'  => false,
            'main_banner_related_posts_numbers'    => 3,
            'main_banner_block_posts_order_by'  => 'rand-desc',
            'main_banner_block_posts_to_include'    =>  '[]',
            'main_banner_block_posts_categories'   => '[]',
            'main_banner_six_trailing_posts_order_by'  => 'rand-desc',
            'main_banner_six_trailing_posts_categories'   => '[]',
            'main_banner_six_trailing_posts'   => '[]',
            'main_banner_six_trailing_posts_layout'   => 'row',
            'main_banner_width_layout'  => 'global',
            'full_width_blocks'   => json_encode(array(
                array(
                    'type'  => 'news-grid',
                    'blockId'    => '',
                    'option'    => true,
                    'column'    => 'three',
                    'layout'    => 'one',
                    'title'     => esc_html__( 'Latest posts', 'newsis' ),
                    'thumbOption'    => true,
                    'categoryOption'    => true,
                    'authorOption'  => true,
                    'dateOption'    => true,
                    'commentOption' => true,
                    'excerptOption' => true,
                    'excerptLength' => 10,
                    'query' => json_encode([
                        'order' => 'date-desc',
                        'count' => 3,
                        'offset' => 0,
                        'dateFilter' => 'all',
                        'posts' => [],
                        'categories' => [],
                        'ids' => []
                    ]),
                    'buttonOption' => false,
                    'viewallOption'=> false,
                    'viewallUrl'   => '',
                    'imageRatio'   =>  json_encode([
                        'desktop'   =>  0,
                        'tablet'   =>  0,
                        'smartphone'   =>  0
                    ])
                ),
                array(
                    'type'  => 'ad-block',
                    'blockId'    => '',
                    'option'    =>   false,
                    'media' =>   json_encode([
                        'media_url' =>  '',
                        'media_id'  =>  0
                    ]),
                    'url'   =>  '',
                    'targetAttr'    =>  '_blank',
                    'relAttr'   =>  'nofollow'
                )
            )),
            'full_width_blocks_width_layout'  => 'global',
            'full_width_vertical_spacing_top'    =>  [
                'desktop'   =>  30,
                'tablet'    =>  30,
                'smartphone'    =>  30
            ],
            'full_width_vertical_spacing_bottom'    =>  [
                'desktop'   =>  30,
                'tablet'    =>  30,
                'smartphone'    =>  30
            ],
            'leftc_rights_blocks'   => json_encode(array(
                array(
                    'type'  => 'news-filter',
                    'blockId'    => '',
                    'option'    => true,
                    'layout'    => 'one',
                    'title'     => esc_html__( 'Latest posts', 'newsis' ),
                    'categoryOption'    => true,
                    'authorOption'  => true,
                    'dateOption'    => true,
                    'commentOption' => true,
                    'excerptOption' => false,
                    'excerptLength' => 10,
                    'query' => json_encode([
                        'order' => 'date-desc',
                        'count' => 6,
                        'offset' => 0,
                        'dateFilter' => 'all',
                        'posts' => [],
                        'categories' => [],
                        'ids' => []
                    ]),
                    'buttonOption'    => false,
                    'viewallOption'    => false,
                    'viewallUrl'   => '',
                    'imageRatio'   =>  json_encode([
                        'desktop'   =>  0,
                        'tablet'   =>  0,
                        'smartphone'   =>  0,
                    ]),
                    'imageSize' =>  'medium_large'
                ),
                array(
                    'type'  => 'news-alter',
                    'blockId'    => '',
                    'option'    => false,
                    'layout'    => 'two',
                    'title'     => esc_html__( 'Latest posts', 'newsis' ),
                    'categoryOption'    => true,
                    'authorOption'  => true,
                    'dateOption'    => true,
                    'commentOption' => true,
                    'excerptOption' => false,
                    'excerptLength' => 10,
                    'query' => json_encode([
                        'order' => 'date-desc',
                        'count' => 6,
                        'offset' => 0,
                        'dateFilter' => 'all',
                        'posts' => [],
                        'categories' => [],
                        'ids' => []
                    ]),
                    'buttonOption'    => false,
                    'viewallOption'    => false,
                    'viewallUrl'   => '',
                    'imageRatio'   =>  json_encode([
                        'desktop'   =>  0,
                        'tablet'   =>  0,
                        'smartphone'   =>  0,
                    ]),
                    'imageSize' =>  'medium_large'
                )
            )),
            'leftc_rights_blocks_width_layout'  => 'global',
            'leftc_rights_vertical_spacing_top'  =>  [
                'desktop'   =>  30,
                'tablet'    =>  30,
                'smartphone'    =>  30
            ],
            'leftc_rights_vertical_spacing_bottom'  =>  [
                'desktop'   =>  30,
                'tablet'    =>  30,
                'smartphone'    =>  30
            ],
            'lefts_rightc_blocks'   => json_encode(array(
                array(
                    'type'  => 'news-list',
                    'blockId'    => '',
                    'option'    => true,
                    'layout'    => 'one',
                    'column'    => 'two',
                    'title'     => esc_html__( 'Latest posts', 'newsis' ),
                    'thumbOption'    => true,
                    'categoryOption'    => true,
                    'authorOption'  => true,
                    'dateOption'    => true,
                    'commentOption' => true,
                    'excerptOption' => true,
                    'excerptLength' => 10,
                    'query' => json_encode([
                        'order' => 'date-desc',
                        'count' => 4,
                        'offset' => 0,
                        'dateFilter' => 'all',
                        'posts' => [],
                        'categories' => [],
                        'ids' => []
                    ]),
                    'buttonOption'    => false,
                    'viewallOption'    => false,
                    'viewallUrl'   => '',
                    'imageRatio'   =>  json_encode([
                        'desktop'   =>  0,
                        'tablet'   =>  0,
                        'smartphone'   =>  0,
                    ])
                ),
                array(
                    'type'  => 'news-alter',
                    'blockId'    => '',
                    'option'    => false,
                    'layout'    => 'one',
                    'column'    => 'two',
                    'title'     => esc_html__( 'Latest posts', 'newsis' ),
                    'thumbOption'    => true,
                    'categoryOption'    => true,
                    'authorOption'  => true,
                    'dateOption'    => true,
                    'commentOption' => true,
                    'excerptOption' => true,
                    'excerptLength' => 10,
                    'query' => json_encode([
                        'order' => 'date-desc',
                        'count' => 4,
                        'offset' => 0,
                        'dateFilter' => 'all',
                        'posts' => [],
                        'categories' => [],
                        'ids' => []
                    ]),
                    'buttonOption'    => false,
                    'viewallOption'    => false,
                    'viewallUrl'   => '',
                    'imageRatio'   =>  json_encode([
                        'desktop'   =>  0,
                        'tablet'   =>  0,
                        'smartphone'   =>  0,
                    ]),
                    'imageSize' =>  'medium_large'
                )
            )),
            'lefts_rightc_blocks_width_layout'  => 'global',
            'lefts_rightc_vertical_spacing_top'  =>  [
                'desktop'   =>  30,
                'tablet'    =>  30,
                'smartphone'    =>  30
            ],
            'lefts_rightc_vertical_spacing_bottom'  =>  [
                'desktop'   =>  30,
                'tablet'    =>  30,
                'smartphone'    =>  30
            ],
            'bottom_full_width_blocks'   => json_encode(array(
                array(
                    'type'  => 'news-carousel',
                    'blockId'    => '',
                    'option'    => true,
                    'layout'    => 'one',
                    'title'     => esc_html__( 'You May Have Missed', 'newsis' ),
                    'categoryOption'    => true,
                    'authorOption'  => true,
                    'dateOption'    => true,
                    'commentOption' => false,
                    'excerptOption' => false,
                    'excerptLength' => 10,
                    'columns' => 4,
                    'query' => json_encode([
                        'order' => 'rand-desc',
                        'count' => 8,
                        'offset' => 0,
                        'dateFilter' => 'all',
                        'posts' => [],
                        'categories' => [],
                        'ids' => []
                    ]),
                    'buttonOption'    => false,
                    'viewallOption'    => false,
                    'viewallUrl'   => '',
                    'dots' => true,
                    'loop' => false,
                    'arrows' => true,
                    'auto' => false,
                    'imageRatio'   =>  json_encode([
                        'desktop'   =>  0,
                        'tablet'   =>  0,
                        'smartphone'   =>  0
                    ])
                ),
                array(
                    'type'  => 'news-list',
                    'blockId'    => '',
                    'option'    => false,
                    'layout'    => 'two',
                    'column'    => 'two',
                    'title'     => esc_html__( 'Latest posts', 'newsis' ),
                    'thumbOption'    => true,
                    'categoryOption'    => true,
                    'authorOption'  => true,
                    'dateOption'    => true,
                    'commentOption' => true,
                    'excerptOption' => true,
                    'excerptLength' => 10,
                    'query' => json_encode([
                        'order' => 'date-desc',
                        'count' => 4,
                        'offset' => 0,
                        'dateFilter' => 'all',
                        'posts' => [],
                        'categories' => [],
                        'ids' => []
                    ]),
                    'buttonOption'    => false,
                    'viewallOption'    => false,
                    'viewallUrl'   => '',
                    'imageRatio'   =>  json_encode([
                        'desktop'   =>  0,
                        'tablet'   =>  0,
                        'smartphone'   =>  0,
                    ])
                ),
            )),
            'bottom_full_width_blocks_width_layout'  => 'global',
            'bottom_full_width_blocks_vertical_spacing_top'  =>  [
                'desktop'   =>  30,
                'tablet'    =>  30,
                'smartphone'    =>  30
            ],
            'bottom_full_width_blocks_vertical_spacing_bottom'  =>  [
                'desktop'   =>  30,
                'tablet'    =>  30,
                'smartphone'    =>  30
            ],
            'footer_option' => false,
            'footer_section_width'  => 'boxed-width',
            'footer_widget_column'  => 'column-three',
            'footer_vertical_spacing_top'   =>  [
                'desktop'   =>  35,
                'tablet'    =>  35,
                'smartphone'    =>  35
            ],
            'footer_vertical_spacing_bottom'   =>  [
                'desktop'   =>  35,
                'tablet'    =>  35,
                'smartphone'    =>  35
            ],
            'bottom_footer_option'  => true,
            'bottom_footer_social_option'   => false,
            'bottom_footer_menu_option'     => false,
            'bottom_footer_site_info'   => esc_html__( 'Newsis - Modern WordPress Theme %year%.', 'newsis' ),
            'bottom_footer_site_info_alignment' => 'center',
            'bottom_footer_width_layout'    => 'global',
            'bottom_footer_background_color_group'  => json_encode(array(
                'type'  => 'solid',
                'solid' => null,
                'gradient'  => null
            )),
            'single_post_related_posts_option'  => true,
            'single_post_related_posts_title'   => esc_html__( 'Related News', 'newsis' ),
            'single_post_show_original_image_option'=> false,
            'single_post_related_posts_popup_option'=> false,
            'single_post_image_ratio'   =>  array(
                'desktop'   => 0,
                'tablet'    => 0,
                'smartphone'    => 0
            ),
            'single_post_width_layout'=> 'global',
            'single_post_title_typo'=> array(
                'font_family'   => array( 'value' => 'Frank Ruhl Libre', 'label' => 'Frank Ruhl Libre' ),
                'font_weight'   => array( 'value' => '700', 'label' => 'Bold 700' ),
                'font_size'   => array(
                    'desktop' => 32,
                    'tablet' => 32,
                    'smartphone' => 30
                ),
                'line_height'   => array(
                    'desktop' => 40,
                    'tablet' => 40,
                    'smartphone' => 35
                ),
                'letter_spacing'   => array(
                    'desktop' => 0,
                    'tablet' => 0,
                    'smartphone' => 0
                ),
                'text_transform'    => 'unset',
                'text_decoration'    => 'none',
            ),
            'single_post_meta_typo'=> array(
                'font_family'   => array( 'value' => 'Noto Sans JP', 'label' => 'Noto Sans JP' ),
                'font_weight'   => array( 'value' => '500', 'label' => 'Medium 500' ),
                'font_size'   => array(
                    'desktop' => 13,
                    'tablet' => 13,
                    'smartphone' => 13
                ),
                'line_height'   => array(
                    'desktop' => 18,
                    'tablet' => 18,
                    'smartphone' => 18
                ),
                'letter_spacing'   => array(
                    'desktop' => 0,
                    'tablet' => 0,
                    'smartphone' => 0
                ),
                'text_transform'    => 'capitalize',
                'text_decoration'    => 'none',
            ),
            'single_post_content_typo'=> array(
                'font_family'   => array( 'value' => 'Noto Sans JP', 'label' => 'Noto Sans JP' ),
                'font_weight'   => array( 'value' => '300', 'label' => 'Light 300' ),
                'font_size'   => array(
                    'desktop' => 16,
                    'tablet' => 16,
                    'smartphone' => 16
                ),
                'line_height'   => array(
                    'desktop' => 30,
                    'tablet' => 30,
                    'smartphone' => 30
                ),
                'letter_spacing'   => array(
                    'desktop' => 0,
                    'tablet' => 0,
                    'smartphone' => 0
                ),
                'text_transform'    => 'unset',
                'text_decoration'    => 'none',
            ),
            'single_post_content_h1_typo'=> array(
                'font_family'   => array( 'value' => 'Frank Ruhl Libre', 'label' => 'Frank Ruhl Libre' ),
                'font_weight'   => array( 'value' => '700', 'label' => 'Regular 700' ),
                'font_size'   => array(
                    'desktop' => 32,
                    'tablet' => 16,
                    'smartphone' => 16
                ),
                'line_height'   => array(
                    'desktop' => 46,
                    'tablet' => 22,
                    'smartphone' => 22
                ),
                'letter_spacing'   => array(
                    'desktop' => 0,
                    'tablet' => 0,
                    'smartphone' => 0
                ),
                'text_transform'    => 'capitalize',
                'text_decoration'    => 'none',
            ),
            'single_post_content_h2_typo'=> array(
                'font_family'   => array( 'value' => 'Frank Ruhl Libre', 'label' => 'Frank Ruhl Libre' ),
                'font_weight'   => array( 'value' => '700', 'label' => 'Regular 700' ),
                'font_size'   => array(
                    'desktop' => 26,
                    'tablet' => 16,
                    'smartphone' => 16
                ),
                'line_height'   => array(
                    'desktop' => 38,
                    'tablet' => 22,
                    'smartphone' => 22
                ),
                'letter_spacing'   => array(
                    'desktop' => 0,
                    'tablet' => 0,
                    'smartphone' => 0
                ),
                'text_transform'    => 'capitalize',
                'text_decoration'    => 'none',
            ),
            'single_post_content_h3_typo'=> array(
                'font_family'   => array( 'value' => 'Frank Ruhl Libre', 'label' => 'Frank Ruhl Libre' ),
                'font_weight'   => array( 'value' => '700', 'label' => 'Regular 700' ),
                'font_size'   => array(
                    'desktop' => 19,
                    'tablet' => 16,
                    'smartphone' => 16
                ),
                'line_height'   => array(
                    'desktop' => 27,
                    'tablet' => 22,
                    'smartphone' => 22
                ),
                'letter_spacing'   => array(
                    'desktop' => 0,
                    'tablet' => 0,
                    'smartphone' => 0
                ),
                'text_transform'    => 'capitalize',
                'text_decoration'    => 'none',
            ),
            'single_post_content_h4_typo'=> array(
                'font_family'   => array( 'value' => 'Frank Ruhl Libre', 'label' => 'Frank Ruhl Libre' ),
                'font_weight'   => array( 'value' => '700', 'label' => 'Regular 700' ),
                'font_size'   => array(
                    'desktop' => 17,
                    'tablet' => 16,
                    'smartphone' => 16
                ),
                'line_height'   => array(
                    'desktop' => 24,
                    'tablet' => 22,
                    'smartphone' => 22
                ),
                'letter_spacing'   => array(
                    'desktop' => 0,
                    'tablet' => 0,
                    'smartphone' => 0
                ),
                'text_transform'    => 'capitalize',
                'text_decoration'    => 'none',
            ),
            'single_post_content_h5_typo'=> array(
                'font_family'   => array( 'value' => 'Frank Ruhl Libre', 'label' => 'Frank Ruhl Libre' ),
                'font_weight'   => array( 'value' => '700', 'label' => 'Regular 700' ),
                'font_size'   => array(
                    'desktop' => 14,
                    'tablet' => 16,
                    'smartphone' => 16
                ),
                'line_height'   => array(
                    'desktop' => 20,
                    'tablet' => 20,
                    'smartphone' => 20
                ),
                'letter_spacing'   => array(
                    'desktop' => 0,
                    'tablet' => 0,
                    'smartphone' => 0
                ),
                'text_transform'    => 'capitalize',
                'text_decoration'    => 'none',
            ),
            'single_post_content_h6_typo'=> array(
                'font_family'   => array( 'value' => 'Frank Ruhl Libre', 'label' => 'Frank Ruhl Libre' ),
                'font_weight'   => array( 'value' => '700', 'label' => 'Regular 700' ),
                'font_size'   => array(
                    'desktop' => 11,
                    'tablet' => 11,
                    'smartphone' => 10
                ),
                'line_height'   => array(
                    'desktop' => 16,
                    'tablet' => 16,
                    'smartphone' => 16
                ),
                'letter_spacing'   => array(
                    'desktop' => 0,
                    'tablet' => 0,
                    'smartphone' => 0
                ),
                'text_transform'    => 'capitalize',
                'text_decoration'    => 'none',
            ),
            'archive_page_layout'   => 'one',
            'archive_page_title_prefix'   => false,
            'archive_page_category_option'   => true,
            'archive_pagination_type'   => 'number',
            'archive_post_element_order'    => array(
                array( 'value'  => 'title', 'option' => true ),
                array( 'value'  => 'meta', 'option' => true ),
                array( 'value'  => 'excerpt', 'option' => true ),
                array( 'value'  => 'button', 'option' => true )
            ),
            'archive_post_meta_order'    => array(
                array( 'value'  => 'author', 'option' => true ),
                array( 'value'  => 'date', 'option' => true ),
                array( 'value'  => 'comments', 'option' => true ),
                array( 'value'  => 'read-time', 'option' => true )
            ),
            'archive_image_ratio'   =>  array(
                'desktop'   => 0,
                'tablet'    => 0,
                'smartphone'    => 0
            ),
            'archive_width_layout'=> 'global',
            'archive_vertical_spacing_top'    =>  [
                'desktop'   =>  30,
                'tablet'    =>  30,
                'smartphone'    =>  30
            ],
            'archive_vertical_spacing_bottom'    =>  [
                'desktop'   =>  30,
                'tablet'    =>  30,
                'smartphone'    =>  30
            ],
            'single_page_width_layout' => 'global',
            'single_page_show_original_image_option'  =>  false,
            'single_page_image_ratio'   =>  array(
                'desktop'   => 0,
                'tablet'    => 0,
                'smartphone'    => 0
            ),
            'error_page_image'  => 0,
            'error_page_width_layout' => 'global',
            'search_page_width_layout' => 'global',
            'site_section_block_title_typo'    => array(
                'font_family'   => array( 'value' => 'Noto Sans JP', 'label' => 'Noto Sans JP' ),
                'font_weight'   => array( 'value' => '500', 'label' => 'Medium 500' ),
                'font_size'   => array(
                    'desktop' => 17,
                    'tablet' => 13,
                    'smartphone' => 13
                ),
                'line_height'   => array(
                    'desktop' => 30,
                    'tablet' => 30,
                    'smartphone' => 30
                ),
                'letter_spacing'   => array(
                    'desktop' => 0,
                    'tablet' => 0,
                    'smartphone' => 0
                ),
                'text_transform'    => 'uppercase',
                'text_decoration'    => 'none',
            ),
            'site_archive_post_title_typo'    => array(
                'font_family'   => array( 'value' => 'Frank Ruhl Libre', 'label' => 'Frank Ruhl Libre' ),
                'font_weight'   => array( 'value' => '500', 'label' => 'Medium 500' ),
                'font_size'   => array(
                    'desktop' => 22,
                    'tablet' => 18,
                    'smartphone' => 15
                ),
                'line_height'   => array(
                    'desktop' => 24,
                    'tablet' => 30,
                    'smartphone' => 26
                ),
                'letter_spacing'   => array(
                    'desktop' => 0,
                    'tablet' => 0,
                    'smartphone' => 0
                ),
                'text_transform'    => 'unset',
                'text_decoration'    => 'none',
            ),
            'site_archive_post_meta_typo'    => array(
                'font_family'   => array( 'value' => 'Noto Sans JP', 'label' => 'Noto Sans JP' ),
                'font_weight'   => array( 'value' => '400', 'label' => 'Regular 400' ),
                'font_size'   => array(
                    'desktop' => 14,
                    'tablet' => 12,
                    'smartphone' => 11
                ),
                'line_height'   => array(
                    'desktop' => 16,
                    'tablet' => 16,
                    'smartphone' => 16
                ),
                'letter_spacing'   => array(
                    'desktop' => 0,
                    'tablet' => 0,
                    'smartphone' => 0
                ),
                'text_transform'    => 'uppercase',
                'text_decoration'    => 'none',
            ),
            'site_archive_post_content_typo'    => array(
                'font_family'   => array( 'value' => 'Noto Sans JP', 'label' => 'Noto Sans JP' ),
                'font_weight'   => array( 'value' => '300', 'label' => 'Light 300' ),
                'font_size'   => array(
                    'desktop' => 15,
                    'tablet' => 15,
                    'smartphone' => 15
                ),
                'line_height'   => array(
                    'desktop' => 24,
                    'tablet' => 24,
                    'smartphone' => 24
                ),
                'letter_spacing'   => array(
                    'desktop' => 0,
                    'tablet' => 0,
                    'smartphone' => 0
                ),
                'text_transform'    => 'unset',
                'text_decoration'    => 'none'
            ),
            'stt_responsive_option'    => array(
                'desktop'   => true,
                'tablet'   => true,
                'mobile'   => false
            ),
            'stt_icon_picker' => [
                'type'  => 'icon',
                'value' => 'fa-solid fa-angle-up',
            ],
            'stt_alignment' => 'right'
        ));
        $totalCats = get_categories();
        if( $totalCats ) :
            foreach( $totalCats as $singleCat ) :
                $array_defaults['category_' .absint($singleCat->term_id). '_color'] = newsis_get_rcolor_code();
            endforeach;
        endif;
        return $array_defaults[$key];
    }
 endif;