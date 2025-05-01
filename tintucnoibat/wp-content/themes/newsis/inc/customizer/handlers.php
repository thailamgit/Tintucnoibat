<?php
use Newsis\CustomizerDefault as NI;
/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
add_action( 'customize_preview_init', function() {
    wp_enqueue_script( 
        'newsis-customizer-preview',
        get_template_directory_uri() . '/inc/customizer/assets/customizer-preview.min.js',
        ['customize-preview'],
        NEWSIS_VERSION,
        true
    );
    // localize scripts
	wp_localize_script( 
        'newsis-customizer-preview',
        'newsisPreviewObject', array(
            '_wpnonce'	=> wp_create_nonce( 'newsis-customizer-nonce' ),
            'ajaxUrl' => esc_url(admin_url('admin-ajax.php')),
            'totalCats' => get_categories() ? get_categories() : [],
        )
    );
});

add_action( 'customize_controls_enqueue_scripts', function() {
    $buildControlsDeps = apply_filters(  'newsis_customizer_build_controls_dependencies', array(
        'react',
        'wp-blocks',
        'wp-editor',
        'wp-element',
        'wp-i18n',
        'wp-polyfill',
        'jquery',
        'wp-components'
    ));
	wp_enqueue_style( 
        'newsis-customizer-control',
        get_template_directory_uri() . '/inc/customizer/assets/customizer-controls.min.css', 
        array('wp-components'),
        NEWSIS_VERSION,
        'all'
    );
    wp_enqueue_script( 
        'newsis-customizer-control',
        get_template_directory_uri() . '/inc/customizer/assets/customizer-extends.min.js',
        $buildControlsDeps,
        NEWSIS_VERSION,
        true
    );
    // localize scripts
    wp_localize_script( 
        'newsis-customizer-control', 
        'customizerControlsObject', array(
            'categories'    => newsis_get_multicheckbox_categories_simple_array(),
            'posts' => newsis_get_multicheckbox_posts_simple_array(),
            'imageSizes'    => newsis_get_image_sizes_option_array(),
            '_wpnonce'  => wp_create_nonce( 'newsis-customizer-controls-live-nonce' ),
            'ajaxUrl'   => esc_url(admin_url('admin-ajax.php'))
        )
    );
});

if( !function_exists( 'newsis_customizer_about_theme_panel' ) ) :
    /**
     * Register blog archive section settings
     * 
     */
    function newsis_customizer_about_theme_panel( $wp_customize ) {
        /**
         * About theme section
         * 
         * @since 1.0.0
         */
        $wp_customize->add_section( NEWSIS_PREFIX . 'about_section', array(
            'title' => esc_html__( 'About Theme', 'newsis' ),
            'priority'  => 1
        ));

        // theme documentation info box
        $wp_customize->add_setting( 'site_documentation_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Info_Box_Control( $wp_customize, 'site_documentation_info', array(
                'label'	      => esc_html__( 'Theme Documentation', 'newsis' ),
                'description' => esc_html__( 'We have well prepared documentation which includes overall instructions and recommendations that are required in this theme.', 'newsis' ),
                'section'     => NEWSIS_PREFIX . 'about_section',
                'settings'    => 'site_documentation_info',
                'choices' => array(
                    array(
                        'label' => esc_html__( 'View Documentation', 'newsis' ),
                        'url'   => esc_url( '//doc.blazethemes.com/newsis' )
                    )
                )
            ))
        );

        // theme documentation info box
        $wp_customize->add_setting( 'site_support_info', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Info_Box_Control( $wp_customize, 'site_support_info', array(
                'label'	      => esc_html__( 'Theme Support', 'newsis' ),
                'description' => esc_html__( 'We provide 24/7 support regarding any theme issue. Our support team will help you to solve any kind of issue. Feel free to contact us.', 'newsis' ),
                'section'     => NEWSIS_PREFIX . 'about_section',
                'settings'    => 'site_support_info',
                'choices' => array(
                    array(
                        'label' => esc_html__( 'Support Form', 'newsis' ),
                        'url'   => esc_url( '//blazethemes.com/support' )
                    )
                )
            ))
        );
    }
    add_action( 'customize_register', 'newsis_customizer_about_theme_panel', 10 );
endif;

if( !function_exists( 'newsis_customizer_global_panel' ) ) :
    /**
     * Register global options settings
     * 
     */
    function newsis_customizer_global_panel( $wp_customize ) {
        /**
         * Global panel
         * 
         * @package Newsis
         * @since 1.0.0
         */
        $wp_customize->add_panel( 'newsis_global_panel', array(
            'title' => esc_html__( 'Global', 'newsis' ),
            'priority'  => 5
        ));

        // section- seo/misc settings section
        $wp_customize->add_section( 'newsis_seo_misc_section', array(
            'title' => esc_html__( 'SEO / Misc', 'newsis' ),
            'panel' => 'newsis_global_panel'
        ));

        // site schema ready option
        $wp_customize->add_setting( 'site_schema_ready', array(
            'default'   => NI\newsis_get_customizer_default( 'site_schema_ready' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
            'transport'    => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Toggle_Control( $wp_customize, 'site_schema_ready', array(
                'label'	      => esc_html__( 'Make website schema ready', 'newsis' ),
                'section'     => 'newsis_seo_misc_section',
                'settings'    => 'site_schema_ready'
            ))
        );

        // site date to show
        $wp_customize->add_setting( 'site_date_to_show', array(
            'sanitize_callback' => 'newsis_sanitize_select_control',
            'default'   => NI\newsis_get_customizer_default( 'site_date_to_show' )
        ));
        $wp_customize->add_control( 'site_date_to_show', array(
            'type'      => 'select',
            'section'   => 'newsis_seo_misc_section',
            'label'     => esc_html__( 'Date to display', 'newsis' ),
            'description' => esc_html__( 'Whether to show date published or modified date.', 'newsis' ),
            'choices'   => array(
                'published'  => __( 'Published date', 'newsis' ),
                'modified'   => __( 'Modified date', 'newsis' )
            )
        ));

        // site date format
        $wp_customize->add_setting( 'site_date_format', array(
            'sanitize_callback' => 'newsis_sanitize_select_control',
            'default'   => NI\newsis_get_customizer_default( 'site_date_format' )
        ));
        $wp_customize->add_control( 'site_date_format', array(
            'type'      => 'select',
            'section'   => 'newsis_seo_misc_section',
            'label'     => esc_html__( 'Date format', 'newsis' ),
            'description' => esc_html__( 'Date format applied to single and archive pages.', 'newsis' ),
            'choices'   => array(
                'theme_format'  => __( 'Default by theme', 'newsis' ),
                'default'   => __( 'Wordpress default date', 'newsis' )
            )
        ));

        // color presets heading
        $wp_customize->add_setting( 'preset_colors_heading', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Toggle_Control( $wp_customize, 'preset_colors_heading', array(
                'label'	      => esc_html__( 'Theme Presets', 'newsis' ),
                'section'     => 'colors'
            ))
        );

        // primary preset color
        $wp_customize->add_setting( 'preset_color_1', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_color_1' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'newsis_sanitize_color_picker_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Color_Picker_Control( $wp_customize, 'preset_color_1', array(
                'label'	      => esc_html__( 'Color 1', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_color_1',
                'variable'   => '--newsis-global-preset-color-1'
            ))
        );

        // secondary preset color
        $wp_customize->add_setting( 'preset_color_2', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_color_2' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'newsis_sanitize_color_picker_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Color_Picker_Control( $wp_customize, 'preset_color_2', array(
                'label'	      => esc_html__( 'Color 2', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_color_2',
                'variable'   => '--newsis-global-preset-color-2'
            ))
        );

        // tertiary preset color
        $wp_customize->add_setting( 'preset_color_3', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_color_3' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'newsis_sanitize_color_picker_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Color_Picker_Control( $wp_customize, 'preset_color_3', array(
                'label'	      => esc_html__( 'Color 3', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_color_3',
                'variable'   => '--newsis-global-preset-color-3'
            ))
        );

        // primary preset link color
        $wp_customize->add_setting( 'preset_color_4', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_color_4' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'newsis_sanitize_color_picker_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Color_Picker_Control( $wp_customize, 'preset_color_4', array(
                'label'	      => esc_html__( 'Color 4', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_color_4',
                'variable'   => '--newsis-global-preset-color-4'
            ))
        );

        // secondary preset link color
        $wp_customize->add_setting( 'preset_color_5', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_color_5' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'newsis_sanitize_color_picker_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Color_Picker_Control( $wp_customize, 'preset_color_5', array(
                'label'	      => esc_html__( 'Color 5', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_color_5',
                'variable'   => '--newsis-global-preset-color-5'
            ))
        );
        
        // tertiary preset link color
        $wp_customize->add_setting( 'preset_color_6', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_color_6' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'newsis_sanitize_color_picker_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Color_Picker_Control( $wp_customize, 'preset_color_6', array(
                'label'	      => esc_html__( 'Color 6', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_color_6',
                'variable'   => '--newsis-global-preset-color-6'
            ))
        );

        // tertiary preset link color
        $wp_customize->add_setting( 'preset_color_7', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_color_7' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'newsis_sanitize_color_picker_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Color_Picker_Control( $wp_customize, 'preset_color_7', array(
                'label'       => esc_html__( 'Color 7', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_color_7',
                'variable'   => '--newsis-global-preset-color-7'
            ))
        );

        // tertiary preset link color
        $wp_customize->add_setting( 'preset_color_8', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_color_8' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'newsis_sanitize_color_picker_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Color_Picker_Control( $wp_customize, 'preset_color_8', array(
                'label'       => esc_html__( 'Color 8', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_color_8',
                'variable'   => '--newsis-global-preset-color-8'
            ))
        );

        // tertiary preset link color
        $wp_customize->add_setting( 'preset_color_9', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_color_9' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'newsis_sanitize_color_picker_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Color_Picker_Control( $wp_customize, 'preset_color_9', array(
                'label'       => esc_html__( 'Color 9', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_color_9',
                'variable'   => '--newsis-global-preset-color-9'
            ))
        );

        // tertiary preset link color
        $wp_customize->add_setting( 'preset_color_10', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_color_10' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'newsis_sanitize_color_picker_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Color_Picker_Control( $wp_customize, 'preset_color_10', array(
                'label'       => esc_html__( 'Color 10', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_color_10',
                'variable'   => '--newsis-global-preset-color-10'
            ))
        );

        // tertiary preset link color
        $wp_customize->add_setting( 'preset_color_11', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_color_11' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'newsis_sanitize_color_picker_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Color_Picker_Control( $wp_customize, 'preset_color_11', array(
                'label'       => esc_html__( 'Color 11', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_color_11',
                'variable'   => '--newsis-global-preset-color-11'
            ))
        );

        // tertiary preset link color
        $wp_customize->add_setting( 'preset_color_12', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_color_12' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'newsis_sanitize_color_picker_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Color_Picker_Control( $wp_customize, 'preset_color_12', array(
                'label'       => esc_html__( 'Color 12', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_color_12',
                'variable'   => '--newsis-global-preset-color-12'
            ))
        );

        // gradient color presets heading
        $wp_customize->add_setting( 'gradient_preset_colors_heading', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Toggle_Control( $wp_customize, 'gradient_preset_colors_heading', array(
                'label'	      => esc_html__( 'Gradient Presets', 'newsis' ),
                'section'     => 'colors'
            ))
        );

        // gradient color 1
        $wp_customize->add_setting( 'preset_gradient_1', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_gradient_1' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Gradient_Picker_Control( $wp_customize, 'preset_gradient_1', array(
                'label'	      => esc_html__( 'Gradient 1', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_gradient_1',
                'variable'   => '--newsis-global-preset-gradient-color-1'
            ))
        );
        
        // gradient color 2
        $wp_customize->add_setting( 'preset_gradient_2', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_gradient_2' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Gradient_Picker_Control( $wp_customize, 'preset_gradient_2', array(
                'label'	      => esc_html__( 'Gradient 2', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_gradient_2',
                'variable'   => '--newsis-global-preset-gradient-color-2'
            ))
        );

        // gradient color 3
        $wp_customize->add_setting( 'preset_gradient_3', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_gradient_3' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Gradient_Picker_Control( $wp_customize, 'preset_gradient_3', array(
                'label'	      => esc_html__( 'Gradient 3', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_gradient_3',
                'variable'   => '--newsis-global-preset-gradient-color-3'
            ))
        );

        // gradient color 4
        $wp_customize->add_setting( 'preset_gradient_4', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_gradient_4' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Gradient_Picker_Control( $wp_customize, 'preset_gradient_4', array(
                'label'	      => esc_html__( 'Gradient 4', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_gradient_4',
                'variable'   => '--newsis-global-preset-gradient-color-4'
            ))
        );

        // gradient color 5
        $wp_customize->add_setting( 'preset_gradient_5', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_gradient_5' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Gradient_Picker_Control( $wp_customize, 'preset_gradient_5', array(
                'label'	      => esc_html__( 'Gradient 5', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_gradient_5',
                'variable'   => '--newsis-global-preset-gradient-color-5'
            ))
        );

        // gradient color 6
        $wp_customize->add_setting( 'preset_gradient_6', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_gradient_6' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Gradient_Picker_Control( $wp_customize, 'preset_gradient_6', array(
                'label'	      => esc_html__( 'Gradient 6', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_gradient_6',
                'variable'   => '--newsis-global-preset-gradient-color-6'
            ))
        );

        // gradient color 7
        $wp_customize->add_setting( 'preset_gradient_7', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_gradient_7' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Gradient_Picker_Control( $wp_customize, 'preset_gradient_7', array(
                'label'       => esc_html__( 'Gradient 7', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_gradient_7',
                'variable'   => '--newsis-global-preset-gradient-color-7'
            ))
        );

        // gradient color 8
        $wp_customize->add_setting( 'preset_gradient_8', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_gradient_8' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Gradient_Picker_Control( $wp_customize, 'preset_gradient_8', array(
                'label'       => esc_html__( 'Gradient 8', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_gradient_8',
                'variable'   => '--newsis-global-preset-gradient-color-8'
            ))
        );

        // gradient color 9
        $wp_customize->add_setting( 'preset_gradient_9', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_gradient_9' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Gradient_Picker_Control( $wp_customize, 'preset_gradient_9', array(
                'label'       => esc_html__( 'Gradient 9', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_gradient_9',
                'variable'   => '--newsis-global-preset-gradient-color-9'
            ))
        );

        // gradient color 10
        $wp_customize->add_setting( 'preset_gradient_10', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_gradient_10' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Gradient_Picker_Control( $wp_customize, 'preset_gradient_10', array(
                'label'       => esc_html__( 'Gradient 10', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_gradient_10',
                'variable'   => '--newsis-global-preset-gradient-color-10'
            ))
        );

        // gradient color 11
        $wp_customize->add_setting( 'preset_gradient_11', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_gradient_11' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Gradient_Picker_Control( $wp_customize, 'preset_gradient_11', array(
                'label'       => esc_html__( 'Gradient 11', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_gradient_11',
                'variable'   => '--newsis-global-preset-gradient-color-11'
            ))
        );

        // gradient color 12
        $wp_customize->add_setting( 'preset_gradient_12', array(
            'default'   => NI\newsis_get_customizer_default( 'preset_gradient_12' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Preset_Gradient_Picker_Control( $wp_customize, 'preset_gradient_12', array(
                'label'       => esc_html__( 'Gradient 12', 'newsis' ),
                'section'     => 'colors',
                'settings'    => 'preset_gradient_12',
                'variable'   => '--newsis-global-preset-gradient-color-12'
            ))
        );

        // section- category colors section
        $wp_customize->add_section( 'newsis_category_colors_section', array(
            'title' => esc_html__( 'Category Colors', 'newsis' ),
            'panel' => 'newsis_colors_panel',
            'priority'  => 40
        ));

        $totalCats = get_categories();
        if( $totalCats ) :
            foreach( $totalCats as $singleCat ) :
                // category colors control
                $wp_customize->add_setting( 'category_' .absint($singleCat->term_id). '_color', array(
                    'default'   => NI\newsis_get_customizer_default( 'category_' .absint($singleCat->term_id). '_color' ),
                    'sanitize_callback' => 'newsis_sanitize_color_group_picker_control',
                    'transport' =>  'postMessage'
                ));
                $wp_customize->add_control( 
                    new Newsis_WP_Color_Group_Picker_Control( $wp_customize, 'category_' .absint($singleCat->term_id). '_color', array(
                        'label'	      => esc_html($singleCat->name),
                        'section'     => 'newsis_category_colors_section',
                        'settings'    => 'category_' .absint($singleCat->term_id). '_color'
                    ))
                );
            endforeach;
        endif;

        // section- preloader section
        $wp_customize->add_section( 'newsis_preloader_section', array(
            'title' => esc_html__( 'Preloader', 'newsis' ),
            'panel' => 'newsis_global_panel'
        ));
        
        // preloader option
        $wp_customize->add_setting( 'preloader_option', array(
            'default'   => NI\newsis_get_customizer_default('preloader_option'),
            'sanitize_callback' => 'newsis_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'preloader_option', array(
                'label'	      => esc_html__( 'Enable site preloader', 'newsis' ),
                'section'     => 'newsis_preloader_section',
                'settings'    => 'preloader_option'
            ))
        );

        // post title animation effects 
        $wp_customize->add_setting( 'preloader_type', array(
            'sanitize_callback' => 'newsis_sanitize_select_control',
            'default'   => NI\newsis_get_customizer_default( 'preloader_type' ),
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 'preloader_type', array(
            'label'     => esc_html__( 'Preloader Type', 'newsis' ),
            'type'      => 'select',
            'section'   => 'newsis_preloader_section',
            'choices'   => array(
                '1' => __( 'One', 'newsis' ),
                '2' => __( 'Two', 'newsis' ),
                '3' => __( 'Three', 'newsis' ),
                '4' => __( 'Four', 'newsis' ),
                '5'    => __( 'Five', 'newsis' )
            )
        ));

        // section- website styles section
        $wp_customize->add_section( 'newsis_website_styles_section', array(
            'title' => esc_html__( 'Website Styles', 'newsis' ),
            'panel' => 'newsis_global_panel'
        ));

        // website block box shadow heading
        $wp_customize->add_setting( 'website_block_icon_picker_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'website_block_icon_picker_header', array(
                'label'	      => esc_html__( 'Icon Pickers', 'newsis' ),
                'section'     => 'newsis_website_styles_section',
                'settings'    => 'website_block_icon_picker_header'
            ))
        );

        // date before icons
        $wp_customize->add_setting( 'website_date_before_icon', [
            'default'   =>  NI\newsis_get_customizer_default( 'website_date_before_icon' ),
            'sanitize_callback' =>  'newsis_sanitize_icon_picker_control'
        ]);
        $wp_customize->add_control(
            new Newsis_WP_Icon_Picker_Control( $wp_customize, 'website_date_before_icon', [
                'label' =>  esc_html__( 'Date icon', 'newsis' ),
                'section'   =>  'newsis_website_styles_section'
            ])
        );

        // author before icons
        $wp_customize->add_setting( 'website_author_before_icon', [
            'default'   =>  NI\newsis_get_customizer_default( 'website_author_before_icon' ),
            'sanitize_callback' =>  'newsis_sanitize_icon_picker_control'
        ]);
        $wp_customize->add_control(
            new Newsis_WP_Icon_Picker_Control( $wp_customize, 'website_author_before_icon', [
                'label' =>  esc_html__( 'Author icon', 'newsis' ),
                'section'   =>  'newsis_website_styles_section'
            ])
        );
        
        // comments before icons
        $wp_customize->add_setting( 'website_comments_before_icon', [
            'default'   =>  NI\newsis_get_customizer_default( 'website_comments_before_icon' ),
            'sanitize_callback' =>  'newsis_sanitize_icon_picker_control'
        ]);
        $wp_customize->add_control(
            new Newsis_WP_Icon_Picker_Control( $wp_customize, 'website_comments_before_icon', [
                'label' =>  esc_html__( 'Comments icon', 'newsis' ),
                'section'   =>  'newsis_website_styles_section'
            ])
        );

        // read time before icons
        $wp_customize->add_setting( 'website_read_time_before_icon', [
            'default'   =>  NI\newsis_get_customizer_default( 'website_read_time_before_icon' ),
            'sanitize_callback' =>  'newsis_sanitize_icon_picker_control'
        ]);
        $wp_customize->add_control(
            new Newsis_WP_Icon_Picker_Control( $wp_customize, 'website_read_time_before_icon', [
                'label' =>  esc_html__( 'Read time icon', 'newsis' ),
                'section'   =>  'newsis_website_styles_section'
            ])
        );

        // section- card settings section
        $wp_customize->add_section( 'newsis_card_settings_section', array(
            'title' => esc_html__( 'Card Settings', 'newsis' ),
            'panel' => 'newsis_global_panel'
        ));

         // card settings option
         $wp_customize->add_setting( 'card_settings_option', array(
            'default'   => NI\newsis_get_customizer_default('card_settings_option'),
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
            'transport' =>  'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'card_settings_option', array(
                'label'	      => esc_html__( 'Enable card settings', 'newsis' ),
                'section'     => 'newsis_card_settings_section',
                'settings'    => 'card_settings_option'
            ))
        );

        // card box shadow
        $wp_customize->add_setting( 'card_box_shadow_control', [
            'default'   => NI\newsis_get_customizer_default( 'card_box_shadow_control' ),
            'sanitize_callback' => 'newsis_sanitize_box_shadow_control',
            'transport' => 'postMessage'
        ]);
        $wp_customize->add_control( 
            new Newsis_WP_Box_Shadow_Control( $wp_customize, 'card_box_shadow_control', [
                'label'	      => esc_html__( 'Box Shadow', 'newsis' ),
                'section'     => 'newsis_card_settings_section',
                'settings'    => 'card_box_shadow_control',
                'tab'   => 'design'
            ])
        );

        // card box shadow hover
        $wp_customize->add_setting( 'card_hover_box_shadow', [
            'default'   => NI\newsis_get_customizer_default( 'card_hover_box_shadow' ),
            'sanitize_callback' => 'newsis_sanitize_box_shadow_control',
            'transport' => 'postMessage'
        ]);
        $wp_customize->add_control( 
            new Newsis_WP_Box_Shadow_Control( $wp_customize, 'card_hover_box_shadow', [
                'label'	      => esc_html__( 'Box Shadow ( on hover )', 'newsis' ),
                'section'     => 'newsis_card_settings_section',
                'settings'    => 'card_hover_box_shadow',
                'tab'   => 'design'
            ])
        );
        
        // section- website layout section
        $wp_customize->add_section( 'newsis_website_layout_section', array(
            'title' => esc_html__( 'Website Layout', 'newsis' ),
            'panel' => 'newsis_global_panel'
        ));
        
        // website layout heading
        $wp_customize->add_setting( 'website_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'website_layout_header', array(
                'label'	      => esc_html__( 'Website Layout', 'newsis' ),
                'section'     => 'newsis_website_layout_section',
                'settings'    => 'website_layout_header'
            ))
        );

        // website layout
        $wp_customize->add_setting( 'website_layout',
            array(
                'default'           => NI\newsis_get_customizer_default( 'website_layout' ),
                'sanitize_callback' => 'newsis_sanitize_select_control',
                'transport' => 'postMessage'
            )
        );
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Image_Control( $wp_customize, 'website_layout',
            array(
                'section'  => 'newsis_website_layout_section',
                'choices'  => array(
                    'boxed--layout' => array(
                        'label' => esc_html__( 'Boxed', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/boxed-width.jpg'
                    ),
                    'full-width--layout' => array(
                        'label' => esc_html__( 'Full Width', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/full-width.jpg'
                    )
                )
            )
        ));

        // website content layout heading
        $wp_customize->add_setting( 'website_content_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'website_content_layout_header', array(
                'label'	      => esc_html__( 'Website Content Global Layout', 'newsis' ),
                'section'     => 'newsis_website_layout_section',
                'settings'    => 'website_content_layout_header'
            ))
        );

        // website content layout
        $wp_customize->add_setting( 'website_content_layout',
            array(
            'default'           => NI\newsis_get_customizer_default( 'website_content_layout' ),
            'sanitize_callback' => 'newsis_sanitize_select_control',
            )
        );
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Image_Control( $wp_customize, 'website_content_layout',
            array(
                'section'  => 'newsis_website_layout_section',
                'choices'  => array(
                    'boxed--layout' => array(
                        'label' => esc_html__( 'Boxed', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/boxed_content.jpg'
                    ),
                    'full-width--layout' => array(
                        'label' => esc_html__( 'Full Width', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/full_content.jpg'
                    )
                )
            )
        ));

        // website block title layout heading
        $wp_customize->add_setting( 'website_block_title_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'website_block_title_layout_header', array(
                'label'	      => esc_html__( 'Block Title Layout', 'newsis' ),
                'section'     => 'newsis_website_layout_section',
                'settings'    => 'website_block_title_layout_header'
            ))
        );

        // website block title layout
        $wp_customize->add_setting( 'website_block_title_layout',
            array(
                'default'           => NI\newsis_get_customizer_default( 'website_block_title_layout' ),
                'sanitize_callback' => 'newsis_sanitize_select_control',
                'transport' => 'postMessage'
            )
        );
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Image_Control( $wp_customize, 'website_block_title_layout',
            array(
                'section'  => 'newsis_website_layout_section',
                'choices'  => array(
                    'layout-one' => array(
                        'label' => esc_html__( 'Layout One', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/block-title-layout-one.jpg'
                    ),
                    'layout-four' => array(
                        'label' => esc_html__( 'Layout Two', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/block-title-layout-four.jpg'
                    )
                )
            )
        ));

        // section- animation section
        $wp_customize->add_section( 'newsis_animation_section', array(
            'title' => esc_html__( 'Animation / Hover Effects', 'newsis' ),
            'panel' => 'newsis_global_panel'
        ));

        // website hover effects heading
        $wp_customize->add_setting( 'website_hover_effects_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'website_hover_effects_header', array(
                'label'	      => esc_html__( 'Hover Effects Setting', 'newsis' ),
                'section'     => 'newsis_animation_section',
                'settings'    => 'website_hover_effects_header'
            ))
        );

        // post title animation effects 
        $wp_customize->add_setting( 'post_title_hover_effects', array(
            'sanitize_callback' => 'newsis_sanitize_select_control',
            'default'   => NI\newsis_get_customizer_default( 'post_title_hover_effects' ),
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 'post_title_hover_effects', array(
            'type'      => 'select',
            'section'   => 'newsis_animation_section',
            'label'     => esc_html__( 'Post title hover effects', 'newsis' ),
            'description' => esc_html__( 'Applied to post titles listed in archive pages.', 'newsis' ),
            'choices'   => array(
                'none'  => __( 'None', 'newsis' ),
                'two'   => __( 'Effect One', 'newsis' ),                
                'six'   => __( 'Effect Two', 'newsis' )
            )
        ));

        // site image animation effects 
        $wp_customize->add_setting( 'site_image_hover_effects', array(
            'sanitize_callback' => 'newsis_sanitize_select_control',
            'default'   => NI\newsis_get_customizer_default( 'site_image_hover_effects' ),
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 'site_image_hover_effects', array(
            'type'      => 'select',
            'section'   => 'newsis_animation_section',
            'label'     => esc_html__( 'Image hover effects', 'newsis' ),
            'description' => esc_html__( 'Applied to post thumbanails listed in archive pages.', 'newsis' ),
            'choices'   => array(
                'none'  => __( 'None', 'newsis' ),
                'four'  => __( 'Effect One', 'newsis' ),
                'eight' => __( 'Effect Two', 'newsis' )
            )
        ));

        // site image animation effects 
        $wp_customize->add_setting( 'post_block_hover_effects', array(
            'sanitize_callback' => 'newsis_sanitize_select_control',
            'default'   => NI\newsis_get_customizer_default( 'post_block_hover_effects' ),
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 'post_block_hover_effects', array(
            'type'      => 'select',
            'section'   => 'newsis_animation_section',
            'label'     => esc_html__( 'Post blocks hover effects', 'newsis' ),
            'description' => esc_html__( 'Applied to post blocks.', 'newsis' ),
            'choices'   => array(
                'none' => __( 'None', 'newsis' ),
                'one'  => __( 'Effect One', 'newsis' )
            )
        ));

        // section- social icons section
        $wp_customize->add_section( 'newsis_social_icons_section', array(
            'title' => esc_html__( 'Social Icons', 'newsis' ),
            'panel' => 'newsis_global_panel'
        ));
        
        // social icons setting heading
        $wp_customize->add_setting( 'social_icons_settings_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'social_icons_settings_header', array(
                'label'	      => esc_html__( 'Social Icons Settings', 'newsis' ),
                'section'     => 'newsis_social_icons_section',
                'settings'    => 'social_icons_settings_header'
            ))
        );

        // social icons target attribute value
        $wp_customize->add_setting( 'social_icons_target', array(
            'sanitize_callback' => 'newsis_sanitize_select_control',
            'default'   => NI\newsis_get_customizer_default( 'social_icons_target' ),
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 'social_icons_target', array(
            'type'      => 'select',
            'section'   => 'newsis_social_icons_section',
            'label'     => esc_html__( 'Social Icon Link Open in', 'newsis' ),
            'description' => esc_html__( 'Sets the target attribute according to the value.', 'newsis' ),
            'choices'   => array(
                '_blank' => esc_html__( 'Open link in new tab', 'newsis' ),
                '_self'  => esc_html__( 'Open link in same tab', 'newsis' )
            )
        ));

        // social icons items
        $wp_customize->add_setting( 'social_icons', array(
            'default'   => NI\newsis_get_customizer_default( 'social_icons' ),
            'sanitize_callback' => 'newsis_sanitize_repeater_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Custom_Repeater( $wp_customize, 'social_icons', array(
                'label'         => esc_html__( 'Social Icons', 'newsis' ),
                'description'   => esc_html__( 'Hold bar icon and drag vertically to re-order the icons', 'newsis' ),
                'section'       => 'newsis_social_icons_section',
                'settings'      => 'social_icons',
                'row_label'     => 'inherit-icon_class',
                'fields'        => array(
                    'icon_class'   => array(
                        'type'          => 'fontawesome-icon-picker',
                        'families'      =>  'social',
                        'label'         => esc_html__( 'Social Icon', 'newsis' ),
                        'description'   => esc_html__( 'Select from dropdown.', 'newsis' ),
                        'default'       => esc_attr( 'fab fa-instagram' )

                    ),
                    'icon_url'  => array(
                        'type'      => 'url',
                        'label'     => esc_html__( 'URL for icon', 'newsis' ),
                        'default'   => ''
                    ),
                    'item_option'             => 'show'
                )
            ))
        );

        // section- buttons section
        $wp_customize->add_section( 'newsis_buttons_section', array(
            'title' => esc_html__( 'Buttons', 'newsis' ),
            'panel' => 'newsis_global_panel'
        ));

        // site title typo
        $wp_customize->add_setting( 'global_button_typo', array(
            'default'   => NI\newsis_get_customizer_default( 'global_button_typo' ),
            'sanitize_callback' => 'newsis_sanitize_typo_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Typography_Control( $wp_customize, 'global_button_typo', array(
                'label'	      => esc_html__( 'Typography', 'newsis' ),
                'section'     => 'newsis_buttons_section',
                'settings'    => 'global_button_typo',
                'tab'   => 'design',
                'fields'    => array( 'font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'text_transform', 'text_decoration')
            ))
        );

        // global button icon picker
        $wp_customize->add_setting( 'global_button_icon_picker', [
            'default'   =>  NI\newsis_get_customizer_default( 'global_button_icon_picker' ),
            'sanitize_callback' =>  'newsis_sanitize_icon_picker_control',
            'transport' => 'postMessage'
        ]);
        $wp_customize->add_control(
            new Newsis_WP_Icon_Picker_Control( $wp_customize, 'global_button_icon_picker', [
                'label' =>  esc_html__( 'Button Icon', 'newsis' ),
                'section'   =>  'newsis_buttons_section'
            ])
        );

        // global button label
        $wp_customize->add_setting( 'global_button_label', [
            'default'   =>  NI\newsis_get_customizer_default( 'global_button_label' ),
            'sanitize_callback' =>  'sanitize_text_field',
            'transport' => 'postMessage'
        ]);
        $wp_customize->add_control('global_button_label', [
            'label' =>  esc_html__( 'Button Label', 'newsis' ),
            'section'   =>  'newsis_buttons_section',
            'type'  =>  'text'
        ]);

        // button font size
        $wp_customize->add_setting( 'global_button_font_size', array(
            'default'   => NI\newsis_get_customizer_default( 'global_button_font_size' ),
            'sanitize_callback' => 'newsis_sanitize_responsive_range',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Responsive_Range_Control( $wp_customize, 'global_button_font_size', array(
                    'label'	      => esc_html__( 'Icon Size (px)', 'newsis' ),
                    'section'     => 'newsis_buttons_section',
                    'settings'    => 'global_button_font_size',
                    'unit'        => 'px',
                    'input_attrs' => array(
                    'max'         => 200,
                    'min'         => 1,
                    'step'        => 1,
                    'reset' => true
                )
            ))
        );
        
        // section- sidebar options section
        $wp_customize->add_section( 'newsis_sidebar_options_section', array(
            'title' => esc_html__( 'Sidebar Options', 'newsis' ),
            'panel' => 'newsis_global_panel'
        ));

        // frontpage sidebar layout heading
        $wp_customize->add_setting( 'frontpage_sidebar_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'frontpage_sidebar_layout_header', array(
                'label'	      => esc_html__( 'Frontpage Sidebar Layouts', 'newsis' ),
                'section'     => 'newsis_sidebar_options_section',
                'settings'    => 'frontpage_sidebar_layout_header'
            ))
        );

        // frontpage sidebar layout
        $wp_customize->add_setting( 'frontpage_sidebar_layout',
            array(
            'default'           => NI\newsis_get_customizer_default( 'frontpage_sidebar_layout' ),
            'sanitize_callback' => 'newsis_sanitize_select_control',
            )
        );
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Image_Control( $wp_customize, 'frontpage_sidebar_layout',
            array(
                'section'  => 'newsis_sidebar_options_section',
                'choices'  => newsis_get_customizer_sidebar_array()
            )
        ));

        // frontpage sidebar sticky option
        $wp_customize->add_setting( 'frontpage_sidebar_sticky_option', array(
            'default'   => NI\newsis_get_customizer_default( 'frontpage_sidebar_sticky_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'frontpage_sidebar_sticky_option', array(
                'label'	      => esc_html__( 'Enable sidebar sticky', 'newsis' ),
                'section'     => 'newsis_sidebar_options_section',
                'settings'    => 'frontpage_sidebar_sticky_option'
            ))
        );

        // archive sidebar layouts heading
        $wp_customize->add_setting( 'archive_sidebar_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'archive_sidebar_layout_header', array(
                'label'	      => esc_html__( 'Archive Sidebar Layouts', 'newsis' ),
                'section'     => 'newsis_sidebar_options_section',
                'settings'    => 'archive_sidebar_layout_header'
            ))
        );

        // archive sidebar layout
        $wp_customize->add_setting( 'archive_sidebar_layout',
            array(
            'default'           => NI\newsis_get_customizer_default( 'archive_sidebar_layout' ),
            'sanitize_callback' => 'newsis_sanitize_select_control',
            )
        );
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Image_Control( $wp_customize, 'archive_sidebar_layout',
            array(
                'section'  => 'newsis_sidebar_options_section',
                'choices'  => newsis_get_customizer_sidebar_array()
            )
        ));

        // archive sidebar sticky option
        $wp_customize->add_setting( 'archive_sidebar_sticky_option', array(
            'default'   => NI\newsis_get_customizer_default( 'archive_sidebar_sticky_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'archive_sidebar_sticky_option', array(
                'label'	      => esc_html__( 'Enable sidebar sticky', 'newsis' ),
                'section'     => 'newsis_sidebar_options_section',
                'settings'    => 'archive_sidebar_sticky_option'
            ))
        );

        // single sidebar layouts heading
        $wp_customize->add_setting( 'single_sidebar_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'single_sidebar_layout_header', array(
                'label'	      => esc_html__( 'Post Sidebar Layouts', 'newsis' ),
                'section'     => 'newsis_sidebar_options_section',
                'settings'    => 'single_sidebar_layout_header'
            ))
        );

        // single sidebar layout
        $wp_customize->add_setting( 'single_sidebar_layout',
            array(
            'default'           => NI\newsis_get_customizer_default( 'single_sidebar_layout' ),
            'sanitize_callback' => 'newsis_sanitize_select_control',
            )
        );
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Image_Control( $wp_customize, 'single_sidebar_layout',
            array(
                'section'  => 'newsis_sidebar_options_section',
                'choices'  => newsis_get_customizer_sidebar_array()
            )
        ));

        // single sidebar sticky option
        $wp_customize->add_setting( 'single_sidebar_sticky_option', array(
            'default'   => NI\newsis_get_customizer_default( 'single_sidebar_sticky_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'single_sidebar_sticky_option', array(
                'label'	      => esc_html__( 'Enable sidebar sticky', 'newsis' ),
                'section'     => 'newsis_sidebar_options_section',
                'settings'    => 'single_sidebar_sticky_option'
            ))
        );

        // page sidebar layouts heading
        $wp_customize->add_setting( 'page_sidebar_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'page_sidebar_layout_header', array(
                'label'	      => esc_html__( 'Page Sidebar Layouts', 'newsis' ),
                'section'     => 'newsis_sidebar_options_section',
                'settings'    => 'page_sidebar_layout_header'
            ))
        );

        // page sidebar layout
        $wp_customize->add_setting( 'page_sidebar_layout',
            array(
            'default'           => NI\newsis_get_customizer_default( 'page_sidebar_layout' ),
            'sanitize_callback' => 'newsis_sanitize_select_control',
            )
        );
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Image_Control( $wp_customize, 'page_sidebar_layout',
            array(
                'section'  => 'newsis_sidebar_options_section',
                'choices'  => newsis_get_customizer_sidebar_array()
            )
        ));

        // page sidebar sticky option
        $wp_customize->add_setting( 'page_sidebar_sticky_option', array(
            'default'   => NI\newsis_get_customizer_default( 'page_sidebar_sticky_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'page_sidebar_sticky_option', array(
                'label'	      => esc_html__( 'Enable sidebar sticky', 'newsis' ),
                'section'     => 'newsis_sidebar_options_section',
                'settings'    => 'page_sidebar_sticky_option'
            ))
        );

        // section- breadcrumb options section
        $wp_customize->add_section( 'newsis_breadcrumb_options_section', array(
            'title' => esc_html__( 'Breadcrumb Options', 'newsis' ),
            'panel' => 'newsis_global_panel'
        ));

        // breadcrumb option
        $wp_customize->add_setting( 'site_breadcrumb_option', array(
            'default'   => NI\newsis_get_customizer_default( 'site_breadcrumb_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'site_breadcrumb_option', array(
                'label'	      => esc_html__( 'Show breadcrumb trails', 'newsis' ),
                'section'     => 'newsis_breadcrumb_options_section',
                'settings'    => 'site_breadcrumb_option'
            ))
        );

        // breadcrumb type 
        $wp_customize->add_setting( 'site_breadcrumb_type', array(
            'sanitize_callback' => 'newsis_sanitize_select_control',
            'default'   => NI\newsis_get_customizer_default( 'site_breadcrumb_type' )
        ));
        $wp_customize->add_control( 'site_breadcrumb_type', array(
            'type'      => 'select',
            'section'   => 'newsis_breadcrumb_options_section',
            'label'     => esc_html__( 'Breadcrumb type', 'newsis' ),
            'description' => esc_html__( 'If you use other than "default" one you will need to install and activate respective plugins Breadcrumb NavXT, Yoast SEO and Rank Math SEO', 'newsis' ),
            'choices'   => array(
                'default' => __( 'Default', 'newsis' ),
                'bcn'  => __( 'NavXT', 'newsis' ),
                'yoast'  => __( 'Yoast SEO', 'newsis' ),
                'rankmath'  => __( 'Rank Math', 'newsis' )
            )
        ));

        // breadcrumb hook on
        $wp_customize->add_setting( 'site_breadcrumb_hook_on', array(
            'sanitize_callback' => 'newsis_sanitize_select_control',
            'default'   => NI\newsis_get_customizer_default( 'site_breadcrumb_hook_on' )
        ));
        $wp_customize->add_control( 'site_breadcrumb_hook_on', array(
            'type'      => 'select',
            'section'   => 'newsis_breadcrumb_options_section',
            'label'     => esc_html__( 'Display Breadcrumb On', 'newsis' ),
            'choices'   => array(
                'main_container' => __( 'Before Main Container - Full Width', 'newsis' ),
                'inner_container'  => __( 'Before Inner Container', 'newsis' )
            )
        ));

        // section- scroll to top options
        $wp_customize->add_section( 'newsis_stt_options_section', array(
            'title' => esc_html__( 'Scroll To Top', 'newsis' ),
            'panel' => 'newsis_global_panel'
        ));

        // Resposive vivibility option
        $wp_customize->add_setting( 'stt_responsive_option', array(
            'default' => NI\newsis_get_customizer_default( 'stt_responsive_option' ),
            'sanitize_callback' => 'newsis_sanitize_responsive_multiselect_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Responsive_Multiselect_Tab_Control( $wp_customize, 'stt_responsive_option', array(
                'label'	      => esc_html__( 'Scroll To Top Visibility', 'newsis' ),
                'section'     => 'newsis_stt_options_section',
                'settings'    => 'stt_responsive_option'
            ))
        );

       // stt icon picker
        $wp_customize->add_setting( 'stt_icon_picker', [
            'default'   =>  NI\newsis_get_customizer_default( 'stt_icon_picker' ),
            'sanitize_callback' =>  'newsis_sanitize_icon_picker_control',
            'transport' => 'postMessage'
        ]);
        $wp_customize->add_control(
            new Newsis_WP_Icon_Picker_Control( $wp_customize, 'stt_icon_picker', [
                'label' =>  esc_html__( 'Scroll to Top Icon', 'newsis' ),
                'section'   =>  'newsis_stt_options_section'
            ])
        );

        // scroll to top alignment
        $wp_customize->add_setting( 'stt_alignment', array(
            'default' => NI\newsis_get_customizer_default( 'stt_alignment' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Tab_Control( $wp_customize, 'stt_alignment', array(
                'label'	      => esc_html__( 'Button Align', 'newsis' ),
                'section'     => 'newsis_stt_options_section',
                'settings'    => 'stt_alignment',
                'choices' => array(
                    array(
                        'value' => 'left',
                        'label' => esc_html__('Left', 'newsis' )
                    ),
                    array(
                        'value' => 'center',
                        'label' => esc_html__('Center', 'newsis' )
                    ),
                    array(
                        'value' => 'right',
                        'label' => esc_html__('Right', 'newsis' )
                    )
                )
            ))
        );
    }
    add_action( 'customize_register', 'newsis_customizer_global_panel', 10 );
endif;

if( !function_exists( 'newsis_customizer_site_identity_panel' ) ) :
    /**
     * Register site identity settings
     * 
     */
    function newsis_customizer_site_identity_panel( $wp_customize ) {
        /**
         * Register "Site Identity Options" panel
         * 
         */
        $wp_customize->add_panel( 'newsis_site_identity_panel', array(
            'title' => esc_html__( 'Site Identity', 'newsis' ),
            'priority' => 5
        ));
        $wp_customize->get_section( 'title_tagline' )->panel = 'newsis_site_identity_panel'; // assing title tagline section to site identity panel
        $wp_customize->get_section( 'title_tagline' )->title = esc_html__( 'Logo & Site Icon', 'newsis' ); // modify site logo label

        /**
         * Site Title Section
         * 
         * panel - newsis_site_identity_panel
         */
        $wp_customize->add_section( 'newsis_site_title_section', array(
            'title' => esc_html__( 'Site Title & Tagline', 'newsis' ),
            'panel' => 'newsis_site_identity_panel',
            'priority'  => 30,
        ));
        $wp_customize->get_control( 'blogname' )->section = 'newsis_site_title_section';
        $wp_customize->get_control( 'display_header_text' )->section = 'newsis_site_title_section';
        $wp_customize->get_control( 'display_header_text' )->label = esc_html__( 'Display site title', 'newsis' );
        $wp_customize->get_control( 'blogdescription' )->section = 'newsis_site_title_section';
        
        // site logo width
        $wp_customize->add_setting( 'newsis_site_logo_width', array(
            'default'   => NI\newsis_get_customizer_default( 'newsis_site_logo_width' ),
            'sanitize_callback' => 'newsis_sanitize_responsive_range',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Responsive_Range_Control( $wp_customize, 'newsis_site_logo_width', array(
                    'label'	      => esc_html__( 'Logo Width (px)', 'newsis' ),
                    'section'     => 'title_tagline',
                    'settings'    => 'newsis_site_logo_width',
                    'unit'        => 'px',
                    'input_attrs' => array(
                    'max'         => 400,
                    'min'         => 1,
                    'step'        => 1,
                    'reset' => true
                )
            ))
        );

        // site title section tab
        $wp_customize->add_setting( 'site_title_section_tab', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'   => 'general'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Tab_Control( $wp_customize, 'site_title_section_tab', array(
                'section'     => 'newsis_site_title_section',
                'priority'  => 1,
                'choices'  => array(
                    array(
                        'name'  => 'general',
                        'title'  => esc_html__( 'General', 'newsis' )
                    ),
                    array(
                        'name'  => 'design',
                        'title'  => esc_html__( 'Design', 'newsis' )
                    )
                )
            ))
        );

        // blog description option
        $wp_customize->add_setting( 'blogdescription_option', array(
            'default'        => true,
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 'blogdescription_option', array(
            'label'    => esc_html__( 'Display site description', 'newsis' ),
            'section'  => 'newsis_site_title_section',
            'type'     => 'checkbox',
            'priority' => 50
        ));

        $wp_customize->get_control( 'header_textcolor' )->section = 'newsis_site_title_section';
        $wp_customize->get_control( 'header_textcolor' )->priority = 60;
        $wp_customize->get_control( 'header_textcolor' )->label = esc_html__( 'Site Title Color', 'newsis' );

        // header text hover color
        $wp_customize->add_setting( 'site_title_hover_textcolor', array(
            'default' => NI\newsis_get_customizer_default( 'site_title_hover_textcolor' ),
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Default_Color_Control( $wp_customize, 'site_title_hover_textcolor', array(
                'label'      => esc_html__( 'Site Title Hover Color', 'newsis' ),
                'section'    => 'newsis_site_title_section',
                'settings'   => 'site_title_hover_textcolor',
                'priority'    => 70,
                'tab'   => 'design'
            ))
        );

        // site description color
        $wp_customize->add_setting( 'site_description_color', array(
            'default' => NI\newsis_get_customizer_default( 'site_description_color' ),
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Default_Color_Control( $wp_customize, 'site_description_color', array(
                'label'      => esc_html__( 'Site Description Color', 'newsis' ),
                'section'    => 'newsis_site_title_section',
                'settings'   => 'site_description_color',
                'priority'    => 70,
                'tab'   => 'design'
            ))
        );

        // site title typo
        $wp_customize->add_setting( 'site_title_typo', array(
            'default'   => NI\newsis_get_customizer_default( 'site_title_typo' ),
            'sanitize_callback' => 'newsis_sanitize_typo_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Typography_Control( $wp_customize, 'site_title_typo', array(
                'label'	      => esc_html__( 'Site Title Typography', 'newsis' ),
                'section'     => 'newsis_site_title_section',
                'settings'    => 'site_title_typo',
                'tab'   => 'design',
                'fields'    => array( 'font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'text_transform', 'text_decoration')
            ))
        );

        // site tagline typo
        $wp_customize->add_setting( 'site_tagline_typo', array(
            'default'   => NI\newsis_get_customizer_default( 'site_tagline_typo' ),
            'sanitize_callback' => 'newsis_sanitize_typo_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Typography_Control( $wp_customize, 'site_tagline_typo', array(
                'label'	      => esc_html__( 'Site Tagline Typography', 'newsis' ),
                'section'     => 'newsis_site_title_section',
                'settings'    => 'site_tagline_typo',
                'tab'   => 'design',
                'fields'    => array( 'font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'text_transform', 'text_decoration')
            ))
        );
    }
    add_action( 'customize_register', 'newsis_customizer_site_identity_panel', 10 );
endif;

if( !function_exists( 'newsis_customizer_top_header_panel' ) ) :
    /**
     * Register header options settings
     * 
     */
    function newsis_customizer_top_header_panel( $wp_customize ) {
        /**
         * Top header section
         * 
         */
        $wp_customize->add_section( 'newsis_top_header_section', array(
            'title' => esc_html__( 'Top Header', 'newsis' ),
            'priority'  => 68
        ));
        
        // section tab
        $wp_customize->add_setting( 'top_header_section_tab', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'   => 'general'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Tab_Control( $wp_customize, 'top_header_section_tab', array(
                'section'     => 'newsis_top_header_section',
                'choices'  => array(
                    array(
                        'name'  => 'general',
                        'title'  => esc_html__( 'General', 'newsis' )
                    ),
                    array(
                        'name'  => 'design',
                        'title'  => esc_html__( 'Design', 'newsis' )
                    )
                )
            ))
        );
        
        // Top header option
        $wp_customize->add_setting( 'top_header_option', array(
            'default'         => NI\newsis_get_customizer_default( 'top_header_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
    
        $wp_customize->add_control( 
            new Newsis_WP_Toggle_Control( $wp_customize, 'top_header_option', array(
                'label'	      => esc_html__( 'Show top header', 'newsis' ),
                'description' => esc_html__( 'Toggle to enable or disable top header bar', 'newsis' ),
                'section'     => 'newsis_top_header_section',
                'settings'    => 'top_header_option'
            ))
        );

        // Top header date time option
        $wp_customize->add_setting( 'top_header_date_time_option', array(
            'default'         => NI\newsis_get_customizer_default( 'top_header_date_time_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'top_header_date_time_option', array(
                'label'	      => esc_html__( 'Show date and time', 'newsis' ),
                'section'     => 'newsis_top_header_section',
                'settings'    => 'top_header_date_time_option',
            ))
        );

        // top header right content type
        $wp_customize->add_setting( 'top_header_right_content_type', array(
            'default' => NI\newsis_get_customizer_default( 'top_header_right_content_type' ),
            'sanitize_callback' => 'newsis_sanitize_select_control'
        ));
        $wp_customize->add_control( 'top_header_right_content_type', array(
            'type'      => 'select',
            'section'   => 'newsis_top_header_section',
            'label'     => __( 'Ticker news / Nav menu choices', 'newsis' ),
            'choices'   => array(
                'ticker-news' => esc_html__( 'Ticker News', 'newsis' ),
                'nav-menu' => esc_html__( 'Nav Menu', 'newsis' )
            )
        ));

        // Top header ticker news option
        $wp_customize->add_setting( 'top_header_menu_option', array(
            'default'         => NI\newsis_get_customizer_default( 'top_header_menu_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'top_header_menu_option', array(
                'label'	      => esc_html__( 'Show nav menu', 'newsis' ),
                'section'     => 'newsis_top_header_section',
                'settings'    => 'top_header_menu_option',
                'active_callback'   => function( $setting ) {
                    if ( $setting->manager->get_setting( 'top_header_right_content_type' )->value() == 'nav-menu' ) {
                        return true;
                    }
                    return false;
                }
            ))
        );

        // Top header ticker news option
        $wp_customize->add_setting( 'top_header_ticker_news_option', array(
            'default'         => NI\newsis_get_customizer_default( 'top_header_ticker_news_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'top_header_ticker_news_option', array(
                'label'	      => esc_html__( 'Show ticker news', 'newsis' ),
                'section'     => 'newsis_top_header_section',
                'settings'    => 'top_header_ticker_news_option',
                'active_callback'   => function( $setting ) {
                    if ( $setting->manager->get_setting( 'top_header_right_content_type' )->value() == 'ticker-news' ) {
                        return true;
                    }
                    return false;
                }
            ))
        );

        // Ticker News categories
        $wp_customize->add_setting( 'top_header_ticker_news_categories', array(
            'default' => NI\newsis_get_customizer_default( 'top_header_ticker_news_categories' ),
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Categories_Multiselect_Control( $wp_customize, 'top_header_ticker_news_categories', array(
                'label'     => esc_html__( 'Posts Categories', 'newsis' ),
                'section'   => 'newsis_top_header_section',
                'settings'  => 'top_header_ticker_news_categories',
                'choices'   => newsis_get_multicheckbox_categories_simple_array(),
                'active_callback'   => function( $setting ) {
                    if ( $setting->manager->get_setting( 'top_header_ticker_news_option' )->value() && $setting->manager->get_setting( 'top_header_right_content_type' )->value() == 'ticker-news' ) {
                        return true;
                    }
                    return false;
                }
            ))
        );

        // Ticker News posts
        $wp_customize->add_setting( 'top_header_ticker_news_posts', array(
            'default' => NI\newsis_get_customizer_default( 'top_header_ticker_news_posts' ),
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Posts_Multiselect_Control( $wp_customize, 'top_header_ticker_news_posts', array(
                'label'     => esc_html__( 'Posts To Include', 'newsis' ),
                'section'   => 'newsis_top_header_section',
                'settings'  => 'top_header_ticker_news_posts',
                'choices'   => newsis_get_multicheckbox_posts_simple_array(),
                'active_callback'   => function( $setting ) {
                    if ( $setting->manager->get_setting( 'top_header_ticker_news_option' )->value() && $setting->manager->get_setting( 'top_header_right_content_type' )->value() == 'ticker-news' ) {
                        return true;
                    }
                    return false;
                }
            ))
        );

        // top header post query date range
        $wp_customize->add_setting( 'top_header_ticker_news_date_filter', array(
            'default' => NI\newsis_get_customizer_default( 'top_header_ticker_news_date_filter' ),
            'sanitize_callback' => 'newsis_sanitize_select_control'
        ));
        $wp_customize->add_control( 'top_header_ticker_news_date_filter', array(
            'label'     => __( 'Date Range', 'newsis' ),
            'type'      => 'select',
            'section'   => 'newsis_top_header_section',
            'choices'   => newsis_get_date_filter_choices_array(),
            'active_callback'   => function( $setting ) {
                if ( $setting->manager->get_setting( 'top_header_ticker_news_option' )->value() && $setting->manager->get_setting( 'top_header_right_content_type' )->value() == 'ticker-news' ) {
                    return true;
                }
                return false;
            }
        ));

        // top header social option
        $wp_customize->add_setting( 'top_header_social_option', array(
            'default'   => NI\newsis_get_customizer_default( 'top_header_social_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'top_header_social_option', array(
                'label'	      => esc_html__( 'Show social icons', 'newsis' ),
                'section'     => 'newsis_top_header_section',
                'settings'    => 'top_header_social_option',
            ))
        );

        // top header show social icons hover animation
        $wp_customize->add_setting( 'top_header_social_icons_hover_animation', array(
            'default'   => NI\newsis_get_customizer_default( 'top_header_social_icons_hover_animation' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'top_header_social_icons_hover_animation', array(
                'label'	      => esc_html__( 'Show social icons hover animation', 'newsis' ),
                'section'     => 'newsis_top_header_section',
                'settings'    => 'top_header_social_icons_hover_animation',
            ))
        );

        // Redirect header social icons link
        $wp_customize->add_setting( 'top_header_social_icons_redirects', array(
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Redirect_Control( $wp_customize, 'top_header_social_icons_redirects', array(
                'section'     => 'newsis_top_header_section',
                'settings'    => 'top_header_social_icons_redirects',
                'choices'     => array(
                    'header-social-icons' => array(
                        'type'  => 'section',
                        'id'    => 'newsis_social_icons_section',
                        'label' => esc_html__( 'Manage social icons', 'newsis' )
                    )
                )
            ))
        );

        // Top header background colors group control
        $wp_customize->add_setting( 'top_header_background_color_group', array(
            'default'   => NI\newsis_get_customizer_default( 'top_header_background_color_group' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Color_Group_Control( $wp_customize, 'top_header_background_color_group', array(
                'label'	      => esc_html__( 'Section Background', 'newsis' ),
                'section'     => 'newsis_top_header_section',
                'settings'    => 'top_header_background_color_group',
                'tab'   => 'design'
            ))
        );
    }
    add_action( 'customize_register', 'newsis_customizer_top_header_panel', 10 );
endif;

if( !function_exists( 'newsis_customizer_header_panel' ) ) :
    /**
     * Register header options settings
     * 
     */
    function newsis_customizer_header_panel( $wp_customize ) {
        /**
         * Header panel
         * 
         */
        $wp_customize->add_panel( 'newsis_header_panel', array(
            'title' => esc_html__( 'Theme Header', 'newsis' ),
            'priority'  => 69
        ));
        
        // Header ads banner section
        $wp_customize->add_section( 'newsis_header_ads_banner_section', array(
            'title' => esc_html__( 'Ads Banner', 'newsis' ),
            'panel' => 'newsis_header_panel',
            'priority'  => 10
        ));

        // Header Ads Banner setting heading
        $wp_customize->add_setting( 'newsis_header_ads_banner_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'newsis_header_ads_banner_header', array(
                'label'	      => esc_html__( 'Ads Banner Setting', 'newsis' ),
                'section'     => 'newsis_header_ads_banner_section',
                'settings'    => 'newsis_header_ads_banner_header'
            ))
        );

        // Resposive vivibility option
        $wp_customize->add_setting( 'header_ads_banner_responsive_option', array(
            'default' => NI\newsis_get_customizer_default( 'header_ads_banner_responsive_option' ),
            'sanitize_callback' => 'newsis_sanitize_responsive_multiselect_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Responsive_Multiselect_Tab_Control( $wp_customize, 'header_ads_banner_responsive_option', array(
                'label'	      => esc_html__( 'Ads Banner Visibility', 'newsis' ),
                'section'     => 'newsis_header_ads_banner_section',
                'settings'    => 'header_ads_banner_responsive_option'
            ))
        );

        // Header ads banner type
        $wp_customize->add_setting( 'header_ads_banner_type', array(
            'default' => NI\newsis_get_customizer_default( 'header_ads_banner_type' ),
            'sanitize_callback' => 'newsis_sanitize_select_control'
        ));
        $wp_customize->add_control( 'header_ads_banner_type', array(
            'type'      => 'select',
            'section'   => 'newsis_header_ads_banner_section',
            'label'     => __( 'Ads banner type', 'newsis' ),
            'description' => __( 'Choose to display ads content from.', 'newsis' ),
            'choices'   => array(
                'none'  => esc_html__( 'None', 'newsis' ),
                'custom' => esc_html__( 'Custom', 'newsis' )
            ),
        ));

        // ads image field
        $wp_customize->add_setting( 'header_ads_banner_custom_image', array(
            'default' => NI\newsis_get_customizer_default( 'header_ads_banner_custom_image' ),
            'sanitize_callback' => 'absint',
        ));
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'header_ads_banner_custom_image', array(
            'section' => 'newsis_header_ads_banner_section',
            'mime_type' => 'image',
            'label' => esc_html__( 'Ads Image', 'newsis' ),
            'description' => esc_html__( 'Recommended size for ad image is 900 (width) * 350 (height)', 'newsis' ),
            'active_callback'   => function( $setting ) {
                if ( $setting->manager->get_setting( 'header_ads_banner_type' )->value() === 'custom' ) {
                    return true;
                }
                return false;
            }
        )));

        // ads url field
        $wp_customize->add_setting( 'header_ads_banner_custom_url', array(
            'default' => NI\newsis_get_customizer_default( 'header_ads_banner_custom_url' ),
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control( 'header_ads_banner_custom_url', array(
            'type'  => 'url',
            'section'   => 'newsis_header_ads_banner_section',
            'label'     => esc_html__( 'Ads url', 'newsis' ),
            'active_callback'   => function( $setting ) {
                if ( $setting->manager->get_setting( 'header_ads_banner_type' )->value() === 'custom' ) {
                    return true;
                }
                return false;
            }
        ));

        // header general settings section
        $wp_customize->add_section( 'newsis_header_general_settings_section', array(
            'title' => esc_html__( 'General Settings', 'newsis' ),
            'panel' => 'newsis_header_panel',
            'priority'  => 5
        ));

        // section tab
        $wp_customize->add_setting( 'main_header_section_tab', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'   => 'general'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Tab_Control( $wp_customize, 'main_header_section_tab', array(
                'section'     => 'newsis_header_general_settings_section',
                'choices'  => array(
                    array(
                        'name'  => 'general',
                        'title'  => esc_html__( 'General', 'newsis' )
                    ),
                    array(
                        'name'  => 'design',
                        'title'  => esc_html__( 'Design', 'newsis' )
                    )
                )
            ))
        );

        // header width layout
        $wp_customize->add_setting( 'header_width_layout', array(
            'default' => NI\newsis_get_customizer_default( 'header_width_layout' ),
            'sanitize_callback' => 'newsis_sanitize_select_control',
            'transport' =>  'postMessage'
        ));
        $wp_customize->add_control( 'header_width_layout', array(
            'type'      => 'select',
            'section'   => 'newsis_header_general_settings_section',
            'label'     => __( 'Width Layout', 'newsis' ),
            'choices'   => array(
                'contain' => esc_html__( 'Container', 'newsis' ),
                'full-width' => esc_html__( 'Full Width', 'newsis' )
            )
        ));

        // redirect site logo section
        $wp_customize->add_setting( 'header_site_logo_redirects', array(
            'sanitize_callback' => 'newsis_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Redirect_Control( $wp_customize, 'header_site_logo_redirects', array(
                'section'     => 'newsis_header_general_settings_section',
                'settings'    => 'header_site_logo_redirects',
                'choices'     => array(
                    'header-social-icons' => array(
                        'type'  => 'section',
                        'id'    => 'title_tagline',
                        'label' => esc_html__( 'Manage Site Logo', 'newsis' )
                    )
                )
            ))
        );

        // redirect site title section
        $wp_customize->add_setting( 'header_site_title_redirects', array(
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Redirect_Control( $wp_customize, 'header_site_title_redirects', array(
                'section'     => 'newsis_header_general_settings_section',
                'settings'    => 'header_site_title_redirects',
                'choices'     => array(
                    'header-social-icons' => array(
                        'type'  => 'section',
                        'id'    => 'newsis_site_title_section',
                        'label' => esc_html__( 'Manage Site & Tagline', 'newsis' )
                    )
                )
            ))
        );

        // header sticky option
        $wp_customize->add_setting( 'theme_header_sticky', array(
            'default'   => NI\newsis_get_customizer_default( 'theme_header_sticky' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'theme_header_sticky', array(
                'label'	      => esc_html__( 'Enable header section sticky ( on scroll up )', 'newsis' ),
                'section'     => 'newsis_header_general_settings_section',
                'settings'    => 'theme_header_sticky'
            ))
        );

        // header sticky option on scroll down
        $wp_customize->add_setting( 'theme_header_sticky_on_scroll_down', array(
            'default'   => NI\newsis_get_customizer_default( 'theme_header_sticky_on_scroll_down' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'theme_header_sticky_on_scroll_down', array(
                'label'	      => esc_html__( 'Enable header section sticky ( on scroll down )', 'newsis' ),
                'section'     => 'newsis_header_general_settings_section',
                'settings'    => 'theme_header_sticky_on_scroll_down'
            ))
        );

        // header top and bottom padding
        $wp_customize->add_setting( 'header_vertical_padding', array(
            'default'   => NI\newsis_get_customizer_default( 'header_vertical_padding' ),
            'sanitize_callback' => 'newsis_sanitize_responsive_range',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Responsive_Range_Control( $wp_customize, 'header_vertical_padding', array(
                    'label'	      => esc_html__( 'Vertical Padding (px)', 'newsis' ),
                    'section'     => 'newsis_header_general_settings_section',
                    'settings'    => 'header_vertical_padding',
                    'unit'        => 'px',
                    'tab'   => 'design',
                    'input_attrs' => array(
                    'max'         => 500,
                    'min'         => 1,
                    'step'        => 1,
                    'reset' => true
                )
            ))
        );

        // Header background colors setting heading
        $wp_customize->add_setting( 'header_background_color_group', array(
            'default'   => NI\newsis_get_customizer_default( 'header_background_color_group' ),
            'sanitize_callback' => 'newsis_sanitize_color_image_group_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Color_Image_Group_Control( $wp_customize, 'header_background_color_group', array(
                'label'	      => esc_html__( 'Background', 'newsis' ),
                'section'     => 'newsis_header_general_settings_section',
                'settings'    => 'header_background_color_group',
                'tab'   => 'design'
            ))
        );

        // Header newsletter section
        $wp_customize->add_section( 'newsis_header_newsletter_section', array(
            'title' => esc_html__( 'Newsletter / Subscribe Button', 'newsis' ),
            'panel' => 'newsis_header_panel',
            'priority'  => 15
        ));

        // header newsletter button option
        $wp_customize->add_setting( 'header_newsletter_option', array(
            'default'   => NI\newsis_get_customizer_default( 'header_newsletter_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'header_newsletter_option', array(
                'label'	      => esc_html__( 'Show newsletter button', 'newsis' ),
                'section'     => 'newsis_header_newsletter_section',
                'settings'    => 'header_newsletter_option'
            ))
        );

        // newsletter icon picker
        $wp_customize->add_setting( 'newsletter_icon_picker', [
            'default'   =>  NI\newsis_get_customizer_default( 'newsletter_icon_picker' ),
            'sanitize_callback' =>  'newsis_sanitize_icon_picker_control',
            'transport' => 'postMessage'
        ]);
        $wp_customize->add_control(
            new Newsis_WP_Icon_Picker_Control( $wp_customize, 'newsletter_icon_picker', [
                'label' =>  esc_html__( 'Button Icon', 'newsis' ),
                'section'   =>  'newsis_header_newsletter_section'
            ])
        );

        // newsletter label
        $wp_customize->add_setting( 'newsletter_label', [
            'default'   =>  NI\newsis_get_customizer_default( 'newsletter_label' ),
            'sanitize_callback' =>  'sanitize_text_field',
            'transport' => 'postMessage'
        ]);
        $wp_customize->add_control('newsletter_label', [
            'label' =>  esc_html__( 'Button Label', 'newsis' ),
            'section'   =>  'newsis_header_newsletter_section',
            'type'  =>  'text'
        ]);
        
        // newsletter redirect href target
        $wp_customize->add_setting( 'header_newsletter_redirect_href_target', array(
            'default' => NI\newsis_get_customizer_default( 'header_newsletter_redirect_href_target' ),
            'sanitize_callback' => 'newsis_sanitize_select_control'
        ));
        $wp_customize->add_control( 'header_newsletter_redirect_href_target', array(
            'type'      => 'select',
            'section'   => 'newsis_header_newsletter_section',
            'label'     => __( 'Open link on', 'newsis' ),
            'choices'   => array(
                '_self' => esc_html__( 'Open in same tab', 'newsis' ),
                '_blank' => esc_html__( 'Open in new tab', 'newsis' )
            )
        ));

        // newsletter redirect url
        $wp_customize->add_setting( 'header_newsletter_redirect_href_link', array(
            'default' => NI\newsis_get_customizer_default( 'header_newsletter_redirect_href_link' ),
            'sanitize_callback' => 'newsis_sanitize_url',
        ));
        $wp_customize->add_control( 'header_newsletter_redirect_href_link', array(
            'label' => esc_html__( 'Redirect URL.', 'newsis' ),
            'description'   => esc_html__( 'Add url for the button to redirect.', 'newsis' ),
            'section'   => 'newsis_header_newsletter_section',
            'type'  => 'url'
        ));

        // newsletter show border
        $wp_customize->add_setting( 'header_newsletter_show_border', array(
            'default'   => NI\newsis_get_customizer_default( 'header_newsletter_show_border' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'header_newsletter_show_border', array(
                'label'	      => esc_html__( 'Show border', 'newsis' ),
                'section'     => 'newsis_header_newsletter_section',
                'settings'    => 'header_newsletter_show_border',
            ))
        );

        // newsletter show hover animation
        $wp_customize->add_setting( 'header_newsletter_show_hover_animation', array(
            'default'   => NI\newsis_get_customizer_default( 'header_newsletter_show_hover_animation' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'header_newsletter_show_hover_animation', array(
                'label'	      => esc_html__( 'Show hover animation', 'newsis' ),
                'section'     => 'newsis_header_newsletter_section',
                'settings'    => 'header_newsletter_show_hover_animation'
            ))
        );

        // Header random news section
        $wp_customize->add_section( 'newsis_header_random_news_section', array(
            'title' => esc_html__( 'Random News', 'newsis' ),
            'panel' => 'newsis_header_panel',
            'priority'  => 15
        ));

        // header random news button option
        $wp_customize->add_setting( 'header_random_news_option', array(
            'default'   => NI\newsis_get_customizer_default( 'header_random_news_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'header_random_news_option', array(
                'label'	      => esc_html__( 'Show random news button', 'newsis' ),
                'section'     => 'newsis_header_random_news_section',
                'settings'    => 'header_random_news_option'
            ))
        );

        // random news icon picker
        $wp_customize->add_setting( 'random_news_icon_picker', [
            'default'   =>  NI\newsis_get_customizer_default( 'random_news_icon_picker' ),
            'sanitize_callback' =>  'newsis_sanitize_icon_picker_control',
            'transport' => 'postMessage'
        ]);
        $wp_customize->add_control(
            new Newsis_WP_Icon_Picker_Control( $wp_customize, 'random_news_icon_picker', [
                'label' =>  esc_html__( 'Button Icon', 'newsis' ),
                'section'   =>  'newsis_header_random_news_section'
            ])
        );

        // random news label
        $wp_customize->add_setting( 'random_news_label', [
            'default'   =>  NI\newsis_get_customizer_default( 'random_news_label' ),
            'sanitize_callback' =>  'sanitize_text_field',
            'transport' => 'postMessage'
        ]);
        $wp_customize->add_control('random_news_label', [
            'label' =>  esc_html__( 'Button Label', 'newsis' ),
            'section'   =>  'newsis_header_random_news_section',
            'type'  =>  'text'
        ]);

        // random news redirect href target
        $wp_customize->add_setting( 'header_random_news_redirect_href_target', array(
            'default' => NI\newsis_get_customizer_default( 'header_random_news_redirect_href_target' ),
            'sanitize_callback' => 'newsis_sanitize_select_control'
        ));
        $wp_customize->add_control( 'header_random_news_redirect_href_target', array(
            'type'      => 'select',
            'section'   => 'newsis_header_random_news_section',
            'label'     => __( 'Open link on', 'newsis' ),
            'choices'   => array(
                '_self' => esc_html__( 'Open in same tab', 'newsis' ),
                '_blank' => esc_html__( 'Open in new tab', 'newsis' )
            )
        ));

        /**
         * Menu Options Section
         * 
         * panel - newsis_header_options_panel
         */
        $wp_customize->add_section( 'newsis_header_menu_option_section', array(
            'title' => esc_html__( 'Menu Options', 'newsis' ),
            'panel' => 'newsis_header_panel',
            'priority'  => 30,
        ));

        // menu section tab
        $wp_customize->add_setting( 'header_menu_section_tab', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'   => 'general'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Tab_Control( $wp_customize, 'header_menu_section_tab', array(
                'section'     => 'newsis_header_menu_option_section',
                'choices'  => array(
                    array(
                        'name'  => 'general',
                        'title'  => esc_html__( 'Design', 'newsis' )
                    ),
                    array(
                        'name'  => 'typo',
                        'title'  => esc_html__( 'Typography', 'newsis' )
                    )
                )
            ))
        );

        // live search target
        $wp_customize->add_setting( 'header_menu_hover_effect', array(
            'sanitize_callback' => 'newsis_sanitize_select_control',
            'default'   => NI\newsis_get_customizer_default( 'header_menu_hover_effect' ),
            'transport' =>  'postMessage'
        ));
        $wp_customize->add_control( 'header_menu_hover_effect', array(
            'type'  => 'select',
            'section'   => 'newsis_header_menu_option_section',
            'label' => esc_html__( 'Hover Effect', 'newsis' ),
            'tab'   => 'general',
            'choices'   => array(
                'none' => esc_html__( 'None', 'newsis' ),
                'one'  => esc_html__( 'One', 'newsis' )
            )
        ));

        // header menu text color
        $wp_customize->add_setting( 'header_menu_color', array(
            'default'   => NI\newsis_get_customizer_default( 'header_menu_color' ),
            'sanitize_callback' => 'newsis_sanitize_color_group_picker_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Color_Group_Picker_Control( $wp_customize, 'header_menu_color', array(
                'label'     => esc_html__( 'Text Color', 'newsis' ),
                'section'   => 'newsis_header_menu_option_section',
                'settings'  => 'header_menu_color',
                'tab'   => 'general'
            ))
        );

        // active menu color
        $wp_customize->add_setting( 'header_active_menu_color', array(
            'default'   => NI\newsis_get_customizer_default( 'header_active_menu_color' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'newsis_sanitize_color_picker_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Color_Picker_Control( $wp_customize, 'header_active_menu_color', array(
                'label'	      => esc_html__( 'Active Menu Color', 'newsis' ),
                'section'     => 'newsis_header_menu_option_section',
                'settings'    => 'header_active_menu_color',
                'tab'   => 'general'
            ))
        );
        
        // header menu background color group
        $wp_customize->add_setting( 'header_menu_background_color_group', array(
            'default'   => NI\newsis_get_customizer_default( 'header_menu_background_color_group' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Color_Group_Control( $wp_customize, 'header_menu_background_color_group', array(
                'label'	      => esc_html__( 'Background', 'newsis' ),
                'section'     => 'newsis_header_menu_option_section',
                'settings'    => 'header_menu_background_color_group',
                'tab'   => 'general'
            ))
        );

        // mobile menu bottom header
        $wp_customize->add_setting( 'header_mobile_menu_button_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'header_mobile_menu_button_header', array(
                'label'	      => esc_html__( 'Mobile Menu', 'newsis' ),
                'section'     => 'newsis_header_menu_option_section',
                'settings'    => 'header_mobile_menu_button_header',
                'tab'   => 'general'
            ))
        );

        // mobile menu button
        $wp_customize->add_setting( 'header_mobile_menu_button_color', array(
            'default'   => NI\newsis_get_customizer_default( 'header_mobile_menu_button_color' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'newsis_sanitize_color_picker_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Color_Picker_Control( $wp_customize, 'header_mobile_menu_button_color', array(
                'label'	      => esc_html__( 'Mobile Menu Toggle', 'newsis' ),
                'section'     => 'newsis_header_menu_option_section',
                'settings'    => 'header_mobile_menu_button_color',
                'tab'   => 'general'
            ))
        );

        // mobile menu text color
        $wp_customize->add_setting( 'header_mobile_menu_text_color', array(
            'default'   => NI\newsis_get_customizer_default( 'header_mobile_menu_text_color' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'newsis_sanitize_color_picker_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Color_Picker_Control( $wp_customize, 'header_mobile_menu_text_color', array(
                'label'	      => esc_html__( 'Mobile Menu Text Color', 'newsis' ),
                'section'     => 'newsis_header_menu_option_section',
                'settings'    => 'header_mobile_menu_text_color',
                'tab'   => 'general'
            ))
        );

        $wp_customize->add_setting( 'header_mobile_menu_background_color', array(
            'default'   => NI\newsis_get_customizer_default( 'header_mobile_menu_background_color' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Color_Group_Control( $wp_customize, 'header_mobile_menu_background_color', array(
                'label'	      => esc_html__( 'Mobile Menu Background', 'newsis' ),
                'section'     => 'newsis_header_menu_option_section',
                'settings'    => 'header_mobile_menu_background_color',
                'tab'   => 'general'
            ))
        );

        // menu typo
        $wp_customize->add_setting( 'header_menu_typo', array(
            'default'   => NI\newsis_get_customizer_default( 'header_menu_typo' ),
            'sanitize_callback' => 'newsis_sanitize_typo_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Typography_Control( $wp_customize, 'header_menu_typo', array(
                'label'	      => esc_html__( 'Main Menu', 'newsis' ),
                'section'     => 'newsis_header_menu_option_section',
                'settings'    => 'header_menu_typo',
                'tab'   => 'typo',
                'fields'    => array( 'font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'text_transform', 'text_decoration')
            ))
        );

        // sub menu typo
        $wp_customize->add_setting( 'header_sub_menu_typo', array(
            'default'   => NI\newsis_get_customizer_default( 'header_sub_menu_typo' ),
            'sanitize_callback' => 'newsis_sanitize_typo_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Typography_Control( $wp_customize, 'header_sub_menu_typo', array(
                'label'	      => esc_html__( 'Sub Menu', 'newsis' ),
                'section'     => 'newsis_header_menu_option_section',
                'settings'    => 'header_sub_menu_typo',
                'tab'   => 'typo',
                'fields'    => array( 'font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'text_transform', 'text_decoration')
            ))
        );

        /**
         * Off Canvas Section
         * 
         * panel - newsis_header_options_panel
         */
        $wp_customize->add_section( 'newsis_header_off_canvas_section', array(
            'title' => esc_html__( 'Off Canvas', 'newsis' ),
            'panel' => 'newsis_header_panel',
            'priority'  => 40
        ));

        // off canvas section tab
        $wp_customize->add_setting( 'newsis_header_off_canvas_tab', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'   => 'general'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Tab_Control( $wp_customize, 'newsis_header_off_canvas_tab', array(
                'section'     => 'newsis_header_off_canvas_section',
                'priority'  => 1,
                'choices'  => array(
                    array(
                        'name'  => 'general',
                        'title'  => esc_html__( 'General', 'newsis' )
                    ),
                    array(
                        'name'  => 'design',
                        'title'  => esc_html__( 'Design', 'newsis' )
                    )
                )
            ))
        );

        // header off canvas button option
        $wp_customize->add_setting( 'header_off_canvas_option', array(
            'default'         => NI\newsis_get_customizer_default( 'header_off_canvas_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
    
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'header_off_canvas_option', array(
                'label'	      => esc_html__( 'Show off canvas', 'newsis' ),
                'section'     => 'newsis_header_off_canvas_section',
                'settings'    => 'header_off_canvas_option'
            ))
        );

        // redirect off canvas button link
        $wp_customize->add_setting( 'header_sidebar_toggle_button_redirects', array(
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
        ));

        $wp_customize->add_control( 
            new Newsis_WP_Redirect_Control( $wp_customize, 'header_sidebar_toggle_button_redirects', array(
                'section'     => 'newsis_header_off_canvas_section',
                'settings'    => 'header_sidebar_toggle_button_redirects',
                'choices'     => array(
                    'header-social-icons' => array(
                        'type'  => 'section',
                        'id'    => 'sidebar-widgets-off-canvas-sidebar',
                        'label' => esc_html__( 'Manage sidebar from here', 'newsis' )
                    )
                )
            ))
        );

        // header off canvas sidebar color
        $wp_customize->add_setting( 'header_off_canvas_toggle_color', array(
            'default'   => NI\newsis_get_customizer_default( 'header_off_canvas_toggle_color' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'newsis_sanitize_color_group_picker_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Color_Group_Picker_Control( $wp_customize, 'header_off_canvas_toggle_color', array(
                'label'	      => esc_html__( 'Toggle Bar Color', 'newsis' ),
                'section'     => 'newsis_header_off_canvas_section',
                'settings'    => 'header_off_canvas_toggle_color',
                'tab'   => 'design'
            ))
        );

        /**
         * Custom Button Section
         * 
         * panel - newsis_header_options_panel
         */
        $wp_customize->add_section( 'newsis_header_custom_button_section', array(
            'title' => esc_html__( 'Custom Button', 'newsis' ),
            'panel' => 'newsis_header_panel',
            'priority'  => 40
        ));

        // main banner section tab
        $wp_customize->add_setting( 'newsis_header_custom_button_section_tab', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'   => 'general'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Tab_Control( $wp_customize, 'newsis_header_custom_button_section_tab', array(
                'section'     => 'newsis_header_custom_button_section',
                'priority'  => 1,
                'choices'  => array(
                    array(
                        'name'  => 'general',
                        'title'  => esc_html__( 'General', 'newsis' )
                    ),
                    array(
                        'name'  => 'design',
                        'title'  => esc_html__( 'Design', 'newsis' )
                    )
                )
            ))
        );

        // header custom button option
        $wp_customize->add_setting( 'theme_header_custom_button_option', array(
            'default'   => NI\newsis_get_customizer_default( 'theme_header_custom_button_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Toggle_Control( $wp_customize, 'theme_header_custom_button_option', array(
                'label'	      => esc_html__( 'Show header custom button', 'newsis' ),
                'section'     => 'newsis_header_custom_button_section',
                'settings'    => 'theme_header_custom_button_option'
            ))
        );
        
        // custom button icon picker
        $wp_customize->add_setting( 'header_custom_button_icon_picker', [
            'default'   =>  NI\newsis_get_customizer_default( 'header_custom_button_icon_picker' ),
            'sanitize_callback' =>  'newsis_sanitize_icon_picker_control',
            'transport' => 'postMessage'
        ]);
        $wp_customize->add_control(
            new Newsis_WP_Icon_Picker_Control( $wp_customize, 'header_custom_button_icon_picker', [
                'label' =>  esc_html__( 'Button Icon', 'newsis' ),
                'section'   =>  'newsis_header_custom_button_section'
            ])
        );

        // custom button label
        $wp_customize->add_setting( 'header_custom_button_label', [
            'default'   =>  NI\newsis_get_customizer_default( 'header_custom_button_label' ),
            'sanitize_callback' =>  'sanitize_text_field',
            'transport' => 'postMessage'
        ]);
        $wp_customize->add_control('header_custom_button_label', [
            'label' =>  esc_html__( 'Button Label', 'newsis' ),
            'section'   =>  'newsis_header_custom_button_section',
            'type'  =>  'text'
        ]);

        // custom button redirect url
        $wp_customize->add_setting( 'header_custom_button_redirect_href_link', array(
            'default' => NI\newsis_get_customizer_default( 'header_custom_button_redirect_href_link' ),
            'sanitize_callback' => 'newsis_sanitize_url',
        ));
        $wp_customize->add_control( 'header_custom_button_redirect_href_link', array(
            'label' => esc_html__( 'Redirect URL.', 'newsis' ),
            'description'   => esc_html__( 'Add url for the button to redirect.', 'newsis' ),
            'section'   => 'newsis_header_custom_button_section',
            'type'  => 'url'
        ));

         // header top and bottom padding
         $wp_customize->add_setting( 'custom_button_icon_size', array(
            'default'   => NI\newsis_get_customizer_default( 'custom_button_icon_size' ),
            'sanitize_callback' => 'newsis_sanitize_responsive_range',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Responsive_Range_Control( $wp_customize, 'custom_button_icon_size', array(
                    'label'	      => esc_html__( 'Icon Size(px)', 'newsis' ),
                    'section'     => 'newsis_header_custom_button_section',
                    'settings'    => 'custom_button_icon_size',
                    'unit'        => 'px',
                    'input_attrs' => array(
                    'max'         => 500,
                    'min'         => 1,
                    'step'        => 1,
                    'reset' => true
                )
            ))
        );

        // theme header => typography heading
        $wp_customize->add_setting( 'theme_header_typography_section_heading', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'theme_header_typography_section_heading', array(
                'label'	      => esc_html__( 'Typography', 'newsis' ),
                'section'     => 'newsis_header_custom_button_section',
                'settings'    => 'theme_header_typography_section_heading',
                'tab'   =>  'design'
            ))
        );

         // theme header => Text typo
         $wp_customize->add_setting( 'custom_button_text_typo', array(
            'default'   => NI\newsis_get_customizer_default( 'custom_button_text_typo' ),
            'sanitize_callback' => 'newsis_sanitize_typo_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Typography_Control( $wp_customize, 'custom_button_text_typo', array(
                'label'	      => esc_html__( 'Text Typography', 'newsis' ),
                'section'     => 'newsis_header_custom_button_section',
                'settings'    => 'custom_button_text_typo',
                'tab'   => 'design',
                'fields'    => array( 'font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'text_transform', 'text_decoration')
            ))
        );

        /**
         * Live Search Section
         * 
         * panel - newsis_header_options_panel
         */
        $wp_customize->add_section( 'newsis_header_live_search_section', array(
            'title' => esc_html__( 'Live Search', 'newsis' ),
            'panel' => 'newsis_header_panel',
            'priority'  => 50
        ));

        // header search option
        $wp_customize->add_setting( 'header_search_option', array(
            'default'   => NI\newsis_get_customizer_default( 'header_search_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
    
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'header_search_option', array(
                'label'	      => esc_html__( 'Show search icon', 'newsis' ),
                'section'     => 'newsis_header_live_search_section',
                'settings'    => 'header_search_option'
            ))
        );

        // search popup styles heading
        $wp_customize->add_setting( 'website_search_popop_styles_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'website_search_popop_styles_header', array(
                'label'	      => esc_html__( 'Live Search', 'newsis' ),
                'section'     => 'newsis_header_live_search_section',
                'settings'    => 'website_search_popop_styles_header'
            ))
        );

        // header live search option
        $wp_customize->add_setting( 'theme_header_live_search_option', array(
            'default'   => NI\newsis_get_customizer_default( 'theme_header_live_search_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Toggle_Control( $wp_customize, 'theme_header_live_search_option', array(
                'label'	      => esc_html__( 'Enable live search', 'newsis' ),
                'section'     => 'newsis_header_live_search_section'
            ))
        );

        // button label
        $wp_customize->add_setting( 'theme_header_live_search_button_label', array(
            'default' => NI\newsis_get_customizer_default( 'theme_header_live_search_button_label' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' =>  'postMessage'
        ));
        $wp_customize->add_control( 'theme_header_live_search_button_label', array(
            'type'      => 'text',
            'section'   => 'newsis_header_live_search_section',
            'label'     => esc_html__( 'Button Label', 'newsis' )
        ));

        // live search target
        $wp_customize->add_setting( 'theme_header_live_search_button_target', array(
            'sanitize_callback' => 'newsis_sanitize_select_control',
            'default'   => NI\newsis_get_customizer_default( 'theme_header_live_search_button_target' )
        ));
        $wp_customize->add_control( 'theme_header_live_search_button_target', array(
            'type'      => 'select',
            'section'   => 'newsis_header_live_search_section',
            'label'     => esc_html__( 'Search result button open in', 'newsis' ),
            'description' => esc_html__( 'Sets the target attribute according to the value.', 'newsis' ),
            'choices'   => array(
                '_blank' => esc_html__( 'Open link in new tab', 'newsis' ),
                '_self'  => esc_html__( 'Open link in same tab', 'newsis' )
            )
        ));

        /**
         * Theme Mode Section
         * 
         * panel - newsis_header_options_panel
         */
        $wp_customize->add_section( 'newsis_header_theme_mode_section', array(
            'title' => esc_html__( 'Theme Mode', 'newsis' ),
            'panel' => 'newsis_header_panel',
            'priority'  => 50
        ));

        // header theme mode toggle option
        $wp_customize->add_setting( 'header_theme_mode_toggle_option', array(
            'default'   => NI\newsis_get_customizer_default( 'header_theme_mode_toggle_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'header_theme_mode_toggle_option', array(
                'label'	      => esc_html__( 'Show dark/light toggle icon', 'newsis' ),
                'section'     => 'newsis_header_theme_mode_section',
                'settings'    => 'header_theme_mode_toggle_option'
            ))
        );
    }
    add_action( 'customize_register', 'newsis_customizer_header_panel', 10 );
endif;

if( !function_exists( 'newsis_customizer_ticker_news_panel' ) ) :
    // Register header options settings
    function newsis_customizer_ticker_news_panel( $wp_customize ) {
        // Header ads banner section
        $wp_customize->add_section( 'newsis_ticker_news_section', array(
            'title' => esc_html__( 'Ticker News', 'newsis' ),
            'priority'  => 70
        ));

        // Ticker News Width Layouts setting heading
        $wp_customize->add_setting( 'ticker_news_width_layouts_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'ticker_news_width_layouts_header', array(
                'label'	      => esc_html__( 'Width Layouts', 'newsis' ),
                'section'     => 'newsis_ticker_news_section',
                'settings'    => 'ticker_news_width_layouts_header'
            ))
        );

        // website content layout
        $wp_customize->add_setting( 'ticker_news_width_layout',
            array(
            'default'           => NI\newsis_get_customizer_default( 'ticker_news_width_layout' ),
            'sanitize_callback' => 'newsis_sanitize_select_control',
            )
        );
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Image_Control( $wp_customize, 'ticker_news_width_layout',
            array(
                'section'  => 'newsis_ticker_news_section',
                'choices'  => array(
                    'global' => array(
                        'label' => esc_html__( 'Global', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/global.jpg'
                    ),
                    'boxed--layout' => array(
                        'label' => esc_html__( 'Boxed', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/boxed_content.jpg'
                    ),
                    'full-width--layout' => array(
                        'label' => esc_html__( 'Full Width', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/full_content.jpg'
                    )
                )
            )
        ));

        // Header menu hover effect
        $wp_customize->add_setting( 'ticker_news_visible', array(
            'default' => NI\newsis_get_customizer_default( 'ticker_news_visible' ),
            'sanitize_callback' => 'newsis_sanitize_select_control'
        ));
        $wp_customize->add_control( 'ticker_news_visible', array(
            'type'      => 'select',
            'section'   => 'newsis_ticker_news_section',
            'label'     => esc_html__( 'Show ticker on', 'newsis' ),
            'choices'   => array(
                'all' => esc_html__( 'Show in all', 'newsis' ),
                'front-page' => esc_html__( 'Frontpage', 'newsis' ),
                'innerpages' => esc_html__( 'Show only in innerpages', 'newsis' ),
                'none' => esc_html__( 'Hide in all pages', 'newsis' ),
            ),
        ));
        
        // Ticker News content setting heading
        $wp_customize->add_setting( 'ticker_news_content_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'ticker_news_content_header', array(
                'label'	      => esc_html__( 'Content Setting', 'newsis' ),
                'section'     => 'newsis_ticker_news_section',
                'settings'    => 'ticker_news_content_header',
                'type'        => 'section-heading',
            ))
        );

        // ticker news title icon
        $wp_customize->add_setting( 'ticker_news_title_icon', [
            'default'   =>  NI\newsis_get_customizer_default( 'ticker_news_title_icon' ),
            'sanitize_callback' =>  'newsis_sanitize_icon_picker_control',
            'transport' => 'postMessage'
        ]);
        $wp_customize->add_control(
            new Newsis_WP_Icon_Picker_Control( $wp_customize, 'ticker_news_title_icon', [
                'label' =>  esc_html__( 'Ticker icon', 'newsis' ),
                'section'   =>  'newsis_ticker_news_section'
            ])
        );

        // ticker News title
        $wp_customize->add_setting( 'ticker_news_title', array(
            'default' => NI\newsis_get_customizer_default( 'ticker_news_title' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 'ticker_news_title', array(
                'label' => esc_html__( 'Ticker title', 'newsis' ),
                'type'  => 'text',
                'section'   => 'newsis_ticker_news_section'
            )
        );

        // Ticker News categories
        $wp_customize->add_setting( 'ticker_news_categories', array(
            'default' => NI\newsis_get_customizer_default( 'ticker_news_categories' ),
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Categories_Multiselect_Control( $wp_customize, 'ticker_news_categories', array(
                'label'     => esc_html__( 'Posts Categories', 'newsis' ),
                'section'   => 'newsis_ticker_news_section',
                'settings'  => 'ticker_news_categories',
                'choices'   => newsis_get_multicheckbox_categories_simple_array()
            ))
        );

        // Ticker News posts
        $wp_customize->add_setting( 'ticker_news_posts', array(
            'default' => NI\newsis_get_customizer_default( 'ticker_news_posts' ),
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Posts_Multiselect_Control( $wp_customize, 'ticker_news_posts', array(
                'label'     => esc_html__( 'Posts To Include', 'newsis' ),
                'section'   => 'newsis_ticker_news_section',
                'settings'  => 'ticker_news_posts',
                'choices'   => newsis_get_multicheckbox_posts_simple_array()
            ))
        );

        // Ticker News orderby
        $wp_customize->add_setting( 'ticker_news_order_by', array(
            'default' => NI\newsis_get_customizer_default( 'ticker_news_order_by' ),
            'sanitize_callback' => 'newsis_sanitize_select_control'
        ));
        $wp_customize->add_control( 'ticker_news_order_by', array(
            'type'      => 'select',
            'section'   => 'newsis_ticker_news_section',
            'label'     => esc_html__( 'Orderby', 'newsis' ),
            'choices'   => newsis_customizer_orderby_options_array(),
        ));
        
        // ticker news post query date range
        $wp_customize->add_setting( 'ticker_news_date_filter', array(
            'default' => NI\newsis_get_customizer_default( 'ticker_news_date_filter' ),
            'sanitize_callback' => 'newsis_sanitize_select_control'
        ));
        $wp_customize->add_control( 'ticker_news_date_filter', array(
            'label'     => __( 'Date Range', 'newsis' ),
            'type'      => 'select',
            'section'   => 'newsis_ticker_news_section',
            'choices'   => newsis_get_date_filter_choices_array(),
            'active_callback'   => function( $setting ) {
                if ( $setting->manager->get_setting( 'top_header_ticker_news_option' )->value() && $setting->manager->get_setting( 'top_header_right_content_type' )->value() == 'ticker-news' ) {
                    return true;
                }
                return false;
            }
        ));
    }
    add_action( 'customize_register', 'newsis_customizer_ticker_news_panel', 10 );
endif;

if( !function_exists( 'newsis_customizer_main_banner_panel' ) ) :
    /**
     * Register main banner section settings
     * 
     */
    function newsis_customizer_main_banner_panel( $wp_customize ) {
        /**
         * Main Banner section
         * 
         */
        $wp_customize->add_section( 'newsis_main_banner_section', array(
            'title' => esc_html__( 'Main Banner', 'newsis' ),
            'priority'  => 70
        ));

        // main banner section tab
        $wp_customize->add_setting( 'main_banner_section_tab', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'   => 'general'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Tab_Control( $wp_customize, 'main_banner_section_tab', array(
                'section'     => 'newsis_main_banner_section',
                'priority'  => 1,
                'choices'  => array(
                    array(
                        'name'  => 'general',
                        'title'  => esc_html__( 'General', 'newsis' )
                    ),
                    array(
                        'name'  => 'design',
                        'title'  => esc_html__( 'Design', 'newsis' )
                    )
                )
            ))
        );

        // main banner option
        $wp_customize->add_setting( 'main_banner_option', array(
            'default'   => NI\newsis_get_customizer_default( 'main_banner_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Toggle_Control( $wp_customize, 'main_banner_option', array(
                'label'	      => esc_html__( 'Show main banner', 'newsis' ),
                'section'     => 'newsis_main_banner_section',
                'settings'    => 'main_banner_option'
            ))
        );

        // main banner Layouts
        $wp_customize->add_setting( 'main_banner_layout', array(
            'default'           => NI\newsis_get_customizer_default( 'main_banner_layout' ),
            'sanitize_callback' => 'newsis_sanitize_select_control',
        ));
        $wp_customize->add_control( new Newsis_WP_Radio_Image_Control(
            $wp_customize,
            'main_banner_layout',
            array(
                'section'  => 'newsis_main_banner_section',
                'priority' => 10,
                'choices'  => array(
                    'four' => array(
                        'label' => esc_html__( 'Layout Four', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/main_banner_four.jpg'
                    ),
                    'six' => array(
                        'label' => esc_html__( 'Layout Six', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/main_banner_six.jpg'
                    )
                )
            )
        ));

        // main banner slider setting heading
        $wp_customize->add_setting( 'main_banner_slider_settings_header', array(
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'main_banner_slider_settings_header', array(
                'label'	      => esc_html__( 'Slider Setting', 'newsis' ),
                'section'     => 'newsis_main_banner_section',
                'settings'    => 'main_banner_slider_settings_header',
                'type'        => 'section-heading',
            ))
        );
        
        // Main Banner slider categories
        $wp_customize->add_setting( 'main_banner_slider_categories', array(
            'default' => NI\newsis_get_customizer_default( 'main_banner_slider_categories' ),
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Categories_Multiselect_Control( $wp_customize, 'main_banner_slider_categories', array(
                'label'     => esc_html__( 'Posts Categories', 'newsis' ),
                'section'   => 'newsis_main_banner_section',
                'settings'  => 'main_banner_slider_categories',
                'choices'   => newsis_get_multicheckbox_categories_simple_array()
            ))
        );

        // main banner posts
        $wp_customize->add_setting( 'main_banner_posts', array(
            'default' => NI\newsis_get_customizer_default( 'main_banner_posts' ),
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Posts_Multiselect_Control( $wp_customize, 'main_banner_posts', array(
                'label'     => esc_html__( 'Posts To Include', 'newsis' ),
                'section'   => 'newsis_main_banner_section',
                'settings'  => 'main_banner_posts',
                'choices'   => newsis_get_multicheckbox_posts_simple_array()
            ))
        );

        // Main Banner slider orderby
        $wp_customize->add_setting( 'main_banner_slider_order_by', array(
            'default' => NI\newsis_get_customizer_default( 'main_banner_slider_order_by' ),
            'sanitize_callback' => 'newsis_sanitize_select_control'
        ));
        $wp_customize->add_control( 'main_banner_slider_order_by', array(
            'type'      => 'select',
            'section'   => 'newsis_main_banner_section',
            'label'     => esc_html__( 'Orderby', 'newsis' ),
            'choices'   => newsis_customizer_orderby_options_array(),
        ));

        // main banner post query date range
        $wp_customize->add_setting( 'main_banner_date_filter', array(
            'default' => NI\newsis_get_customizer_default( 'main_banner_date_filter' ),
            'sanitize_callback' => 'newsis_sanitize_select_control'
        ));
        $wp_customize->add_control( 'main_banner_date_filter', array(
            'label'     => __( 'Date Range', 'newsis' ),
            'type'      => 'select',
            'section'   => 'newsis_main_banner_section',
            'choices'   => newsis_get_date_filter_choices_array(),
            'active_callback'   => function( $setting ) {
                if ( $setting->manager->get_setting( 'top_header_ticker_news_option' )->value() && $setting->manager->get_setting( 'top_header_right_content_type' )->value() == 'ticker-news' ) {
                    return true;
                }
                return false;
            }
        ));


        // main banner related posts option
        $wp_customize->add_setting( 'main_banner_related_posts_option', array(
            'default'   => NI\newsis_get_customizer_default( 'main_banner_related_posts_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'main_banner_related_posts_option', array(
                'label'	      => esc_html__( 'Show related posts', 'newsis' ),
                'section'     => 'newsis_main_banner_section',
                'settings'    => 'main_banner_related_posts_option'
            ))
        );
        
        // Main banner block posts setting heading
        $wp_customize->add_setting( 'main_banner_block_posts_settings_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'main_banner_block_posts_settings_header', array(
                'label'	      => esc_html__( 'Block Posts Setting', 'newsis' ),
                'section'     => 'newsis_main_banner_section',
                'settings'    => 'main_banner_block_posts_settings_header',
                'type'        => 'section-heading',
                'active_callback'   => function( $setting ) {
                    if ( $setting->manager->get_setting( 'main_banner_layout' )->value() === 'four' ) {
                        return true;
                    }
                    return false;
                }
            ))
        );

        // Main Banner block posts slider orderby
        $wp_customize->add_setting( 'main_banner_block_posts_order_by', array(
            'default' => NI\newsis_get_customizer_default( 'main_banner_block_posts_order_by' ),
            'sanitize_callback' => 'newsis_sanitize_select_control'
        ));
        $wp_customize->add_control( 'main_banner_block_posts_order_by', array(
            'type'      => 'select',
            'section'   => 'newsis_main_banner_section',
            'label'     => esc_html__( 'Orderby', 'newsis' ),
            'choices'   => newsis_customizer_orderby_options_array(),
            'active_callback'   => function( $setting ) {
                if ( $setting->manager->get_setting( 'main_banner_layout' )->value() === 'four' ) {
                    return true;
                }
                return false;
            }
        ));

        // Main Banner block posts categories
        $wp_customize->add_setting( 'main_banner_block_posts_to_include', array(
            'default' => NI\newsis_get_customizer_default( 'main_banner_block_posts_to_include' ),
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Categories_Multiselect_Control( $wp_customize, 'main_banner_block_posts_to_include', array(
                'label'     => esc_html__( 'Block posts to include', 'newsis' ),
                'section'   => 'newsis_main_banner_section',
                'settings'  => 'main_banner_block_posts_to_include',
                'choices'   => newsis_get_multicheckbox_posts_simple_array(),
                'active_callback'   => function( $setting ) {
                    if ( $setting->manager->get_setting( 'main_banner_layout' )->value() === 'four' ) {
                        return true;
                    }
                    return false;
                }
            ))
        );

        // Main Banner block posts categories
        $wp_customize->add_setting( 'main_banner_block_posts_categories', array(
            'default' => NI\newsis_get_customizer_default( 'main_banner_block_posts_categories' ),
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Categories_Multiselect_Control( $wp_customize, 'main_banner_block_posts_categories', array(
                'label'     => esc_html__( 'Block posts categories', 'newsis' ),
                'section'   => 'newsis_main_banner_section',
                'settings'  => 'main_banner_block_posts_categories',
                'choices'   => newsis_get_multicheckbox_categories_simple_array(),
                'active_callback'   => function( $setting ) {
                    if ( $setting->manager->get_setting( 'main_banner_layout' )->value() === 'four' ) {
                        return true;
                    }
                    return false;
                }
            ))
        );

        // Main banner six trailing posts setting heading
        $wp_customize->add_setting( 'main_banner_six_trailing_posts_settings_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'main_banner_six_trailing_posts_settings_header', array(
                'label'	      => esc_html__( 'Trailing Posts Setting', 'newsis' ),
                'section'     => 'newsis_main_banner_section',
                'settings'    => 'main_banner_six_trailing_posts_settings_header',
                'active_callback'   => function( $setting ) {
                    if ( $setting->manager->get_setting( 'main_banner_layout' )->value() === 'six' ) {
                        return true;
                    }
                    return false;
                }
            ))
        );

        // Main banner trailing posts layouts
        $wp_customize->add_setting( 'main_banner_six_trailing_posts_layout', array(
            'default'           => NI\newsis_get_customizer_default( 'main_banner_six_trailing_posts_layout' ),
            'sanitize_callback' => 'newsis_sanitize_select_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( new Newsis_WP_Radio_Image_Control(
            $wp_customize,
            'main_banner_six_trailing_posts_layout',
            array(
                'section'  => 'newsis_main_banner_section',
                'priority' => 10,
                'choices'  => array(
                    'row' => array(
                        'label' => esc_html__( 'Row Layout', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/main_banner_six_trailing_posts_layout_row.jpg'
                    ),
                    'column' => array(
                        'label' => esc_html__( 'Column Layout', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/main_banner_six_trailing_posts_layout_column.jpg'
                    )
                ),
                'active_callback'   => function( $setting ) {
                    if ( $setting->manager->get_setting( 'main_banner_layout' )->value() === 'six' ) {
                        return true;
                    }
                    return false;
                }
            )
        ));
        
        // Main banner six trailing posts slider orderby
        $wp_customize->add_setting( 'main_banner_six_trailing_posts_order_by', array(
            'default' => NI\newsis_get_customizer_default( 'main_banner_six_trailing_posts_order_by' ),
            'sanitize_callback' => 'newsis_sanitize_select_control'
        ));
        $wp_customize->add_control( 'main_banner_six_trailing_posts_order_by', array(
            'type'      => 'select',
            'section'   => 'newsis_main_banner_section',
            'label'     => esc_html__( 'Orderby', 'newsis' ),
            'choices'   => newsis_customizer_orderby_options_array(),
            'active_callback'   => function( $setting ) {
                if ( $setting->manager->get_setting( 'main_banner_layout' )->value() === 'six' ) {
                    return true;
                }
                return false;
            }
        ));

        // Main banner six trailing posts categories
        $wp_customize->add_setting( 'main_banner_six_trailing_posts_categories', array(
            'default' => NI\newsis_get_customizer_default( 'main_banner_six_trailing_posts_categories' ),
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Categories_Multiselect_Control( $wp_customize, 'main_banner_six_trailing_posts_categories', array(
                'label'     => esc_html__( 'Posts categories', 'newsis' ),
                'section'   => 'newsis_main_banner_section',
                'settings'  => 'main_banner_six_trailing_posts_categories',
                'choices'   => newsis_get_multicheckbox_categories_simple_array(),
                'active_callback'   => function( $setting ) {
                    if ( $setting->manager->get_setting( 'main_banner_layout' )->value() === 'six' ) {
                        return true;
                    }
                    return false;
                }
            ))
        );

        // main banner posts
        $wp_customize->add_setting( 'main_banner_six_trailing_posts', array(
            'default' => NI\newsis_get_customizer_default( 'main_banner_six_trailing_posts' ),
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Posts_Multiselect_Control( $wp_customize, 'main_banner_six_trailing_posts', array(
                'label'     => esc_html__( 'Posts', 'newsis' ),
                'section'   => 'newsis_main_banner_section',
                'settings'  => 'main_banner_six_trailing_posts',
                'choices'   => newsis_get_multicheckbox_posts_simple_array(),
                'active_callback'   => function( $setting ) {
                    if ( $setting->manager->get_setting( 'main_banner_layout' )->value() === 'six' ) {
                        return true;
                    }
                    return false;
                }
            ))
        );

        // banner Width Layouts setting heading
        $wp_customize->add_setting( 'main_banner_width_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'main_banner_width_layout_header', array(
                'label'	      => esc_html__( 'Width Layouts', 'newsis' ),
                'section'     => 'newsis_main_banner_section',
                'settings'    => 'main_banner_width_layout_header',
                'tab'   => 'design'
            ))
        );

        // banner layout
        $wp_customize->add_setting( 'main_banner_width_layout',
            array(
            'default'           => NI\newsis_get_customizer_default( 'main_banner_width_layout' ),
            'sanitize_callback' => 'newsis_sanitize_select_control',
            )
        );
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Image_Control( $wp_customize, 'main_banner_width_layout',
            array(
                'section'  => 'newsis_main_banner_section',
                'tab'   => 'design',
                'choices'  => array(
                    'global' => array(
                        'label' => esc_html__( 'Global', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/global.jpg'
                    ),
                    'boxed--layout' => array(
                        'label' => esc_html__( 'Boxed', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/boxed_content.jpg'
                    ),
                    'full-width--layout' => array(
                        'label' => esc_html__( 'Full Width', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/full_content.jpg'
                    )
                )
            )
        ));
    }
    add_action( 'customize_register', 'newsis_customizer_main_banner_panel', 10 );
endif;

if( !function_exists( 'newsis_customizer_footer_panel' ) ) :
    /**
     * Register footer options settings
     * 
     */
    function newsis_customizer_footer_panel( $wp_customize ) {
        /**
         * Theme Footer Section
         * 
         * panel - newsis_footer_panel
         */
        $wp_customize->add_section( 'newsis_footer_section', array(
            'title' => esc_html__( 'Theme Footer', 'newsis' ),
            'priority'  => 74
        ));
        
        // section tab
        $wp_customize->add_setting( 'footer_section_tab', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'   => 'general'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Tab_Control( $wp_customize, 'footer_section_tab', array(
                'section'     => 'newsis_footer_section',
                'choices'  => array(
                    array(
                        'name'  => 'general',
                        'title'  => esc_html__( 'General', 'newsis' )
                    ),
                    array(
                        'name'  => 'design',
                        'title'  => esc_html__( 'Design', 'newsis' )
                    )
                )
            ))
        );

        // Footer Option
        $wp_customize->add_setting( 'footer_option', array(
            'default'   => NI\newsis_get_customizer_default( 'footer_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
            'transport'   => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Toggle_Control( $wp_customize, 'footer_option', array(
                'label'	      => esc_html__( 'Enable footer section', 'newsis' ),
                'section'     => 'newsis_footer_section',
                'settings'    => 'footer_option',
                'tab'   => 'general'
            ))
        );

        /// Add the footer layout control.
        $wp_customize->add_setting( 'footer_widget_column', array(
            'default'           => NI\newsis_get_customizer_default( 'footer_widget_column' ),
            'sanitize_callback' => 'newsis_sanitize_select_control',
            'transport'   => 'postMessage'
            )
        );
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Image_Control( $wp_customize, 'footer_widget_column', array(
                'section'  => 'newsis_footer_section',
                'tab'   => 'general',
                'choices'  => array(
                    'column-one' => array(
                        'label' => esc_html__( 'Column One', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/footer_column_one.jpg'
                    ),
                    'column-two' => array(
                        'label' => esc_html__( 'Column Two', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/footer_column_two.jpg'
                    ),
                    'column-three' => array(
                        'label' => esc_html__( 'Column Three', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/footer_column_three.jpg'
                    ),
                    'column-four' => array(
                        'label' => esc_html__( 'Column Four', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/footer_column_four.jpg'
                    )
                )
            )
        ));

        // archive pagination type
        $wp_customize->add_setting( 'footer_section_width', array(
            'default' => NI\newsis_get_customizer_default( 'footer_section_width' ),
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Tab_Control( $wp_customize, 'footer_section_width', array(
                'label'	      => esc_html__( 'Section Width', 'newsis' ),
                'section'     => 'newsis_footer_section',
                'settings'    => 'footer_section_width',
                'choices' => array(
                    array(
                        'value' => 'full-width',
                        'label' => esc_html__('Full Width', 'newsis' )
                    ),
                    array(
                        'value' => 'boxed-width',
                        'label' => esc_html__('Boxed Width', 'newsis' )
                    )
                )
            ))
        );
        
        // Redirect widgets link
        $wp_customize->add_setting( 'footer_widgets_redirects', array(
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Redirect_Control( $wp_customize, 'footer_widgets_redirects', array(
                'label'	      => esc_html__( 'Widgets', 'newsis' ),
                'section'     => 'newsis_footer_section',
                'settings'    => 'footer_widgets_redirects',
                'tab'   => 'general',
                'choices'     => array(
                    'footer-column-one' => array(
                        'type'  => 'section',
                        'id'    => 'sidebar-widgets-footer-sidebar--column-1',
                        'label' => esc_html__( 'Manage footer widget one', 'newsis' )
                    ),
                    'footer-column-two' => array(
                        'type'  => 'section',
                        'id'    => 'sidebar-widgets-footer-sidebar--column-2',
                        'label' => esc_html__( 'Manage footer widget two', 'newsis' )
                    ),
                    'footer-column-three' => array(
                        'type'  => 'section',
                        'id'    => 'sidebar-widgets-footer-sidebar--column-3',
                        'label' => esc_html__( 'Manage footer widget three', 'newsis' )
                    ),
                    'footer-column-four' => array(
                        'type'  => 'section',
                        'id'    => 'sidebar-widgets-footer-sidebar--column-4',
                        'label' => esc_html__( 'Manage footer widget four', 'newsis' )
                    )
                )
            ))
        );

        // footer vertical spacing top
        $wp_customize->add_setting( 'footer_vertical_spacing_top', array(
            'default'   => NI\newsis_get_customizer_default( 'footer_vertical_spacing_top' ),
            'sanitize_callback' => 'newsis_sanitize_responsive_range',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Responsive_Range_Control( $wp_customize, 'footer_vertical_spacing_top', array(
                'label'	      => esc_html__( 'Vertical Spacing Top (px)', 'newsis' ),
                'section'     => 'newsis_footer_section',
                'settings'    => 'footer_vertical_spacing_top',
                'unit'        => 'px',
                'input_attrs' => array(
                    'max'         => 200,
                    'min'         => 0,
                    'step'        => 1,
                    'reset' => true
                ),
                'tab'   =>  'design'
            ))
        );

        // footer vertical spacing bottom
        $wp_customize->add_setting( 'footer_vertical_spacing_bottom', array(
            'default'   => NI\newsis_get_customizer_default( 'footer_vertical_spacing_bottom' ),
            'sanitize_callback' => 'newsis_sanitize_responsive_range',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Responsive_Range_Control( $wp_customize, 'footer_vertical_spacing_bottom', array(
                'label'	      => esc_html__( 'Vertical Spacing Bottom (px)', 'newsis' ),
                'section'     => 'newsis_footer_section',
                'settings'    => 'footer_vertical_spacing_bottom',
                'unit'        => 'px',
                'input_attrs' => array(
                    'max'         => 200,
                    'min'         => 0,
                    'step'        => 1,
                    'reset' => true
                ),
                'tab'   =>  'design'
            ))
        );
    }
    add_action( 'customize_register', 'newsis_customizer_footer_panel', 10 );
endif;

if( !function_exists( 'newsis_customizer_bottom_footer_panel' ) ) :
    /**
     * Register bottom footer options settings
     * 
     */
    function newsis_customizer_bottom_footer_panel( $wp_customize ) {
        /**
         * Bottom Footer Section
         * 
         * panel - newsis_footer_panel
         */
        $wp_customize->add_section( 'newsis_bottom_footer_section', array(
            'title' => esc_html__( 'Bottom Footer', 'newsis' ),
            'priority'  => 75
        ));

        // section tab
        $wp_customize->add_setting( 'bottom_footer_section_tab', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'   => 'general'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Tab_Control( $wp_customize, 'bottom_footer_section_tab', array(
                'section'     => 'newsis_bottom_footer_section',
                'choices'  => array(
                    array(
                        'name'  => 'general',
                        'title'  => esc_html__( 'General', 'newsis' )
                    ),
                    array(
                        'name'  => 'design',
                        'title'  => esc_html__( 'Design', 'newsis' )
                    )
                )
            ))
        );

        // Bottom Footer Option
        $wp_customize->add_setting( 'bottom_footer_option', array(
            'default'         => NI\newsis_get_customizer_default( 'bottom_footer_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Toggle_Control( $wp_customize, 'bottom_footer_option', array(
                'label'	      => esc_html__( 'Enable bottom footer', 'newsis' ),
                'section'     => 'newsis_bottom_footer_section',
                'settings'    => 'bottom_footer_option'
            ))
        );

        // Main Banner slider categories option
        $wp_customize->add_setting( 'bottom_footer_social_option', array(
            'default'   => NI\newsis_get_customizer_default( 'bottom_footer_social_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'bottom_footer_social_option', array(
                'label'	      => esc_html__( 'Show bottom social icons', 'newsis' ),
                'section'     => 'newsis_bottom_footer_section',
                'settings'    => 'bottom_footer_social_option'
            ))
        );

        // Main Banner slider categories option
        $wp_customize->add_setting( 'bottom_footer_menu_option', array(
            'default'   => NI\newsis_get_customizer_default( 'bottom_footer_menu_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'bottom_footer_menu_option', array(
                'label'	      => esc_html__( 'Show bottom footer menu', 'newsis' ),
                'section'     => 'newsis_bottom_footer_section',
                'settings'    => 'bottom_footer_menu_option'
            ))
        );

        // Bottom footer site info
        $wp_customize->add_setting( 'bottom_footer_site_info', array(
            'default'    => NI\newsis_get_customizer_default( 'bottom_footer_site_info' ),
            'sanitize_callback' => 'wp_kses_post'
        ));
        $wp_customize->add_control( 'bottom_footer_site_info', array(
            'label' =>    esc_html__( 'Copyright Text', 'newsis' ),
            'description'   =>    esc_html__( 'Add %year% to retrieve current year', 'newsis' ),
            'section'   =>    'newsis_bottom_footer_section',
            'type'  =>  'textarea'
        ));

        // copyright info alignment
        $wp_customize->add_setting( 'bottom_footer_site_info_alignment', array(
            'default' => NI\newsis_get_customizer_default( 'bottom_footer_site_info_alignment' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Tab_Control( $wp_customize, 'bottom_footer_site_info_alignment', array(
                'label'	      => esc_html__( 'Copyright align', 'newsis' ),
                'section'     => 'newsis_bottom_footer_section',
                'settings'    => 'bottom_footer_site_info_alignment',
                'choices' => array(
                    array(
                        'value' => 'left',
                        'label' => esc_html__('Left', 'newsis' )
                    ),
                    array(
                        'value' => 'center',
                        'label' => esc_html__('Center', 'newsis' )
                    ),
                    array(
                        'value' => 'right',
                        'label' => esc_html__('Right', 'newsis' )
                    )
                )
            ))
        );

        // bottom footer width layout heading
        $wp_customize->add_setting( 'bottom_footer_width_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'bottom_footer_width_layout_header', array(
                'label'	      => esc_html__( 'Width Layouts', 'newsis' ),
                'section'     => 'newsis_bottom_footer_section',
                'settings'    => 'bottom_footer_width_layout_header',
                'tab'   => 'design'
            ))
        );

        // bottom footer width layout
        $wp_customize->add_setting( 'bottom_footer_width_layout',
            array(
            'default'           => NI\newsis_get_customizer_default( 'bottom_footer_width_layout' ),
            'sanitize_callback' => 'newsis_sanitize_select_control',
            )
        );
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Image_Control( $wp_customize, 'bottom_footer_width_layout',
            array(
                'section'  => 'newsis_bottom_footer_section',
                'tab'   => 'design',
                'choices'  => array(
                    'global' => array(
                        'label' => esc_html__( 'Global', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/global.jpg'
                    ),
                    'boxed--layout' => array(
                        'label' => esc_html__( 'Boxed', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/boxed_content.jpg'
                    ),
                    'full-width--layout' => array(
                        'label' => esc_html__( 'Full Width', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/full_content.jpg'
                    )
                )
            )
        ));

        // bottom footer background
        $wp_customize->add_setting( 'bottom_footer_background_color_group', array(
            'default'   => NI\newsis_get_customizer_default( 'bottom_footer_background_color_group' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        
        $wp_customize->add_control( 
            new Newsis_WP_Color_Group_Control( $wp_customize, 'bottom_footer_background_color_group', array(
                'label'	      => esc_html__( 'Background', 'newsis' ),
                'section'     => 'newsis_bottom_footer_section',
                'settings'    => 'bottom_footer_background_color_group',
                'tab'   => 'design'
            ))
        );
    }
    add_action( 'customize_register', 'newsis_customizer_bottom_footer_panel', 10 );
endif;

if( !function_exists( 'newsis_customizer_typography_panel' ) ) :
    /**
     * Register typography options settings
     * 
     */
    function newsis_customizer_typography_panel( $wp_customize ) {
        // typography options panel settings
        $wp_customize->add_section( 'newsis_typography_section', array(
            'title' => esc_html__( 'Typography', 'newsis' ),
            'priority'  => 55
        ));

        // block title typo
        $wp_customize->add_setting( 'site_section_block_title_typo', array(
            'default'   => NI\newsis_get_customizer_default( 'site_section_block_title_typo' ),
            'sanitize_callback' => 'newsis_sanitize_typo_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Typography_Control( $wp_customize, 'site_section_block_title_typo', array(
                'label'	      => esc_html__( 'Block Title', 'newsis' ),
                'section'     => 'newsis_typography_section',
                'settings'    => 'site_section_block_title_typo',
                'fields'    => array( 'font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'text_transform', 'text_decoration')
            ))
        );

        // post title typo
        $wp_customize->add_setting( 'site_archive_post_title_typo', array(
            'default'   => NI\newsis_get_customizer_default( 'site_archive_post_title_typo' ),
            'sanitize_callback' => 'newsis_sanitize_typo_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Typography_Control( $wp_customize, 'site_archive_post_title_typo', array(
                'label'	      => esc_html__( 'Post Title', 'newsis' ),
                'section'     => 'newsis_typography_section',
                'settings'    => 'site_archive_post_title_typo',
                'fields'    => array( 'font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'text_transform', 'text_decoration')
            ))
        );
        
        // post meta typo
        $wp_customize->add_setting( 'site_archive_post_meta_typo', array(
            'default'   => NI\newsis_get_customizer_default( 'site_archive_post_meta_typo' ),
            'sanitize_callback' => 'newsis_sanitize_typo_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Typography_Control( $wp_customize, 'site_archive_post_meta_typo', array(
                'label'	      => esc_html__( 'Post Meta', 'newsis' ),
                'section'     => 'newsis_typography_section',
                'settings'    => 'site_archive_post_meta_typo',
                'fields'    => array( 'font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'text_transform', 'text_decoration')
            ))
        );

        // post content typo
        $wp_customize->add_setting( 'site_archive_post_content_typo', array(
            'default'   => NI\newsis_get_customizer_default( 'site_archive_post_content_typo' ),
            'sanitize_callback' => 'newsis_sanitize_typo_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Typography_Control( $wp_customize, 'site_archive_post_content_typo', array(
                'label'	      => esc_html__( 'Post Content', 'newsis' ),
                'section'     => 'newsis_typography_section',
                'settings'    => 'site_archive_post_content_typo',
                'fields'    => array( 'font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'text_transform', 'text_decoration')
            ))
        );
    }
    add_action( 'customize_register', 'newsis_customizer_typography_panel', 10 );
endif;

if( !function_exists( 'newsis_customizer_front_sections_panel' ) ) :
    /**
     * Register front sections settings
     * 
     */
    function newsis_customizer_front_sections_panel( $wp_customize ) {
        // Front sections panel
        $wp_customize->add_panel( 'newsis_front_sections_panel', array(
            'title' => esc_html__( 'Front sections', 'newsis' ),
            'priority'  => 71
        ));

        // full width content section
        $wp_customize->add_section( 'newsis_full_width_section', array(
            'title' => esc_html__( 'Full Width', 'newsis' ),
            'panel' => 'newsis_front_sections_panel',
            'priority'  => 10
        ));

        // section tab
        $wp_customize->add_setting( 'full_width_section_tab', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'   => 'general'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Tab_Control( $wp_customize, 'full_width_section_tab', array(
                'section'     => 'newsis_full_width_section',
                'choices'  => array(
                    array(
                        'name'  => 'general',
                        'title'  => esc_html__( 'General', 'newsis' )
                    ),
                    array(
                        'name'  => 'design',
                        'title'  => esc_html__( 'Design', 'newsis' )
                    )
                )
            ))
        );

        // full width repeater control
        $wp_customize->add_setting( 'full_width_blocks', array(
            'default'   => NI\newsis_get_customizer_default( 'full_width_blocks' ),
            'sanitize_callback' => 'newsis_sanitize_repeater_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Block_Repeater_Control( $wp_customize, 'full_width_blocks', array(
                'label'	      => esc_html__( 'Blocks to show in this section', 'newsis' ),
                'description' => esc_html__( 'Hold bar icon at right of block item and drag vertically to re-order blocks', 'newsis' ),
                'section'     => 'newsis_full_width_section',
                'settings'    => 'full_width_blocks'
            ))
        );

        // Width Layouts setting heading
        $wp_customize->add_setting( 'full_width_blocks_width_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'full_width_blocks_width_layout_header', array(
                'label'	      => esc_html__( 'Width Layouts', 'newsis' ),
                'section'     => 'newsis_full_width_section',
                'settings'    => 'full_width_blocks_width_layout_header',
                'tab'   => 'design'
            ))
        );

        // width layout
        $wp_customize->add_setting( 'full_width_blocks_width_layout', array(
            'default'           => NI\newsis_get_customizer_default( 'full_width_blocks_width_layout' ),
            'sanitize_callback' => 'newsis_sanitize_select_control',
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Image_Control( $wp_customize, 'full_width_blocks_width_layout',
            array(
                'section'  => 'newsis_full_width_section',
                'tab'   => 'design',
                'choices'  => array(
                    'global' => array(
                        'label' => esc_html__( 'Global', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/global.jpg'
                    ),
                    'boxed--layout' => array(
                        'label' => esc_html__( 'Boxed', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/boxed_content.jpg'
                    ),
                    'full-width--layout' => array(
                        'label' => esc_html__( 'Full Width', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/full_content.jpg'
                    )
                )
            )
        ));

        // full width vertical spacing top
        $wp_customize->add_setting( 'full_width_vertical_spacing_top', array(
            'default'   => NI\newsis_get_customizer_default( 'full_width_vertical_spacing_top' ),
            'sanitize_callback' => 'newsis_sanitize_responsive_range',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Responsive_Range_Control( $wp_customize, 'full_width_vertical_spacing_top', array(
                'label'	      => esc_html__( 'Vertical Spacing Top (px)', 'newsis' ),
                'section'     => 'newsis_full_width_section',
                'settings'    => 'full_width_vertical_spacing_top',
                'unit'        => 'px',
                'input_attrs' => array(
                    'max'         => 200,
                    'min'         => 0,
                    'step'        => 1,
                    'reset' => true
                ),
                'tab'   =>  'design'
            ))
        );

        // full width vertical spacing bottom
        $wp_customize->add_setting( 'full_width_vertical_spacing_bottom', array(
            'default'   => NI\newsis_get_customizer_default( 'full_width_vertical_spacing_bottom' ),
            'sanitize_callback' => 'newsis_sanitize_responsive_range',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Responsive_Range_Control( $wp_customize, 'full_width_vertical_spacing_bottom', array(
                'label'	      => esc_html__( 'Vertical Spacing Bottom (px)', 'newsis' ),
                'section'     => 'newsis_full_width_section',
                'settings'    => 'full_width_vertical_spacing_bottom',
                'unit'        => 'px',
                'input_attrs' => array(
                    'max'         => 200,
                    'min'         => 0,
                    'step'        => 1,
                    'reset' => true
                ),
                'tab'   =>  'design'
            ))
        );

        // Left content -right sidebar section
        $wp_customize->add_section( 'newsis_leftc_rights_section', array(
            'title' => esc_html__( 'Left Content  - Right Sidebar', 'newsis' ),
            'panel' => 'newsis_front_sections_panel',
            'priority'  => 10
        ));

        // section tab
        $wp_customize->add_setting( 'leftc_rights_section_tab', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'   => 'general'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Tab_Control( $wp_customize, 'leftc_rights_section_tab', array(
                'section'     => 'newsis_leftc_rights_section',
                'choices'  => array(
                    array(
                        'name'  => 'general',
                        'title'  => esc_html__( 'General', 'newsis' )
                    ),
                    array(
                        'name'  => 'design',
                        'title'  => esc_html__( 'Design', 'newsis' )
                    )
                )
            ))
        );

        // redirect to manage sidebar
        $wp_customize->add_setting( 'leftc_rights_section_sidebar_redirect', array(
            'sanitize_callback' => 'newsis_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Redirect_Control( $wp_customize, 'leftc_rights_section_sidebar_redirect', array(
                'label'	      => esc_html__( 'Widgets', 'newsis' ),
                'section'     => 'newsis_leftc_rights_section',
                'settings'    => 'leftc_rights_section_sidebar_redirect',
                'tab'   => 'general',
                'choices'     => array(
                    'footer-column-one' => array(
                        'type'  => 'section',
                        'id'    => 'sidebar-widgets-front-right-sidebar',
                        'label' => esc_html__( 'Manage right sidebar', 'newsis' )
                    )
                )
            ))
        );

        // Block Repeater control
        $wp_customize->add_setting( 'leftc_rights_blocks', array(
            'sanitize_callback' => 'newsis_sanitize_repeater_control',
            'default'   => NI\newsis_get_customizer_default( 'leftc_rights_blocks' )
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Block_Repeater_Control( $wp_customize, 'leftc_rights_blocks', array(
                'label'	      => esc_html__( 'Blocks to show in this section', 'newsis' ),
                'description' => esc_html__( 'Hold bar icon at right of block item and drag vertically to re-order blocks', 'newsis' ),
                'section'     => 'newsis_leftc_rights_section',
                'settings'    => 'leftc_rights_blocks'
            ))
        );

        // Width Layouts setting heading
        $wp_customize->add_setting( 'leftc_rights_blocks_width_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'leftc_rights_blocks_width_layout_header', array(
                'label'	      => esc_html__( 'Width Layouts', 'newsis' ),
                'section'     => 'newsis_leftc_rights_section',
                'settings'    => 'leftc_rights_blocks_width_layout_header',
                'tab'   => 'design'
            ))
        );

        // width layout
        $wp_customize->add_setting( 'leftc_rights_blocks_width_layout', array(
            'default'           => NI\newsis_get_customizer_default( 'leftc_rights_blocks_width_layout' ),
            'sanitize_callback' => 'newsis_sanitize_select_control',
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Image_Control( $wp_customize, 'leftc_rights_blocks_width_layout',
            array(
                'section'  => 'newsis_leftc_rights_section',
                'tab'   => 'design',
                'choices'  => array(
                    'global' => array(
                        'label' => esc_html__( 'Global', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/global.jpg'
                    ),
                    'boxed--layout' => array(
                        'label' => esc_html__( 'Boxed', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/boxed_content.jpg'
                    ),
                    'full-width--layout' => array(
                        'label' => esc_html__( 'Full Width', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/full_content.jpg'
                    )
                )
            )
        ));

        // leftc rights vertical spacing top
        $wp_customize->add_setting( 'leftc_rights_vertical_spacing_top', array(
            'default'   => NI\newsis_get_customizer_default( 'leftc_rights_vertical_spacing_top' ),
            'sanitize_callback' => 'newsis_sanitize_responsive_range',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Responsive_Range_Control( $wp_customize, 'leftc_rights_vertical_spacing_top', array(
                'label'	      => esc_html__( 'Vertical Spacing Top (px)', 'newsis' ),
                'section'     => 'newsis_leftc_rights_section',
                'settings'    => 'leftc_rights_vertical_spacing_top',
                'unit'        => 'px',
                'input_attrs' => array(
                    'max'         => 200,
                    'min'         => 0,
                    'step'        => 1,
                    'reset' => true
                ),
                'tab'   =>  'design'
            ))
        );

        // leftc rights vertical spacing bottom
        $wp_customize->add_setting( 'leftc_rights_vertical_spacing_bottom', array(
            'default'   => NI\newsis_get_customizer_default( 'leftc_rights_vertical_spacing_bottom' ),
            'sanitize_callback' => 'newsis_sanitize_responsive_range',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Responsive_Range_Control( $wp_customize, 'leftc_rights_vertical_spacing_bottom', array(
                'label'	      => esc_html__( 'Vertical Spacing Bottom (px)', 'newsis' ),
                'section'     => 'newsis_leftc_rights_section',
                'settings'    => 'leftc_rights_vertical_spacing_bottom',
                'unit'        => 'px',
                'input_attrs' => array(
                    'max'         => 200,
                    'min'         => 0,
                    'step'        => 1,
                    'reset' => true
                ),
                'tab'   =>  'design'
            ))
        );

        // Left sidebar - Right content section
        $wp_customize->add_section( 'newsis_lefts_rightc_section', array(
            'title' => esc_html__( 'Left Sidebar - Right Content', 'newsis' ),
            'panel' => 'newsis_front_sections_panel',
            'priority'  => 10
        ));

        // section tab
        $wp_customize->add_setting( 'lefts_rightc_section_tab', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'   => 'general'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Tab_Control( $wp_customize, 'lefts_rightc_section_tab', array(
                'section'     => 'newsis_lefts_rightc_section',
                'choices'  => array(
                    array(
                        'name'  => 'general',
                        'title'  => esc_html__( 'General', 'newsis' )
                    ),
                    array(
                        'name'  => 'design',
                        'title'  => esc_html__( 'Design', 'newsis' )
                    )
                )
            ))
        );

        // redirect to manage sidebar
        $wp_customize->add_setting( 'lefts_rightc_section_sidebar_redirect', array(
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Redirect_Control( $wp_customize, 'lefts_rightc_section_sidebar_redirect', array(
                'label'	      => esc_html__( 'Widgets', 'newsis' ),
                'section'     => 'newsis_lefts_rightc_section',
                'settings'    => 'lefts_rightc_section_sidebar_redirect',
                'tab'   => 'general',
                'choices'     => array(
                    'footer-column-one' => array(
                        'type'  => 'section',
                        'id'    => 'sidebar-widgets-front-left-sidebar',
                        'label' => esc_html__( 'Manage left sidebar', 'newsis' )
                    )
                )
            ))
        );

        // Block Repeater control
        $wp_customize->add_setting( 'lefts_rightc_blocks', array(
            'sanitize_callback' => 'newsis_sanitize_repeater_control',
            'default'   => NI\newsis_get_customizer_default( 'lefts_rightc_blocks' )
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Block_Repeater_Control( $wp_customize, 'lefts_rightc_blocks', array(
                'label'	      => esc_html__( 'Blocks to show in this section', 'newsis' ),
                'description' => esc_html__( 'Hold bar icon at right of block item and drag vertically to re-order blocks', 'newsis' ),
                'section'     => 'newsis_lefts_rightc_section',
                'settings'    => 'lefts_rightc_blocks'
            ))
        );

        // Width Layouts setting heading
        $wp_customize->add_setting( 'lefts_rightc_blocks_width_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'lefts_rightc_blocks_width_layout_header', array(
                'label'	      => esc_html__( 'Width Layouts', 'newsis' ),
                'section'     => 'newsis_lefts_rightc_section',
                'settings'    => 'lefts_rightc_blocks_width_layout_header',
                'tab'   => 'design'
            ))
        );

        // width layout
        $wp_customize->add_setting( 'lefts_rightc_blocks_width_layout',
            array(
            'default'           => NI\newsis_get_customizer_default( 'lefts_rightc_blocks_width_layout' ),
            'sanitize_callback' => 'newsis_sanitize_select_control',
            )
        );
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Image_Control( $wp_customize, 'lefts_rightc_blocks_width_layout',
            array(
                'section'  => 'newsis_lefts_rightc_section',
                'tab'   => 'design',
                'choices'  => array(
                    'global' => array(
                        'label' => esc_html__( 'Global', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/global.jpg'
                    ),
                    'boxed--layout' => array(
                        'label' => esc_html__( 'Boxed', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/boxed_content.jpg'
                    ),
                    'full-width--layout' => array(
                        'label' => esc_html__( 'Full Width', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/full_content.jpg'
                    )
                )
            )
        ));

        // lefts rightc vertical spacing top
        $wp_customize->add_setting( 'lefts_rightc_vertical_spacing_top', array(
            'default'   => NI\newsis_get_customizer_default( 'lefts_rightc_vertical_spacing_top' ),
            'sanitize_callback' => 'newsis_sanitize_responsive_range',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Responsive_Range_Control( $wp_customize, 'lefts_rightc_vertical_spacing_top', array(
                'label'	      => esc_html__( 'Vertical Spacing Top (px)', 'newsis' ),
                'section'     => 'newsis_lefts_rightc_section',
                'settings'    => 'lefts_rightc_vertical_spacing_top',
                'unit'        => 'px',
                'input_attrs' => array(
                    'max'         => 200,
                    'min'         => 0,
                    'step'        => 1,
                    'reset' => true
                ),
                'tab'   =>  'design'
            ))
        );

         // lefts rightc vertical spacing bottom
         $wp_customize->add_setting( 'lefts_rightc_vertical_spacing_bottom', array(
            'default'   => NI\newsis_get_customizer_default( 'lefts_rightc_vertical_spacing_bottom' ),
            'sanitize_callback' => 'newsis_sanitize_responsive_range',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Responsive_Range_Control( $wp_customize, 'lefts_rightc_vertical_spacing_bottom', array(
                'label'	      => esc_html__( 'Vertical Spacing Bottom (px)', 'newsis' ),
                'section'     => 'newsis_lefts_rightc_section',
                'settings'    => 'lefts_rightc_vertical_spacing_bottom',
                'unit'        => 'px',
                'input_attrs' => array(
                    'max'         => 200,
                    'min'         => 0,
                    'step'        => 1,
                    'reset' => true
                ),
                'tab'   =>  'design'
            ))
        );

        // bottom full width content section
        $wp_customize->add_section( 'newsis_bottom_full_width_section', array(
            'title' => esc_html__( 'Bottom Full Width', 'newsis' ),
            'panel' => 'newsis_front_sections_panel',
            'priority'  => 50
        ));

        // section tab
        $wp_customize->add_setting( 'bottom_full_width_section_tab', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'   => 'general'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Tab_Control( $wp_customize, 'bottom_full_width_section_tab', array(
                'section'     => 'newsis_bottom_full_width_section',
                'choices'  => array(
                    array(
                        'name'  => 'general',
                        'title'  => esc_html__( 'General', 'newsis' )
                    ),
                    array(
                        'name'  => 'design',
                        'title'  => esc_html__( 'Design', 'newsis' )
                    )
                )
            ))
        );

        // bottom full width blocks control
        $wp_customize->add_setting( 'bottom_full_width_blocks', array(
            'sanitize_callback' => 'newsis_sanitize_repeater_control',
            'default'   => NI\newsis_get_customizer_default( 'bottom_full_width_blocks' )
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Block_Repeater_Control( $wp_customize, 'bottom_full_width_blocks', array(
                'label'	      => esc_html__( 'Blocks to show in this section', 'newsis' ),
                'description' => esc_html__( 'Hold bar icon at right of block item and drag vertically to re-order blocks', 'newsis' ),
                'section'     => 'newsis_bottom_full_width_section',
                'settings'    => 'bottom_full_width_blocks'
            ))
        );

        // Width Layouts setting heading
        $wp_customize->add_setting( 'bottom_full_width_blocks_width_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'bottom_full_width_blocks_width_layout_header', array(
                'label'	      => esc_html__( 'Width Layouts', 'newsis' ),
                'section'     => 'newsis_bottom_full_width_section',
                'settings'    => 'bottom_full_width_blocks_width_layout_header',
                'tab'   => 'design'
            ))
        );

        // width layout
        $wp_customize->add_setting( 'bottom_full_width_blocks_width_layout', array(
            'default'           => NI\newsis_get_customizer_default( 'bottom_full_width_blocks_width_layout' ),
            'sanitize_callback' => 'newsis_sanitize_select_control',
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Image_Control( $wp_customize, 'bottom_full_width_blocks_width_layout',
            array(
                'section'  => 'newsis_bottom_full_width_section',
                'tab'   => 'design',
                'choices'  => array(
                    'global' => array(
                        'label' => esc_html__( 'Global', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/global.jpg'
                    ),
                    'boxed--layout' => array(
                        'label' => esc_html__( 'Boxed', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/boxed_content.jpg'
                    ),
                    'full-width--layout' => array(
                        'label' => esc_html__( 'Full Width', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/full_content.jpg'
                    )
                )
            )
        ));

        // bottom full width blocks vertical spacing top
        $wp_customize->add_setting( 'bottom_full_width_blocks_vertical_spacing_top', array(
            'default'   => NI\newsis_get_customizer_default( 'bottom_full_width_blocks_vertical_spacing_top' ),
            'sanitize_callback' => 'newsis_sanitize_responsive_range',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Responsive_Range_Control( $wp_customize, 'bottom_full_width_blocks_vertical_spacing_top', array(
                'label'	      => esc_html__( 'Vertical Spacing Top (px)', 'newsis' ),
                'section'     => 'newsis_bottom_full_width_section',
                'settings'    => 'bottom_full_width_blocks_vertical_spacing_top',
                'unit'        => 'px',
                'input_attrs' => array(
                    'max'         => 200,
                    'min'         => 0,
                    'step'        => 1,
                    'reset' => true
                ),
                'tab'   =>  'design'
            ))
        );

        // bottom full width blocks vertical spacing bottom
        $wp_customize->add_setting( 'bottom_full_width_blocks_vertical_spacing_bottom', array(
            'default'   => NI\newsis_get_customizer_default( 'bottom_full_width_blocks_vertical_spacing_bottom' ),
            'sanitize_callback' => 'newsis_sanitize_responsive_range',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Responsive_Range_Control( $wp_customize, 'bottom_full_width_blocks_vertical_spacing_bottom', array(
                'label'	      => esc_html__( 'Vertical Spacing Bottom (px)', 'newsis' ),
                'section'     => 'newsis_bottom_full_width_section',
                'settings'    => 'bottom_full_width_blocks_vertical_spacing_bottom',
                'unit'        => 'px',
                'input_attrs' => array(
                    'max'         => 200,
                    'min'         => 0,
                    'step'        => 1,
                    'reset' => true
                ),
                'tab'   =>  'design'
            ))
        );

        // front sections reorder section
        $wp_customize->add_section( 'newsis_front_sections_reorder_section', array(
            'title' => esc_html__( 'Reorder sections', 'newsis' ),
            'panel' => 'newsis_front_sections_panel',
            'priority'  => 70
        ));

        /**
         * Frontpage sections options
         * 
         * @package Newsis
         * @since 1.0.0
         */
        $wp_customize->add_setting( 'homepage_content_order', array(
            'default'   => NI\newsis_get_customizer_default( 'homepage_content_order' ),
            'sanitize_callback' => 'newsis_sanitize_sortable_control'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Item_Sortable_Control( $wp_customize, 'homepage_content_order', array(
                'label'         => esc_html__( 'Section Re-order', 'newsis' ),
                'description'   => esc_html__( 'Hold item and drag vertically to re-order the items', 'newsis' ),
                'section'       => 'newsis_front_sections_reorder_section',
                'settings'      => 'homepage_content_order',
                'fields'    => array(
                    'full_width_section'  => array(
                        'label' => esc_html__( 'Full width Section', 'newsis' )
                    ),
                    'leftc_rights_section'  => array(
                        'label' => esc_html__( 'Left Content - Right Sidebar', 'newsis' )
                    ),
                    'lefts_rightc_section'  => array(
                        'label' => esc_html__( 'Left Sidebar - Right Content', 'newsis' )
                    ),
                    'bottom_full_width_section'  => array(
                        'label' => esc_html__( 'Bottom Full width Section', 'newsis' )
                    ),
                    'video_playlist'  => array(
                        'label' => esc_html__( 'Video Playlist Section', 'newsis' )
                    ),
                    'latest_posts'  => array(
                        'label' => esc_html__( 'Latest Posts / Page Content', 'newsis' )
                    )
                )
            ))
        );
    }
    add_action( 'customize_register', 'newsis_customizer_front_sections_panel', 10 );
endif;

if( !function_exists( 'newsis_customizer_opinion_section_panel' ) ) :
    /**
     * Register opinion section settings
     * 
     */
    function newsis_customizer_opinion_section_panel( $wp_customize ) {
        // section- opnions section
        $wp_customize->add_section( 'newsis_customizer_opinion_section', array(
            'title' => esc_html__( 'Your opinions', 'newsis' ),
            'priority'  => 76
        ));
    }
    add_action( 'customize_register', 'newsis_customizer_opinion_section_panel', 10 );
endif;

if( !function_exists( 'newsis_customizer_blog_post_archive_panel' ) ) :
    /**
     * Register global options settings
     * 
     */
    function newsis_customizer_blog_post_archive_panel( $wp_customize ) {
        // Blog/Archives panel
        $wp_customize->add_panel( 'newsis_blog_post_archive_panel', array(
            'title' => esc_html__( 'Blog / Archives', 'newsis' ),
            'priority'  => 72
        ));
        
        // blog / archive section
        $wp_customize->add_section( 'newsis_blog_archive_general_setting_section', array(
            'title' => esc_html__( 'General Settings', 'newsis' ),
            'panel' => 'newsis_blog_post_archive_panel',
            'priority'  => 10
        ));

        // archive tab section tab
        $wp_customize->add_setting( 'archive_section_tab', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'   => 'general'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Tab_Control( $wp_customize, 'archive_section_tab', array(
                'section'     => 'newsis_blog_archive_general_setting_section',
                'choices'  => array(
                    array(
                        'name'  => 'general',
                        'title'  => esc_html__( 'General', 'newsis' )
                    ),
                    array(
                        'name'  => 'design',
                        'title'  => esc_html__( 'Design', 'newsis' )
                    )
                )
            ))
        );

        // archive layout settings heading
        $wp_customize->add_setting( 'archive_page_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Toggle_Control( $wp_customize, 'archive_page_layout_header', array(
                'label'	      => esc_html__( 'Layout Settings', 'newsis' ),
                'section'     => 'newsis_blog_archive_general_setting_section'
            ))
        );

        // archive title prefix option
        $wp_customize->add_setting( 'archive_page_title_prefix', array(
            'default' => NI\newsis_get_customizer_default( 'archive_page_title_prefix' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'archive_page_title_prefix', array(
                'label'	      => esc_html__( 'Show archive title prefix', 'newsis' ),
                'section'     => 'newsis_blog_archive_general_setting_section',
                'settings'    => 'archive_page_title_prefix'
            ))
        );

        // archive post layouts
        $wp_customize->add_setting( 'archive_page_layout', array(
            'default'           => NI\newsis_get_customizer_default( 'archive_page_layout' ),
            'sanitize_callback' => 'newsis_sanitize_select_control',
            'transport' =>  'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Image_Control( $wp_customize, 'archive_page_layout',
            array(
                'section'  => 'newsis_blog_archive_general_setting_section',
                'priority' => 10,
                'choices'  => array(
                    'one' => array(
                        'label' => esc_html__( 'Layout One', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/archive_one.jpg'
                    ),
                    'two' => array(
                        'label' => esc_html__( 'Layout Two', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/archive_two.jpg'
                    )
                )
            )
        ));

        // live search heading
        $wp_customize->add_setting( 'archive_page_elements_setting_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Toggle_Control( $wp_customize, 'archive_page_elements_setting_header', array(
                'label'	      => esc_html__( 'Elements Settings', 'newsis' ),
                'section'     => 'newsis_blog_archive_general_setting_section'
            ))
        );

        // archive categories option
        $wp_customize->add_setting( 'archive_page_category_option', array(
            'default' => NI\newsis_get_customizer_default( 'archive_page_category_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'archive_page_category_option', array(
                'label'	      => esc_html__( 'Show archive categories', 'newsis' ),
                'section'     => 'newsis_blog_archive_general_setting_section',
                'settings'    => 'archive_page_category_option'
            ))
        );
        
        // archive elements sort
        $wp_customize->add_setting( 'archive_post_element_order', array(
            'default'   => NI\newsis_get_customizer_default( 'archive_post_element_order' ),
            'sanitize_callback' => 'newsis_sanitize_sortable_control'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Item_Sortable_Control( $wp_customize, 'archive_post_element_order', array(
                'label'         => esc_html__( 'Elements Re-order', 'newsis' ),
                'section'       => 'newsis_blog_archive_general_setting_section',
                'settings'      => 'archive_post_element_order',
                'tab'   => 'general',
                'fields'    => array(
                    'title'  => array(
                        'label' => esc_html__( 'Title', 'newsis' )
                    ),
                    'meta'  => array(
                        'label' => esc_html__( 'Meta', 'newsis' )
                    ),
                    'excerpt'  => array(
                        'label' => esc_html__( 'Excerpt', 'newsis' )
                    ),
                    'button'  => array(
                        'label' => esc_html__( 'Button', 'newsis' )
                    ),
                )
            ))
        );

        // Redirect continue reading button
        $wp_customize->add_setting( 'archive_button_redirect', array(
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Redirect_Control( $wp_customize, 'archive_button_redirect', array(
                'section'     => 'newsis_blog_archive_general_setting_section',
                'settings'    => 'archive_button_redirect',
                'choices'     => array(
                    'header-social-icons' => array(
                        'type'  => 'section',
                        'id'    => 'newsis_buttons_section',
                        'label' => esc_html__( 'Edit button styles', 'newsis' )
                    )
                )
            ))
        );

        // archive meta sort
        $wp_customize->add_setting( 'archive_post_meta_order', array(
            'default'   => NI\newsis_get_customizer_default( 'archive_post_meta_order' ),
            'sanitize_callback' => 'newsis_sanitize_sortable_control'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Item_Sortable_Control( $wp_customize, 'archive_post_meta_order', array(
                'label'         => esc_html__( 'Meta Re-order', 'newsis' ),
                'section'       => 'newsis_blog_archive_general_setting_section',
                'settings'      => 'archive_post_meta_order',
                'tab'   => 'general',
                'fields'    => array(
                    'author'  => array(
                        'label' => esc_html__( 'Author Name', 'newsis' )
                    ),
                    'date'  => array(
                        'label' => esc_html__( 'Published/Modified Date', 'newsis' )
                    ),
                    'comments'  => array(
                        'label' => esc_html__( 'Comments Number', 'newsis' )
                    ),
                    'read-time'  => array(
                        'label' => esc_html__( 'Read Time', 'newsis' )
                    )
                )
            ))
        );

        // archive image settings
        $wp_customize->add_setting( 'archive_image_settings_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Toggle_Control( $wp_customize, 'archive_image_settings_header', array(
                'label'	      => esc_html__( 'Image Settings', 'newsis' ),
                'section'     => 'newsis_blog_archive_general_setting_section'
            ))
        );

        // ticker news image ratio
        $wp_customize->add_setting( 'archive_image_ratio', array(
            'default'   => NI\newsis_get_customizer_default( 'archive_image_ratio' ),
            'sanitize_callback' => 'newsis_sanitize_responsive_range',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Responsive_Range_Control( $wp_customize, 'archive_image_ratio', array(
                    'label'	      => esc_html__( 'Image Ratio', 'newsis' ),
                    'section'     => 'newsis_blog_archive_general_setting_section',
                    'settings'    => 'archive_image_ratio',
                    'unit'        => 'px',
                    'input_attrs' => array(
                    'max'         => 2,
                    'min'         => 0,
                    'step'        => 0.1,
                    'reset' => true
                )
            ))
        );

        // Width Layouts setting heading
        $wp_customize->add_setting( 'archive_width_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'archive_width_layout_header', array(
                'label'	      => esc_html__( 'Width Layouts', 'newsis' ),
                'section'     => 'newsis_blog_archive_general_setting_section',
                'settings'    => 'archive_width_layout_header',
                'tab'   => 'design'
            ))
        );

        // width layout
        $wp_customize->add_setting( 'archive_width_layout', array(
            'default'           => NI\newsis_get_customizer_default( 'archive_width_layout' ),
            'sanitize_callback' => 'newsis_sanitize_select_control',
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Image_Control( $wp_customize, 'archive_width_layout',
            array(
                'section'  => 'newsis_blog_archive_general_setting_section',
                'tab'   => 'design',
                'choices'  => array(
                    'global' => array(
                        'label' => esc_html__( 'Global', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/global.jpg'
                    ),
                    'boxed--layout' => array(
                        'label' => esc_html__( 'Boxed', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/boxed_content.jpg'
                    ),
                    'full-width--layout' => array(
                        'label' => esc_html__( 'Full Width', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/full_content.jpg'
                    )
                )
            )
        ));

        // archive vertical spacing top
        $wp_customize->add_setting( 'archive_vertical_spacing_top', array(
            'default'   => NI\newsis_get_customizer_default( 'archive_vertical_spacing_top' ),
            'sanitize_callback' => 'newsis_sanitize_responsive_range',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Responsive_Range_Control( $wp_customize, 'archive_vertical_spacing_top', array(
                'label'	      => esc_html__( 'Vertical Spacing Top (px)', 'newsis' ),
                'section'     => 'newsis_blog_archive_general_setting_section',
                'settings'    => 'archive_vertical_spacing_top',
                'unit'        => 'px',
                'input_attrs' => array(
                    'max'         => 200,
                    'min'         => 0,
                    'step'        => 1,
                    'reset' => true
                ),
                'tab'   =>  'design'
            ))
        );

        // archive vertical spacing bottom
        $wp_customize->add_setting( 'archive_vertical_spacing_bottom', array(
            'default'   => NI\newsis_get_customizer_default( 'archive_vertical_spacing_bottom' ),
            'sanitize_callback' => 'newsis_sanitize_responsive_range',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Responsive_Range_Control( $wp_customize, 'archive_vertical_spacing_bottom', array(
                'label'	      => esc_html__( 'Vertical Spacing Bottom (px)', 'newsis' ),
                'section'     => 'newsis_blog_archive_general_setting_section',
                'settings'    => 'archive_vertical_spacing_bottom',
                'unit'        => 'px',
                'input_attrs' => array(
                    'max'         => 200,
                    'min'         => 0,
                    'step'        => 1,
                    'reset' => true
                ),
                'tab'   =>  'design'
            ))
        );

        //  single post section
        $wp_customize->add_section( 'newsis_single_post_section', array(
            'title' => esc_html__( 'Single Post', 'newsis' ),
            'priority'  => 73
        ));

        // single tab section tab
        $wp_customize->add_setting( 'single_post_section_tab', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'   => 'general'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Tab_Control( $wp_customize, 'single_post_section_tab', array(
                'section'     => 'newsis_single_post_section',
                'choices'  => array(
                    array(
                        'name'  => 'general',
                        'title'  => esc_html__( 'General', 'newsis' )
                    ),
                    array(
                        'name'  => 'design',
                        'title'  => esc_html__( 'Design', 'newsis' )
                    )
                )
            ))
        );

        // single post show original image
        $wp_customize->add_setting( 'single_post_show_original_image_option', array(
            'default'   => NI\newsis_get_customizer_default( 'single_post_show_original_image_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Checkbox_Control( $wp_customize, 'single_post_show_original_image_option', array(
                'label'	      => esc_html__( 'Show original image', 'newsis' ),
                'section'     => 'newsis_single_post_section',
                'settings'    => 'single_post_show_original_image_option'
            ))
        );

        // single post related news heading
        $wp_customize->add_setting( 'single_post_related_posts_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'single_post_related_posts_header', array(
                'label'	      => esc_html__( 'Related News', 'newsis' ),
                'section'     => 'newsis_single_post_section',
                'settings'    => 'single_post_related_posts_header'
            ))
        );

        // related news option
        $wp_customize->add_setting( 'single_post_related_posts_option', array(
            'default'   => NI\newsis_get_customizer_default( 'single_post_related_posts_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Simple_Toggle_Control( $wp_customize, 'single_post_related_posts_option', array(
                'label'	      => esc_html__( 'Show related news', 'newsis' ),
                'section'     => 'newsis_single_post_section',
                'settings'    => 'single_post_related_posts_option'
            ))
        );

        // related news title
        $wp_customize->add_setting( 'single_post_related_posts_title', array(
            'default' => NI\newsis_get_customizer_default( 'single_post_related_posts_title' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 'single_post_related_posts_title', array(
            'type'      => 'text',
            'section'   => 'newsis_single_post_section',
            'label'     => esc_html__( 'Related news title', 'newsis' )
        ));

        // show related posts on popup
        $wp_customize->add_setting( 'single_post_related_posts_popup_option', array(
            'default'   => NI\newsis_get_customizer_default( 'single_post_related_posts_popup_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Checkbox_Control( $wp_customize, 'single_post_related_posts_popup_option', array(
                'label'	      => esc_html__( 'Show related post on popup box', 'newsis' ),
                'section'     => 'newsis_single_post_section',
                'settings'    => 'single_post_related_posts_popup_option'
            ))
        );

        // single post related news heading
        $wp_customize->add_setting( 'single_post_image_settings_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'single_post_image_settings_header', array(
                'label'	      => esc_html__( 'Image Ratio', 'newsis' ),
                'section'     => 'newsis_single_post_section',
                'settings'    => 'single_post_image_settings_header'
            ))
        );

        // ticker news image ratio
        $wp_customize->add_setting( 'single_post_image_ratio', array(
            'default'   => NI\newsis_get_customizer_default( 'single_post_image_ratio' ),
            'sanitize_callback' => 'newsis_sanitize_responsive_range',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Responsive_Range_Control( $wp_customize, 'single_post_image_ratio', array(
                    'label'	      => esc_html__( 'Image Ratio', 'newsis' ),
                    'section'     => 'newsis_single_post_section',
                    'settings'    => 'single_post_image_ratio',
                    'unit'        => 'px',
                    'input_attrs' => array(
                    'max'         => 2,
                    'min'         => 0,
                    'step'        => 0.1,
                    'reset' => true
                )
            ))
        );

        // Width Layouts setting heading
        $wp_customize->add_setting( 'single_post_width_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'single_post_width_layout_header', array(
                'label'	      => esc_html__( 'Width Layouts', 'newsis' ),
                'section'     => 'newsis_single_post_section',
                'settings'    => 'single_post_width_layout_header',
                'tab'   => 'design'
            ))
        );

        // width layout
        $wp_customize->add_setting( 'single_post_width_layout', array(
            'default'           => NI\newsis_get_customizer_default( 'single_post_width_layout' ),
            'sanitize_callback' => 'newsis_sanitize_select_control',
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Image_Control( $wp_customize, 'single_post_width_layout',
            array(
                'section'  => 'newsis_single_post_section',
                'tab'   => 'design',
                'choices'  => array(
                    'global' => array(
                        'label' => esc_html__( 'Global', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/global.jpg'
                    ),
                    'boxed--layout' => array(
                        'label' => esc_html__( 'Boxed', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/boxed_content.jpg'
                    ),
                    'full-width--layout' => array(
                        'label' => esc_html__( 'Full Width', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/full_content.jpg'
                    )
                )
            )
        ));

        // single post typography heading
        $wp_customize->add_setting( 'single_post_typo_heading', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Toggle_Control( $wp_customize, 'single_post_typo_heading', array(
                'label' => esc_html__( 'Typography', 'newsis' ),
                'section'   => 'newsis_single_post_section',
                'tab'   => 'design'
            ))
        );

        // single post title typo
        $wp_customize->add_setting( 'single_post_title_typo', array(
            'default'   => NI\newsis_get_customizer_default( 'single_post_title_typo' ),
            'sanitize_callback' => 'newsis_sanitize_typo_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Typography_Control( $wp_customize, 'single_post_title_typo', array(
                'label'	      => esc_html__( 'Post Title', 'newsis' ),
                'section'     => 'newsis_single_post_section',
                'settings'    => 'single_post_title_typo',
                'fields'    => array( 'font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'text_transform', 'text_decoration'),
                'tab'   => 'design'
            ))
        );

        // single post meta typo
        $wp_customize->add_setting( 'single_post_meta_typo', array(
            'default'   => NI\newsis_get_customizer_default( 'single_post_meta_typo' ),
            'sanitize_callback' => 'newsis_sanitize_typo_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Typography_Control( $wp_customize, 'single_post_meta_typo', array(
                'label'	      => esc_html__( 'Post Meta', 'newsis' ),
                'section'     => 'newsis_single_post_section',
                'settings'    => 'single_post_meta_typo',
                'fields'    => array( 'font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'text_transform', 'text_decoration'),
                'tab'   => 'design'
            ))
        );
        
        // single post content typo
        $wp_customize->add_setting( 'single_post_content_typo', array(
            'default'   => NI\newsis_get_customizer_default( 'single_post_content_typo' ),
            'sanitize_callback' => 'newsis_sanitize_typo_control',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Typography_Control( $wp_customize, 'single_post_content_typo', array(
                'label'	      => esc_html__( 'Post Content', 'newsis' ),
                'section'     => 'newsis_single_post_section',
                'settings'    => 'single_post_content_typo',
                'fields'    => array( 'font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'text_transform', 'text_decoration'),
                'tab'   => 'design'
            ))
        );

        // h1 typo
        $wp_customize->add_setting( 'single_post_content_h1_typo', array(
            'default'   => NI\newsis_get_customizer_default( 'single_post_content_h1_typo' ),
            'sanitize_callback' => 'newsis_sanitize_typo_control',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Typography_Control( $wp_customize, 'single_post_content_h1_typo', array(
                'label'	      => esc_html__( 'H1 Typography', 'newsis' ),
                'section'     => 'newsis_single_post_section',
                'settings'    => 'single_post_content_h1_typo',
                'fields'    => array( 'font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'text_transform', 'text_decoration'),
                'tab'   => 'design'
            ))
        );

        // h2 typo
        $wp_customize->add_setting( 'single_post_content_h2_typo', array(
            'default'   => NI\newsis_get_customizer_default( 'single_post_content_h2_typo' ),
            'sanitize_callback' => 'newsis_sanitize_typo_control',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Typography_Control( $wp_customize, 'single_post_content_h2_typo', array(
                'label'	      => esc_html__( 'H2 Typography', 'newsis' ),
                'section'     => 'newsis_single_post_section',
                'settings'    => 'single_post_content_h2_typo',
                'fields'    => array( 'font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'text_transform', 'text_decoration'),
                'tab'   => 'design'
            ))
        );

        // h3 typo
        $wp_customize->add_setting( 'single_post_content_h3_typo', array(
            'default'   => NI\newsis_get_customizer_default( 'single_post_content_h3_typo' ),
            'sanitize_callback' => 'newsis_sanitize_typo_control',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Typography_Control( $wp_customize, 'single_post_content_h3_typo', array(
                'label'	      => esc_html__( 'H3 Typography', 'newsis' ),
                'section'     => 'newsis_single_post_section',
                'settings'    => 'single_post_content_h3_typo',
                'fields'    => array( 'font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'text_transform', 'text_decoration'),
                'tab'   => 'design'
            ))
        );

        // h4 typo
        $wp_customize->add_setting( 'single_post_content_h4_typo', array(
            'default'   => NI\newsis_get_customizer_default( 'single_post_content_h4_typo' ),
            'sanitize_callback' => 'newsis_sanitize_typo_control',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Typography_Control( $wp_customize, 'single_post_content_h4_typo', array(
                'label'	      => esc_html__( 'H4 Typography', 'newsis' ),
                'section'     => 'newsis_single_post_section',
                'settings'    => 'single_post_content_h4_typo',
                'fields'    => array( 'font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'text_transform', 'text_decoration'),
                'tab'   => 'design'
            ))
        );

        // h5 typo
        $wp_customize->add_setting( 'single_post_content_h5_typo', array(
            'default'   => NI\newsis_get_customizer_default( 'single_post_content_h5_typo' ),
            'sanitize_callback' => 'newsis_sanitize_typo_control',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Typography_Control( $wp_customize, 'single_post_content_h5_typo', array(
                'label'	      => esc_html__( 'H5 Typography', 'newsis' ),
                'section'     => 'newsis_single_post_section',
                'settings'    => 'single_post_content_h5_typo',
                'fields'    => array( 'font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'text_transform', 'text_decoration'),
                'tab'   => 'design'
            ))
        );

        // h6 typo
        $wp_customize->add_setting( 'single_post_content_h6_typo', array(
            'default'   => NI\newsis_get_customizer_default( 'single_post_content_h6_typo' ),
            'sanitize_callback' => 'newsis_sanitize_typo_control',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Typography_Control( $wp_customize, 'single_post_content_h6_typo', array(
                'label'	      => esc_html__( 'H6 Typography', 'newsis' ),
                'section'     => 'newsis_single_post_section',
                'settings'    => 'single_post_content_h6_typo',
                'fields'    => array( 'font_family', 'font_weight', 'font_size', 'line_height', 'letter_spacing', 'text_transform', 'text_decoration'),
                'tab'   => 'design'
            ))
        );

        // blog / archive pagination section
        $wp_customize->add_section( 'newsis_blog_archive_pagination_section', array(
            'title' => esc_html__( 'Pagination', 'newsis' ),
            'panel' => 'newsis_blog_post_archive_panel',
            'priority'  => 10
        ));
        
        // archive pagination type
        $wp_customize->add_setting( 'archive_pagination_type', array(
            'default' => NI\newsis_get_customizer_default( 'archive_pagination_type' ),
            'sanitize_callback' => 'newsis_sanitize_select_control'
        ));
        $wp_customize->add_control( 'archive_pagination_type', array(
            'label'     => esc_html__( 'Pagination Type', 'newsis' ),
            'type'      => 'select',
            'section'   => 'newsis_blog_archive_pagination_section',
            'choices'   => array(
                'default'   => esc_html__( 'Default', 'newsis' ),
                'number'    => esc_html__( 'Number', 'newsis' )
            ),
        ));
    }
    add_action( 'customize_register', 'newsis_customizer_blog_post_archive_panel', 10 );
endif;

if( !function_exists( 'newsis_customizer_page_panel' ) ) :
    /**
     * Register global options settings
     * 
     */
    function newsis_customizer_page_panel( $wp_customize ) {
        // page panel
        $wp_customize->add_panel( 'newsis_page_panel', array(
            'title' => esc_html__( 'Page Settings', 'newsis' ),
            'priority'  => 74
        ));

        // 404 section
        $wp_customize->add_section( 'newsis_page_section', array(
            'title' => esc_html__( 'Page Setting', 'newsis' ),
            'panel' => 'newsis_page_panel',
            'priority'  => 10
        ));

        // Width Layouts setting heading
        $wp_customize->add_setting( 'single_page_width_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'single_page_width_layout_header', array(
                'label'	      => esc_html__( 'Width Layouts', 'newsis' ),
                'section'     => 'newsis_page_section',
                'settings'    => 'single_page_width_layout_header'
            ))
        );

        // width layout
        $wp_customize->add_setting( 'single_page_width_layout', array(
            'default'           => NI\newsis_get_customizer_default( 'single_page_width_layout' ),
            'sanitize_callback' => 'newsis_sanitize_select_control',
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Image_Control( $wp_customize, 'single_page_width_layout',
            array(
                'section'  => 'newsis_page_section',
                'choices'  => array(
                    'global' => array(
                        'label' => esc_html__( 'Global', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/global.jpg'
                    ),
                    'boxed--layout' => array(
                        'label' => esc_html__( 'Boxed', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/boxed_content.jpg'
                    ),
                    'full-width--layout' => array(
                        'label' => esc_html__( 'Full Width', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/full_content.jpg'
                    )
                )
            )
        ));

        // show original image
        $wp_customize->add_setting( 'single_page_show_original_image_option', array(
            'default'   => NI\newsis_get_customizer_default( 'single_page_show_original_image_option' ),
            'sanitize_callback' => 'newsis_sanitize_toggle_control',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Checkbox_Control( $wp_customize, 'single_page_show_original_image_option', array(
                'label'	      => esc_html__( 'Show original image', 'newsis' ),
                'section'     => 'newsis_page_section',
                'settings'    => 'single_page_show_original_image_option'
            ))
        );

        // single post related news heading
        $wp_customize->add_setting( 'single_page_image_settings_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'single_page_image_settings_header', array(
                'label'	      => esc_html__( 'Image Ratio', 'newsis' ),
                'section'     => 'newsis_page_section',
                'settings'    => 'single_page_image_settings_header'
            ))
        );

        // ticker news image ratio
        $wp_customize->add_setting( 'single_page_image_ratio', array(
            'default'   => NI\newsis_get_customizer_default( 'single_page_image_ratio' ),
            'sanitize_callback' => 'newsis_sanitize_responsive_range',
            'transport' => 'postMessage'
        ));
        $wp_customize->add_control(
            new Newsis_WP_Responsive_Range_Control( $wp_customize, 'single_page_image_ratio', array(
                    'label'	      => esc_html__( 'Image Ratio', 'newsis' ),
                    'section'     => 'newsis_page_section',
                    'settings'    => 'single_page_image_ratio',
                    'unit'        => 'px',
                    'input_attrs' => array(
                    'max'         => 2,
                    'min'         => 0,
                    'step'        => 0.1,
                    'reset' => true
                )
            ))
        );

        // 404 section
        $wp_customize->add_section( 'newsis_404_section', array(
            'title' => esc_html__( '404', 'newsis' ),
            'panel' => 'newsis_page_panel',
            'priority'  => 20
        ));

        // 404 section tab
        $wp_customize->add_setting( '404_section_tab', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'   => 'general'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Tab_Control( $wp_customize, '404_section_tab', array(
                'section'     => 'newsis_404_section',
                'priority'  => 1,
                'choices'  => array(
                    array(
                        'name'  => 'general',
                        'title'  => esc_html__( 'General', 'newsis' )
                    ),
                    array(
                        'name'  => 'design',
                        'title'  => esc_html__( 'Design', 'newsis' )
                    )
                )
            ))
        );

        // 404 image field
        $wp_customize->add_setting( 'error_page_image', array(
            'default' => NI\newsis_get_customizer_default( 'error_page_image' ),
            'sanitize_callback' => 'absint',
        ));
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'error_page_image', array(
            'section' => 'newsis_404_section',
            'mime_type' => 'image',
            'label' => esc_html__( '404 Image', 'newsis' ),
            'description' => esc_html__( 'Upload image that shows you are on 404 error page', 'newsis' )
        )));
        
        // Width Layouts setting heading
        $wp_customize->add_setting( 'error_page_width_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'error_page_width_layout_header', array(
                'label'	      => esc_html__( 'Width Layouts', 'newsis' ),
                'section'     => 'newsis_404_section',
                'settings'    => 'error_page_width_layout_header',
                'tab'   => 'design'
            ))
        );

        // width layout
        $wp_customize->add_setting( 'error_page_width_layout', array(
            'default'           => NI\newsis_get_customizer_default( 'error_page_width_layout' ),
            'sanitize_callback' => 'newsis_sanitize_select_control',
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Image_Control( $wp_customize, 'error_page_width_layout',
            array(
                'section'  => 'newsis_404_section',
                'tab'   => 'design',
                'choices'  => array(
                    'global' => array(
                        'label' => esc_html__( 'Global', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/global.jpg'
                    ),
                    'boxed--layout' => array(
                        'label' => esc_html__( 'Boxed', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/boxed_content.jpg'
                    ),
                    'full-width--layout' => array(
                        'label' => esc_html__( 'Full Width', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/full_content.jpg'
                    )
                )
            )
        ));

        // search page section
        $wp_customize->add_section( 'newsis_search_page_section', array(
            'title' => esc_html__( 'Search Page', 'newsis' ),
            'panel' => 'newsis_page_panel',
            'priority'  => 30
        ));

        // Width Layouts setting heading
        $wp_customize->add_setting( 'search_page_width_layout_header', array(
            'sanitize_callback' => 'sanitize_text_field'
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Section_Heading_Control( $wp_customize, 'search_page_width_layout_header', array(
                'label'	      => esc_html__( 'Width Layouts', 'newsis' ),
                'section'     => 'newsis_search_page_section',
                'settings'    => 'search_page_width_layout_header',
                'tab'   => 'design'
            ))
        );

        // width layout
        $wp_customize->add_setting( 'search_page_width_layout', array(
            'default'           => NI\newsis_get_customizer_default( 'search_page_width_layout' ),
            'sanitize_callback' => 'newsis_sanitize_select_control',
        ));
        $wp_customize->add_control( 
            new Newsis_WP_Radio_Image_Control( $wp_customize, 'search_page_width_layout',
            array(
                'section'  => 'newsis_search_page_section',
                'tab'   => 'design',
                'choices'  => array(
                    'global' => array(
                        'label' => esc_html__( 'Global', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/global.jpg'
                    ),
                    'boxed--layout' => array(
                        'label' => esc_html__( 'Boxed', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/boxed_content.jpg'
                    ),
                    'full-width--layout' => array(
                        'label' => esc_html__( 'Full Width', 'newsis' ),
                        'url'   => '%s/assets/images/customizer/full_content.jpg'
                    )
                )
            )
        ));
    }
    add_action( 'customize_register', 'newsis_customizer_page_panel', 10 );
endif;

// extract to the customizer js
$newsisAddAction = function() {
    $action_prefix = "wp_ajax_" . "newsis_";
    // retrieve posts with search key
    add_action( $action_prefix . 'get_multicheckbox_posts_simple_array', function() {
        check_ajax_referer( 'newsis-customizer-controls-live-nonce', 'security' );
        $searchKey = isset($_POST['search']) ? sanitize_text_field(wp_unslash($_POST['search'])): '';
        $posts_list = get_posts(array('numberposts'=>-1, 's'=>esc_html($searchKey)));
        $posts_array = [];
        foreach( $posts_list as $postItem ) :
            $posts_array[] = array( 
                'value'	=> esc_html( $postItem->ID ),
                'label'	=> esc_html(str_replace(array('\'', '"'), '', $postItem->post_title))
            );
        endforeach;
        wp_send_json_success($posts_array);
        wp_die();
    });
    // retrieve categories with search key
    add_action( $action_prefix . 'get_multicheckbox_categories_simple_array', function() {
        check_ajax_referer( 'newsis-customizer-controls-live-nonce', 'security' );
        $searchKey = isset($_POST['search']) ? sanitize_text_field(wp_unslash($_POST['search'])): '';
        $categories_list = get_categories(array('number'=>-1, 'search'=>esc_html($searchKey)));
        $categories_array = [];
        foreach( $categories_list as $categoryItem ) :
            $categories_array[] = array( 
                'value'	=> esc_html( $categoryItem->term_id ),
                'label'	=> esc_html(str_replace(array('\'', '"'), '', $categoryItem->name)) . ' (' . absint( $categoryItem->count ) . ')'
            );
        endforeach;
        wp_send_json_success($categories_array);
        wp_die();
    });

    // typography fonts url
    add_action( $action_prefix . 'typography_fonts_url', function() {
        check_ajax_referer( 'newsis-customizer-nonce', 'security' );
		// enqueue inline style
		ob_start();
			echo newsis_typo_fonts_url();
        $newsis_typography_fonts_url = ob_get_clean();
		echo apply_filters( 'newsis_typography_fonts_url', esc_url($newsis_typography_fonts_url) );
		wp_die();
	});
};
$newsisAddAction();
