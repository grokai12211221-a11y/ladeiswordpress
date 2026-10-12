<?php
/**
 * Admin UI: settings page under the WooCommerce menu.
 *
 * @package LC_GitHub_Sync
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LC_GS_Admin {

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'menu' ) );
		add_action( 'admin_notices', array( __CLASS__, 'notices' ) );
		add_action( 'admin_init', array( __CLASS__, 'handle_post' ) );
	}

	public static function menu() {
		$parent = class_exists( 'WooCommerce' ) ? 'woocommerce' : 'options-general.php';
		add_submenu_page(
			$parent,
			__( 'GitHub Product Sync', 'lc-github-sync' ),
			__( 'GitHub Sync', 'lc-github-sync' ),
			'manage_woocommerce',
			'lc-github-sync',
			array( __CLASS__, 'page' )
		);
	}

	/**
	 * Handle settings save + Sync Now.
	 */
	public static function handle_post() {
		if ( ! isset( $_GET['page'] ) || 'lc-github-sync' !== $_GET['page'] ) {
			return;
		}
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		// Save settings.
		if ( isset( $_POST['lc_gs_save'] ) ) {
			check_admin_referer( 'lc_gs_settings' );

			$csv_url = isset( $_POST['lc_gs_csv_url'] ) ? esc_url_raw( trim( wp_unslash( $_POST['lc_gs_csv_url'] ) ) ) : '';
			if ( '' !== $csv_url ) {
				update_option( 'lc_gs_csv_url', $csv_url );
			}

			$auto = isset( $_POST['lc_gs_auto_sync'] ) ? 'yes' : 'no';
			$old  = get_option( 'lc_gs_auto_sync', 'yes' );
			update_option( 'lc_gs_auto_sync', $auto );
			if ( 'yes' === $auto && 'yes' !== $old ) {
				lc_gs_maybe_schedule();
			} elseif ( 'no' === $auto ) {
				lc_gs_maybe_unschedule();
			}

			wp_safe_redirect( add_query_arg( array( 'page' => 'lc-github-sync', 'lc-gs-saved' => '1' ), admin_url( 'admin.php' ) ) );
			exit;
		}

		// Sync Now.
		if ( isset( $_POST['lc_gs_sync_now'] ) ) {
			check_admin_referer( 'lc_gs_sync_now' );
			$result = LC_GS_Sync::run( 'manual' );
			set_transient( 'lc_gs_last_result', $result, 120 );
			wp_safe_redirect( add_query_arg( array( 'page' => 'lc-github-sync', 'lc-gs-ran' => '1' ), admin_url( 'admin.php' ) ) );
			exit;
		}
	}

	/**
	 * Admin notices: WooCommerce missing + source-of-truth warning.
	 */
	public static function notices() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		if ( ! class_exists( 'WooCommerce' ) ) {
			echo '<div class="notice notice-error"><p><strong>';
			esc_html_e( 'LC GitHub Product Sync:', 'lc-github-sync' );
			echo '</strong> ';
			esc_html_e( 'WooCommerce is not active — product sync is disabled until WooCommerce is installed and activated.', 'lc-github-sync' );
			echo '</p></div>';
			return;
		}

		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( ! $screen ) {
			return;
		}

		// On the plugin page and the products list: warn that the CSV wins.
		$on_plugin_page  = 'woocommerce_page_lc-github-sync' === $screen->id || 'settings_page_lc-github-sync' === $screen->id;
		$on_products_list = 'edit-product' === $screen->id;

		if ( $on_plugin_page || $on_products_list ) {
			echo '<div class="notice notice-warning"><p><strong>';
			esc_html_e( 'GitHub is the source of truth:', 'lc-github-sync' );
			echo '</strong> ';
			esc_html_e( 'products edited in wp-admin will be overwritten by the next GitHub sync.', 'lc-github-sync' );
			echo '</p></div>';
		}
	}

	public static function page() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'lc-github-sync' ) );
		}

		$csv_url   = get_option( 'lc_gs_csv_url', LC_GS_DEFAULT_CSV );
		$auto_sync = get_option( 'lc_gs_auto_sync', 'yes' );
		$log       = get_option( 'lc_gs_log', array() );
		$next      = wp_next_scheduled( LC_GS_CRON_HOOK );
		$woo_ok    = class_exists( 'WooCommerce' );

		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'GitHub Product Sync', 'lc-github-sync' ) . '</h1>';

		if ( isset( $_GET['lc-gs-saved'] ) ) {
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Settings saved.', 'lc-github-sync' ) . '</p></div>';
		}

		if ( isset( $_GET['lc-gs-ran'] ) ) {
			$result = get_transient( 'lc_gs_last_result' );
			if ( is_array( $result ) ) {
				$cls = ! empty( $result['errors'] ) ? 'notice-warning' : 'notice-success';
				echo '<div class="notice ' . esc_attr( $cls ) . ' is-dismissible"><p><strong>' . esc_html__( 'Sync finished:', 'lc-github-sync' ) . '</strong> ';
				printf(
					/* translators: 1: created, 2: updated, 3: trashed, 4: skipped */
					esc_html__( '%1$d created, %2$d updated, %3$d trashed, %4$d skipped.', 'lc-github-sync' ),
					(int) $result['created'],
					(int) $result['updated'],
					(int) $result['trashed'],
					(int) $result['skipped']
				);
				if ( ! empty( $result['errors'] ) ) {
					echo '<br>' . esc_html__( 'Errors:', 'lc-github-sync' ) . ' ' . esc_html( implode( ' | ', array_slice( $result['errors'], 0, 5 ) ) );
				}
				echo '</p></div>';
				delete_transient( 'lc_gs_last_result' );
			}
		}

		if ( ! $woo_ok ) {
			echo '<div class="notice notice-error"><p>' . esc_html__( 'WooCommerce is not active — sync is disabled.', 'lc-github-sync' ) . '</p></div>';
		}

		// Settings form.
		echo '<form method="post" action="">';
		wp_nonce_field( 'lc_gs_settings' );
		echo '<table class="form-table"><tbody>';

		echo '<tr><th scope="row"><label for="lc_gs_csv_url">' . esc_html__( 'CSV URL', 'lc-github-sync' ) . '</label></th><td>';
		echo '<input type="url" id="lc_gs_csv_url" name="lc_gs_csv_url" value="' . esc_attr( $csv_url ) . '" class="regular-text code" style="width:100%;max-width:640px" required>';
		echo '<p class="description">' . esc_html__( 'Raw GitHub URL of products.csv. The branch is part of the URL (…/main/products.csv) — change "main" to sync from a different branch.', 'lc-github-sync' ) . '</p>';
		echo '</td></tr>';

		echo '<tr><th scope="row">' . esc_html__( 'Auto-sync', 'lc-github-sync' ) . '</th><td>';
		echo '<label><input type="checkbox" name="lc_gs_auto_sync" value="1"' . checked( $auto_sync, 'yes', false ) . '> ';
		esc_html_e( 'Sync automatically every hour (WP-Cron)', 'lc-github-sync' );
		echo '</label>';
		if ( $next ) {
			echo '<p class="description">';
			printf(
				/* translators: %s: date/time */
				esc_html__( 'Next scheduled run: %s', 'lc-github-sync' ),
				esc_html( get_date_from_gmt( gmdate( 'Y-m-d H:i:s', $next ), get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ) )
			);
			echo '</p>';
		}
		echo '</td></tr>';

		echo '</tbody></table>';
		echo '<p><input type="submit" name="lc_gs_save" class="button button-primary" value="' . esc_attr__( 'Save Settings', 'lc-github-sync' ) . '"></p>';
		echo '</form>';

		echo '<hr>';

		// Sync Now.
		echo '<h2>' . esc_html__( 'Run sync now', 'lc-github-sync' ) . '</h2>';
		echo '<form method="post" action="">';
		wp_nonce_field( 'lc_gs_sync_now' );
		echo '<p><input type="submit" name="lc_gs_sync_now" class="button button-secondary button-hero" value="' . esc_attr__( 'Sync Now', 'lc-github-sync' ) . '"' . ( $woo_ok ? '' : ' disabled' ) . '></p>';
		echo '</form>';

		echo '<hr>';

		// Log.
		echo '<h2>' . esc_html__( 'Sync log (last 20 runs)', 'lc-github-sync' ) . '</h2>';
		if ( empty( $log ) ) {
			echo '<p>' . esc_html__( 'No syncs have run yet.', 'lc-github-sync' ) . '</p>';
		} else {
			echo '<table class="widefat striped"><thead><tr>';
			echo '<th>' . esc_html__( 'Time', 'lc-github-sync' ) . '</th>';
			echo '<th>' . esc_html__( 'Source', 'lc-github-sync' ) . '</th>';
			echo '<th>' . esc_html__( 'Created', 'lc-github-sync' ) . '</th>';
			echo '<th>' . esc_html__( 'Updated', 'lc-github-sync' ) . '</th>';
			echo '<th>' . esc_html__( 'Trashed', 'lc-github-sync' ) . '</th>';
			echo '<th>' . esc_html__( 'Skipped', 'lc-github-sync' ) . '</th>';
			echo '<th>' . esc_html__( 'Errors', 'lc-github-sync' ) . '</th>';
			echo '</tr></thead><tbody>';
			foreach ( $log as $entry ) {
				echo '<tr>';
				echo '<td>' . esc_html( $entry['time'] ) . '</td>';
				echo '<td>' . esc_html( $entry['source'] ) . '</td>';
				echo '<td>' . (int) $entry['created'] . '</td>';
				echo '<td>' . (int) $entry['updated'] . '</td>';
				echo '<td>' . (int) $entry['trashed'] . '</td>';
				echo '<td>' . (int) $entry['skipped'] . '</td>';
				echo '<td>' . ( ! empty( $entry['errors'] ) ? esc_html( implode( ' | ', array_slice( (array) $entry['errors'], 0, 3 ) ) ) : '&mdash;' ) . '</td>';
				echo '</tr>';
			}
			echo '</tbody></table>';
		}

		echo '<hr>';
		echo '<h2>' . esc_html__( 'How to manage products', 'lc-github-sync' ) . '</h2>';
		echo '<ol>';
		echo '<li>' . esc_html__( 'Open products.csv in your GitHub repository and edit it (pencil icon).', 'lc-github-sync' ) . '</li>';
		echo '<li>' . esc_html__( 'To add a product: add a new row with a unique SKU.', 'lc-github-sync' ) . '</li>';
		echo '<li>' . esc_html__( 'To edit: change any cell in that product’s row.', 'lc-github-sync' ) . '</li>';
		echo '<li>' . esc_html__( 'To delete: write "delete" in the Action column of that row.', 'lc-github-sync' ) . '</li>';
		echo '<li>' . esc_html__( 'Commit the change — the store syncs automatically within an hour, or press Sync Now above.', 'lc-github-sync' ) . '</li>';
		echo '</ol>';

		echo '</div>';
	}
}
