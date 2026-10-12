<?php
/**
 * Archive template.
 *
 * @package LadyCorrections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Elementor Pro Theme Builder archive location takes over when set. */
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'archive' ) ) {
	return;
}

get_header();

do_action( 'lc_before_main' );
?>

<div class="lc-pagehead">
	<div class="lc-container">
		<h1><?php the_archive_title(); ?></h1>
		<?php $desc = get_the_archive_description(); ?>
		<?php if ( $desc ) : ?>
			<p><?php echo wp_kses_post( $desc ); ?></p>
		<?php endif; ?>
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
		<h2><?php esc_html_e( 'Nothing found', 'ladycorrections' ); ?></h2>
		<p><?php esc_html_e( 'Try a different search or browse the shop.', 'ladycorrections' ); ?></p>
	</div>
<?php endif; ?>

<?php lc_content_close(); ?>

<?php
do_action( 'lc_after_main' );
get_footer();
