<?php
/**
 * Shop toolbar: AJAX filter form, mobile drawer, active-filter chips,
 * and the live result count.
 *
 * Loaded from woocommerce.php on the main shop page only (not on product
 * taxonomy archives). The form submits via GET to the shop URL, so the
 * plain no-JS fallback works: the server reads $_GET in
 * lc_get_shop_filter_params(). With JS enabled, lc-shop.js intercepts
 * changes and fetches filtered results via admin-ajax.
 *
 * @package LadyCorrections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

$params   = lc_get_shop_filter_params();
$bounds   = lc_get_price_bounds();
$shop_url = get_permalink( wc_get_page_id( 'shop' ) );
if ( ! $shop_url ) {
	$shop_url = home_url( '/' );
}

$cats = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'parent'     => 0,
		'hide_empty' => true,
	)
);
if ( is_wp_error( $cats ) ) {
	$cats = array();
}

$orderby_options = array(
	'menu_order' => __( 'Default', 'ladycorrections' ),
	'popularity' => __( 'Popularity', 'ladycorrections' ),
	'rating'     => __( 'Average rating', 'ladycorrections' ),
	'date'       => __( 'Newest', 'ladycorrections' ),
	'price'      => __( 'Price: low to high', 'ladycorrections' ),
	'price-desc' => __( 'Price: high to low', 'ladycorrections' ),
);

$filters_title = get_theme_mod( 'lc_f_title', __( 'Filters', 'ladycorrections' ) );
$initial_found = (int) get_query_var( 'lc_shop_found', 0 );
?>
<div class="lc-shop-toolbar" id="lc-shop-toolbar">

	<button type="button" class="lc-filters-open" id="lc-filters-open" aria-controls="lc-shop-filters" aria-expanded="false">
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 5h18l-7 8v5l-4 2v-7L3 5z" stroke-linejoin="round"/></svg>
		<span><?php echo esc_html( $filters_title ); ?></span>
		<span class="lc-filters-count" id="lc-filters-count" hidden>0</span>
	</button>

	<form id="lc-shop-filters" class="lc-shop-filters" method="get" action="<?php echo esc_url( $shop_url ); ?>" aria-label="<?php echo esc_attr( $filters_title ); ?>">
		<input type="hidden" name="paged" value="<?php echo esc_attr( max( 1, (int) $params['paged'] ) ); ?>">

		<div class="lc-filters-head">
			<h2><?php echo esc_html( $filters_title ); ?></h2>
			<button type="button" class="lc-filters-close" id="lc-filters-close" aria-label="<?php esc_attr_e( 'Close filters', 'ladycorrections' ); ?>">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>

		<div class="lc-filters-body">

			<div class="lc-toolbar-row lc-toolbar-row--main">
				<label class="lc-search-wrap">
					<span class="screen-reader-text"><?php esc_html_e( 'Search products', 'ladycorrections' ); ?></span>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
					<input type="search" name="s" class="lc-search-input" placeholder="<?php esc_attr_e( 'Search products…', 'ladycorrections' ); ?>" value="<?php echo esc_attr( $params['s'] ); ?>" autocomplete="off">
				</label>

				<label class="lc-sort-wrap">
					<span class="lc-sort-label"><?php echo esc_html( get_theme_mod( 'lc_f_sort_l', __( 'Sort by', 'ladycorrections' ) ) ); ?></span>
					<span class="lc-select-wrap">
						<select name="orderby" class="lc-sort-select">
							<?php foreach ( $orderby_options as $value => $label ) : ?>
								<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $params['orderby'] ? $params['orderby'] : 'menu_order', $value ); ?>>
									<?php echo esc_html( $label ); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
					</span>
				</label>

				<div class="lc-toggles">
					<label class="lc-switch">
						<input type="checkbox" name="on_sale" value="1" <?php checked( $params['on_sale'] ); ?>>
						<span class="lc-switch-track" aria-hidden="true"><span class="lc-switch-thumb"></span></span>
						<span class="lc-switch-label"><?php echo esc_html( get_theme_mod( 'lc_f_onsale', __( 'On sale', 'ladycorrections' ) ) ); ?></span>
					</label>
					<label class="lc-switch">
						<input type="checkbox" name="in_stock" value="1" <?php checked( $params['in_stock'] ); ?>>
						<span class="lc-switch-track" aria-hidden="true"><span class="lc-switch-thumb"></span></span>
						<span class="lc-switch-label"><?php echo esc_html( get_theme_mod( 'lc_f_instock', __( 'In stock', 'ladycorrections' ) ) ); ?></span>
					</label>
				</div>

				<fieldset class="lc-filter-price">
					<legend class="screen-reader-text"><?php echo esc_html( get_theme_mod( 'lc_f_price_l', __( 'Price', 'ladycorrections' ) ) ); ?></legend>
					<div class="lc-price-row">
						<label class="lc-price-field">
							<span class="screen-reader-text"><?php esc_html_e( 'Minimum price', 'ladycorrections' ); ?></span>
							<input type="number" name="min_price" min="<?php echo esc_attr( $bounds['min'] ); ?>" max="<?php echo esc_attr( $bounds['max'] ); ?>" step="any" inputmode="decimal" placeholder="<?php echo esc_attr( get_theme_mod( 'lc_f_min', __( 'Min', 'ladycorrections' ) ) ); ?>" value="<?php echo esc_attr( $params['min_price'] ); ?>" aria-label="<?php esc_attr_e( 'Minimum price', 'ladycorrections' ); ?>">
						</label>
						<span class="lc-price-sep" aria-hidden="true">&ndash;</span>
						<label class="lc-price-field">
							<span class="screen-reader-text"><?php esc_html_e( 'Maximum price', 'ladycorrections' ); ?></span>
							<input type="number" name="max_price" min="<?php echo esc_attr( $bounds['min'] ); ?>" max="<?php echo esc_attr( $bounds['max'] ); ?>" step="any" inputmode="decimal" placeholder="<?php echo esc_attr( get_theme_mod( 'lc_f_max', __( 'Max', 'ladycorrections' ) ) ); ?>" value="<?php echo esc_attr( $params['max_price'] ); ?>" aria-label="<?php esc_attr_e( 'Maximum price', 'ladycorrections' ); ?>">
						</label>
						<button type="submit" class="lc-btn lc-btn--outline lc-price-apply"><?php echo esc_html( get_theme_mod( 'lc_f_apply', __( 'Apply', 'ladycorrections' ) ) ); ?></button>
					</div>
				</fieldset>
			</div>

			<div class="lc-toolbar-row lc-toolbar-row--cats">
				<fieldset class="lc-filter-cats">
					<legend class="screen-reader-text"><?php echo esc_html( get_theme_mod( 'lc_f_cat_l', __( 'Category', 'ladycorrections' ) ) ); ?></legend>
					<div class="lc-pills" role="radiogroup" aria-label="<?php echo esc_attr( get_theme_mod( 'lc_f_cat_l', __( 'Category', 'ladycorrections' ) ) ); ?>">
						<label class="lc-pill">
							<input type="radio" name="cat" value="" <?php checked( $params['cat'], '' ); ?>>
							<span><?php echo esc_html( get_theme_mod( 'lc_f_all', __( 'All', 'ladycorrections' ) ) ); ?></span>
						</label>
						<?php foreach ( $cats as $cat ) : ?>
							<label class="lc-pill">
								<input type="radio" name="cat" value="<?php echo esc_attr( $cat->slug ); ?>" <?php checked( $params['cat'], $cat->slug ); ?>>
								<span><?php echo esc_html( $cat->name ); ?> <em class="lc-pill-count"><?php echo absint( $cat->count ); ?></em></span>
							</label>
						<?php endforeach; ?>
					</div>
				</fieldset>
			</div>

			<noscript>
				<p><button type="submit" class="lc-btn lc-btn--primary"><?php echo esc_html( get_theme_mod( 'lc_f_apply', __( 'Apply filters', 'ladycorrections' ) ) ); ?></button></p>
			</noscript>

		</div>

		<div class="lc-filters-foot">
			<button type="button" class="lc-btn lc-btn--outline lc-filters-clear" id="lc-filters-clear">
				<?php echo esc_html( get_theme_mod( 'lc_f_clear', __( 'Clear all filters', 'ladycorrections' ) ) ); ?>
			</button>
			<button type="button" class="lc-btn lc-btn--primary lc-filters-apply" id="lc-filters-apply">
				<?php
				/* translators: %d: number of products. */
				echo esc_html( sprintf( get_theme_mod( 'lc_f_show', __( 'Show %d products', 'ladycorrections' ) ), $initial_found ) );
				?>
			</button>
		</div>
	</form>

	<div class="lc-active-chips" id="lc-active-chips" aria-live="polite"></div>

	<p class="lc-result-count" id="lc-result-count" aria-live="polite">
		<?php
		/* translators: %d: number of products. */
		echo esc_html( sprintf( get_theme_mod( 'lc_f_results', __( '%d products', 'ladycorrections' ) ), $initial_found ) );
		?>
	</p>

</div>
