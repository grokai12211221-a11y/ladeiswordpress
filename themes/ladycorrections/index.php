<?php
/**
 * Blog index.
 *
 * @package LadyCorrections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

do_action( 'lc_before_main' );
?>

<div class="lc-pagehead">
	<div class="lc-container">
		<h1><?php esc_html_e( 'Journal', 'ladycorrections' ); ?></h1>
		<p><?php esc_html_e( 'Beauty tips, routines and new arrivals.', 'ladycorrections' ); ?></p>
	</div>
</div>

<?php lc_content_open(); ?>

<?php if ( have_posts() ) : ?>
	<div class="lc-posts__grid">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'template-parts/content', 'card' ); ?>
		<?php endwhile; ?>
	</div>
	<nav class="lc-pagination" aria-label="<?php esc_attr_e( 'Posts navigation', 'ladycorrections' ); ?>">
		<?php
		echo wp_kses_post(
			paginate_links(
				array(
					'prev_text' => '&larr; ' . esc_html__( 'Prev', 'ladycorrections' ),
					'next_text' => esc_html__( 'Next', 'ladycorrections' ) . ' &rarr;',
				)
			)
		);
		?>
	</nav>
<?php else : ?>
	<div class="lc-nothing">
		<h2><?php esc_html_e( 'Nothing here yet', 'ladycorrections' ); ?></h2>
		<p><?php esc_html_e( 'Check back soon for new stories.', 'ladycorrections' ); ?></p>
	</div>
<?php endif; ?>

<?php lc_content_close(); ?>

<?php
do_action( 'lc_after_main' );
get_footer();
