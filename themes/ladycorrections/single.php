<?php
/**
 * Single post.
 *
 * @package LadyCorrections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

do_action( 'lc_before_main' );
?>

<?php lc_content_open(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'lc-single' ); ?>>

		<header class="lc-text-center">
			<div class="lc-post-card__meta" style="justify-content:center;">
				<span><?php echo esc_html( get_the_date() ); ?></span>
				<span><?php the_category( ', ' ); ?></span>
				<span>
					<?php
					/* translators: %s: author name. */
					printf( esc_html__( 'By %s', 'ladycorrections' ), esc_html( get_the_author() ) );
					?>
				</span>
			</div>
			<h1><?php the_title(); ?></h1>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="lc-single__thumb">
				<?php the_post_thumbnail( 'large' ); ?>
			</div>
		<?php endif; ?>

		<div class="lc-entry">
			<?php
			the_content(
				sprintf(
					/* translators: %s: post title. */
					esc_html__( 'Continue reading %s', 'ladycorrections' ),
					'<span class="screen-reader-text">' . get_the_title() . '</span>'
				)
			);
			wp_link_pages(
				array(
					'before' => '<nav class="lc-pagination"><span>' . esc_html__( 'Pages:', 'ladycorrections' ) . '</span>',
					'after'  => '</nav>',
				)
			);
			?>
		</div>

		<?php $tags = get_the_tag_list(); ?>
		<?php if ( $tags ) : ?>
			<div class="lc-tags" aria-label="<?php esc_attr_e( 'Tags', 'ladycorrections' ); ?>">
				<?php echo wp_kses_post( $tags ); ?>
			</div>
		<?php endif; ?>

		<div class="lc-author-box">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 72 ); ?>
			<div>
				<strong><?php echo esc_html( get_the_author() ); ?></strong>
				<p style="margin:0; font-size:14px; color:var(--lc-muted);"><?php echo esc_html( get_the_author_meta( 'description' ) ); ?></p>
			</div>
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
