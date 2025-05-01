<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Newsis
 */
use Newsis\CustomizerDefault as NI;
/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function newsis_body_classes( $classes ) {
	global $post;

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	$classes[] = esc_attr( 'newsis-title-' . NI\newsis_get_customizer_option( 'post_title_hover_effects'  ) ); // post title hover effects
	$classes[] = esc_attr( 'newsis-image-hover--effect-' . NI\newsis_get_customizer_option( 'site_image_hover_effects' ) ); // site image hover effects
	$classes[] = esc_attr( 'newsis-post-blocks-hover--effect-' . NI\newsis_get_customizer_option( 'post_block_hover_effects' ) ); // post blocks hover effects
	$classes[] = esc_attr( 'site-' . NI\newsis_get_customizer_option( 'website_layout' ) ); // site layout
	$header_width_layout = NI\newsis_get_customizer_option('header_width_layout');
	$classes[] = esc_attr('header-width--' . $header_width_layout);

	$theme_mode = array_key_exists( 'themeMode', $_COOKIE ) ? $_COOKIE['themeMode'] : '';
	if( $theme_mode == 'dark' ) {
		$classes[] = esc_attr( 'newsis_dark_mode' ); // site mode
		$classes[] = 'newsis_font_typography';
	} else {
		$classes[] = 'newsis_main_body newsis_font_typography';
	}

	$website_block_title_layout = NI\newsis_get_customizer_option('website_block_title_layout');
	$classes[] = esc_attr('block-title--' . $website_block_title_layout);

	if( NI\newsis_get_customizer_option( 'header_search_option' ) ) :
		$classes[] = esc_attr('search-popup--style-one');
	endif;
	
	// page layout
	if( is_page() || is_404() || is_search() ) :
		if( is_front_page() ) {
			$frontpage_sidebar_layout = NI\newsis_get_customizer_option( 'frontpage_sidebar_layout' );
			$frontpage_sidebar_sticky_option = NI\newsis_get_customizer_option( 'frontpage_sidebar_sticky_option' );
			if( $frontpage_sidebar_sticky_option ) $classes[] = esc_attr( 'sidebar-sticky' );
			$classes[] = esc_attr( $frontpage_sidebar_layout );
		} else {
			if( is_page() ) {
				$page_sidebar_layout = NI\newsis_get_customizer_option( 'page_sidebar_layout' );
			} else {
				$page_sidebar_layout = NI\newsis_get_customizer_option( 'page_sidebar_layout' );
			}
			$page_sidebar_sticky_option = NI\newsis_get_customizer_option( 'page_sidebar_sticky_option' );
			if( $page_sidebar_sticky_option ) $classes[] = esc_attr( 'sidebar-sticky' );
			$classes[] = esc_attr( $page_sidebar_layout );
		}
	endif;

	// single post layout
	if( is_single() ) :
		$single_sidebar_layout = NI\newsis_get_customizer_option( 'single_sidebar_layout' );
		$single_sidebar_sticky_option = NI\newsis_get_customizer_option( 'single_sidebar_sticky_option' );
		if( $single_sidebar_sticky_option ) $classes[] = esc_attr( 'sidebar-sticky' );
		$classes[] = esc_attr( $single_sidebar_layout );
	endif;

	// archive layout
	if( is_archive() || is_home() ) :
		$archive_sidebar_layout = NI\newsis_get_customizer_option( 'archive_sidebar_layout' );
		$archive_page_layout = NI\newsis_get_customizer_option( 'archive_page_layout' );
		$archive_sidebar_sticky_option = NI\newsis_get_customizer_option( 'archive_sidebar_sticky_option' );
		if( $archive_sidebar_sticky_option ) $classes[] = esc_attr( 'sidebar-sticky' );
		$classes[] = esc_attr( 'post-layout--'. $archive_page_layout );
		$classes[] = esc_attr( $archive_sidebar_layout );
	endif;

	$card_settings_option = NI\newsis_get_customizer_option( 'card_settings_option' );
	if( $card_settings_option ) $classes[] = 'newsis-iscard';
	return $classes;
}
add_filter( 'body_class', 'newsis_body_classes' );


/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function newsis_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'newsis_pingback_header' );

//define constant
define( 'NEWSIS_INCLUDES_PATH', get_template_directory() . '/inc/' );

/**
 * Enqueue theme scripts and styles.
 */
function newsis_scripts() {
	global $wp_query;
	require_once get_theme_file_path( 'inc/wptt-webfont-loader.php' );
	wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/assets/lib/fontawesome/css/all.min.css', array(), '6.5.1', 'all' );
	wp_enqueue_style( 'slick', get_template_directory_uri() . '/assets/lib/slick/slick.css', array(), '1.8.1', 'all' );
	wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/assets/lib/magnific-popup/magnific-popup.css', array(), '1.1.0', 'all' );
	wp_enqueue_style( 'newsis-typo-fonts', wptt_get_webfont_url( newsis_typo_fonts_url() ), array(), null );
	// enqueue inline style
	wp_enqueue_style( 'newsis-style', get_stylesheet_uri(), array(), NEWSIS_VERSION );
	wp_add_inline_style( 'newsis-style', newsis_current_styles() );
	wp_enqueue_style( 'newsis-main-style', get_template_directory_uri().'/assets/css/main.css', array(), NEWSIS_VERSION );
	// additional css
	wp_enqueue_style( 'newsis-main-style-additional', get_template_directory_uri().'/assets/css/add.css', array(), NEWSIS_VERSION );
	wp_enqueue_style( 'newsis-loader-style', get_template_directory_uri().'/assets/css/loader.css', array(), NEWSIS_VERSION );
	wp_enqueue_style( 'newsis-responsive-style', get_template_directory_uri().'/assets/css/responsive.css', array(), NEWSIS_VERSION );
	wp_style_add_data( 'newsis-style', 'rtl', 'replace' );
	wp_enqueue_script( 'slick', get_template_directory_uri() . '/assets/lib/slick/slick.min.js', array( 'jquery' ), '1.8.1', true );
	wp_enqueue_script( 'magnific-popup', get_template_directory_uri() . '/assets/lib/magnific-popup/magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );
	wp_enqueue_script( 'js-marquee', get_template_directory_uri() . '/assets/lib/js-marquee/jquery.marquee.min.js', array( 'jquery' ), '1.6.0', true );
	wp_enqueue_script( 'js-cookie', get_template_directory_uri() . '/assets/lib/jquery-cookie/jquery-cookie.js', array( 'jquery' ), '1.4.1', true );
	wp_enqueue_script( 'newsis-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), NEWSIS_VERSION, true );
	wp_enqueue_script( 'newsis-theme', get_template_directory_uri() . '/assets/js/theme.js', array( 'jquery' ), NEWSIS_VERSION, true );
	wp_enqueue_script( 'waypoint', get_template_directory_uri() . '/assets/lib/waypoint/jquery.waypoint.min.js', array( 'jquery' ), '4.0.1', true );
	$scriptVars['_wpnonce'] = wp_create_nonce( 'newsis-nonce' );
	$scriptVars['ajaxUrl'] 	= esc_url(admin_url('admin-ajax.php'));
	$scriptVars['stt']	= NI\newsis_get_multiselect_tab_option('stt_responsive_option');
	$scriptVars['sticky_header']= NI\newsis_get_customizer_option('theme_header_sticky');
	$scriptVars['sticky_header_on_scroll_down']= NI\newsis_get_customizer_option('theme_header_sticky_on_scroll_down');
	$scriptVars['livesearch']= NI\newsis_get_customizer_option('theme_header_live_search_option');
	$scriptVars['is_customizer']= is_customize_preview();
	if( is_home() || is_archive() || is_search() ) {
		global $wp_query;
		$scriptVars['paged']	= get_query_var('paged');
		$scriptVars['query_vars'] = $wp_query->query_vars;
	}

	// localize scripts
	wp_localize_script( 'newsis-theme', 'newsisObject' , $scriptVars);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'newsis_scripts' );

if( ! function_exists( 'newsis_current_styles' ) ) :
	/**
	 * Generates the current changes in styling of the theme.
	 * 
	 * @package Newsis
	 * @since 1.0.0
	 */
	function newsis_current_styles() {
		// enqueue inline style
		ob_start();
			// inline style call
			$nPresetCode = function($var,$id) {
				newsis_assign_preset_var($var,$id);
			};
			$nPresetCode( "--newsis-global-preset-color-1", "preset_color_1" );
			$nPresetCode( "--newsis-global-preset-color-2", "preset_color_2" );
			$nPresetCode( "--newsis-global-preset-color-3", "preset_color_3" );
			$nPresetCode( "--newsis-global-preset-color-4", "preset_color_4" );
			$nPresetCode( "--newsis-global-preset-color-5", "preset_color_5" );
			$nPresetCode( "--newsis-global-preset-color-6", "preset_color_6" );
			$nPresetCode( "--newsis-global-preset-color-7", "preset_color_7" );
			$nPresetCode( "--newsis-global-preset-color-8", "preset_color_8" );
			$nPresetCode( "--newsis-global-preset-color-9", "preset_color_9" );
			$nPresetCode( "--newsis-global-preset-color-10", "preset_color_10" );
			$nPresetCode( "--newsis-global-preset-color-11", "preset_color_11" );
			$nPresetCode( "--newsis-global-preset-color-12", "preset_color_12" );
			$nPresetCode( "--newsis-global-preset-gradient-color-1", "preset_gradient_1" );
			$nPresetCode( "--newsis-global-preset-gradient-color-2", "preset_gradient_2" );
			$nPresetCode( "--newsis-global-preset-gradient-color-3", "preset_gradient_3" );
			$nPresetCode( "--newsis-global-preset-gradient-color-4", "preset_gradient_4" );
			$nPresetCode( "--newsis-global-preset-gradient-color-5", "preset_gradient_5" );
			$nPresetCode( "--newsis-global-preset-gradient-color-6", "preset_gradient_6" );
			$nPresetCode( "--newsis-global-preset-gradient-color-7", "preset_gradient_7" );
			$nPresetCode( "--newsis-global-preset-gradient-color-8", "preset_gradient_8" );
			$nPresetCode( "--newsis-global-preset-gradient-color-9", "preset_gradient_9" );
			$nPresetCode( "--newsis-global-preset-gradient-color-10", "preset_gradient_10" );
			$nPresetCode( "--newsis-global-preset-gradient-color-11", "preset_gradient_11" );
			$nPresetCode( "--newsis-global-preset-gradient-color-12", "preset_gradient_12" );
			newsis_header_padding('--header-padding', 'header_vertical_padding');
			newsis_header_padding('--full-width-padding-top', 'full_width_vertical_spacing_top');
			newsis_header_padding('--leftc_rights-padding-top', 'leftc_rights_vertical_spacing_top');
			newsis_header_padding('--lefts_rightc-padding-top', 'lefts_rightc_vertical_spacing_top');
			newsis_header_padding('--bottom-full-width-padding-top', 'bottom_full_width_blocks_vertical_spacing_top');			
			newsis_header_padding('--archive-padding-top', 'archive_vertical_spacing_top');
			newsis_header_padding('--footer-padding-top', 'footer_vertical_spacing_top');

			newsis_header_padding('--full-width-padding-bottom', 'full_width_vertical_spacing_bottom');
			newsis_header_padding('--leftc_rights-padding-bottom', 'leftc_rights_vertical_spacing_bottom');
			newsis_header_padding('--lefts_rightc-padding-bottom', 'lefts_rightc_vertical_spacing_bottom');
			newsis_header_padding('--bottom-full-width-padding-bottom', 'bottom_full_width_blocks_vertical_spacing_bottom');
			newsis_header_padding('--archive-padding-bottom', 'archive_vertical_spacing_bottom');
			newsis_header_padding('--footer-padding-bottom', 'footer_vertical_spacing_bottom');
			$nBackgroundCode = function($identifier,$id) {
				newsis_get_background_style($identifier,$id);
			};
			$nBackgroundCode('.newsis_main_body .site-header.layout--default .top-header','top_header_background_color_group');
			$nBackgroundCode('.newsis_main_body .site-header.layout--default .menu-section .row, .newsis_main_body .site-header.layout--three .header-smh-button-wrap','header_menu_background_color_group');

			$nTypoCode = function($identifier,$id) {
				newsis_get_typo_style($identifier,$id);
			};
			$nTypoCode( "--site-title", 'site_title_typo' );
			$nTypoCode( "--site-tagline", 'site_tagline_typo' );
			$nTypoCode( "--block-title", 'site_section_block_title_typo');
			$nTypoCode("--post-title",'site_archive_post_title_typo');
			$nTypoCode("--meta", 'site_archive_post_meta_typo');
			$nTypoCode("--content", 'site_archive_post_content_typo');
			$nTypoCode("--menu", 'header_menu_typo');
			$nTypoCode("--submenu", 'header_sub_menu_typo');
			$nTypoCode("--custom-btn", 'custom_button_text_typo');
			$nTypoCode("--post-link-btn", 'global_button_typo');
			$nTypoCode("--single-title",'single_post_title_typo');
			$nTypoCode("--single-meta", 'single_post_meta_typo');
			$nTypoCode("--single-content", 'single_post_content_typo');
			$nTypoCode("--single-content-h1", 'single_post_content_h1_typo');
			$nTypoCode("--single-content-h2", 'single_post_content_h2_typo');
			$nTypoCode("--single-content-h3", 'single_post_content_h3_typo');
			$nTypoCode("--single-content-h4", 'single_post_content_h4_typo');
			$nTypoCode("--single-content-h5", 'single_post_content_h5_typo');
			$nTypoCode("--single-content-h6", 'single_post_content_h6_typo');
			newsis_site_logo_width_fnc("body .site-branding img.custom-logo", 'newsis_site_logo_width');
			$nColorGroupCode = function($identifier,$id,$property='color') {
				newsis_color_options_one($identifier,$id,$property);
			};
			$nColorGroupCode('.newsis_main_body #newsis_menu_burger span','header_mobile_menu_button_color','background-color');
			$nColorGroupCode('.newsis_main_body .menu_txt','header_mobile_menu_button_color','color');
			$nColorCode = function($identifier,$id) {
				newsis_text_color_var($identifier,$id);
			};
			$nColorCode('--menu-color','header_menu_color');
			newsis_color_value_change_responsive('nav.main-navigation ul.menu li a, nav.main-navigation ul.nav-menu li a','header_mobile_menu_text_color');
			newsis_get_background_style_responsive('.newsis_main_body nav.main-navigation ul.menu, .newsis_main_body nav.main-navigation ul.nav-menu, .newsis_main_body .main-navigation ul.menu ul, .newsis_main_body .main-navigation ul.nav-menu ul','header_mobile_menu_background_color');
			newsis_top_border_color('.newsis_main_body nav.main-navigation ul.menu, .newsis_main_body nav.main-navigation ul.nav-menu','header_mobile_menu_background_color');
			newsis_get_background_style_var('--site-bk-color', 'site_background_color');
			newsis_visibility_options('.ads-banner','header_ads_banner_responsive_option');
			newsis_visibility_options('body #newsis-scroll-to-top.show','stt_responsive_option');
			newsis_font_size_style('--custom-btn-icon-size','custom_button_icon_size');
			newsis_font_size_style("--readmore-button-font-size", 'global_button_font_size');
			$nBackgroundCode('body.newsis_main_body .site-header.layout--default .site-branding-section, body.newsis_main_body .site-header.layout--default .menu-section', 'header_background_color_group');
			newsis_theme_color('--newsis-global-preset-theme-color','theme_color');
			newsis_theme_color('--menu-color-active', 'header_active_menu_color');
			$nColorCode('--sidebar-toggle-color','header_off_canvas_toggle_color');
			$nBackgroundCode('body.newsis_main_body .site-footer .bottom-footer', 'bottom_footer_background_color_group');
			newsis_image_ratio_variable('--newsis-archive-image-ratio','archive_image_ratio');
			newsis_image_ratio_variable('--newsis-single-image-ratio','single_post_image_ratio');
			newsis_image_ratio_variable('--newsis-page-image-ratio','single_page_image_ratio');
			newsis_box_shadow_styles('.newsis-iscard .newsis-card, .newsis-iscard .widget_block, .newsis-iscard .widget_meta ul','card_box_shadow_control');
			newsis_box_shadow_styles('.newsis-iscard .newsis-card:hover, .newsis-iscard .widget_block:hover, .newsis-iscard .widget_meta ul:hover','card_hover_box_shadow');
			newsis_value_change_responsive('a.post-link-button i','global_button_font_size', 'font-size');
			newsis_category_colors_styles();
			// front sections image settings styles
		$current_styles = ob_get_clean();
		return apply_filters( 'newsis_current_styles', wp_strip_all_tags($current_styles) );
	}
endif;

if( ! function_exists( 'newsis_customizer_social_icons' ) ) :
	/**
	 * Functions get social icons
	 * 
	 */
	function newsis_customizer_social_icons() {
		$social_icons = NI\newsis_get_customizer_option( 'social_icons' );
		$social_icons_target = NI\newsis_get_customizer_option( 'social_icons_target' );
		$decoded_social_icons = json_decode( $social_icons );
		echo '<div class="social-icons">';
			foreach( $decoded_social_icons as $icon ) :
				if( $icon->item_option === 'show' ) {
		?>
					<a class="social-icon" href="<?php echo esc_url( $icon->icon_url ); ?>" target="<?php echo esc_attr( $social_icons_target ); ?>"><i class="<?php echo esc_attr( $icon->icon_class ); ?>"></i></a>
		<?php
				}
			endforeach;
		echo '</div>';
	}
endif;

if( ! function_exists( 'newsis_get_multicheckbox_categories_simple_array' ) ) :
	/**
	 * Return array of categories prepended with "*" key.
	 * 
	 */
	function newsis_get_multicheckbox_categories_simple_array() {
		$categories_list = get_categories(['number'	=> 6]);
		$cats_array = [];
		foreach( $categories_list as $cat ) :
			$cats_array[] = array( 
				'value'	=> esc_html( $cat->term_id ),
				'label'	=> esc_html(str_replace(array('\'', '"'), '', $cat->name)) . ' (' .absint( $cat->count ). ')'
			);
		endforeach;
		return $cats_array;
	}
endif;

if( ! function_exists( 'newsis_get_multicheckbox_posts_simple_array' ) ) :
	/**
	 * Return array of posts prepended with "*" key.
	 * 
	 */
	function newsis_get_multicheckbox_posts_simple_array() {
		$posts_list = get_posts(array('numberposts'=>6));
		$posts_array = [];
		foreach( $posts_list as $postItem ) :
			$posts_array[] = array( 
				'value'	=> esc_html( $postItem->ID ),
				'label'	=> esc_html(str_replace(array('\'', '"'), '', $postItem->post_title))
			);
		endforeach;
		return $posts_array;
	}
endif;

if( ! function_exists( 'newsis_get_date_filter_choices_array' ) ) :
	/**
	 * Return array of date filter choices.
	 * 
	 */
	function newsis_get_date_filter_choices_array() {
		return apply_filters( 'newsis_get_date_filter_choices_array_filter', [
			'all'	=> esc_html__('All', 'newsis' ),
			'last-seven-days'	=> esc_html__('Last 7 days', 'newsis' ),
			'today'	=> esc_html__('Today', 'newsis' ),
			'this-week'	=> esc_html__('This Week', 'newsis' ),
			'last-week'	=> esc_html__('Last Week', 'newsis' ),
			'this-month'	=> esc_html__('This Month', 'newsis' ),
			'last-month'	=> esc_html__('Last Month', 'newsis' ),
			'this-year'	=> esc_html__('This Year', 'newsis' )
		]);
	}
endif;

if( ! function_exists( 'newsis_get_array_key_string_to_int' ) ) :
	/**
	 * Return array of int values.
	 * 
	 */
	function newsis_get_array_key_string_to_int( $array ) {
		if( ! isset( $array ) || empty( $array ) ) return;
		$filtered_array = array_map( function($arr) {
			if( is_numeric( $arr ) ) return (int) $arr;
		}, $array);
		return apply_filters( 'newsis_array_with_int_values', $filtered_array );
	}
endif;

/**
 * Return string with "implode" execution in param
 * 
 */
 function newsis_get_categories_for_args($array) {
	if( ! $array ) return apply_filters( 'newsis_categories_for_argument', '' );
	foreach( $array as $value ) {
		$string_array[] = $value->value;
	}
	return apply_filters( 'newsis_categories_for_argument', implode( ',', $string_array ) );
}

/**
 * Return array with execution in param
 * 
 */
function newsis_get_post_id_for_args($array) {
	if( ! $array ) return apply_filters( 'newsis_posts_slugs_for_argument', '' );
	foreach( $array as $value ) {
		$string_array[] = $value->value;
	}
	return apply_filters( 'newsis_posts_slugs_for_argument', $string_array );
}

if( !function_exists( 'newsis_typo_fonts_url' ) ) :
	/**
	 * Filter and Enqueue typography fonts
	 * 
	 * @package Newsis
	 * @since 1.0.0
	 */
	function newsis_typo_fonts_url() {
		$filter = NEWSIS_PREFIX . 'typo_combine_filter';
		$action = function($filter,$id) {
			return apply_filters(
				$filter,
				$id
			);
		};
		$site_title_typo_value = $action($filter,'site_title_typo');
		$site_tagline_typo_value = $action($filter,'site_tagline_typo');
		$header_menu_typo_value = $action($filter,'header_menu_typo');
		$header_sub_menu_typo_value = $action($filter,'header_sub_menu_typo');
		$site_section_block_title_typo_value = $action($filter,'site_section_block_title_typo');
		$site_archive_post_title_typo_value = $action($filter,'site_archive_post_title_typo');
		$site_archive_post_meta_typo_value = $action($filter,'site_archive_post_meta_typo');
		$site_archive_post_content_typo_value = $action($filter,'site_archive_post_content_typo');
		$single_post_title_typo_value = $action($filter,'single_post_title_typo');
		$single_post_meta_typo_value = $action($filter,'single_post_meta_typo');
		$single_post_content_typo_value = $action($filter,'single_post_content_typo');
		$single_post_content_h1_typo_value = $action($filter,'single_post_content_h1_typo');
		$single_post_content_h2_typo_value = $action($filter,'single_post_content_h2_typo');
		$single_post_content_h3_typo_value = $action($filter,'single_post_content_h3_typo');
		$single_post_content_h4_typo_value = $action($filter,'single_post_content_h4_typo');
		$single_post_content_h5_typo_value = $action($filter,'single_post_content_h5_typo');
		$single_post_content_h6_typo_value = $action($filter,'single_post_content_h6_typo');
		$custom_button_text_typo = $action($filter,'custom_button_text_typo');
		$global_button_typo = $action($filter,'global_button_typo');
		$typo1 = "Frank Ruhl Libre:100,300,500,600";
		$typo2 = "Noto Sans JP:100,300,500,600,700";

		$get_fonts = apply_filters( 'newsis_get_fonts_toparse', [$site_title_typo_value, $site_tagline_typo_value, $header_menu_typo_value, $header_sub_menu_typo_value, $site_section_block_title_typo_value, $site_archive_post_title_typo_value, $site_archive_post_meta_typo_value, $site_archive_post_content_typo_value, $single_post_title_typo_value, $single_post_meta_typo_value, $single_post_content_typo_value, $single_post_content_h1_typo_value, $single_post_content_h2_typo_value, $single_post_content_h3_typo_value, $single_post_content_h4_typo_value, $single_post_content_h5_typo_value, $single_post_content_h6_typo_value, $custom_button_text_typo, $global_button_typo, $typo1, $typo2] );
		$font_weight_array = array();

		foreach ( $get_fonts as $fonts ) {
			$each_font = explode( ':', $fonts );
			if ( ! isset ( $font_weight_array[$each_font[0]] ) ) {
				$font_weight_array[$each_font[0]][] = $each_font[1];
			} else {
				if ( ! in_array( $each_font[1], $font_weight_array[$each_font[0]] ) ) {
					$font_weight_array[$each_font[0]][] = $each_font[1];
				}
			}
		}
		$final_font_array = array();
		foreach ( $font_weight_array as $font => $font_weight ) {
			$each_font_string = $font.':'.implode( ',', $font_weight );
			$final_font_array[] = $each_font_string;
		}

		$final_font_string = implode( '|', $final_font_array );
		$google_fonts_url = '';
		$subsets   = 'cyrillic,cyrillic-ext';
		if ( $final_font_string ) {
			$query_args = array(
				'family' => urlencode( $final_font_string ),
				'subset' => urlencode( $subsets )
			);
			$google_fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
		}
		return $google_fonts_url;
	}
endif;

if(! function_exists('newsis_get_color_format')):
    function newsis_get_color_format($color) {
		if( str_contains( $color, '--newsis-global-preset' ) ) {
			return( 'var( ' .esc_html( $color ). ' )' );
		} else {
			return $color;
		}
    }
endif;

if( ! function_exists( 'newsis_get_rcolor_code' ) ) :
	/**
	 * Returns randon color code
	 * 
	 * @package Newsis
	 * @since 1.0.0
	 */
	function newsis_get_rcolor_code() {
		$color_array["color"] = "#333333";
		$color_array["hover"] = "#448bef";
		return apply_filters( 'newsis_apply_random_color_shuffle_value', $color_array );
	}
endif;

require get_template_directory() . '/inc/theme-starter.php'; // theme starter functions.
require get_template_directory() . '/inc/customizer/customizer.php'; // Customizer additions.
require get_template_directory() . '/inc/extras/helpers.php'; // helpers files.
require get_template_directory() . '/inc/extras/extras.php'; // extras files.
require get_template_directory() . '/inc/widgets/widgets.php'; // widget handlers
include get_template_directory() . '/inc/styles.php';
include get_template_directory() . '/inc/admin/class-theme-info.php';

/**
 * Filter posts ajax function
 *
 * @package Newsis
 * @since 1.0.0
 */
function newsis_filter_posts_load_tab_content() {
	check_ajax_referer( 'newsis-nonce', 'security' );
	$options = isset( $_GET['options'] ) ? json_decode( stripslashes( $_GET['options'] ) ): '';
	if( empty( $options ) ) return;
	$query = json_decode( $options->query );
	$layout = $options->layout;
	$orderArray = explode( '-', $query->order );
	$posts_args = array(
		'posts_per_page'   => absint( $query->count ),
		'order' => esc_html( $orderArray[1] ),
		'orderby' => esc_html( $orderArray[0] ),
		'cat' => esc_html( $options->category_id ),
		'ignore_sticky_posts'    => true
	);
	if( $query->ids ) $post_args['post__not_in'] = newsis_get_array_key_string_to_int( $query->ids );
	$n_posts = new \WP_Query( $posts_args );
	$total_posts = $n_posts->post_count;
	if( $n_posts -> have_posts() ):
		ob_start();
		echo '<div class="tab-content content-' .esc_html( $options->category_id ). '">';
			while( $n_posts->have_posts() ) : $n_posts->the_post();
				$options->featuredPosts = false;
				$res['loaded'] = true;
				$current_post = $n_posts->current_post;
				if( $layout == 'four' ) {
					if( $current_post === 0 ) echo '<div class="featured-post">';
					if( $current_post === 0 || $current_post === 1 ) $options->featuredPosts = true;
					if( $current_post === 2 ) {
						?>
						<div class="trailing-post">
						<?php
					}
				} else {
					if( ($current_post % 5) === 0 ) echo '<div class="row-wrap">';
						if( $current_post === 0 ) {
							echo '<div class="featured-post">';
							$options->featuredPosts = true;
						}
							if( $current_post === 1 || $current_post === 5 ) {
								?>
								<div class="trailing-post <?php if($current_post === 5) echo esc_attr('bottom-trailing-post'); ?>">
								<?php
							}
				}
								// get template file w.r.t par
								get_template_part( 'template-parts/news-filter/content', 'one', $options );
				if( $layout == 'four' ) {
					if( $total_posts === $current_post + 1 ) echo '</div><!-- .trailing-post -->';
						if( $current_post === 1 ) echo '</div><!-- .featured-post-->';
				} else {
							if( $current_post === 4 || ( $total_posts === $current_post + 1 ) ) echo '</div><!-- .trailing-post -->';
						if( $current_post === 0 ) echo '</div><!-- .featured-post-->';
					if( ($current_post % 5) === 4 || ( $total_posts === $current_post + 1 ) ) echo '</div><!-- .row-wrap -->';
				}
			endwhile;
		echo '</div>';	
		$res['posts'] = ob_get_clean();
	else :
		$res['loaded'] = false;
		$res['posts'] = esc_html__( 'No posts found', 'newsis' );
	endif;
	wp_send_json_success( $res );
	wp_die();
}
add_action( 'wp_ajax_newsis_filter_posts_load_tab_content', 'newsis_filter_posts_load_tab_content');
add_action( 'wp_ajax_nopriv_newsis_filter_posts_load_tab_content', 'newsis_filter_posts_load_tab_content' );

if( ! function_exists( 'newsis_search_posts_content' ) ) :
	/**
	 * Posts ajax function with search query
	 *
	 * @package Newsis
	 * @since 1.0.0
	 */
	function newsis_search_posts_content() {
		check_ajax_referer( 'newsis-nonce', 'security' );
		$search_key = isset( $_POST['search_key'] ) ? sanitize_text_field( stripslashes( $_POST['search_key'] ) ): '';
		$query_vars = [
			'post_type'	=> 'post',
			'post_status'	=> 'publish',
			'posts_per_page'	=> 4,
			's'	=> esc_html($search_key)
		];
		$n_posts = new WP_Query( $query_vars );
		$res['loaded'] = false;
		if ( $n_posts->have_posts() ) :
			ob_start();
			echo '<div class="search-results-wrap">';
				echo '<div class="search-posts-wrap">';
				$res['loaded'] = true;
					/* Start the Loop */
					while ( $n_posts->have_posts() ) :
						$n_posts->the_post();
						?>
							<div class="article-item">
								<figure class="post-thumb-wrap <?php if( ! has_post_thumbnail() ){ echo esc_attr( 'no-feat-img' ); } ?>">
									<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
										<?php
											if( has_post_thumbnail() ) { 
												the_post_thumbnail( 'thumbnail', array(
													'title' => the_title_attribute(array(
														'echo'  => false
													))
												));
											}
										?>
									</a>
								</figure>
								<div class="post-element">
									<h2 class="post-title">
										<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" target="<?php echo esc_attr( NI\newsis_get_customizer_option( 'theme_header_live_search_button_target' ) ); ?>">
											<?php the_title(); ?>
										</a>
									</h2>
									<?php newsis_posted_on(); ?>
								</div>
							</div>
						<?php
					endwhile;
				echo '</div><!-- .search-posts-wrap -->';
				?>
					<a class="view-all-search-button" href="<?php echo esc_url( get_search_link( esc_html($search_key) ) ); ?>" target="<?php echo esc_attr( NI\newsis_get_customizer_option( 'theme_header_live_search_button_target' ) ); ?>"><?php echo esc_html( NI\newsis_get_customizer_option( 'theme_header_live_search_button_label' ) ); ?></a>
				<?php
			echo '</div><!-- .search-results-wrap -->';
			$res['posts'] = ob_get_clean();
		else :
			ob_start();
				?>
				<div class="search-results-wrap no-posts-found">
					<h2 class="no-posts-found-title"><?php echo esc_html__( '404 Not Found', 'newsis' ); ?></h2>
					<p class="no-posts-found-description"><?php echo esc_html__( 'It looks like nothing was found at this location. Maybe try another search?', 'newsis' ); ?></p>
				</div><!-- .search-results-wrap -->
				<?php
			$res['posts'] = ob_get_clean();
		endif;
		wp_send_json_success( $res );
		wp_die();
	}
	add_action( 'wp_ajax_newsis_search_posts_content', 'newsis_search_posts_content');
	add_action( 'wp_ajax_nopriv_newsis_search_posts_content', 'newsis_search_posts_content' );
endif;

if( ! function_exists( 'newsis_lazy_load_value' ) ) :
	/**
	 * Echos lazy load attribute value.
	 * 
	 * @package Newsis
	 * @since 1.0.0
	 */
	function newsis_lazy_load_value() {
		echo esc_attr( apply_filters( 'newsis_lazy_load_value', 'lazy' ) );
	}
endif;

if( ! function_exists( 'newsis_add_menu_description' ) ) :
	// merge menu description element to the menu 
	function newsis_add_menu_description( $item_output, $item, $depth, $args ) {
		if($args->theme_location != 'menu-2') return $item_output;
		
		if ( !empty( $item->description ) ) {
			$item_output = str_replace( $args->link_after . '</a>', '<span class="menu-item-description">' . $item->description . '</span>' . $args->link_after . '</a>', $item_output );
		}
		return $item_output;
	}
	add_filter( 'walker_nav_menu_start_el', 'newsis_add_menu_description', 10, 4 );
endif;

if( ! function_exists( 'newsis_bool_to_string' ) ) :
	// boolean value to string 
	function newsis_bool_to_string( $bool ) {
		$string = ( $bool ) ? '1' : '0';
		return $string;
	}
endif;
	
 if( ! function_exists( 'newsis_get_image_sizes_option_array' ) ) :
	/**
	 * Get list of image sizes
	 * 
	 * @since 1.0.0
	 * @package Newsis
	 */
	function newsis_get_image_sizes_option_array() {
		$image_sizes = get_intermediate_image_sizes();
		foreach( $image_sizes as $image_size ) :
			$sizes[] = [
				'label'	=> esc_html( $image_size ),
				'value'	=> esc_html( $image_size )
			];
		endforeach;
		return $sizes;
	}
endif;

if( ! function_exists( 'newsis_get_style_tag' ) ) :
	/**
	 * Generate Style tag for image ratio and image radius for news grid, list, carousel
	 * 
	 * @since 1.0.0
	 * @package Newsis
	 */
	function newsis_get_style_tag( $variables, $selectors = '' ) {
		echo '<style id="'. esc_attr( $variables['unique_id'] ) .'-style">';
			if( isset( $variables['image_ratio'] ) ) {
				$image_ratio = json_decode( $variables['image_ratio'] );

				if( $image_ratio->desktop > 0 ) echo "#" . $variables['unique_id']. " article figure.post-thumb-wrap { padding-bottom: calc( " . $image_ratio->desktop . " * 100% ) }\n";

				if( $image_ratio->tablet > 0 ) echo " @media (max-width: 769px){ #" . $variables['unique_id']. " article figure.post-thumb-wrap { padding-bottom: calc( " . $image_ratio->tablet . " * 100% ) } }\n";

				if( $image_ratio->smartphone > 0 ) echo " @media (max-width: 548px){ #" . $variables['unique_id']. " article figure.post-thumb-wrap { padding-bottom: calc( " . $image_ratio->smartphone . " * 100% ) }}\n";

			}
		echo "</style>";
	}
endif;

if( ! function_exists( 'newsis_get_style_tag_fb' ) ) :
	/**
	 * Generates style tag for image ratio and image radius for news filter and new block
	 * 
	 * @since 1.0.0
	 * @package Newsis
	 */
	function newsis_get_style_tag_fb( $variables, $selectors = '' ) {
		echo '<style id="'. esc_attr( $variables['unique_id'] ) .'-style">';
			if( isset( $variables['image_ratio'] ) ) {
				$image_ratio = json_decode( $variables['image_ratio'] );
				// for featured post
				if( $variables['layout'] == 'three' ) {

					if( $image_ratio->desktop > 0 ) echo "#" . $variables['unique_id']. ".newsis-block.layout--three .featured-post figure { padding-bottom: calc( " . ( $image_ratio->desktop * 0.4 ) . " * 100% ) }\n";

					if( $image_ratio->tablet > 0 ) echo "@media (max-width: 769px) {#" . $variables['unique_id']. ".newsis-block.layout--three .featured-post figure { padding-bottom: calc( " . ( $image_ratio->tablet * 0.4 ) . " * 100% ) } }\n";

					if( $image_ratio->smartphone > 0 ) echo "@media (max-width: 769px) {#" . $variables['unique_id']. ".newsis-block.layout--three .featured-post figure { padding-bottom: calc( " . ( $image_ratio->smartphone * 0.4 ) . " * 100% ) } }\n";	

				} else {

					if( $image_ratio->desktop > 0 ) echo "#" . $variables['unique_id']. ".newsis-block .featured-post figure { padding-bottom: calc( " . $image_ratio->desktop . " * 100% ) }\n";

					if( $image_ratio->tablet > 0 ) echo "@media (max-width: 769px) {#" . $variables['unique_id']. ".newsis-block .featured-post figure { padding-bottom: calc( " . $image_ratio->tablet . " * 100% ) } }\n";

					if( $image_ratio->smartphone > 0 ) echo "@media (max-width: 769px) {#" . $variables['unique_id']. ".newsis-block .featured-post figure { padding-bottom: calc( " . $image_ratio->smartphone . " * 100% ) } }\n";

				}

				// for trailing post
				if( $variables['layout'] == 'two' ) {
					
					if( $image_ratio->desktop > 0 ) echo "#" . $variables['unique_id']. ".newsis-block.layout--two .trailing-post figure { padding-bottom: calc( " . ( $image_ratio->desktop * 0.78 ) . " * 100% ) }\n";

					if( $image_ratio->tablet > 0 ) echo "@media (max-width: 769px) {#" . $variables['unique_id']. ".newsis-block .trailing-post figure { padding-bottom: calc( " . ( $image_ratio->tablet * 0.78 ) . " * 100% ) } }\n";

					if( $image_ratio->smartphone > 0 ) echo "@media (max-width: 769px) {#" . $variables['unique_id']. ".newsis-block.layout--two .trailing-post figure { padding-bottom: calc( " . ( $image_ratio->smartphone * 0.78 ) . " * 100% ) } }\n";

				} else {

					if( $image_ratio->desktop > 0 ) echo "#" . $variables['unique_id']. ".newsis-block .trailing-post figure { padding-bottom: calc( " . ( $image_ratio->desktop * 0.3 ) . " * 100% ) }\n";

					if( $image_ratio->tablet > 0 ) echo "@media (max-width: 769px) {#" . $variables['unique_id']. ".newsis-block .trailing-post figure { padding-bottom: calc( " . ( $image_ratio->tablet * 0.3 ) . " * 100% ) } }\n";

					if( $image_ratio->smartphone > 0 ) echo "@media (max-width: 769px) {#" . $variables['unique_id']. ".newsis-block .trailing-post figure { padding-bottom: calc( " . ( $image_ratio->smartphone * 0.3 ) . " * 100% ) } }\n";

				}

			}
		echo "</style>";
	}
endif;