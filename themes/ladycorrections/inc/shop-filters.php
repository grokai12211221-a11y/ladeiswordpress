<?php
/**
 * AJAX shop filters for the LadyCorrections WooCommerce theme.
 *
 * Provides the server side of the professional shop filtering experience:
 * parameter sanitizing, WP_Query assembly, product rendering, pagination,
 * the admin-ajax endpoint, and asset loading for the shop page.
 *
 * Everything in this file is guarded by class_exists( 'WooCommerce' ) so the
 * theme keeps working when WooCommerce is not active.
 *
 * @package LadyCorrections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

/* ============================================================
   1. FILTER PARAMETERS
   ============================================================ */

/**
 * Read and sanitize shop filter parameters from the request.
 *
 * Works for both the no-JS GET fallback (form submits to the shop URL)
 * and the AJAX POST calls, so $_REQUEST is used on purpose.
 *
 * @return array Sanitized filter parameters.
 */
function lc_get_shop_filter_params() {
	$orderby_whitelist = array( 'menu_order', 'popularity', 'rating', 'date', 'price', 'price-desc' );
	$orderby_raw       = isset( $_REQUEST['orderby'] ) ? sanitize_key( wp_unslash( $_REQUEST['orderby'] ) ) : '';
	if ( ! in_array( $orderby_raw, $orderby_whitelist, true ) ) {
		$orderby_raw = '';
	}

	$min_price = '';
	if ( isset( $_REQUEST['min_price'] ) && '' !== $_REQUEST['min_price'] ) {
		$min_price = (float) wp_unslash( $_REQUEST['min_price'] );
		if ( $min_price < 0 ) {
			$min_price = 0;
		}
	}

	$max_price = '';
	if ( isset( $_REQUEST['max_price'] ) && '' !== $_REQUEST['max_price'] ) {
		$max_price = (float) wp_unslash( $_REQUEST['max_price'] );
		if ( $max_price < 0 ) {
			$max_price = 0;
		}
	}

	return array(
		'cat'       => isset( $_REQUEST['cat'] ) ? sanitize_title( wp_unslash( $_REQUEST['cat'] ) ) : '',
		'orderby'   => $orderby_raw,
		'on_sale'   => ! empty( $_REQUEST['on_sale'] ),
		'in_stock'  => ! empty( $_REQUEST['in_stock'] ),
		'min_price' => $min_price,
		'max_price' => $max_price,
		's'         => isset( $_REQUEST['s'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) : '',
		'paged'     => isset( $_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : 1,
	);
}

/* ============================================================
   2. QUERY ASSEMBLY
   ============================================================ */

/**
 * Build WP_Query args from sanitized filter parameters.
 *
 * @param array $params Sanitized params from lc_get_shop_filter_params().
 * @return array WP_Query arguments.
 */
function lc_get_shop_filter_query_args( $params ) {
	$args = array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'posts_per_page'      => apply_filters( 'loop_shop_per_page', 12 ),
		'paged'               => max( 1, (int) $params['paged'] ),
		'ignore_sticky_posts' => true,
	);

	$tax_query = array( 'relation' => 'AND' );

	/* Category filter. */
	if ( ! empty( $params['cat'] ) ) {
		$tax_query[] = array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => $params['cat'],
		);
	}

	/* Keep WooCommerce-hidden products out of the results, like the
	 * default shop loop does. */
	$tax_query[] = array(
		'taxonomy' => 'product_visibility',
		'field'    => 'name',
		'terms'    => array( 'exclude-from-catalog', 'exclude-from-search' ),
		'operator' => 'NOT IN',
	);

	$args['tax_query'] = $tax_query;

	$meta_query = array( 'relation' => 'AND' );

	/* Price range filter on _price (DECIMAL). */
	if ( '' !== $params['min_price'] || '' !== $params['max_price'] ) {
		$min = ( '' === $params['min_price'] ) ? 0 : (float) $params['min_price'];
		$max = ( '' === $params['max_price'] ) ? 99999999 : (float) $params['max_price'];
		if ( $max < $min ) {
			$max = $min;
		}
		$meta_query[] = array(
			'key'     => '_price',
			'value'   => array( $min, $max ),
			'compare' => 'BETWEEN',
			'type'    => 'DECIMAL',
		);
	}

	/* In-stock filter. */
	if ( ! empty( $params['in_stock'] ) ) {
		$meta_query[] = array(
			'key'     => '_stock_status',
			'value'   => 'instock',
			'compare' => '=',
		);
	}

	if ( count( $meta_query ) > 1 ) {
		$args['meta_query'] = $meta_query;
	}

	/* On-sale filter. */
	if ( ! empty( $params['on_sale'] ) ) {
		$ids               = function_exists( 'wc_get_product_ids_on_sale' ) ? wc_get_product_ids_on_sale() : array();
		$args['post__in']  = ! empty( $ids ) ? $ids : array( 0 );
	}

	/* Keyword search. */
	if ( '' !== $params['s'] ) {
		$args['s'] = $params['s'];
	}

	/* Ordering: prefer WooCommerce's own catalog ordering logic. */
	$orderby = $params['orderby'] ? $params['orderby'] : 'menu_order';
	if ( function_exists( 'WC' ) && WC()->query && method_exists( WC()->query, 'get_catalog_ordering_args' ) ) {
		$ordering = WC()->query->get_catalog_ordering_args( $orderby );
		if ( isset( $ordering['orderby'] ) ) {
			$args['orderby'] = $ordering['orderby'];
		}
		if ( isset( $ordering['order'] ) ) {
			$args['order'] = $ordering['order'];
		}
		if ( isset( $ordering['meta_key'] ) && '' !== $ordering['meta_key'] ) {
			$args['meta_key'] = $ordering['meta_key'];
		}
	} else {
		switch ( $orderby ) {
			case 'price':
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = '_price';
				$args['order']    = 'ASC';
				break;
			case 'price-desc':
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = '_price';
				$args['order']    = 'DESC';
				break;
			case 'rating':
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = '_average_rating';
				$args['order']    = 'DESC';
				break;
			case 'date':
				$args['orderby'] = 'date';
				$args['order']   = 'DESC';
				break;
			case 'popularity':
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = 'total_sales';
				$args['order']    = 'DESC';
				break;
			default: /* menu_order */
				$args['orderby'] = 'menu_order title';
				$args['order']   = 'ASC';
				break;
		}
	}

	return apply_filters( 'lc_shop_filter_query_args', $args, $params );
}

/* ============================================================
   3. PRICE BOUNDS
   ============================================================ */

/**
 * Get the min/max _price across published products for the price inputs.
 *
 * @return array Array with 'min' and 'max' floats.
 */
function lc_get_price_bounds() {
	global $wpdb;

	$row = $wpdb->get_row(
		"SELECT MIN(CAST(pm.meta_value AS DECIMAL(10,2))) AS min_price,
		        MAX(CAST(pm.meta_value AS DECIMAL(10,2))) AS max_price
		 FROM {$wpdb->postmeta} pm
		 INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
		 WHERE pm.meta_key = '_price'
		   AND p.post_type = 'product'
		   AND p.post_status = 'publish'"
	);

	$min = ( $row && null !== $row->min_price ) ? (float) $row->min_price : 0;
	$max = ( $row && null !== $row->max_price ) ? (float) $row->max_price : 1000000;

	if ( $max <= $min ) {
		$max = $min + 1;
	}

	return array(
		'min' => $min,
		'max' => $max,
	);
}

/* ============================================================
   4. PRODUCT RENDERING
   ============================================================ */

/**
 * Render the filtered product loop.
 *
 * Uses the standard WooCommerce loop helpers so product cards render exactly
 * like the theme's normal shop loop (including the theme's wishlist heart and
 * quick-view button hooks on woocommerce_before_shop_loop_item_title).
 *
 * @param array $args WP_Query arguments.
 * @return array Array with 'html', 'found' and 'pages'.
 */
function lc_render_shop_products( $args ) {
	$query = new WP_Query( $args );

	ob_start();

	if ( $query->have_posts() ) {
		woocommerce_product_loop_start();

		while ( $query->have_posts() ) {
			$query->the_post();
			wc_get_template_part( 'content', 'product' );
		}

		woocommerce_product_loop_end();
	} else {
		?>
		<div class="lc-empty-state">
			<div class="lc-empty-icon" aria-hidden="true">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M6 7h12l1.5 13.5a1 1 0 0 1-1 1.1H5.5a1 1 0 0 1-1-1.1L6 7z"/><path d="M9 10V6a3 3 0 0 1 6 0v4"/></svg>
			</div>
			<h3><?php echo esc_html( get_theme_mod( 'lc_f_empty_h', __( 'No products found', 'ladycorrections' ) ) ); ?></h3>
			<p><?php echo esc_html( get_theme_mod( 'lc_f_empty_p', __( 'Try adjusting your filters or search to find what you are looking for.', 'ladycorrections' ) ) ); ?></p>
			<button type="button" class="lc-btn lc-btn--primary lc-f-empty-clear">
				<?php echo esc_html( get_theme_mod( 'lc_f_clear', __( 'Clear all filters', 'ladycorrections' ) ) ); ?>
			</button>
		</div>
		<?php
	}

	$html = ob_get_clean();

	$result = array(
		'html'  => $html,
		'found' => (int) $query->found_posts,
		'pages' => (int) $query->max_num_pages,
	);

	wp_reset_postdata();

	return $result;
}

/* ============================================================
   5. PAGINATION + BASE URL
   ============================================================ */

/**
 * Build the shop URL with the current filter params (minus paged).
 * Used as the pagination base so the no-JS fallback keeps filters
 * when navigating pages.
 *
 * @param array $params Sanitized filter params.
 * @return string Filtered shop URL.
 */
function lc_shop_base_url( $params ) {
	$url = get_permalink( wc_get_page_id( 'shop' ) );
	if ( ! $url ) {
		$url = home_url( '/' );
	}

	$query_args = array();
	foreach ( $params as $key => $value ) {
		if ( 'paged' === $key ) {
			continue;
		}
		if ( '' === $value || false === $value || null === $value ) {
			continue;
		}
		if ( 'on_sale' === $key || 'in_stock' === $key ) {
			$query_args[ $key ] = '1';
			continue;
		}
		$query_args[ $key ] = $value;
	}

	return add_query_arg( $query_args, $url );
}

/**
 * Pagination markup for the filtered results.
 *
 * @param int    $pages    Total pages.
 * @param int    $paged    Current page.
 * @param string $base_url Base URL with filters applied (no paged).
 * @return string HTML or empty string.
 */
function lc_shop_pagination_html( $pages, $paged, $base_url ) {
	if ( $pages <= 1 ) {
		return '';
	}

	$links = paginate_links(
		array(
			'base'      => esc_url_raw( add_query_arg( 'paged', '%#%', $base_url ) ),
			'format'    => '',
			'current'   => max( 1, (int) $paged ),
			'total'     => (int) $pages,
			'prev_text' => '&#171;',
			'next_text' => '&#187;',
			'type'      => 'list',
			'mid_size'  => 1,
			'end_size'  => 1,
		)
	);

	if ( ! $links ) {
		return '';
	}

	return '<nav class="lc-shop-pagination" aria-label="' . esc_attr__( 'Products pagination', 'ladycorrections' ) . '">' . $links . '</nav>';
}

/* ============================================================
   6. AJAX ENDPOINT
   ============================================================ */

/**
 * AJAX handler: lc_shop_filter.
 */
function lc_ajax_shop_filter() {
	check_ajax_referer( 'lc_shop', 'nonce' );

	$params     = lc_get_shop_filter_params();
	$args       = lc_get_shop_filter_query_args( $params );
	$result     = lc_render_shop_products( $args );
	$pagination = lc_shop_pagination_html( $result['pages'], $params['paged'], lc_shop_base_url( $params ) );

	wp_send_json_success(
		array(
			'html'       => $result['html'],
			'found'      => $result['found'],
			'pages'      => $result['pages'],
			'pagination' => $pagination,
		)
	);
}
add_action( 'wp_ajax_lc_shop_filter', 'lc_ajax_shop_filter' );
add_action( 'wp_ajax_nopriv_lc_shop_filter', 'lc_ajax_shop_filter' );

/* ============================================================
   7. ASSETS
   ============================================================ */

/**
 * Enqueue shop filter assets on the main shop page only.
 */
function lc_enqueue_shop_filters() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	if ( ! is_shop() || is_product_taxonomy() ) {
		return;
	}

	$css_path = LC_DIR . '/assets/css/lc-shop.css';
	$css_ver  = file_exists( $css_path ) ? filemtime( $css_path ) : LC_VERSION;
	wp_enqueue_style( 'lc-shop', LC_URI . '/assets/css/lc-shop.css', array( 'lc-style' ), $css_ver );

	$js_path = LC_DIR . '/assets/js/lc-shop.js';
	$js_ver  = file_exists( $js_path ) ? filemtime( $js_path ) : LC_VERSION;
	wp_enqueue_script( 'lc-shop', LC_URI . '/assets/js/lc-shop.js', array(), $js_ver, true );

	$shop_url = get_permalink( wc_get_page_id( 'shop' ) );
	if ( ! $shop_url ) {
		$shop_url = home_url( '/' );
	}

	wp_localize_script(
		'lc-shop',
		'lcShop',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'lc_shop' ),
			'shopUrl' => $shop_url,
			'i18n'    => array(
				/* translators: %d: number of products. */
				'show'    => get_theme_mod( 'lc_f_show', __( 'Show %d products', 'ladycorrections' ) ),
				'clear'   => get_theme_mod( 'lc_f_clear', __( 'Clear all filters', 'ladycorrections' ) ),
				/* translators: %d: number of products. */
				'results' => get_theme_mod( 'lc_f_results', __( '%d products', 'ladycorrections' ) ),
				'onSale'  => get_theme_mod( 'lc_f_onsale', __( 'On sale', 'ladycorrections' ) ),
				'inStock' => get_theme_mod( 'lc_f_instock', __( 'In stock', 'ladycorrections' ) ),
				/* translators: %s: filter label. */
				'remove'  => __( 'Remove filter: %s', 'ladycorrections' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'lc_enqueue_shop_filters' );
