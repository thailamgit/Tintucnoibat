<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Newsis
 */
use Newsis\CustomizerDefault as NI;
$single_post_show_original_image_option = NI\newsis_get_customizer_option( 'single_post_show_original_image_option' );
?>
<article <?php newsis_schema_article_attributes(); ?> id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-inner newsis-card">
		<header class="entry-header">
			<?php
				newsis_post_thumbnail( $single_post_show_original_image_option );	// thumbnail
				the_category();	// category
				the_title( '<h1 class="entry-title"' .newsis_schema_article_name_attributes(). '>', '</h1>' ); // title
				if ( 'post' === get_post_type() ) :	// meta
					?>
					<div class="entry-meta">
						<?php
							newsis_posted_by();	// author
							newsis_posted_on();	// date
							newsis_comments_number();	// comment
							$website_read_time_before_icon = NI\newsis_get_customizer_option( 'website_read_time_before_icon' );	// read time
							if( $website_read_time_before_icon['type'] == 'none' ) {
								echo '<span class="read-time">' .newsis_post_read_time( get_the_content() ). ' ' .esc_html__( 'mins', 'newsis' ). '</span>';
							} else {
								echo '<span class="read-time ' .esc_attr( $website_read_time_before_icon['value'] ). '">' .newsis_post_read_time( get_the_content() ). ' ' .esc_html__( 'mins', 'newsis' ). '</span>';
							}
						?>
					</div><!-- .entry-meta -->
				<?php endif;
			?>
		</header><!-- .entry-header -->

		<div <?php newsis_schema_article_body_attributes(); ?> class="entry-content">
			<?php
				the_content(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'newsis' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						wp_kses_post( get_the_title() )
					)
				);

				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'newsis' ),
						'after'  => '</div>',
					)
				);
			?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php newsis_tags_list(); ?>
			<?php newsis_entry_footer(); ?>
		</footer><!-- .entry-footer -->
		<?php
			the_post_navigation(
				array(
					'prev_text' => '<span class="nav-subtitle"><i class="fas fa-angle-double-left"></i>' . esc_html__( 'Previous:', 'newsis' ) . '</span> <span class="nav-title">%title</span>',
					'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'newsis' ) . '<i class="fas fa-angle-double-right"></i></span> <span class="nav-title">%title</span>',
				)
			);
		?>
	</div>
	<?php
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;
	?>
</article><!-- #post-<?php the_ID(); ?> -->
<?php
	/**
	 * hook - newsis_single_post_append_hook
	 * 
	 */
	do_action( 'newsis_single_post_append_hook' );
?>