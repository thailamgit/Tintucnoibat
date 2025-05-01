<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Newsis
 */

 /**
  * hook - newsis_before_footer_section
  * 
  */
  do_action( 'newsis_before_footer_section' );
?>
	<footer id="colophon" class="site-footer dark_bk">
		<?php
			/**
			 * Function - newsis_footer_sections_html
			 * 
			 * @since 1.0.0
			 * 
			 */
			newsis_footer_sections_html();

			/**
			 * Function - newsis_bottom_footer_sections_html
			 * 
			 * @since 1.0.0
			 * 
			 */
			newsis_bottom_footer_sections_html();
		?>
	</footer><!-- #colophon -->
	<?php
		/**
		* hook - newsis_after_footer_hook
		*
		* @hooked - newsis_scroll_to_top
		*
		*/
		if( has_action( 'newsis_after_footer_hook' ) ) {
			do_action( 'newsis_after_footer_hook' );
		}
	?>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>