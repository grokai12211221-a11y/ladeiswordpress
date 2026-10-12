<?php
/**
 * Static page.
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
		<h1><?php the_title(); ?></h1>
	</div>
</div>

<?php lc_content_open(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<article id="page-<?php the_ID(); ?>" <?php post_class( 'lc-single' ); ?>>
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="lc-single__thumb">
				<?php the_post_thumbnail( 'large' ); ?>
			</div>
		<?php endif; ?>
		<div class="lc-entry">
			<?php the_content(); ?>
			<?php
			wp_link_pages(
				array(
					'before' => '<nav class="lc-pagination"><span>' . esc_html__( 'Pages:', 'ladycorrections' ) . '</span>',
					'after'  => '</nav>',
				)
			);
			?>
		</div>
		<?php
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
		?>
	</article>
<?php endwhile; ?>

<?php lc_content_close(); ?>

<?php
do_action( 'lc_after_main' );
get_footer();
