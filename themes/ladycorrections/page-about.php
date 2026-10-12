<?php
/**
 * Template Name: LC About
 *
 * Brand story page: hero band, animated stat counters, values grid, CTA band.
 *
 * @package LadyCorrections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Split a stat value like "10k+" or "4.9" into an animatable number, its
 * decimal count, and the remaining suffix (e.g. "k+", "+").
 *
 * @param string $raw Raw stat value.
 * @return array{0:float|null,1:int,2:string} Number|null, decimals, suffix.
 */
function lc_parse_stat_value( $raw ) {
	$raw = trim( (string) $raw );
	if ( preg_match( '/^([\d.,]+)(.*)$/u', $raw, $matches ) ) {
		$plain = str_replace( ',', '', $matches[1] );
		if ( is_numeric( $plain ) ) {
			$decimals = 0;
			if ( false !== strpos( $plain, '.' ) ) {
				$decimals = strlen( substr( $plain, strpos( $plain, '.' ) + 1 ) );
			}
			return array( (float) $plain, $decimals, trim( $matches[2] ) );
		}
	}
	return array( null, 0, $raw );
}

get_header();

do_action( 'lc_before_main' );
?>

<main id="lc-main" class="lc-main">

	<div class="lc-pagehead">
		<div class="lc-container">
			<h1><?php echo esc_html( get_theme_mod( 'lc_about_title', __( 'Our Story', 'ladycorrections' ) ) ); ?></h1>
			<p><?php echo esc_html( get_theme_mod( 'lc_about_sub', __( 'Discover the brand behind your favorite beauty essentials.', 'ladycorrections' ) ) ); ?></p>
		</div>
	</div>

	<section class="lc-section">
		<div class="lc-container">
			<div class="lc-stats">
				<?php
				$lc_stats = array(
					array(
						'num'   => get_theme_mod( 'lc_about_s1n', __( '10k+', 'ladycorrections' ) ),
						'label' => get_theme_mod( 'lc_about_s1l', __( 'Happy customers', 'ladycorrections' ) ),
					),
					array(
						'num'   => get_theme_mod( 'lc_about_s2n', __( '120+', 'ladycorrections' ) ),
						'label' => get_theme_mod( 'lc_about_s2l', __( 'Premium products', 'ladycorrections' ) ),
					),
					array(
						'num'   => get_theme_mod( 'lc_about_s3n', __( '18', 'ladycorrections' ) ),
						'label' => get_theme_mod( 'lc_about_s3l', __( 'Governorates served', 'ladycorrections' ) ),
					),
					array(
						'num'   => get_theme_mod( 'lc_about_s4n', __( '4.9', 'ladycorrections' ) ),
						'label' => get_theme_mod( 'lc_about_s4l', __( 'Average rating', 'ladycorrections' ) ),
					),
				);

				foreach ( $lc_stats as $lc_stat ) :
					list( $lc_number, $lc_decimals, $lc_suffix ) = lc_parse_stat_value( $lc_stat['num'] );
					?>
					<div class="lc-stat">
						<div class="lc-stat__num" aria-label="<?php echo esc_attr( trim( (string) $lc_stat['num'] . ' ' . (string) $lc_stat['label'] ) ); ?>">
							<?php if ( null !== $lc_number ) : ?>
								<span data-lc-count="<?php echo esc_attr( $lc_number ); ?>"<?php echo $lc_decimals ? ' data-lc-decimals="' . esc_attr( $lc_decimals ) . '"' : ''; ?>><?php echo esc_html( (string) $lc_stat['num'] ); ?></span><?php echo $lc_suffix ? esc_html( $lc_suffix ) : ''; ?>
							<?php else : ?>
								<?php echo esc_html( $lc_suffix ); ?>
							<?php endif; ?>
						</div>
						<div class="lc-stat__label"><?php echo esc_html( $lc_stat['label'] ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="lc-section lc-section--blush">
		<div class="lc-container">
			<div class="lc-values">
				<div class="lc-value-card">
					<div class="lc-value-card__icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 3v5c0 4.5-3 8-7 10-4-2-7-5.5-7-10V6l7-3z"/><path d="M9 12l2 2 4-4"/></svg>
					</div>
					<h2 class="lc-value-card__title"><?php echo esc_html( get_theme_mod( 'lc_about_v1t', __( '100% Authentic', 'ladycorrections' ) ) ); ?></h2>
					<p class="lc-value-card__text"><?php echo esc_html( get_theme_mod( 'lc_about_v1d', __( 'Original brands only, sourced directly from authorized distributors.', 'ladycorrections' ) ) ); ?></p>
				</div>
				<div class="lc-value-card">
					<div class="lc-value-card__icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
					</div>
					<h2 class="lc-value-card__title"><?php echo esc_html( get_theme_mod( 'lc_about_v2t', __( 'Fair Prices', 'ladycorrections' ) ) ); ?></h2>
					<p class="lc-value-card__text"><?php echo esc_html( get_theme_mod( 'lc_about_v2d', __( 'Honest pricing in IQD with no hidden markups.', 'ladycorrections' ) ) ); ?></p>
				</div>
				<div class="lc-value-card">
					<div class="lc-value-card__icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
					</div>
					<h2 class="lc-value-card__title"><?php echo esc_html( get_theme_mod( 'lc_about_v3t', __( 'Fast COD Delivery', 'ladycorrections' ) ) ); ?></h2>
					<p class="lc-value-card__text"><?php echo esc_html( get_theme_mod( 'lc_about_v3d', __( 'Cash on delivery, brought to your door across Iraq.', 'ladycorrections' ) ) ); ?></p>
				</div>
				<div class="lc-value-card">
					<div class="lc-value-card__icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
					</div>
					<h2 class="lc-value-card__title"><?php echo esc_html( get_theme_mod( 'lc_about_v4t', __( 'Support in EN/AR', 'ladycorrections' ) ) ); ?></h2>
					<p class="lc-value-card__text"><?php echo esc_html( get_theme_mod( 'lc_about_v4d', __( 'Real humans, in your language, always here to help.', 'ladycorrections' ) ) ); ?></p>
				</div>
			</div>
		</div>
	</section>

	<section class="lc-section">
		<div class="lc-container">
			<div class="lc-cta-band">
				<h2><?php echo esc_html( get_theme_mod( 'lc_about_cta_h', __( 'Ready to glow?', 'ladycorrections' ) ) ); ?></h2>
				<p><?php echo esc_html( get_theme_mod( 'lc_about_cta_p', __( 'Join thousands of happy customers across Iraq — shop our most loved picks.', 'ladycorrections' ) ) ); ?></p>
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<a class="lc-btn lc-btn--primary" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php echo esc_html( get_theme_mod( 'lc_about_cta_btn', __( 'Shop Bestsellers', 'ladycorrections' ) ) ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</section>

</main>

<?php
do_action( 'lc_after_main' );
get_footer();
