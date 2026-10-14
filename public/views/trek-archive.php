<?php
/**
 * Frontend Trek Archive Grid view template.
 *
 * @package    YatraVanta
 * @subpackage YatraVanta/Public/Views
 * @author     Nilesh Vastarpara
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<div class="yatravanta-trek-archive-wrapper">
	<?php if ( $query->have_posts() ) : ?>
		<div class="yatravanta-trek-archive-grid" style="--yatravanta-archive-cols: <?php echo esc_attr( $columns ); ?>;">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- values are escaped inside get_trek_archive_card_html().
				echo \YatraVanta\Frontend\Controllers\TrekShortcodesController::get_trek_archive_card_html( get_the_ID(), $show_excerpt, $show_price );
			endwhile;
			?>
		</div>

		<?php if ( $show_pagination && $query->max_num_pages > 1 ) : ?>
			<div class="yatravanta-trek-archive-pagination">
				<?php
				echo paginate_links( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted output from WP core.
					array(
						'base'      => add_query_arg( 'yatravanta_trek_page', '%#%' ),
						'format'    => '',
						'current'   => $current_page,
						'total'     => $query->max_num_pages,
						'prev_text' => esc_html__( '« Prev', 'yatravanta-trek-booking-manager' ),
						'next_text' => esc_html__( 'Next »', 'yatravanta-trek-booking-manager' ),
						'type'      => 'plain',
					)
				);
				?>
			</div>
		<?php endif; ?>

	<?php else : ?>
		<p class="yatravanta-trek-archive-empty"><?php esc_html_e( 'No treks found.', 'yatravanta-trek-booking-manager' ); ?></p>
	<?php endif; ?>
</div>
