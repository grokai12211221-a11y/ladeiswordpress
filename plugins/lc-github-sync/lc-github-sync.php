<?php
/**
 * Plugin Name:       LC GitHub Product Sync
 * Plugin URI:        https://lad.mbiinventra.com/
 * Description:       Syncs WooCommerce products from a simple CSV file hosted on GitHub. Edit products.csv on GitHub to add, edit or delete products — the store updates automatically.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            LadyCorrections
 * Text Domain:       lc-github-sync
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'LC_GS_VERSION', '1.0.0' );
define( 'LC_GS_FILE', __FILE__ );
define( 'LC_GS_DIR', plugin_dir_path( __FILE__ ) );
define( 'LC_GS_CRON_HOOK', 'lc_gs_hourly_sync' );
define( 'LC_GS_DEFAULT_CSV', 'https://raw.githubusercontent.com/grokai12211221-a11y/ladeiswordpress/main/products.csv' );

require_once LC_GS_DIR . 'includes/class-lc-gs-sync.php';
require_once LC_GS_DIR . 'includes/class-lc-gs-admin.php';

/**
 * Schedule / unschedule the hourly sync.
 */
function lc_gs_maybe_schedule() {
	if ( ! wp_next_scheduled( LC_GS_CRON_HOOK ) ) {
		wp_schedule_event( time() + HOUR_IN_SECONDS, 'hourly', LC_GS_CRON_HOOK );
	}
}

function lc_gs_maybe_unschedule() {
	$timestamp = wp_next_scheduled( LC_GS_CRON_HOOK );
	if ( $timestamp ) {
		wp_unschedule_event( $timestamp, LC_GS_CRON_HOOK );
	}
}

function lc_gs_activate() {
	if ( get_option( 'lc_gs_auto_sync', 'yes' ) === 'yes' ) {
		lc_gs_maybe_schedule();
	}
}
register_activation_hook( __FILE__, 'lc_gs_activate' );

function lc_gs_deactivate() {
	lc_gs_maybe_unschedule();
}
register_deactivation_hook( __FILE__, 'lc_gs_deactivate' );

/**
 * Hourly cron callback.
 */
function lc_gs_run_cron_sync() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	if ( get_option( 'lc_gs_auto_sync', 'yes' ) !== 'yes' ) {
		return;
	}
	LC_GS_Sync::run( 'cron' );
}
add_action( LC_GS_CRON_HOOK, 'lc_gs_run_cron_sync' );

function lc_gs_init() {
	load_plugin_textdomain( 'lc-github-sync', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	if ( is_admin() ) {
		LC_GS_Admin::init();
	}
}
add_action( 'plugins_loaded', 'lc_gs_init' );
