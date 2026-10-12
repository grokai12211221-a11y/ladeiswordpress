<?php
/**
 * Checkout & cart assets loader for LadyCorrections.
 *
 * Enqueues the premium cart/checkout stylesheet (assets/css/lc-checkout.css)
 * on WooCommerce cart and checkout pages only.
 *
 * The integrator must require_once this file (e.g. from functions.php):
 *   require_once LC_DIR . '/inc/checkout-assets.php';
 *
 * @package LadyCorrections
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue the lc-checkout stylesheet on cart + checkout pages.
 */
function lc_checkout_assets() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	if ( ! is_cart() && ! is_checkout() ) {
		return;
	}

	$css_file = LC_DIR . '/assets/css/lc-checkout.css';
	$css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : LC_VERSION;

	wp_enqueue_style(
		'lc-checkout',
		LC_URI . '/assets/css/lc-checkout.css',
		array( 'lc-woocommerce' ),
		$css_ver
	);
}
add_action( 'wp_enqueue_scripts', 'lc_checkout_assets', 20 );
