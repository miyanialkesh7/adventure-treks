<?php
/**
 * Single Trek Page Template
 *
 * Auto-loaded for all yatravanta_trek CPT single pages.
 * Uses the active theme's header and footer, renders full trek details
 * and the AJAX booking widget in a two-column premium layout.
 *
 * @package    YatraVanta
 * @subpackage YatraVanta/Public/Views
 * @author     Nilesh Vastarpara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

\YatraVanta\Frontend\Controllers\TrekShortcodesController::render_header();

// Ensure we have the post.
if ( ! have_posts() ) {
	echo '<p style="padding:40px;text-align:center;">' . esc_html__( 'Trek not found.', 'yatravanta-trek-booking-manager' ) . '</p>';
	\YatraVanta\Frontend\Controllers\TrekShortcodesController::render_footer();
	return;
}

while ( have_posts() ) :
	the_post();
	$yatravanta_trek_id = get_the_ID();

	// Resolve the hero images: the trek's Photo Gallery when configured,
	// falling back to the Featured Image for treks without one yet.
	global $wpdb;
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
	$yatravanta_trek_row      = $wpdb->get_row( $wpdb->prepare( "SELECT gallery, duration FROM {$wpdb->prefix}yatravanta_treks WHERE post_id = %d", $yatravanta_trek_id ), ARRAY_A );
	$yatravanta_gallery_raw   = $yatravanta_trek_row ? (string) $yatravanta_trek_row['gallery'] : '';
	$yatravanta_trek_duration = $yatravanta_trek_row ? (string) $yatravanta_trek_row['duration'] : '';

	$yatravanta_gallery_ids = ! empty( $yatravanta_gallery_raw ) ? array_filter( array_map( 'intval', explode( ',', $yatravanta_gallery_raw ) ) ) : array();

	// One query for every image's alt text instead of one per image.
	update_meta_cache( 'post', $yatravanta_gallery_ids );

	$yatravanta_hero_images = array();
	foreach ( $yatravanta_gallery_ids as $yatravanta_img_id ) {
		$yatravanta_main_src = wp_get_attachment_image_src( $yatravanta_img_id, 'large' );
		$yatravanta_full_src = wp_get_attachment_image_src( $yatravanta_img_id, 'full' );
		if ( ! $yatravanta_main_src ) {
			continue;
		}
		$yatravanta_hero_images[] = array(
			'url'  => $yatravanta_main_src[0],
			'full' => $yatravanta_full_src ? $yatravanta_full_src[0] : $yatravanta_main_src[0],
			'alt'  => get_post_meta( $yatravanta_img_id, '_wp_attachment_image_alt', true ),
		);
	}

	if ( empty( $yatravanta_hero_images ) && has_post_thumbnail() ) {
		$yatravanta_hero_images[] = array(
			'url'  => get_the_post_thumbnail_url( $yatravanta_trek_id, 'full' ),
			'full' => get_the_post_thumbnail_url( $yatravanta_trek_id, 'full' ),
			'alt'  => get_the_title(),
		);
	}
	?>

<div class="yatravanta-single-trek-wrap">

	<!-- ── HERO PHOTO GALLERY (mosaic: 1 large + 2x2 grid, "View All Images") ──── -->
	<?php
	$yatravanta_total_images = count( $yatravanta_hero_images );
	$yatravanta_main_image   = $yatravanta_total_images > 0 ? $yatravanta_hero_images[0] : null;
	$yatravanta_side_images  = $yatravanta_total_images > 1 ? array_slice( $yatravanta_hero_images, 1, 4 ) : array();
	$yatravanta_side_count   = count( $yatravanta_side_images );
	$yatravanta_hidden_count = max( 0, $yatravanta_total_images - 5 );
	?>
	<?php if ( $yatravanta_main_image ) : ?>
		<div class="yatravanta-hero-gallery-card" id="yatravanta_hero_gallery">
			<div class="yatravanta-hero-mosaic<?php echo 0 === $yatravanta_side_count ? ' yatravanta-hero-mosaic--single' : ''; ?>">

				<div class="yatravanta-mosaic-main yatravanta-hero-lightbox-trigger" data-full="<?php echo esc_url( $yatravanta_main_image['full'] ); ?>" data-index="0">
					<img src="<?php echo esc_url( $yatravanta_main_image['url'] ); ?>"
						alt="<?php echo esc_attr( $yatravanta_main_image['alt'] ? $yatravanta_main_image['alt'] : get_the_title() ); ?>"
						loading="eager" />
				</div>
				<?php if ( $yatravanta_total_images >= 1 ) : ?>
					<!-- Sibling grid item sharing .yatravanta-mosaic-main's cell (mobile only) — not
						nested inside it, so it never depends on that element's `position`. -->
					<button type="button" class="yatravanta-mosaic-viewall-btn yatravanta-mosaic-viewall-mobile" data-start-index="0">
						<span class="dashicons dashicons-images-alt2"></span> <?php esc_html_e( 'View All Images', 'yatravanta-trek-booking-manager' ); ?>
					</button>
				<?php endif; ?>

				<?php if ( $yatravanta_side_count > 0 ) : ?>
					<div class="yatravanta-mosaic-side" data-count="<?php echo esc_attr( $yatravanta_side_count ); ?>">
						<?php foreach ( $yatravanta_side_images as $yatravanta_side_idx => $yatravanta_side_img ) : ?>
							<?php $yatravanta_real_idx = $yatravanta_side_idx + 1; ?>
							<div class="yatravanta-mosaic-cell yatravanta-hero-lightbox-trigger" data-full="<?php echo esc_url( $yatravanta_side_img['full'] ); ?>" data-index="<?php echo esc_attr( $yatravanta_real_idx ); ?>">
								<img src="<?php echo esc_url( $yatravanta_side_img['url'] ); ?>"
									alt="<?php echo esc_attr( $yatravanta_side_img['alt'] ? $yatravanta_side_img['alt'] : get_the_title() ); ?>"
									loading="lazy" />
							</div>
						<?php endforeach; ?>
						<?php if ( $yatravanta_total_images > 1 ) : ?>
							<!-- Sibling grid item sharing the last cell's grid area (row2/col2) —
								not nested inside that cell, so no `position` dependency at all.
								Always shown on desktop; jumps past the 5 tiles only when more exist. -->
							<button type="button" class="yatravanta-mosaic-viewall-btn" data-start-index="<?php echo esc_attr( $yatravanta_hidden_count > 0 ? 5 : 0 ); ?>">
								<span class="dashicons dashicons-images-alt2"></span> <?php esc_html_e( 'View All Images', 'yatravanta-trek-booking-manager' ); ?>
							</button>
						<?php endif; ?>
					</div>
				<?php endif; ?>

			</div>

			<?php if ( $yatravanta_hidden_count > 0 ) : ?>
				<!-- Photos beyond the 5 visible mosaic tiles: not shown as tiles, but the
					lightbox needs their URLs so "View All Images" can actually reach them. -->
				<div style="display:none;" aria-hidden="true">
					<?php for ( $yatravanta_hidden_i = 5; $yatravanta_hidden_i < $yatravanta_total_images; $yatravanta_hidden_i++ ) : ?>
						<span class="yatravanta-hero-lightbox-source" data-full="<?php echo esc_url( $yatravanta_hero_images[ $yatravanta_hidden_i ]['full'] ); ?>" data-index="<?php echo esc_attr( $yatravanta_hidden_i ); ?>"></span>
					<?php endfor; ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<!-- Share dialog (opened by the Share button). Rendered even without photos so sharing always works. -->
	<div class="yatravanta-share-modal" id="yatravanta_share_modal" role="dialog" aria-modal="true" aria-labelledby="yatravanta_share_title">
		<div class="yatravanta-share-dialog">
			<div class="yatravanta-share-head">
				<h3 id="yatravanta_share_title"><?php esc_html_e( 'Share this trek', 'yatravanta-trek-booking-manager' ); ?></h3>
				<button type="button" class="yatravanta-share-close" id="yatravanta_share_close" aria-label="<?php esc_attr_e( 'Close', 'yatravanta-trek-booking-manager' ); ?>"><span class="dashicons dashicons-no-alt"></span></button>
			</div>
			<p class="yatravanta-share-sub"><?php echo esc_html( get_the_title() ); ?></p>
			<div class="yatravanta-share-grid">
				<a href="#" class="yatravanta-share-option" data-share="whatsapp" target="_blank" rel="noopener noreferrer"><span class="yatravanta-share-icon yatravanta-share-whatsapp"><span class="dashicons dashicons-whatsapp"></span></span>WhatsApp</a>
				<a href="#" class="yatravanta-share-option" data-share="facebook" target="_blank" rel="noopener noreferrer"><span class="yatravanta-share-icon yatravanta-share-facebook"><span class="dashicons dashicons-facebook-alt"></span></span>Facebook</a>
				<a href="#" class="yatravanta-share-option" data-share="x" target="_blank" rel="noopener noreferrer"><span class="yatravanta-share-icon yatravanta-share-x"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/></svg></span>X</a>
				<a href="#" class="yatravanta-share-option" data-share="linkedin" target="_blank" rel="noopener noreferrer"><span class="yatravanta-share-icon yatravanta-share-linkedin"><span class="dashicons dashicons-linkedin"></span></span>LinkedIn</a>
				<a href="#" class="yatravanta-share-option" data-share="reddit" target="_blank" rel="noopener noreferrer"><span class="yatravanta-share-icon yatravanta-share-reddit"><span class="dashicons dashicons-reddit"></span></span>Reddit</a>
				<a href="#" class="yatravanta-share-option" data-share="email"><span class="yatravanta-share-icon yatravanta-share-email"><span class="dashicons dashicons-email-alt"></span></span><?php esc_html_e( 'Email', 'yatravanta-trek-booking-manager' ); ?></a>
			</div>
			<div class="yatravanta-share-copy-row">
				<input type="text" id="yatravanta_share_url" value="<?php echo esc_url( get_permalink( $yatravanta_trek_id ) ); ?>" readonly />
				<button type="button" class="yatravanta-share-copy-btn" id="yatravanta_share_copy" data-copied="<?php esc_attr_e( 'Copied!', 'yatravanta-trek-booking-manager' ); ?>"><?php esc_html_e( 'Copy link', 'yatravanta-trek-booking-manager' ); ?></button>
			</div>
		</div>
	</div>

	<!-- ── HERO GALLERY LIGHTBOX (full, uncropped photo view) ──── -->
	<?php if ( ! empty( $yatravanta_hero_images ) ) : ?>
		<div class="yatravanta-hero-lightbox" id="yatravanta_hero_lightbox">
			<div class="yatravanta-hero-lightbox-overlay" data-lightbox-close></div>
			<div class="yatravanta-hero-lightbox-content">
				<button type="button" class="yatravanta-hero-lightbox-close" data-lightbox-close aria-label="<?php esc_attr_e( 'Close', 'yatravanta-trek-booking-manager' ); ?>">&times;</button>
				<?php if ( count( $yatravanta_hero_images ) > 1 ) : ?>
					<button type="button" class="yatravanta-hero-lightbox-prev" id="yatravanta_hero_lightbox_prev" aria-label="<?php esc_attr_e( 'Previous photo', 'yatravanta-trek-booking-manager' ); ?>"><span class="dashicons dashicons-arrow-left-alt2"></span></button>
					<button type="button" class="yatravanta-hero-lightbox-next" id="yatravanta_hero_lightbox_next" aria-label="<?php esc_attr_e( 'Next photo', 'yatravanta-trek-booking-manager' ); ?>"><span class="dashicons dashicons-arrow-right-alt2"></span></button>
				<?php endif; ?>
				<img id="yatravanta_hero_lightbox_img" src="" alt="" />
				<?php if ( count( $yatravanta_hero_images ) > 1 ) : ?>
					<span class="yatravanta-hero-lightbox-counter" id="yatravanta_hero_lightbox_counter"></span>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<!--  TWO-COLUMN BODY -->
	<div class="yatravanta-single-body">

		<!-- MAIN -->
		<div class="yatravanta-single-main">

			<!-- TREK TITLE CARD (duration, title, tagline, share) -->
			<div class="yatravanta-hero-heading-card">
				<div class="yatravanta-hero-heading-text">
					<?php if ( ! empty( $yatravanta_trek_duration ) ) : ?>
						<span class="yatravanta-hero-duration"><?php echo esc_html( $yatravanta_trek_duration ); ?></span>
					<?php endif; ?>
					<h1><?php the_title(); ?></h1>
					<?php if ( has_excerpt() ) : ?>
						<p class="yatravanta-hero-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
					<?php endif; ?>
				</div>
				<button type="button" class="yatravanta-hero-share-btn" id="yatravanta_hero_share_btn" data-share-url="<?php echo esc_url( get_permalink( $yatravanta_trek_id ) ); ?>" data-share-title="<?php echo esc_attr( get_the_title() ); ?>">
					<span class="dashicons dashicons-share"></span> <?php esc_html_e( 'Share', 'yatravanta-trek-booking-manager' ); ?>
				</button>
			</div>

			<?php if ( get_the_content() ) : ?>
			<div class="yatravanta-section-card yatravanta-about-card">
				<h2><span class="dashicons dashicons-text-page"></span><?php esc_html_e( 'About This Trek', 'yatravanta-trek-booking-manager' ); ?></h2>
				<?php
				// Plain-text teaser: first 250 characters, then the full formatted content on demand.
				$yatravanta_about_html  = apply_filters( 'the_content', get_the_content() ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core hook.
				$yatravanta_about_text  = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( $yatravanta_about_html ) ) );
				$yatravanta_about_long  = mb_strlen( $yatravanta_about_text ) > 250;
				$yatravanta_about_short = $yatravanta_about_long ? rtrim( mb_substr( $yatravanta_about_text, 0, 250 ) ) . '...' : '';
				?>
				<div class="yatravanta-post-content-wrap<?php echo $yatravanta_about_long ? ' yatravanta-collapsible' : ''; ?>" id="yatravanta_about_wrap">
					<?php if ( $yatravanta_about_long ) : ?>
						<div class="yatravanta-post-content yatravanta-about-short"><p><?php echo esc_html( $yatravanta_about_short ); ?></p></div>
					<?php endif; ?>
					<div class="yatravanta-post-content yatravanta-about-full"><?php echo $yatravanta_about_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core the_content output. ?></div>
				</div>
				<button type="button" class="yatravanta-show-more-btn" id="yatravanta_about_toggle"
					data-more="<?php esc_attr_e( 'Show More', 'yatravanta-trek-booking-manager' ); ?>"
					data-less="<?php esc_attr_e( 'Show Less', 'yatravanta-trek-booking-manager' ); ?>">
					<span class="yatravanta-toggle-label"><?php esc_html_e( 'Show More', 'yatravanta-trek-booking-manager' ); ?></span>
					<span class="dashicons dashicons-arrow-down-alt2"></span>
				</button>
			</div>
			<?php endif; ?>

			<!-- Trek Details Shortcode (specs, highlights, itinerary, FAQs, policies, gallery) -->
			<?php echo do_shortcode( '[yatravanta_details id="' . $yatravanta_trek_id . '"]' ); ?>

		</div>

		<!-- SIDEBAR -->
		<div class="yatravanta-single-sidebar">
			<!-- Booking Widget Shortcode -->
			<?php echo do_shortcode( '[yatravanta_booking id="' . $yatravanta_trek_id . '"]' ); ?>
		</div>

	</div><!-- /.yatravanta-single-body -->

</div><!-- /.yatravanta-single-trek-wrap -->


	<?php
endwhile;

\YatraVanta\Frontend\Controllers\TrekShortcodesController::render_footer();
