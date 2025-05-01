<?php
/**
 * Includes all the frontpage sections html functions
 * 
 * @package Newsis
 * @since 1.0.0
 */
use Newsis\CustomizerDefault as NI;

if( ! function_exists( 'newsis_main_banner_part' ) ) :
    /**
     * Main Banner element
     * 
     * @since 1.0.0
     */
     function newsis_main_banner_part() {
        $main_banner_option = NI\newsis_get_customizer_option( 'main_banner_option' );
        if( ! $main_banner_option || is_paged() || newsis_is_paged_filtered() ) return;
        $main_banner_layout = NI\newsis_get_customizer_option( 'main_banner_layout' );
        $main_banner_slider_order_by = NI\newsis_get_customizer_option( 'main_banner_slider_order_by' );
        $orderArray = explode( '-', $main_banner_slider_order_by );
        $main_banner_slider_categories = json_decode( NI\newsis_get_customizer_option( 'main_banner_slider_categories' ) );
        $main_banner_args = array(
            'slider_args'  => array(
                'order' => esc_html( $orderArray[1] ),
                'orderby' => esc_html( $orderArray[0] ),
                'ignore_sticky_posts'   => true
            )
        ); 
        $main_banner_args['slider_args']['posts_per_page'] = 6;
        if( NI\newsis_get_customizer_option( 'main_banner_date_filter' ) != 'all' ) $main_banner_args['slider_args']['date_query'] = newsis_get_date_format_array_args(NI\newsis_get_customizer_option( 'main_banner_date_filter' ));
        if( $main_banner_slider_categories ) $main_banner_args['slider_args']['cat'] = newsis_get_categories_for_args($main_banner_slider_categories);
        $main_banner_posts = json_decode(NI\newsis_get_customizer_option( 'main_banner_posts' ));
        if( $main_banner_posts ) $main_banner_args['slider_args']['post__in'] = newsis_get_post_id_for_args($main_banner_posts);
        $main_banner_width_layout = newsis_get_section_width_layout_val('main_banner_width_layout');
        ?>
            <section id="main-banner-section" class="newsis-section <?php echo esc_attr( 'banner-layout--'. $main_banner_layout ); ?> <?php echo esc_attr( 'width-' . $main_banner_width_layout ); ?>">
                <div class="newsis-container">
                    <div class="row">
                        <?php get_template_part( 'template-parts/main-banner/template', esc_html( $main_banner_layout ), $main_banner_args ); ?>
                    </div>
                </div>
            </section>
        <?php
     }
endif;
add_action( 'newsis_main_banner_hook', 'newsis_main_banner_part', 10 );

if( ! function_exists( 'newsis_full_width_blocks_part' ) ) :
    /**
     * Full Width Blocks element
     * 
     * @since 1.0.0
     */
     function newsis_full_width_blocks_part() {
        $full_width_blocks = NI\newsis_get_customizer_option( 'full_width_blocks' );
        if( empty( $full_width_blocks ) || is_paged() || newsis_is_paged_filtered() ) return;
        $full_width_blocks = json_decode( $full_width_blocks );
        if( ! in_array( true, array_column( $full_width_blocks, 'option' ) ) ) {
            return;
        }
        $full_width_blocks_width_layout = newsis_get_section_width_layout_val('full_width_blocks_width_layout');
        ?>
            <section id="full-width-section" class="newsis-section full-width-section <?php echo esc_attr( 'width-' . $full_width_blocks_width_layout ); ?>">
                <div class="newsis-container">
                    <div class="row">
                        <?php
                            foreach( $full_width_blocks as $block ) :
                                if( $block->option ) :
                                    $type = $block->type;
                                    switch($type) {
                                        case 'ad-block' :
                                            newsis_advertisement_block_html( $block, true );
                                                        break;
                                        default: $layout = $block->layout;
                                                $block_query = json_decode( $block->query );
                                                $order = $block_query->order;
                                                $postCategories = $block_query->categories;
                                                $customexclude_ids = $block_query->ids;
                                                $orderArray = explode( '-', $order );
                                                $block_args = array(
                                                    'post_args' => array(
                                                        'post_type' => 'post',
                                                        'order' => esc_html( $orderArray[1] ),
                                                        'orderby' => esc_html( $orderArray[0] ),
                                                        'ignore_sticky_posts'   => true
                                                    ),
                                                    'options'    => $block
                                                );
                                                $offset = isset( $block_query->offset ) ? $block_query->offset: 0;
                                                if( $offset > 0 ) $block_args['post_args']['offset'] = absint($offset);
                                                $block_args['post_args']['posts_per_page'] = absint( $block_query->count );
                                                if( $customexclude_ids ) $block_args['post_args']['post__not_in'] = newsis_get_post_id_for_args( $customexclude_ids );
                                                if( $postCategories ) $block_args['post_args']['cat'] = newsis_get_categories_for_args($postCategories);
                                                if( $block_query->dateFilter != 'all' ) $block_args['post_args']['date_query'] = newsis_get_date_format_array_args($block_query->dateFilter);
                                                if( $block_query->posts ) $block_args['post_args']['post__in'] = newsis_get_post_id_for_args($block_query->posts);
                                                // get template file w.r.t par
                                                $block_args['uniqueID'] = wp_unique_id('newsis-block--');
                                                $style_variables = [
                                                    'unique_id' =>  $block_args['uniqueID'],
                                                    'layout'    =>  $block_args['options']->layout,
                                                    'image_ratio' => $block->imageRatio
                                                ];
                                                ( in_array( $block_args['options']->type, [ 'news-grid', 'news-carousel', 'news-list' ] ) ) ? newsis_get_style_tag( $style_variables ) : newsis_get_style_tag_fb( $style_variables );
                                                get_template_part( 'template-parts/' .esc_html( $type ). '/template', esc_html( $layout ), $block_args );
                                    }
                                endif;
                            endforeach;
                        ?>
                    </div>
                </div>
            </section>
        <?php
     }
     add_action( 'newsis_full_width_blocks_hook', 'newsis_full_width_blocks_part' );
endif;

if( ! function_exists( 'newsis_leftc_rights_blocks_part' ) ) :
    /**
     * Left Content Right Sidebar Blocks element
     * 
     * @since 1.0.0
     */
     function newsis_leftc_rights_blocks_part() {
        $leftc_rights_blocks = NI\newsis_get_customizer_option( 'leftc_rights_blocks' );
        if( empty( $leftc_rights_blocks ) || is_paged() || newsis_is_paged_filtered() ) return;
        $leftc_rights_blocks = json_decode( $leftc_rights_blocks );
        if( ! in_array( true, array_column( $leftc_rights_blocks, 'option' ) ) ) {
            return;
        }
        $leftc_rights_blocks_width_layout = newsis_get_section_width_layout_val('leftc_rights_blocks_width_layout');
        ?>
            <section id="leftc-rights-section" class="newsis-section leftc-rights-section <?php echo esc_attr( 'width-' . $leftc_rights_blocks_width_layout ); ?>">
                <div class="newsis-container">
                    <div class="row">
                        <div class="primary-content">
                            <?php
                                foreach( $leftc_rights_blocks as $block ) :
                                    if( $block->option ) :
                                        $type = $block->type;
                                        switch($type) {
                                            case 'ad-block' : newsis_advertisement_block_html( $block, true );
                                                            break;
                                            default: $layout = $block->layout;
                                                    $block_query = json_decode( $block->query );
                                                    $order = $block_query->order;
                                                    $postCategories = $block_query->categories;
                                                    $customexclude_ids = $block_query->ids;
                                                    $orderArray = explode( '-', $order );
                                                    $block_args = array(
                                                        'post_args' => array(
                                                            'post_type' => 'post',
                                                            'order' => esc_html( $orderArray[1] ),
                                                            'orderby' => esc_html( $orderArray[0] ),
                                                            'ignore_sticky_posts'   => true
                                                        ),
                                                        'options'    => $block
                                                    );
                                                    $offset = isset( $block_query->offset ) ? $block_query->offset: 0;
                                                    if( $offset > 0 ) $block_args['post_args']['offset'] = absint($offset);
                                                    $block_args['post_args']['posts_per_page'] = absint( $block_query->count );
                                                    if( $customexclude_ids ) $block_args['post_args']['post__not_in'] = newsis_get_post_id_for_args( $customexclude_ids );
                                                    if( $postCategories ) $block_args['post_args']['cat'] = newsis_get_categories_for_args($postCategories);
                                                    if( $block_query->dateFilter != 'all' ) $block_args['post_args']['date_query'] = newsis_get_date_format_array_args($block_query->dateFilter);
                                                    if( $block_query->posts ) $block_args['post_args']['post__in'] = newsis_get_post_id_for_args($block_query->posts);
                                                    // get template file w.r.t par
                                                    $block_args['uniqueID'] = wp_unique_id('newsis-block--');
                                                    $style_variables = [
                                                        'unique_id' =>  $block_args['uniqueID'],
                                                        'layout'    =>  $block_args['options']->layout,
                                                        'image_ratio' => $block->imageRatio
                                                    ];
                                                    ( in_array( $block_args['options']->type, [ 'news-grid', 'news-carousel', 'news-list' ] ) ) ? newsis_get_style_tag( $style_variables ) : newsis_get_style_tag_fb( $style_variables );
                                                    get_template_part( 'template-parts/' .esc_html( $type ). '/template', esc_html( $layout ), $block_args );
                                        }
                                    endif;
                                endforeach;
                            ?>
                        </div>
                        <div class="secondary-sidebar">
                            <?php dynamic_sidebar( 'front-right-sidebar' ); ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php
     }
     add_action( 'newsis_leftc_rights_blocks_hook', 'newsis_leftc_rights_blocks_part', 10 );
endif;

if( ! function_exists( 'newsis_lefts_rightc_blocks_part' ) ) :
    /**
     * Left Sidebar Right Content Blocks element
     * 
     * @since 1.0.0
     */
     function newsis_lefts_rightc_blocks_part() {
        $lefts_rightc_blocks = NI\newsis_get_customizer_option( 'lefts_rightc_blocks' );
        if( empty( $lefts_rightc_blocks )|| is_paged() || newsis_is_paged_filtered() ) return;
        $lefts_rightc_blocks = json_decode( $lefts_rightc_blocks );
        if( ! in_array( true, array_column( $lefts_rightc_blocks, 'option' ) ) ) {
            return;
        }
        $lefts_rightc_blocks_width_layout = newsis_get_section_width_layout_val('lefts_rightc_blocks_width_layout');
        ?>
            <section id="lefts-rightc-section" class="newsis-section lefts-rightc-section <?php echo esc_attr( 'width-' . $lefts_rightc_blocks_width_layout ); ?>">
                <div class="newsis-container">
                    <div class="row">
                        <div class="secondary-sidebar">
                            <?php dynamic_sidebar( 'front-left-sidebar' ); ?>
                        </div>
                        <div class="primary-content">
                            <?php
                                foreach( $lefts_rightc_blocks as $block ) :
                                    if( $block->option ) :
                                        $type = $block->type;
                                        switch($type) {
                                            case 'ad-block' : newsis_advertisement_block_html( $block, true );
                                                            break;
                                            default: $layout = $block->layout;
                                                    $block_query = json_decode( $block->query );
                                                    $order = $block_query->order;
                                                    $postCategories = $block_query->categories;
                                                    $customexclude_ids = $block_query->ids;
                                                    $orderArray = explode( '-', $order );
                                                    $block_args = array(
                                                        'post_args' => array(
                                                            'post_type' => 'post',
                                                            'order' => esc_html( $orderArray[1] ),
                                                            'orderby' => esc_html( $orderArray[0] ),
                                                            'ignore_sticky_posts'   => true
                                                        ),
                                                        'options'    => $block
                                                    );
                                                    $offset = isset( $block_query->offset ) ? $block_query->offset: 0;
                                                    if( $offset > 0 ) $block_args['post_args']['offset'] = absint($offset);
                                                    $block_args['post_args']['posts_per_page'] = absint( $block_query->count );
                                                    if( $customexclude_ids ) $block_args['post_args']['post__not_in'] = newsis_get_post_id_for_args( $customexclude_ids );
                                                    if( $postCategories ) $block_args['post_args']['cat'] = newsis_get_categories_for_args($postCategories);
                                                    if( $block_query->dateFilter != 'all' ) $block_args['post_args']['date_query'] = newsis_get_date_format_array_args($block_query->dateFilter);
                                                    if( $block_query->posts ) $block_args['post_args']['post__in'] = newsis_get_post_id_for_args($block_query->posts);
                                                    // get template file w.r.t par
                                                    $block_args['uniqueID'] = wp_unique_id('newsis-block--');
                                                    $style_variables = [
                                                        'unique_id' =>  $block_args['uniqueID'],
                                                        'layout'    =>  $block_args['options']->layout,
                                                        'image_ratio' => $block->imageRatio
                                                    ];
                                                    ( in_array( $block_args['options']->type, [ 'news-grid', 'news-carousel', 'news-list' ] ) ) ? newsis_get_style_tag( $style_variables ) : newsis_get_style_tag_fb( $style_variables );
                                                    get_template_part( 'template-parts/' .esc_html( $type ). '/template', esc_html( $layout ), $block_args );
                                        }
                                    endif;
                                endforeach;
                            ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php
     }
     add_action( 'newsis_lefts_rightc_blocks_hook', 'newsis_lefts_rightc_blocks_part', 10 );
endif;

if( ! function_exists( 'newsis_bottom_full_width_blocks_part' ) ) :
    /**
     * Bottom Full Width Blocks element
     * 
     * @since 1.0.0
     */
     function newsis_bottom_full_width_blocks_part() {
        $bottom_full_width_blocks = NI\newsis_get_customizer_option( 'bottom_full_width_blocks' );
        if( empty( $bottom_full_width_blocks )|| is_paged() || newsis_is_paged_filtered() ) return;
        $bottom_full_width_blocks = json_decode( $bottom_full_width_blocks );
        if( ! in_array( true, array_column( $bottom_full_width_blocks, 'option' ) ) ) {
            return;
        }
        $bottom_full_width_blocks_width_layout = newsis_get_section_width_layout_val('bottom_full_width_blocks_width_layout');
        ?>
            <section id="bottom-full-width-section" class="newsis-section bottom-full-width-section <?php echo esc_attr( 'width-' . $bottom_full_width_blocks_width_layout ); ?>">
                <div class="newsis-container">
                    <div class="row">
                        <?php
                            foreach( $bottom_full_width_blocks as $block ) :
                                if( $block->option ) :
                                    $type = $block->type;
                                    switch($type) {
                                        case 'ad-block' : newsis_advertisement_block_html( $block, true );
                                                        break;
                                        default: $layout = $block->layout;
                                                $block_query = json_decode( $block->query );
                                                $order = $block_query->order;
                                                $postCategories = $block_query->categories;
                                                $customexclude_ids = $block_query->ids;
                                                $orderArray = explode( '-', $order );
                                                $block_args = array(
                                                    'post_args' => array(
                                                        'post_type' => 'post',
                                                        'order' => esc_html( $orderArray[1] ),
                                                        'orderby' => esc_html( $orderArray[0] ),
                                                        'ignore_sticky_posts'   => true
                                                    ),
                                                    'options'    => $block
                                                );
                                                $offset = isset( $block_query->offset ) ? $block_query->offset: 0;
                                                if( $offset > 0 ) $block_args['post_args']['offset'] = absint($offset);
                                                $block_args['post_args']['posts_per_page'] = absint( $block_query->count );
                                                if( $customexclude_ids ) $block_args['post_args']['post__not_in'] = newsis_get_post_id_for_args( $customexclude_ids );
                                                if( $postCategories ) $block_args['post_args']['cat'] = newsis_get_categories_for_args($postCategories);
                                                if( $block_query->dateFilter != 'all' ) $block_args['post_args']['date_query'] = newsis_get_date_format_array_args($block_query->dateFilter);
                                                if( $block_query->posts ) $block_args['post_args']['post__in'] = newsis_get_post_id_for_args($block_query->posts);
                                                // get template file w.r.t par
                                                $block_args['uniqueID'] = wp_unique_id('newsis-block--');
                                                $style_variables = [
                                                    'unique_id' =>  $block_args['uniqueID'],
                                                    'layout'    =>  $block_args['options']->layout,
                                                    'image_ratio' => $block->imageRatio
                                                ];
                                                ( in_array( $block_args['options']->type, [ 'news-grid', 'news-carousel', 'news-list' ] ) ) ? newsis_get_style_tag( $style_variables ) : newsis_get_style_tag_fb( $style_variables );
                                                get_template_part( 'template-parts/' .esc_html( $type ). '/template', esc_html( $layout ), $block_args );
                                    }
                                endif;
                            endforeach;
                        ?>
                    </div>
                </div>
            </section>
        <?php
     }
     add_action( 'newsis_bottom_full_width_blocks_hook', 'newsis_bottom_full_width_blocks_part', 10 );
endif;