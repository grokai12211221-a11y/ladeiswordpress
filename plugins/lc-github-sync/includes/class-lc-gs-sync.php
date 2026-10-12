<?php
/**
 * Sync engine: fetches the GitHub CSV and upserts WooCommerce products.
 *
 * @package LC_GitHub_Sync
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LC_GS_Sync {

	/**
	 * Run a full sync.
	 *
	 * @param string $source 'manual' or 'cron'.
	 * @return array Result counts.
	 */
	public static function run( $source = 'manual' ) {
		$result = array(
			'created' => 0,
			'updated' => 0,
			'trashed' => 0,
			'skipped' => 0,
			'errors'  => array(),
		);

		if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'wc_get_product_id_by_sku' ) ) {
			$result['errors'][] = __( 'WooCommerce is not active — sync disabled.', 'lc-github-sync' );
			self::log( $source, $result );
			return $result;
		}

		$csv_url = get_option( 'lc_gs_csv_url', LC_GS_DEFAULT_CSV );
		if ( empty( $csv_url ) ) {
			$result['errors'][] = __( 'CSV URL is empty. Set it on the GitHub Sync settings page.', 'lc-github-sync' );
			self::log( $source, $result );
			return $result;
		}

		$response = wp_remote_get(
			esc_url_raw( $csv_url ),
			array( 'timeout' => 30 )
		);

		if ( is_wp_error( $response ) ) {
			$result['errors'][] = sprintf(
				/* translators: %s: error message */
				__( 'Could not download the CSV: %s', 'lc-github-sync' ),
				$response->get_error_message()
			);
			self::log( $source, $result );
			return $result;
		}

		$code = (int) wp_remote_retrieve_response_code( $response );
		if ( 200 !== $code ) {
			$result['errors'][] = sprintf(
				/* translators: %d: HTTP status code */
				__( 'Could not download the CSV: HTTP %d', 'lc-github-sync' ),
				$code
			);
			self::log( $source, $result );
			return $result;
		}

		$body = wp_remote_retrieve_body( $response );
		$body = preg_replace( '/^\xEF\xBB\xBF/', '', (string) $body ); // Strip UTF-8 BOM.
		$rows = self::parse_csv( $body );

		if ( empty( $rows ) ) {
			$result['errors'][] = __( 'The CSV is empty or could not be parsed.', 'lc-github-sync' );
			self::log( $source, $result );
			return $result;
		}

		foreach ( $rows as $index => $row ) {
			try {
				self::process_row( $row, $result );
			} catch ( \Throwable $e ) { // Never let one bad row kill the sync.
				$result['errors'][] = sprintf(
					/* translators: 1: row number, 2: error message */
					__( 'Row %1$d: %2$s', 'lc-github-sync' ),
					$index + 2,
					$e->getMessage()
				);
			}
		}

		self::log( $source, $result );
		return $result;
	}

	/**
	 * Parse CSV text into rows keyed by lowercased header names.
	 *
	 * @param string $body Raw CSV text.
	 * @return array
	 */
	protected static function parse_csv( $body ) {
		$body  = str_replace( array( "\r\n", "\r" ), "\n", $body );
		$lines = explode( "\n", $body );
		$rows  = array();
		$headers = null;

		foreach ( $lines as $line ) {
			if ( '' === trim( $line ) ) {
				continue;
			}
			$fields = str_getcsv( $line ); // Handles commas inside quoted fields.
			if ( null === $headers ) {
				$headers = array_map(
					function ( $h ) {
						return strtolower( trim( (string) $h ) );
					},
					$fields
				);
				continue;
			}
			$row = array();
			foreach ( $headers as $idx => $name ) {
				$row[ $name ] = isset( $fields[ $idx ] ) ? $fields[ $idx ] : '';
			}
			$rows[] = $row;
		}

		return $rows;
	}

	/**
	 * Process a single CSV row: create, update or trash a product.
	 *
	 * @param array $row    Parsed row.
	 * @param array $result Result counters (by reference).
	 */
	protected static function process_row( $row, &$result ) {
		$sku = isset( $row['sku'] ) ? trim( (string) $row['sku'] ) : '';
		if ( '' === $sku ) {
			$result['skipped']++;
			$result['errors'][] = __( 'Skipped a row with no SKU.', 'lc-github-sync' );
			return;
		}

		$action     = isset( $row['action'] ) ? strtolower( trim( (string) $row['action'] ) ) : '';
		$product_id = wc_get_product_id_by_sku( $sku );

		// Delete action: move to trash.
		if ( 'delete' === $action ) {
			if ( $product_id ) {
				wp_trash_post( $product_id );
				$result['trashed']++;
			} else {
				$result['skipped']++;
			}
			return;
		}

		$name = isset( $row['name'] ) ? trim( (string) $row['name'] ) : '';
		if ( '' === $name ) {
			$result['skipped']++;
			$result['errors'][] = sprintf(
				/* translators: %s: SKU */
				__( 'SKU %s: missing product name, skipped.', 'lc-github-sync' ),
				$sku
			);
			return;
		}

		$is_new = ! $product_id;
		if ( $is_new ) {
			$product = new WC_Product_Simple();
			$product->set_sku( $sku );
		} else {
			$product = wc_get_product( $product_id );
			if ( ! $product ) {
				$result['errors'][] = sprintf(
					/* translators: %s: SKU */
					__( 'SKU %s: product record not found.', 'lc-github-sync' ),
					$sku
				);
				return;
			}
		}

		$status  = ( isset( $row['status'] ) && 'draft' === strtolower( trim( (string) $row['status'] ) ) ) ? 'draft' : 'publish';
		$regular = isset( $row['regular price'] ) ? trim( (string) $row['regular price'] ) : '';
		$sale    = isset( $row['sale price'] ) ? trim( (string) $row['sale price'] ) : '';
		$stock   = isset( $row['stock'] ) ? trim( (string) $row['stock'] ) : '';
		$cats    = isset( $row['categories'] ) ? (string) $row['categories'] : '';
		$short   = isset( $row['short description'] ) ? (string) $row['short description'] : '';
		$desc    = isset( $row['description'] ) ? (string) $row['description'] : '';
		$image   = isset( $row['image url'] ) ? trim( (string) $row['image url'] ) : '';

		$product->set_name( $name );
		$product->set_status( $status );
		$product->set_catalog_visibility( 'visible' );
		$product->set_regular_price( $regular );
		$product->set_sale_price( '' === $sale ? '' : $sale );

		if ( is_numeric( $stock ) ) {
			$product->set_manage_stock( true );
			$product->set_stock_quantity( (int) $stock );
			$product->set_stock_status( (int) $stock > 0 ? 'instock' : 'outofstock' );
		} else {
			$product->set_manage_stock( false );
		}

		$product->set_short_description( wp_kses_post( $short ) );
		$product->set_description( wp_kses_post( $desc ) );

		$product_id = $product->save();

		// Categories (created if needed).
		$term_ids = self::ensure_categories( $cats );
		if ( ! empty( $term_ids ) ) {
			wp_set_object_terms( $product_id, $term_ids, 'product_cat' );
		}

		// Image: only sideload when the URL changed since the last sync.
		if ( '' !== $image ) {
			$current = get_post_meta( $product_id, '_lc_sync_image_url', true );
			if ( $current !== $image ) {
				$attach_id = self::sideload_image( $image, $product_id, $name );
				if ( $attach_id ) {
					set_post_thumbnail( $product_id, $attach_id );
					update_post_meta( $product_id, '_lc_sync_image_url', $image );
				} else {
					$result['errors'][] = sprintf(
						/* translators: %s: SKU */
						__( 'SKU %s: image download failed, kept existing image.', 'lc-github-sync' ),
						$sku
					);
				}
			}
		}

		update_post_meta( $product_id, '_lc_sync_managed', 'yes' );

		if ( $is_new ) {
			$result['created']++;
		} else {
			$result['updated']++;
		}
	}

	/**
	 * Split the Categories cell and make sure each term exists.
	 *
	 * @param string $cats Raw cell value.
	 * @return int[] Term IDs.
	 */
	protected static function ensure_categories( $cats ) {
		$term_ids = array();
		$names    = preg_split( '/[,|;]/', (string) $cats );
		if ( ! is_array( $names ) ) {
			return $term_ids;
		}
		foreach ( $names as $name ) {
			$name = trim( $name );
			if ( '' === $name ) {
				continue;
			}
			$term = term_exists( $name, 'product_cat' );
			if ( ! $term ) {
				$term = wp_insert_term( $name, 'product_cat' );
			}
			if ( ! is_wp_error( $term ) && isset( $term['term_id'] ) ) {
				$term_ids[] = (int) $term['term_id'];
			}
		}
		return $term_ids;
	}

	/**
	 * Download a remote image into the media library.
	 *
	 * @param string $url     Image URL.
	 * @param int    $post_id Attach to this product.
	 * @param string $name    Image title.
	 * @return int Attachment ID, 0 on failure.
	 */
	protected static function sideload_image( $url, $post_id, $name ) {
		if ( ! function_exists( 'media_handle_sideload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';
		}

		$tmp = download_url( esc_url_raw( $url ), 30 );
		if ( is_wp_error( $tmp ) ) {
			return 0;
		}

		$path     = wp_parse_url( $url, PHP_URL_PATH );
		$filename = $path ? sanitize_file_name( basename( $path ) ) : '';
		if ( '' === $filename || false === strpos( $filename, '.' ) ) {
			$filename = 'product-image.jpg';
		}

		$file_array = array(
			'name'     => $filename,
			'tmp_name' => $tmp,
		);

		$attach_id = media_handle_sideload( $file_array, $post_id, $name );
		if ( is_wp_error( $attach_id ) ) {
			if ( file_exists( $tmp ) ) {
				@unlink( $tmp ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
			}
			return 0;
		}

		return (int) $attach_id;
	}

	/**
	 * Store a sync run in the log (keeps the last 20).
	 *
	 * @param string $source 'manual' or 'cron'.
	 * @param array  $result Result counts.
	 */
	protected static function log( $source, $result ) {
		$log = get_option( 'lc_gs_log', array() );
		if ( ! is_array( $log ) ) {
			$log = array();
		}
		array_unshift(
			$log,
			array(
				'time'    => current_time( 'mysql' ),
				'source'  => $source,
				'created' => (int) $result['created'],
				'updated' => (int) $result['updated'],
				'trashed' => (int) $result['trashed'],
				'skipped' => (int) $result['skipped'],
				'errors'  => array_slice( (array) $result['errors'], 0, 10 ),
			)
		);
		update_option( 'lc_gs_log', array_slice( $log, 0, 20 ), false );
	}
}
