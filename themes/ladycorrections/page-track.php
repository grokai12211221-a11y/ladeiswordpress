<?php
/**
 * Template Name: LC Track Order
 *
 * Order tracking page: renders the WooCommerce [woocommerce_order_tracking]
 * shortcode inside a premium card.
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
			<h1><?php echo esc_html( get_theme_mod( 'lc_track_h', __( 'Track Your Order', 'ladycorrections' ) ) ); ?></h1>
			<p><?php echo esc_html( get_theme_mod( 'lc_track_sub', __( 'Enter your order ID and billing email to see the latest status.', 'ladycorrections' ) ) ); ?></p>
		</div>
	</div>

	<section class="lc-section">
		<div class="lc-container">
			<div class="lc-track-card">
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<?php echo do_shortcode( '[woocommerce_order_tracking]' ); ?>
				<?php else : ?>
					<p class="lc-notice" role="status"><?php esc_html_e( 'Order tracking requires WooCommerce to be installed and active.', 'ladycorrections' ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</section>

</main>

<?php
do_action( 'lc_after_main' );
get_footer();
