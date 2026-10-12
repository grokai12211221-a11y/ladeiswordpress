<?php
/**
 * Template Name: LC Info Page
 *
 * Generic info page (Shipping, Returns, Privacy, Terms…): renders the page
 * title and content with premium typography, a "Last updated" line, and
 * link chips to related info pages.
 *
 * @package LadyCorrections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

do_action( 'lc_before_main' );
?>

<main id="lc-main" class="lc-main">

	<div class="lc-pagehead">
		<div class="lc-container">
			<h1><?php the_title(); ?></h1>
		</div>
	</div>

	<section class="lc-section">
		<div class="lc-container">
			<div class="lc-page-card">

				<?php while ( have_posts() ) : the_post(); ?>
					<div class="lc-richtext">
						<?php the_content(); ?>
					</div>
				<?php endwhile; ?>

				<p class="lc-info-updated">
					<?php echo esc_html( get_theme_mod( 'lc_info_updated', __( 'Last updated', 'ladycorrections' ) ) ); ?>:
					<?php echo esc_html( get_the_modified_date() ); ?>
				</p>

				<?php
				$lc_related = array();
				foreach ( array( 'shipping', 'returns', 'faq', 'contact' ) as $lc_slug ) {
					$lc_rel_page = get_page_by_path( $lc_slug );
					if ( $lc_rel_page ) {
						$lc_related[] = $lc_rel_page;
					}
				}
				?>

				<?php if ( $lc_related ) : ?>
					<div class="lc-rel-links" aria-label="<?php esc_attr_e( 'Related pages', 'ladycorrections' ); ?>">
						<?php foreach ( $lc_related as $lc_rel_page ) : ?>
							<a class="lc-rel-links__chip" href="<?php echo esc_url( get_permalink( $lc_rel_page ) ); ?>"><?php echo esc_html( get_the_title( $lc_rel_page ) ); ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</section>

</main>

<?php
do_action( 'lc_after_main' );
get_footer();
