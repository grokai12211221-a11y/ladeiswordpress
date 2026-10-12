<?php
/**
 * Checkout Form — LadyCorrections premium layout.
 *
 * Based on WooCommerce templates/checkout/form-checkout.php @version 9.4.0.
 * Two-column layout: customer details on the left, sticky 3D order-review
 * card on the right. ALL WooCommerce hooks, the checkout form attributes,
 * the registration/login guard and coupon-notice handling are preserved.
 *
 * @package LadyCorrections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

?>
<div class="lc-checkout-wrap">

	<div class="lc-co-secure">
		<span class="lc-co-secure__icon" aria-hidden="true">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<rect x="4.5" y="10.5" width="15" height="10" rx="2.5"/>
				<path d="M8 10.5V7.5a4 4 0 0 1 8 0v3"/>
				<path d="M12 14.5v2"/>
			</svg>
		</span>
		<h2 class="lc-co-secure__title"><?php echo esc_html( get_theme_mod( 'lc_co_secure', __( 'Secure Checkout', 'ladycorrections' ) ) ); ?></h2>
	</div>

	<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" aria-label="<?php echo esc_attr__( 'Checkout', 'woocommerce' ); ?>">

		<div class="lc-co-grid">

			<div class="lc-co-main">
				<?php if ( $checkout->get_checkout_fields() ) : ?>

					<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

					<div class="lc-co-details" id="customer_details">
						<div class="lc-co-block lc-co-billing">
							<?php do_action( 'woocommerce_checkout_billing' ); ?>
						</div>

						<div class="lc-co-block lc-co-shipping">
							<?php do_action( 'woocommerce_checkout_shipping' ); ?>
						</div>
					</div>

					<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

				<?php endif; ?>
			</div>

			<aside class="lc-co-side" aria-label="<?php esc_attr_e( 'Order summary', 'ladycorrections' ); ?>">
				<div class="lc-co-summary">

					<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

					<h3 id="order_review_heading" class="lc-co-summary__title"><?php esc_html_e( 'Your order', 'woocommerce' ); ?></h3>

					<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

					<div id="order_review" class="woocommerce-checkout-review-order lc-co-review">
						<?php do_action( 'woocommerce_checkout_order_review' ); ?>
					</div>

					<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

					<div class="lc-co-trust">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<rect x="4.5" y="10.5" width="15" height="10" rx="2.5"/>
							<path d="M8 10.5V7.5a4 4 0 0 1 8 0v3"/>
						</svg>
						<span><?php echo esc_html( get_theme_mod( 'lc_co_trust', __( 'Cash on Delivery available across Iraq', 'ladycorrections' ) ) ); ?></span>
					</div>

				</div>
			</aside>

		</div>

	</form>

</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
