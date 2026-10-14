<?php
/**
 * Settings admin page view template
 *
 * @package    YatraVanta
 * @subpackage YatraVanta/Admin/Views
 * @author     Nilesh Vastarpara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<?php
	$yatravanta_ie_notice = get_transient( 'yatravanta_import_export_notice_' . get_current_user_id() );
	if ( $yatravanta_ie_notice ) {
		delete_transient( 'yatravanta_import_export_notice_' . get_current_user_id() );
		printf(
			'<div class="notice notice-%1$s inline is-dismissible"><p>%2$s</p></div>',
			esc_attr( 'success' === $yatravanta_ie_notice[0] ? 'success' : 'error' ),
			esc_html( $yatravanta_ie_notice[1] )
		);
	}
	?>

	<h2 class="nav-tab-wrapper">
		<a href="#" class="nav-tab nav-tab-active" data-yatravanta-tab="general"><?php esc_html_e( 'General', 'yatravanta-trek-booking-manager' ); ?></a>
		<a href="#" class="nav-tab" data-yatravanta-tab="currency"><?php esc_html_e( 'Currency', 'yatravanta-trek-booking-manager' ); ?></a>
		<a href="#" class="nav-tab" data-yatravanta-tab="payment"><?php esc_html_e( 'Payment', 'yatravanta-trek-booking-manager' ); ?></a>
		<a href="#" class="nav-tab" data-yatravanta-tab="email"><?php esc_html_e( 'Email', 'yatravanta-trek-booking-manager' ); ?></a>
		<a href="#" class="nav-tab" data-yatravanta-tab="import-export"><?php esc_html_e( 'Import / Export', 'yatravanta-trek-booking-manager' ); ?></a>
	</h2>

	<form method="post" action="options.php" class="yatravanta-settings-form-card">
		<?php
		settings_fields( 'yatravanta_settings_group' );
		do_settings_sections( 'yatravanta_settings_group' );
		?>

		<div id="yatravanta-settings-tab-general" class="yatravanta-settings-tab">
			<h2><?php esc_html_e( 'Appearance Settings', 'yatravanta-trek-booking-manager' ); ?></h2>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row">
							<?php esc_html_e( 'Site Logo', 'yatravanta-trek-booking-manager' ); ?>
						</th>
						<td>
							<?php
							$yatravanta_logo_url = \YatraVanta\Includes\Plugin::get_site_logo_url();
							// The preview ignores the checkbox, so it shows which logo would be used.
							$yatravanta_theme_logo_id = (int) get_theme_mod( 'custom_logo' );
							if ( ! $yatravanta_theme_logo_id ) {
								$yatravanta_theme_logo_id = (int) get_option( 'site_logo' );
							}
							$yatravanta_preview_url = $yatravanta_theme_logo_id ? wp_get_attachment_image_url( $yatravanta_theme_logo_id, 'medium' ) : '';
							?>
							<fieldset>
								<legend class="screen-reader-text"><span><?php esc_html_e( 'Site Logo', 'yatravanta-trek-booking-manager' ); ?></span></legend>
								<label for="yatravanta_use_site_logo">
									<input name="yatravanta_use_site_logo" type="checkbox" id="yatravanta_use_site_logo" value="1" <?php checked( '1', get_option( 'yatravanta_use_site_logo', '1' ) ); ?> />
									<?php esc_html_e( 'Use the site logo in the header of booking confirmation and status update emails.', 'yatravanta-trek-booking-manager' ); ?>
								</label>
							</fieldset>
							<?php if ( $yatravanta_preview_url ) : ?>
								<p class="yatravanta-site-logo-preview"><img src="<?php echo esc_url( $yatravanta_preview_url ); ?>" class="yatravanta-logo-preview-img" alt="" /></p>
								<p class="description"><?php esc_html_e( 'This is your WordPress site logo.', 'yatravanta-trek-booking-manager' ); ?></p>
							<?php else : ?>
								<p class="description">
									<?php esc_html_e( 'No site logo is set, so emails show your site name instead.', 'yatravanta-trek-booking-manager' ); ?>
									<?php if ( current_theme_supports( 'custom-logo' ) ) : ?>
										<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=title_tagline' ) ); ?>"><?php esc_html_e( 'Set a site logo', 'yatravanta-trek-booking-manager' ); ?></a>
									<?php endif; ?>
								</p>
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<?php esc_html_e( 'SEO Options', 'yatravanta-trek-booking-manager' ); ?>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span><?php esc_html_e( 'SEO Options', 'yatravanta-trek-booking-manager' ); ?></span></legend>
								<label for="yatravanta_enable_schema">
									<input name="yatravanta_enable_schema" type="checkbox" id="yatravanta_enable_schema" value="1" <?php checked( '1', get_option( 'yatravanta_enable_schema', '1' ) ); ?> />
									<?php esc_html_e( 'Generate Schema.org structured JSON-LD data for Trek posts.', 'yatravanta-trek-booking-manager' ); ?>
								</label>
							</fieldset>
						</td>
					</tr>
				</tbody>
			</table>

			<h2><?php esc_html_e( 'Color', 'yatravanta-trek-booking-manager' ); ?></h2>
			<p class="description"><?php esc_html_e( 'Leave a color empty to use your theme\'s own colors (Primary and Secondary).', 'yatravanta-trek-booking-manager' ); ?></p>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row">
							<label for="yatravanta_primary_color"><?php esc_html_e( 'Primary', 'yatravanta-trek-booking-manager' ); ?></label>
						</th>
						<td>
							<input type="text" name="yatravanta_primary_color" id="yatravanta_primary_color" class="yatravanta-color-picker" value="<?php echo esc_attr( get_option( 'yatravanta_primary_color', '' ) ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="yatravanta_secondary_color"><?php esc_html_e( 'Secondary', 'yatravanta-trek-booking-manager' ); ?></label>
						</th>
						<td>
							<input type="text" name="yatravanta_secondary_color" id="yatravanta_secondary_color" class="yatravanta-color-picker" value="<?php echo esc_attr( get_option( 'yatravanta_secondary_color', '' ) ); ?>" />
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div id="yatravanta-settings-tab-currency" class="yatravanta-settings-tab" style="display:none;">
			<h2><?php esc_html_e( 'Currency options', 'yatravanta-trek-booking-manager' ); ?></h2>
			<p><?php esc_html_e( 'The following options affect how prices are displayed on the frontend, in booking emails and in the admin bookings screens.', 'yatravanta-trek-booking-manager' ); ?></p>
			<?php
			$yatravanta_current_currency = get_option( 'yatravanta_currency_symbol', '$' );
			$yatravanta_currencies       = \YatraVanta\Admin\Controllers\AdminController::get_currencies();
			$yatravanta_positions        = \YatraVanta\Admin\Controllers\AdminController::get_currency_positions();
			$yatravanta_price_fmt        = \YatraVanta\Admin\Controllers\AdminController::get_price_format();
			?>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row">
							<label for="yatravanta_currency_symbol"><?php esc_html_e( 'Currency', 'yatravanta-trek-booking-manager' ); ?></label>
						</th>
						<td>
							<select name="yatravanta_currency_symbol" id="yatravanta_currency_symbol">
								<?php foreach ( $yatravanta_currencies as $yatravanta_symbol => $yatravanta_label ) : ?>
									<option value="<?php echo esc_attr( $yatravanta_symbol ); ?>" <?php selected( $yatravanta_current_currency, $yatravanta_symbol ); ?>>
										<?php echo esc_html( $yatravanta_label ); ?>
									</option>
								<?php endforeach; ?>
							</select>
							<p class="description"><?php esc_html_e( 'The currency symbol shown alongside trek prices.', 'yatravanta-trek-booking-manager' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="yatravanta_currency_position"><?php esc_html_e( 'Currency position', 'yatravanta-trek-booking-manager' ); ?></label>
						</th>
						<td>
							<select name="yatravanta_currency_position" id="yatravanta_currency_position">
								<?php foreach ( $yatravanta_positions as $yatravanta_pos_key => $yatravanta_pos_label ) : ?>
									<option value="<?php echo esc_attr( $yatravanta_pos_key ); ?>" <?php selected( $yatravanta_price_fmt['position'], $yatravanta_pos_key ); ?>>
										<?php echo esc_html( $yatravanta_pos_label ); ?>
									</option>
								<?php endforeach; ?>
							</select>
							<p class="description"><?php esc_html_e( 'Where the currency symbol appears relative to the amount.', 'yatravanta-trek-booking-manager' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="yatravanta_thousand_separator"><?php esc_html_e( 'Thousand separator', 'yatravanta-trek-booking-manager' ); ?></label>
						</th>
						<td>
							<input name="yatravanta_thousand_separator" type="text" id="yatravanta_thousand_separator" value="<?php echo esc_attr( $yatravanta_price_fmt['thousand'] ); ?>" maxlength="1" class="small-text" />
							<p class="description"><?php esc_html_e( 'Separates thousands, e.g. 11,499. Leave empty for none.', 'yatravanta-trek-booking-manager' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="yatravanta_decimal_separator"><?php esc_html_e( 'Decimal separator', 'yatravanta-trek-booking-manager' ); ?></label>
						</th>
						<td>
							<input name="yatravanta_decimal_separator" type="text" id="yatravanta_decimal_separator" value="<?php echo esc_attr( $yatravanta_price_fmt['decimal'] ); ?>" maxlength="1" class="small-text" />
							<p class="description"><?php esc_html_e( 'Separates the decimal part, e.g. 11,499.00. Cannot be empty.', 'yatravanta-trek-booking-manager' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="yatravanta_price_decimals"><?php esc_html_e( 'Number of decimals', 'yatravanta-trek-booking-manager' ); ?></label>
						</th>
						<td>
							<input name="yatravanta_price_decimals" type="number" id="yatravanta_price_decimals" value="<?php echo esc_attr( $yatravanta_price_fmt['decimals'] ); ?>" min="0" max="4" step="1" class="small-text" />
							<p class="description"><?php esc_html_e( 'How many digits to show after the decimal point (0 - 4).', 'yatravanta-trek-booking-manager' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Preview', 'yatravanta-trek-booking-manager' ); ?></th>
						<td>
							<strong id="yatravanta_currency_preview"><?php echo esc_html( \YatraVanta\Admin\Controllers\AdminController::format_price( 1234567.891 ) ); ?></strong>
							<p class="description"><?php esc_html_e( 'Updates as you change the options above. Save to apply.', 'yatravanta-trek-booking-manager' ); ?></p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div id="yatravanta-settings-tab-payment" class="yatravanta-settings-tab" style="display:none;">
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row">
							<?php esc_html_e( 'Payment Method', 'yatravanta-trek-booking-manager' ); ?>
						</th>
						<td>
							<?php $yatravanta_payment_method = get_option( 'yatravanta_payment_method', 'cash' ); ?>
							<fieldset>
								<legend class="screen-reader-text"><span><?php esc_html_e( 'Payment Method', 'yatravanta-trek-booking-manager' ); ?></span></legend>
								<label for="yatravanta_payment_method_cash" style="margin-right:20px !important;">
									<input type="radio" name="yatravanta_payment_method" id="yatravanta_payment_method_cash" value="cash" <?php checked( 'cash', $yatravanta_payment_method ); ?> />
									<?php esc_html_e( 'Cash', 'yatravanta-trek-booking-manager' ); ?>
								</label>
								<label for="yatravanta_payment_method_upi">
									<input type="radio" name="yatravanta_payment_method" id="yatravanta_payment_method_upi" value="upi" <?php checked( 'upi', $yatravanta_payment_method ); ?> />
									<?php esc_html_e( 'UPI', 'yatravanta-trek-booking-manager' ); ?>
								</label>
							</fieldset>
						</td>
					</tr>
				</tbody>
			</table>

			<table class="form-table" role="presentation" id="yatravanta_upi_fields" style="display:none;">
				<tbody>
					<tr>
						<th scope="row">
							<label for="yatravanta_upi_id"><?php esc_html_e( 'UPI ID', 'yatravanta-trek-booking-manager' ); ?></label>
						</th>
						<td>
							<input type="text" name="yatravanta_upi_id" id="yatravanta_upi_id" class="regular-text" value="<?php echo esc_attr( get_option( 'yatravanta_upi_id', '' ) ); ?>" placeholder="<?php esc_attr_e( 'e.g. yourname@upi', 'yatravanta-trek-booking-manager' ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="yatravanta_upi_qr_code"><?php esc_html_e( 'UPI QR Code', 'yatravanta-trek-booking-manager' ); ?></label>
						</th>
						<td>
							<input type="hidden" name="yatravanta_upi_qr_code" id="yatravanta_upi_qr_code" value="<?php echo esc_attr( get_option( 'yatravanta_upi_qr_code', '' ) ); ?>" />
							<div id="yatravanta_upi_qr_code_preview">
								<?php $yatravanta_qr_code_url = get_option( 'yatravanta_upi_qr_code', '' ); ?>
								<?php if ( $yatravanta_qr_code_url ) : ?>
									<img src="<?php echo esc_url( $yatravanta_qr_code_url ); ?>" class="yatravanta-qr-code-preview-img" alt="<?php esc_attr_e( 'UPI QR code preview', 'yatravanta-trek-booking-manager' ); ?>" />
								<?php endif; ?>
							</div>
							<p>
								<button type="button" class="button" id="yatravanta_upi_qr_code_select_btn"><?php esc_html_e( 'Select QR Code Image', 'yatravanta-trek-booking-manager' ); ?></button>
								<button type="button" class="button" id="yatravanta_upi_qr_code_remove_btn" <?php echo $yatravanta_qr_code_url ? '' : 'style="display:none;"'; ?>><?php esc_html_e( 'Remove', 'yatravanta-trek-booking-manager' ); ?></button>
							</p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div id="yatravanta-settings-tab-email" class="yatravanta-settings-tab" style="display:none;">
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row">
							<label for="yatravanta_from_name"><?php esc_html_e( 'From Name', 'yatravanta-trek-booking-manager' ); ?></label>
						</th>
						<td>
							<input name="yatravanta_from_name" type="text" id="yatravanta_from_name" value="<?php echo esc_attr( get_option( 'yatravanta_from_name', get_bloginfo( 'name' ) ) ); ?>" class="regular-text" required />
							<p class="description"><?php esc_html_e( 'The name that emails are sent from.', 'yatravanta-trek-booking-manager' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="yatravanta_booking_email"><?php esc_html_e( 'Notification Email', 'yatravanta-trek-booking-manager' ); ?></label>
						</th>
						<td>
							<input name="yatravanta_booking_email" type="email" id="yatravanta_booking_email" value="<?php echo esc_attr( get_option( 'yatravanta_booking_email', get_bloginfo( 'admin_email' ) ) ); ?>" class="regular-text" required />
							<p class="description"><?php esc_html_e( 'Email address that will receive reservation alerts, and used as the sender address on booking emails.', 'yatravanta-trek-booking-manager' ); ?></p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<?php submit_button(); ?>
	</form>

	<?php // Outside the settings form: these post to admin-post.php, and forms cannot be nested. ?>
	<div id="yatravanta-settings-tab-import-export" class="yatravanta-settings-tab yatravanta-settings-form-card" style="display:none;">
		<h2><?php esc_html_e( 'Export Treks', 'yatravanta-trek-booking-manager' ); ?></h2>
		<p><?php esc_html_e( 'Download all treks as a JSON file, including specifications, departure cities, itineraries, pickup points, dates, seats and pricing. Bookings are not included.', 'yatravanta-trek-booking-manager' ); ?></p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="yatravanta_export_treks" />
			<?php wp_nonce_field( 'yatravanta_export_treks' ); ?>
			<?php submit_button( __( 'Export Treks', 'yatravanta-trek-booking-manager' ), 'primary', 'submit', false ); ?>
		</form>

		<hr />

		<h2><?php esc_html_e( 'Import Treks', 'yatravanta-trek-booking-manager' ); ?></h2>
		<p><?php esc_html_e( 'Choose a JSON file exported from YatraVanta Trek Booking Manager. Every trek in the file is created as a new trek; existing treks are never overwritten. Images are downloaded into the media library.', 'yatravanta-trek-booking-manager' ); ?></p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
			<input type="hidden" name="action" value="yatravanta_import_treks" />
			<?php wp_nonce_field( 'yatravanta_import_treks' ); ?>
			<p><input type="file" name="yatravanta_import_file" accept=".json,application/json" aria-label="<?php esc_attr_e( 'Export file to import', 'yatravanta-trek-booking-manager' ); ?>" required /></p>
			<?php submit_button( __( 'Import Treks', 'yatravanta-trek-booking-manager' ), 'primary', 'submit', false ); ?>
		</form>
	</div>
</div>
