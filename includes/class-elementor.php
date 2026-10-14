<?php
/**
 * Elementor integration bootstrap class.
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
 * Elementor class.
 */
class Elementor {

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Hook into Elementor initialization.
		add_action( 'init', array( $this, 'check_and_hook' ) );
	}

	/**
	 * Verify Elementor is loaded and register hooks.
	 */
	public function check_and_hook() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		// Register custom widgets category.
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );

		// Register widgets.
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
	}

	/**
	 * Register Custom Elementor Category.
	 *
	 * @param object $elements_manager Elementor elements manager instance.
	 */
	public function register_category( $elements_manager ) {
		$elements_manager->add_category(
			'yatravanta-trek-booking-manager',
			array(
				'title' => esc_html__( 'YatraVanta Trek Booking Manager', 'yatravanta-trek-booking-manager' ),
				'icon'  => 'fa fa-mountain',
			)
		);

	}

	/**
	 * Register Trek widgets.
	 *
	 * @param object $widgets_manager Elementor widgets manager instance.
	 */
	public function register_widgets( $widgets_manager ) {
		// Include widget files.
		require_once YATRAVANTA_PATH . 'includes/elementor/widgets/class-trekdetailswidget.php';
		require_once YATRAVANTA_PATH . 'includes/elementor/widgets/class-trekbookingwidget.php';
		require_once YATRAVANTA_PATH . 'includes/elementor/widgets/class-trekarchivewidget.php';

		// Instantiate and register.
		$widgets_manager->register( new \YatraVanta\Includes\Elementor\Widgets\TrekDetailsWidget() );
		$widgets_manager->register( new \YatraVanta\Includes\Elementor\Widgets\TrekBookingWidget() );
		$widgets_manager->register( new \YatraVanta\Includes\Elementor\Widgets\TrekArchiveWidget() );
	}
}
