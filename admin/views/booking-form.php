<?php
/**
 * Add/Edit Booking admin form view.
 *
 * @package    YatraVanta
 * @subpackage YatraVanta/Admin/Views
 * @author     Nilesh Vastarpara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$yatravanta_is_edit  = ! empty( $booking );
$yatravanta_currency = get_option( 'yatravanta_currency_symbol', '$' );
?>
<div class="wrap">
	<h1 class="wp-heading-inline">
		<?php echo $yatravanta_is_edit ? esc_html__( 'Edit Booking', 'yatravanta-trek-booking-manager' ) : esc_html__( 'Add New Booking', 'yatravanta-trek-booking-manager' ); ?>
	</h1>
	<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=yatravanta_trek&page=yatravanta-bookings' ) ); ?>" class="page-title-action"><?php esc_html_e( 'Back to Bookings', 'yatravanta-trek-booking-manager' ); ?></a>
	<hr class="wp-header-end">

	<?php if ( $yatravanta_is_edit ) : ?>
		<p><strong><?php esc_html_e( 'Booking ID:', 'yatravanta-trek-booking-manager' ); ?></strong> <?php echo esc_html( \YatraVanta\Admin\Controllers\TrekBookingsController::format_booking_ref( $booking->id ) ); ?></p>
	<?php endif; ?>

	<?php
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['yatravanta_seats_error'] ) ) :
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$yatravanta_max_seats = absint( wp_unslash( $_GET['yatravanta_seats_error'] ) );
		?>
		<div class="notice notice-error is-dismissible">
			<p>
				<?php
				if ( $yatravanta_max_seats > 0 ) {
					printf(
						/* translators: %d: maximum number of seats available for the selected date. */
						esc_html__( 'Only %d seat(s) available for the selected departure date. Please reduce Adults/Children or choose another date.', 'yatravanta-trek-booking-manager' ),
						(int) $yatravanta_max_seats
					);
				} else {
					esc_html_e( 'No seats are available for the selected departure date. Please choose another date.', 'yatravanta-trek-booking-manager' );
				}
				?>
			</p>
		</div>
		<?php
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	elseif ( isset( $_GET['yatravanta_error'] ) ) :
		?>
		<div class="notice notice-error is-dismissible"><p><?php esc_html_e( 'Please fill in all the required fields before saving the booking.', 'yatravanta-trek-booking-manager' ); ?></p></div>
		<?php
	endif;
	?>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="yatravanta_booking_form">
		<?php wp_nonce_field( 'yatravanta_save_booking', 'yatravanta_booking_nonce' ); ?>
		<input type="hidden" name="action" value="yatravanta_save_booking" />
		<input type="hidden" name="booking_id" value="<?php echo esc_attr( $yatravanta_is_edit ? $booking->id : 0 ); ?>" />
		<input type="hidden" name="total_amount" id="yatravanta_b_amount" value="<?php echo esc_attr( $yatravanta_is_edit ? $booking->total_amount : '0.00' ); ?>" />

		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><label for="yatravanta_b_trek"><?php esc_html_e( 'Trek', 'yatravanta-trek-booking-manager' ); ?> <span class="yatravanta-required">*</span></label></th>
					<td>
						<select name="trek_id" id="yatravanta_b_trek" class="regular-text" required>
							<option value=""><?php esc_html_e( '-- Select Trek --', 'yatravanta-trek-booking-manager' ); ?></option>
							<?php foreach ( $treks as $yatravanta_trek ) : ?>
								<option value="<?php echo esc_attr( $yatravanta_trek->ID ); ?>" <?php selected( $yatravanta_is_edit ? $booking->trek_id : 0, $yatravanta_trek->ID ); ?>>
									<?php echo esc_html( $yatravanta_trek->post_title ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="yatravanta_b_city"><?php esc_html_e( 'Departure City', 'yatravanta-trek-booking-manager' ); ?> <span class="yatravanta-required">*</span></label></th>
					<td>
						<select name="city_id" id="yatravanta_b_city" class="regular-text" data-selected="<?php echo esc_attr( $yatravanta_is_edit ? $booking->city_id : '' ); ?>" required>
							<option value=""><?php esc_html_e( '-- Select Trek First --', 'yatravanta-trek-booking-manager' ); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="yatravanta_b_date"><?php esc_html_e( 'Travel Date', 'yatravanta-trek-booking-manager' ); ?> <span class="yatravanta-required">*</span></label></th>
					<td>
						<select name="date_id" id="yatravanta_b_date" class="regular-text" data-selected="<?php echo esc_attr( $yatravanta_is_edit ? $booking->date_id : '' ); ?>" required>
							<option value=""><?php esc_html_e( '-- Select City First --', 'yatravanta-trek-booking-manager' ); ?></option>
						</select>
					</td>
				</tr>
				<tr id="yatravanta_b_pickup_row" style="display:none;">
					<th scope="row"><label for="yatravanta_b_pickup"><?php esc_html_e( 'Pickup Point', 'yatravanta-trek-booking-manager' ); ?></label></th>
					<td>
						<select name="pickup_point" id="yatravanta_b_pickup" class="regular-text" data-selected="<?php echo esc_attr( $yatravanta_is_edit ? $booking->pickup_point : '' ); ?>">
							<option value=""><?php esc_html_e( '-- Select Pickup Point --', 'yatravanta-trek-booking-manager' ); ?></option>
						</select>
					</td>
				</tr>
				<tr id="yatravanta_b_transport_row" style="display:none;">
					<th scope="row"><label for="yatravanta_b_transport"><?php esc_html_e( 'Transportation Type', 'yatravanta-trek-booking-manager' ); ?></label></th>
					<td>
						<select name="transport_type" id="yatravanta_b_transport" class="regular-text" data-selected="<?php echo esc_attr( $yatravanta_is_edit ? $booking->transport_type : '' ); ?>">
							<option value=""><?php esc_html_e( '-- Select Transportation Type --', 'yatravanta-trek-booking-manager' ); ?></option>
						</select>
						<input type="hidden" name="transport_price" id="yatravanta_b_transport_price" value="<?php echo esc_attr( $yatravanta_is_edit ? $booking->transport_price : '0.00' ); ?>" />
					</td>
				</tr>
				<tr id="yatravanta_b_addons_row" style="display:none;">
					<th scope="row"><?php esc_html_e( 'Add-ons', 'yatravanta-trek-booking-manager' ); ?></th>
					<td>
						<div id="yatravanta_b_addons_container" data-selected="<?php echo esc_attr( $yatravanta_is_edit && ! empty( $booking->addons ) ? $booking->addons : '[]' ); ?>"></div>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="yatravanta_b_name"><?php esc_html_e( 'Customer Name', 'yatravanta-trek-booking-manager' ); ?> <span class="yatravanta-required">*</span></label></th>
					<td><input type="text" name="cust_name" id="yatravanta_b_name" class="regular-text" required value="<?php echo esc_attr( $yatravanta_is_edit ? $booking->cust_name : '' ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="yatravanta_b_email"><?php esc_html_e( 'Email Address', 'yatravanta-trek-booking-manager' ); ?> <span class="yatravanta-required">*</span></label></th>
					<td><input type="email" name="cust_email" id="yatravanta_b_email" class="regular-text" required value="<?php echo esc_attr( $yatravanta_is_edit ? $booking->cust_email : '' ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="yatravanta_b_phone"><?php esc_html_e( 'Phone Number', 'yatravanta-trek-booking-manager' ); ?> <span class="yatravanta-required">*</span></label></th>
					<td><input type="text" name="cust_phone" id="yatravanta_b_phone" class="regular-text" required value="<?php echo esc_attr( $yatravanta_is_edit ? $booking->cust_phone : '' ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="yatravanta_b_adults"><?php esc_html_e( 'Adults', 'yatravanta-trek-booking-manager' ); ?> <span class="yatravanta-required">*</span></label></th>
					<td><input type="number" name="num_adults" id="yatravanta_b_adults" min="1" required value="<?php echo esc_attr( $yatravanta_is_edit ? $booking->num_adults : 1 ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="yatravanta_b_children"><?php esc_html_e( 'Children', 'yatravanta-trek-booking-manager' ); ?></label></th>
					<td><input type="number" name="num_children" id="yatravanta_b_children" min="0" value="<?php echo esc_attr( $yatravanta_is_edit ? $booking->num_children : 0 ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Price Breakdown', 'yatravanta-trek-booking-manager' ); ?></th>
					<td>
						<div id="yatravanta_b_price_breakdown" class="yatravanta-price-breakdown-box">
							<p class="description"><?php esc_html_e( 'Select a trek, city, and date to calculate pricing automatically.', 'yatravanta-trek-booking-manager' ); ?></p>
						</div>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="yatravanta_b_status"><?php esc_html_e( 'Status', 'yatravanta-trek-booking-manager' ); ?> <span class="yatravanta-required">*</span></label></th>
					<td>
						<select name="status" id="yatravanta_b_status" required>
							<?php
							$yatravanta_status_options = array(
								'pending'   => __( 'Pending', 'yatravanta-trek-booking-manager' ),
								'confirmed' => __( 'Confirmed', 'yatravanta-trek-booking-manager' ),
								'cancelled' => __( 'Cancelled', 'yatravanta-trek-booking-manager' ),
							);
							$yatravanta_current_status = $yatravanta_is_edit ? $booking->status : 'pending';
							foreach ( $yatravanta_status_options as $yatravanta_status_val => $yatravanta_status_label ) :
								?>
								<option value="<?php echo esc_attr( $yatravanta_status_val ); ?>" <?php selected( $yatravanta_current_status, $yatravanta_status_val ); ?>><?php echo esc_html( $yatravanta_status_label ); ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="yatravanta_b_amount_paid"><?php esc_html_e( 'Amount Received', 'yatravanta-trek-booking-manager' ); ?></label></th>
					<td>
						<input type="number" name="amount_paid" id="yatravanta_b_amount_paid" min="0" step="0.01" value="<?php echo esc_attr( $yatravanta_is_edit ? $booking->amount_paid : '0.00' ); ?>" />
						<button type="button" class="button" id="yatravanta_b_mark_paid"><?php esc_html_e( 'Mark fully paid', 'yatravanta-trek-booking-manager' ); ?></button>
						<p class="description"><?php esc_html_e( 'Enter the advance / token amount received so far. Payment status and balance update automatically.', 'yatravanta-trek-booking-manager' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Payment Status', 'yatravanta-trek-booking-manager' ); ?></th>
					<td>
						<strong id="yatravanta_b_pay_status">-</strong>
						<span id="yatravanta_b_pay_balance" style="margin-left:12px;"></span>
					</td>
				</tr>
			</tbody>
		</table>

		<?php submit_button( $yatravanta_is_edit ? __( 'Update Booking', 'yatravanta-trek-booking-manager' ) : __( 'Add Booking', 'yatravanta-trek-booking-manager' ) ); ?>
	</form>
</div>
