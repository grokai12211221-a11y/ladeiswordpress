<?php
/**
 * Thankyou page — LadyCorrections premium layout.
 *
 * Based on WooCommerce templates/checkout/thankyou.php @version 11.2.0.
 * Wraps the standard output in an animated success card while keeping
 * every hook ($order handling, failed-order path, woocommerce_thankyou_*
 * and woocommerce_thankyou) intact.
 *
 * @package LadyCorrections
 *
 * @var WC_Order|false $order
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order lc-thanks-wrap">

	<?php if ( $order && ! is_wp_error( $order ) ) : ?>

		<?php do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<?php
			$default_order_failed_text = esc_html__( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' );

			/**
			 * Filters the message shown when an order has failed.
			 *
			 * @param string   $message The failed order message.
			 * @param WC_Order $order   The failed order.
			 *
			 * @since 11.2.0
			 */
			$order_failed_text = apply_filters( 'woocommerce_thankyou_order_failed_text', $default_order_failed_text, $order );
			$order_failed_text = is_string( $order_failed_text ) ? $order_failed_text : $default_order_failed_text;
			?>

			<div class="lc-thanks-card lc-thanks-card--failed">
				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php echo wp_kses_post( $order_failed_text ); ?></p>

				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
					<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
					<?php if ( is_user_logged_in() ) : ?>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
					<?php endif; ?>
				</p>
			</div>

		<?php else : ?>

			<div class="lc-thanks-card">
				<div class="lc-thanks-check" aria-hidden="true">
					<svg class="lc-thanks-check__svg" viewBox="0 0 52 52">
						<circle class="lc-thanks-check__circle" cx="26" cy="26" r="24" fill="none"/>
						<path class="lc-thanks-check__tick" fill="none" d="M14 27l8 8 16-17"/>
					</svg>
				</div>

				<h2 class="lc-thanks-title"><?php echo esc_html( get_theme_mod( 'lc_co_thanks_h', __( 'Thank you for your order!', 'ladycorrections' ) ) ); ?></h2>

				<p class="lc-thanks-orderno">
					<?php
					/* translators: %s: order number, e.g. #123 */
					printf( esc_html__( 'Order %s', 'ladycorrections' ), '<strong>#' . esc_html( $order->get_order_number() ) . '</strong>' );
					?>
				</p>

				<p class="lc-thanks-msg"><?php echo esc_html( get_theme_mod( 'lc_co_thanks_p', __( "Your order has been received and is being prepared. We'll contact you soon to confirm delivery.", 'ladycorrections' ) ) ); ?></p>

				<ul class="lc-thanks-steps">
					<li>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.13.96.36 1.9.7 2.8a2 2 0 0 1-.45 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.45c.9.34 1.84.57 2.8.7A2 2 0 0 1 22 16.9z"/></svg>
						<span><?php esc_html_e( "We'll call you shortly to confirm your order", 'ladycorrections' ); ?></span>
					</li>
					<li>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2.5" y="6" width="19" height="12" rx="2.5"/><circle cx="12" cy="12" r="2.6"/><path d="M6 9.5h.01M18 14.5h.01"/></svg>
						<span><?php esc_html_e( 'Pay in cash when your order arrives', 'ladycorrections' ); ?></span>
					</li>
					<li>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 8l-9-5-9 5v8l9 5 9-5z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/></svg>
						<span><?php esc_html_e( 'Track your order anytime from your account', 'ladycorrections' ); ?></span>
					</li>
				</ul>
			</div>

			<?php wc_get_template( 'checkout/order-received.php', array( 'order' => $order ) ); ?>

			<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

				<li class="woocommerce-order-overview__order order">
					<?php esc_html_e( 'Order number:', 'woocommerce' ); ?>
					<strong><?php echo esc_html( $order->get_order_number() ); ?></strong>
				</li>

				<li class="woocommerce-order-overview__date date">
					<?php esc_html_e( 'Date:', 'woocommerce' ); ?>
					<strong><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</li>

				<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
					<li class="woocommerce-order-overview__email email">
						<?php esc_html_e( 'Email:', 'woocommerce' ); ?>
						<strong><?php echo esc_html( $order->get_billing_email() ); ?></strong>
					</li>
				<?php endif; ?>

				<li class="woocommerce-order-overview__total total">
					<?php esc_html_e( 'Total:', 'woocommerce' ); ?>
					<strong><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</li>

				<?php if ( $order->get_payment_method_title() ) : ?>
					<li class="woocommerce-order-overview__payment-method method">
						<?php esc_html_e( 'Payment method:', 'woocommerce' ); ?>
						<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
					</li>
				<?php endif; ?>

			</ul>

		<?php endif; ?>

		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

	<?php else : ?>

		<?php wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); ?>

	<?php endif; ?>

</div>
