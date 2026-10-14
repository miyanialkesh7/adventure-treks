<?php
/**
 * Bookings List View.
 *
 * @package YatraVanta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Trek Bookings', 'yatravanta-trek-booking-manager' ); ?></h1>
	<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=yatravanta_trek&page=yatravanta-bookings&action=add' ) ); ?>" class="page-title-action"><?php esc_html_e( 'Add New', 'yatravanta-trek-booking-manager' ); ?></a>
	<hr class="wp-header-end">

	<?php
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['yatravanta_saved'] ) ) :
		?>
		<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Booking saved successfully.', 'yatravanta-trek-booking-manager' ); ?></p></div>
		<?php
	endif;
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['yatravanta_error'] ) ) :
		?>
		<div class="notice notice-error is-dismissible"><p><?php esc_html_e( 'Please fill in all the required fields before saving the booking.', 'yatravanta-trek-booking-manager' ); ?></p></div>
		<?php
	endif;
	?>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder">
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<?php $table->views(); ?>
					<form method="get">
						<input type="hidden" name="post_type" value="yatravanta_trek" />
						<input type="hidden" name="page" value="yatravanta-bookings" />
						<?php
						// phpcs:ignore WordPress.Security.NonceVerification.Recommended
						if ( isset( $_GET['booking_view'] ) && 'trash' === $_GET['booking_view'] ) :
							?>
							<input type="hidden" name="booking_view" value="trash" />
						<?php endif; ?>
						<?php
						$table->search_box( __( 'Search', 'yatravanta-trek-booking-manager' ), 'search_id' );
						$table->display();
						?>
					</form>
				</div>
			</div>
		</div>
		<br class="clear">
	</div>

	<!-- Booking Details View Modal -->
	<div class="yatravanta-modal-overlay" id="yatravanta_booking_view_modal" style="display:none;">
		<div class="yatravanta-modal-box" style="max-width:600px;">
			<div class="yatravanta-modal-header">
				<h2><?php esc_html_e( 'Booking Details', 'yatravanta-trek-booking-manager' ); ?> - <span id="yatravanta_booking_view_ref"></span></h2>
				<span class="yatravanta-modal-close" id="yatravanta_booking_view_close_btn">&times;</span>
			</div>
			<div class="yatravanta-modal-body">
				<div class="yatravanta-loading-spinner" id="yatravanta_booking_view_loading">
					<span class="spinner is-active"></span> <?php esc_html_e( 'Loading booking details...', 'yatravanta-trek-booking-manager' ); ?>
				</div>
				<table class="widefat striped" id="yatravanta_booking_view_table" style="display:none;">
					<tbody id="yatravanta_booking_view_tbody">
						<!-- Populated via AJAX -->
					</tbody>
				</table>
			</div>
			<div class="yatravanta-modal-footer">
				<button type="button" class="button" id="yatravanta_booking_view_close_btn2"><?php esc_html_e( 'Close', 'yatravanta-trek-booking-manager' ); ?></button>
			</div>
		</div>
	</div>
</div>
