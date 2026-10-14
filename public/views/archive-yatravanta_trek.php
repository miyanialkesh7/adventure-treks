<?php
/**
 * Trek Archive Page Template
 *
 * Auto-loaded for the yatravanta_trek CPT archive page (works regardless of
 * the active theme/editor — Gutenberg, classic, or block themes). Renders
 * the trek grid using the real main query, so pagination follows WordPress's
 * standard /page/2/ structure.
 *
 * @package    YatraVanta
 * @subpackage YatraVanta/Public/Views
 * @author     Nilesh Vastarpara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

\YatraVanta\Frontend\Controllers\TrekShortcodesController::render_header();
?>

<div class="yatravanta-trek-archive-wrapper" style="max-width:1200px; margin:30px auto; padding:0 20px;">

	<header class="yatravanta-trek-archive-header" style="margin-bottom:24px;">
		<h1><?php post_type_archive_title(); ?></h1>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="yatravanta-trek-archive-grid" style="--yatravanta-archive-cols: 3;">
			<?php
			while ( have_posts() ) :
				the_post();
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- values are escaped inside get_trek_archive_card_html().
				echo \YatraVanta\Frontend\Controllers\TrekShortcodesController::get_trek_archive_card_html( get_the_ID(), true, true );
			endwhile;
			?>
		</div>

		<div class="yatravanta-trek-archive-pagination">
			<?php
			the_posts_pagination(
				array(
					'prev_text' => esc_html__( '« Prev', 'yatravanta-trek-booking-manager' ),
					'next_text' => esc_html__( 'Next »', 'yatravanta-trek-booking-manager' ),
				)
			);
			?>
		</div>
	<?php else : ?>
		<p class="yatravanta-trek-archive-empty"><?php esc_html_e( 'No treks found.', 'yatravanta-trek-booking-manager' ); ?></p>
	<?php endif; ?>

</div>

<?php
\YatraVanta\Frontend\Controllers\TrekShortcodesController::render_footer();
