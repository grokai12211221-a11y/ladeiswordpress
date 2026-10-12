<?php
/**
 * Post card (used in blog/archive/search grids).
 *
 * @package LadyCorrections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'lc-post-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="lc-post-card__img" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php the_post_thumbnail( 'medium_large' ); ?>
		</a>
	<?php endif; ?>
	<div class="lc-post-card__body">
		<div class="lc-post-card__meta">
			<span><?php echo esc_html( get_the_date() ); ?></span>
			<span><?php the_category( ', ' ); ?></span>
		</div>
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p><?php echo esc_html( get_the_excerpt() ); ?></p>
		<a class="lc-readmore" href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'Read more', 'ladycorrections' ); ?> &rarr;
		</a>
	</div>
</article>
