<?php
/**
 * 404 — page not found.
 *
 * @package LadyCorrections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

do_action( 'lc_before_main' );
?>

<?php lc_content_open(); ?>

<div class="lc-404">
	<div class="lc-404__code" aria-hidden="true">404</div>
	<h1><?php echo esc_html( get_theme_mod( 'lc_404_h', __( 'Oops! Page not found', 'ladycorrections' ) ) ); ?></h1>
	<p><?php echo esc_html( get_theme_mod( 'lc_404_p', __( 'The page you are looking for may have been moved or no longer exists. Try searching, or head back home.', 'ladycorrections' ) ) ); ?></p>
	<?php get_search_form(); ?>
	<a class="lc-btn lc-btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php echo esc_html( get_theme_mod( 'lc_404_btn', __( 'Back to home', 'ladycorrections' ) ) ); ?>
	</a>
</div>

<?php lc_content_close(); ?>

<?php
do_action( 'lc_after_main' );
get_footer();
