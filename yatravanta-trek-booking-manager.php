<?php
/**
 * Plugin Name:       YatraVanta Trek Booking Manager
 * Description:       A premium trekking and adventure trip management plugin for WordPress.
 * Version:           1.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Nilesh Vastarpara
 * Author URI:        https://nileshvastarpara.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       yatravanta-trek-booking-manager
 * Domain Path:       /languages
 *
 * @package           YatraVanta
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently active version of the plugin.
 */
define( 'YATRAVANTA_VERSION', '1.0' );

/**
 * Base directory path for the plugin.
 */
define( 'YATRAVANTA_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Base directory URL for the plugin.
 */
define( 'YATRAVANTA_URL', plugin_dir_url( __FILE__ ) );

// Load Autoloader.
require_once YATRAVANTA_PATH . 'includes/class-autoloader.php';
YatraVanta\Includes\Autoloader::register();

/**
 * Activation code runner.
 */
function yatravanta_activate() {
	YatraVanta\Includes\Activator::activate();
}

/**
 * Deactivation code runner.
 */
function yatravanta_deactivate() {
	YatraVanta\Includes\Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'yatravanta_activate' );
register_deactivation_hook( __FILE__, 'yatravanta_deactivate' );

/**
 * Begins execution of the plugin.
 *
 * @return void
 */
function yatravanta_run() {
	$plugin = YatraVanta\Includes\Plugin::get_instance();
	$plugin->run();
}
yatravanta_run();
