<?php
/**
 * Departure Cities meta box view template
 *
 * @package    YatraVanta
 * @subpackage YatraVanta/Admin/Views
 * @author     Nilesh Vastarpara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="yatravanta-departures-container" id="yatravanta_departures_module_root" data-trek-id="<?php echo esc_attr( get_the_ID() ); ?>">

	<!-- Control Bar -->
	<div class="yatravanta-departures-header">
		<p class="description"><?php esc_html_e( 'Manage departure locations for this trek. Drag rows to change their display order on the frontend booking widget.', 'yatravanta-trek-booking-manager' ); ?></p>
		<button type="button" class="button button-primary" id="yatravanta_add_city_btn">
			+ <?php esc_html_e( 'Add Departure City', 'yatravanta-trek-booking-manager' ); ?>
		</button>
	</div>

	<!-- Loading Spinner -->
	<div class="yatravanta-loading-spinner" id="yatravanta_cities_loading" style="display:none;">
		<span class="spinner is-active"></span> <?php esc_html_e( 'Loading cities data...', 'yatravanta-trek-booking-manager' ); ?>
	</div>

	<!-- Next steps after adding a city (filled by admin-departures.js) -->
	<div class="yatravanta-next-steps" id="yatravanta_city_next_steps" style="display:none;"></div>

	<!-- Cities Grid List -->
	<div class="yatravanta-cities-list-wrapper">
		<table class="widefat fixed striped yatravanta-admin-table" id="yatravanta_cities_table">
			<thead>
				<tr>
					<th class="column-order" style="width: 30px;"></th>
					<th class="column-name" style="width: 200px;"><?php esc_html_e( 'City Name', 'yatravanta-trek-booking-manager' ); ?></th>
					<th class="column-price" style="width: 90px;"><?php esc_html_e( 'Base Price', 'yatravanta-trek-booking-manager' ); ?></th>
					<th class="column-offer" style="width: 90px;"><?php esc_html_e( 'Offer Price', 'yatravanta-trek-booking-manager' ); ?></th>
					<th class="column-transport" style="width: 130px;"><?php esc_html_e( 'Transport', 'yatravanta-trek-booking-manager' ); ?></th>
					<th class="column-deadline" style="width: 110px;"><?php esc_html_e( 'Deadline (Days)', 'yatravanta-trek-booking-manager' ); ?></th>
					<th class="column-status" style="width: 90px;"><?php esc_html_e( 'Status', 'yatravanta-trek-booking-manager' ); ?></th>
					<th class="column-actions" style="text-align: right;"><?php esc_html_e( 'Actions', 'yatravanta-trek-booking-manager' ); ?></th>
				</tr>
			</thead>
			<tbody id="yatravanta_cities_tbody">
				<!-- Loaded via AJAX -->
				<tr>
					<td colspan="8" style="text-align:center; padding:20px; color:#666;">
						<?php esc_html_e( 'No departure cities configured. Click the button above to add one.', 'yatravanta-trek-booking-manager' ); ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<!-- Add/Edit City Modal Dialog -->
	<div class="yatravanta-modal-overlay" id="yatravanta_city_modal" style="display: none;">
		<div class="yatravanta-modal-box">
			<div class="yatravanta-modal-header">
				<h3 id="yatravanta_modal_title"><?php esc_html_e( 'Add Departure City', 'yatravanta-trek-booking-manager' ); ?></h3>
				<span class="yatravanta-modal-close" id="yatravanta_modal_close_btn">&times;</span>
			</div>
			<div class="yatravanta-modal-body">
				<div id="yatravanta_city_form" class="yatravanta-city-form-wrapper">
					<input type="hidden" name="city_id" id="yatravanta_form_city_id" value="" />
					
					<div class="yatravanta-form-row">
						<label for="yatravanta_form_city_name"><?php esc_html_e( 'City Name', 'yatravanta-trek-booking-manager' ); ?> <span style="color: #d63638;">*</span></label>
						<input type="text" id="yatravanta_form_city_name" name="city_name" placeholder="<?php esc_attr_e( 'e.g. Surat', 'yatravanta-trek-booking-manager' ); ?>" />
					</div>

					<div class="yatravanta-form-grid-2">
						<div class="yatravanta-form-row">
							<label for="yatravanta_form_base_price"><?php esc_html_e( 'Base Price', 'yatravanta-trek-booking-manager' ); ?> <span style="color: #d63638;">*</span></label>
							<input type="number" step="1" id="yatravanta_form_base_price" name="base_price" value="0" min="0" />
						</div>
						<div class="yatravanta-form-row">
							<label for="yatravanta_form_offer_price"><?php esc_html_e( 'Offer Price', 'yatravanta-trek-booking-manager' ); ?></label>
							<input type="number" step="1" id="yatravanta_form_offer_price" name="offer_price" value="0" min="0" />
						</div>
					</div>

					<div class="yatravanta-form-grid-2">
						<div class="yatravanta-form-row">
							<label for="yatravanta_form_transport_type"><?php esc_html_e( 'Transport Type', 'yatravanta-trek-booking-manager' ); ?></label>
							<input type="text" id="yatravanta_form_transport_type" name="transport_type" placeholder="<?php esc_attr_e( 'e.g. AC Sleeper Bus / Train', 'yatravanta-trek-booking-manager' ); ?>" />
						</div>
						<div class="yatravanta-form-row">
							<label for="yatravanta_form_reporting_time"><?php esc_html_e( 'Reporting Time', 'yatravanta-trek-booking-manager' ); ?></label>
							<input type="text" id="yatravanta_form_reporting_time" name="reporting_time" class="yatravanta-timepicker" placeholder="<?php esc_attr_e( 'e.g. 09:30 AM', 'yatravanta-trek-booking-manager' ); ?>" />
						</div>
					</div>

					<div class="yatravanta-form-row">
						<label for="yatravanta_form_google_map_link"><?php esc_html_e( 'Google Map Link for Pickup', 'yatravanta-trek-booking-manager' ); ?></label>
						<input type="url" id="yatravanta_form_google_map_link" name="google_map_link" placeholder="<?php esc_attr_e( 'https://maps.google.com/...', 'yatravanta-trek-booking-manager' ); ?>" />
					</div>

					<div class="yatravanta-form-grid-2">
						<div class="yatravanta-form-row">
							<label for="yatravanta_form_booking_deadline"><?php esc_html_e( 'Booking Deadline (Days Before)', 'yatravanta-trek-booking-manager' ); ?></label>
							<input type="number" id="yatravanta_form_booking_deadline" name="booking_deadline" value="3" min="0" step="1" inputmode="numeric" data-yatravanta-integer="1" />
							<span class="description"><?php esc_html_e( 'Close booking X days prior to departure.', 'yatravanta-trek-booking-manager' ); ?></span>
						</div>
						<div class="yatravanta-form-row">
							<label for="yatravanta_form_status"><?php esc_html_e( 'Status', 'yatravanta-trek-booking-manager' ); ?></label>
							<select id="yatravanta_form_status" name="status">
								<option value="" disabled selected><?php esc_html_e( '-- Select Status --', 'yatravanta-trek-booking-manager' ); ?></option>
								<option value="active"><?php esc_html_e( 'Active', 'yatravanta-trek-booking-manager' ); ?></option>
								<option value="inactive"><?php esc_html_e( 'Inactive', 'yatravanta-trek-booking-manager' ); ?></option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="yatravanta-modal-footer">
				<button type="button" class="button" id="yatravanta_modal_cancel_btn"><?php esc_html_e( 'Cancel', 'yatravanta-trek-booking-manager' ); ?></button>
				<button type="button" class="button button-primary" id="yatravanta_modal_save_btn"><?php esc_html_e( 'Save City', 'yatravanta-trek-booking-manager' ); ?></button>
			</div>
		</div>
	</div>
	<!-- Dates List Modal -->
	<div class="yatravanta-modal-overlay" id="yatravanta_dates_modal" style="display: none;">
		<div class="yatravanta-modal-box" style="max-width: 850px; width: 90%;">
			<div class="yatravanta-modal-header">
				<h3><?php esc_html_e( 'Manage Departure Dates', 'yatravanta-trek-booking-manager' ); ?> - <span id="yatravanta_dates_modal_city_title"></span></h3>
				<span class="yatravanta-modal-close" id="yatravanta_dates_modal_close_btn">&times;</span>
			</div>
			<div class="yatravanta-modal-body">
				<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
					<p class="description" style="margin:0;"><?php esc_html_e( 'Schedule departure dates for this city. Overridden prices will apply only to that specific date.', 'yatravanta-trek-booking-manager' ); ?></p>
					<button type="button" class="button button-primary" id="yatravanta_add_date_btn">+ <?php esc_html_e( 'Add Departure Date', 'yatravanta-trek-booking-manager' ); ?></button>
				</div>
				<div class="yatravanta-loading-spinner" id="yatravanta_dates_loading" style="display:none;">
					<span class="spinner is-active"></span> <?php esc_html_e( 'Loading dates...', 'yatravanta-trek-booking-manager' ); ?>
				</div>
				<div class="yatravanta-table-scroll">
					<table class="widefat fixed striped yatravanta-admin-table" id="yatravanta_dates_table" style="margin-top:10px;">
					<thead>
						<tr>
							<th style="width: 130px;"><?php esc_html_e( 'Date', 'yatravanta-trek-booking-manager' ); ?></th>
							<th style="width: 110px;"><?php esc_html_e( 'Status', 'yatravanta-trek-booking-manager' ); ?></th>
							<th style="width: 140px;"><?php esc_html_e( 'Seats (Tot/Book/Avail)', 'yatravanta-trek-booking-manager' ); ?></th>
							<th><?php esc_html_e( 'Price Overrides', 'yatravanta-trek-booking-manager' ); ?></th>
							<th><?php esc_html_e( 'Notes', 'yatravanta-trek-booking-manager' ); ?></th>
							<th style="width: 120px; text-align: right;"><?php esc_html_e( 'Actions', 'yatravanta-trek-booking-manager' ); ?></th>
						</tr>
					</thead>
					<tbody id="yatravanta_dates_tbody">
						<!-- Loaded via AJAX -->
					</tbody>
				</table>
				</div>
			</div>
			<div class="yatravanta-modal-footer">
				<button type="button" class="button" id="yatravanta_dates_modal_back_btn"><?php esc_html_e( 'Close', 'yatravanta-trek-booking-manager' ); ?></button>
			</div>
		</div>
	</div>

	<!-- Add/Edit Date Form Modal -->
	<div class="yatravanta-modal-overlay" id="yatravanta_date_form_modal" style="display: none; z-index: 100000;">
		<div class="yatravanta-modal-box" style="max-width: 500px;">
			<div class="yatravanta-modal-header">
				<h3 id="yatravanta_date_form_title"><?php esc_html_e( 'Add Departure Date', 'yatravanta-trek-booking-manager' ); ?></h3>
				<span class="yatravanta-modal-close" id="yatravanta_date_form_close_btn">&times;</span>
			</div>
			<div class="yatravanta-modal-body">
				<div id="yatravanta_date_form" class="yatravanta-city-form-wrapper">
					<input type="hidden" name="date_id" id="yatravanta_form_date_id" value="" />
					<input type="hidden" name="date_city_id" id="yatravanta_form_date_city_id" value="" />

					<div class="yatravanta-form-row">
						<label for="yatravanta_form_departure_date"><?php esc_html_e( 'Departure Date', 'yatravanta-trek-booking-manager' ); ?> <span style="color: #d63638;">*</span></label>
						<input type="date" id="yatravanta_form_departure_date" name="departure_date" />
					</div>

					<div class="yatravanta-form-grid-2">
						<div class="yatravanta-form-row">
							<label for="yatravanta_form_date_total_seats"><?php esc_html_e( 'Total Seats', 'yatravanta-trek-booking-manager' ); ?> <span style="color: #d63638;">*</span></label>
							<input type="number" id="yatravanta_form_date_total_seats" name="total_seats" value="30" min="1" step="1" inputmode="numeric" data-yatravanta-integer="1" />
						</div>
						<div class="yatravanta-form-row">
							<label for="yatravanta_form_date_booked_seats"><?php esc_html_e( 'Booked Seats', 'yatravanta-trek-booking-manager' ); ?></label>
							<input type="number" id="yatravanta_form_date_booked_seats" name="booked_seats" value="0" min="0" step="1" inputmode="numeric" data-yatravanta-integer="1" />
						</div>
					</div>

					<div class="yatravanta-form-row">
						<label for="yatravanta_form_date_status"><?php esc_html_e( 'Status', 'yatravanta-trek-booking-manager' ); ?></label>
						<select id="yatravanta_form_date_status" name="status">
							<option value="" disabled selected><?php esc_html_e( '-- Select Status --', 'yatravanta-trek-booking-manager' ); ?></option>
							<option value="open"><?php esc_html_e( 'Open (Available)', 'yatravanta-trek-booking-manager' ); ?></option>
							<option value="seat_count"><?php esc_html_e( 'Seat Count (Show Remaining Seats)', 'yatravanta-trek-booking-manager' ); ?></option>
							<option value="few_seats"><?php esc_html_e( 'Few Seats Remaining (Hide Exact Count)', 'yatravanta-trek-booking-manager' ); ?></option>
							<option value="sold_out"><?php esc_html_e( 'Sold Out', 'yatravanta-trek-booking-manager' ); ?></option>
							<option value="cancelled"><?php esc_html_e( 'Cancelled', 'yatravanta-trek-booking-manager' ); ?></option>
						</select>
						<span class="description"><?php esc_html_e( 'Controls how availability is shown on the front-end. Total/Booked Seats above still manage the actual count.', 'yatravanta-trek-booking-manager' ); ?></span>
					</div>

					<div class="yatravanta-form-row">
						<label for="yatravanta_form_date_notes"><?php esc_html_e( 'Notes / Warning Message', 'yatravanta-trek-booking-manager' ); ?></label>
						<input type="text" id="yatravanta_form_date_notes" name="notes" placeholder="<?php esc_attr_e( 'e.g. Weather updates or custom notes', 'yatravanta-trek-booking-manager' ); ?>" />
					</div>

					<div style="border-top: 1px solid #ddd; margin: 15px 0 10px 0; padding-top: 10px; font-weight: bold; color: #23282d;">
						<?php esc_html_e( 'Pricing Overrides (Optional)', 'yatravanta-trek-booking-manager' ); ?>
					</div>

					<div class="yatravanta-form-grid-3" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
						<div class="yatravanta-form-row">
							<label for="yatravanta_form_date_adult_price"><?php esc_html_e( 'Adult Price', 'yatravanta-trek-booking-manager' ); ?></label>
							<input type="number" step="1" id="yatravanta_form_date_adult_price" name="adult_price" value="0" min="0" />
						</div>
						<div class="yatravanta-form-row">
							<label for="yatravanta_form_date_child_price"><?php esc_html_e( 'Child Price', 'yatravanta-trek-booking-manager' ); ?></label>
							<input type="number" step="1" id="yatravanta_form_date_child_price" name="child_price" value="0" min="0" />
						</div>
						<div class="yatravanta-form-row">
							<label for="yatravanta_form_date_offer_price"><?php esc_html_e( 'Offer Price', 'yatravanta-trek-booking-manager' ); ?></label>
							<input type="number" step="1" id="yatravanta_form_date_offer_price" name="offer_price" value="0" min="0" />
						</div>
					</div>
					<span class="description" style="display:block; margin-top:-5px; font-size:11px; color:#666;">
						<?php esc_html_e( 'Leave at 0.00 to fall back to the default departure city prices.', 'yatravanta-trek-booking-manager' ); ?>
					</span>
				</div>
			</div>
			<div class="yatravanta-modal-overlay-footer" style="display: flex; justify-content: flex-end; gap: 10px; padding: 15px 20px; background: #f0f0f1; border-top: 1px solid #ccd0d4;">
				<button type="button" class="button" id="yatravanta_date_form_cancel_btn"><?php esc_html_e( 'Cancel', 'yatravanta-trek-booking-manager' ); ?></button>
				<button type="button" class="button button-primary" id="yatravanta_date_form_save_btn"><?php esc_html_e( 'Save Date Config', 'yatravanta-trek-booking-manager' ); ?></button>
			</div>
		</div>
	</div>
	<!-- Itinerary Builder Modal -->
	<div class="yatravanta-modal-overlay" id="yatravanta_itinerary_modal" style="display: none;">
		<div class="yatravanta-modal-box" style="max-width: 900px; width: 95%; height: 90vh;">
			<div class="yatravanta-modal-header">
				<h3><?php esc_html_e( 'Itinerary Builder', 'yatravanta-trek-booking-manager' ); ?> - <span id="yatravanta_itinerary_modal_city_title"></span></h3>
				<span class="yatravanta-modal-close" id="yatravanta_itinerary_modal_close_btn">&times;</span>
			</div>
			<div class="yatravanta-modal-body" style="display: flex; gap: 20px; overflow: hidden; height: 100%; padding: 15px;">
				
				<!-- Left Column: Days List -->
				<div style="flex: 0 0 280px; width: 280px; min-width: 280px; box-sizing: border-box; display: flex; flex-direction: column; border-right: 1px solid #ddd; padding-right: 15px; height: 100%;">
					<div style="display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 15px;">
						<h4 style="margin: 0; font-size: 14px; font-weight: 600; white-space: nowrap;"><?php esc_html_e( 'Itinerary Days', 'yatravanta-trek-booking-manager' ); ?></h4>
						<button type="button" class="button button-small" id="yatravanta_add_day_btn" style="flex: 0 0 auto; white-space: nowrap;">+ <?php esc_html_e( 'Add Day', 'yatravanta-trek-booking-manager' ); ?></button>
					</div>
					<div id="yatravanta_itinerary_days_list" style="flex-grow: 1; overflow-y: auto; padding-right: 5px; display: flex; flex-direction: column; gap: 8px;">
						<!-- Days populated via AJAX -->
					</div>
				</div>

				<!-- Right Column: Timeline Events inside Selected Day -->
				<div style="flex-grow: 1; display: flex; flex-direction: column; height: 100%;">
					<div id="yatravanta_no_day_selected_msg" style="display: flex; flex-grow: 1; justify-content: center; align-items: center; text-align: center; color: #888;">
						<div>
							<span class="dashicons dashicons-calendar-alt" style="font-size: 48px; width:48px; height:48px; display:block; margin:0 auto 10px auto;"></span>
							<?php esc_html_e( 'Select a day from the left sidebar to manage its activities timeline.', 'yatravanta-trek-booking-manager' ); ?>
						</div>
					</div>

					<div id="yatravanta_day_timeline_wrapper" style="display: none; flex-direction: column; height: 100%;">
						<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
							<div>
								<h4 id="yatravanta_selected_day_title" style="margin: 0; font-size: 15px; font-weight: 600;"></h4>
								<p id="yatravanta_selected_day_desc" style="margin: 3px 0 0 0; font-size: 12px; color: #666; font-style: italic;"></p>
							</div>
							<div style="display:flex; align-items:center; gap:8px; flex-shrink:0; white-space:nowrap;">
								<button type="button" class="button" id="yatravanta_edit_selected_day_btn"><?php esc_html_e( 'Edit Day Settings', 'yatravanta-trek-booking-manager' ); ?></button>
								<button type="button" class="button button-primary" id="yatravanta_add_activity_btn">+ <?php esc_html_e( 'Add Activity', 'yatravanta-trek-booking-manager' ); ?></button>
							</div>
						</div>

						<div id="yatravanta_day_activities_timeline" style="flex-grow: 1; overflow-y: auto; display: flex; flex-direction: column; gap: 12px; padding: 5px 0;">
							<!-- Activities populated via AJAX -->
						</div>
					</div>
				</div>

			</div>
			<div class="yatravanta-modal-footer">
				<button type="button" class="button" id="yatravanta_itinerary_modal_back_btn"><?php esc_html_e( 'Close Builder', 'yatravanta-trek-booking-manager' ); ?></button>
			</div>
		</div>
	</div>

	<!-- Add/Edit Day Form Modal -->
	<div class="yatravanta-modal-overlay" id="yatravanta_day_form_modal" style="display: none; z-index: 100000;">
		<div class="yatravanta-modal-box" style="max-width: 450px;">
			<div class="yatravanta-modal-header">
				<h3 id="yatravanta_day_form_title"><?php esc_html_e( 'Add Day Settings', 'yatravanta-trek-booking-manager' ); ?></h3>
				<span class="yatravanta-modal-close" id="yatravanta_day_form_close_btn">&times;</span>
			</div>
			<div class="yatravanta-modal-body">
				<div id="yatravanta_day_form" class="yatravanta-city-form-wrapper">
					<input type="hidden" name="day_id" id="yatravanta_form_day_id" value="" />
					
					<div class="yatravanta-form-row">
						<label for="yatravanta_form_day_number"><?php esc_html_e( 'Day Number', 'yatravanta-trek-booking-manager' ); ?> <span style="color: #d63638;">*</span></label>
						<input type="number" id="yatravanta_form_day_number" name="day_number" value="1" min="0" step="1" inputmode="numeric" data-yatravanta-integer="1" />
						<span class="description"><?php esc_html_e( 'Use 0 for departure day assembly info, 1 for start, etc.', 'yatravanta-trek-booking-manager' ); ?></span>
					</div>

					<div class="yatravanta-form-row">
						<label for="yatravanta_form_day_title"><?php esc_html_e( 'Day Title', 'yatravanta-trek-booking-manager' ); ?> <span style="color: #d63638;">*</span></label>
						<input type="text" id="yatravanta_form_day_title" name="title" placeholder="<?php esc_attr_e( 'e.g. Arrival at Basecamp & Briefing', 'yatravanta-trek-booking-manager' ); ?>" />
					</div>

					<div class="yatravanta-form-row">
						<label for="yatravanta_form_day_description"><?php esc_html_e( 'Day Overview / Description', 'yatravanta-trek-booking-manager' ); ?></label>
						<textarea id="yatravanta_form_day_description" name="description" rows="4" placeholder="<?php esc_attr_e( 'Brief outline of this day\'s trekking milestones...', 'yatravanta-trek-booking-manager' ); ?>"></textarea>
					</div>
				</div>
			</div>
			<div class="yatravanta-modal-overlay-footer" style="display: flex; justify-content: flex-end; gap: 10px; padding: 15px 20px; background: #f0f0f1; border-top: 1px solid #ccd0d4;">
				<button type="button" class="button" id="yatravanta_day_form_cancel_btn"><?php esc_html_e( 'Cancel', 'yatravanta-trek-booking-manager' ); ?></button>
				<button type="button" class="button button-primary" id="yatravanta_day_form_save_btn"><?php esc_html_e( 'Save Day', 'yatravanta-trek-booking-manager' ); ?></button>
			</div>
		</div>
	</div>

	<!-- Add/Edit Activity Item Form Modal -->
	<div class="yatravanta-modal-overlay" id="yatravanta_activity_form_modal" style="display: none; z-index: 100001;">
		<div class="yatravanta-modal-box" style="max-width: 500px;">
			<div class="yatravanta-modal-header">
				<h3 id="yatravanta_activity_form_title"><?php esc_html_e( 'Add Activity Event', 'yatravanta-trek-booking-manager' ); ?></h3>
				<span class="yatravanta-modal-close" id="yatravanta_activity_form_close_btn">&times;</span>
			</div>
			<div class="yatravanta-modal-body">
				<div id="yatravanta_activity_form" class="yatravanta-city-form-wrapper">
					<input type="hidden" name="activity_id" id="yatravanta_form_activity_id" value="" />
					
					<div class="yatravanta-form-grid-2">
						<div class="yatravanta-form-row">
							<label for="yatravanta_form_activity_time"><?php esc_html_e( 'Time', 'yatravanta-trek-booking-manager' ); ?></label>
							<input type="text" id="yatravanta_form_activity_time" name="item_time" class="yatravanta-timepicker" placeholder="<?php esc_attr_e( 'e.g. 08:30 AM', 'yatravanta-trek-booking-manager' ); ?>" />
						</div>
						<div class="yatravanta-form-row">
							<label for="yatravanta_form_activity_icon"><?php esc_html_e( 'Event Icon', 'yatravanta-trek-booking-manager' ); ?></label>
							<select id="yatravanta_form_activity_icon" name="icon">
								<option value="" disabled selected><?php esc_html_e( '-- Select Icon --', 'yatravanta-trek-booking-manager' ); ?></option>
								<option value="dashicons-palmtree">🌴 <?php esc_html_e( 'Trek / Outdoors', 'yatravanta-trek-booking-manager' ); ?></option>
								<option value="dashicons-car">🚗 <?php esc_html_e( 'Transport / Travel', 'yatravanta-trek-booking-manager' ); ?></option>
								<option value="dashicons-food">🍴 <?php esc_html_e( 'Meals / Dining', 'yatravanta-trek-booking-manager' ); ?></option>
								<option value="dashicons-location">📍 <?php esc_html_e( 'Sightseeing', 'yatravanta-trek-booking-manager' ); ?></option>
								<option value="dashicons-admin-home">🏕️ <?php esc_html_e( 'Hotel / Camp Stay', 'yatravanta-trek-booking-manager' ); ?></option>
								<option value="dashicons-clock">⏰ <?php esc_html_e( 'Reporting', 'yatravanta-trek-booking-manager' ); ?></option>
								<option value="dashicons-marker">🚩 <?php esc_html_e( 'Summit / Milestone', 'yatravanta-trek-booking-manager' ); ?></option>
							</select>
						</div>
					</div>

					<div class="yatravanta-form-row">
						<label for="yatravanta_form_activity_title"><?php esc_html_e( 'Activity Title', 'yatravanta-trek-booking-manager' ); ?> <span style="color: #d63638;">*</span></label>
						<input type="text" id="yatravanta_form_activity_title" name="title" placeholder="<?php esc_attr_e( 'e.g. Hot Breakfast served at Camp', 'yatravanta-trek-booking-manager' ); ?>" />
					</div>

					<div class="yatravanta-form-row">
						<label for="yatravanta_form_activity_image"><?php esc_html_e( 'Event Image', 'yatravanta-trek-booking-manager' ); ?></label>
						<div style="display:flex; gap:10px; align-items:center;">
							<input type="text" id="yatravanta_form_activity_image" name="image_url" placeholder="<?php esc_attr_e( 'https://...', 'yatravanta-trek-booking-manager' ); ?>" class="regular-text" style="flex-grow:1;" />
							<button type="button" class="button" id="yatravanta_select_activity_image_btn"><?php esc_html_e( 'Select', 'yatravanta-trek-booking-manager' ); ?></button>
						</div>
						<div id="yatravanta_activity_image_preview" style="margin-top:10px;"></div>
					</div>

					<div class="yatravanta-form-row">
						<label for="yatravanta_form_activity_desc"><?php esc_html_e( 'Activity Details', 'yatravanta-trek-booking-manager' ); ?></label>
						<textarea id="yatravanta_form_activity_desc" name="description" rows="4" placeholder="<?php esc_attr_e( 'Detailed outline of what trekkers do during this activity slot...', 'yatravanta-trek-booking-manager' ); ?>"></textarea>
					</div>
				</div>
			</div>
			<div class="yatravanta-modal-overlay-footer" style="display: flex; justify-content: flex-end; gap: 10px; padding: 15px 20px; background: #f0f0f1; border-top: 1px solid #ccd0d4;">
				<button type="button" class="button" id="yatravanta_activity_form_cancel_btn"><?php esc_html_e( 'Cancel', 'yatravanta-trek-booking-manager' ); ?></button>
				<button type="button" class="button button-primary" id="yatravanta_activity_form_save_btn"><?php esc_html_e( 'Save Activity', 'yatravanta-trek-booking-manager' ); ?></button>
			</div>
		</div>
	</div>
	<!-- Pricing Manager Modal -->
	<div class="yatravanta-modal-overlay" id="yatravanta_pricing_modal" style="display: none;">
		<div class="yatravanta-modal-box" style="max-width: 750px; width: 90%; height: 85vh;">
			<div class="yatravanta-modal-header">
				<h3><?php esc_html_e( 'Configure Pricing Rules & Add-ons', 'yatravanta-trek-booking-manager' ); ?> - <span id="yatravanta_pricing_modal_city_title"></span></h3>
				<span class="yatravanta-modal-close" id="yatravanta_pricing_modal_close_btn">&times;</span>
			</div>
			<div class="yatravanta-modal-body" style="overflow-y: auto;">
				<div id="yatravanta_pricing_form" class="yatravanta-city-form-wrapper">
					<input type="hidden" name="pricing_city_id" id="yatravanta_pricing_city_id" value="" />
					
					<!-- Base Pricing Section -->
					<div style="font-weight: bold; font-size: 14px; border-bottom: 1px solid #eee; padding-bottom: 8px; margin-bottom: 15px; color: #23282d;">
						<?php esc_html_e( 'Base Pricing', 'yatravanta-trek-booking-manager' ); ?>
					</div>
					<div class="yatravanta-form-grid-3" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-bottom: 20px;">
						<div class="yatravanta-form-row" style="margin-bottom:0;">
							<label for="yatravanta_pricing_adult_price"><?php esc_html_e( 'Adult Price', 'yatravanta-trek-booking-manager' ); ?> <span style="color: #d63638;">*</span></label>
							<input type="number" step="1" id="yatravanta_pricing_adult_price" name="adult_price" value="0" min="0" />
						</div>
						<div class="yatravanta-form-row" style="margin-bottom:0;">
							<label for="yatravanta_pricing_child_price"><?php esc_html_e( 'Child Price', 'yatravanta-trek-booking-manager' ); ?></label>
							<input type="number" step="1" id="yatravanta_pricing_child_price" name="child_price" value="0" min="0" />
						</div>
						<div class="yatravanta-form-row" style="margin-bottom:0;">
							<label for="yatravanta_pricing_offer_price"><?php esc_html_e( 'Offer Price', 'yatravanta-trek-booking-manager' ); ?></label>
							<input type="number" step="1" id="yatravanta_pricing_offer_price" name="offer_price" value="0" min="0" />
						</div>
					</div>

					<!-- Group Discounts Repeater -->
					<div style="display:flex; justify-content:space-between; align-items:center; font-weight: bold; font-size: 14px; border-bottom: 1px solid #eee; padding-bottom: 8px; margin-bottom: 15px; color: #23282d; margin-top:20px;">
						<span><?php esc_html_e( 'Group Discounts', 'yatravanta-trek-booking-manager' ); ?></span>
						<button type="button" class="button button-small" id="yatravanta_add_group_discount_rule_btn">+ <?php esc_html_e( 'Add Discount Rule', 'yatravanta-trek-booking-manager' ); ?></button>
					</div>
					<div id="yatravanta_group_discount_rules_list" style="margin-bottom: 20px;">
						<!-- Group rules list -->
					</div>

					<!-- Extra Charges Repeater -->
					<div style="display:flex; justify-content:space-between; align-items:center; font-weight: bold; font-size: 14px; border-bottom: 1px solid #eee; padding-bottom: 8px; margin-bottom: 15px; color: #23282d; margin-top:20px;">
						<span><?php esc_html_e( 'Mandatory Extra Charges', 'yatravanta-trek-booking-manager' ); ?></span>
						<button type="button" class="button button-small" id="yatravanta_add_extra_charge_btn">+ <?php esc_html_e( 'Add Fee/Charge', 'yatravanta-trek-booking-manager' ); ?></button>
					</div>
					<div id="yatravanta_extra_charges_list" style="margin-bottom: 20px;">
						<!-- Extra fees list -->
					</div>

					<!-- Optional Add-ons Repeater -->
					<div style="display:flex; justify-content:space-between; align-items:center; font-weight: bold; font-size: 14px; border-bottom: 1px solid #eee; padding-bottom: 8px; margin-bottom: 15px; color: #23282d; margin-top:20px;">
						<span><?php esc_html_e( 'Optional Add-ons', 'yatravanta-trek-booking-manager' ); ?></span>
						<button type="button" class="button button-small" id="yatravanta_add_optional_addon_btn">+ <?php esc_html_e( 'Add Optional Extra', 'yatravanta-trek-booking-manager' ); ?></button>
					</div>
					<div id="yatravanta_optional_addons_list" style="margin-bottom: 20px;">
						<!-- Add-ons list -->
					</div>

					<!-- Transportation Options Repeater -->
					<div style="display:flex; justify-content:space-between; align-items:center; font-weight: bold; font-size: 14px; border-bottom: 1px solid #eee; padding-bottom: 8px; margin-bottom: 15px; color: #23282d; margin-top:20px;">
						<span><?php esc_html_e( 'Transportation Options', 'yatravanta-trek-booking-manager' ); ?></span>
						<button type="button" class="button button-small" id="yatravanta_add_transport_option_btn">+ <?php esc_html_e( 'Add Transportation Option', 'yatravanta-trek-booking-manager' ); ?></button>
					</div>
					<p class="description" style="margin: -10px 0 12px;"><?php esc_html_e( 'Shown to visitors as a single choice right after they pick this city. The additional price is added per person on top of the base fare.', 'yatravanta-trek-booking-manager' ); ?></p>
					<div id="yatravanta_transport_options_list" style="margin-bottom: 20px;">
						<!-- Transport options list -->
					</div>

				</div>
			</div>
			<div class="yatravanta-modal-overlay-footer" style="display: flex; justify-content: flex-end; gap: 10px; padding: 15px 20px; background: #f0f0f1; border-top: 1px solid #ccd0d4;">
				<button type="button" class="button" id="yatravanta_pricing_modal_cancel_btn"><?php esc_html_e( 'Cancel', 'yatravanta-trek-booking-manager' ); ?></button>
				<button type="button" class="button button-primary" id="yatravanta_pricing_modal_save_btn"><?php esc_html_e( 'Save Pricing Setup', 'yatravanta-trek-booking-manager' ); ?></button>
			</div>
		</div>
	</div>

	<!-- Pickup Points Manager Modal -->
	<div class="yatravanta-modal-overlay" id="yatravanta_pickups_modal" style="display: none;">
		<div class="yatravanta-modal-box" style="max-width: 800px; width: 90%; height: 85vh;">
			<div class="yatravanta-modal-header">
				<h3><?php esc_html_e( 'Manage Pickup Points', 'yatravanta-trek-booking-manager' ); ?> - <span id="yatravanta_pickups_modal_city_title"></span></h3>
				<span class="yatravanta-modal-close" id="yatravanta_pickups_modal_close_btn">&times;</span>
			</div>
			<div class="yatravanta-modal-body" style="overflow-y: auto;">
				
				<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
					<p class="description" style="margin:0;"><?php esc_html_e( 'Configure pickup points for this city. You can drag and drop to reorder them.', 'yatravanta-trek-booking-manager' ); ?></p>
					<button type="button" class="button button-primary" id="yatravanta_add_pickup_btn">+ <?php esc_html_e( 'Add Pickup Point', 'yatravanta-trek-booking-manager' ); ?></button>
				</div>
				
				<div class="yatravanta-loading-spinner" id="yatravanta_pickups_loading" style="display:none;">
					<span class="spinner is-active"></span> <?php esc_html_e( 'Loading pickup points...', 'yatravanta-trek-booking-manager' ); ?>
				</div>

				<div class="yatravanta-table-scroll">
					<table class="widefat fixed striped yatravanta-admin-table" id="yatravanta_pickups_table" style="margin-top:10px;">
					<thead>
						<tr>
							<th style="width: 40px;"></th>
							<th style="width: 200px;"><?php esc_html_e( 'Location Name', 'yatravanta-trek-booking-manager' ); ?></th>
							<th style="width: 120px;"><?php esc_html_e( 'Time', 'yatravanta-trek-booking-manager' ); ?></th>
							<th><?php esc_html_e( 'Map URL', 'yatravanta-trek-booking-manager' ); ?></th>
							<th style="width: 120px; text-align: right;"><?php esc_html_e( 'Actions', 'yatravanta-trek-booking-manager' ); ?></th>
						</tr>
					</thead>
					<tbody id="yatravanta_pickups_tbody">
						<!-- Loaded via AJAX -->
					</tbody>
				</table>
				</div>

				<!-- Pickup Point Form (Hidden by default) -->
				<div id="yatravanta_pickup_form_container" style="display: none; margin-top: 20px; padding: 15px; border: 1px solid #ccd0d4; background: #fff;">
					<h4 style="margin-top: 0;" id="yatravanta_pickup_form_title"><?php esc_html_e( 'Add Pickup Point', 'yatravanta-trek-booking-manager' ); ?></h4>
					<input type="hidden" id="yatravanta_pickup_id" name="pickup_id" value="" />
					
					<div class="yatravanta-form-grid-2">
						<div class="yatravanta-form-row">
							<label for="yatravanta_pickup_location_name"><?php esc_html_e( 'Location Name', 'yatravanta-trek-booking-manager' ); ?> <span style="color: #d63638;">*</span></label>
							<input type="text" id="yatravanta_pickup_location_name" name="location_name" placeholder="<?php esc_attr_e( 'e.g. Dehradun Railway Station', 'yatravanta-trek-booking-manager' ); ?>" />
						</div>
						<div class="yatravanta-form-row">
							<label for="yatravanta_pickup_time"><?php esc_html_e( 'Pickup Time', 'yatravanta-trek-booking-manager' ); ?></label>
							<input type="text" id="yatravanta_pickup_time" name="pickup_time" class="yatravanta-timepicker" placeholder="<?php esc_attr_e( 'e.g. 06:30 AM', 'yatravanta-trek-booking-manager' ); ?>" />
						</div>
					</div>
					
					<div class="yatravanta-form-row">
						<label for="yatravanta_pickup_map_url"><?php esc_html_e( 'Google Maps URL', 'yatravanta-trek-booking-manager' ); ?></label>
						<input type="url" id="yatravanta_pickup_map_url" name="google_maps_url" placeholder="<?php esc_attr_e( 'https://maps.google.com/...', 'yatravanta-trek-booking-manager' ); ?>" class="regular-text" style="width: 100%;" />
					</div>
					
					<div class="yatravanta-form-row">
						<label for="yatravanta_pickup_instructions"><?php esc_html_e( 'Special Instructions', 'yatravanta-trek-booking-manager' ); ?></label>
						<textarea id="yatravanta_pickup_instructions" name="instructions" rows="2" placeholder="<?php esc_attr_e( 'e.g. Wait near Gate 1...', 'yatravanta-trek-booking-manager' ); ?>"></textarea>
					</div>
					
					<div style="margin-top: 15px; display: flex; justify-content: flex-end; gap: 10px;">
						<button type="button" class="button" id="yatravanta_pickup_form_cancel_btn"><?php esc_html_e( 'Cancel', 'yatravanta-trek-booking-manager' ); ?></button>
						<button type="button" class="button button-primary" id="yatravanta_pickup_form_save_btn"><?php esc_html_e( 'Save Pickup Point', 'yatravanta-trek-booking-manager' ); ?></button>
					</div>
				</div>

			</div>
		</div>
	</div>

</div>
