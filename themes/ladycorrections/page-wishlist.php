<?php
/**
 * Template Name: LC Wishlist
 *
 * Wishlist page: saved product IDs live in localStorage under "lc_wishlist"
 * and are rendered into #lc-wish-grid via the lc_get_wishlist_products
 * AJAX endpoint (see inc/wishlist-ajax.php).
 *
 * @package LadyCorrections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

do_action( 'lc_before_main' );
?>

<main id="lc-main" class="lc-main">

	<div class="lc-pagehead">
		<div class="lc-container">
			<h1><?php echo esc_html( get_theme_mod( 'lc_wish_h', __( 'My Wishlist', 'ladycorrections' ) ) ); ?></h1>
			<p><?php echo esc_html( get_theme_mod( 'lc_wish_sub', __( 'Your saved favorites, ready when you are.', 'ladycorrections' ) ) ); ?></p>
		</div>
	</div>

	<section class="lc-section">
		<div class="lc-container">

			<div class="lc-wish-toolbar">
				<span class="lc-wish-count" aria-live="polite"><span id="lc-wish-count-num">0</span></span>
				<button type="button" class="lc-btn lc-btn--outline" id="lc-wish-clear"><?php echo esc_html( get_theme_mod( 'lc_wish_clear', __( 'Clear all', 'ladycorrections' ) ) ); ?></button>
			</div>

			<div
				id="lc-wish-grid"
				class="lc-wish-grid"
				aria-busy="true"
				data-remove-label="<?php echo esc_attr( get_theme_mod( 'lc_wish_remove', __( 'Remove', 'ladycorrections' ) ) ); ?>"
			></div>

			<div class="lc-empty-state" id="lc-wish-empty" hidden>
				<div class="lc-empty-state__icon" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
				</div>
				<h2><?php echo esc_html( get_theme_mod( 'lc_wish_empty_h', __( 'Your wishlist is empty', 'ladycorrections' ) ) ); ?></h2>
				<p><?php echo esc_html( get_theme_mod( 'lc_wish_empty_p', __( 'Tap the heart on any product to save it here for later.', 'ladycorrections' ) ) ); ?></p>
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<a class="lc-btn lc-btn--primary" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php echo esc_html( get_theme_mod( 'lc_wish_empty_btn', __( 'Browse Products', 'ladycorrections' ) ) ); ?></a>
				<?php endif; ?>
			</div>

		</div>
	</section>

</main>

<?php
do_action( 'lc_after_main' );
get_footer();
