<?php
/**
 * Plugin Name:       LC Store Setup
 * Description:       One-click setup wizard for the LadyCorrections store: creates store pages, menus, homepage, coupon, COD gateway and Iraq shipping in one go.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            LadyCorrections
 * Text Domain:       lc-store-setup
 * Domain Path:       /languages
 *
 * @package LC_Store_Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'LC_SETUP_VERSION', '1.0.0' );
define( 'LC_SETUP_PATH', plugin_dir_path( __FILE__ ) );
define( 'LC_SETUP_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load plugin text domain.
 */
function lc_setup_load_textdomain() {
	load_plugin_textdomain( 'lc-store-setup', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'lc_setup_load_textdomain' );

require_once LC_SETUP_PATH . 'includes/content.php';
require_once LC_SETUP_PATH . 'includes/setup.php';
require_once LC_SETUP_PATH . 'includes/admin-page.php';
