<?php
/**
 * Empty cart page — premium LadyCorrections version.
 *
 * Keeps the standard WooCommerce hooks so plugins keep working.
 *
 * @package LadyCorrections
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_cart_is_empty' );

$shop_url = function_exists( 'wc_get_page_id' ) && wc_get_page_id( 'shop' ) > 0
	? get_permalink( wc_get_page_id( 'shop' ) )
	: home_url( '/' );
?>

<div class="lc-empty-cart">
	<div class="lc-empty-cart__art" aria-hidden="true">
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4">
			<circle cx="9" cy="21" r="1.4" />
			<circle cx="19" cy="21" r="1.4" />
			<path d="M2 3h3l2.6 12.4a2 2 0 0 0 2 1.6h8.7a2 2 0 0 0 2-1.6L22 7H6" />
		</svg>
	</div>
	<h2><?php echo esc_html( get_theme_mod( 'lc_cart_empty_h', __( 'Your cart is empty', 'ladycorrections' ) ) ); ?></h2>
	<p><?php echo wp_kses_post( get_theme_mod( 'lc_cart_empty_p', __( 'Looks like you haven\'t added anything yet — let\'s fix that.', 'ladycorrections' ) ) ); ?></p>
	<p class="return-to-shop">
		<a class="lc-btn lc-btn--primary button wc-backward" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', $shop_url ) ); ?>">
			<?php echo esc_html( get_theme_mod( 'lc_cart_empty_btn', __( 'Start Shopping', 'ladycorrections' ) ) ); ?>
		</a>
	</p>
</div>
