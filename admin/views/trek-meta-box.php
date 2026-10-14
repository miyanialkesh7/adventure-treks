<?php
/**
 * Trek Meta Box view template
 *
 * @package    YatraVanta
 * @subpackage YatraVanta/Admin/Views
 * @author     Nilesh Vastarpara
 */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="yatravanta-meta-tabs-wrapper">
	<!-- Tab Navigation -->
	<nav class="nav-tab-wrapper yatravanta-meta-tabs-nav">
		<a href="#yatravanta-tab-general" class="nav-tab nav-tab-active"><?php esc_html_e( 'General Info', 'yatravanta-trek-booking-manager' ); ?></a>
		<a href="#yatravanta-tab-gallery" class="nav-tab"><?php esc_html_e( 'Gallery', 'yatravanta-trek-booking-manager' ); ?></a>
		<a href="#yatravanta-tab-highlights" class="nav-tab"><?php esc_html_e( 'Inclusions, Exclusions & Packing', 'yatravanta-trek-booking-manager' ); ?></a>
		<a href="#yatravanta-tab-faq" class="nav-tab"><?php esc_html_e( 'FAQ Repeater', 'yatravanta-trek-booking-manager' ); ?></a>
		<a href="#yatravanta-tab-policies" class="nav-tab"><?php esc_html_e( 'Policies', 'yatravanta-trek-booking-manager' ); ?></a>
	</nav>

	<!-- Tab Panels -->
	<div class="yatravanta-meta-tabs-content">
		
		<!-- TAB 1: GENERAL INFO -->
		<div id="yatravanta-tab-general" class="yatravanta-meta-tab-panel active">
			<div class="yatravanta-form-grid-2" style="margin-top: 15px;">
				<div class="yatravanta-form-row">
					<label for="yatravanta_difficulty"><?php esc_html_e( 'Difficulty Level', 'yatravanta-trek-booking-manager' ); ?></label>
					<select name="yatravanta_difficulty" id="yatravanta_difficulty" style="width:100%;">
						<option value="Easy" <?php selected( $trek['difficulty'], 'Easy' ); ?>><?php esc_html_e( 'Easy', 'yatravanta-trek-booking-manager' ); ?></option>
						<option value="Moderate" <?php selected( $trek['difficulty'], 'Moderate' ); ?>><?php esc_html_e( 'Moderate', 'yatravanta-trek-booking-manager' ); ?></option>
						<option value="Difficult" <?php selected( $trek['difficulty'], 'Difficult' ); ?>><?php esc_html_e( 'Difficult', 'yatravanta-trek-booking-manager' ); ?></option>
						<option value="Strenuous" <?php selected( $trek['difficulty'], 'Strenuous' ); ?>><?php esc_html_e( 'Strenuous', 'yatravanta-trek-booking-manager' ); ?></option>
					</select>
				</div>
				<div class="yatravanta-form-row">
					<label for="yatravanta_duration"><?php esc_html_e( 'Duration (e.g. 5 Days / 4 Nights)', 'yatravanta-trek-booking-manager' ); ?></label>
					<input type="text" name="yatravanta_duration" id="yatravanta_duration" data-yatravanta-plain-text="1" value="<?php echo esc_attr( $trek['duration'] ); ?>" style="width:100%;" placeholder="<?php esc_attr_e( 'e.g. 5 Days / 4 Nights', 'yatravanta-trek-booking-manager' ); ?>" />
				</div>
				<div class="yatravanta-form-row">
					<label for="yatravanta_altitude"><?php esc_html_e( 'Max Altitude (e.g. 12,500 ft)', 'yatravanta-trek-booking-manager' ); ?></label>
					<input type="text" name="yatravanta_altitude" id="yatravanta_altitude" data-yatravanta-plain-text="1" value="<?php echo esc_attr( $trek['altitude'] ); ?>" style="width:100%;" placeholder="<?php esc_attr_e( 'e.g. 12,500 ft', 'yatravanta-trek-booking-manager' ); ?>" />
				</div>
				<div class="yatravanta-form-row">
					<label for="yatravanta_region"><?php esc_html_e( 'Region / State', 'yatravanta-trek-booking-manager' ); ?></label>
					<input type="text" name="yatravanta_region" id="yatravanta_region" data-yatravanta-plain-text="1" value="<?php echo esc_attr( $trek['region'] ); ?>" style="width:100%;" placeholder="<?php esc_attr_e( 'e.g. Himachal Pradesh', 'yatravanta-trek-booking-manager' ); ?>" />
				</div>
				<div class="yatravanta-form-row">
					<label for="yatravanta_season"><?php esc_html_e( 'Best Season', 'yatravanta-trek-booking-manager' ); ?></label>
					<input type="text" name="yatravanta_season" id="yatravanta_season" data-yatravanta-plain-text="1" value="<?php echo esc_attr( $trek['season'] ); ?>" style="width:100%;" placeholder="<?php esc_attr_e( 'e.g. May to October', 'yatravanta-trek-booking-manager' ); ?>" />
				</div>
				<div class="yatravanta-form-row">
					<label for="yatravanta_distance"><?php esc_html_e( 'Trekking Distance (e.g. 26 km)', 'yatravanta-trek-booking-manager' ); ?></label>
					<input type="text" name="yatravanta_distance" id="yatravanta_distance" data-yatravanta-plain-text="1" value="<?php echo esc_attr( $trek['distance'] ); ?>" style="width:100%;" placeholder="<?php esc_attr_e( 'e.g. 26 km', 'yatravanta-trek-booking-manager' ); ?>" />
				</div>
				<div class="yatravanta-form-row">
					<label for="yatravanta_fitness_level"><?php esc_html_e( 'Fitness Level Required', 'yatravanta-trek-booking-manager' ); ?></label>
					<?php
					$yatravanta_fitness_options = array( 'Beginner', 'Moderate', 'Good', 'High', 'Very High' );
					// Keep a previously saved free-text value selectable instead of silently dropping it.
					if ( '' !== $trek['fitness_level'] && ! in_array( $trek['fitness_level'], $yatravanta_fitness_options, true ) ) {
						$yatravanta_fitness_options[] = $trek['fitness_level'];
					}
					?>
					<select name="yatravanta_fitness_level" id="yatravanta_fitness_level" style="width:100%;">
						<option value=""><?php esc_html_e( '— Select —', 'yatravanta-trek-booking-manager' ); ?></option>
						<?php foreach ( $yatravanta_fitness_options as $yatravanta_fitness_option ) : ?>
							<option value="<?php echo esc_attr( $yatravanta_fitness_option ); ?>" <?php selected( $trek['fitness_level'], $yatravanta_fitness_option ); ?>><?php echo esc_html( $yatravanta_fitness_option ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="yatravanta-form-row">
					<label for="yatravanta_age_limit"><?php esc_html_e( 'Age Limit (e.g. 10 - 55 Years)', 'yatravanta-trek-booking-manager' ); ?></label>
					<input type="text" name="yatravanta_age_limit" id="yatravanta_age_limit" data-yatravanta-plain-text="plus" value="<?php echo esc_attr( $trek['age_limit'] ); ?>" style="width:100%;" placeholder="<?php esc_attr_e( 'e.g. 10 to 60 Years', 'yatravanta-trek-booking-manager' ); ?>" />
				</div>
				<div class="yatravanta-form-row">
					<label for="yatravanta_adult_age"><?php esc_html_e( 'Adults Age (booking widget, e.g. 12+)', 'yatravanta-trek-booking-manager' ); ?></label>
					<input type="text" name="yatravanta_adult_age" id="yatravanta_adult_age" data-yatravanta-plain-text="plus" value="<?php echo esc_attr( get_post_meta( $post->ID, '_yatravanta_adult_age', true ) ); ?>" style="width:100%;" placeholder="<?php esc_attr_e( '12+', 'yatravanta-trek-booking-manager' ); ?>" />
				</div>
				<div class="yatravanta-form-row">
					<label for="yatravanta_child_age"><?php esc_html_e( 'Children Age (booking widget, e.g. 5-11)', 'yatravanta-trek-booking-manager' ); ?></label>
					<input type="text" name="yatravanta_child_age" id="yatravanta_child_age" data-yatravanta-plain-text="plus" value="<?php echo esc_attr( get_post_meta( $post->ID, '_yatravanta_child_age', true ) ); ?>" style="width:100%;" placeholder="<?php esc_attr_e( '5-11', 'yatravanta-trek-booking-manager' ); ?>" />
				</div>
				<div class="yatravanta-form-row">
					<label for="yatravanta_group_size"><?php esc_html_e( 'Ideal Group Size', 'yatravanta-trek-booking-manager' ); ?></label>
					<input type="text" name="yatravanta_group_size" id="yatravanta_group_size" data-yatravanta-plain-text="1" value="<?php echo esc_attr( $trek['group_size'] ); ?>" style="width:100%;" placeholder="<?php esc_attr_e( 'e.g. 12 to 20 Trekkers', 'yatravanta-trek-booking-manager' ); ?>" />
				</div>
			</div>
		</div>

		<!-- TAB 2: GALLERY -->
		<div id="yatravanta-tab-gallery" class="yatravanta-meta-tab-panel">
			<div class="yatravanta-gallery-container">
				<input type="hidden" name="yatravanta_gallery" id="yatravanta_gallery_ids" value="<?php echo esc_attr( $trek['gallery'] ); ?>" />
				<div class="yatravanta-gallery-thumbs" id="yatravanta_gallery_thumbs_wrapper">
					<?php
					if ( ! empty( $trek['gallery'] ) ) {
						$gallery_ids = explode( ',', $trek['gallery'] );
						foreach ( $gallery_ids as $img_id ) {
							$img_src = wp_get_attachment_image_src( $img_id, 'thumbnail' );
							if ( $img_src ) {
								echo '<div class="yatravanta-gallery-thumb-item" data-id="' . esc_attr( $img_id ) . '">';
								echo '<img src="' . esc_url( $img_src[0] ) . '" alt="" />';
								echo '<a href="#" class="yatravanta-gallery-remove-btn" title="' . esc_attr__( 'Remove', 'yatravanta-trek-booking-manager' ) . '">&times;</a>';
								echo '</div>';
							}
						}
					}
					?>
				</div>
				<button type="button" class="button button-primary button-large" id="yatravanta_select_gallery_btn">
					<?php esc_html_e( 'Add / Manage Gallery Images', 'yatravanta-trek-booking-manager' ); ?>
				</button>
				<p class="description"><?php esc_html_e( 'Select multiple images from the media library to display in the trek slider.', 'yatravanta-trek-booking-manager' ); ?></p>
			</div>
		</div>

		<!-- TAB 3: HIGHLIGHTS & PACKING -->
		<div id="yatravanta-tab-highlights" class="yatravanta-meta-tab-panel">
			<table class="form-table">
				<tr>
					<th><label for="yatravanta_highlights"><?php esc_html_e( 'Inclusions / Key Highlights (One per line)', 'yatravanta-trek-booking-manager' ); ?></label></th>
					<td>
						<textarea name="yatravanta_highlights" id="yatravanta_highlights" rows="8" class="large-text" placeholder="<?php esc_attr_e( "Trek through lush green pine valleys\nExperience camping under starry sky\nStunning views of Mt. Trishul", 'yatravanta-trek-booking-manager' ); ?>"><?php echo esc_textarea( $trek['highlights'] ); ?></textarea>
						<p class="description"><?php esc_html_e( 'Shown on the front-end as "Inclusions", with a green checkmark next to each line.', 'yatravanta-trek-booking-manager' ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="yatravanta_exclusions"><?php esc_html_e( 'Exclusions (One per line)', 'yatravanta-trek-booking-manager' ); ?></label></th>
					<td>
						<textarea name="yatravanta_exclusions" id="yatravanta_exclusions" rows="8" class="large-text" placeholder="<?php esc_attr_e( "Personal trekking gear/equipment rental\nTravel insurance\nMeals not mentioned in the itinerary\nAny costs due to natural calamities or delays", 'yatravanta-trek-booking-manager' ); ?>"><?php echo esc_textarea( $trek['exclusions'] ); ?></textarea>
						<p class="description"><?php esc_html_e( 'Shown next to Inclusions on the front-end, with a red cross next to each line.', 'yatravanta-trek-booking-manager' ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="yatravanta_things_to_carry"><?php esc_html_e( 'Things to Carry Checklist (One per line)', 'yatravanta-trek-booking-manager' ); ?></label></th>
					<td>
						<textarea name="yatravanta_things_to_carry" id="yatravanta_things_to_carry" rows="8" class="large-text" placeholder="<?php esc_attr_e( "Trekking shoes with good grip\nWarm jacket / fleece\nWater bottles 2L\nHeadlamp / Torch", 'yatravanta-trek-booking-manager' ); ?>"><?php echo esc_textarea( $trek['things_to_carry'] ); ?></textarea>
					</td>
				</tr>
			</table>
		</div>

		<!-- TAB 4: FAQ REPEATER -->
		<div id="yatravanta-tab-faq" class="yatravanta-meta-tab-panel">
			<div id="yatravanta_faq_repeater_list">
				<?php if ( ! empty( $faq_items ) ) : ?>
					<?php foreach ( $faq_items as $index => $item ) : ?>
						<div class="yatravanta-faq-repeater-row" data-index="<?php echo esc_attr( $index ); ?>">
							<span class="yatravanta-drag-handle" style="cursor: move;">☰</span>
							<div class="yatravanta-faq-row-fields">
								<input type="text" name="yatravanta_faq[<?php echo esc_attr( $index ); ?>][q]" value="<?php echo esc_attr( $item['q'] ); ?>" placeholder="<?php esc_attr_e( 'Question', 'yatravanta-trek-booking-manager' ); ?>" aria-label="<?php esc_attr_e( 'FAQ question', 'yatravanta-trek-booking-manager' ); ?>" class="large-text" />
								<textarea name="yatravanta_faq[<?php echo esc_attr( $index ); ?>][a]" rows="3" placeholder="<?php esc_attr_e( 'Answer', 'yatravanta-trek-booking-manager' ); ?>" aria-label="<?php esc_attr_e( 'FAQ answer', 'yatravanta-trek-booking-manager' ); ?>" class="large-text"><?php echo esc_textarea( $item['a'] ); ?></textarea>
							</div>
							<button type="button" class="button yatravanta-remove-faq-row-btn"><?php esc_html_e( 'Remove', 'yatravanta-trek-booking-manager' ); ?></button>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
			<button type="button" class="button button-primary" id="yatravanta_add_faq_row_btn">
				<?php esc_html_e( 'Add New FAQ Item', 'yatravanta-trek-booking-manager' ); ?>
			</button>
		</div>

		<!-- TAB 5: POLICIES -->
		<div id="yatravanta-tab-policies" class="yatravanta-meta-tab-panel">
			<p class="description"><?php esc_html_e( 'Leave a policy empty and it will not be shown on the trek page.', 'yatravanta-trek-booking-manager' ); ?></p>
			<table class="form-table">
				<?php foreach ( \YatraVanta\Admin\Controllers\TrekMetaBoxController::get_policy_types() as $yatravanta_policy_key => $yatravanta_policy ) : ?>
					<tr>
						<th><label for="yatravanta_policy_<?php echo esc_attr( $yatravanta_policy_key ); ?>"><?php echo esc_html( $yatravanta_policy['label'] ); ?></label></th>
						<td>
							<?php
							wp_editor(
								isset( $policies[ $yatravanta_policy_key ] ) ? $policies[ $yatravanta_policy_key ] : '',
								'yatravanta_policy_' . $yatravanta_policy_key,
								array(
									'textarea_name' => 'yatravanta_policy_' . $yatravanta_policy_key,
									'textarea_rows' => 8,
									'media_buttons' => false,
									'teeny'         => true,
								)
							);
							?>
							<p class="description"><?php esc_html_e( 'Shown to visitors in a popup below the trek details.', 'yatravanta-trek-booking-manager' ); ?></p>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>

	</div>
</div>
