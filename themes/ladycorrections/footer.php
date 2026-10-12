<?php
/**
 * Theme footer.
 *
 * @package LadyCorrections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Elementor Pro Theme Builder footer location takes over when set. */
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'footer' ) ) {
	wp_footer();
	?>
	</body>
	</html>
	<?php
	return;
}
?>

<?php do_action( 'lc_before_footer' ); ?>

<footer class="lc-footer">
	<?php if ( is_active_sidebar( 'footer-widgets' ) ) : ?>
		<div class="lc-container" style="padding-top:40px;">
			<?php dynamic_sidebar( 'footer-widgets' ); ?>
		</div>
	<?php endif; ?>

	<div class="lc-container lc-footer__main">

		<div class="lc-footer__brand">
			<a class="lc-footer__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php esc_html_e( 'Lady', 'ladycorrections' ); ?><span><?php esc_html_e( 'Corrections', 'ladycorrections' ); ?></span>
			</a>
			<p><?php echo wp_kses_post( get_theme_mod( 'lc_footer_about', '' ) ); ?></p>
			<div class="lc-social" aria-label="<?php echo esc_attr( get_theme_mod( 'lc_foot_social_h', __( 'Follow us', 'ladycorrections' ) ) ); ?>">
				<a href="#" aria-label="Instagram" rel="noopener">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
				</a>
				<a href="#" aria-label="Facebook" rel="noopener">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
				</a>
				<a href="#" aria-label="TikTok" rel="noopener">
					<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19.6 7.2a5.6 5.6 0 0 1-3.4-1.1v6.5a6.1 6.1 0 1 1-6.1-6.1c.3 0 .7 0 1 .1v3.1a3 3 0 1 0 2.1 2.9V1.5h3a5.6 5.6 0 0 0 3.4 3v2.7z"/></svg>
				</a>
				<?php
				$lc_wa_number = get_theme_mod( 'lc_whatsapp', '' );
				$lc_wa_number = preg_replace( '/\D+/', '', (string) $lc_wa_number );
				?>
				<?php if ( $lc_wa_number ) : ?>
				<a href="<?php echo esc_url( 'https://wa.me/' . $lc_wa_number ); ?>" aria-label="WhatsApp" target="_blank" rel="noopener">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.6 8.6 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.6 8.6 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5a8.6 8.6 0 0 1 8 8v.5z"/></svg>
				</a>
				<?php endif; ?>
			</div>
		</div>

		<div>
			<h4><?php echo esc_html( get_theme_mod( 'lc_foot_shop_h', __( 'Shop', 'ladycorrections' ) ) ); ?></h4>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer-shop',
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => false,
				)
			);
			?>
		</div>

		<div>
			<h4><?php echo esc_html( get_theme_mod( 'lc_foot_help_h', __( 'Help', 'ladycorrections' ) ) ); ?></h4>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer-help',
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => false,
				)
			);
			?>
		</div>

		<div>
			<h4><?php echo esc_html( get_theme_mod( 'lc_foot_pol_h', __( 'Policies', 'ladycorrections' ) ) ); ?></h4>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer-policies',
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => false,
				)
			);
			?>
		</div>

	</div>

	<div class="lc-container">
		<div class="lc-footer__bottom">
			<span class="lc-copyright"><?php echo wp_kses_post( get_theme_mod( 'lc_copyright_text', '' ) ); ?></span>
			<div class="lc-pay-badges" aria-label="<?php esc_attr_e( 'Accepted payment methods', 'ladycorrections' ); ?>">
				<span><?php echo esc_html( get_theme_mod( 'lc_pay_1', __( 'Cash on Delivery', 'ladycorrections' ) ) ); ?></span>
				<span><?php echo esc_html( get_theme_mod( 'lc_pay_2', __( 'Cash', 'ladycorrections' ) ) ); ?></span>
				<span><?php echo esc_html( get_theme_mod( 'lc_pay_3', __( 'Bank Transfer', 'ladycorrections' ) ) ); ?></span>
			</div>
		</div>
	</div>
</footer>

<button class="lc-to-top" id="lc-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'ladycorrections' ); ?>">&uarr;</button>

<?php /* ---------- QUICK VIEW MODAL (populated by JS) ---------- */ ?>
<?php if ( class_exists( 'WooCommerce' ) ) : ?>
<div class="lc-qv-veil" id="lc-qv-veil" role="dialog" aria-modal="true" aria-labelledby="lc-qv-title" aria-hidden="true">
	<div class="lc-qv__wrap">
		<button class="lc-qv__close" id="lc-qv-close" aria-label="<?php esc_attr_e( 'Close quick view', 'ladycorrections' ); ?>">&times;</button>
		<div class="lc-qv">
			<div class="lc-qv__media"><img id="lc-qv-img" src="" alt=""></div>
			<div class="lc-qv__body">
				<h3 id="lc-qv-title"></h3>
				<div class="lc-qv__stars" id="lc-qv-stars" aria-hidden="true"></div>
				<p id="lc-qv-rating" style="font-size:13.5px;color:var(--lc-muted);"></p>
				<div class="lc-qv__price" id="lc-qv-price"></div>
				<div class="lc-qv__actions">
					<a class="lc-btn lc-btn--primary" id="lc-qv-link" href="#"><?php echo esc_html( get_theme_mod( 'lc_qv_details', __( 'View full details', 'ladycorrections' ) ) ); ?></a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php do_action( 'lc_after_footer' ); ?>

<?php wp_footer(); ?>
</body>
</html>
