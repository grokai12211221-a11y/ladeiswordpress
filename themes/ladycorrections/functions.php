<?php
/**
 * LadyCorrections theme functions.
 *
 * @package LadyCorrections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'LC_VERSION', '2.1.0' );
define( 'LC_DIR', get_template_directory() );
define( 'LC_URI', get_template_directory_uri() );

/**
 * Theme setup: supports, menus, text domain.
 */
function lc_setup() {
	load_theme_textdomain( 'ladycorrections', LC_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 320,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'style.css' );

	/* Gutenberg / block-editor compatibility. */
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support(
		'editor-color-palette',
		array(
			array( 'name' => __( 'Primary Pink', 'ladycorrections' ), 'slug' => 'lc-primary', 'color' => '#E91663' ),
			array( 'name' => __( 'Rose', 'ladycorrections' ), 'slug' => 'lc-rose', 'color' => '#F06292' ),
			array( 'name' => __( 'Rose Light', 'ladycorrections' ), 'slug' => 'lc-rose-light', 'color' => '#F8BBD0' ),
			array( 'name' => __( 'Blush', 'ladycorrections' ), 'slug' => 'lc-blush', 'color' => '#FFF0F5' ),
			array( 'name' => __( 'Berry', 'ladycorrections' ), 'slug' => 'lc-berry', 'color' => '#4A071E' ),
			array( 'name' => __( 'Gold', 'ladycorrections' ), 'slug' => 'lc-gold', 'color' => '#D4A24E' ),
			array( 'name' => __( 'Ink', 'ladycorrections' ), 'slug' => 'lc-ink', 'color' => '#1F1F2B' ),
		)
	);

	/* WooCommerce support (guarded so theme works without it). */
	if ( class_exists( 'WooCommerce' ) ) {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

	register_nav_menus(
		array(
			'primary'        => __( 'Primary Menu', 'ladycorrections' ),
			'mobile'         => __( 'Mobile Drawer Menu', 'ladycorrections' ),
			'footer-shop'    => __( 'Footer: Shop Links', 'ladycorrections' ),
			'footer-help'    => __( 'Footer: Help Links', 'ladycorrections' ),
			'footer-policies'=> __( 'Footer: Policy Links', 'ladycorrections' ),
		)
	);
}
add_action( 'after_setup_theme', 'lc_setup' );

/**
 * Enqueue styles and scripts with filemtime versioning.
 */
function lc_enqueue_assets() {
	$css_ver = file_exists( LC_DIR . '/style.css' ) ? filemtime( LC_DIR . '/style.css' ) : LC_VERSION;

	/* Google Fonts: Playfair Display (headings), Inter (body), Cairo (Arabic). */
	wp_enqueue_style(
		'lc-fonts',
		'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Inter:wght@400;500;600;700&family=Cairo:wght@400;600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'lc-style', get_stylesheet_uri(), array( 'lc-fonts' ), $css_ver );

	if ( class_exists( 'WooCommerce' ) ) {
		$woo_ver = file_exists( LC_DIR . '/assets/css/woocommerce.css' ) ? filemtime( LC_DIR . '/assets/css/woocommerce.css' ) : LC_VERSION;
		wp_enqueue_style( 'lc-woocommerce', LC_URI . '/assets/css/woocommerce.css', array( 'lc-style' ), $woo_ver );
	}

	$js_ver = file_exists( LC_DIR . '/assets/js/main.js' ) ? filemtime( LC_DIR . '/assets/js/main.js' ) : LC_VERSION;
	wp_enqueue_script( 'lc-main', LC_URI . '/assets/js/main.js', array(), $js_ver, true );

	/* Pass Customizer-driven data to JS (flash countdown date, ajax url). */
	wp_localize_script(
		'lc-main',
		'lcData',
		array(
			'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
			'flashEnd'     => get_theme_mod( 'lc_flash_end', '' ),
			'invalidEmail' => __( 'Please enter a valid email address.', 'ladycorrections' ),
			/* translators: %s: subscriber email address. */
			'subscribed'   => __( 'Thank you! Your 10% code is on its way to %s.', 'ladycorrections' ),
			'quickView'    => __( 'Quick view', 'ladycorrections' ),
			'viewDetails'  => __( 'View full details', 'ladycorrections' ),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'lc_enqueue_assets' );

/**
 * Register sidebars: shop sidebar + footer widgets.
 */
function lc_register_sidebars() {
	register_sidebar(
		array(
			'name'          => __( 'Shop Sidebar', 'ladycorrections' ),
			'id'            => 'shop-sidebar',
			'description'   => __( 'Appears on the shop page beside products.', 'ladycorrections' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
	register_sidebar(
		array(
			'name'          => __( 'Footer Widgets', 'ladycorrections' ),
			'id'            => 'footer-widgets',
			'description'   => __( 'Optional widgets above the footer columns.', 'ladycorrections' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);
}
add_action( 'widgets_init', 'lc_register_sidebars' );

/**
 * Customizer-driven CSS variables (brand colors).
 */
function lc_customizer_css() {
	$primary = get_theme_mod( 'lc_primary_color', '#E91663' );
	$berry   = get_theme_mod( 'lc_berry_color', '#4A071E' );
	?>
	<style id="lc-customizer-css">
		:root {
			--lc-primary: <?php echo esc_attr( $primary ); ?>;
			--lc-berry: <?php echo esc_attr( $berry ); ?>;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'lc_customizer_css', 20 );

/* ============================================================
   WOOCOMMERCE INTEGRATION (all guarded)
   ============================================================ */

/**
 * Remove WooCommerce default content wrappers.
 * The theme outputs its own container markup directly in woocommerce.php,
 * so the default unstyled wrappers are not needed.
 */
if ( class_exists( 'WooCommerce' ) ) {
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

	/* Breadcrumb delimiter + home label. */
	add_filter( 'woocommerce_breadcrumb_defaults', 'lc_woo_breadcrumbs' );
	add_filter( 'woocommerce_sale_flash', 'lc_sale_flash_percent', 10, 3 );
	add_filter( 'woocommerce_add_to_cart_fragments', 'lc_cart_count_fragment' );
	add_filter( 'woocommerce_show_page_title', '__return_false' );
}

/**
 * Breadcrumb tweaks.
 */
function lc_woo_breadcrumbs( $defaults ) {
	$defaults['delimiter']   = ' <span aria-hidden="true">&rsaquo;</span> ';
	$defaults['wrap_before'] = '<nav class="lc-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'ladycorrections' ) . '">';
	$defaults['wrap_after']  = '</nav>';
	$defaults['home']        = esc_html__( 'Home', 'ladycorrections' );
	return $defaults;
}

/**
 * Show "-X%" discount badge instead of "Sale!".
 */
function lc_sale_flash_percent( $html, $post, $product ) {
	if ( ! is_a( $product, 'WC_Product' ) ) {
		return $html;
	}
	if ( $product->is_type( 'variable' ) ) {
		$percent = lc_variable_max_discount( $product );
	} else {
		$regular = (float) $product->get_regular_price();
		$sale    = (float) $product->get_sale_price();
		$percent = ( $regular > 0 && $sale > 0 && $sale < $regular ) ? round( ( $regular - $sale ) / $regular * 100 ) : 0;
	}
	if ( $percent > 0 ) {
		return '<span class="onsale">-' . esc_html( $percent ) . '%</span>';
	}
	return $html;
}

/**
 * Max discount across variable product variations.
 */
function lc_variable_max_discount( $product ) {
	$max = 0;
	foreach ( $product->get_available_variations() as $variation ) {
		$regular = isset( $variation['display_regular_price'] ) ? (float) $variation['display_regular_price'] : 0;
		$price   = isset( $variation['display_price'] ) ? (float) $variation['display_price'] : 0;
		if ( $regular > 0 && $price > 0 && $price < $regular ) {
			$max = max( $max, round( ( $regular - $price ) / $regular * 100 ) );
		}
	}
	return (int) $max;
}

/**
 * AJAX cart count fragment for the header badge.
 */
function lc_cart_count_fragment( $fragments ) {
	ob_start();
	lc_cart_count_badge();
	$fragments['.lc-cart-count'] = ob_get_clean();
	return $fragments;
}

/**
 * Header cart count badge markup.
 */
function lc_cart_count_badge() {
	$count = function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	echo '<span class="lc-cart-count" aria-label="' . esc_attr__( 'Items in cart', 'ladycorrections' ) . '">' . esc_html( $count ) . '</span>';
}

/**
 * Wishlist button on product cards.
 *
 * Fires the overridable `lc_wishlist_button` action so wishlist plugins
 * (TI WooCommerce Wishlist, YITH, …) can replace the default heart.
 */
function lc_render_wishlist_button() {
	/**
	 * Output the wishlist button for a product card.
	 *
	 * Themes/plugins: remove the default and print your own markup here.
	 */
	do_action( 'lc_wishlist_button' );
}
function lc_wishlist_heart_default() {
	global $product;
	$pid = ( is_a( $product, 'WC_Product' ) ) ? $product->get_id() : 0;
	echo '<button type="button" class="lc-wishlist-btn" data-product-id="' . esc_attr( $pid ) . '" aria-label="' . esc_attr__( 'Add to wishlist', 'ladycorrections' ) . '" aria-pressed="false">';
	echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"/></svg>';
	echo '</button>';
}
add_action( 'lc_wishlist_button', 'lc_wishlist_heart_default', 10 );
if ( class_exists( 'WooCommerce' ) ) {
	add_action( 'woocommerce_before_shop_loop_item_title', 'lc_render_wishlist_button', 15 );
	add_action( 'woocommerce_before_shop_loop_item_title', 'lc_quickview_button', 16 );
}

/**
 * Quick-view trigger on product cards.
 * Renders product data as data-attributes; JS opens a modal (no AJAX needed).
 */
function lc_quickview_button() {
	global $product;
	if ( ! is_a( $product, 'WC_Product' ) ) {
		return;
	}
	$image_id  = $product->get_image_id();
	$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : wc_placeholder_img_src( 'large' );
	$rating    = $product->get_average_rating();
	$rcount    = $product->get_review_count();
	/* translators: %1$s: average rating, %2$d: review count. */
	$rating_txt = $rating > 0 ? sprintf( __( '%1$s out of 5 (%2$d reviews)', 'ladycorrections' ), number_format_i18n( (float) $rating, 1 ), (int) $rcount ) : __( 'Not rated yet', 'ladycorrections' );
	echo '<span class="lc-quickview-btn" role="button" tabindex="0"'
		. ' aria-label="' . esc_attr__( 'Quick view', 'ladycorrections' ) . '"'
		. ' data-lc-qv'
		. ' data-title="' . esc_attr( $product->get_name() ) . '"'
		. ' data-price="' . esc_attr( $product->get_price_html() ) . '"'
		. ' data-image="' . esc_url( $image_url ) . '"'
		. ' data-url="' . esc_url( $product->get_permalink() ) . '"'
		. ' data-rating="' . esc_attr( $rating_txt ) . '"'
		. ' data-stars="' . esc_attr( str_repeat( '&#9733;', (int) round( $rating ) ) ) . '"'
		. '><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>'
		. '<span>' . esc_html__( 'Quick view', 'ladycorrections' ) . '</span>'
		. '</span>';
}

/* ============================================================
   ELEMENTOR COMPATIBILITY
   ============================================================ */

/**
 * Register Elementor Pro Theme Builder locations so users can
 * override header / footer / archive / single with Elementor.
 */
function lc_register_elementor_locations( $manager ) {
	$manager->register_location( 'header' );
	$manager->register_location( 'footer' );
	$manager->register_location( 'archive' );
	$manager->register_location( 'single' );
}
add_action( 'elementor/theme/register_locations', 'lc_register_elementor_locations' );

/* ============================================================
   HELPERS
   ============================================================ */

/**
 * Theme template container open/close for non-Woo pages.
 */
function lc_content_open() {
	echo '<main id="lc-main" class="lc-main"><div class="lc-container lc-section">';
}
function lc_content_close() {
	echo '</div></main>';
}

/**
 * Excerpt length.
 */
function lc_excerpt_length( $length ) {
	return 24;
}
add_filter( 'excerpt_length', 'lc_excerpt_length' );

/**
 * Body classes.
 */
function lc_body_classes( $classes ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		$classes[] = 'lc-no-woo';
	}
	return $classes;
}
add_filter( 'body_class', 'lc_body_classes' );

/* Load Customizer. */
require_once LC_DIR . '/inc/customizer.php';

/* AJAX shop filters (created for v2.1). */
if ( file_exists( LC_DIR . '/inc/shop-filters.php' ) ) {
	require_once LC_DIR . '/inc/shop-filters.php';
}

/* Wishlist page AJAX endpoint (created for v2.1). */
if ( file_exists( LC_DIR . '/inc/wishlist-ajax.php' ) ) {
	require_once LC_DIR . '/inc/wishlist-ajax.php';
}

/* Cart/checkout asset loader (created for v2.1). */
if ( file_exists( LC_DIR . '/inc/checkout-assets.php' ) ) {
	require_once LC_DIR . '/inc/checkout-assets.php';
}

/**
 * Live-preview JS for Customizer postMessage settings (brand colors).
 */
function lc_customize_preview_js() {
	wp_enqueue_script(
		'lc-customize-preview',
		LC_URI . '/assets/js/customize-preview.js',
		array( 'customize-preview' ),
		LC_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'lc_customize_preview_js' );

/**
 * Assets for the v2.1 full-store page templates (About, Contact, FAQ,
 * Track, Wishlist, Info). Loaded only on those templates.
 */
function lc_page_templates_assets() {
	$templates = array( 'page-about.php', 'page-contact.php', 'page-faq.php', 'page-track.php', 'page-wishlist.php', 'page-info.php' );
	if ( ! is_page_template( $templates ) ) {
		return;
	}
	if ( file_exists( LC_DIR . '/assets/css/lc-pages.css' ) ) {
		wp_enqueue_style( 'lc-pages', LC_URI . '/assets/css/lc-pages.css', array( 'lc-style' ), LC_VERSION );
	}
	if ( is_page_template( 'page-faq.php' ) && file_exists( LC_DIR . '/assets/js/lc-pages.js' ) ) {
		wp_enqueue_script( 'lc-pages', LC_URI . '/assets/js/lc-pages.js', array(), LC_VERSION, true );
	}
	if ( is_page_template( 'page-wishlist.php' ) && file_exists( LC_DIR . '/assets/js/lc-wishlist.js' ) ) {
		wp_enqueue_script( 'lc-wishlist-page', LC_URI . '/assets/js/lc-wishlist.js', array(), LC_VERSION, true );
		wp_localize_script(
			'lc-wishlist-page',
			'lcWish',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'lc_wish' ),
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'lc_page_templates_assets' );
