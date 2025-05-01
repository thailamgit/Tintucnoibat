<?php
/**
 * Includes sanitize functions
 * 
 * @package Newsis
 * @since 1.0.0
 */
if( !function_exists( 'newsis_sanitize_toggle_control' )  ) :
    /**
     * Sanitize customizer toggle control value
     * 
     * @package Newsis
     * @since 1.0.0
     */
    function newsis_sanitize_toggle_control($value) {
        return rest_sanitize_boolean( $value );
    }
 endif;

 if( !function_exists( 'newsis_sanitize_url' )  ) :
    /**
     * Sanitize customizer url control value
     * 
     * @package Newsis
     * @since 1.0.0
     */
    function newsis_sanitize_url($value) {
        return esc_url_raw($value);
    }
 endif;

 if( !function_exists( 'newsis_sanitize_select_control' )  ) :
    /**
     * Sanitize customizer select control value
     * 
     * @package Newsis
     * @since 1.0.0
     */
    function newsis_sanitize_select_control( $input, $setting ) {
        // Ensure input is a slug.
        $input = sanitize_key( $input );
        // Get list of choices from the control associated with the setting.
        $choices = $setting->manager->get_control( $setting->id )->choices;
        // If the input is a valid key, return it; otherwise, return the default.
        return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
    }
endif;

if( !function_exists( 'newsis_sanitize_number_range' )  ) :
    /**
     * Sanitize range slider control value
     * 
     * @package Newsis
     * @since 1.0.0
     */
    function newsis_sanitize_number_range($number, $setting) {
        // Ensure input is an absolute integer.
        $number = absint($number);
        // Get the input attributes associated with the setting.
        $atts = $setting->manager->get_control($setting->id)->input_attrs;
        // Get minimum number in the range.
        $min = ( isset($atts['min']) ? $atts['min'] : $number );
        // Get maximum number in the range.
        $max = ( isset($atts['max']) ? $atts['max'] : $number );
        // Get step.
        $step = ( isset($atts['step']) ? $atts['step'] : 1 );
        // If the number is within the valid range, return it; otherwise, return the default
        return ( $min <= $number && $number <= $max && is_int($number / $step) ? $number : $setting->default );
    }
endif;

if( !function_exists( 'newsis_sanitize_responsive_range' )  ) :
    /**
     * Sanitize range slider control value
     * 
     * @package Newsis
     * @since 1.0.0
     */
    function newsis_sanitize_responsive_range($range, $setting) {
        // Ensure input is an absolute integer.
        foreach( $range as $rangKey => $rang ) :
            $range[$rangKey] = is_numeric( $rang ) ? $rang: 0;
        endforeach;
        // Get the input attributes associated with the setting.
        $atts = $setting->manager->get_control($setting->id)->input_attrs;

        // Get minimum number in the range.
        $min = ( isset($atts['min']) ? $atts['min'] : $number );
        // Get maximum number in the range.
        $max = ( isset($atts['max']) ? $atts['max'] : $number );
        // Get step.
        $step = ( isset($atts['step']) ? $atts['step'] : 1 );

        // If the number is within the valid range, return it; otherwise, return the default
        return ( ( $min <= $range['smartphone'] && $range['smartphone'] <= $max && is_numeric($range['smartphone'] / $step) && ( $min <= $range['tablet'] && $range['tablet'] <= $max && is_numeric($range['tablet'] / $step) ? $range : $setting->default ) && ( $min <= $range['desktop'] && $range['desktop'] <= $max && is_numeric($range['desktop'] / $step) ? $range : $setting->default ) ) ? $range : $setting->default );
    }
endif;

if( !function_exists( 'newsis_sanitize_array' )  ) :
    /**
     * Sanitize array control value
     * 
     * @package Newsis
     * @since 1.0.0
     */ 
    function newsis_sanitize_array( $value ) {
        return wp_unslash( $value );
    }
 endif;

 if( !function_exists( 'newsis_sanitize_responsive_multiselect_control' )  ) :
    /**
     * Sanitize responsive multiselect control value
     * 
     * @package Newsis
     * @since 1.0.0
     */
    function newsis_sanitize_responsive_multiselect_control( $value ) {
        if( ! is_array( $value ) ) return array("desktop"=> true, "tablet"=> true, "mobile"=> true);
        $value["desktop"] = ! isset( $value["desktop"] ) ? true : rest_sanitize_boolean( $value["desktop"] );
        $value["tablet"] = ! isset( $value["tablet"] ) ? true : rest_sanitize_boolean( $value["tablet"] );
        $value["mobile"] = ! isset( $value["mobile"] ) ? true : rest_sanitize_boolean( $value["mobile"] );
        return $value;
    }
 endif;

 if( !function_exists( 'newsis_sanitize_color_picker_control' )  ) :
    /**
     * Sanitize color value
     * 
     * @package Newsis
     * @since 1.0.0
     */
    function newsis_sanitize_color_picker_control($color,$setting) {
        if( sanitize_hex_color($color) ) { // 3 or 6 hex digits, or the empty string.
            return $color;
        } else if ( preg_match( '|^#([A-Fa-f0-9]{8})|', $color ) ) { // 8 hex digits, or the empty string.
            return $color;
        } else if (strlen($color) > 8 && substr( $color, 0, 22 ) === "--newsis-global-preset") {
			return $color;
		} else {
            $setting->default;
        }
    }
 endif;

 if( !function_exists( 'newsis_sanitize_color_group_picker_control' )  ) :
    /**
     * Sanitize color group value
     * 
     * @package Newsis
     * @since 1.0.0
     */
    function newsis_sanitize_color_group_picker_control($color, $setting) {
        if( ! isset( $color['color'] ) && ! isset( $color['hover'] ) ) return apply_filters( NEWSIS_PREFIX . 'filtered_color_group_picker_control_value', $setting->default );
        if( $color['color'] == null && $color['hover'] == null ) return apply_filters( NEWSIS_PREFIX . 'filtered_color_group_picker_control_value', $color );

        foreach( $color as $colorKey => $colorValue ) {
            $color[$colorKey]  = ( $colorValue == null ) ? $colorValue : newsis_sanitize_color_picker_control($colorValue, (object) ['default'=>$colorValue]);
        }
        return apply_filters( NEWSIS_PREFIX . 'filtered_color_group_picker_control_value', $color );
    }
 endif;

 if( !function_exists( 'newsis_sanitize_box_control' )  ) :
    /**
     * Sanitize box control value
     * 
     * @package Newsis
     * @since 1.0.0
     */
    function newsis_sanitize_box_control($box,$setting) {
        if( ! is_array( $box ) ) return apply_filters( NEWSIS_PREFIX . 'filtered_box_control_value', $setting->default );
        return apply_filters( NEWSIS_PREFIX . 'filtered_box_control_value', $box );
    }
 endif;

 if( !function_exists( 'newsis_sanitize_color_image_group_control' )  ) :
    /**
     * Sanitize color group image control value
     * 
     * @package Newsis
     * @since 1.0.0
     */
    function newsis_sanitize_color_image_group_control($value,$setting) {
        return apply_filters( NEWSIS_PREFIX . 'color_image_group_control_value', wp_kses_post($value) );
    }
 endif;

 if( !function_exists( 'newsis_sanitize_repeater_control' )  ) :
    /**
     * Sanitize color group image control value
     * 
     * @package Newsis
     * @since 1.0.0
     */
    function newsis_sanitize_repeater_control($value,$setting) {
        return apply_filters( NEWSIS_PREFIX . 'repeater_control_value', wp_kses_post($value) );
    }
 endif;

 if( !function_exists( 'newsis_sanitize_sortable_control' )  ) :
    /**
     * Sanitize sortable control value
     * 
     * @package Newsis
     * @since 1.0.0
     */
    function newsis_sanitize_sortable_control($box,$setting) {
        if( ! is_array( $box ) ) return apply_filters( NEWSIS_PREFIX . 'sortable_control_value', $setting->default );
        return apply_filters( NEWSIS_PREFIX . 'sortable_control_value', $box );
    }
 endif;

 if( !function_exists( 'newsis_sanitize_custom_text_control' )  ) :
    /**
     * Sanitize custom text value
     * 
     * @package Newsis
     * @since 1.0.0
     */
    function newsis_sanitize_custom_text_control($control, $setting) {
        if( ! isset( $control['icon'] ) || ! isset( $control['text'] ) || ! is_array( $control ) ) return apply_filters( NEWSIS_PREFIX . 'custom_text_control_value', $setting->default );

        foreach( $control as $controlKey => $controlValue ) {
            $control[$controlKey]  = esc_html( $controlValue );
        }
        return apply_filters( NEWSIS_PREFIX . 'custom_text_control_value', $control );
    }
 endif;

 if( !function_exists( 'newsis_sanitize_custom_icon_picker_control' )  ) :
    /**
     * Sanitize custom icon value
     * 
     * @package Newsis
     * @since 1.0.0
     */
    function newsis_sanitize_custom_icon_picker_control($control, $setting) {
        return apply_filters( NEWSIS_PREFIX . 'custom_icon_picker_control_value', esc_html($control) );
    }
 endif;

 if( !function_exists( 'newsis_sanitize_get_responsive_integer_value' )  ) :
    /**
     * Sanitize number value
     * 
     * @package Newsis
     * @since 1.0.0
     */
    function newsis_sanitize_get_responsive_integer_value($value) {
        $value['desktop'] = isset( $value['desktop'] ) ? $value['desktop'] : 0;
        $value['tablet'] = isset( $value['tablet'] ) ? $value['tablet'] : 0;
        $value['smartphone'] = isset( $value['smartphone'] ) ? $value['smartphone'] : 0;
        return apply_filters( NEWSIS_PREFIX . 'custom_responsive_integer_value', $value );
    }
 endif;

 if( !function_exists( 'newsis_sanitize_typo_control' )  ) :
    /**
     * Sanitize typo value
     * 
     * @package Newsis
     * @since 1.0.0
     */
    function newsis_sanitize_typo_control($control,$setting) {
        $control['font_family']['value'] = isset( $control['font_family']['value'] ) ? esc_html($control['font_family']['value']) : $setting->default['font_family']['value'];
        $control['font_weight']['value'] = isset( $control['font_weight']['value'] ) ? esc_html($control['font_weight']['value']) : '400';
        $control['font_size'] = isset( $control['font_size'] ) ? newsis_sanitize_get_responsive_integer_value($control['font_size']) : $setting->default['font_size'];
        $control['line_height'] = isset( $control['line_height'] ) ? newsis_sanitize_get_responsive_integer_value($control['line_height']) : $setting->default['line_height'];
        $control['letter_spacing'] = isset( $control['letter_spacing'] ) ? newsis_sanitize_get_responsive_integer_value($control['letter_spacing']) : $setting->default['letter_spacing'];
        if( isset( $control['text_transform'] ) ) {
            $control['text_transform'] = in_array( $control['text_transform'], ['unset','capitalize','uppercase','lowercase'] ) ? esc_html($control['text_transform']) : 'capitalize';
        } else {
            $control['text_transform'] = $setting->default['text_transform'];
        }
        if( isset( $control['text_decoration'] ) ) {
            $control['text_decoration'] = in_array( $control['text_decoration'], ['none','underline','line-through'] ) ? esc_html($control['text_decoration']) : 'none';
        } else {
            $control['text_decoration'] = $setting->default['text_decoration'];
        }
        return apply_filters( NEWSIS_PREFIX . 'typo_control_value', $control );
    }
 endif;

 if( !function_exists( 'newsis_sanitize_box_shadow_control' )  ) :
    /**
     * Sanitize box shadow value
     * 
     * @package Newsis
     * @since 1.0.0
     */
    function newsis_sanitize_box_shadow_control($control,$setting) {
        $control['option'] = isset( $control['option'] ) ? esc_html($control['option']) : $setting->default['option'];
        $control['type'] = isset( $control['type'] ) ? esc_html($control['type']) : $setting->default['type'];
        $control['hoffset'] = isset( $control['hoffset'] ) ? $control['hoffset'] : $setting->default['hoffset'];
        $control['voffset'] = isset( $control['voffset'] ) ? $control['voffset'] : $setting->default['voffset'];
        $control['blur'] = isset( $control['blur'] ) ? $control['blur'] : $setting->default['blur'];
        $control['spread'] = isset( $control['spread'] ) ? $control['spread'] : $setting->default['spread'];
        return apply_filters( NEWSIS_PREFIX . 'box_shadow_control_value', $control );
    }
 endif;

 if( !function_exists( 'newsis_sanitize_css_code_control' )  ) :
    /**
     * Sanitize css code control value
     * 
     * @package Newsis
     * @since 1.0.0
     */
    function newsis_sanitize_css_code_control( $css ) {
        if ( preg_match( '#</?\w+#', $css ) ) {
            return '';
        }
        return $css;
    }
 endif;

 if( ! function_exists( 'newsis_sanitize_icon_picker_control' )  ) :
    /**
     * Sanitize array icon picker control value
     * 
     * @package Newsis
     * @since 1.0.0
     */ 
    function newsis_sanitize_icon_picker_control( $value ) {
        $unslashed_value = wp_unslash( $value );
        if( ! in_array( $unslashed_value['type'], ['icon','svg','none'] ) ) {
            $unslashed_value['type'] = 'none';
            $unslashed_value['value'] = '';
        }
        return $unslashed_value;
    }
 endif;

 /**
 * Function to sanitize responsive spacing control
 * 
 * @package Newsis
 * @since 1.0.0
 */

if( ! function_exists( 'newsis_sanitize_spacing_control' ) ) :
    function newsis_sanitize_spacing_control( $value, $setting ) {
        if( ! is_array( $value ) ) return $settings->default;
        // for desktop
        $value['desktop']['top'] = isset( $value['desktop']['top'] ) && is_int( $value['desktop']['top'] ) ? $value['desktop']['top'] : $setting->default['desktop']['top'];
        $value['desktop']['right'] = isset( $value['desktop']['right'] ) && is_int( $value['desktop']['right'] ) ? $value['desktop']['right'] : $setting->default['desktop']['right'];
        $value['desktop']['bottom'] = isset( $value['desktop']['bottom'] ) && is_int( $value['desktop']['bottom'] ) ? $value['desktop']['bottom'] : $setting->default['desktop']['bottom'];
        $value['desktop']['left'] = isset( $value['desktop']['left'] ) && is_int( $value['desktop']['left'] ) ? $value['desktop']['left'] : $setting->default['desktop']['left'];
        $value['desktop']['link'] = isset( $value['desktop']['link'] ) && is_bool( $value['desktop']['link'] ) ? $value['desktop']['link'] : $setting->default['desktop']['link'];
        // for tablet
        $value['tablet']['top'] = isset( $value['tablet']['top'] ) && is_int( $value['tablet']['top'] ) ? $value['tablet']['top'] : $setting->default['tablet']['top'];
        $value['tablet']['right'] = isset( $value['tablet']['right'] ) && is_int( $value['tablet']['right'] ) ? $value['tablet']['right'] : $setting->default['tablet']['right'];
        $value['tablet']['bottom'] = isset( $value['tablet']['bottom'] ) && is_int( $value['tablet']['bottom'] ) ? $value['tablet']['bottom'] : $setting->default['tablet']['bottom'];
        $value['tablet']['left'] = isset( $value['tablet']['left'] ) && is_int( $value['tablet']['left'] ) ? $value['tablet']['left'] : $setting->default['tablet']['left'];
        $value['tablet']['link'] = isset( $value['tablet']['link'] ) && is_bool( $value['tablet']['link'] ) ? $value['tablet']['link'] : $setting->default['tablet']['link'];
        // for smartphone
        $value['smartphone']['top'] = isset( $value['smartphone']['top'] ) && is_int( $value['smartphone']['top'] ) ? $value['smartphone']['top'] : $setting->default['smartphone']['top'];
        $value['smartphone']['right'] = isset( $value['smartphone']['right'] ) && is_int( $value['smartphone']['right'] ) ? $value['smartphone']['right'] : $setting->default['smartphone']['right'];
        $value['smartphone']['bottom'] = isset( $value['smartphone']['bottom'] ) && is_int( $value['smartphone']['bottom'] ) ? $value['smartphone']['bottom'] : $setting->default['smartphone']['bottom'];
        $value['smartphone']['left'] = isset( $value['smartphone']['left'] ) && is_int( $value['smartphone']['left'] ) ? $value['smartphone']['left'] : $setting->default['smartphone']['left'];
        $value['smartphone']['link'] = isset( $value['smartphone']['link'] ) && is_bool( $value['smartphone']['link'] ) ? $value['smartphone']['link'] : $setting->default['smartphone']['link'];

        return $value;
    }
 endif;