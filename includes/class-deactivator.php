<?php
/**
 * Fired during plugin deactivation
 *
 * @package    YatraVanta
 * @subpackage YatraVanta/Includes
 * @author     Nilesh Vastarpara
 */

namespace YatraVanta\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 */
class Deactivator {

	/**
	 * Run the deactivation routines.
	 *
	 * @return void
	 */
	public static function deactivate() {
		// Flush rewrite rules.
		flush_rewrite_rules();
	}
}
