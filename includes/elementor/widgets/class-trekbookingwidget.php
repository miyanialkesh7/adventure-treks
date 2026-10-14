<?php
/**
 * Elementor Trek Booking Widget.
 *
 * @package    YatraVanta
 * @subpackage YatraVanta/Includes/Elementor/Widgets
 * @author     Nilesh Vastarpara
 */

namespace YatraVanta\Includes\Elementor\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * TrekBookingWidget class.
 */
class TrekBookingWidget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'yatravanta_trek_booking';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Trek Booking', 'yatravanta-trek-booking-manager' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'yatravanta-trek-booking-manager' );
	}

	/**
	 * Declare the stylesheet this widget needs so Elementor loads it
	 * reliably in both the editor preview and the live frontend, instead
	 * of depending on the ad-hoc wp_enqueue_style() call inside render().
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'yatravanta-public-booking-css' );
	}

	/**
	 * Declare the script the booking flow (checkout validation, success receipt) needs,
	 * so Elementor loads it in the editor preview and on cached pages too.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'yatravanta-public-booking-js' );
	}

	/**
	 * Register widget controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'Configuration', 'yatravanta-trek-booking-manager' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		// Get all treks.
		$treks = get_posts(
			array(
				'post_type'      => 'yatravanta_trek',
				'posts_per_page' => -1,
			)
		);

		$options = array( '0' => esc_html__( 'Current Post / Trek Page', 'yatravanta-trek-booking-manager' ) );
		if ( ! empty( $treks ) ) {
			foreach ( $treks as $t ) {
				$options[ $t->ID ] = $t->post_title;
			}
		}

		$this->add_control(
			'trek_id',
			array(
				'label'   => esc_html__( 'Select Trek', 'yatravanta-trek-booking-manager' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => '0',
				'options' => $options,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$trek_id  = intval( $settings['trek_id'] );

		if ( ! $trek_id ) {
			$trek_id = get_the_ID();
		}

		if ( $trek_id && get_post_type( $trek_id ) === 'yatravanta_trek' ) {
			echo do_shortcode( '[yatravanta_booking id="' . $trek_id . '"]' );
		} else {
			echo '<p style="color:#666; font-style:italic;">' . esc_html__( 'Please select a valid Trek CPT or insert this widget into a Trek single post page.', 'yatravanta-trek-booking-manager' ) . '</p>';
		}
	}
}
