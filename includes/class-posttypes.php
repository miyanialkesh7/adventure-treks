<?php
/**
 * Register Custom Post Types for YatraVanta Trek Booking Manager
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
 * PostTypes class.
 */
class PostTypes {

	/**
	 * Register actions.
	 *
	 * @return void
	 */
	public static function register() {
		add_action( 'init', array( __CLASS__, 'register_trek_post_type' ) );
		add_action( 'add_meta_boxes', array( __CLASS__, 'remove_extra_meta_boxes' ), 99 );
	}

	/**
	 * Register yatravanta_trek custom post type.
	 *
	 * @return void
	 */
	public static function register_trek_post_type() {
		$labels = array(
			'name'                  => _x( 'Treks', 'Post Type General Name', 'yatravanta-trek-booking-manager' ),
			'singular_name'         => _x( 'Trek', 'Post Type Singular Name', 'yatravanta-trek-booking-manager' ),
			'menu_name'             => __( 'YatraVanta Trek Booking Manager', 'yatravanta-trek-booking-manager' ),
			'name_admin_bar'        => __( 'Trek', 'yatravanta-trek-booking-manager' ),
			'archives'              => __( 'Trek Archives', 'yatravanta-trek-booking-manager' ),
			'attributes'            => __( 'Trek Attributes', 'yatravanta-trek-booking-manager' ),
			'parent_item_colon'     => __( 'Parent Trek:', 'yatravanta-trek-booking-manager' ),
			'all_items'             => __( 'All Treks', 'yatravanta-trek-booking-manager' ),
			'add_new_item'          => __( 'Add New Trek', 'yatravanta-trek-booking-manager' ),
			'add_new'               => __( 'Add New', 'yatravanta-trek-booking-manager' ),
			'new_item'              => __( 'New Trek', 'yatravanta-trek-booking-manager' ),
			'edit_item'             => __( 'Edit Trek', 'yatravanta-trek-booking-manager' ),
			'update_item'           => __( 'Update Trek', 'yatravanta-trek-booking-manager' ),
			'view_item'             => __( 'View Trek', 'yatravanta-trek-booking-manager' ),
			'view_items'            => __( 'View Treks', 'yatravanta-trek-booking-manager' ),
			'search_items'          => __( 'Search Trek', 'yatravanta-trek-booking-manager' ),
			'not_found'             => __( 'No treks found.', 'yatravanta-trek-booking-manager' ),
			'not_found_in_trash'    => __( 'No treks found in Trash.', 'yatravanta-trek-booking-manager' ),
			'featured_image'        => __( 'Featured Image', 'yatravanta-trek-booking-manager' ),
			'set_featured_image'    => __( 'Set featured image', 'yatravanta-trek-booking-manager' ),
			'remove_featured_image' => __( 'Remove featured image', 'yatravanta-trek-booking-manager' ),
			'use_featured_image'    => __( 'Use as featured image', 'yatravanta-trek-booking-manager' ),
			'insert_into_item'      => __( 'Insert into trek', 'yatravanta-trek-booking-manager' ),
			'uploaded_to_this_item' => __( 'Uploaded to this trek', 'yatravanta-trek-booking-manager' ),
			'items_list'            => __( 'Treks list', 'yatravanta-trek-booking-manager' ),
			'items_list_navigation' => __( 'Treks list navigation', 'yatravanta-trek-booking-manager' ),
			'filter_items_list'     => __( 'Filter treks list', 'yatravanta-trek-booking-manager' ),
		);

		$args = array(
			'label'               => __( 'Trek', 'yatravanta-trek-booking-manager' ),
			'description'         => __( 'YatraVanta Trek Booking Manager trek post type', 'yatravanta-trek-booking-manager' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-palmtree',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest'        => true, // Required for Gutenberg and modern REST query capability.
		);

		register_post_type( 'yatravanta_trek', $args );
	}

	/**
	 * Remove generic meta boxes that other plugins/themes add to the trek editor
	 * (the Custom Fields box); a trek's data is managed through YatraVanta Trek Booking Manager's own boxes.
	 *
	 * @return void
	 */
	public static function remove_extra_meta_boxes() {
		remove_meta_box( 'postcustom', 'yatravanta_trek', 'normal' );
		remove_meta_box( 'postcustom', 'yatravanta_trek', 'advanced' );
	}
}
