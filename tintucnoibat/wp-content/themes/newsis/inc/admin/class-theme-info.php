<?php
/**
 * Theme admin page detail info
 * 
 * @package Newsis
 * @since 1.1.0
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if( !class_exists( 'Newsis_Theme_Info' ) ) :
    class Newsis_Theme_Info {
        /**
         * Variable
         */ 
        protected $theme_name;
        protected $version;
        protected $demofile;
        protected $importer_status;
        protected $newsis_disable_admin_notices;
        public $ajax_response = array();
        public $restriction_message;
        private static $_instance = null;

        /**
         * Ensures only one instance of the class is loaded or can be loaded
         * 
         * @access public
         * @static
         */
        public static function instance() {
            if( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Initial class load
         * 
         */
        function __construct() {
            $this->theme_name = esc_html__( 'Newsis', 'newsis' );
            $this->version = wp_get_theme()->get( 'Version' );
            $this->demofile = include get_template_directory() . '/inc/admin/assets/demos.php';
            $this->newsis_disable_admin_notices = get_theme_mod( 'newsis_disable_admin_notices', false );
            $this->restriction_message = esc_html__( "You dont have permission to perform this action", "newsis" );
            //Add the theme page
            add_action( 'admin_menu', array( $this, 'add_theme_info_menu' ) );
            if( ! $this->newsis_disable_admin_notices ) :
                add_action( 'admin_notices', array( $this, 'add_welcome_admin_notice' ) );
                add_action( 'admin_notices', array( $this, 'add_review_admin_notice' ) );
            endif;
            add_action( 'admin_enqueue_scripts', array( $this, 'theme_info_scripts' ) );
            add_action( 'wp_ajax_newsis_importer_plugin_action', array( $this, 'newsis_importer_plugin_action' ) );
            add_action( 'wp_ajax_newsis_set_ajax_transient', array( $this, 'newsis_set_ajax_transient' ) );
            add_action( 'wp_ajax_newsis_dismiss_welcome_notice', array( $this, 'newsis_dismiss_welcome_notice' ) );
            add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widgets' ) );
        }

        /**
         * Enqueue scripts
         * 
         */
        function theme_info_scripts($hook) {
            if( ! $this->newsis_disable_admin_notices ) :
                $newsis_welcome_notice_dismiss = get_option( 'newsis_welcome_notice_dismiss' );
                if( ! $newsis_welcome_notice_dismiss ) {
                    wp_enqueue_style( 'newsis-welcome-notice', get_template_directory_uri() . '/inc/admin/assets/welcome-notice.css', array(), esc_attr( $this->version ), 'all' );
                }
                if( ! get_transient( 'newsis_show_review_notice' ) ) :
                    wp_enqueue_style( 'newsis-admin-notice', get_template_directory_uri() . '/inc/admin/assets/admin-notice.css', array(), esc_attr( $this->version ), 'all' );
                endif;
                    wp_enqueue_script( 'newsis-admin-notice', get_template_directory_uri() . '/inc/admin/assets/admin-notice.js', array( 'jquery' ), esc_attr( $this->version ), true );
                    wp_localize_script( 'newsis-admin-notice', 'newsisThemeInfoObject', array(
                        'ajaxUrl'   => esc_url( admin_url( 'admin-ajax.php' ) ),
                        '_wpnonce'  => wp_create_nonce( 'newsis-theme-info-nonce' ),
                        'dismissingText'    => esc_html__( 'Dismissing', 'newsis' )
                    ));
            endif;
            if( $hook != "appearance_page_newsis-info" ) {
                return;
            }
            wp_enqueue_style( 'newsis-info', get_template_directory_uri() . '/inc/admin/assets/info-page.css', array(), esc_attr( $this->version ), 'all' );
            wp_enqueue_script( 'newsis-info', get_template_directory_uri() . '/inc/admin/assets/info-page.js', array( 'jquery' ), esc_attr( $this->version ), true );
            wp_localize_script( 'newsis-info', 'newsisThemeInfoObject', array(
                'ajaxUrl'   => esc_url( admin_url( 'admin-ajax.php' ) ),
                '_wpnonce'  => wp_create_nonce( 'newsis-theme-info-nonce' )
            ));
        }

        // Register admin menu for theme info
        function add_theme_info_menu() {
            $theme_info = add_theme_page( 
                esc_html__( 'Newsis Options', 'newsis' ),
                esc_html__( 'Newsis Options','newsis' ),
                'manage_options', 
                'newsis-info', 
                array( $this, 'info_page_callback' )
            );
        }

        /**
         * Theme info page callback
         * 
         * renders the theme info structure
         */
        function info_page_callback() {
        ?>
            <div id="theme-info-admin">
                <div class="info-container">
                    <h2 class="info-title"><?php echo esc_html( $this->theme_name ). ' - ' . esc_attr( $this->version ); ?></h2>
                    <div class="info-block">
                        <a href="<?php echo esc_url('//doc.blazethemes.com/newsis/');?>" target="_blank" class="dashicons dashicons-book-alt info-icon"></a>
                        <p class="info-text">
                            <a href="<?php echo esc_url('//doc.blazethemes.com/newsis/');?>" target="_blank"><?php esc_html_e( 'Setup Tutorials', 'newsis' ); ?></a>
                        </p>
                    </div>
                    <div class="info-block">
                        <a href="<?php echo esc_url( '//blazethemes.com/support' ); ?>" target="_blank" class="dashicons dashicons-sos info-icon"></a>
                        <p class="info-text">
                            <a href="<?php echo esc_url( '//blazethemes.com/support' ); ?>" target="_blank"><?php esc_html_e('Support','newsis'); ?></a>
                        </p>
                    </div>
                    <div class="info-block">
                        <a href="<?php echo esc_url( '//preview.blazethemes.com/newsis-one/' ); ?>" target="_blank" class="dashicons dashicons-desktop info-icon"></a>
                        <p class="info-text">
                            <a href="<?php echo esc_url( '//preview.blazethemes.com/newsis-one/' ); ?>" target="_blank"><?php esc_html_e('Theme demo','newsis'); ?></a>
                        </p>
                    </div>
                    <div class="info-block">
                        <a href="<?php echo esc_url( '//wordpress.org/support/theme/newsis/reviews/?filter=5' ); ?>" target="_blank" class="dashicons dashicons-thumbs-up info-icon"></a>
                        <p class="info-text">
                            <a href="<?php echo esc_url( '//wordpress.org/support/theme/newsis/reviews/?filter=5' ); ?>" target="_blank"><?php esc_html_e('Leave a review','newsis'); ?></a>
                        </p>
                    </div>
                    <div class="info-block">
                        <a href="<?php echo esc_url( '//blazethemes.com/theme/newsis-free/#theme-comparison-section' ); ?>" target="_blank" class="dashicons dashicons-editor-table info-icon"></a>
                        <p class="info-text">
                            <a href="<?php echo esc_url( '//blazethemes.com/theme/newsis-free/#theme-comparison-section' ); ?>" target="_blank"><?php esc_html_e('Free vs PRO','newsis'); ?></a>
                        </p>
                    </div>
                </div><!-- .info-container -->
                <div class="theme-info-admin-inner">
                    <div class="theme-demos-listing">
                        <h2 class="info-title"><?php esc_html_e( 'Free and Premium Demos', 'newsis' ); ?></h2>
                        <div class="demo-importer-actions">
                            <?php
                                $this->importer_status = $this->plugin_active_status('blaze-demo-importer/blaze-demo-importer.php');
                                switch( $this->importer_status ) {
                                    case 'inactive' : printf( esc_html__( 'Activate Blazethemes Demo Importer Now and Import any available demo in One Click', 'newsis') . ' %s', '<button class="newsis-importer-action-trigger" data-action="activate" data-process="' .esc_html( "Activating plugin" ). '">' .esc_html( 'Activate Plugin' ). '</button>' ); 
                                                    break;
                                    case 'install'  : printf( esc_html__( 'Install BlazeThemes Demo Importer and Import any available demo in One Click', 'newsis') . ' %s', '<button class="newsis-importer-action-trigger" data-action="install" data-process="' .esc_html( "Installing plugin" ). '">' .esc_html( 'Install and Activate Plugin' ). '</button>' );
                                                    break;
                                            default: esc_html_e( 'All Ready for demo import!! Setup your site exactly like demo', 'newsis' );
                                }
                            ?>
                        </div>
                    </div>
                    <?php $this->theme_display_demos(); ?>
                </div>
            </div><!-- #theme-info-admin -->
        <?php
        }

        /*
         *  Display the available demos
         */

        function theme_display_demos() {
            ?>
            <div class="wrap blaze-demo-importer-demo-importer-wrap">
                <?php
                if (is_array($this->demofile) && !is_null($this->demofile) && !empty($this->demofile)) {
                    $tags = array();
                    foreach ($this->demofile as $demo_slug => $demo_pack) {
                        if (isset($demo_pack['tags']) && is_array($demo_pack['tags'])) {
                            foreach ($demo_pack['tags'] as $key => $tag) {
                                $tags[$key] = $tag;
                            }
                        }
                    }
                    asort($tags);
                    
                    if ( !empty( $tags ) ) {
                        ?>
                        <div class="blaze-demo-importer-tab-filter blaze-demo-importer-clearfix">
                            <?php
                                if (!empty($tags)) {
                                    ?>
                                    <div class="blaze-demo-importer-tab-group" data-filter-group="tag">
                                        <div class="blaze-demo-importer-tab blaze-demo-importer-active" data-filter="*">
                                            <?php esc_html_e('All', 'newsis'); ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                            ?>
                        </div>
                        <?php
                    }
                    ?>

                    <div class="blaze-demo-importer-demo-box-wrap wp-clearfix">
                        <?php
                        // Loop through Demos
                        foreach ($this->demofile as $demo_slug => $demo_pack) {
                            $tags = $class = '';
                            if (isset($demo_pack['tags'])) {
                                $tags = implode(' ', array_keys($demo_pack['tags']));
                            }

                            $classes = $tags;

                            $type = isset($demo_pack['type']) ? $demo_pack['type'] : 'free';
                            ?>
                            <div id="<?php echo esc_attr($demo_slug); ?>" class="blaze-demo-importer-demo-box <?php echo esc_attr($classes); ?>">
                                <div class="blaze-demo-importer-demo-elements">
                                    <?php if ($type == 'pro') { ?>
                                        <div class="premium_label">
                                            <?php esc_html_e( 'Pro', 'newsis' ); ?>
                                        </div>
                                    <?php } else{ ?>
                                        <div class="free_label"><?php esc_html_e( 'Free', 'newsis' ); ?></div>
                                    <?php } ?>

                                    <img src="<?php echo esc_url($demo_pack['image']); ?> ">

                                    <div class="blaze-demo-importer-demo-actions">
                                        <h4><?php echo esc_html($demo_pack['name']); ?></h4>
                                        <div class="blaze-demo-importer-demo-buttons">
                                            <a href="<?php echo esc_url($demo_pack['preview_url']); ?>" target="_blank" class="button">
                                                <?php echo esc_html__('Preview', 'newsis'); ?>
                                            </a>
                                            <?php
                                                if ( $type == 'pro' && ! strpos( get_option('stylesheet'), 'pro' ) ) {
                                                    $buy_url = isset($demo_pack['buy_url']) ? $demo_pack['buy_url'] : '#';
                                                ?>
                                                    <a target="_blank" href="<?php echo esc_url($buy_url) ?>" class="button button-primary">
                                                        <?php echo esc_html__('Buy Now', 'newsis') ?>
                                                    </a>
                                            <?php } else {
                                                        if( $this->importer_status === 'active' ) {
                                                ?>
                                                            <a href="#blaze-demo-importer-modal-<?php echo esc_attr($demo_slug) ?>" class="blaze-demo-importer-modal-button button button-primary">
                                                                <?php echo esc_html__('Install', 'newsis') ?>
                                                            </a>
                                            <?php 
                                                        }
                                                    }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                        if( isset( $demo_pack['pagebuilder'] ) ) {
                                            foreach( $demo_pack['pagebuilder'] as $pagebuilder ) {
                                                echo '<h4 class="pagebuilder-label">' .esc_html( $pagebuilder ). '</h4>';
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                        <?php }
                        ?>
                    </div>
            <?php } else {
                    ?>
                    <div class="blaze-demo-importer-demo-wrap">
                        <?php esc_html_e("It looks like the config file for the demos is missing or conatins errors!. Demo install can't go futher!", 'newsis'); ?>  
                    </div>
                <?php }
                ?>

                <?php
                /* Demo Modals */
                if (is_array($this->demofile) && !is_null($this->demofile)) {
                    foreach ($this->demofile as $demo_slug => $demo_pack) {
                        ?>
                        <div id="blaze-demo-importer-modal-<?php echo esc_attr($demo_slug) ?>" class="blaze-demo-importer-modal" style="display: none;">

                            <div class="blaze-demo-importer-modal-header">
                                <h2><?php printf(esc_html('Import %s Demo', 'newsis'), esc_html($demo_pack['name'])); ?></h2>
                                <div class="blaze-demo-importer-modal-back"><span class="dashicons dashicons-no-alt"></span></div>
                            </div>

                            <div class="blaze-demo-importer-modal-wrap">
                                <p><?php echo sprintf(esc_html__('We recommend you backup your website content before attempting to import the demo so that you can recover your website if something goes wrong. You can use %s plugin for it.', 'newsis'), '<a href="https://wordpress.org/plugins/all-in-one-wp-migration/" target="_blank">' . esc_html__('All in one migration', 'newsis') . '</a>'); ?></p>

                                <p><?php echo esc_html__('This process will install all the required plugins, import contents and setup customizer and theme options.', 'newsis'); ?></p>

                                <div class="blaze-demo-importer-modal-recommended-plugins">
                                    <h4><?php esc_html_e('Required Plugins', 'newsis'); ?></h4>
                                    <p><?php esc_html_e('For your website to look exactly like the demo,the import process will install and activate the following plugin if they are not installed or activated.', 'newsis'); ?></p>
                                    <?php
                                    $plugins = isset( $demo_pack['plugins'] ) ? $demo_pack['plugins'] : '';

                                    if (is_array( $plugins ) ) {
                                        ?>
                                        <ul class="blaze-demo-importer-plugin-status">
                                            <?php
                                                foreach ( $plugins as $plugin ) {
                                                    $name = isset( $plugin['name'] ) ? $plugin['name'] : '';
                                                    $status = $this->plugin_active_status($plugin['file_path']);
                                                    if ($status == 'active') {
                                                        $plugin_class = '<span class="dashicons dashicons-yes-alt"></span>';
                                                    } else if ($status == 'inactive') {
                                                        $plugin_class = '<span class="dashicons dashicons-warning"></span>';
                                                    } else {
                                                        $plugin_class = '<span class="dashicons dashicons-dismiss"></span>';
                                                    }
                                                ?>
                                                    <li class="blaze-demo-importer-<?php echo esc_attr($status); ?>">
                                                        <?php echo $plugin_class . ' ' . esc_html($name) . ' - <i>' . $this->get_plugin_status($status) . '</i>'; ?>
                                                    </li>
                                                <?php }
                                            ?>
                                        </ul>
                                        <?php
                                    } else { ?>
                                        <ul>
                                            <li><?php esc_html_e('No Required Plugins Found.', 'newsis'); ?></li>
                                        </ul>
                                <?php
                                    }
                                ?>
                                </div>
                                <div class="blaze-demo-importer-reset-checkbox">
                                    <h4><?php esc_html_e('Reset Website', 'newsis') ?></h4>
                                    <p><?php esc_html_e('Reseting the website will delete all your post, pages, custom post types, categories, taxonomies, images and all other customizer and theme option settings.', 'newsis') ?></p>
                                    <p><?php esc_html_e('It is always recommended to reset the database for a complete demo import.', 'newsis') ?></p>
                                    <label class="blaze-demo-importer-reset-website-checkbox">
                                        <input id="checkbox-reset-<?php echo esc_attr($demo_slug); ?>" type="checkbox" value='1' checked="checked"/>
                                        <?php echo esc_html('Reset Website - Check this box only if you are sure to reset the website.', 'newsis'); ?>
                                    </label>
                                </div>
                                <a href="javascript:void(0)" data-demo-slug="<?php echo esc_attr($demo_slug) ?>" class="button button-primary blaze-demo-importer-import-demo"><?php esc_html_e('Import Demo', 'newsis'); ?></a>
                                <a href="javascript:void(0)" class="button blaze-demo-importer-modal-cancel"><?php esc_html_e('Cancel', 'newsis'); ?></a>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
                <div id="blaze-demo-importer-import-progress" style="display: none">
                    <h2 class="blaze-demo-importer-import-progress-header"><?php echo esc_html__('Demo Import Progress', 'newsis'); ?></h2>
                    <div class="blaze-demo-importer-import-progress-wrap">
                        <div class="blaze-demo-importer-import-progress-message"><div class="message-item"></div></div>
                        <span class="progress-bar-health">0<span>%</span></span>
                        <div class="blaze-demo-importer-import-loader">
                            <div class="loaderBar"></div>
                        </div>
                        <div class="blaze-demo-importer-import-progress-bar">
                            <div class="loaderBar"></div>
                        </div>
                        <span class="progress-bar-note"><?php esc_html_e( 'Demo import success', 'newsis' ); ?></span>
                    </div>
                </div>
            </div>
            <?php
        }

        /** 
         * Check if Plugin is active or not
         */
        function plugin_active_status($file_path) {
            $status = 'install';
            $plugin_path = WP_PLUGIN_DIR . '/' . esc_attr($file_path);

            if (file_exists($plugin_path)) {
                $status = is_plugin_active($file_path) ? 'active' : 'inactive';
            }

            return $status;
        }

        public function get_plugin_status($status) {
            switch ($status) {
                case 'install':
                    $plugin_status = esc_html__('Not Installed', 'newsis');
                    break;

                case 'active':
                    $plugin_status = esc_html__('Installed and Active', 'newsis');
                    break;

                case 'inactive':
                    $plugin_status = esc_html__('Installed but Not Active', 'newsis');
                    break;
            }
            return $plugin_status;
        }

        /**
         * Activate or install importer plugin 
         * 
         */
        function newsis_importer_plugin_action() {
            check_ajax_referer( 'newsis-theme-info-nonce', '_wpnonce' );
            if ( ! current_user_can("install_plugins") ) wp_die( $this->restriction_message );

            $_plugin_action = isset( $_REQUEST['plugin_action'] ) ? sanitize_text_field( $_REQUEST['plugin_action'] ) : '';
            $file_path = 'blaze-demo-importer/blaze-demo-importer.php';
            if( $_plugin_action === 'activate' ) {
                if( $file_path ) {
                    activate_plugin( $file_path, '', false, true );
                }
                $this->ajax_response['status'] = true;
                $this->ajax_response['message'] = esc_html__( 'Demo importer plugin activated', 'newsis' );
                $this->send_ajax_response();
            } else if( $_plugin_action === 'install' ) {
                $download_link = esc_url( 'https://downloads.wordpress.org/plugin/blaze-demo-importer.zip' );
                // Include required libs for installation
                require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
                require_once ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php';
                require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
                $skin = new WP_Ajax_Upgrader_Skin();
                $upgrader = new Plugin_Upgrader($skin);
                $upgrader->install( $download_link );
                activate_plugin( $file_path, '', false, true );
                $this->ajax_response['status'] = true;
                $this->ajax_response['message'] = esc_html__( 'Demo importer plugin installed and activated', 'newsis' );
                $this->send_ajax_response();
            }
            $this->ajax_response['status'] = false;
            $this->ajax_response['message'] = esc_html__( 'Error while trying to install or active the plugin.', 'newsis' );
            $this->send_ajax_response();
        }

        /**
         * Set transient required for theme
         * 
         */
        function newsis_set_ajax_transient() {
            check_ajax_referer( 'newsis-theme-info-nonce', '_wpnonce' );
            if ( ! current_user_can( 'manage_options' ) ) { // Check if the current user can manage options (an administrator)
                wp_die( $this->restriction_message );
            }
            set_transient( 'newsis_show_review_notice', 'hide' );
            $this->ajax_response['status'] = true;
            $this->ajax_response['message'] = esc_html__( 'Review notice hidden.', 'newsis' );
            $this->send_ajax_response();
        }

        /**
         * Set transient required for theme
         * 
         */
        function newsis_dismiss_welcome_notice() {
            check_ajax_referer( 'newsis-theme-info-nonce', '_wpnonce' );
            if ( ! current_user_can( 'manage_options' ) ) { // Check if the current user can manage options (an administrator)
                wp_die( $this->restriction_message );
            }
            update_option( 'newsis_welcome_notice_dismiss', true );
            $this->ajax_response['status'] = true;
            $this->ajax_response['message'] = esc_html__( 'Welcom notice hidden', 'newsis' );
            $this->send_ajax_response();
        }

        public function send_ajax_response() {
            $json = wp_json_encode( $this->ajax_response );
            echo $json;
            die();
        }

        /**
         * Add welcome notice to the admin dashboard
         * 
         * @since 1.1.0
         */
        function add_welcome_admin_notice() {
            if ( ! current_user_can( 'manage_options' ) ) { // Check if the current user can manage options (an administrator)
                return;
            }

            if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
            $newsis_welcome_notice_dismiss = get_option( 'newsis_welcome_notice_dismiss' );
            if( $newsis_welcome_notice_dismiss ) {
                return;
            }

            if( isset( $_GET['page'] ) ) {
                if( $_GET['page'] == 'newsis-info' ) {
                    return;
                }
            }
        ?>
            <div class="newsis-welcome-notice notice notice-info is-dismissible">
                <div class="notice-content-wrap">
                    <h2 class="notice-heading"><?php esc_html_e( 'Thank you for using Newsis theme', 'newsis' ); ?></h2>
                    <div class="notice-content">
                        <div class="notice-content-inner"><?php esc_html_e( 'Get started with multipurpose news theme and give your site a new look. We recommend you to please go through the documentation to get started with theme and setup homepage quicky.', 'newsis' ); ?></div>
                        <div class="notice-actions">
                            <a class="button button-primary load-customize hide-if-no-customize" href="<?php echo esc_url( admin_url( '/themes.php?page=newsis-info' ) ); ?>"><?php esc_html_e( 'Install Demos', 'newsis' ); ?></a>
                            <a class="button button-primary load-customize hide-if-no-customize" target="_blank" href="<?php echo esc_url( admin_url( '/customize.php' ) ); ?>"><?php esc_html_e( 'Customize Site', 'newsis' ); ?></a>
                            <a class="button button-primary load-customize hide-if-no-customize" target="_blank" href="<?php echo esc_url( '//doc.blazethemes.com/newsis/ '); ?>"><?php esc_html_e( 'Documentation', 'newsis' ); ?></a>
                        </div>
                    </div>
                </div><!-- .notice-content-wrap -->
                <img class="screenshot-wrap" src="<?php echo esc_url( get_template_directory_uri() . '/screenshot.png' ); ?>" height="200" height="200"/>
                <div class="notice-dismiss-button"><?php esc_html_e( 'Dismiss this notice', 'newsis' ); ?></div>
            </div>
            <?php
        }
        
        /**
         * Add review notice to admin section
         * 
         * @since 1.3.0
         */
        function add_review_admin_notice() {
            if ( ! current_user_can( 'manage_options' ) ) { // Check if the current user can manage options (an administrator)
                return;
            }

            if( isset( $_GET['page'] ) ) {
                if( $_GET['page'] == 'newsis-info' ) {
                    return;
                }
            }
            if( get_transient( 'newsis_show_review_notice' ) === 'hide' ) {
                return;
            }
            ?>
            <div class="newsis-admin-notice notice notice-success is-dismissible">
                <h2><?php esc_html_e( 'Newsis Review Notice', 'newsis' ); ?></h2>
                <div class="notice-content-wrap">
                    <div class="notice-content">
                        <span class="notice-highlight"><?php esc_html_e( "How was your experience using Newsis theme ? ", "newsis" ); ?></span>
                        <p><?php esc_html_e( "We are constantly striving to improve and we'd like to hear from you. Hope you spend your few seconds reviewing our product. We appreciate your time and feedback! Thank you!!", "newsis" ); ?> <span class="dashicons dashicons-smiley"></span></p>
                        <div class="notice-actions">
                            <a class="button review" data-redirect="<?php echo esc_url( '//wordpress.org/support/theme/newsis/reviews/?filter=5' ); ?>" target="_blank"><span class="dashicons dashicons-thumbs-up"></span><?php esc_html_e( 'Leave A Review', 'newsis' ); ?></a>
                            <a class="button dismiss"><span class="dashicons dashicons-dismiss"></span><?php esc_html_e( 'Dismiss Notice', 'newsis' ); ?></a>
                        </div>
                    </div>
                </div><!-- .notice-content-wrap -->
            </div>
            <?php
        }

        /**
         * Add a new dashboard widget.
         * 
         * @since 1.2.9
         */
        public function add_dashboard_widgets() {
            wp_add_dashboard_widget( 'newsis_blazethemes_author_overview', 
                esc_html__( 'BlazeThemes Author Overview', 'newsis' ),
                array( $this, 'dashboard_widget_render' ),
                null,
                null,
                'side'
            );
        }

        // Output the contents of the dashboard widget
        function dashboard_widget_render( $post, $callback_args ) {
            $blaze_blogs = wp_remote_get( "https://blazethemes.com/wp-json/wp/v2/posts?per_page=5&order=desc&tags=88");
            if ( ( !is_wp_error($blaze_blogs)) && (200 === wp_remote_retrieve_response_code( $blaze_blogs ) ) ) {
                $responseBody = json_decode($blaze_blogs['body']);
                if( json_last_error() === JSON_ERROR_NONE ) {
                    if( is_array($responseBody) ) :
                        echo '<ul class="newsis-blog-list">';
                            foreach( $responseBody as $item ) :
                        ?>
                                <li class="blog-item">
                                    <a href="<?php echo esc_url( $item->link ); ?>" target="_blank"><?php echo esc_html( $item->title->rendered ); ?></a>
                                    <div class="blog-excerpt"><?php echo wp_kses_post( $item->excerpt->rendered ); ?></div>
                                </li>
                        <?php
                            endforeach;
                        echo '</ul>';
                        ?>
                            <p class="community-events-footer">
                                <a href="<?php echo esc_url( 'https://blazethemes.com/blog/' ); ?>" target="_blank"><?php echo esc_html__( 'Blogs', 'newsis' ); ?><span class="screen-reader-text"><?php echo esc_html__( '(opens in a new tab)', 'newsis' ); ?></span><span aria-hidden="true" class="dashicons dashicons-external"></span></a> | 
                                <a href="<?php echo esc_url( 'https://blazethemes.com/support/' ); ?>" target="_blank"><?php echo esc_html__( 'Help', 'newsis' ); ?><span class="screen-reader-text"><?php echo esc_html__( '(opens in a new tab)', 'newsis' ); ?></span><span aria-hidden="true" class="dashicons dashicons-external"></span></a> | 
                                <a href="<?php echo esc_url( 'https://blazethemes.com/theme/newsis-pro/' ); ?>" target="_blank"><?php echo esc_html__( 'Upgrade', 'newsis' ); ?><span class="screen-reader-text"><?php echo esc_html__( '(opens in a new tab)', 'newsis' ); ?></span><span aria-hidden="true" class="dashicons dashicons-external"></span></a>
                            </p>
                        <?php
                    endif;
                }
            }
        }
    }
    Newsis_Theme_Info::instance();
endif;

class Newsis_Notice {
    public $name;
    public $type;
    public $dismiss_url;
    public $temporary_dismiss_url;
    public $pricing_url;
    public $current_user_id;

    /**
     * The constructor.
     *
     * @param string $name Notice Name.
     * @param string $type Notice type.
     * @param string $dismiss_url Notice permanent dismiss URL.
     * @param string $temporary_dismiss_url Notice temporary dismiss URL.
     *
     * @since 1.4.7
     *
     */
    public function __construct($name, $type, $dismiss_url, $temporary_dismiss_url)
    {
        $this->name = $name;
        $this->type = $type;
        $this->dismiss_url = $dismiss_url;
        $this->temporary_dismiss_url = $temporary_dismiss_url;
        $this->pricing_url = 'https://blazethemes.com/theme/newsis-pro/';
        $this->current_user_id = get_current_user_id();

        // Notice markup.
        add_action('admin_notices', array($this, 'notice'));
        if ( $this->is_dismiss_notice() ) {
            add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );
        }
        $this->dismiss_notice();
        $this->dismiss_notice_temporary();
    }

    public function notice() {
        if ( ! $this->is_dismiss_notice() ) {
            $this->notice_markup();
        }
    }
    // add notice scripts
    public function add_scripts() {
        wp_enqueue_style( 'newsis-upgrade-notice', get_template_directory_uri() . '/inc/admin/assets/upgrade-notice.css', array(), '1.0.3', 'all' );
    }

    private function is_dismiss_notice(){
        return apply_filters('newsis_' . $this->name . '_notice_dismiss', true);
    }

    public function notice_markup(){
        echo '';
    }

    /**
     * Hide a notice if the GET variable is set.
     */
    public function dismiss_notice() {
        if (isset($_GET['newsis_notice_dismiss']) && isset($_GET['_newsis_upgrade_notice_dismiss_nonce'])) { // WPCS: input var ok.
            if (!wp_verify_nonce(wp_unslash($_GET['_newsis_upgrade_notice_dismiss_nonce']), 'newsis_upgrade_notice_dismiss_nonce')) { // phpcs:ignore WordPress.VIP.ValidatedSanitizedInput.InputNotSanitized
                wp_die(__('Action failed. Please refresh the page and retry.', 'newsis')); // WPCS: xss ok.
            }

            if (!current_user_can('publish_posts')) {
                wp_die(__('Cheatin&#8217; huh?', 'newsis')); // WPCS: xss ok.
            }

            $dismiss_notice = sanitize_text_field(wp_unslash($_GET['newsis_notice_dismiss']));

            // Hide.
            if ($dismiss_notice === $_GET['newsis_notice_dismiss']) {
                add_user_meta(get_current_user_id(), 'newsis_' . $dismiss_notice . '_notice_dismiss', 'yes', true);
            }
        }
    }

    public function dismiss_notice_temporary() {
        if (isset($_GET['newsis_notice_dismiss_temporary']) && isset($_GET['_newsis_upgrade_notice_dismiss_temporary_nonce'])) { // WPCS: input var ok.
            if (!wp_verify_nonce(wp_unslash($_GET['_newsis_upgrade_notice_dismiss_temporary_nonce']), 'newsis_upgrade_notice_dismiss_temporary_nonce')) { // phpcs:ignore WordPress.VIP.ValidatedSanitizedInput.InputNotSanitized
                wp_die(__('Action failed. Please refresh the page and retry.', 'newsis')); // WPCS: xss ok.
            }

            if (!current_user_can('publish_posts')) {
                wp_die(__('Cheatin&#8217; huh?', 'newsis')); // WPCS: xss ok.
            }

            $dismiss_notice = sanitize_text_field(wp_unslash($_GET['newsis_notice_dismiss_temporary']));

            // Hide.
            if ($dismiss_notice === $_GET['newsis_notice_dismiss_temporary']) {
                add_user_meta(get_current_user_id(), 'newsis_' . $dismiss_notice . '_notice_dismiss_temporary', 'yes', true);
            }
        }
    }
}

class Newsis_Upgrade_Notice extends Newsis_Notice {
    public function __construct() {
        if ( ! current_user_can( 'publish_posts' ) ) {
            return;
        }

        $dismiss_url = wp_nonce_url(
            add_query_arg( 'newsis_notice_dismiss', 'upgrade', admin_url() ),
            'newsis_upgrade_notice_dismiss_nonce',
            '_newsis_upgrade_notice_dismiss_nonce'
        );

        $temporary_dismiss_url = wp_nonce_url(
            add_query_arg( 'newsis_notice_dismiss_temporary', 'upgrade', admin_url() ),
            'newsis_upgrade_notice_dismiss_temporary_nonce',
            '_newsis_upgrade_notice_dismiss_temporary_nonce'
        );

        parent::__construct( 'upgrade', 'info', $dismiss_url, $temporary_dismiss_url );
        $this->set_notice_time();
        $this->set_temporary_dismiss_notice_time();
        $this->set_dismiss_notice();
    }

    private function set_notice_time() {
        if ( ! get_option( 'newsis_upgrade_notice_start_time' ) ) {
            update_option( 'newsis_upgrade_notice_start_time', time() );
        }
    }

    private function set_temporary_dismiss_notice_time() {
        if ( isset( $_GET['newsis_notice_dismiss_temporary'] ) && 'upgrade' === $_GET['newsis_notice_dismiss_temporary'] ) {
            update_user_meta( $this->current_user_id, 'newsis_upgrade_notice_dismiss_temporary_start_time', time() );
        }
    }

    public function set_dismiss_notice() {
        /**
         * Do not show notice if:
         *
         * 1. It has not been 5 days since the theme is activated.
         * 2. If the user has ignored the message partially for 2 days.
         * 3. Dismiss always if clicked on 'Dismiss' button.
         */
        if ( get_option( 'newsis_upgrade_notice_start_time' ) > strtotime( '-5 day' )
            || get_user_meta( get_current_user_id(), 'newsis_upgrade_notice_dismiss', true )
            || get_user_meta( get_current_user_id(), 'newsis_upgrade_notice_dismiss_temporary_start_time', true ) > strtotime( '-2 day' )
        ) {
            add_filter( 'newsis_upgrade_notice_dismiss', '__return_true' );
        } else {
            add_filter( 'newsis_upgrade_notice_dismiss', '__return_false' );
        }
    }

    public function notice_markup() {
        ?>
        <div class="notice notice-success newsis-upgrade-notice" >
            <a class="newsis-upgrade-notice-dismiss notice-dismiss" href="<?php echo esc_url( $this->dismiss_url ); ?>"></a>
            <p class="notice-text">
                <?php
                    $current_user = wp_get_current_user();
                    printf(
                    /* Translators: %1$s current user display name., %2$s this theme name., %3$s discount coupon code., %4$s discount percentage. */
                    esc_html__(
                            'Hello %1$s! We hope you\'re enjoying the %2$s theme now that you\'ve been using it for a while. You may always upgrade to pro to gain access to more premium features. You can also get a %4$s discount by using the promo code %3$s during checkout. Enjoy! ',
                            'newsis'
                        ),
                        '<strong>' . esc_html( $current_user->display_name ) . '</strong>',
                        'Newsis',
                        '<code class="coupon-code">NEWSIS10</code>',
                        '<strong>10%</strong>'
                    );
                ?>
            </p>
            <div class="links">
                <a href="<?php echo esc_url( $this->pricing_url ); ?>" class="button button-primary" target="_blank">
                    <span><?php esc_html_e( 'Upgrade to pro', 'newsis' ); ?></span>
                </a>
                <a href="<?php echo esc_url( $this->temporary_dismiss_url ); ?>" class="button button-secondary">
                    <span><?php esc_html_e( 'Maybe later', 'newsis' ); ?></span>
                </a>
                <a href="https://blazethemes.com/support/" class="button button-secondary" target="_blank">
                    <span><?php esc_html_e( 'Got pre sales queries?', 'newsis' ); ?></span>
                </a>
            </div>
        </div>
        <?php
    }
}
new Newsis_Upgrade_Notice();