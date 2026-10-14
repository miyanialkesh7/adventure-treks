<?php
/**
 * Controller for registering and rendering frontend shortcodes.
 *
 * @package    YatraVanta
 * @subpackage YatraVanta/Frontend/Controllers
 * @author     Nilesh Vastarpara
 */

namespace YatraVanta\Frontend\Controllers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * TrekShortcodesController class.
 */
class TrekShortcodesController {

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Register shortcodes.
		add_shortcode( 'yatravanta_details', array( $this, 'render_trek_details' ) );

		add_shortcode( 'yatravanta_itinerary', array( $this, 'render_trek_itinerary' ) );

		add_shortcode( 'yatravanta_list', array( $this, 'render_trek_archive' ) );

		// Override single/archive templates for yatravanta_trek CPT.
		add_filter( 'template_include', array( $this, 'load_single_trek_template' ) );
		add_filter( 'template_include', array( $this, 'load_archive_trek_template' ) );

		// Load CSS/JS.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'add_dynamic_color_vars' ), 20 );
	}

	/**
	 * Attach the admin-configured Primary/Secondary colors as CSS custom
	 * properties to the trek details stylesheet. No-ops on pages where
	 * that stylesheet was never registered.
	 *
	 * @return void
	 */
	public function add_dynamic_color_vars() {
		wp_add_inline_style( 'yatravanta-public-details-css', \YatraVanta\Includes\Plugin::get_dynamic_color_css() );
		wp_add_inline_style( 'yatravanta-public-archive-css', \YatraVanta\Includes\Plugin::get_dynamic_color_css() );
	}

	/**
	 * Output the site header. Block themes have no header.php, so calling
	 * get_header() there triggers a deprecation notice; render the document
	 * head and the block header template part instead.
	 *
	 * @return void
	 */
	public static function render_header() {
		if ( ! wp_is_block_theme() ) {
			get_header();
			return;
		}
		// Buffer the body: block parts (e.g. Navigation) enqueue their scripts/modules
		// while rendering, so wp_head() must run after them (like block templates do).
		ob_start();
		echo '<div class="wp-site-blocks">';
		block_template_part( 'header' );
	}

	/**
	 * Output the site footer (counterpart of render_header()).
	 *
	 * @return void
	 */
	public static function render_footer() {
		if ( ! wp_is_block_theme() ) {
			get_footer();
			return;
		}
		block_template_part( 'footer' );
		echo '</div>';
		$body = ob_get_clean();
		?>
		<!DOCTYPE html>
		<html <?php language_attributes(); ?>>
		<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php wp_head(); ?>
		</head>
		<body <?php body_class(); ?>>
		<?php
		wp_body_open();
		echo $body; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already-rendered template output.
		wp_footer();
		echo '</body></html>';
	}

	/**
	 * Load plugin's single-yatravanta_trek.php template for CPT single pages.
	 *
	 * Priority order: child theme → parent theme → plugin fallback.
	 *
	 * @param string $template Current template path.
	 * @return string Modified template path.
	 */
	public function load_single_trek_template( $template ) {
		if ( is_singular( 'yatravanta_trek' ) ) {
			// Allow theme override first.
			$theme_tpl = locate_template( array( 'single-yatravanta_trek.php', 'single.php' ) );
			if ( $theme_tpl && 'single-yatravanta_trek.php' === basename( $theme_tpl ) ) {
				return $theme_tpl;
			}
			// Fall back to plugin template.
			$plugin_tpl = plugin_dir_path( __DIR__ ) . 'views/single-yatravanta_trek.php';
			if ( file_exists( $plugin_tpl ) ) {
				return $plugin_tpl;
			}
		}
		return $template;
	}

	/**
	 * Load plugin's archive-yatravanta_trek.php template for the CPT archive page.
	 *
	 * Priority order: child theme → parent theme → plugin fallback.
	 *
	 * @param string $template Current template path.
	 * @return string Modified template path.
	 */
	public function load_archive_trek_template( $template ) {
		if ( is_post_type_archive( 'yatravanta_trek' ) ) {
			// Allow theme override first.
			$theme_tpl = locate_template( array( 'archive-yatravanta_trek.php', 'archive.php' ) );
			if ( $theme_tpl && 'archive-yatravanta_trek.php' === basename( $theme_tpl ) ) {
				return $theme_tpl;
			}
			// Fall back to plugin template.
			$plugin_tpl = plugin_dir_path( __DIR__ ) . 'views/archive-yatravanta_trek.php';
			if ( file_exists( $plugin_tpl ) ) {
				return $plugin_tpl;
			}
		}
		return $template;
	}

	/**
	 * Build the HTML for one trek's archive/grid card (thumbnail, starting
	 * price badge, meta row, optional excerpt, and a "View Details" link).
	 * Shared by the [yatravanta_list] shortcode/widget view and the native
	 * archive-yatravanta_trek.php template so both stay visually identical.
	 *
	 * Must be called from within a loop iteration (after the_post()) so
	 * template tags like the_permalink()/the_title() resolve to the right post.
	 *
	 * @param int  $trek_id    Trek post ID (current post in the loop).
	 * @param bool $show_excerpt Whether to render the trimmed excerpt.
	 * @param bool $show_price   Whether to compute and render the starting price badge.
	 * @return string
	 */
	public static function get_trek_archive_card_html( $trek_id, $show_excerpt = true, $show_price = true ) {
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
		$yatravanta_trek_meta = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}yatravanta_treks WHERE post_id = %d", $trek_id ), ARRAY_A );

		$yatravanta_start_price = 0;
		if ( $show_price ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
			$yatravanta_cities_prices = $wpdb->get_results( $wpdb->prepare( "SELECT base_price, offer_price FROM {$wpdb->prefix}yatravanta_departure_cities WHERE trek_id = %d AND status = 'active'", $trek_id ), ARRAY_A );

			if ( ! empty( $yatravanta_cities_prices ) ) {
				$yatravanta_effective_prices = array();
				foreach ( $yatravanta_cities_prices as $yatravanta_city_price ) {
					$yatravanta_effective_prices[] = floatval( $yatravanta_city_price['offer_price'] ) > 0 ? floatval( $yatravanta_city_price['offer_price'] ) : floatval( $yatravanta_city_price['base_price'] );
				}
				$yatravanta_start_price = min( $yatravanta_effective_prices );
			}
		}

		ob_start();
		?>
		<div class="yatravanta-trek-archive-card">
			<a href="<?php the_permalink(); ?>" class="yatravanta-trek-archive-thumb">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'medium_large' ); ?>
				<?php else : ?>
					<div class="yatravanta-trek-archive-thumb-placeholder"><span class="dashicons dashicons-palmtree"></span></div>
				<?php endif; ?>
				<?php if ( $show_price && $yatravanta_start_price > 0 ) : ?>
					<span class="yatravanta-trek-archive-price-badge">
						<?php esc_html_e( 'From', 'yatravanta-trek-booking-manager' ); ?> <?php echo esc_html( \YatraVanta\Admin\Controllers\AdminController::format_price( $yatravanta_start_price ) ); ?>
					</span>
				<?php endif; ?>
			</a>
			<div class="yatravanta-trek-archive-body">
				<h3 class="yatravanta-trek-archive-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

				<?php if ( ! empty( $yatravanta_trek_meta ) ) : ?>
					<div class="yatravanta-trek-archive-meta">
						<?php if ( ! empty( $yatravanta_trek_meta['duration'] ) ) : ?>
							<span><span class="dashicons dashicons-clock"></span> <?php echo esc_html( $yatravanta_trek_meta['duration'] ); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $yatravanta_trek_meta['difficulty'] ) ) : ?>
							<span><span class="dashicons dashicons-performance"></span> <?php echo esc_html( $yatravanta_trek_meta['difficulty'] ); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $yatravanta_trek_meta['region'] ) ) : ?>
							<span><span class="dashicons dashicons-location"></span> <?php echo esc_html( $yatravanta_trek_meta['region'] ); ?></span>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( $show_excerpt ) : ?>
					<p class="yatravanta-trek-archive-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
				<?php endif; ?>

				<a href="<?php the_permalink(); ?>" class="yatravanta-trek-archive-btn"><?php esc_html_e( 'View Details', 'yatravanta-trek-booking-manager' ); ?></a>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Register frontend CSS and JS.
	 */
	public function enqueue_assets() {
		// Frontend CSS.
		wp_register_style(
			'yatravanta-public-details-css',
			YATRAVANTA_URL . 'assets/public/css/trek-details.css',
			array( 'dashicons' ),
			YATRAVANTA_VERSION . '.' . filemtime( YATRAVANTA_PATH . 'assets/public/css/trek-details.css' )
		);

		// Frontend JS.
		wp_register_script(
			'yatravanta-public-details-js',
			YATRAVANTA_URL . 'assets/public/js/trek-details.js',
			array(),
			YATRAVANTA_VERSION . '.' . filemtime( YATRAVANTA_PATH . 'assets/public/js/trek-details.js' ),
			true
		);

		$yatravanta_post_content = get_post() ? get_post()->post_content : '';
		if ( is_singular( 'yatravanta_trek' )
			|| has_shortcode( $yatravanta_post_content, 'yatravanta_details' )
			|| has_shortcode( $yatravanta_post_content, 'yatravanta_itinerary' )
		) {
			wp_enqueue_style( 'yatravanta-public-details-css' );
			wp_enqueue_script( 'yatravanta-public-details-js' );
		}

		// Single trek page layout, gallery lightbox and share dialog.
		if ( is_singular( 'yatravanta_trek' ) ) {
			wp_enqueue_style(
				'yatravanta-public-single-css',
				YATRAVANTA_URL . 'assets/public/css/single-trek.css',
				array( 'yatravanta-public-details-css' ),
				\YatraVanta\Includes\Plugin::asset_version( 'assets/public/css/single-trek.css' )
			);
			wp_enqueue_script(
				'yatravanta-public-single-js',
				YATRAVANTA_URL . 'assets/public/js/single-trek.js',
				array(),
				\YatraVanta\Includes\Plugin::asset_version( 'assets/public/js/single-trek.js' ),
				true
			);
		}

		// Trek Archive stylesheet.
		wp_register_style(
			'yatravanta-public-archive-css',
			YATRAVANTA_URL . 'assets/public/css/trek-archive.css',
			array( 'dashicons' ),
			\YatraVanta\Includes\Plugin::asset_version( 'assets/public/css/trek-archive.css' )
		);

		if ( is_post_type_archive( 'yatravanta_trek' ) || has_shortcode( $yatravanta_post_content, 'yatravanta_list' ) ) {
			wp_enqueue_style( 'yatravanta-public-archive-css' );
		}
	}

	/**
	 * Shortcode Renderer for [yatravanta_details].
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function render_trek_details( $atts ) {
		// Enqueue assets on demand.
		wp_enqueue_style( 'yatravanta-public-details-css' );
		wp_enqueue_script( 'yatravanta-public-details-js' );

		$args = shortcode_atts(
			array(
				'id' => get_the_ID(),
			),
			$atts
		);

		$trek_id = intval( $args['id'] );
		if ( ! $trek_id || get_post_type( $trek_id ) !== 'yatravanta_trek' ) {
			return '<p style="color:#b32d2e;">' . esc_html__( 'Error: Invalid Trek ID for details widget.', 'yatravanta-trek-booking-manager' ) . '</p>';
		}

		global $wpdb;

		// 1. Fetch Trek specifications meta
		$table_treks = $wpdb->prefix . 'yatravanta_treks';
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
		$trek = $wpdb->get_row(
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
			$wpdb->prepare( "SELECT * FROM $table_treks WHERE post_id = %d", $trek_id ),
			ARRAY_A
		);

		if ( ! $trek ) {
			return '<p style="color:#666; font-style:italic;">' . esc_html__( 'Trek specifications have not been configured yet.', 'yatravanta-trek-booking-manager' ) . '</p>';
		}

		// Columns such as highlights and exclusions are nullable; a NULL must never reach esc_html() or
		// esc_textarea() (PHP 8.1+ deprecation notice printed into the page).
		$trek = array_map(
			static function ( $value ) {
				return null === $value ? '' : $value;
			},
			$trek
		);

		// 2. Fetch active departure cities to default the itinerary Day list
		$table_cities = $wpdb->prefix . 'yatravanta_departure_cities';
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
		$cities = $wpdb->get_results(
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
			$wpdb->prepare( "SELECT * FROM $table_cities WHERE trek_id = %d AND status = 'active' ORDER BY menu_order ASC", $trek_id )
		);

		$default_city_id = ! empty( $cities ) ? intval( $cities[0]->id ) : 0;

		// Resolve a default departure date for the initial itinerary render: honor
		// ?date= from a shared/bookmarked URL, otherwise fall back to the first
		// upcoming scheduled date for the default city.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$requested_date         = isset( $_GET['date'] ) ? sanitize_text_field( wp_unslash( $_GET['date'] ) ) : '';
		$default_departure_date = '';
		if ( $requested_date && preg_match( '/^\d{4}-\d{2}-\d{2}$/', $requested_date ) ) {
			$default_departure_date = $requested_date;
		} elseif ( $default_city_id ) {
			$table_dates            = $wpdb->prefix . 'yatravanta_departure_dates';
			$default_departure_date = (string) $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, PluginCheck.Security.DirectDB.UnescapedDBParameter
				$wpdb->prepare(
					// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
					"SELECT departure_date FROM $table_dates WHERE city_id = %d AND status NOT IN ( 'cancelled', 'sold_out' ) AND departure_date >= CURDATE() ORDER BY departure_date ASC LIMIT 1",
					$default_city_id
				)
			);
		}

		// Decode lists.
		$faq_items = ! empty( $trek['faq'] ) ? json_decode( $trek['faq'], true ) : array();
		$faq_items = is_array( $faq_items ) ? array_values(
			array_filter(
				$faq_items,
				function ( $faq ) {
					return ! empty( $faq['q'] ) && ! empty( $faq['a'] );
				}
			)
		) : array();
		$policies  = ! empty( $trek['policies'] ) ? json_decode( $trek['policies'], true ) : array();

		ob_start();
		$view_path = plugin_dir_path( __DIR__ ) . 'views/trek-details.php';
		if ( file_exists( $view_path ) ) {
			include $view_path;
		}
		return ob_get_clean();
	}

	/**
	 * Shortcode Renderer for [yatravanta_list]. Also used by the "Trek Archive Grid" Elementor widget.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function render_trek_archive( $atts ) {
		wp_enqueue_style( 'yatravanta-public-archive-css' );

		$args = shortcode_atts(
			array(
				'include'        => '',
				'posts_per_page' => 9,
				'columns'        => 3,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'show_excerpt'   => 'yes',
				'show_price'     => 'yes',
				'pagination'     => 'yes',
			),
			$atts,
			'yatravanta_list'
		);

		$include_ids = array_filter( array_map( 'intval', explode( ',', $args['include'] ) ) );

		$posts_per_page  = max( 1, intval( $args['posts_per_page'] ) );
		$columns         = max( 1, min( 6, intval( $args['columns'] ) ) );
		$orderby         = in_array( $args['orderby'], array( 'date', 'title', 'menu_order', 'rand' ), true ) ? $args['orderby'] : 'date';
		$order           = 'ASC' === strtoupper( $args['order'] ) ? 'ASC' : 'DESC';
		$show_excerpt    = 'yes' === $args['show_excerpt'];
		$show_price      = 'yes' === $args['show_price'];
		$show_pagination = ! empty( $include_ids ) ? false : 'yes' === $args['pagination'];

		// Dedicated query var (not core's `paged`) so this loop's pagination never
		// collides with the host page's own main-query pagination.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_page = isset( $_GET['yatravanta_trek_page'] ) ? max( 1, absint( wp_unslash( $_GET['yatravanta_trek_page'] ) ) ) : 1;

		if ( ! empty( $include_ids ) ) {
			// Manual selection: show exactly the chosen treks, in the order picked, no pagination.
			$query_args = array(
				'post_type'      => 'yatravanta_trek',
				'post_status'    => 'publish',
				'post__in'       => $include_ids,
				'orderby'        => 'post__in',
				'posts_per_page' => count( $include_ids ),
			);
		} else {
			$query_args = array(
				'post_type'      => 'yatravanta_trek',
				'post_status'    => 'publish',
				'posts_per_page' => $posts_per_page,
				'paged'          => $current_page,
				'orderby'        => $orderby,
				'order'          => $order,
			);
		}

		$query = new \WP_Query( $query_args );

		ob_start();
		$view_path = plugin_dir_path( __DIR__ ) . 'views/trek-archive.php';
		if ( file_exists( $view_path ) ) {
			include $view_path;
		}
		wp_reset_postdata();
		return ob_get_clean();
	}

	/**
	 * Shortcode Renderer for [yatravanta_itinerary].
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function render_trek_itinerary( $atts ) {
		wp_enqueue_style( 'yatravanta-public-details-css' );

		$args = shortcode_atts(
			array(
				'city_id' => 0,
				'trek_id' => get_the_ID(),
				'date'    => '',
			),
			$atts
		);

		$city_id        = intval( $args['city_id'] );
		$trek_id        = intval( $args['trek_id'] );
		$departure_date = (string) $args['date'];
		$has_valid_date = (bool) preg_match( '/^\d{4}-\d{2}-\d{2}$/', $departure_date );

		global $wpdb;

		// If city_id is not specified, lookup first active city for the current trek.
		if ( ! $city_id && $trek_id ) {
			$table_cities = $wpdb->prefix . 'yatravanta_departure_cities';
			$city_id      = intval(
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
				$wpdb->get_var(
					// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
					$wpdb->prepare( "SELECT id FROM $table_cities WHERE trek_id = %d AND status = 'active' ORDER BY menu_order ASC LIMIT 1", $trek_id )
				)
			);
		}

		if ( ! $city_id ) {
			return '<p style="color:#666; font-style:italic;">' . esc_html__( 'No active departure city configuration found to render itinerary.', 'yatravanta-trek-booking-manager' ) . '</p>';
		}

		$table_days = $wpdb->prefix . 'yatravanta_itineraries';

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
		$days = $wpdb->get_results(
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
			$wpdb->prepare( "SELECT * FROM $table_days WHERE city_id = %d ORDER BY menu_order ASC", $city_id )
		);

		if ( empty( $days ) ) {
			return '<p style="color:#666; font-style:italic;">' . esc_html__( 'No itinerary found. Please contact the admin for details.', 'yatravanta-trek-booking-manager' ) . '</p>';
		}

		$items_by_day = \YatraVanta\Includes\Database::get_items_by_day( wp_list_pluck( $days, 'id' ) );

		ob_start();
		?>
		<div class="yatravanta-frontend-itinerary-timeline" id="trek_itinerary_container">
			<?php
			foreach ( $days as $day ) :
				$day_id = intval( $day->id );
				$items  = isset( $items_by_day[ $day_id ] ) ? $items_by_day[ $day_id ] : array();
				?>
				<div class="yatravanta-timeline-day-block">
					<div class="yatravanta-timeline-day-header">
						<span class="yatravanta-timeline-day-badge">
							<?php
							if ( $has_valid_date ) {
								$calendar_date = gmdate( 'd M Y', strtotime( $departure_date . ' +' . intval( $day->day_number ) . ' days' ) );
								/* translators: 1: Day number, 2: Calendar date */
								printf( esc_html__( 'Day %1$d — %2$s', 'yatravanta-trek-booking-manager' ), intval( $day->day_number ), esc_html( $calendar_date ) );
							} else {
								/* translators: %d: Day number */
								printf( esc_html__( 'Day %d', 'yatravanta-trek-booking-manager' ), intval( $day->day_number ) );
							}
							?>
						</span>
						<h4 class="yatravanta-timeline-day-title"><?php echo esc_html( $day->title ); ?></h4>
					</div>
					<?php if ( ! empty( $day->description ) ) : ?>
						<p class="yatravanta-timeline-day-summary"><?php echo esc_html( $day->description ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $items ) ) : ?>
						<button type="button" class="yatravanta-day-toggle-btn" data-label-more="<?php echo esc_attr__( 'Show More', 'yatravanta-trek-booking-manager' ); ?>" data-label-less="<?php echo esc_attr__( 'Show Less', 'yatravanta-trek-booking-manager' ); ?>">
							<span class="yatravanta-toggle-label"><?php esc_html_e( 'Show More', 'yatravanta-trek-booking-manager' ); ?></span>
							<span class="dashicons dashicons-arrow-down-alt2"></span>
						</button>

						<div class="yatravanta-timeline-events">
							<?php
							foreach ( $items as $item ) :
								$icon_class = $item->icon ? $item->icon : 'dashicons-palmtree';
								?>
								<div class="yatravanta-timeline-event-card">
									<div class="yatravanta-event-icon-wrapper"><span class="dashicons <?php echo esc_attr( $icon_class ); ?>"></span></div>
									<div class="yatravanta-event-content-box">
										<div class="yatravanta-event-meta">
											<span class="yatravanta-event-time"><?php echo esc_html( $item->item_time ); ?></span>
										</div>
										<h5 class="yatravanta-event-title"><?php echo esc_html( $item->title ); ?></h5>
										<?php if ( ! empty( $item->description ) ) : ?>
											<p class="yatravanta-event-description"><?php echo wp_kses_post( $item->description ); ?></p>
										<?php endif; ?>
										<?php if ( ! empty( $item->image_url ) ) : ?>
											<div class="yatravanta-event-media"><img src="<?php echo esc_url( $item->image_url ); ?>" alt="<?php echo esc_attr( $item->title ); ?>" /></div>
										<?php endif; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
		return ob_get_clean();
	}
}
