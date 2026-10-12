<?php
/**
 * Search results.
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
		<h1>
			<?php
			/* translators: %s: search query. */
			printf( esc_html__( 'Results for "%s"', 'ladycorrections' ), esc_html( get_search_query() ) );
			?>
		</h1>
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
		<h2><?php echo esc_html( get_theme_mod( 'lc_search_empty_h', __( 'No results found', 'ladycorrections' ) ) ); ?></h2>
		<p><?php echo esc_html( get_theme_mod( 'lc_search_empty_p', __( 'Try different keywords or browse the shop instead.', 'ladycorrections' ) ) ); ?></p>
		<?php get_search_form(); ?>
	</div>
<?php endif; ?>

<?php lc_content_close(); ?>

<?php
do_action( 'lc_after_main' );
get_footer();
