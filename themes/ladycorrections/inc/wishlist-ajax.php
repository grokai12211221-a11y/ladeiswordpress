<?php
/**
 * Wishlist AJAX endpoint.
 *
 * Serves the LC Wishlist page template: takes an array of product IDs from
 * localStorage and returns the rendered WooCommerce product-loop HTML.
 *
 * @package LadyCorrections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_ajax_lc_get_wishlist_products', 'lc_ajax_wishlist_products' );
add_action( 'wp_ajax_nopriv_lc_get_wishlist_products', 'lc_ajax_wishlist_products' );

/**
 * Return rendered product cards for the given wishlist IDs.
 */
function lc_ajax_wishlist_products() {
	check_ajax_referer( 'lc_wish', 'nonce' );

	$lc_raw = isset( $_POST['ids'] ) ? (array) wp_unslash( $_POST['ids'] ) : array();
	$lc_ids = array_filter( array_map( 'absint', $lc_raw ) );

	if ( empty( $lc_ids ) || ! class_exists( 'WooCommerce' ) ) {
		wp_send_json_success( array( 'html' => '' ) );
	}

	$lc_query = new WP_Query(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'post__in'       => $lc_ids,
			'orderby'        => 'post__in',
			'posts_per_page' => count( $lc_ids ),
			'no_found_rows'  => true,
		)
	);

	ob_start();
	if ( $lc_query->have_posts() ) {
		woocommerce_product_loop_start();
		while ( $lc_query->have_posts() ) {
			$lc_query->the_post();
			wc_get_template_part( 'content', 'product' );
		}
		woocommerce_product_loop_end();
	}
	wp_reset_postdata();

	wp_send_json_success(
		array(
			'html'  => ob_get_clean(),
			'count' => count( $lc_ids ),
		)
	);
}
