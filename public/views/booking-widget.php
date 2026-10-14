<?php
/**
 * Frontend Booking Widget view template.
 *
 * @package    YatraVanta
 * @subpackage YatraVanta/Public/Views
 * @author     Nilesh Vastarpara
 */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Age ranges shown beside the Adults / Children counters (set per trek; defaults respect the trek's Age Limit when blank).
global $wpdb;
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
$yatravanta_age_limit    = (string) $wpdb->get_var( $wpdb->prepare( "SELECT age_limit FROM {$wpdb->prefix}yatravanta_treks WHERE post_id = %d", $trek_id ) );
$yatravanta_adult_age    = get_post_meta( $trek_id, '_yatravanta_adult_age', true );
$yatravanta_child_age    = get_post_meta( $trek_id, '_yatravanta_child_age', true );
$yatravanta_age_defaults = \YatraVanta\Admin\Controllers\TrekMetaBoxController::default_ages( $yatravanta_age_limit );
$yatravanta_adult_age    = '' !== $yatravanta_adult_age ? $yatravanta_adult_age : $yatravanta_age_defaults['adult'];
$yatravanta_child_age    = '' !== $yatravanta_child_age ? $yatravanta_child_age : $yatravanta_age_defaults['child'];
?>
<div class="yatravanta-booking-widget-wrapper" id="yatravanta_booking_widget_root" data-trek-id="<?php echo esc_attr( $trek_id ); ?>">
	
	<!-- Header Block -->
	<div class="yatravanta-widget-header">
		<h3><?php esc_html_e( 'Book Your Trek', 'yatravanta-trek-booking-manager' ); ?></h3>
		<p class="yatravanta-widget-subtitle"><?php esc_html_e( 'Select departure city and dates to check availability and calculate price.', 'yatravanta-trek-booking-manager' ); ?></p>
	</div>

	<!-- Step 1: Select Departure City -->
	<div class="yatravanta-widget-section">
		<h4 class="yatravanta-section-title"><span class="yatravanta-step-badge">1</span> <?php esc_html_e( 'Select Departure City', 'yatravanta-trek-booking-manager' ); ?></h4>
		<div class="yatravanta-city-pills-grid" id="yatravanta_widget_city_pills">
			<?php if ( ! empty( $cities ) ) : ?>
				<?php foreach ( $cities as $c ) : ?>
					<button type="button" class="yatravanta-city-pill-btn" data-id="<?php echo esc_attr( $c->id ); ?>">
						<span class="dashicons dashicons-location"></span> <?php echo esc_html( $c->city_name ); ?>
					</button>
				<?php endforeach; ?>
			<?php else : ?>
				<p style="color:#666; font-style:italic;"><?php esc_html_e( 'No departure cities configured for this trek.', 'yatravanta-trek-booking-manager' ); ?></p>
			<?php endif; ?>
		</div>
	</div>

	<!-- Step 2: Select Transportation Type (Hidden initially until City is selected) -->
	<div class="yatravanta-widget-section" id="yatravanta_widget_transport_section" style="display: none;">
		<h4 class="yatravanta-section-title"><span class="yatravanta-step-badge">2</span> <?php esc_html_e( 'Select Transportation Type', 'yatravanta-trek-booking-manager' ); ?></h4>
		<div class="yatravanta-transport-options-list" id="yatravanta_widget_transport_list">
			<!-- Populated via AJAX -->
		</div>
	</div>

	<!-- Step 3: Select Date (Hidden initially until City is selected) -->
	<div class="yatravanta-widget-section" id="yatravanta_widget_date_section" style="display: none;">
		<h4 class="yatravanta-section-title"><span class="yatravanta-step-badge">3</span> <?php esc_html_e( 'Select Departure Date', 'yatravanta-trek-booking-manager' ); ?></h4>
		<div class="yatravanta-loading-indicator" id="yatravanta_widget_dates_loading" style="display:none; padding: 15px 0; align-items: center; color: var(--yatravanta-primary-color, #137a7f); font-weight: 600;">
			<span class="dashicons dashicons-update" style="animation: spin 2s linear infinite; margin-right: 8px;"></span> <?php esc_html_e( 'Finding scheduled slots...', 'yatravanta-trek-booking-manager' ); ?>
		</div>
		<div class="yatravanta-dates-grid" id="yatravanta_widget_dates_grid">
			<!-- Populated via AJAX -->
		</div>
	</div>

	<!-- Loader for Booking Details -->
	<div class="yatravanta-loading-indicator" id="yatravanta_widget_details_loading" style="display:none; padding: 20px 0; justify-content: center; align-items: center; color: var(--yatravanta-primary-color, #137a7f); font-weight: 600;">
		<span class="dashicons dashicons-update" style="animation: spin 2s linear infinite; margin-right: 8px;"></span> <?php esc_html_e( 'Loading booking details...', 'yatravanta-trek-booking-manager' ); ?>
	</div>

	<!-- Step 3: Details Panel & Booking Calculator (Hidden until Date is selected) -->
	<div class="yatravanta-widget-section" id="yatravanta_widget_details_section" style="display: none;">
		<h4 class="yatravanta-section-title"><span class="yatravanta-step-badge">4</span> <?php esc_html_e( 'Configure Booking Details', 'yatravanta-trek-booking-manager' ); ?></h4>
		
		<!-- Availability Badge & Transports Grid -->
		<div class="yatravanta-widget-details-grid">
			<div class="yatravanta-detail-card">
				<span class="yatravanta-detail-label"><?php esc_html_e( 'Seat Availability', 'yatravanta-trek-booking-manager' ); ?></span>
				<div class="yatravanta-detail-value" id="yatravanta_widget_avail_seats">--</div>
			</div>
			<div class="yatravanta-detail-card">
				<span class="yatravanta-detail-label"><?php esc_html_e( 'Transport Type', 'yatravanta-trek-booking-manager' ); ?></span>
				<div class="yatravanta-detail-value" id="yatravanta_widget_transport">--</div>
			</div>
			<div class="yatravanta-detail-card">
				<span class="yatravanta-detail-label"><?php esc_html_e( 'Reporting Time', 'yatravanta-trek-booking-manager' ); ?></span>
				<div class="yatravanta-detail-value" id="yatravanta_widget_reporting">--</div>
			</div>
		</div>

		<!-- Pricing Info Box -->
		<div class="yatravanta-pricing-infobox">
			<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap: wrap; gap: 5px;">
				<span style="font-weight:600; flex-shrink: 0;"><?php esc_html_e( 'Per Person Rate', 'yatravanta-trek-booking-manager' ); ?>:</span>
				<div style="text-align:right; white-space:nowrap; flex-shrink: 0;">
					<span id="yatravanta_widget_base_price_cross" style="text-decoration:line-through; color:#999; margin-right:8px; display:none; white-space:nowrap;"></span>
					<span id="yatravanta_widget_price_tag" style="font-size:18px; font-weight:700; color:var(--yatravanta-primary-color, #137a7f); white-space:nowrap;"></span>
				</div>
			</div>
			<p class="description" id="yatravanta_widget_child_price_tag" style="margin:5px 0 0 0; text-align:right; font-size:11px; display:none;"></p>
			<p class="description" id="yatravanta_widget_date_notes" style="margin:8px 0 0 0; font-style:italic; font-size:11px; color:#b32d2e; display:none;"></p>
		</div>

		<!-- Pax Count Inputs -->
		<div class="yatravanta-pax-calculator">
			<div class="yatravanta-pax-row">
				<div class="yatravanta-pax-label">
					<strong><?php esc_html_e( 'Adults', 'yatravanta-trek-booking-manager' ); ?></strong>
					<span class="description"><?php echo esc_html( sprintf( /* translators: %s: age range, e.g. 12+ */ __( 'Age %s', 'yatravanta-trek-booking-manager' ), $yatravanta_adult_age ) ); ?></span>
				</div>
				<div class="yatravanta-pax-counter">
					<button type="button" class="yatravanta-counter-btn minus" data-type="adults">-</button>
					<input type="number" id="yatravanta_widget_pax_adults" value="1" min="1" readonly />
					<button type="button" class="yatravanta-counter-btn plus" data-type="adults">+</button>
				</div>
			</div>
			<div class="yatravanta-pax-row" id="yatravanta_widget_child_row" style="display:none;">
				<div class="yatravanta-pax-label">
					<strong><?php esc_html_e( 'Children', 'yatravanta-trek-booking-manager' ); ?></strong>
					<?php if ( '' !== $yatravanta_child_age ) : ?>
						<span class="description"><?php echo esc_html( sprintf( /* translators: %s: age range, e.g. 5-11 */ __( 'Age %s', 'yatravanta-trek-booking-manager' ), $yatravanta_child_age ) ); ?></span>
					<?php endif; ?>
				</div>
				<div class="yatravanta-pax-counter">
					<button type="button" class="yatravanta-counter-btn minus" data-type="children">-</button>
					<input type="number" id="yatravanta_widget_pax_children" value="0" min="0" readonly />
					<button type="button" class="yatravanta-counter-btn plus" data-type="children">+</button>
				</div>
			</div>
		</div>

		<!-- Optional Add-ons checklists -->
		<div class="yatravanta-widget-addons-container" id="yatravanta_widget_addons_section" style="display:none;">
			<h5 style="margin:0 0 10px 0; font-size:12px; font-weight:600; color:#3c434a;"><?php esc_html_e( 'Choose Optional Add-ons', 'yatravanta-trek-booking-manager' ); ?></h5>
			<div id="yatravanta_widget_addons_list">
				<!-- Populated dynamically -->
			</div>
		</div>

		<!-- Receipt Breakdown List -->
		<div class="yatravanta-booking-receipt">
			<h5 style="margin:0 0 8px 0; font-size:12px; font-weight:600; color:#3c434a;"><?php esc_html_e( 'Price Breakdown', 'yatravanta-trek-booking-manager' ); ?></h5>
			<div class="yatravanta-receipt-rows" id="yatravanta_widget_receipt_rows">
				<!-- Dynamically generated receipt -->
			</div>
			<div class="yatravanta-receipt-total">
				<span><?php esc_html_e( 'Total', 'yatravanta-trek-booking-manager' ); ?>:</span>
				<strong id="yatravanta_widget_grand_total">--</strong>
			</div>
		</div>

		<!-- Trigger Checkout Button -->
		<button type="button" class="yatravanta-book-now-btn" id="yatravanta_widget_checkout_btn">
			<?php esc_html_e( 'Book Trek Now', 'yatravanta-trek-booking-manager' ); ?>
		</button>
	</div>

	<!-- Step 4: Checkout Form Dialog (Modal popup overlay) -->
	<div class="yatravanta-booking-modal" id="yatravanta_checkout_modal" style="display: none;">
		<div class="yatravanta-booking-modal-content">
			<div class="yatravanta-booking-modal-header">
				<h4><?php esc_html_e( 'Complete Your Booking', 'yatravanta-trek-booking-manager' ); ?></h4>
				<span class="yatravanta-booking-modal-close" id="yatravanta_checkout_modal_close">&times;</span>
			</div>
			<div class="yatravanta-booking-modal-body">
				<form id="yatravanta_checkout_form" novalidate>
					<div class="yatravanta-form-field">
						<label for="yatravanta_checkout_name"><?php esc_html_e( 'Full Name *', 'yatravanta-trek-booking-manager' ); ?></label>
						<input type="text" id="yatravanta_checkout_name" name="cust_name" required placeholder="<?php esc_attr_e( 'e.g. Nilesh Vastarpara', 'yatravanta-trek-booking-manager' ); ?>" />
					</div>
					<div class="yatravanta-form-field">
						<label for="yatravanta_checkout_email"><?php esc_html_e( 'Email Address *', 'yatravanta-trek-booking-manager' ); ?></label>
						<input type="email" id="yatravanta_checkout_email" name="cust_email" required placeholder="<?php esc_attr_e( 'e.g. nilesh@example.com', 'yatravanta-trek-booking-manager' ); ?>" />
					</div>
					<div class="yatravanta-form-field">
						<label for="yatravanta_checkout_phone"><?php esc_html_e( 'Phone Number *', 'yatravanta-trek-booking-manager' ); ?></label>
						<input type="tel" id="yatravanta_checkout_phone" name="cust_phone" required placeholder="<?php esc_attr_e( 'e.g. +91 98765 43210', 'yatravanta-trek-booking-manager' ); ?>" />
					</div>
					<div class="yatravanta-form-field" id="yatravanta_checkout_pickup_field" style="display:none;">
						<label for="yatravanta_checkout_pickup"><?php esc_html_e( 'Preferred Pickup Location *', 'yatravanta-trek-booking-manager' ); ?></label>
						<select id="yatravanta_checkout_pickup" name="pickup_point">
							<!-- Populated via AJAX -->
						</select>
						<span class="description" id="yatravanta_checkout_pickup_instructions" style="font-size:11px; margin-top:5px; display:block; color:#666;"></span>
					</div>
				</form>
				<div class="yatravanta-checkout-terms" id="yatravanta_checkout_terms_wrap">
					<label style="display:flex; align-items:flex-start; gap:8px; font-size:12px; line-height:1.5; color:#666; margin:0; cursor:pointer;">
						<input type="checkbox" id="yatravanta_checkout_terms_agree" style="margin:2px 0 0; flex-shrink:0;" />
						<span><?php esc_html_e( 'By booking, you agree to the cancellation, refund policies, and terms and conditions configured for this trek.', 'yatravanta-trek-booking-manager' ); ?></span>
					</label>
				</div>
			</div>
			<div class="yatravanta-booking-modal-footer">
				<button type="button" class="yatravanta-modal-btn cancel" id="yatravanta_checkout_cancel_btn"><?php esc_html_e( 'Cancel', 'yatravanta-trek-booking-manager' ); ?></button>
				<button type="button" class="yatravanta-modal-btn confirm" id="yatravanta_checkout_confirm_btn"><?php esc_html_e( 'Proceed to Payment', 'yatravanta-trek-booking-manager' ); ?></button>
			</div>
		</div>
	</div>

	<!-- Step 4.5: Payment Modal (price breakdown + admin-configured payment method) -->
	<div class="yatravanta-booking-modal" id="yatravanta_payment_modal" style="display: none;">
		<div class="yatravanta-booking-modal-content">
			<div class="yatravanta-booking-modal-header">
				<h4><?php esc_html_e( 'Payment', 'yatravanta-trek-booking-manager' ); ?></h4>
				<span class="yatravanta-booking-modal-close" id="yatravanta_payment_modal_close">&times;</span>
			</div>
			<div class="yatravanta-booking-modal-body">
				<div class="yatravanta-booking-receipt">
					<h5 style="margin:0 0 8px 0; font-size:12px; font-weight:600; color:#3c434a;"><?php esc_html_e( 'Price Breakdown', 'yatravanta-trek-booking-manager' ); ?></h5>
					<div class="yatravanta-receipt-rows" id="yatravanta_payment_receipt_rows">
						<!-- Cloned from the booking calculator receipt -->
					</div>
					<div class="yatravanta-receipt-total">
						<span><?php esc_html_e( 'Total', 'yatravanta-trek-booking-manager' ); ?>:</span>
						<strong id="yatravanta_payment_grand_total">--</strong>
					</div>
				</div>

				<div class="yatravanta-payment-method-box" id="yatravanta_payment_method_box">
					<!-- Populated dynamically based on admin Payment settings -->
				</div>
			</div>
			<div class="yatravanta-booking-modal-footer">
				<button type="button" class="yatravanta-modal-btn cancel" id="yatravanta_payment_back_btn"><?php esc_html_e( 'Back', 'yatravanta-trek-booking-manager' ); ?></button>
				<button type="button" class="yatravanta-modal-btn confirm" id="yatravanta_payment_confirm_btn"><?php esc_html_e( 'Confirm Reservation', 'yatravanta-trek-booking-manager' ); ?></button>
			</div>
		</div>
	</div>

	<!-- Step 5: Success Receipt Summary Overlay -->
	<div class="yatravanta-booking-modal" id="yatravanta_success_modal" style="display: none;">
		<div class="yatravanta-booking-modal-content" style="max-width:450px; text-align:center;">
			<div class="yatravanta-booking-modal-body" style="padding:30px 20px;">
				<span class="yatravanta-success-check" aria-hidden="true">
					<svg viewBox="0 0 24 24" width="34" height="34" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
				</span>
				<h3 class="yatravanta-success-title"><?php esc_html_e( 'Thank you for your booking!', 'yatravanta-trek-booking-manager' ); ?></h3>
				
				<p style="font-size:13px; color:#555; margin-bottom:20px; line-height:1.4;">
					<?php esc_html_e( 'Your reservation is pending confirmation. We have sent your booking receipt by email, and you will receive another email once your reservation is confirmed.', 'yatravanta-trek-booking-manager' ); ?>
				</p>
				<div class="yatravanta-success-receipt" id="yatravanta_success_receipt_body" style="margin-bottom:20px;">
					<!-- Loaded via response -->
				</div>
				<button type="button" class="yatravanta-book-now-btn" id="yatravanta_success_close_btn" style="margin:0; width:100%;">
					<?php esc_html_e( 'Close & Return', 'yatravanta-trek-booking-manager' ); ?>
				</button>
			</div>
		</div>
	</div>

</div>

<!-- Sticky Bottom Booking Bar (mobile + desktop) -->
<div class="yatravanta-sticky-booking-bar" id="yatravanta_sticky_booking_bar" style="display: none;">
	<div class="yatravanta-sticky-bar-price">
		<span class="yatravanta-sticky-bar-label"><?php esc_html_e( 'Total', 'yatravanta-trek-booking-manager' ); ?>:</span>
		<span class="yatravanta-sticky-bar-amount" id="yatravanta_sticky_bar_amount">--</span>
		<span class="yatravanta-sticky-bar-unit" id="yatravanta_sticky_bar_unit">/ <?php esc_html_e( 'Person', 'yatravanta-trek-booking-manager' ); ?></span>
	</div>
	<button type="button" class="yatravanta-sticky-bar-cta" id="yatravanta_sticky_bar_cta">
		<?php esc_html_e( 'Book Now', 'yatravanta-trek-booking-manager' ); ?>
	</button>
</div>
