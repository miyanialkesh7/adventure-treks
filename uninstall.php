<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package    YatraVanta
 * @author     Nilesh Vastarpara
 */

// If uninstall not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Load Autoloader to resolve database class.
require_once plugin_dir_path( __FILE__ ) . 'includes/class-autoloader.php';
YatraVanta\Includes\Autoloader::register();

/**
 * Remove the plugin's data when the "remove data" option is enabled.
 *
 * @return void
 */
function yatravanta_uninstall() {
	// Check if the setting to remove data is enabled.
	if ( get_option( 'yatravanta_remove_data_on_uninstall' ) ) {
		// Drop custom database tables.
		YatraVanta\Includes\Database::drop_tables();

		// Delete all 'yatravanta_trek' posts.
		$yatravanta_posts = get_posts(
			array(
				'post_type'      => 'yatravanta_trek',
				'posts_per_page' => -1,
				'post_status'    => 'any',
				'fields'         => 'ids',
			)
		);

		if ( ! empty( $yatravanta_posts ) ) {
			foreach ( $yatravanta_posts as $yatravanta_post_id ) {
				wp_delete_post( $yatravanta_post_id, true );
			}
		}

		// Delete plugin options (if any are saved in options table).
		delete_option( 'yatravanta_version' );
		delete_option( 'yatravanta_currency_symbol' );
		delete_option( 'yatravanta_currency_position' );
		delete_option( 'yatravanta_thousand_separator' );
		delete_option( 'yatravanta_decimal_separator' );
		delete_option( 'yatravanta_price_decimals' );
		delete_option( 'yatravanta_booking_email' );
		delete_option( 'yatravanta_enable_schema' );
		delete_option( 'yatravanta_use_site_logo' );
		delete_option( 'yatravanta_site_logo' );
		delete_option( 'yatravanta_remove_data_on_uninstall' );

		// Clear scheduled actions or transients if any, e.g. delete_expired_transients( true ).
	}
}

yatravanta_uninstall();
