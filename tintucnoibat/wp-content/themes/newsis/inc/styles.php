<?php
/**
 * Includes the inline css
 * 
 * @package Newsis
 * @since 1.0.0
 */
use Newsis\CustomizerDefault as NI;
if( ! function_exists( 'newsis_assign_preset_var' ) ) :
   /**
   * Generate css code for top header color options
   *
   * @package Newsis
   * @since 1.0.0 
   */
   function newsis_assign_preset_var( $selector, $control) {
         $decoded_control =  NI\newsis_get_customizer_option( $control );
         if( ! $decoded_control ) return;
         echo " body.newsis_font_typography{ " . $selector . ": ".esc_html( $decoded_control ).  ";}\n";
   }
endif;

if( ! function_exists( 'newsis_get_background_style' ) ) :
   /**
    * Generate css code for background control.
    *
    * @package Newsis
    * @since 1.0.0 
    */
   function newsis_get_background_style( $selector, $control, $var = '' ) {
      $decoded_control = json_decode( NI\newsis_get_customizer_option( $control ), true );
      if( ! $decoded_control ) return;
      if( isset( $decoded_control['type'] ) ) :
         $type = $decoded_control['type'];
         switch( $type ) {
            case 'image' : if( isset( $decoded_control[$type]['media_id'] ) ) echo $selector . " { background-image: url(" .esc_url( wp_get_attachment_url( $decoded_control[$type]['media_id'] ) ). ") }\n";
                  if( isset( $decoded_control['repeat'] ) ) echo $selector . "{ background-repeat: " .esc_html( $decoded_control['repeat'] ). "}\n";
                  if( isset( $decoded_control['position'] ) ) echo $selector . "{ background-position:" .esc_html( $decoded_control['position'] ). "}\n";
                  if( isset( $decoded_control['attachment'] ) ) echo $selector . "{ background-attachment: " .esc_html( $decoded_control['attachment'] ). "}\n";
                  if( isset( $decoded_control['size'] ) ) echo $selector . "{ background-size: " .esc_html( $decoded_control['size'] ). "}\n";
               break;
            default: if( isset( $decoded_control[$type] ) ) echo $selector . "{ background: " .newsis_get_color_format( $decoded_control[$type] ). "}\n";
         }
      endif;
   }
endif;

if( ! function_exists( 'newsis_get_background_style_var' ) ) :
   /**
    * Generate css code for background control.
    *
    * @package Newsis
    * @since 1.0.0 
    */
   function newsis_get_background_style_var( $selector, $control) {
      $decoded_control = json_decode( NI\newsis_get_customizer_option( $control ), true );
      if( ! $decoded_control ) return;
      if( isset( $decoded_control['type'] ) ) :
         $type = $decoded_control['type'];
         if( isset( $decoded_control[$type] ) ) echo ".newsis_main_body { ".$selector.": " .newsis_get_color_format( $decoded_control[$type] ). "}\n";
      endif;
   }
endif;

if( ! function_exists( 'newsis_get_background_style_responsive' ) ) :
   /**
    * Generate css code for background control.
    *
    * @package Newsis
    * @since 1.0.0 
    */
   function newsis_get_background_style_responsive( $selector, $control, $var = '' ) {
      $decoded_control = json_decode( NI\newsis_get_customizer_option( $control ), true );
      if( ! $decoded_control ) return;
      if( isset( $decoded_control['type'] ) ) :
         $type = $decoded_control['type'];
         switch( $type ) {
            case 'image' : if( isset( $decoded_control[$type]['media_id'] ) ) echo $selector . " { background-image: url(" .esc_url( wp_get_attachment_url( $decoded_control[$type]['media_id'] ) ). ") }\n";
                  if( isset( $decoded_control['repeat'] ) ) echo $selector . "{ background-repeat: " .esc_html( $decoded_control['repeat'] ). "}\n";
                  if( isset( $decoded_control['position'] ) ) echo $selector . "{ background-position:" .esc_html( $decoded_control['position'] ). "}\n";
                  if( isset( $decoded_control['attachment'] ) ) echo $selector . "{ background-attachment: " .esc_html( $decoded_control['attachment'] ). "}\n";
                  if( isset( $decoded_control['size'] ) ) echo $selector . "{ background-size: " .esc_html( $decoded_control['size'] ). "}\n";
               break;
            default: if( isset( $decoded_control[$type] ) ) echo "@media(max-width: 768px){ ". $selector . "{ background: " .newsis_get_color_format( $decoded_control[$type] ). "} }\n";
         }
      endif;
   }
endif;

if( ! function_exists( 'newsis_get_typo_style' ) ) :
   /**
   * Generate css code for typography control.
   *
   * @package Newsis
   * @since 1.0.0 
   */
   function newsis_get_typo_style( $selector, $control ) {
      $decoded_control = NI\newsis_get_customizer_option( $control );
      if( ! $decoded_control ) return;
      if( isset( $decoded_control['font_family'] ) ) :
         echo ".newsis_font_typography { ".$selector."-family : " .esc_html( $decoded_control['font_family']['value'] ).  "; }\n";
      endif;

      if( isset( $decoded_control['font_weight'] ) ) :
         echo ".newsis_font_typography { ".$selector."-weight : " .esc_html( $decoded_control['font_weight']['value'] ).  "; }\n";
      endif;

      if( isset( $decoded_control['text_transform'] ) ) :
         echo ".newsis_font_typography { ".$selector."-texttransform : " .esc_html( $decoded_control['text_transform'] ).  "; }\n";
      endif;

      if( isset( $decoded_control['text_decoration'] ) ) :
         echo ".newsis_font_typography { ".$selector."-textdecoration : " .esc_html( $decoded_control['text_decoration'] ).  "; }\n";
      endif;

      if( isset( $decoded_control['font_size'] ) ) :
         if( isset( $decoded_control['font_size']['desktop'] ) ) echo ".newsis_font_typography { ".$selector."-size : " .absint( $decoded_control['font_size']['desktop'] ).  "px; }\n";
         if( isset( $decoded_control['font_size']['tablet'] ) ) echo ".newsis_font_typography { ".$selector."-size-tab : " .absint( $decoded_control['font_size']['tablet'] ).  "px; }\n";
         if( isset( $decoded_control['font_size']['smartphone'] ) ) echo ".newsis_font_typography { ".$selector."-size-mobile : " .absint( $decoded_control['font_size']['smartphone'] ).  "px; }\n";
      endif;
      if( isset( $decoded_control['line_height'] ) ) :
         if( isset( $decoded_control['line_height']['desktop'] ) ) echo ".newsis_font_typography { ".$selector."-lineheight : " .absint( $decoded_control['line_height']['desktop'] ).  "px; }\n";
         if( isset( $decoded_control['line_height']['tablet'] ) ) echo ".newsis_font_typography { ".$selector."-lineheight-tab : " .absint( $decoded_control['line_height']['tablet'] ).  "px; }\n";
         if( isset( $decoded_control['line_height']['smartphone'] ) ) echo ".newsis_font_typography { ".$selector."-lineheight-mobile : " .absint( $decoded_control['line_height']['smartphone'] ).  "px; }\n";
      endif;
      if( isset( $decoded_control['letter_spacing'] ) ) :
         if( isset( $decoded_control['letter_spacing']['desktop'] ) ) echo ".newsis_font_typography { ".$selector."-letterspacing : " .absint( $decoded_control['letter_spacing']['desktop'] ).  "px; }\n";
         if( isset( $decoded_control['letter_spacing']['tablet'] ) ) echo ".newsis_font_typography { ".$selector."-letterspacing-tab : " .absint( $decoded_control['letter_spacing']['tablet'] ).  "px; }\n";
         if( isset( $decoded_control['letter_spacing']['smartphone'] ) ) echo ".newsis_font_typography { ".$selector."-letterspacing-mobile : " .absint( $decoded_control['letter_spacing']['smartphone'] ).  "px; }\n";
      endif;
   }
endif;

if( ! function_exists( 'newsis_site_logo_width_fnc' ) ) :
   /**
   * Generate css code for Logo Width
   *
   * @package Newsis
   * @since 1.0.0 
   */
   function newsis_site_logo_width_fnc( $selector, $control, $property = 'width'  ) {
      $decoded_control = NI\newsis_get_customizer_option( $control );
      if( ! $decoded_control ) return;
      if( isset( $decoded_control['desktop'] ) ) :
         $desktop = $decoded_control['desktop'];
         echo $selector . "{ " . esc_html( $property ). ": ".esc_html( $desktop ).  "px; }\n";
         endif;
         if( isset( $decoded_control['tablet'] ) ) :
         $tablet = $decoded_control['tablet'];
         echo "@media(max-width: 940px) { " .$selector . "{ " . esc_html( $property ). ": ".esc_html( $tablet ).  "px; } }\n";
         endif;
         if( isset( $decoded_control['smartphone'] ) ) :
         $smartphone = $decoded_control['smartphone'];
         echo "@media(max-width: 610px) { " .$selector . "{ " . esc_html( $property ). ": ".esc_html($smartphone).  "px; } }\n";
      endif;
   }
endif;

if( ! function_exists( 'newsis_top_border_color' ) ) :
   /**
    * Generate css code for top header color options
    *
    * @package Newsis
    * @since 1.0.0 
    */
   function newsis_top_border_color( $selector, $control, $property = 'border-color' ) {
      $decoded_control = json_decode( NI\newsis_get_customizer_option( $control ), true );
      if( ! $decoded_control ) return;
      if( isset( $decoded_control['type'] ) ) :
         $type = $decoded_control['type'];
         if( isset( $decoded_control[$type] ) ) echo $selector . "{ " . esc_html( $property ). ": ".esc_html( $decoded_control[$type] ).  "}\n";
         if($type == 'solid'){
            echo $selector . "{ border-color: ". newsis_get_color_format( $decoded_control[$type] ) .";}\n";
            echo $selector . " li{ border-color: ". newsis_get_color_format( $decoded_control[$type] ) .";}\n";
         }
         if($type == 'gradient') echo $selector . " li{ border: none;}\n";
      endif;
   }
endif;

if( ! function_exists( 'newsis_color_options_one' ) ) :
   /**
    * Generate css code for Top header Text Color
    *
    * @package Newsis
    * @since 1.0.0 
    */
   function newsis_color_options_one( $selector, $control, $property = 'color' ) {
         $decoded_control =  NI\newsis_get_customizer_option( $control );
         if( ! $decoded_control ) return;
         echo $selector . " { " . esc_html( $property ). ": ".newsis_get_color_format($decoded_control ).  " }\n";
   }
endif;

if( ! function_exists( 'newsis_text_color_var' ) ) :
   /**
    * Generate css code for top header color options
    *
    * @package Newsis
    * @since 1.0.0 
    */
   function newsis_text_color_var( $selector, $control) {
      $decoded_control =  NI\newsis_get_customizer_option( $control );
      if( ! $decoded_control ) return;
      if( isset( $decoded_control['color'] ) ) :
         $color = $decoded_control['color'];
         echo ".newsis_font_typography  { " . $selector . ": ".newsis_get_color_format($color).  ";}\n";
      endif;
      if( isset( $decoded_control['hover'] ) ) :
         $color_hover = $decoded_control['hover'];
         echo ".newsis_font_typography  { " . $selector . "-hover : ".newsis_get_color_format($color_hover).  "; }\n";
      endif;
   }
endif;

if( ! function_exists('newsis_visibility_options') ):
   /**
    * Generate css code for top header color options
    *
    * @package Newsis
    * @since 1.0.0 
    */
   function newsis_visibility_options( $selector, $control ) {
      $decoded_control =  NI\newsis_get_customizer_option( $control );
      if( ! $decoded_control ) return;
      if( isset( $decoded_control['desktop'] ) ) :
         if($decoded_control['desktop'] == false) echo $selector . "{ display : none;}\n";
      endif;

      if( isset( $decoded_control['tablet'] ) ) :
         if($decoded_control['tablet'] == false) echo "@media(max-width: 940px) and (min-width:611px) { " .$selector . "{ display : none;} }\n";
      endif;

      if( isset( $decoded_control['mobile'] ) ) :
         if($decoded_control['mobile'] == false) { 
            echo "@media(max-width: 610px) { " .$selector . "{ display : none;} }\n";
         }
         if($decoded_control['mobile'] == true){
            echo "@media(max-width: 610px) { " .$selector . "{ display : block;} }\n";
         }
      endif;
   }
endif;

if( ! function_exists( 'newsis_theme_color' ) ) :
   /**
    * Generate css code for top header color options
    *
    * @package Newsis
    * @since 1.0.0 
    */
   function newsis_theme_color( $selector, $control) {
      $decoded_control =  NI\newsis_get_customizer_option( $control );
      if( ! $decoded_control ) return;
      echo " body.newsis_main_body{ " . $selector . ": ".newsis_get_color_format($decoded_control).  ";}\n";
      echo " body.newsis_dark_mode{ " . $selector . ": ".newsis_get_color_format($decoded_control).  ";}\n";
   }
endif;

if( ! function_exists( 'newsis_header_padding' ) ) :
   /**
    * Generate css code for Top header Text Color
    *
    * @package Newsis
    * @since 1.0.0 
    */
   function newsis_header_padding( $selector, $control ) {
      $decoded_control = NI\newsis_get_customizer_option( $control );
      if( ! $decoded_control ) return;

      if( isset( $decoded_control['desktop'] ) ) :
         echo ".newsis_font_typography { ".$selector.": ". $decoded_control['desktop'] ."px;}\n";
      endif;

      if( isset( $decoded_control['tablet'] ) ) :
         echo " .newsis_font_typography { ".$selector."-tablet: ". $decoded_control['tablet'] ."px;}\n";
      endif;

      if( isset( $decoded_control['smartphone'] ) ) :
         echo " .newsis_font_typography { ".$selector."-smartphone: ". $decoded_control['smartphone'] ."px;}\n";
      endif;
   }
endif;

if( ! function_exists( 'newsis_font_size_style' ) ) :
   /**
    * Generates css code for font size
    *
    * @package Newsis
    * @since 1.0.0
    */
   function newsis_font_size_style( $selector, $control ) {
      $decoded_control = NI\newsis_get_customizer_option( $control );
      if( ! $decoded_control ) return;
      if( isset( $decoded_control['desktop'] ) ) :
         $desktop = $decoded_control['desktop'];
         echo "body.newsis_main_body{ " . $selector . ": ".esc_html( $desktop ).  "px;}\n";
      endif;
      if( isset( $decoded_control['tablet'] ) ) :
         $tablet = $decoded_control['tablet'];
         echo "body.newsis_main_body{ " . $selector . "-tablet: ".esc_html( $tablet ).  "px;}\n";
      endif;
      if( isset( $decoded_control['smartphone'] ) ) :
         $smartphone = $decoded_control['smartphone'];
         echo "body.newsis_main_body{ " . $selector . "-smartphone: ".esc_html( $smartphone ).  "px;}\n";
      endif;
   }
endif;

if( ! function_exists( 'newsis_category_colors_styles' ) ) :
   /**
    * Generates css code for font size
    *
    * @package Newsis
    * @since 1.0.0
    */
   function newsis_category_colors_styles() {
      $totalCats = get_categories();
      if( $totalCats ) :
         foreach( $totalCats as $singleCat ) :
            $category_color = NI\newsis_get_customizer_option( 'category_' .absint($singleCat->term_id). '_color' );
            echo "body .post-categories .cat-item.cat-" . absint($singleCat->term_id) . " { background-color : " .newsis_get_color_format( $category_color['color'] ). "}\n";
            echo "body .post-categories .cat-item.cat-" . absint($singleCat->term_id) . ":hover { background-color : " .newsis_get_color_format( $category_color['hover'] ). "}\n";
            echo "body .newsis-category-no-bk .post-categories .cat-item.cat-" . absint($singleCat->term_id) . " a { color : " .newsis_get_color_format( $category_color['color'] ). "}\n";
            echo "body .newsis-category-no-bk .post-categories .cat-item.cat-" . absint($singleCat->term_id) . " a:hover { color : " .newsis_get_color_format( $category_color['hover'] ). ";}\n";
         endforeach;
      endif;
   }
endif;

if( ! function_exists( 'newsis_image_ratio_variable' ) ) :
   /**
    * Generate css code for variable change with responsive
    *
    * @package Newsis
    * @since 1.0.0
    */
   function newsis_image_ratio_variable ( $selector, $control ) {
      $decoded_control = NI\newsis_get_customizer_option( $control );
      if( ! $decoded_control ) return;
      if( isset( $decoded_control['desktop'] ) && $decoded_control['desktop'] > 0 ) :
         $desktop = $decoded_control['desktop'];
         echo "body { ". $selector . " : ". $desktop ."}\n";
         endif;
         if( isset( $decoded_control['tablet'] ) && $decoded_control['tablet'] > 0 ) :
         $tablet = $decoded_control['tablet'];
         echo "body { " .$selector . "-tab : ". $tablet . " } }\n";
         endif;
         if( isset( $decoded_control['smartphone'] ) && $decoded_control['smartphone'] > 0 ) :
         $smartphone = $decoded_control['smartphone'];
         echo "body { " .$selector . "-mobile : ". $smartphone .  " }\n";
      endif;
   }
endif;

// box shadow
if( ! function_exists( 'newsis_box_shadow_styles' ) ) :
   /**
    * Generates css code for box shadow
    *
    * @package Newsis
    * @since 1.0.0
    */
   function newsis_box_shadow_styles($selector,$value) {
      $newsis_box_shadow = NI\newsis_get_customizer_option($value);
      if( $newsis_box_shadow['option'] == 'none' ) {
         echo $selector."{ box-shadow: 0px 0px 0px 0px;
         }\n";
      } else {
         if( $newsis_box_shadow['type'] == 'outset') $newsis_box_shadow['type'] = '';
         echo $selector."{ box-shadow : ".esc_html( $newsis_box_shadow['type'] ) ." ".esc_html( $newsis_box_shadow['hoffset'] ).  "px ". esc_html( $newsis_box_shadow['voffset'] ). "px ".esc_html( $newsis_box_shadow['blur'] ).  "px ".esc_html( $newsis_box_shadow['spread'] ).  "px ".newsis_get_color_format( $newsis_box_shadow['color'] ).  ";
         }\n";
      }
   }
endif;

// Value change with responsive
if( ! function_exists( 'newsis_value_change_responsive' ) ) :
   /**
   * Generate css code for variable change with responsive
   *
   * @package Newsis
   * @since 1.0.0 
   */
   function newsis_value_change_responsive ( $selector, $control, $property ) {
      $decoded_control = NI\newsis_get_customizer_option( $control );
      if( ! $decoded_control ) return;
      if( isset( $decoded_control['desktop'] ) ) :
         $desktop = $decoded_control['desktop'];
         echo $selector . "{ " . esc_html( $property ). ": ".esc_html( $desktop ).  "px; }";
      endif;

      if( isset( $decoded_control['tablet'] ) ) :
         $tablet = $decoded_control['tablet'];
         echo "@media(max-width: 940px) { " .$selector . "{ " . esc_html( $property ). ": ".esc_html( $tablet ).  "px; } }\n";
      endif;
      
      if( isset( $decoded_control['smartphone'] ) ) :
         $smartphone = $decoded_control['smartphone'];
         echo "@media(max-width: 610px) { " .$selector . "{ " . esc_html( $property ). ": ".esc_html($smartphone).  "px; } }\n";
   endif;
   }
endif;

// Value change with responsive
if( ! function_exists( 'newsis_color_value_change_responsive' ) ) :
   /**
   * Generate css code for variable change with responsive
   *
   * @package Newsis
   * @since 1.0.0 
   */
   function newsis_color_value_change_responsive ( $selector, $control, $property = 'color' ) {
      $decoded_control = NI\newsis_get_customizer_option( $control );
      if( ! $decoded_control ) return;
      
      if( isset( $decoded_control ) ) :
         echo "@media(max-width: 610px) { " .$selector . "{ " . esc_html( $property ). ": ".esc_html( newsis_get_color_format( $decoded_control ) ).  "; } }\n";
   endif;
   }
endif;