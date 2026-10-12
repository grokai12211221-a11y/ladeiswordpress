<?php
/**
 * Cart Page — LadyCorrections premium layout.
 *
 * Based on WooCommerce templates/cart/cart.php @version 11.2.0.
 * Renders cart items as modern cards in a two-column grid with a sticky
 * 3D summary card. ALL WooCommerce hooks, form names, nonces and field
 * names from the standard template are preserved.
 *
 * @package LadyCorrections
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );

if ( WC()->cart->is_empty() ) : ?>

	<div class="lc-cart-wrap lc-cart-empty">
		<?php do_action( 'woocommerce_cart_is_empty' ); ?>

		<div class="lc-cart-empty__art" aria-hidden="true">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
				<circle cx="9" cy="20" r="1.6"/>
				<circle cx="17" cy="20" r="1.6"/>
				<path d="M2.5 3.5h2.2l2.5 12.2a1.5 1.5 0 0 0 1.5 1.2h8.6a1.5 1.5 0 0 0 1.5-1.2L21 7H6"/>
			</svg>
		</div>

		<h2 class="lc-cart-empty__title"><?php echo esc_html( get_theme_mod( 'lc_cart_empty_h', __( 'Your cart is empty', 'ladycorrections' ) ) ); ?></h2>
		<p class="lc-cart-empty__text"><?php echo esc_html( get_theme_mod( 'lc_cart_empty_p', __( "Looks like you haven't added anything yet — let's fix that.", 'ladycorrections' ) ) ); ?></p>

		<?php
		$lc_shop_url = apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) );
		if ( ! $lc_shop_url ) {
			$lc_shop_url = home_url( '/' );
		}
		?>
		<a class="lc-cart-empty__cta" href="<?php echo esc_url( $lc_shop_url ); ?>">
			<?php echo esc_html( get_theme_mod( 'lc_cart_empty_btn', __( 'Start Shopping', 'ladycorrections' ) ) ); ?>
		</a>
	</div>

<?php else : ?>

	<div class="lc-cart-wrap">
		<div class="lc-cart-grid">

			<div class="lc-cart-main">
				<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
					<?php do_action( 'woocommerce_before_cart_table' ); ?>

					<div class="lc-cart-items" role="region" aria-label="<?php esc_attr_e( 'Cart items', 'ladycorrections' ); ?>">
						<?php do_action( 'woocommerce_before_cart_contents' ); ?>

						<?php
						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
							$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
							$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

							/**
							 * Filter whether this cart item is visible in the cart.
							 *
							 * @since 2.1.0
							 * @param bool   $visible       Whether the cart item is visible. Default true.
							 * @param array  $cart_item     The cart item data.
							 * @param string $cart_item_key The cart item key.
							 */
							$visible = apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key );

							if ( $_product instanceof WC_Product && $_product->exists() && $cart_item['quantity'] > 0 && $visible ) {
								$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
								$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
								?>
								<div class="lc-cart-item woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

									<div class="lc-cart-item__remove">
										<?php
											echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
												'woocommerce_cart_item_remove_link',
												sprintf(
													'<a role="button" href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
													esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
													/* translators: %s is the product name */
													esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
													esc_attr( $product_id ),
													esc_attr( $_product->get_sku() )
												),
												$cart_item_key
											);
										?>
									</div>

									<div class="lc-cart-item__media">
									<?php
									/**
									 * Filter the product thumbnail displayed in the WooCommerce cart.
									 *
									 * @since 2.1.0
									 * @param string $thumbnail     The HTML for the product image.
									 * @param array  $cart_item     The cart item data.
									 * @param string $cart_item_key Unique key for the cart item.
									 */
									$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

									if ( ! $product_permalink ) {
										echo $thumbnail; // PHPCS: XSS ok.
									} else {
										printf(
											'<a href="%s" aria-label="%s">%s</a>',
											esc_url( $product_permalink ),
											esc_attr( wp_strip_all_tags( $product_name ) ),
											$thumbnail // PHPCS: XSS ok.
										);
									}
									?>
									</div>

									<div class="lc-cart-item__body">
										<div class="lc-cart-item__name">
										<?php
										if ( ! $product_permalink ) {
											echo wp_kses_post( $product_name . '&nbsp;' );
										} else {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
										}

										do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

										// Meta data.
										echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

										// Backorder notification.
										if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
										}
										?>
										</div>

										<div class="lc-cart-item__price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
											<?php
												echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
											?>
										</div>

										<div class="lc-cart-item__qty" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
										<?php
										if ( $_product->is_sold_individually() ) {
											$min_quantity = 1;
											$max_quantity = 1;
										} else {
											$min_quantity = 0;
											$max_quantity = $_product->get_max_purchase_quantity();
										}

										$product_quantity = woocommerce_quantity_input(
											array(
												'input_name'   => "cart[{$cart_item_key}][qty]",
												'input_value'  => $cart_item['quantity'],
												'max_value'    => $max_quantity,
												'min_value'    => $min_quantity,
												'product_name' => $product_name,
											),
											$_product,
											false
										);

										echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
										?>
										</div>

										<div class="lc-cart-item__subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
											<?php
												echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
											?>
										</div>
									</div>
								</div>
								<?php
							}
						}
						?>

						<?php do_action( 'woocommerce_cart_contents' ); ?>
					</div>

					<div class="lc-cart-actions">
						<?php if ( wc_coupons_enabled() ) { ?>
							<div class="coupon lc-coupon">
								<label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label>
								<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" autocomplete="off" />
								<button type="submit" class="button lc-coupon__apply<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?></button>
								<?php do_action( 'woocommerce_cart_coupon' ); ?>
							</div>
						<?php } ?>

						<button type="submit" class="button lc-cart__update<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

						<?php do_action( 'woocommerce_cart_actions' ); ?>

						<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
					</div>

					<?php do_action( 'woocommerce_after_cart_contents' ); ?>
					<?php do_action( 'woocommerce_after_cart_table' ); ?>
				</form>
			</div>

			<aside class="lc-cart-side" aria-label="<?php esc_attr_e( 'Cart summary', 'ladycorrections' ); ?>">
				<div class="lc-cart-summary">
					<?php
					/*
					 * Render the standard totals (cart/cart-totals.php with ALL of its
					 * hooks: woocommerce_before_cart_totals, _before/_after_shipping,
					 * _before/_after_order_total, woocommerce_after_cart_totals and
					 * woocommerce_proceed_to_checkout) inside the sticky summary card.
					 * Unhooked from the collaterals so it is not rendered twice.
					 */
					remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
					woocommerce_cart_totals();
					?>
				</div>

				<?php
				$lc_shop_url = apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) );
				if ( ! $lc_shop_url ) {
					$lc_shop_url = home_url( '/' );
				}
				?>
				<a class="lc-continue-link" href="<?php echo esc_url( $lc_shop_url ); ?>">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
					<?php echo esc_html( get_theme_mod( 'lc_cart_continue', __( 'Continue shopping', 'ladycorrections' ) ) ); ?>
				</a>

				<?php
				$lc_trust_raw   = get_theme_mod( 'lc_cart_trust', __( 'Cash on Delivery available • 100% authentic • Easy 7-day returns', 'ladycorrections' ) );
				$lc_trust_items = array_filter( array_map( 'trim', explode( '•', (string) $lc_trust_raw ) ) );
				if ( $lc_trust_items ) :
					$lc_trust_icons = array(
						'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2.5" y="6" width="19" height="12" rx="2.5"/><circle cx="12" cy="12" r="2.6"/><path d="M6 9.5h.01M18 14.5h.01"/></svg>',
						'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2.5l7.5 3v6c0 5-3.2 8.3-7.5 10-4.3-1.7-7.5-5-7.5-10v-6z"/><path d="M9 12l2.2 2.2L15.5 10"/></svg>',
						'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 4v5h5"/></svg>',
					);
					?>
					<div class="lc-cart-trust">
						<?php foreach ( $lc_trust_items as $lc_i => $lc_trust_item ) : ?>
							<span class="lc-cart-trust__item">
								<?php echo $lc_trust_icons[ $lc_i % count( $lc_trust_icons ) ]; // PHPCS: XSS ok - static theme SVG. ?>
								<?php echo esc_html( $lc_trust_item ); ?>
							</span>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</aside>

		</div>
	</div>

<?php endif; ?>

<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<div class="cart-collaterals">
	<?php
		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10 (relocated into .lc-cart-summary above)
		 */
		do_action( 'woocommerce_cart_collaterals' );
	?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
