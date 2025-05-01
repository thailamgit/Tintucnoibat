<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Newsis
 */
use Newsis\CustomizerDefault as NI;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> <?php newsis_schema_body_attributes(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'newsis' ); ?></a>
	<div class="newsis_ovelay_div"></div>
	<?php
		/**
		 * hook - newsis_page_prepend_hook
		 * 
		 * @package Newsis
		 * @since 1.0.0
		 */
		do_action( "newsis_page_prepend_hook" );
	?>
	
	<header id="masthead" class="site-header layout--default layout--one">
		<?php
			/**
			 * Function - newsis_top_header_html
			 * 
			 * @since 1.0.0
			 * 
			 */
			newsis_top_header_html();

			/**
			 * Function - newsis_header_html
			 * 
			 * @since 1.0.0
			 * 
			 */
			newsis_header_html();
		?>
	</header><!-- #masthead -->
	
	<?php
	/**
	 * function - newsis_after_header_html
	 * 
	 * @since 1.0.0
	 */
	newsis_after_header_html();