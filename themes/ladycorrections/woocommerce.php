<?php
/**
 * WooCommerce wrapper template.
 *
 * The theme prints its own container markup around woocommerce_content()
 * (instead of hooking wrappers to WooCommerce actions), so the layout is
 * identical on shop, product, cart, checkout and account pages.
 *
 * @package LadyCorrections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$show_sidebar = is_active_sidebar( 'shop-sidebar' ) && ( is_shop() || is_product_taxonomy() );
?>

<main id="lc-main" class="lc-main">

	<?php if ( is_shop() || is_product_taxonomy() ) : ?>
		<div class="lc-pagehead">
			<div class="lc-container">
				<h1><?php woocommerce_page_title(); ?></h1>
				<?php if ( is_product_taxonomy() ) : ?>
					<?php $desc = term_description(); ?>
					<?php if ( $desc ) : ?>
						<p><?php echo wp_kses_post( $desc ); ?></p>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<div class="lc-container lc-section">
		<div class="lc-shop-layout<?php echo $show_sidebar ? ' has-sidebar' : ''; ?>">

			<?php if ( $show_sidebar ) : ?>
				<aside class="lc-sidebar" aria-label="<?php esc_attr_e( 'Shop sidebar', 'ladycorrections' ); ?>">
					<?php dynamic_sidebar( 'shop-sidebar' ); ?>
				</aside>
			<?php endif; ?>

			<div class="lc-shop-content">
				<?php if ( is_shop() && ! is_product_taxonomy() && class_exists( 'WooCommerce' ) ) : ?>
					<?php
					/* Professional AJAX shop filters. Product taxonomy archives
					 * keep the default WooCommerce loop below. */
					$lc_filter_params = lc_get_shop_filter_params();
					$lc_shop          = lc_render_shop_products( lc_get_shop_filter_query_args( $lc_filter_params ) );
					set_query_var( 'lc_shop_found', $lc_shop['found'] );
					get_template_part( 'template-parts/shop-toolbar' );
					?>
					<div class="lc-shop-results" id="lc-shop-results">
						<?php echo $lc_shop['html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- rendered product loop HTML. ?>
					</div>
					<div class="lc-shop-pagination-wrap" id="lc-shop-pagination">
						<?php echo lc_shop_pagination_html( $lc_shop['pages'], $lc_filter_params['paged'], lc_shop_base_url( $lc_filter_params ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- paginate_links HTML. ?>
					</div>
				<?php else : ?>
					<?php woocommerce_content(); ?>
				<?php endif; ?>
			</div>

		</div>
	</div>

</main>

<?php
get_footer();
