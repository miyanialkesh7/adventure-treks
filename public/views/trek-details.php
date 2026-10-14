<?php
/**
 * View template for rendering Trek details frontend UI.
 *
 * @package    YatraVanta
 * @subpackage YatraVanta/Public/Views
 * @author     Nilesh Vastarpara
 */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="yatravanta-trek-details-container">
	
	<!-- 1. Specifications Icon Grid -->
	<div class="yatravanta-specs-header-grid">
		
		<?php if ( ! empty( $trek['difficulty'] ) ) : ?>
			<div class="yatravanta-spec-item">
				<span class="dashicons dashicons-performance"></span>
				<div class="yatravanta-spec-info">
					<span class="yatravanta-spec-label"><?php esc_html_e( 'Difficulty', 'yatravanta-trek-booking-manager' ); ?></span>
					<strong class="yatravanta-spec-value"><?php echo esc_html( $trek['difficulty'] ); ?></strong>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $trek['duration'] ) ) : ?>
			<div class="yatravanta-spec-item">
				<span class="dashicons dashicons-clock"></span>
				<div class="yatravanta-spec-info">
					<span class="yatravanta-spec-label"><?php esc_html_e( 'Duration', 'yatravanta-trek-booking-manager' ); ?></span>
					<strong class="yatravanta-spec-value"><?php echo esc_html( $trek['duration'] ); ?></strong>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $trek['altitude'] ) ) : ?>
			<div class="yatravanta-spec-item">
				<span class="dashicons dashicons-arrow-up-alt"></span>
				<div class="yatravanta-spec-info">
					<span class="yatravanta-spec-label"><?php esc_html_e( 'Max Altitude', 'yatravanta-trek-booking-manager' ); ?></span>
					<strong class="yatravanta-spec-value"><?php echo esc_html( $trek['altitude'] ); ?></strong>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $trek['region'] ) ) : ?>
			<div class="yatravanta-spec-item">
				<span class="dashicons dashicons-location"></span>
				<div class="yatravanta-spec-info">
					<span class="yatravanta-spec-label"><?php esc_html_e( 'Region', 'yatravanta-trek-booking-manager' ); ?></span>
					<strong class="yatravanta-spec-value"><?php echo esc_html( $trek['region'] ); ?></strong>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $trek['season'] ) ) : ?>
			<div class="yatravanta-spec-item">
				<span class="dashicons dashicons-calendar-alt"></span>
				<div class="yatravanta-spec-info">
					<span class="yatravanta-spec-label"><?php esc_html_e( 'Best Season', 'yatravanta-trek-booking-manager' ); ?></span>
					<strong class="yatravanta-spec-value"><?php echo esc_html( $trek['season'] ); ?></strong>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $trek['distance'] ) ) : ?>
			<div class="yatravanta-spec-item">
				<span class="dashicons dashicons-location-alt"></span>
				<div class="yatravanta-spec-info">
					<span class="yatravanta-spec-label"><?php esc_html_e( 'Trek Distance', 'yatravanta-trek-booking-manager' ); ?></span>
					<strong class="yatravanta-spec-value"><?php echo esc_html( $trek['distance'] ); ?></strong>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $trek['fitness_level'] ) ) : ?>
			<div class="yatravanta-spec-item">
				<span class="dashicons dashicons-heart"></span>
				<div class="yatravanta-spec-info">
					<span class="yatravanta-spec-label"><?php esc_html_e( 'Fitness Level', 'yatravanta-trek-booking-manager' ); ?></span>
					<strong class="yatravanta-spec-value"><?php echo esc_html( $trek['fitness_level'] ); ?></strong>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $trek['age_limit'] ) ) : ?>
			<div class="yatravanta-spec-item">
				<span class="dashicons dashicons-admin-users"></span>
				<div class="yatravanta-spec-info">
					<span class="yatravanta-spec-label"><?php esc_html_e( 'Age Limit', 'yatravanta-trek-booking-manager' ); ?></span>
					<strong class="yatravanta-spec-value"><?php echo esc_html( $trek['age_limit'] ); ?></strong>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $trek['group_size'] ) ) : ?>
			<div class="yatravanta-spec-item">
				<span class="dashicons dashicons-groups"></span>
				<div class="yatravanta-spec-info">
					<span class="yatravanta-spec-label"><?php esc_html_e( 'Group Size', 'yatravanta-trek-booking-manager' ); ?></span>
					<strong class="yatravanta-spec-value"><?php echo esc_html( $trek['group_size'] ); ?></strong>
				</div>
			</div>
		<?php endif; ?>

	</div>

	<!-- 2. Content Sections in Tab Layout -->
	<div class="yatravanta-details-tabs-wrapper">
		<ul class="yatravanta-details-tabs-nav">
			<li class="active"><a href="#yatravanta-f-tab-itinerary"><?php esc_html_e( 'Itinerary', 'yatravanta-trek-booking-manager' ); ?></a></li>
			<?php if ( ! empty( $trek['highlights'] ) || ! empty( $trek['exclusions'] ) ) : ?>
				<li><a href="#yatravanta-f-tab-inclusions"><?php esc_html_e( 'Inclusions & Exclusions', 'yatravanta-trek-booking-manager' ); ?></a></li>
			<?php endif; ?>
			<?php if ( ! empty( $faq_items ) ) : ?>
				<li><a href="#yatravanta-f-tab-faq"><?php esc_html_e( 'FAQs', 'yatravanta-trek-booking-manager' ); ?></a></li>
			<?php endif; ?>
			<?php if ( ! empty( $trek['things_to_carry'] ) ) : ?>
				<li><a href="#yatravanta-f-tab-carry"><?php esc_html_e( 'Things To Carry', 'yatravanta-trek-booking-manager' ); ?></a></li>
			<?php endif; ?>
		</ul>

		<div class="yatravanta-details-tabs-content">

			<!-- Section: Itinerary -->
			<div id="yatravanta-f-tab-itinerary" class="yatravanta-details-tab-panel active">
				<div class="yatravanta-tab-inner-content">
					<h3 class="yatravanta-tab-headline"><?php esc_html_e( 'Detailed Itinerary', 'yatravanta-trek-booking-manager' ); ?></h3>
					<div id="trek_itinerary_container">
						<?php
						if ( $default_city_id ) {
							$yatravanta_itinerary_shortcode = '[yatravanta_itinerary city_id="' . intval( $default_city_id ) . '"';
							if ( ! empty( $default_departure_date ) ) {
								$yatravanta_itinerary_shortcode .= ' date="' . esc_attr( $default_departure_date ) . '"';
							}
							$yatravanta_itinerary_shortcode .= ']';
							echo do_shortcode( $yatravanta_itinerary_shortcode );
						} else {
							echo '<p style="color:#666; font-style:italic;">' . esc_html__( 'No itinerary found. Please contact the admin for details.', 'yatravanta-trek-booking-manager' ) . '</p>';
						}
						?>
					</div>
				</div>
			</div>

			<!-- Section: Inclusions & Exclusions -->
			<?php if ( ! empty( $trek['highlights'] ) || ! empty( $trek['exclusions'] ) ) : ?>
				<div id="yatravanta-f-tab-inclusions" class="yatravanta-details-tab-panel">
					<div class="yatravanta-tab-inner-content">
						<h3 class="yatravanta-tab-headline"><?php esc_html_e( 'Inclusions & Exclusions', 'yatravanta-trek-booking-manager' ); ?></h3>
						<div class="yatravanta-incl-excl-grid">

							<?php if ( ! empty( $trek['highlights'] ) ) : ?>
								<div class="yatravanta-highlights-list-box">
									<h4 class="yatravanta-incl-excl-heading included"><?php esc_html_e( "What's Included", 'yatravanta-trek-booking-manager' ); ?></h4>
									<ul>
										<?php
										$yatravanta_incl_items = explode( "\n", str_replace( "\r", '', $trek['highlights'] ) );
										foreach ( $yatravanta_incl_items as $yatravanta_incl_item ) {
											$yatravanta_incl_item = trim( $yatravanta_incl_item );
											if ( ! empty( $yatravanta_incl_item ) ) {
												echo '<li><span class="dashicons dashicons-yes-alt"></span> ' . esc_html( $yatravanta_incl_item ) . '</li>';
											}
										}
										?>
									</ul>
								</div>
							<?php endif; ?>

							<?php if ( ! empty( $trek['exclusions'] ) ) : ?>
								<div class="yatravanta-exclusions-list-box">
									<h4 class="yatravanta-incl-excl-heading excluded"><?php esc_html_e( "What's Excluded", 'yatravanta-trek-booking-manager' ); ?></h4>
									<ul>
										<?php
										$yatravanta_excl_items = explode( "\n", str_replace( "\r", '', $trek['exclusions'] ) );
										foreach ( $yatravanta_excl_items as $yatravanta_excl_item ) {
											$yatravanta_excl_item = trim( $yatravanta_excl_item );
											if ( ! empty( $yatravanta_excl_item ) ) {
												echo '<li><span class="dashicons dashicons-dismiss"></span> ' . esc_html( $yatravanta_excl_item ) . '</li>';
											}
										}
										?>
									</ul>
								</div>
							<?php endif; ?>

						</div>
					</div>
				</div>
			<?php endif; ?>

			<!-- Tab: FAQs -->
			<?php if ( ! empty( $faq_items ) ) : ?>
				<div id="yatravanta-f-tab-faq" class="yatravanta-details-tab-panel">
					<div class="yatravanta-tab-inner-content">
						<h3 class="yatravanta-tab-headline"><?php esc_html_e( 'Frequently Asked Questions', 'yatravanta-trek-booking-manager' ); ?></h3>
						<div class="yatravanta-faq-accordion-container">
							<?php foreach ( $faq_items as $idx => $faq ) : ?>
								<div class="yatravanta-accordion-item">
									<button class="yatravanta-accordion-trigger" type="button">
										<?php echo esc_html( $faq['q'] ); ?>
										<span class="dashicons dashicons-arrow-down-alt2"></span>
									</button>
									<div class="yatravanta-accordion-content" style="max-height: 0;">
										<p><?php echo wp_kses_post( $faq['a'] ); ?></p>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<!-- Section: Things to Carry -->
			<?php if ( ! empty( $trek['things_to_carry'] ) ) : ?>
				<div id="yatravanta-f-tab-carry" class="yatravanta-details-tab-panel">
					<div class="yatravanta-tab-inner-content">
						<h3 class="yatravanta-tab-headline"><?php esc_html_e( 'Essential Items & Packing Guidelines', 'yatravanta-trek-booking-manager' ); ?></h3>
						<div class="yatravanta-carry-items-grid">
							<?php
							$carry_items = explode( "\n", str_replace( "\r", '', $trek['things_to_carry'] ) );
							foreach ( $carry_items as $c_item ) {
								$c_item = trim( $c_item );
								if ( ! empty( $c_item ) ) {
									echo '<div class="yatravanta-carry-card">';
									echo '  <span class="dashicons dashicons-saved" style="color:var(--yatravanta-primary-color, #137a7f); font-size:18px; width:18px; height:18px; line-height:1;"></span>';
									echo '  <span>' . esc_html( $c_item ) . '</span>';
									echo '</div>';
								}
							}
							?>
						</div>
					</div>
				</div>
			<?php endif; ?>

		</div>
	</div>

	<?php
	// Only policies that have content are listed; with none, the whole section is hidden.
	$yatravanta_policy_types    = \YatraVanta\Admin\Controllers\TrekMetaBoxController::get_policy_types();
	$yatravanta_active_policies = array();
	foreach ( $yatravanta_policy_types as $yatravanta_policy_key => $yatravanta_policy ) {
		if ( ! empty( $policies[ $yatravanta_policy_key ] ) && '' !== trim( wp_strip_all_tags( $policies[ $yatravanta_policy_key ] ) ) ) {
			$yatravanta_active_policies[ $yatravanta_policy_key ] = $yatravanta_policy;
		}
	}
	?>

	<!-- Quick Links & Policies - always visible below the tabs, not tied to any single tab's content -->
	<?php if ( ! empty( $yatravanta_active_policies ) ) : ?>
		<div class="yatravanta-section-card yatravanta-quicklinks-card">
			<h2><?php esc_html_e( 'Quick Links & Policies', 'yatravanta-trek-booking-manager' ); ?></h2>
			<div class="yatravanta-quicklinks-row">
				<?php foreach ( $yatravanta_active_policies as $yatravanta_policy_key => $yatravanta_policy ) : ?>
					<button type="button" class="yatravanta-quicklink-btn" data-popup-target="yatravanta-f-popup-<?php echo esc_attr( $yatravanta_policy_key ); ?>">
						<span class="yatravanta-quicklink-icon gray"><span class="dashicons <?php echo esc_attr( $yatravanta_policy['icon'] ); ?>"></span></span>
						<?php echo esc_html( $yatravanta_policy['label'] ); ?>
					</button>
				<?php endforeach; ?>
			</div>
		</div>

		<!-- Policy popups -->
		<?php foreach ( $yatravanta_active_policies as $yatravanta_policy_key => $yatravanta_policy ) : ?>
			<div class="yatravanta-policy-modal" id="yatravanta-f-popup-<?php echo esc_attr( $yatravanta_policy_key ); ?>">
				<div class="yatravanta-policy-modal-overlay" data-popup-close></div>
				<div class="yatravanta-policy-modal-box">
					<div class="yatravanta-policy-modal-header">
						<h3><?php echo esc_html( $yatravanta_policy['label'] ); ?></h3>
						<span class="yatravanta-policy-modal-close" data-popup-close>&times;</span>
					</div>
					<div class="yatravanta-policy-modal-body">
						<?php echo wp_kses_post( wpautop( $policies[ $yatravanta_policy_key ] ) ); ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>
