<?php
/**
 * Admin dashboard: WP Admin → "LC Store Setup".
 *
 * @package LC_Store_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the admin menu page.
 */
function lc_setup_admin_menu() {
	add_menu_page(
		__( 'LC Store Setup', 'lc-store-setup' ),
		__( 'LC Store Setup', 'lc-store-setup' ),
		'manage_options',
		'lc-store-setup',
		'lc_setup_render_page',
		'dashicons-store',
		59
	);
}
add_action( 'admin_menu', 'lc_setup_admin_menu' );

/**
 * Handle the three setup actions (POST + nonce + capability).
 */
function lc_setup_handle_actions() {
	if ( ! isset( $_POST['lc_setup_action'] ) ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'lc-store-setup' ) );
	}

	check_admin_referer( 'lc_setup_run' );

	$action = sanitize_key( wp_unslash( $_POST['lc_setup_action'] ) );
	$result = '';

	if ( 'create_pages' === $action ) {
		$res    = lc_setup_create_pages();
		$result = sprintf( 'pages:%d:%d', $res['created'], $res['skipped'] );
	} elseif ( 'create_menus' === $action ) {
		$res    = lc_setup_create_menus();
		$result = sprintf( 'menus:%d:%d:%d', $res['menus_created'], $res['items_added'], $res['locations_assigned'] );
	} elseif ( 'configure_basics' === $action ) {
		lc_setup_configure_basics();
		$result = 'basics:1';
	}

	wp_safe_redirect(
		add_query_arg(
			array(
				'page'           => 'lc-store-setup',
				'lc_setup_done'  => $action,
				'lc_setup_stats' => $result,
			),
			admin_url( 'admin.php' )
		)
	);
	exit;
}
add_action( 'admin_init', 'lc_setup_handle_actions' );

/**
 * Render a status badge.
 *
 * @param bool|string $state True = done, false = pending, 'na' = not applicable.
 * @return void
 */
function lc_setup_badge( $state ) {
	if ( 'na' === $state ) {
		echo '<span class="lc-setup-badge lc-setup-badge--na">' . esc_html__( 'N/A — needs WooCommerce', 'lc-store-setup' ) . '</span>';
	} elseif ( $state ) {
		echo '<span class="lc-setup-badge lc-setup-badge--ok">&#10003; ' . esc_html__( 'Done', 'lc-store-setup' ) . '</span>';
	} else {
		echo '<span class="lc-setup-badge lc-setup-badge--todo">' . esc_html__( 'Pending', 'lc-store-setup' ) . '</span>';
	}
}

/**
 * Render the dashboard page.
 *
 * @return void
 */
function lc_setup_render_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'lc-store-setup' ) );
	}

	$page_status  = lc_setup_page_status();
	$menu_status  = lc_setup_menu_status();
	$basic_status = lc_setup_basics_status();

	$pages_done = count( array_filter( $page_status ) );
	$pages_total = count( $page_status );
	$menus_done = count( array_filter( $menu_status ) );
	$menus_total = count( $menu_status );

	$basics_checks = array( 'front_page', 'blog_page', 'coupon', 'cod', 'shipping' );
	$basics_done   = 0;
	foreach ( $basics_checks as $check ) {
		if ( true === $basic_status[ $check ] ) {
			$basics_done++;
		}
	}

	$done_action = isset( $_GET['lc_setup_done'] ) ? sanitize_key( wp_unslash( $_GET['lc_setup_done'] ) ) : '';
	?>
	<div class="wrap lc-setup-wrap">
		<h1><?php esc_html_e( 'LC Store Setup', 'lc-store-setup' ); ?></h1>
		<p class="description">
			<?php esc_html_e( 'One-click wizard that turns a fresh WordPress + WooCommerce install into a complete LadyCorrections store. Every step is safe to re-run — existing content is never duplicated.', 'lc-store-setup' ); ?>
		</p>

		<?php if ( $done_action ) : ?>
			<div class="notice notice-success is-dismissible">
				<p>
					<?php
					if ( 'create_pages' === $done_action ) {
						esc_html_e( 'Store pages checked and created.', 'lc-store-setup' );
					} elseif ( 'create_menus' === $done_action ) {
						esc_html_e( 'Menus created and assigned.', 'lc-store-setup' );
					} elseif ( 'configure_basics' === $done_action ) {
						esc_html_e( 'Store basics configured.', 'lc-store-setup' );
					}
					?>
				</p>
			</div>
		<?php endif; ?>

		<?php if ( ! $basic_status['woo_active'] ) : ?>
			<div class="notice notice-warning">
				<p><?php esc_html_e( 'WooCommerce is not active. Coupon, COD gateway and shipping zone steps will be skipped until WooCommerce is installed and activated.', 'lc-store-setup' ); ?></p>
			</div>
		<?php endif; ?>

		<div class="lc-setup-grid">

			<div class="lc-setup-card">
				<h2>1. <?php esc_html_e( 'Store Pages', 'lc-store-setup' ); ?></h2>
				<p><?php esc_html_e( 'Creates 9 bilingual (EN + AR) pages and assigns the matching theme templates: About, Contact, FAQ, Shipping, Returns, Privacy, Terms, Track Order, Wishlist.', 'lc-store-setup' ); ?></p>
				<ul class="lc-setup-list">
					<?php foreach ( lc_setup_get_pages() as $page ) : ?>
						<li>
							<?php lc_setup_badge( ! empty( $page_status[ $page['slug'] ] ) ); ?>
							<?php echo esc_html( $page['title'] ); ?>
							<code><?php echo esc_html( $page['slug'] ); ?></code>
						</li>
					<?php endforeach; ?>
				</ul>
				<form method="post" action="">
					<?php wp_nonce_field( 'lc_setup_run' ); ?>
					<input type="hidden" name="lc_setup_action" value="create_pages" />
					<?php submit_button( __( 'Create Store Pages', 'lc-store-setup' ), 'primary', 'submit', false ); ?>
				</form>
			</div>

			<div class="lc-setup-card">
				<h2>2. <?php esc_html_e( 'Menus', 'lc-store-setup' ); ?></h2>
				<p><?php esc_html_e( 'Creates 5 menus and assigns them to the theme locations: Primary, Mobile, Footer Shop, Footer Help, Footer Policies.', 'lc-store-setup' ); ?></p>
				<ul class="lc-setup-list">
					<?php
					$menu_labels = array(
						'primary'         => __( 'Primary', 'lc-store-setup' ),
						'mobile'          => __( 'Mobile', 'lc-store-setup' ),
						'footer-shop'     => __( 'Footer Shop', 'lc-store-setup' ),
						'footer-help'     => __( 'Footer Help', 'lc-store-setup' ),
						'footer-policies' => __( 'Footer Policies', 'lc-store-setup' ),
					);
					foreach ( $menu_labels as $loc => $label ) :
						?>
						<li>
							<?php lc_setup_badge( ! empty( $menu_status[ $loc ] ) ); ?>
							<?php echo esc_html( $label ); ?>
							<?php if ( ! empty( $menu_status[ $loc ] ) ) : ?>
								<code><?php echo esc_html( $menu_status[ $loc ] ); ?></code>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
				<form method="post" action="">
					<?php wp_nonce_field( 'lc_setup_run' ); ?>
					<input type="hidden" name="lc_setup_action" value="create_menus" />
					<?php submit_button( __( 'Create & Assign Menus', 'lc-store-setup' ), 'primary', 'submit', false ); ?>
				</form>
			</div>

			<div class="lc-setup-card">
				<h2>3. <?php esc_html_e( 'Store Basics', 'lc-store-setup' ); ?></h2>
				<p><?php esc_html_e( 'Sets homepage & blog page, creates the WELCOME10 coupon (10% off), enables Cash on Delivery as the first payment gateway, and creates the Iraq shipping zone (5,000 IQD flat, free over 50,000 IQD).', 'lc-store-setup' ); ?></p>
				<ul class="lc-setup-list">
					<li><?php lc_setup_badge( $basic_status['front_page'] ); ?><?php esc_html_e( 'Homepage set', 'lc-store-setup' ); ?></li>
					<li><?php lc_setup_badge( $basic_status['blog_page'] ); ?><?php esc_html_e( 'Blog page set', 'lc-store-setup' ); ?></li>
					<li><?php lc_setup_badge( $basic_status['coupon'] ); ?><?php esc_html_e( 'WELCOME10 coupon', 'lc-store-setup' ); ?></li>
					<li><?php lc_setup_badge( $basic_status['cod'] ); ?><?php esc_html_e( 'Cash on Delivery enabled & first', 'lc-store-setup' ); ?></li>
					<li><?php lc_setup_badge( $basic_status['shipping'] ); ?><?php esc_html_e( 'Iraq shipping zone', 'lc-store-setup' ); ?></li>
				</ul>
				<form method="post" action="">
					<?php wp_nonce_field( 'lc_setup_run' ); ?>
					<input type="hidden" name="lc_setup_action" value="configure_basics" />
					<?php submit_button( __( 'Configure Store Basics', 'lc-store-setup' ), 'primary', 'submit', false ); ?>
				</form>
			</div>

		</div>

		<div class="lc-setup-card lc-setup-status">
			<h2><?php esc_html_e( 'Setup Status', 'lc-store-setup' ); ?></h2>
			<p>
				<strong><?php esc_html_e( 'Pages created:', 'lc-store-setup' ); ?></strong>
				<?php echo esc_html( sprintf( __( '%1$d / %2$d', 'lc-store-setup' ), $pages_done, $pages_total ) ); ?>
				&middot;
				<strong><?php esc_html_e( 'Menus assigned:', 'lc-store-setup' ); ?></strong>
				<?php echo esc_html( sprintf( __( '%1$d / %2$d', 'lc-store-setup' ), $menus_done, $menus_total ) ); ?>
				&middot;
				<strong><?php esc_html_e( 'Basics done:', 'lc-store-setup' ); ?></strong>
				<?php echo esc_html( sprintf( __( '%1$d / %2$d', 'lc-store-setup' ), $basics_done, count( $basics_checks ) ) ); ?>
			</p>
			<?php if ( $pages_done === $pages_total && $menus_done === $menus_total && $basics_done === count( $basics_checks ) ) : ?>
				<p class="lc-setup-complete">&#10003; <?php esc_html_e( 'Your store is fully set up!', 'lc-store-setup' ); ?></p>
			<?php endif; ?>
		</div>
	</div>

	<style>
		.lc-setup-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; margin-top: 20px; }
		.lc-setup-card { background: #fff; border: 1px solid #e5e5e5; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,.04); }
		.lc-setup-card h2 { margin-top: 0; }
		.lc-setup-list { list-style: none; margin: 12px 0; padding: 0; }
		.lc-setup-list li { padding: 6px 0; border-bottom: 1px solid #f0f0f0; }
		.lc-setup-list li:last-child { border-bottom: none; }
		.lc-setup-list code { background: #f6f7f7; padding: 1px 6px; border-radius: 4px; font-size: 11px; margin-left: 6px; }
		.lc-setup-badge { display: inline-block; min-width: 70px; text-align: center; font-size: 11px; font-weight: 600; border-radius: 999px; padding: 2px 10px; margin-right: 8px; }
		.lc-setup-badge--ok { background: #e6f4ea; color: #137333; }
		.lc-setup-badge--todo { background: #fef7e0; color: #b06000; }
		.lc-setup-badge--na { background: #f1f3f4; color: #5f6368; }
		.lc-setup-status { margin-top: 20px; }
		.lc-setup-complete { color: #137333; font-weight: 700; font-size: 15px; }
	</style>
	<?php
}
