<?php
/**
 * Newsis Theme Customizer
 *
 * @package Newsis
 */
use Newsis\CustomizerDefault as NI;
/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function newsis_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
    $wp_customize->get_section( 'background_image' )->title = esc_html__( 'Background', 'newsis' );
    $wp_customize->get_section( 'colors' )->title = esc_html__( 'Presets Colors', 'newsis' );
    $wp_customize->get_section( 'colors' )->panel = 'newsis_colors_panel';
    $wp_customize->remove_control( 'background_color' );
    
    /**
     * Colors panel
     * 
     * @package Newsis
     * @since 1.0.0
     */
    $wp_customize->add_panel( 'newsis_colors_panel', array(
        'title' => esc_html__( 'Colors', 'newsis' ),
        'priority'  => 7
    ));

	require get_template_directory() . '/inc/customizer/custom-controls/section-heading/section-heading.php'; // section heading control
	require get_template_directory() . '/inc/customizer/custom-controls/repeater/repeater.php'; // repeater control
    require get_template_directory() . '/inc/customizer/custom-controls/radio-image/radio-image.php'; // radio image control
	require get_template_directory() . '/inc/customizer/custom-controls/redirect-control/redirect-control.php'; // redirect control
    require get_template_directory() . '/inc/customizer/custom-controls/section-heading-toggle/section-heading-toggle.php'; // section-heading-toggle
    require get_template_directory() . '/inc/customizer/custom-controls/icon-picker/icon-picker.php'; // icon picker
    require get_template_directory() . '/inc/customizer/base.php'; // base class

    // color group control
    class Newsis_WP_Color_Group_Control extends Newsis_WP_Base_Control {
        // control type
        public $type = 'color-group';
    }

    // color image group control
    class Newsis_WP_Color_Image_Group_Control extends Newsis_WP_Base_Control {
        // control type
        public $type = 'color-image-group';
    }

    // color picker control
    class Newsis_WP_Color_Picker_Control extends Newsis_WP_Base_Control {
        // control type
        public $type = 'color-picker';
    }

    // preset color picker control
    class Newsis_WP_Preset_Color_Picker_Control extends Newsis_WP_Base_Control {
        // control type
        public $type = 'preset-color-picker';
        public $variable = '--newsis-global-preset-theme-color';

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            if( $this->variable ) {
                $this->json['variable'] = $this->variable;
            }
        }
    }
    
    // preset gradient picker control
    class Newsis_WP_Preset_Gradient_Picker_Control extends Newsis_WP_Base_Control {
        // control type
        public $type = 'preset-gradient-picker';
        public $variable = '--newsis-global-preset-gradient-color-1';

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            if( $this->variable ) {
                $this->json['variable'] = $this->variable;
            }
        }
    }

    // categories multiselect control
    class Newsis_WP_Categories_Multiselect_Control extends Newsis_WP_Base_Control {
        // control type
        public $type = 'categories-multiselect';

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            $this->json['choices'] = $this->choices;
        }
    }

    // posts multiselect control
    class Newsis_WP_Posts_Multiselect_Control extends Newsis_WP_Base_Control {
        // control type
        public $type = 'posts-multiselect';

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            $this->json['choices'] = $this->choices;
        }
    }

    // range control
    class Newsis_WP_Range_Control extends Newsis_WP_Base_Control {
        // control type
        public $type = 'range';

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            $this->json['input_attrs'] = $this->input_attrs;
        }
    }

    // responsive range control
    class Newsis_WP_Responsive_Range_Control extends Newsis_WP_Base_Control {
        // control type
        public $type = 'responsive-range';

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            $this->json['input_attrs'] = $this->input_attrs;
        }
    }

    // toggle control 
    class Newsis_WP_Toggle_Control extends Newsis_WP_Base_Control {
        // control type
        public $type = 'toggle-button';
        public $tab = 'general';
        
        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            if( $this->tab ) {
                $this->json['tab'] = $this->tab;
            }
        }
    }
    
    // checkbox control 
    class Newsis_WP_Checkbox_Control extends Newsis_WP_Base_Control {
        // control type
        public $type = 'checkbox';
        public $tab = 'general';

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            if( $this->tab ) {
                $this->json['tab'] = $this->tab;
            }
        }
    }

    // simple toggle control 
    class Newsis_WP_Simple_Toggle_Control extends Newsis_WP_Base_Control {
        // control type
        public $type = 'simple-toggle';
    }

    // block repeater control 
    class Newsis_WP_Block_Repeater_Control extends Newsis_WP_Base_Control {
        // control type
        public $type = 'block-repeater';
        public $tab = 'general';
        public $fields_to_exclude = [];

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            if( $this->tab ) {
                $this->json['tab'] = $this->tab;
            }
            $this->json['fields_to_exclude'] = $this->fields_to_exclude;
        }
    }

    // item sortable control 
    class Newsis_WP_Item_Sortable_Control extends Newsis_WP_Base_Control {
        // control type
        public $type = 'item-sortable';
        public $fields;

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            $this->json['fields'] = $this->fields;
        }
    }

    // typography control 
    class Newsis_WP_Typography_Control extends Newsis_WP_Base_Control {
        // control type
        public $type = 'typography';
        public $fields;

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            $this->json['fields'] = $this->fields;
        }
    }

    // box shadow control 
    class Newsis_WP_Box_Shadow_Control extends Newsis_WP_Base_Control {
        // control type
        public $type = 'box-shadow';
    }

    // color group picker control - renders color and hover color control
    class Newsis_WP_Color_Group_Picker_Control extends Newsis_WP_Base_Control {
        // control type
        public $type = 'color-group-picker';
    }

    // section tab control - renders section tab control
    class Newsis_WP_Section_Tab_Control extends Newsis_WP_Base_Control {
        // control type
        public $type = 'section-tab';

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            $this->json['choices'] = $this->choices;
        }
    }

    // radio tab control
    class Newsis_WP_Radio_Tab_Control extends Newsis_WP_Base_Control {
        // control type
        public $type = 'radio-tab';

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            $this->json['choices'] = $this->choices;
        }
    }

    // radio tab control
    class Newsis_WP_Responsive_Multiselect_Tab_Control extends Newsis_WP_Base_Control {
        // control type
        public $type = 'responsive-multiselect-tab';

        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            $this->json['choices'] = $this->choices;
        }
    }

    // tab group control
    class Newsis_WP_Default_Color_Control extends WP_Customize_Color_Control {
        /**
         * Additional variabled
         * 
         */
        public $tab = 'general';
        
        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            if( $this->tab && $this->type != 'section-tab' ) {
                $this->json['tab'] = $this->tab;
            }
        }
    }

    // info box control
    class Newsis_WP_Info_Box_Control extends Newsis_WP_Base_Control {
        // control type
        public $type = 'info-box';
        
        /**
         * Add custom JSON parameters to use in the JS template.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function to_json() {
            parent::to_json();
            $this->json['choices'] = $this->choices;
        }
    }

    // register control type
    $wp_customize->register_control_type( 'Newsis_WP_Radio_Image_Control' );

    // website layout heading
    $wp_customize->add_setting( 'theme_color_header', array(
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control( 
        new Newsis_WP_Section_Heading_Control( $wp_customize, 'theme_color_header', array(
            'label'	      => esc_html__( 'Theme Color', 'newsis' ),
            'section'     => 'colors',
            'settings'    => 'theme_color_header'
        ))
    );

    // active menu color
    $wp_customize->add_setting( 'theme_color', array(
        'default'   => NI\newsis_get_customizer_default( 'theme_color' ),
        'sanitize_callback' => 'newsis_sanitize_color_picker_control',
        'transport' => 'postMessage'
    ));
    $wp_customize->add_control( 
        new Newsis_WP_Preset_Color_Picker_Control( $wp_customize, 'theme_color', array(
            'label'	      => esc_html__( 'Theme Color', 'newsis' ),
            'section'     => 'colors',
            'settings'    => 'theme_color'
        ))
    );
    
    // site background color
    $wp_customize->add_setting( 'site_background_color', array(
        'default'   => NI\newsis_get_customizer_default( 'site_background_color' ),
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control( 
        new Newsis_WP_Color_Group_Control( $wp_customize, 'site_background_color', array(
            'label'	      => esc_html__( 'Background Color', 'newsis' ),
            'section'     => 'background_image',
            'settings'    => 'site_background_color',
            'priority'  => 1
        ))
    );
}
add_action( 'customize_register', 'newsis_customize_register' );

add_filter( NEWSIS_PREFIX . 'unique_identifier', function($identifier) {
    $n_delimeter = '-';
    $n_prefix = 'customize';
    $n_sufix = 'control';
    $identifier_id = [$n_prefix,$identifier,$n_sufix];
    return implode($n_delimeter,$identifier_id);
});

if( ! function_exists( 'newsis_customizer_orderby_options_array' ) ) :
    /**
     * Array of order by control choices
     * 
     * @since 1.0.0
     */
    function newsis_customizer_orderby_options_array() {
        return apply_filters( 'newsis_customizer_orderby_options_array', [
			'date-desc' =>  esc_html__( 'Newest - Oldest', 'newsis' ),
			'date-asc' =>  esc_html__( 'Oldest - Newest', 'newsis' ),
			'title-asc' =>  esc_html__( 'A - Z', 'newsis' ),
			'title-desc' =>  esc_html__( 'Z - A', 'newsis' ),
			'rand-desc' =>  esc_html__( 'Random', 'newsis' ),
			'modified-desc' =>  esc_html__( 'Newsest - Oldest( modified )', 'newsis' ),
			'modified-asc' =>  esc_html__( 'Oldest - Newest( modified )', 'newsis' ),
			'comment_count-desc' =>  esc_html__( 'Posts with most comments first', 'newsis' ),
			'comment_count-asc' =>  esc_html__( 'Posts with least comments first', 'newsis' )
		]);
    }
endif;

if( ! function_exists( 'newsis_get_all_fontawesome_icons' ) ) :
	/**
	 * All fontawesome icons array - 6.4.2
	 * 
	 * @since 1.0.0
	 * 
	 */
	function newsis_get_all_fontawesome_icons( $type = '' ) {
        $fontawesome_icons = [
           "fa-brands fa-adn","fa-brands fa-adversal","fa-brands fa-affiliatetheme","fa-brands fa-airbnb","fa-brands fa-algolia","fa-brands fa-alipay","fa-brands fa-amazon","fa-brands fa-amazon-pay","fa-brands fa-amilia","fa-brands fa-android","fa-brands fa-angellist","fa-brands fa-angrycreative","fa-brands fa-angular","fa-brands fa-apper","fa-brands fa-apple","fa-brands fa-apple-pay","fa-brands fa-app-store","fa-brands fa-app-store-ios","fa-brands fa-artstation","fa-brands fa-asymmetrik","fa-brands fa-atlassian","fa-brands fa-weibo","fa-brands fa-weixin","fa-brands fa-whatsapp","fa-brands fa-whmcs","fa-brands fa-wikipedia-w","fa-brands fa-windows","fa-brands fa-wirsindhandwerk","fa-brands fa-wix","fa-brands fa-wizards-of-the-coast","fa-brands fa-wodu","fa-brands fa-wolf-pack-battalion","fa-brands fa-wordpress","fa-brands fa-wordpress-simple","fa-brands fa-wpbeginner","fa-brands fa-wpexplorer","fa-brands fa-wpforms","fa-brands fa-wpressr","fa-brands fa-xbox","fa-brands fa-xing","fa-brands fa-x-twitter","fa-brands fa-yahoo","fa-brands fa-yammer","fa-brands fa-yandex","fa-brands fa-yandex-international","fa-brands fa-yarn","fa-brands fa-y-combinator","fa-brands fa-yelp","fa-brands fa-yoast","fa-brands fa-youtube","fa-brands fa-zhihu","fa-brands fa-themeisle","fa-brands fa-think-peaks","fa-brands fa-threads","fa-brands fa-tiktok","fa-brands fa-trade-federation","fa-brands fa-trello","fa-brands fa-tumblr","fa-brands fa-twitch","fa-brands fa-twitter","fa-brands fa-typo3","fa-brands fa-uber","fa-brands fa-ubuntu","fa-brands fa-uikit","fa-brands fa-umbraco","fa-brands fa-uncharted","fa-brands fa-uniregistry","fa-brands fa-unity","fa-brands fa-unsplash","fa-brands fa-untappd","fa-brands fa-ups","fa-brands fa-upwork","fa-brands fa-usb","fa-brands fa-usps","fa-brands fa-ussunnah","fa-brands fa-vaadin","fa-brands fa-viacoin","fa-brands fa-viadeo","fa-brands fa-viber","fa-brands fa-vimeo","fa-brands fa-vimeo-v","fa-brands fa-vine","fa-brands fa-vk","fa-brands fa-vnv","fa-brands fa-vuejs","fa-brands fa-watchman-monitoring","fa-brands fa-waze","fa-brands fa-webflow","fa-brands fa-weebly","fa-brands fa-square-odnoklassniki","fa-brands fa-square-pied-piper","fa-brands fa-square-pinterest","fa-brands fa-square-reddit","fa-brands fa-square-snapchat","fa-brands fa-squarespace","fa-brands fa-square-steam","fa-brands fa-square-threads","fa-brands fa-square-tumblr","fa-brands fa-square-twitter","fa-brands fa-square-viadeo","fa-brands fa-square-vimeo","fa-brands fa-square-whatsapp","fa-brands fa-square-xing","fa-brands fa-square-x-twitter","fa-brands fa-square-youtube","fa-brands fa-stack-exchange","fa-brands fa-stack-overflow","fa-brands fa-stackpath","fa-brands fa-staylinked","fa-brands fa-steam","fa-brands fa-steam-symbol","fa-brands fa-sticker-mule","fa-brands fa-strava","fa-brands fa-stripe","fa-brands fa-stripe-s","fa-brands fa-stubber","fa-brands fa-studiovinari","fa-brands fa-stumbleupon","fa-brands fa-stumbleupon-circle","fa-brands fa-superpowers","fa-brands fa-supple","fa-brands fa-suse","fa-brands fa-swift","fa-brands fa-symfony","fa-brands fa-teamspeak","fa-brands fa-telegram","fa-brands fa-tencent-weibo","fa-brands fa-themeco","fa-brands fa-the-red-yeti","fa-brands fa-sellcast","fa-brands fa-sellsy","fa-brands fa-servicestack","fa-brands fa-shirtsinbulk","fa-brands fa-shoelace","fa-brands fa-shopify","fa-brands fa-shopware","fa-brands fa-signal-messenger","fa-brands fa-simplybuilt","fa-brands fa-sistrix","fa-brands fa-sith","fa-brands fa-sitrox","fa-brands fa-sketch","fa-brands fa-skyatlas","fa-brands fa-skype","fa-brands fa-slack","fa-brands fa-slideshare","fa-brands fa-snapchat","fa-brands fa-soundcloud","fa-brands fa-sourcetree","fa-brands fa-space-awesome","fa-brands fa-speakap","fa-brands fa-speaker-deck","fa-brands fa-spotify","fa-brands fa-square-behance","fa-brands fa-square-dribbble","fa-brands fa-square-facebook","fa-brands fa-square-font-awesome","fa-brands fa-square-font-awesome-stroke","fa-brands fa-square-git","fa-brands fa-square-github","fa-brands fa-square-gitlab","fa-brands fa-square-google-plus","fa-brands fa-square-hacker-news","fa-brands fa-square-instagram","fa-brands fa-square-js","fa-brands fa-square-lastfm","fa-brands fa-square-letterboxd","fa-brands fa-pied-piper","fa-brands fa-pied-piper-hat","fa-brands fa-pied-piper-pp","fa-brands fa-pinterest","fa-brands fa-pinterest-p","fa-brands fa-pix","fa-brands fa-pixiv","fa-brands fa-playstation","fa-brands fa-product-hunt","fa-brands fa-pushed","fa-brands fa-python","fa-brands fa-qq","fa-brands fa-quinscape","fa-brands fa-quora","fa-brands fa-raspberry-pi","fa-brands fa-ravelry","fa-brands fa-react","fa-brands fa-reacteurope","fa-brands fa-readme","fa-brands fa-rebel","fa-brands fa-reddit","fa-brands fa-reddit-alien","fa-brands fa-redhat","fa-brands fa-red-river","fa-brands fa-renren","fa-brands fa-replyd","fa-brands fa-researchgate","fa-brands fa-resolving","fa-brands fa-rev","fa-brands fa-rocketchat","fa-brands fa-rockrms","fa-brands fa-r-project","fa-brands fa-rust","fa-brands fa-safari","fa-brands fa-salesforce","fa-brands fa-sass","fa-brands fa-schlix","fa-brands fa-screenpal","fa-brands fa-scribd","fa-brands fa-searchengin","fa-brands fa-mix","fa-brands fa-mixcloud","fa-brands fa-mixer","fa-brands fa-mizuni","fa-brands fa-modx","fa-brands fa-monero","fa-brands fa-napster","fa-brands fa-neos","fa-brands fa-nfc-directional","fa-brands fa-nfc-symbol","fa-brands fa-nimblr","fa-brands fa-node","fa-brands fa-node-js","fa-brands fa-npm","fa-brands fa-ns8","fa-brands fa-nutritionix","fa-brands fa-octopus-deploy","fa-brands fa-odnoklassniki","fa-brands fa-odysee","fa-brands fa-old-republic","fa-brands fa-opencart","fa-brands fa-openid","fa-brands fa-opensuse","fa-brands fa-opera","fa-brands fa-optin-monster","fa-brands fa-orcid","fa-brands fa-osi","fa-brands fa-padlet","fa-brands fa-page4","fa-brands fa-pagelines","fa-brands fa-palfed","fa-brands fa-patreon","fa-brands fa-paypal","fa-brands fa-perbyte","fa-brands fa-periscope","fa-brands fa-phabricator","fa-brands fa-phoenix-framework","fa-brands fa-phoenix-squadron","fa-brands fa-php","fa-brands fa-pied-piper-alt","fa-brands fa-joget","fa-brands fa-joomla","fa-brands fa-js","fa-brands fa-jsfiddle","fa-brands fa-kaggle","fa-brands fa-keybase","fa-brands fa-keycdn","fa-brands fa-kickstarter","fa-brands fa-kickstarter-k","fa-brands fa-korvue","fa-brands fa-laravel","fa-brands fa-lastfm","fa-brands fa-leanpub","fa-brands fa-less","fa-brands fa-letterboxd","fa-brands fa-line","fa-brands fa-linkedin","fa-brands fa-linkedin-in","fa-brands fa-linode","fa-brands fa-linux","fa-brands fa-lyft","fa-brands fa-magento","fa-brands fa-mailchimp","fa-brands fa-mandalorian","fa-brands fa-markdown","fa-brands fa-mastodon","fa-brands fa-maxcdn","fa-brands fa-mdb","fa-brands fa-medapps","fa-brands fa-medium","fa-brands fa-medrt","fa-brands fa-meetup","fa-brands fa-megaport","fa-brands fa-mendeley","fa-brands fa-meta","fa-brands fa-microblog","fa-brands fa-microsoft","fa-brands fa-mintbit","fa-brands fa-google","fa-brands fa-google-plus","fa-brands fa-google-scholar","fa-brands fa-google-wallet","fa-brands fa-gratipay","fa-brands fa-grav","fa-brands fa-gripfire","fa-brands fa-grunt","fa-brands fa-guilded","fa-brands fa-gulp","fa-brands fa-hacker-news","fa-brands fa-hackerrank","fa-brands fa-hashnode","fa-brands fa-hips","fa-brands fa-hire-a-helper","fa-brands fa-hive","fa-brands fa-hooli","fa-brands fa-hornbill","fa-brands fa-hotjar","fa-brands fa-houzz","fa-brands fa-html5","fa-brands fa-hubspot","fa-brands fa-ideal","fa-brands fa-imdb","fa-brands fa-instagram","fa-brands fa-instalod","fa-brands fa-intercom","fa-brands fa-internet-explorer","fa-brands fa-invision","fa-brands fa-ioxhost","fa-brands fa-itch-io","fa-brands fa-itunes","fa-brands fa-itunes-note","fa-brands fa-java","fa-brands fa-jedi-order","fa-brands fa-jenkins","fa-brands fa-jira","fa-brands fa-fedora","fa-brands fa-figma","fa-brands fa-firefox","fa-brands fa-firefox-browser","fa-brands fa-firstdraft","fa-brands fa-first-order","fa-brands fa-first-order-alt","fa-brands fa-flickr","fa-brands fa-flipboard","fa-brands fa-fly","fa-brands fa-font-awesome","fa-brands fa-fonticons","fa-brands fa-fonticons-fi","fa-brands fa-fort-awesome","fa-brands fa-fort-awesome-alt","fa-brands fa-forumbee","fa-brands fa-foursquare","fa-brands fa-freebsd","fa-brands fa-free-code-camp","fa-brands fa-fulcrum","fa-brands fa-galactic-republic","fa-brands fa-galactic-senate","fa-brands fa-get-pocket","fa-brands fa-gg","fa-brands fa-gg-circle","fa-brands fa-git","fa-brands fa-git-alt","fa-brands fa-github","fa-brands fa-github-alt","fa-brands fa-gitkraken","fa-brands fa-gitlab","fa-brands fa-gitter","fa-brands fa-glide","fa-brands fa-glide-g","fa-brands fa-gofore","fa-brands fa-golang","fa-brands fa-goodreads","fa-brands fa-goodreads-g","fa-brands fa-google-drive","fa-brands fa-google-pay","fa-brands fa-google-play","fa-brands fa-google-plus-g","fa-brands fa-dailymotion","fa-brands fa-dashcube","fa-brands fa-debian","fa-brands fa-deezer","fa-brands fa-delicious","fa-brands fa-deploydog","fa-brands fa-deskpro","fa-brands fa-dev","fa-brands fa-deviantart","fa-brands fa-dhl","fa-brands fa-diaspora","fa-brands fa-digg","fa-brands fa-digital-ocean","fa-brands fa-discord","fa-brands fa-discourse","fa-brands fa-dochub","fa-brands fa-docker","fa-brands fa-draft2digital","fa-brands fa-dribbble","fa-brands fa-dropbox","fa-brands fa-drupal","fa-brands fa-dyalog","fa-brands fa-earlybirds","fa-brands fa-ebay","fa-brands fa-edge","fa-brands fa-edge-legacy","fa-brands fa-elementor","fa-brands fa-ello","fa-brands fa-ember","fa-brands fa-empire","fa-brands fa-envira","fa-brands fa-erlang","fa-brands fa-ethereum","fa-brands fa-etsy","fa-brands fa-evernote","fa-brands fa-expeditedssl","fa-brands fa-facebook","fa-brands fa-facebook-f","fa-brands fa-facebook-messenger","fa-brands fa-fantasy-flight-games","fa-brands fa-fedex","fa-brands fa-cc-stripe","fa-brands fa-cc-visa","fa-brands fa-centercode","fa-brands fa-centos","fa-brands fa-chrome","fa-brands fa-chromecast","fa-brands fa-cloudflare","fa-brands fa-cloudscale","fa-brands fa-cloudsmith","fa-brands fa-cloudversify","fa-brands fa-cmplid","fa-brands fa-codepen","fa-brands fa-codiepie","fa-brands fa-confluence","fa-brands fa-connectdevelop","fa-brands fa-contao","fa-brands fa-cotton-bureau","fa-brands fa-cpanel","fa-brands fa-creative-commons","fa-brands fa-creative-commons-by","fa-brands fa-creative-commons-nc","fa-brands fa-creative-commons-nc-eu","fa-brands fa-creative-commons-nc-jp","fa-brands fa-creative-commons-nd","fa-brands fa-creative-commons-pd","fa-brands fa-creative-commons-pd-alt","fa-brands fa-creative-commons-remix","fa-brands fa-creative-commons-sa","fa-brands fa-creative-commons-sampling","fa-brands fa-creative-commons-sampling-plus","fa-brands fa-creative-commons-share","fa-brands fa-creative-commons-zero","fa-brands fa-critical-role","fa-brands fa-css3","fa-brands fa-css3-alt","fa-brands fa-cuttlefish","fa-brands fa-d-and-d","fa-brands fa-d-and-d-beyond","fa-brands fa-audible","fa-brands fa-autoprefixer","fa-brands fa-avianex","fa-brands fa-aviato","fa-brands fa-aws","fa-brands fa-bandcamp","fa-brands fa-battle-net","fa-brands fa-behance","fa-brands fa-bilibili","fa-brands fa-bimobject","fa-brands fa-bitbucket","fa-brands fa-bitcoin","fa-brands fa-bity","fa-brands fa-blackberry","fa-brands fa-black-tie","fa-brands fa-blogger","fa-brands fa-blogger-b","fa-brands fa-bluetooth","fa-brands fa-bluetooth-b","fa-brands fa-bootstrap","fa-brands fa-bots","fa-brands fa-brave","fa-brands fa-brave-reverse","fa-brands fa-btc","fa-brands fa-buffer","fa-brands fa-buromobelexperte","fa-brands fa-buy-n-large","fa-brands fa-buysellads","fa-brands fa-canadian-maple-leaf","fa-brands fa-cc-amazon-pay","fa-brands fa-cc-amex","fa-brands fa-cc-apple-pay","fa-brands fa-cc-diners-club","fa-brands fa-cc-discover","fa-brands fa-cc-jcb","fa-brands fa-cc-mastercard","fa-brands fa-cc-paypal","fa-brands fa-42-group","fa-brands fa-500px","fa-brands fa-accessible-icon","fa-brands fa-accusoft", "fa-regular fa-circle-left","fa-regular fa-circle-pause","fa-regular fa-circle-play","fa-regular fa-circle-question","fa-regular fa-circle-right","fa-regular fa-circle-stop","fa-regular fa-circle-up","fa-regular fa-circle-user","fa-regular fa-circle-xmark","fa-regular fa-clipboard","fa-regular fa-clock","fa-regular fa-clone","fa-regular fa-closed-captioning","fa-regular fa-comment","fa-regular fa-comment-dots","fa-regular fa-comments","fa-regular fa-address-book","fa-regular fa-address-card","fa-regular fa-bell","fa-regular fa-bell-slash","fa-regular fa-bookmark","fa-regular fa-building","fa-regular fa-share-from-square","fa-regular fa-snowflake","fa-regular fa-square","fa-regular fa-square-caret-down","fa-regular fa-square-caret-left","fa-regular fa-square-caret-right","fa-regular fa-square-caret-up","fa-regular fa-square-check","fa-regular fa-square-full","fa-regular fa-square-minus","fa-regular fa-square-plus","fa-regular fa-star","fa-regular fa-star-half","fa-regular fa-star-half-stroke","fa-regular fa-sun","fa-regular fa-thumbs-down","fa-regular fa-thumbs-up","fa-regular fa-trash-can","fa-regular fa-user","fa-regular fa-window-maximize","fa-regular fa-window-minimize","fa-regular fa-window-restore","fa-regular fa-hand","fa-regular fa-hand-pointer","fa-regular fa-hand-point-right","fa-regular fa-hand-point-up","fa-regular fa-hand-scissors","fa-regular fa-handshake","fa-regular fa-hand-spock","fa-regular fa-hard-drive","fa-regular fa-heart","fa-regular fa-hospital","fa-regular fa-hourglass","fa-regular fa-hourglass-half","fa-regular fa-id-badge","fa-regular fa-id-card","fa-regular fa-image","fa-regular fa-images","fa-regular fa-keyboard","fa-regular fa-lemon","fa-regular fa-life-ring","fa-regular fa-lightbulb","fa-regular fa-map","fa-regular fa-message","fa-regular fa-money-bill-1","fa-regular fa-moon","fa-regular fa-newspaper","fa-regular fa-note-sticky","fa-regular fa-object-group","fa-regular fa-object-ungroup","fa-regular fa-paper-plane","fa-regular fa-paste","fa-regular fa-pen-to-square","fa-regular fa-rectangle-list","fa-regular fa-rectangle-xmark","fa-regular fa-registered","fa-regular fa-face-meh","fa-regular fa-face-meh-blank","fa-regular fa-face-rolling-eyes","fa-regular fa-face-sad-cry","fa-regular fa-face-sad-tear","fa-regular fa-face-smile","fa-regular fa-face-smile-beam","fa-regular fa-face-smile-wink","fa-regular fa-face-surprise","fa-regular fa-face-tired","fa-regular fa-file","fa-regular fa-file-audio","fa-regular fa-file-code","fa-regular fa-file-excel","fa-regular fa-file-image","fa-regular fa-file-lines","fa-regular fa-file-pdf","fa-regular fa-file-powerpoint","fa-regular fa-file-video","fa-regular fa-file-word","fa-regular fa-file-zipper","fa-regular fa-flag","fa-regular fa-floppy-disk","fa-regular fa-folder","fa-regular fa-folder-closed","fa-regular fa-folder-open","fa-regular fa-font-awesome","fa-regular fa-futbol","fa-regular fa-gem","fa-regular fa-hand-back-fist","fa-regular fa-hand-lizard","fa-regular fa-hand-peace","fa-regular fa-hand-point-down","fa-regular fa-hand-point-left","fa-regular fa-compass","fa-regular fa-copy","fa-regular fa-copyright","fa-regular fa-credit-card","fa-regular fa-envelope","fa-regular fa-envelope-open","fa-regular fa-eye","fa-regular fa-eye-slash","fa-regular fa-face-angry","fa-regular fa-face-dizzy","fa-regular fa-face-flushed","fa-regular fa-face-frown","fa-regular fa-face-frown-open","fa-regular fa-face-grimace","fa-regular fa-face-grin","fa-regular fa-face-grin-beam","fa-regular fa-face-grin-beam-sweat","fa-regular fa-face-grin-hearts","fa-regular fa-face-grin-squint","fa-regular fa-face-grin-squint-tears","fa-regular fa-face-grin-stars","fa-regular fa-face-grin-tears","fa-regular fa-face-grin-tongue","fa-regular fa-face-grin-tongue-squint","fa-regular fa-face-grin-tongue-wink","fa-regular fa-face-grin-wide","fa-regular fa-face-grin-wink","fa-regular fa-face-kiss","fa-regular fa-face-kiss-beam","fa-regular fa-face-kiss-wink-heart","fa-regular fa-face-laugh","fa-regular fa-face-laugh-beam","fa-regular fa-face-laugh-squint","fa-regular fa-face-laugh-wink","fa-regular fa-calendar","fa-regular fa-calendar-check","fa-regular fa-calendar-days","fa-regular fa-calendar-minus","fa-regular fa-calendar-plus","fa-regular fa-calendar-xmark","fa-regular fa-chart-bar","fa-regular fa-chess-bishop","fa-regular fa-chess-king","fa-regular fa-chess-knight","fa-regular fa-chess-pawn","fa-regular fa-chess-queen","fa-regular fa-chess-rook","fa-regular fa-circle","fa-regular fa-circle-check","fa-regular fa-circle-dot","fa-regular fa-circle-down", "fa-solid fa-worm","fa-solid fa-wrench","fa-solid fa-x","fa-solid fa-xmark","fa-solid fa-xmarks-lines","fa-solid fa-x-ray","fa-solid fa-y","fa-solid fa-yen-sign","fa-solid fa-yin-yang","fa-solid fa-z","fa-solid fa-voicemail","fa-solid fa-volcano","fa-solid fa-volleyball","fa-solid fa-volume-high","fa-solid fa-volume-low","fa-solid fa-volume-off","fa-solid fa-volume-xmark","fa-solid fa-vr-cardboard","fa-solid fa-w","fa-solid fa-walkie-talkie","fa-solid fa-wallet","fa-solid fa-wand-magic","fa-solid fa-wand-magic-sparkles","fa-solid fa-wand-sparkles","fa-solid fa-warehouse","fa-solid fa-water","fa-solid fa-water-ladder","fa-solid fa-wave-square","fa-solid fa-weight-hanging","fa-solid fa-weight-scale","fa-solid fa-wheat-awn","fa-solid fa-wheat-awn-circle-exclamation","fa-solid fa-wheelchair","fa-solid fa-wheelchair-move","fa-solid fa-whiskey-glass","fa-solid fa-wifi","fa-solid fa-wind","fa-solid fa-window-maximize","fa-solid fa-window-minimize","fa-solid fa-window-restore","fa-solid fa-wine-bottle","fa-solid fa-users","fa-solid fa-users-gear","fa-solid fa-users-line","fa-solid fa-users-rays","fa-solid fa-users-rectangle","fa-solid fa-users-slash","fa-solid fa-users-viewfinder","fa-solid fa-utensils","fa-solid fa-v","fa-solid fa-van-shuttle","fa-solid fa-vault","fa-solid fa-vector-square","fa-solid fa-venus","fa-solid fa-venus-double","fa-solid fa-venus-mars","fa-solid fa-vest","fa-solid fa-vest-patches","fa-solid fa-vial","fa-solid fa-vial-circle-check","fa-solid fa-vials","fa-solid fa-vial-virus","fa-solid fa-video","fa-solid fa-video-slash","fa-solid fa-vihara","fa-solid fa-virus","fa-solid fa-virus-covid","fa-solid fa-virus-covid-slash","fa-solid fa-viruses","fa-solid fa-virus-slash","fa-solid fa-underline","fa-solid fa-universal-access","fa-solid fa-unlock","fa-solid fa-unlock-keyhole","fa-solid fa-up-down","fa-solid fa-up-down-left-right","fa-solid fa-upload","fa-solid fa-up-long","fa-solid fa-up-right-and-down-left-from-center","fa-solid fa-up-right-from-square","fa-solid fa-user","fa-solid fa-user-astronaut","fa-solid fa-user-check","fa-solid fa-user-clock","fa-solid fa-user-doctor","fa-solid fa-user-gear","fa-solid fa-user-graduate","fa-solid fa-user-group","fa-solid fa-user-injured","fa-solid fa-user-large","fa-solid fa-user-large-slash","fa-solid fa-user-lock","fa-solid fa-user-minus","fa-solid fa-user-ninja","fa-solid fa-user-nurse","fa-solid fa-user-pen","fa-solid fa-user-plus","fa-solid fa-users-between-lines","fa-solid fa-user-secret","fa-solid fa-user-shield","fa-solid fa-user-slash","fa-solid fa-user-tag","fa-solid fa-user-tie","fa-solid fa-user-xmark","fa-solid fa-tree","fa-solid fa-tree-city","fa-solid fa-triangle-exclamation","fa-solid fa-trophy","fa-solid fa-trowel","fa-solid fa-trowel-bricks","fa-solid fa-truck","fa-solid fa-truck-arrow-right","fa-solid fa-truck-droplet","fa-solid fa-truck-fast","fa-solid fa-truck-field","fa-solid fa-truck-field-un","fa-solid fa-truck-front","fa-solid fa-truck-medical","fa-solid fa-truck-monster","fa-solid fa-truck-moving","fa-solid fa-truck-pickup","fa-solid fa-truck-plane","fa-solid fa-truck-ramp-box","fa-solid fa-tty","fa-solid fa-turkish-lira-sign","fa-solid fa-turn-down","fa-solid fa-turn-up","fa-solid fa-tv","fa-solid fa-u","fa-solid fa-umbrella","fa-solid fa-umbrella-beach","fa-solid fa-ticket","fa-solid fa-ticket-simple","fa-solid fa-timeline","fa-solid fa-toggle-off","fa-solid fa-toggle-on","fa-solid fa-toilet","fa-solid fa-toilet-paper","fa-solid fa-toilet-paper-slash","fa-solid fa-toilet-portable","fa-solid fa-toilets-portable","fa-solid fa-toolbox","fa-solid fa-tooth","fa-solid fa-torii-gate","fa-solid fa-tornado","fa-solid fa-tower-broadcast","fa-solid fa-tower-cell","fa-solid fa-tower-observation","fa-solid fa-tractor","fa-solid fa-trademark","fa-solid fa-traffic-light","fa-solid fa-trailer","fa-solid fa-train","fa-solid fa-train-subway","fa-solid fa-train-tram","fa-solid fa-transgender","fa-solid fa-trash","fa-solid fa-trash-arrow-up","fa-solid fa-trash-can","fa-solid fa-trash-can-arrow-up","fa-solid fa-tablets","fa-solid fa-tachograph-digital","fa-solid fa-tag","fa-solid fa-tags","fa-solid fa-tape","fa-solid fa-tarp","fa-solid fa-tarp-droplet","fa-solid fa-taxi","fa-solid fa-teeth","fa-solid fa-teeth-open","fa-solid fa-temperature-arrow-down","fa-solid fa-temperature-arrow-up","fa-solid fa-temperature-empty","fa-solid fa-temperature-full","fa-solid fa-temperature-half","fa-solid fa-temperature-high","fa-solid fa-temperature-low","fa-solid fa-temperature-quarter","fa-solid fa-temperature-three-quarters","fa-solid fa-tenge-sign","fa-solid fa-tent","fa-solid fa-tent-arrow-down-to-line","fa-solid fa-tent-arrow-left-right","fa-solid fa-tent-arrows-down","fa-solid fa-tent-arrow-turn-left","fa-solid fa-tents","fa-solid fa-terminal","fa-solid fa-text-height","fa-solid fa-text-slash","fa-solid fa-text-width","fa-solid fa-thermometer","fa-solid fa-thumbs-down","fa-solid fa-thumbs-up","fa-solid fa-thumbtack","fa-solid fa-star","fa-solid fa-star-half","fa-solid fa-star-of-david","fa-solid fa-star-of-life","fa-solid fa-sterling-sign","fa-solid fa-stethoscope","fa-solid fa-stop","fa-solid fa-stopwatch","fa-solid fa-stopwatch-20","fa-solid fa-store","fa-solid fa-store-slash","fa-solid fa-street-view","fa-solid fa-strikethrough","fa-solid fa-stroopwafel","fa-solid fa-subscript","fa-solid fa-suitcase","fa-solid fa-suitcase-medical","fa-solid fa-suitcase-rolling","fa-solid fa-sun","fa-solid fa-sun-plant-wilt","fa-solid fa-superscript","fa-solid fa-swatchbook","fa-solid fa-synagogue","fa-solid fa-syringe","fa-solid fa-t","fa-solid fa-table","fa-solid fa-table-cells","fa-solid fa-table-cells-large","fa-solid fa-table-columns","fa-solid fa-table-list","fa-solid fa-tablet","fa-solid fa-tablet-button","fa-solid fa-table-tennis-paddle-ball","fa-solid fa-tablet-screen-button","fa-solid fa-spray-can","fa-solid fa-spray-can-sparkles","fa-solid fa-square","fa-solid fa-square-arrow-up-right","fa-solid fa-square-caret-down","fa-solid fa-square-caret-left","fa-solid fa-square-caret-right","fa-solid fa-square-caret-up","fa-solid fa-square-check","fa-solid fa-square-envelope","fa-solid fa-square-full","fa-solid fa-square-h","fa-solid fa-square-minus","fa-solid fa-square-nfi","fa-solid fa-square-parking","fa-solid fa-square-pen","fa-solid fa-square-person-confined","fa-solid fa-square-phone","fa-solid fa-square-phone-flip","fa-solid fa-square-plus","fa-solid fa-square-poll-horizontal","fa-solid fa-square-poll-vertical","fa-solid fa-square-root-variable","fa-solid fa-square-rss","fa-solid fa-square-share-nodes","fa-solid fa-square-up-right","fa-solid fa-square-virus","fa-solid fa-square-xmark","fa-solid fa-staff-snake","fa-solid fa-stairs","fa-solid fa-stamp","fa-solid fa-stapler","fa-solid fa-star-and-crescent","fa-solid fa-star-half-stroke","fa-solid fa-shower","fa-solid fa-shrimp","fa-solid fa-shuffle","fa-solid fa-shuttle-space","fa-solid fa-signal","fa-solid fa-signature","fa-solid fa-sign-hanging","fa-solid fa-signs-post","fa-solid fa-sim-card","fa-solid fa-sink","fa-solid fa-sitemap","fa-solid fa-skull","fa-solid fa-skull-crossbones","fa-solid fa-slash","fa-solid fa-sleigh","fa-solid fa-sliders","fa-solid fa-smog","fa-solid fa-smoking","fa-solid fa-snowflake","fa-solid fa-snowman","fa-solid fa-snowplow","fa-solid fa-soap","fa-solid fa-socks","fa-solid fa-solar-panel","fa-solid fa-sort","fa-solid fa-sort-down","fa-solid fa-sort-up","fa-solid fa-spa","fa-solid fa-spaghetti-monster-flying","fa-solid fa-spell-check","fa-solid fa-spider","fa-solid fa-spinner","fa-solid fa-splotch","fa-solid fa-spoon","fa-solid fa-scale-unbalanced","fa-solid fa-school","fa-solid fa-school-circle-check","fa-solid fa-school-circle-exclamation","fa-solid fa-school-circle-xmark","fa-solid fa-school-flag","fa-solid fa-school-lock","fa-solid fa-scissors","fa-solid fa-screwdriver","fa-solid fa-screwdriver-wrench","fa-solid fa-scroll","fa-solid fa-scroll-torah","fa-solid fa-sd-card","fa-solid fa-section","fa-solid fa-seedling","fa-solid fa-server","fa-solid fa-shapes","fa-solid fa-share","fa-solid fa-share-from-square","fa-solid fa-share-nodes","fa-solid fa-sheet-plastic","fa-solid fa-shekel-sign","fa-solid fa-shield","fa-solid fa-shield-cat","fa-solid fa-shield-dog","fa-solid fa-shield-halved","fa-solid fa-shield-heart","fa-solid fa-shield-virus","fa-solid fa-ship","fa-solid fa-shirt","fa-solid fa-shoe-prints","fa-solid fa-shop","fa-solid fa-shop-lock","fa-solid fa-shop-slash","fa-solid fa-right-long","fa-solid fa-right-to-bracket","fa-solid fa-ring","fa-solid fa-road","fa-solid fa-road-barrier","fa-solid fa-road-bridge","fa-solid fa-road-circle-check","fa-solid fa-road-circle-exclamation","fa-solid fa-road-circle-xmark","fa-solid fa-road-lock","fa-solid fa-road-spikes","fa-solid fa-robot","fa-solid fa-rocket","fa-solid fa-rotate","fa-solid fa-rotate-left","fa-solid fa-rotate-right","fa-solid fa-route","fa-solid fa-rss","fa-solid fa-ruble-sign","fa-solid fa-rug","fa-solid fa-ruler","fa-solid fa-ruler-combined","fa-solid fa-ruler-horizontal","fa-solid fa-ruler-vertical","fa-solid fa-rupee-sign","fa-solid fa-rupiah-sign","fa-solid fa-s","fa-solid fa-sack-dollar","fa-solid fa-sack-xmark","fa-solid fa-sailboat","fa-solid fa-satellite","fa-solid fa-satellite-dish","fa-solid fa-scale-balanced","fa-solid fa-scale-unbalanced-flip","fa-solid fa-prescription","fa-solid fa-prescription-bottle","fa-solid fa-prescription-bottle-medical","fa-solid fa-print","fa-solid fa-pump-medical","fa-solid fa-pump-soap","fa-solid fa-puzzle-piece","fa-solid fa-q","fa-solid fa-qrcode","fa-solid fa-question","fa-solid fa-quote-left","fa-solid fa-quote-right","fa-solid fa-r","fa-solid fa-radiation","fa-solid fa-radio","fa-solid fa-rainbow","fa-solid fa-ranking-star","fa-solid fa-receipt","fa-solid fa-record-vinyl","fa-solid fa-rectangle-ad","fa-solid fa-rectangle-list","fa-solid fa-rectangle-xmark","fa-solid fa-recycle","fa-solid fa-registered","fa-solid fa-repeat","fa-solid fa-reply","fa-solid fa-reply-all","fa-solid fa-republican","fa-solid fa-restroom","fa-solid fa-retweet","fa-solid fa-ribbon","fa-solid fa-right-from-bracket","fa-solid fa-right-left","fa-solid fa-phone","fa-solid fa-phone-flip","fa-solid fa-phone-slash","fa-solid fa-phone-volume","fa-solid fa-photo-film","fa-solid fa-piggy-bank","fa-solid fa-pills","fa-solid fa-pizza-slice","fa-solid fa-place-of-worship","fa-solid fa-plane","fa-solid fa-plane-arrival","fa-solid fa-plane-circle-check","fa-solid fa-plane-circle-exclamation","fa-solid fa-plane-circle-xmark","fa-solid fa-plane-departure","fa-solid fa-plane-lock","fa-solid fa-plane-slash","fa-solid fa-plane-up","fa-solid fa-plant-wilt","fa-solid fa-plate-wheat","fa-solid fa-play","fa-solid fa-plug","fa-solid fa-plug-circle-bolt","fa-solid fa-plug-circle-check","fa-solid fa-plug-circle-exclamation","fa-solid fa-plug-circle-minus","fa-solid fa-plug-circle-plus","fa-solid fa-plug-circle-xmark","fa-solid fa-plus","fa-solid fa-plus-minus","fa-solid fa-podcast","fa-solid fa-poo","fa-solid fa-poop","fa-solid fa-poo-storm","fa-solid fa-power-off","fa-solid fa-person","fa-solid fa-person-dots-from-line","fa-solid fa-person-dress","fa-solid fa-person-dress-burst","fa-solid fa-person-drowning","fa-solid fa-person-falling","fa-solid fa-person-falling-burst","fa-solid fa-person-half-dress","fa-solid fa-person-harassing","fa-solid fa-person-hiking","fa-solid fa-person-military-pointing","fa-solid fa-person-military-rifle","fa-solid fa-person-military-to-person","fa-solid fa-person-praying","fa-solid fa-person-pregnant","fa-solid fa-person-rays","fa-solid fa-person-rifle","fa-solid fa-person-running","fa-solid fa-person-shelter","fa-solid fa-person-skating","fa-solid fa-person-skiing","fa-solid fa-person-skiing-nordic","fa-solid fa-person-snowboarding","fa-solid fa-person-swimming","fa-solid fa-person-through-window","fa-solid fa-person-walking","fa-solid fa-person-walking-arrow-loop-left","fa-solid fa-person-walking-arrow-right","fa-solid fa-person-walking-dashed-line-arrow-right","fa-solid fa-person-walking-luggage","fa-solid fa-person-walking-with-cane","fa-solid fa-peseta-sign","fa-solid fa-peso-sign","fa-solid fa-pause","fa-solid fa-paw","fa-solid fa-peace","fa-solid fa-pen","fa-solid fa-pencil","fa-solid fa-pen-clip","fa-solid fa-pen-fancy","fa-solid fa-pen-nib","fa-solid fa-pen-ruler","fa-solid fa-pen-to-square","fa-solid fa-people-arrows","fa-solid fa-people-carry-box","fa-solid fa-people-group","fa-solid fa-people-line","fa-solid fa-people-pulling","fa-solid fa-people-robbery","fa-solid fa-people-roof","fa-solid fa-pepper-hot","fa-solid fa-percent","fa-solid fa-person-arrow-down-to-line","fa-solid fa-person-arrow-up-from-line","fa-solid fa-person-biking","fa-solid fa-person-booth","fa-solid fa-person-breastfeeding","fa-solid fa-person-burst","fa-solid fa-person-cane","fa-solid fa-person-chalkboard","fa-solid fa-person-circle-check","fa-solid fa-person-circle-exclamation","fa-solid fa-person-circle-minus","fa-solid fa-person-circle-plus","fa-solid fa-person-circle-question","fa-solid fa-person-circle-xmark","fa-solid fa-person-digging","fa-solid fa-mountain","fa-solid fa-mug-hot","fa-solid fa-mug-saucer","fa-solid fa-music","fa-solid fa-n","fa-solid fa-naira-sign","fa-solid fa-network-wired","fa-solid fa-neuter","fa-solid fa-newspaper","fa-solid fa-notdef","fa-solid fa-not-equal","fa-solid fa-notes-medical","fa-solid fa-note-sticky","fa-solid fa-o","fa-solid fa-object-group","fa-solid fa-object-ungroup","fa-solid fa-oil-can","fa-solid fa-oil-well","fa-solid fa-om","fa-solid fa-otter","fa-solid fa-outdent","fa-solid fa-p","fa-solid fa-pager","fa-solid fa-paintbrush","fa-solid fa-paint-roller","fa-solid fa-palette","fa-solid fa-pallet","fa-solid fa-panorama","fa-solid fa-paperclip","fa-solid fa-paper-plane","fa-solid fa-parachute-box","fa-solid fa-paragraph","fa-solid fa-passport","fa-solid fa-paste","fa-solid fa-microphone","fa-solid fa-microphone-lines","fa-solid fa-microphone-lines-slash","fa-solid fa-microphone-slash","fa-solid fa-microscope","fa-solid fa-mill-sign","fa-solid fa-minimize","fa-solid fa-minus","fa-solid fa-mitten","fa-solid fa-mobile","fa-solid fa-mobile-button","fa-solid fa-mobile-retro","fa-solid fa-mobile-screen","fa-solid fa-mobile-screen-button","fa-solid fa-money-bill","fa-solid fa-money-bill-1","fa-solid fa-money-bill-1-wave","fa-solid fa-money-bills","fa-solid fa-money-bill-transfer","fa-solid fa-money-bill-trend-up","fa-solid fa-money-bill-wave","fa-solid fa-money-bill-wheat","fa-solid fa-money-check","fa-solid fa-money-check-dollar","fa-solid fa-monument","fa-solid fa-moon","fa-solid fa-mortar-pestle","fa-solid fa-mosque","fa-solid fa-mosquito","fa-solid fa-mosquito-net","fa-solid fa-motorcycle","fa-solid fa-mound","fa-solid fa-mountain-city","fa-solid fa-mountain-sun","fa-solid fa-magnifying-glass","fa-solid fa-magnifying-glass-location","fa-solid fa-magnifying-glass-minus","fa-solid fa-magnifying-glass-plus","fa-solid fa-manat-sign","fa-solid fa-map","fa-solid fa-map-location","fa-solid fa-map-location-dot","fa-solid fa-map-pin","fa-solid fa-marker","fa-solid fa-mars","fa-solid fa-mars-and-venus","fa-solid fa-mars-and-venus-burst","fa-solid fa-mars-double","fa-solid fa-mars-stroke","fa-solid fa-mars-stroke-right","fa-solid fa-mars-stroke-up","fa-solid fa-martini-glass","fa-solid fa-martini-glass-citrus","fa-solid fa-martini-glass-empty","fa-solid fa-mask","fa-solid fa-mask-face","fa-solid fa-masks-theater","fa-solid fa-mask-ventilator","fa-solid fa-mattress-pillow","fa-solid fa-maximize","fa-solid fa-medal","fa-solid fa-memory","fa-solid fa-menorah","fa-solid fa-mercury","fa-solid fa-message","fa-solid fa-meteor","fa-solid fa-microchip","fa-solid fa-laptop","fa-solid fa-lari-sign","fa-solid fa-layer-group","fa-solid fa-leaf","fa-solid fa-left-long","fa-solid fa-left-right","fa-solid fa-lemon","fa-solid fa-less-than","fa-solid fa-less-than-equal","fa-solid fa-life-ring","fa-solid fa-lightbulb","fa-solid fa-lines-leaning","fa-solid fa-link","fa-solid fa-link-slash","fa-solid fa-lira-sign","fa-solid fa-list","fa-solid fa-list-check","fa-solid fa-list-ol","fa-solid fa-list-ul","fa-solid fa-litecoin-sign","fa-solid fa-location-arrow","fa-solid fa-location-crosshairs","fa-solid fa-location-dot","fa-solid fa-location-pin","fa-solid fa-location-pin-lock","fa-solid fa-lock","fa-solid fa-lock-open","fa-solid fa-locust","fa-solid fa-lungs","fa-solid fa-lungs-virus","fa-solid fa-m","fa-solid fa-magnet","fa-solid fa-magnifying-glass-arrow-right","fa-solid fa-magnifying-glass-chart","fa-solid fa-magnifying-glass-dollar","fa-solid fa-indent","fa-solid fa-indian-rupee-sign","fa-solid fa-industry","fa-solid fa-infinity","fa-solid fa-info","fa-solid fa-italic","fa-solid fa-j","fa-solid fa-jar","fa-solid fa-jar-wheat","fa-solid fa-jedi","fa-solid fa-jet-fighter","fa-solid fa-jet-fighter-up","fa-solid fa-joint","fa-solid fa-jug-detergent","fa-solid fa-k","fa-solid fa-kaaba","fa-solid fa-key","fa-solid fa-keyboard","fa-solid fa-khanda","fa-solid fa-kip-sign","fa-solid fa-kitchen-set","fa-solid fa-kit-medical","fa-solid fa-kiwi-bird","fa-solid fa-l","fa-solid fa-landmark","fa-solid fa-landmark-dome","fa-solid fa-landmark-flag","fa-solid fa-land-mine-on","fa-solid fa-language","fa-solid fa-laptop-code","fa-solid fa-laptop-file","fa-solid fa-laptop-medical","fa-solid fa-house","fa-solid fa-house-chimney","fa-solid fa-house-circle-check","fa-solid fa-house-circle-exclamation","fa-solid fa-house-circle-xmark","fa-solid fa-house-crack","fa-solid fa-house-fire","fa-solid fa-house-flag","fa-solid fa-house-flood-water","fa-solid fa-house-flood-water-circle-arrow-right","fa-solid fa-house-laptop","fa-solid fa-house-lock","fa-solid fa-house-medical","fa-solid fa-house-medical-circle-check","fa-solid fa-house-medical-circle-exclamation","fa-solid fa-house-medical-circle-xmark","fa-solid fa-house-medical-flag","fa-solid fa-house-signal","fa-solid fa-house-tsunami","fa-solid fa-house-user","fa-solid fa-hryvnia-sign","fa-solid fa-hurricane","fa-solid fa-i","fa-solid fa-ice-cream","fa-solid fa-icicles","fa-solid fa-icons","fa-solid fa-i-cursor","fa-solid fa-id-badge","fa-solid fa-id-card","fa-solid fa-id-card-clip","fa-solid fa-igloo","fa-solid fa-image","fa-solid fa-image-portrait","fa-solid fa-images","fa-solid fa-inbox","fa-solid fa-heart","fa-solid fa-heart-circle-bolt","fa-solid fa-heart-circle-check","fa-solid fa-heart-circle-exclamation","fa-solid fa-heart-circle-minus","fa-solid fa-heart-circle-plus","fa-solid fa-heart-circle-xmark","fa-solid fa-heart-crack","fa-solid fa-heart-pulse","fa-solid fa-helicopter","fa-solid fa-helicopter-symbol","fa-solid fa-helmet-safety","fa-solid fa-helmet-un","fa-solid fa-highlighter","fa-solid fa-hill-avalanche","fa-solid fa-hill-rockslide","fa-solid fa-hippo","fa-solid fa-hockey-puck","fa-solid fa-holly-berry","fa-solid fa-horse","fa-solid fa-horse-head","fa-solid fa-hospital","fa-solid fa-hospital-user","fa-solid fa-hotdog","fa-solid fa-hotel","fa-solid fa-hot-tub-person","fa-solid fa-hourglass","fa-solid fa-hourglass-end","fa-solid fa-hourglass-half","fa-solid fa-hourglass-start","fa-solid fa-house-chimney-crack","fa-solid fa-house-chimney-medical","fa-solid fa-house-chimney-user","fa-solid fa-house-chimney-window","fa-solid fa-hand","fa-solid fa-handcuffs","fa-solid fa-hand-pointer","fa-solid fa-hands","fa-solid fa-hands-asl-interpreting","fa-solid fa-hands-bound","fa-solid fa-hands-bubbles","fa-solid fa-hand-scissors","fa-solid fa-hands-clapping","fa-solid fa-handshake","fa-solid fa-handshake-angle","fa-solid fa-handshake-simple","fa-solid fa-handshake-simple-slash","fa-solid fa-handshake-slash","fa-solid fa-hands-holding","fa-solid fa-hands-holding-child","fa-solid fa-hands-holding-circle","fa-solid fa-hand-sparkles","fa-solid fa-hand-spock","fa-solid fa-hands-praying","fa-solid fa-hanukiah","fa-solid fa-hard-drive","fa-solid fa-hashtag","fa-solid fa-hat-cowboy","fa-solid fa-hat-cowboy-side","fa-solid fa-hat-wizard","fa-solid fa-heading","fa-solid fa-headphones","fa-solid fa-headphones-simple","fa-solid fa-headset","fa-solid fa-head-side-cough","fa-solid fa-head-side-cough-slash","fa-solid fa-head-side-mask","fa-solid fa-head-side-virus","fa-solid fa-globe","fa-solid fa-golf-ball-tee","fa-solid fa-gopuram","fa-solid fa-graduation-cap","fa-solid fa-greater-than","fa-solid fa-greater-than-equal","fa-solid fa-grip","fa-solid fa-grip-lines","fa-solid fa-grip-lines-vertical","fa-solid fa-grip-vertical","fa-solid fa-group-arrows-rotate","fa-solid fa-guarani-sign","fa-solid fa-guitar","fa-solid fa-gun","fa-solid fa-h","fa-solid fa-hammer","fa-solid fa-hamsa","fa-solid fa-hand-back-fist","fa-solid fa-hand-dots","fa-solid fa-hand-fist","fa-solid fa-hand-holding","fa-solid fa-hand-holding-dollar","fa-solid fa-hand-holding-droplet","fa-solid fa-hand-holding-hand","fa-solid fa-hand-holding-heart","fa-solid fa-hand-holding-medical","fa-solid fa-hand-lizard","fa-solid fa-hand-middle-finger","fa-solid fa-hand-peace","fa-solid fa-hand-point-down","fa-solid fa-hand-point-left","fa-solid fa-hand-point-right","fa-solid fa-hand-point-up","fa-solid fa-florin-sign","fa-solid fa-folder","fa-solid fa-folder-closed","fa-solid fa-folder-minus","fa-solid fa-folder-open","fa-solid fa-folder-plus","fa-solid fa-folder-tree","fa-solid fa-font","fa-solid fa-font-awesome","fa-solid fa-football","fa-solid fa-forward","fa-solid fa-forward-fast","fa-solid fa-forward-step","fa-solid fa-franc-sign","fa-solid fa-frog","fa-solid fa-futbol","fa-solid fa-g","fa-solid fa-gamepad","fa-solid fa-gas-pump","fa-solid fa-gauge","fa-solid fa-gauge-high","fa-solid fa-gauge-simple","fa-solid fa-gauge-simple-high","fa-solid fa-gavel","fa-solid fa-gear","fa-solid fa-gears","fa-solid fa-gem","fa-solid fa-genderless","fa-solid fa-ghost","fa-solid fa-gift","fa-solid fa-gifts","fa-solid fa-glasses","fa-solid fa-glass-water","fa-solid fa-glass-water-droplet","fa-solid fa-file","fa-solid fa-file-lines","fa-solid fa-file-medical","fa-solid fa-file-pdf","fa-solid fa-file-pen","fa-solid fa-file-powerpoint","fa-solid fa-file-prescription","fa-solid fa-file-shield","fa-solid fa-file-signature","fa-solid fa-file-video","fa-solid fa-file-waveform","fa-solid fa-file-word","fa-solid fa-file-zipper","fa-solid fa-fill","fa-solid fa-fill-drip","fa-solid fa-film","fa-solid fa-filter","fa-solid fa-filter-circle-dollar","fa-solid fa-filter-circle-xmark","fa-solid fa-fingerprint","fa-solid fa-fire","fa-solid fa-fire-burner","fa-solid fa-fire-extinguisher","fa-solid fa-fire-flame-curved","fa-solid fa-fire-flame-simple","fa-solid fa-fish","fa-solid fa-fish-fins","fa-solid fa-flag","fa-solid fa-flag-checkered","fa-solid fa-flag-usa","fa-solid fa-flask","fa-solid fa-flask-vial","fa-solid fa-floppy-disk","fa-solid fa-face-laugh","fa-solid fa-face-meh","fa-solid fa-face-meh-blank","fa-solid fa-face-rolling-eyes","fa-solid fa-face-sad-cry","fa-solid fa-face-sad-tear","fa-solid fa-face-smile","fa-solid fa-face-smile-beam","fa-solid fa-face-smile-wink","fa-solid fa-face-surprise","fa-solid fa-face-tired","fa-solid fa-fan","fa-solid fa-faucet","fa-solid fa-faucet-drip","fa-solid fa-fax","fa-solid fa-feather","fa-solid fa-feather-pointed","fa-solid fa-ferry","fa-solid fa-file-arrow-down","fa-solid fa-file-arrow-up","fa-solid fa-file-audio","fa-solid fa-file-circle-check","fa-solid fa-file-circle-exclamation","fa-solid fa-file-circle-minus","fa-solid fa-file-circle-plus","fa-solid fa-file-circle-question","fa-solid fa-file-circle-xmark","fa-solid fa-file-code","fa-solid fa-file-contract","fa-solid fa-file-csv","fa-solid fa-file-excel","fa-solid fa-file-export","fa-solid fa-file-image","fa-solid fa-file-import","fa-solid fa-file-invoice","fa-solid fa-file-invoice-dollar","fa-solid fa-exclamation","fa-solid fa-expand","fa-solid fa-explosion","fa-solid fa-eye","fa-solid fa-eye-dropper","fa-solid fa-eye-low-vision","fa-solid fa-eye-slash","fa-solid fa-f","fa-solid fa-face-angry","fa-solid fa-face-dizzy","fa-solid fa-face-flushed","fa-solid fa-face-frown","fa-solid fa-face-frown-open","fa-solid fa-face-grimace","fa-solid fa-face-grin","fa-solid fa-face-grin-beam","fa-solid fa-face-grin-beam-sweat","fa-solid fa-face-grin-hearts","fa-solid fa-face-grin-squint","fa-solid fa-face-grin-squint-tears","fa-solid fa-face-grin-stars","fa-solid fa-face-grin-tears","fa-solid fa-face-grin-tongue","fa-solid fa-face-grin-tongue-squint","fa-solid fa-face-grin-tongue-wink","fa-solid fa-face-grin-wide","fa-solid fa-face-grin-wink","fa-solid fa-face-kiss","fa-solid fa-face-kiss-beam","fa-solid fa-face-kiss-wink-heart","fa-solid fa-face-laugh-beam","fa-solid fa-face-laugh-squint","fa-solid fa-face-laugh-wink","fa-solid fa-download","fa-solid fa-dragon","fa-solid fa-draw-polygon","fa-solid fa-droplet","fa-solid fa-droplet-slash","fa-solid fa-drum","fa-solid fa-drum-steelpan","fa-solid fa-drumstick-bite","fa-solid fa-dumbbell","fa-solid fa-dumpster","fa-solid fa-dumpster-fire","fa-solid fa-dungeon","fa-solid fa-e","fa-solid fa-ear-deaf","fa-solid fa-ear-listen","fa-solid fa-earth-africa","fa-solid fa-earth-americas","fa-solid fa-earth-asia","fa-solid fa-earth-europe","fa-solid fa-earth-oceania","fa-solid fa-egg","fa-solid fa-eject","fa-solid fa-elevator","fa-solid fa-ellipsis","fa-solid fa-ellipsis-vertical","fa-solid fa-envelope","fa-solid fa-envelope-circle-check","fa-solid fa-envelope-open","fa-solid fa-envelope-open-text","fa-solid fa-envelopes-bulk","fa-solid fa-equals","fa-solid fa-eraser","fa-solid fa-ethernet","fa-solid fa-euro-sign","fa-solid fa-d","fa-solid fa-database","fa-solid fa-delete-left","fa-solid fa-democrat","fa-solid fa-desktop","fa-solid fa-dharmachakra","fa-solid fa-diagram-next","fa-solid fa-diagram-predecessor","fa-solid fa-diagram-project","fa-solid fa-diagram-successor","fa-solid fa-diamond","fa-solid fa-diamond-turn-right","fa-solid fa-dice","fa-solid fa-dice-d6","fa-solid fa-dice-d20","fa-solid fa-dice-five","fa-solid fa-dice-four","fa-solid fa-dice-one","fa-solid fa-dice-six","fa-solid fa-dice-three","fa-solid fa-dice-two","fa-solid fa-disease","fa-solid fa-display","fa-solid fa-divide","fa-solid fa-dna","fa-solid fa-dog","fa-solid fa-dollar-sign","fa-solid fa-dolly","fa-solid fa-dong-sign","fa-solid fa-door-closed","fa-solid fa-door-open","fa-solid fa-dove","fa-solid fa-down-left-and-up-right-to-center","fa-solid fa-down-long","fa-solid fa-coins","fa-solid fa-colon-sign","fa-solid fa-comment","fa-solid fa-comment-dollar","fa-solid fa-comment-dots","fa-solid fa-comment-medical","fa-solid fa-comments","fa-solid fa-comments-dollar","fa-solid fa-comment-slash","fa-solid fa-comment-sms","fa-solid fa-compact-disc","fa-solid fa-compass","fa-solid fa-compass-drafting","fa-solid fa-compress","fa-solid fa-computer","fa-solid fa-computer-mouse","fa-solid fa-cookie","fa-solid fa-cookie-bite","fa-solid fa-copy","fa-solid fa-copyright","fa-solid fa-couch","fa-solid fa-cow","fa-solid fa-credit-card","fa-solid fa-crop","fa-solid fa-crop-simple","fa-solid fa-cross","fa-solid fa-crosshairs","fa-solid fa-crow","fa-solid fa-crown","fa-solid fa-crutch","fa-solid fa-cruzeiro-sign","fa-solid fa-cube","fa-solid fa-cubes","fa-solid fa-cubes-stacked","fa-solid fa-circle","fa-solid fa-circle-user","fa-solid fa-circle-xmark","fa-solid fa-city","fa-solid fa-clapperboard","fa-solid fa-clipboard","fa-solid fa-clipboard-check","fa-solid fa-clipboard-list","fa-solid fa-clipboard-question","fa-solid fa-clipboard-user","fa-solid fa-clock","fa-solid fa-clock-rotate-left","fa-solid fa-clone","fa-solid fa-closed-captioning","fa-solid fa-cloud","fa-solid fa-cloud-arrow-down","fa-solid fa-cloud-arrow-up","fa-solid fa-cloud-bolt","fa-solid fa-cloud-meatball","fa-solid fa-cloud-moon","fa-solid fa-cloud-moon-rain","fa-solid fa-cloud-rain","fa-solid fa-cloud-showers-heavy","fa-solid fa-cloud-showers-water","fa-solid fa-cloud-sun","fa-solid fa-cloud-sun-rain","fa-solid fa-clover","fa-solid fa-code","fa-solid fa-code-branch","fa-solid fa-code-commit","fa-solid fa-code-compare","fa-solid fa-code-fork","fa-solid fa-code-merge","fa-solid fa-code-pull-request","fa-solid fa-child","fa-solid fa-child-reaching","fa-solid fa-children","fa-solid fa-church","fa-solid fa-circle-arrow-down","fa-solid fa-circle-arrow-left","fa-solid fa-circle-arrow-right","fa-solid fa-circle-arrow-up","fa-solid fa-circle-check","fa-solid fa-circle-chevron-down","fa-solid fa-circle-chevron-left","fa-solid fa-circle-chevron-right","fa-solid fa-circle-chevron-up","fa-solid fa-circle-dollar-to-slot","fa-solid fa-circle-dot","fa-solid fa-circle-down","fa-solid fa-circle-exclamation","fa-solid fa-circle-h","fa-solid fa-circle-half-stroke","fa-solid fa-circle-info","fa-solid fa-circle-left","fa-solid fa-circle-minus","fa-solid fa-circle-nodes","fa-solid fa-circle-notch","fa-solid fa-circle-pause","fa-solid fa-circle-play","fa-solid fa-circle-plus","fa-solid fa-circle-question","fa-solid fa-circle-radiation","fa-solid fa-circle-right","fa-solid fa-circle-stop","fa-solid fa-circle-up","fa-solid fa-cedi-sign","fa-solid fa-cent-sign","fa-solid fa-certificate","fa-solid fa-chair","fa-solid fa-chalkboard","fa-solid fa-chalkboard-user","fa-solid fa-champagne-glasses","fa-solid fa-charging-station","fa-solid fa-chart-area","fa-solid fa-chart-bar","fa-solid fa-chart-column","fa-solid fa-chart-gantt","fa-solid fa-chart-line","fa-solid fa-chart-pie","fa-solid fa-chart-simple","fa-solid fa-check","fa-solid fa-check-double","fa-solid fa-check-to-slot","fa-solid fa-cheese","fa-solid fa-chess","fa-solid fa-chess-bishop","fa-solid fa-chess-board","fa-solid fa-chess-king","fa-solid fa-chess-knight","fa-solid fa-chess-pawn","fa-solid fa-chess-queen","fa-solid fa-chess-rook","fa-solid fa-chevron-down","fa-solid fa-chevron-left","fa-solid fa-chevron-right","fa-solid fa-chevron-up","fa-solid fa-child-combatant","fa-solid fa-child-dress","fa-solid fa-calendar","fa-solid fa-calendar-day","fa-solid fa-calendar-days","fa-solid fa-calendar-minus","fa-solid fa-calendar-plus","fa-solid fa-calendar-week","fa-solid fa-calendar-xmark","fa-solid fa-camera","fa-solid fa-camera-retro","fa-solid fa-camera-rotate","fa-solid fa-campground","fa-solid fa-candy-cane","fa-solid fa-cannabis","fa-solid fa-capsules","fa-solid fa-car","fa-solid fa-caravan","fa-solid fa-car-battery","fa-solid fa-car-burst","fa-solid fa-caret-down","fa-solid fa-caret-left","fa-solid fa-caret-right","fa-solid fa-caret-up","fa-solid fa-car-on","fa-solid fa-car-rear","fa-solid fa-carrot","fa-solid fa-car-side","fa-solid fa-cart-arrow-down","fa-solid fa-cart-flatbed","fa-solid fa-cart-flatbed-suitcase","fa-solid fa-cart-plus","fa-solid fa-cart-shopping","fa-solid fa-car-tunnel","fa-solid fa-cash-register","fa-solid fa-cat","fa-solid fa-briefcase","fa-solid fa-broom","fa-solid fa-broom-ball","fa-solid fa-brush","fa-solid fa-bucket","fa-solid fa-bug","fa-solid fa-bugs","fa-solid fa-bug-slash","fa-solid fa-building","fa-solid fa-building-circle-arrow-right","fa-solid fa-building-circle-check","fa-solid fa-building-circle-exclamation","fa-solid fa-building-circle-xmark","fa-solid fa-building-columns","fa-solid fa-building-flag","fa-solid fa-building-lock","fa-solid fa-building-ngo","fa-solid fa-building-shield","fa-solid fa-building-un","fa-solid fa-building-user","fa-solid fa-building-wheat","fa-solid fa-bullhorn","fa-solid fa-bullseye","fa-solid fa-burger","fa-solid fa-burst","fa-solid fa-bus","fa-solid fa-business-time","fa-solid fa-bus-simple","fa-solid fa-c","fa-solid fa-cable-car","fa-solid fa-cake-candles","fa-solid fa-calculator","fa-solid fa-calendar-check","fa-solid fa-book","fa-solid fa-bookmark","fa-solid fa-book-medical","fa-solid fa-book-open","fa-solid fa-book-open-reader","fa-solid fa-book-quran","fa-solid fa-book-skull","fa-solid fa-book-tanakh","fa-solid fa-border-all","fa-solid fa-border-none","fa-solid fa-border-top-left","fa-solid fa-bore-hole","fa-solid fa-bottle-droplet","fa-solid fa-bottle-water","fa-solid fa-bowl-food","fa-solid fa-bowling-ball","fa-solid fa-bowl-rice","fa-solid fa-box","fa-solid fa-box-archive","fa-solid fa-boxes-packing","fa-solid fa-boxes-stacked","fa-solid fa-box-open","fa-solid fa-box-tissue","fa-solid fa-braille","fa-solid fa-brain","fa-solid fa-brazilian-real-sign","fa-solid fa-bread-slice","fa-solid fa-bridge","fa-solid fa-bridge-circle-check","fa-solid fa-bridge-circle-exclamation","fa-solid fa-bridge-circle-xmark","fa-solid fa-bridge-lock","fa-solid fa-bridge-water","fa-solid fa-briefcase-medical","fa-solid fa-baseball","fa-solid fa-baseball-bat-ball","fa-solid fa-basketball","fa-solid fa-basket-shopping","fa-solid fa-bath","fa-solid fa-battery-empty","fa-solid fa-battery-full","fa-solid fa-battery-half","fa-solid fa-battery-quarter","fa-solid fa-battery-three-quarters","fa-solid fa-bed","fa-solid fa-bed-pulse","fa-solid fa-beer-mug-empty","fa-solid fa-bell","fa-solid fa-bell-concierge","fa-solid fa-bell-slash","fa-solid fa-bezier-curve","fa-solid fa-bicycle","fa-solid fa-binoculars","fa-solid fa-biohazard","fa-solid fa-bitcoin-sign","fa-solid fa-blender","fa-solid fa-blender-phone","fa-solid fa-blog","fa-solid fa-bold","fa-solid fa-bolt","fa-solid fa-bolt-lightning","fa-solid fa-bomb","fa-solid fa-bone","fa-solid fa-bong","fa-solid fa-book-atlas","fa-solid fa-book-bible","fa-solid fa-book-bookmark","fa-solid fa-book-journal-whills","fa-solid fa-arrows-to-circle","fa-solid fa-arrows-to-dot","fa-solid fa-arrows-to-eye","fa-solid fa-arrows-turn-right","fa-solid fa-arrows-turn-to-dots","fa-solid fa-arrows-up-down","fa-solid fa-arrows-up-down-left-right","fa-solid fa-arrows-up-to-line","fa-solid fa-asterisk","fa-solid fa-at","fa-solid fa-atom","fa-solid fa-audio-description","fa-solid fa-austral-sign","fa-solid fa-award","fa-solid fa-b","fa-solid fa-baby","fa-solid fa-baby-carriage","fa-solid fa-backward","fa-solid fa-backward-fast","fa-solid fa-backward-step","fa-solid fa-bacon","fa-solid fa-bacteria","fa-solid fa-bacterium","fa-solid fa-bag-shopping","fa-solid fa-bahai","fa-solid fa-baht-sign","fa-solid fa-ban","fa-solid fa-bandage","fa-solid fa-bangladeshi-taka-sign","fa-solid fa-ban-smoking","fa-solid fa-barcode","fa-solid fa-bars","fa-solid fa-bars-progress","fa-solid fa-bars-staggered","fa-solid fa-arrow-pointer","fa-solid fa-arrow-right","fa-solid fa-arrow-right-arrow-left","fa-solid fa-arrow-right-from-bracket","fa-solid fa-arrow-right-long","fa-solid fa-arrow-right-to-bracket","fa-solid fa-arrow-right-to-city","fa-solid fa-arrow-rotate-left","fa-solid fa-arrow-rotate-right","fa-solid fa-arrows-down-to-line","fa-solid fa-arrows-down-to-people","fa-solid fa-arrows-left-right","fa-solid fa-arrows-left-right-to-line","fa-solid fa-arrows-rotate","fa-solid fa-arrows-spin","fa-solid fa-arrows-split-up-and-left","fa-solid fa-arrow-trend-down","fa-solid fa-arrow-trend-up","fa-solid fa-arrow-turn-down","fa-solid fa-arrow-turn-up","fa-solid fa-arrow-up","fa-solid fa-arrow-up-1-9","fa-solid fa-arrow-up-9-1","fa-solid fa-arrow-up-a-z","fa-solid fa-arrow-up-from-bracket","fa-solid fa-arrow-up-from-ground-water","fa-solid fa-arrow-up-from-water-pump","fa-solid fa-arrow-up-long","fa-solid fa-arrow-up-right-dots","fa-solid fa-arrow-up-right-from-square","fa-solid fa-arrow-up-short-wide","fa-solid fa-arrow-up-wide-short","fa-solid fa-arrow-up-z-a","fa-solid fa-address-book","fa-solid fa-address-card","fa-solid fa-align-center","fa-solid fa-align-justify","fa-solid fa-align-left","fa-solid fa-align-right","fa-solid fa-anchor","fa-solid fa-anchor-circle-check","fa-solid fa-anchor-circle-exclamation","fa-solid fa-anchor-circle-xmark","fa-solid fa-anchor-lock","fa-solid fa-angle-down","fa-solid fa-angle-left","fa-solid fa-angle-right","fa-solid fa-angles-down","fa-solid fa-angles-left","fa-solid fa-angles-right","fa-solid fa-angles-up","fa-solid fa-angle-up","fa-solid fa-ankh","fa-solid fa-apple-whole","fa-solid fa-archway","fa-solid fa-arrow-down","fa-solid fa-arrow-down-1-9","fa-solid fa-arrow-down-9-1","fa-solid fa-arrow-down-a-z","fa-solid fa-arrow-down-long","fa-solid fa-arrow-down-short-wide","fa-solid fa-arrow-down-up-across-line","fa-solid fa-arrow-down-up-lock","fa-solid fa-arrow-down-wide-short","fa-solid fa-arrow-down-z-a","fa-solid fa-arrow-left","fa-solid fa-arrow-left-long","fa-solid fa-0","fa-solid fa-1","fa-solid fa-2","fa-solid fa-3","fa-solid fa-4","fa-solid fa-5","fa-solid fa-6","fa-solid fa-7","fa-solid fa-8","fa-solid fa-9","fa-solid fa-a","fa-solid fa-wine-glass","fa-solid fa-wine-glass-empty","fa-solid fa-won-sign",
        ];
        if( $type == 'social' ) :
            $social_icon = ["fa-solid fa-envelope", "fa-regular fa-envelope"];
            if( ! empty( $fontawesome_icons ) && is_array( $fontawesome_icons ) ) :
                foreach( $fontawesome_icons as $icon ) :
                    $icon_class_array = explode( ' ', $icon );
                    if( in_array( 'fa-brands', $icon_class_array ) ) :
                        $social_icon[] = $icon;
                    endif;
                endforeach;
            endif;
            return $social_icon;
        else :
            return $fontawesome_icons;
        endif;
    }
endif;

require get_template_directory() . '/inc/customizer/handlers.php'; // customizer handlers
require get_template_directory() . '/inc/customizer/selective-refresh.php'; // selective refresh
require get_template_directory() . '/inc/customizer/sanitize-functions.php'; // sanitize functions
require get_template_directory() . '/inc/customizer/customizer-up.php'; // customizer up