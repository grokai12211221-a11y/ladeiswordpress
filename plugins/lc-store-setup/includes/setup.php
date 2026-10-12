<?php
/**
 * Idempotent setup routines: pages, menus, store basics.
 *
 * Every routine checks existing state (by slug, option or name) before
 * creating anything, so re-running is always safe.
 *
 * @package LC_Store_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ============================================================
 * 1. STORE PAGES
 * ============================================================ */

/**
 * Status of the 9 store pages: slug => post ID or false.
 *
 * @return array
 */
function lc_setup_page_status() {
	$status = array();
	foreach ( lc_setup_get_pages() as $page ) {
		$post = get_page_by_path( $page['slug'], OBJECT, 'page' );
		$status[ $page['slug'] ] = ( $post instanceof WP_Post ) ? (int) $post->ID : false;
	}
	return $status;
}

/**
 * Create missing store pages and assign their theme templates.
 *
 * @return array { created: int, skipped: int }
 */
function lc_setup_create_pages() {
	$created = 0;
	$skipped = 0;

	foreach ( lc_setup_get_pages() as $page ) {
		if ( get_page_by_path( $page['slug'], OBJECT, 'page' ) instanceof WP_Post ) {
			$skipped++;
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_title'   => $page['title'],
				'post_name'    => $page['slug'],
				'post_content' => lc_setup_build_content( $page ),
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_author'  => get_current_user_id(),
			),
			true
		);

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		update_post_meta( $post_id, '_wp_page_template', $page['template'] );
		$created++;
	}

	return array(
		'created' => $created,
		'skipped' => $skipped,
	);
}

/* ============================================================
 * 2. MENUS
 * ============================================================ */

/**
 * Resolve a menu item target to a URL.
 *
 * @param array $item Item definition.
 * @return string URL (may be empty if unresolvable).
 */
function lc_setup_resolve_item_url( $item ) {
	$type = isset( $item['type'] ) ? $item['type'] : 'custom';

	if ( 'home' === $type ) {
		return home_url( '/' );
	}

	if ( 'shop' === $type ) {
		if ( class_exists( 'WooCommerce' ) && function_exists( 'wc_get_page_permalink' ) ) {
			$url = wc_get_page_permalink( 'shop' );
			if ( $url ) {
				return $url;
			}
		}
		return home_url( '/shop/' );
	}

	if ( 'myaccount' === $type ) {
		if ( class_exists( 'WooCommerce' ) && function_exists( 'wc_get_page_permalink' ) ) {
			$url = wc_get_page_permalink( 'myaccount' );
			if ( $url ) {
				return $url;
			}
		}
		return home_url( '/my-account/' );
	}

	if ( 'page' === $type && ! empty( $item['slug'] ) ) {
		$post = get_page_by_path( $item['slug'], OBJECT, 'page' );
		if ( $post instanceof WP_Post ) {
			return get_permalink( $post->ID );
		}
		return '';
	}

	if ( 'custom' === $type && ! empty( $item['url'] ) ) {
		$url = $item['url'];
		if ( false !== strpos( $url, '{shop_url}' ) ) {
			$url = str_replace( '{shop_url}', untrailingslashit( lc_setup_resolve_item_url( array( 'type' => 'shop' ) ) ), $url );
		}
		return $url;
	}

	return '';
}

/**
 * Menu definitions: menu slug => name, theme location, items.
 *
 * @return array
 */
function lc_setup_get_menu_definitions() {
	return array(
		'lc-primary'      => array(
			'name'     => __( 'Primary Menu', 'lc-store-setup' ),
			'location' => 'primary',
			'items'    => array(
				array( 'type' => 'home', 'title' => __( 'Home', 'lc-store-setup' ) ),
				array( 'type' => 'shop', 'title' => __( 'Shop', 'lc-store-setup' ) ),
				array( 'type' => 'custom', 'title' => __( 'Sale', 'lc-store-setup' ), 'url' => '{shop_url}/?onsale=1' ),
				array( 'type' => 'page', 'title' => __( 'About', 'lc-store-setup' ), 'slug' => 'about' ),
				array( 'type' => 'page', 'title' => __( 'Contact', 'lc-store-setup' ), 'slug' => 'contact' ),
				array( 'type' => 'page', 'title' => __( 'Track Order', 'lc-store-setup' ), 'slug' => 'track-order' ),
			),
		),
		'lc-mobile'       => array(
			'name'     => __( 'Mobile Menu', 'lc-store-setup' ),
			'location' => 'mobile',
			'items'    => array(
				array( 'type' => 'home', 'title' => __( 'Home', 'lc-store-setup' ) ),
				array( 'type' => 'shop', 'title' => __( 'Shop', 'lc-store-setup' ) ),
				array( 'type' => 'shop', 'title' => __( 'Categories', 'lc-store-setup' ) ),
				array( 'type' => 'page', 'title' => __( 'Wishlist', 'lc-store-setup' ), 'slug' => 'wishlist' ),
				array( 'type' => 'myaccount', 'title' => __( 'My Account', 'lc-store-setup' ) ),
				array( 'type' => 'page', 'title' => __( 'Track Order', 'lc-store-setup' ), 'slug' => 'track-order' ),
				array( 'type' => 'page', 'title' => __( 'Contact', 'lc-store-setup' ), 'slug' => 'contact' ),
			),
		),
		'lc-footer-shop'  => array(
			'name'     => __( 'Footer Shop Menu', 'lc-store-setup' ),
			'location' => 'footer-shop',
			'items'    => array(
				array( 'type' => 'shop', 'title' => __( 'Shop', 'lc-store-setup' ) ),
				array( 'type' => 'custom', 'title' => __( 'Sale', 'lc-store-setup' ), 'url' => '{shop_url}/?onsale=1' ),
				array( 'type' => 'page', 'title' => __( 'Wishlist', 'lc-store-setup' ), 'slug' => 'wishlist' ),
				array( 'type' => 'page', 'title' => __( 'Track Order', 'lc-store-setup' ), 'slug' => 'track-order' ),
				array( 'type' => 'myaccount', 'title' => __( 'My Account', 'lc-store-setup' ) ),
			),
		),
		'lc-footer-help'  => array(
			'name'     => __( 'Footer Help Menu', 'lc-store-setup' ),
			'location' => 'footer-help',
			'items'    => array(
				array( 'type' => 'page', 'title' => __( 'FAQ', 'lc-store-setup' ), 'slug' => 'faq' ),
				array( 'type' => 'page', 'title' => __( 'Shipping & Delivery', 'lc-store-setup' ), 'slug' => 'shipping-info' ),
				array( 'type' => 'page', 'title' => __( 'Returns & Refunds', 'lc-store-setup' ), 'slug' => 'returns-refunds' ),
				array( 'type' => 'page', 'title' => __( 'Contact Us', 'lc-store-setup' ), 'slug' => 'contact' ),
			),
		),
		'lc-footer-policies' => array(
			'name'     => __( 'Footer Policies Menu', 'lc-store-setup' ),
			'location' => 'footer-policies',
			'items'    => array(
				array( 'type' => 'page', 'title' => __( 'Privacy Policy', 'lc-store-setup' ), 'slug' => 'privacy-policy' ),
				array( 'type' => 'page', 'title' => __( 'Terms & Conditions', 'lc-store-setup' ), 'slug' => 'terms-conditions' ),
			),
		),
	);
}

/**
 * Status of menu location assignments: location => menu name or false.
 *
 * @return array
 */
function lc_setup_menu_status() {
	$status    = array();
	$locations = get_nav_menu_locations();

	foreach ( lc_setup_get_menu_definitions() as $def ) {
		$loc = $def['location'];
		if ( isset( $locations[ $loc ] ) && $locations[ $loc ] ) {
			$menu = wp_get_nav_menu_object( $locations[ $loc ] );
			$status[ $loc ] = ( $menu instanceof WP_Term ) ? $menu->name : false;
		} else {
			$status[ $loc ] = false;
		}
	}

	return $status;
}

/**
 * Add a single menu item if an item with the same title doesn't exist yet.
 *
 * @param int   $menu_id Menu term ID.
 * @param array $item    Item definition.
 * @return bool True if added, false if skipped.
 */
function lc_setup_menu_add_item( $menu_id, $item ) {
	$existing = wp_get_nav_menu_items( $menu_id );
	if ( is_array( $existing ) ) {
		foreach ( $existing as $existing_item ) {
			if ( $existing_item->title === $item['title'] ) {
				return false;
			}
		}
	}

	$args = array(
		'menu-item-title'  => $item['title'],
		'menu-item-status' => 'publish',
	);

	$type = isset( $item['type'] ) ? $item['type'] : 'custom';

	if ( 'page' === $type && ! empty( $item['slug'] ) ) {
		$post = get_page_by_path( $item['slug'], OBJECT, 'page' );
		if ( ! $post instanceof WP_Post ) {
			return false;
		}
		$args['menu-item-type']      = 'post_type';
		$args['menu-item-object']    = 'page';
		$args['menu-item-object-id'] = $post->ID;
	} else {
		$url = lc_setup_resolve_item_url( $item );
		if ( '' === $url ) {
			return false;
		}
		$args['menu-item-type'] = 'custom';
		$args['menu-item-url']  = esc_url_raw( $url );
	}

	$result = wp_update_nav_menu_item( $menu_id, 0, $args );
	return ! is_wp_error( $result ) && $result;
}

/**
 * Create menus (if missing), populate missing items, assign theme locations.
 *
 * @return array { menus_created: int, items_added: int, locations_assigned: int }
 */
function lc_setup_create_menus() {
	$menus_created      = 0;
	$items_added        = 0;
	$locations_assigned = 0;
	$registered         = get_registered_nav_menus();

	foreach ( lc_setup_get_menu_definitions() as $menu_slug => $def ) {
		// Re-detect our menu: by slug first, then by the slug tag we store as term meta
		// (wp_create_nav_menu generates its own slug from the menu name).
		$menu = wp_get_nav_menu_object( $menu_slug );
		if ( ! $menu instanceof WP_Term ) {
			$all_menus = wp_get_nav_menus();
			if ( is_array( $all_menus ) ) {
				foreach ( $all_menus as $candidate ) {
					if ( get_term_meta( $candidate->term_id, '_lc_setup_slug', true ) === $menu_slug ) {
						$menu = $candidate;
						break;
					}
				}
			}
		}

		if ( ! $menu instanceof WP_Term ) {
			$menu_id = wp_create_nav_menu( $def['name'] );
			if ( is_wp_error( $menu_id ) || ! $menu_id ) {
				continue;
			}
			// Tag the menu with our slug for reliable re-detection.
			update_term_meta( $menu_id, '_lc_setup_slug', $menu_slug );
			$menus_created++;
		} else {
			$menu_id = (int) $menu->term_id;
		}

		foreach ( $def['items'] as $item ) {
			if ( lc_setup_menu_add_item( $menu_id, $item ) ) {
				$items_added++;
			}
		}

		// Assign to the theme location — only if the active theme registers it.
		$loc = $def['location'];
		if ( isset( $registered[ $loc ] ) ) {
			$locations              = get_nav_menu_locations();
			$locations[ $loc ]      = $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
			$locations_assigned++;
		}
	}

	return array(
		'menus_created'      => $menus_created,
		'items_added'        => $items_added,
		'locations_assigned' => $locations_assigned,
	);
}

/* ============================================================
 * 3. STORE BASICS
 * ============================================================ */

/**
 * Status of store basics.
 *
 * @return array
 */
function lc_setup_basics_status() {
	$woo = class_exists( 'WooCommerce' );

	$coupon_exists = false;
	$cod_enabled   = false;
	$has_zones     = false;

	if ( $woo ) {
		$coupon        = get_page_by_path( 'welcome10', OBJECT, 'shop_coupon' );
		$coupon_exists = ( $coupon instanceof WP_Post );

		$cod_settings = get_option( 'woocommerce_cod_settings', array() );
		$cod_enabled  = ( is_array( $cod_settings ) && isset( $cod_settings['enabled'] ) && 'yes' === $cod_settings['enabled'] );

		if ( class_exists( 'WC_Shipping_Zones' ) ) {
			$has_zones = count( WC_Shipping_Zones::get_zones() ) > 0;
		}
	}

	return array(
		'front_page' => (int) get_option( 'page_on_front' ) > 0,
		'blog_page'  => (int) get_option( 'page_for_posts' ) > 0,
		'coupon'     => $woo ? $coupon_exists : 'na',
		'cod'        => $woo ? $cod_enabled : 'na',
		'shipping'   => $woo ? $has_zones : 'na',
		'woo_active' => $woo,
	);
}

/**
 * Create the WELCOME10 coupon if WooCommerce is active and it doesn't exist.
 *
 * @return bool True if created, false otherwise.
 */
function lc_setup_create_coupon() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return false;
	}

	if ( get_page_by_path( 'welcome10', OBJECT, 'shop_coupon' ) instanceof WP_Post ) {
		return false;
	}

	$coupon_id = wp_insert_post(
		array(
			'post_title'  => 'WELCOME10',
			'post_name'   => 'welcome10',
			'post_status' => 'publish',
			'post_type'   => 'shop_coupon',
			'post_author' => get_current_user_id(),
		),
		true
	);

	if ( is_wp_error( $coupon_id ) || ! $coupon_id ) {
		return false;
	}

	update_post_meta( $coupon_id, 'discount_type', 'percent' );
	update_post_meta( $coupon_id, 'coupon_amount', '10' );

	return true;
}

/**
 * Enable Cash on Delivery and move it first in the gateway order.
 *
 * @return bool True if COD is enabled afterwards.
 */
function lc_setup_enable_cod() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return false;
	}

	$settings = get_option( 'woocommerce_cod_settings', array() );
	if ( ! is_array( $settings ) ) {
		$settings = array();
	}
	$settings['enabled'] = 'yes';
	update_option( 'woocommerce_cod_settings', $settings );

	// Move COD to the top of the gateway order, preserving the rest.
	$order = get_option( 'woocommerce_gateway_order', array() );
	if ( ! is_array( $order ) ) {
		$order = array();
	}
	unset( $order['cod'] );
	$order = array_merge( array( 'cod' => 0 ), $order );
	$index = 0;
	foreach ( $order as $gateway_id => $old ) {
		$order[ $gateway_id ] = $index++;
	}
	update_option( 'woocommerce_gateway_order', $order );

	return true;
}

/**
 * Create the "Iraq" shipping zone with flat rate + free shipping over 50,000 IQD.
 * Only runs when no shipping zones exist yet.
 *
 * @return bool True if created, false otherwise.
 */
function lc_setup_create_shipping_zone() {
	if ( ! class_exists( 'WooCommerce' ) || ! class_exists( 'WC_Shipping_Zone' ) || ! class_exists( 'WC_Shipping_Zones' ) ) {
		return false;
	}

	if ( count( WC_Shipping_Zones::get_zones() ) > 0 ) {
		return false;
	}

	$zone = new WC_Shipping_Zone();
	$zone->set_zone_name( 'Iraq' );
	$zone->add_location( 'IQ', 'country' );
	$zone->save();

	if ( ! $zone->get_id() ) {
		return false;
	}

	// Flat rate: 5,000 IQD "Standard Delivery".
	$flat_id = $zone->add_shipping_method( 'flat_rate' );
	if ( $flat_id ) {
		update_option(
			"woocommerce_flat_rate_{$flat_id}_settings",
			array(
				'title' => __( 'Standard Delivery', 'lc-store-setup' ),
				'cost'  => '5000',
			)
		);
	}

	// Free shipping on orders over 50,000 IQD.
	$free_id = $zone->add_shipping_method( 'free_shipping' );
	if ( $free_id ) {
		update_option(
			"woocommerce_free_shipping_{$free_id}_settings",
			array(
				'title'      => __( 'Free Shipping', 'lc-store-setup' ),
				'requires'   => 'min_amount',
				'min_amount' => '50000',
			)
		);
	}

	return true;
}

/**
 * Configure store basics: homepage, blog page, coupon, COD, shipping zone.
 *
 * @return array Summary of what was done.
 */
function lc_setup_configure_basics() {
	$done = array(
		'front_page' => false,
		'blog_page'  => false,
		'coupon'     => false,
		'cod'        => false,
		'shipping'   => false,
	);

	// Front page: reuse existing "Home" page or create a blank one.
	$home = get_page_by_path( 'home', OBJECT, 'page' );
	if ( ! $home instanceof WP_Post ) {
		$home_id = wp_insert_post(
			array(
				'post_title'   => __( 'Home', 'lc-store-setup' ),
				'post_name'    => 'home',
				'post_content' => '',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_author'  => get_current_user_id(),
			),
			true
		);
		if ( ! is_wp_error( $home_id ) && $home_id ) {
			update_post_meta( $home_id, '_wp_page_template', 'front-page.php' );
			$home = get_post( $home_id );
		}
	}
	if ( $home instanceof WP_Post ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home->ID );
		$done['front_page'] = true;
	}

	// Blog page: reuse existing or create.
	$blog = get_page_by_path( 'blog', OBJECT, 'page' );
	if ( ! $blog instanceof WP_Post ) {
		$blog_id = wp_insert_post(
			array(
				'post_title'   => __( 'Blog', 'lc-store-setup' ),
				'post_name'    => 'blog',
				'post_content' => '',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_author'  => get_current_user_id(),
			),
			true
		);
		if ( ! is_wp_error( $blog_id ) && $blog_id ) {
			$blog = get_post( $blog_id );
		}
	}
	if ( $blog instanceof WP_Post ) {
		update_option( 'page_for_posts', $blog->ID );
		$done['blog_page'] = true;
	}

	$done['coupon']   = lc_setup_create_coupon();
	$done['cod']      = lc_setup_enable_cod();
	$done['shipping'] = lc_setup_create_shipping_zone();

	return $done;
}
