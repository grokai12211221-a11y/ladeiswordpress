<?php
/**
 * Template Name: LC Contact
 *
 * Contact page: info cards, contact form (shortcode or built-in fallback
 * with wp_mail), WhatsApp order button, FAQ teaser band.
 *
 * @package LadyCorrections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---------- Handle the built-in fallback form before any output ---------- */
$lc_contact_sent   = false;
$lc_contact_error  = '';
$lc_contact_name   = '';
$lc_contact_email  = '';
$lc_contact_msg    = '';

if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['lc_contact_nonce'] ) ) {
	check_admin_referer( 'lc_contact', 'lc_contact_nonce' );

	$lc_contact_name  = isset( $_POST['lc_contact_name'] ) ? sanitize_text_field( wp_unslash( $_POST['lc_contact_name'] ) ) : '';
	$lc_contact_email = isset( $_POST['lc_contact_email'] ) ? sanitize_email( wp_unslash( $_POST['lc_contact_email'] ) ) : '';
	$lc_contact_msg   = isset( $_POST['lc_contact_msg'] ) ? sanitize_textarea_field( wp_unslash( $_POST['lc_contact_msg'] ) ) : '';

	$lc_default_err = get_theme_mod( 'lc_contact_f_err', __( 'Something went wrong. Please try again or contact us on WhatsApp.', 'ladycorrections' ) );

	if ( '' === $lc_contact_name || '' === $lc_contact_msg || ! is_email( $lc_contact_email ) ) {
		$lc_contact_error = $lc_default_err;
	} else {
		/* translators: %s: sender name. */
		$lc_subject = sprintf( __( 'New contact message from %s', 'ladycorrections' ), $lc_contact_name );
		$lc_body    = "Name: {$lc_contact_name}\nEmail: {$lc_contact_email}\n\n{$lc_contact_msg}";
		$lc_headers = array( 'Reply-To: ' . $lc_contact_name . ' <' . $lc_contact_email . '>' );

		if ( wp_mail( get_option( 'admin_email' ), $lc_subject, $lc_body, $lc_headers ) ) {
			$lc_contact_sent = true;
		} else {
			$lc_contact_error = $lc_default_err;
		}
	}
}

$lc_faq_page = get_page_by_path( 'faq' );
$lc_faq_url  = $lc_faq_page ? get_permalink( $lc_faq_page ) : '#';
$lc_whatsapp = preg_replace( '/\D+/', '', (string) get_theme_mod( 'lc_whatsapp', '' ) );

get_header();

do_action( 'lc_before_main' );
?>

<main id="lc-main" class="lc-main">

	<div class="lc-pagehead">
		<div class="lc-container">
			<h1><?php echo esc_html( get_theme_mod( 'lc_contact_h', __( 'Contact Us', 'ladycorrections' ) ) ); ?></h1>
			<p><?php echo esc_html( get_theme_mod( 'lc_contact_sub', __( 'Questions about an order, a product, or anything else? We\'d love to hear from you.', 'ladycorrections' ) ) ); ?></p>
		</div>
	</div>

	<section class="lc-section">
		<div class="lc-container">

			<div class="lc-info-cards">
				<?php $lc_phone = trim( (string) get_theme_mod( 'lc_contact_phone', '' ) ); ?>
				<?php if ( '' !== $lc_phone ) : ?>
					<div class="lc-info-card">
						<div class="lc-info-card__icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
						</div>
						<div class="lc-info-card__label"><?php echo esc_html( get_theme_mod( 'lc_contact_phone_l', __( 'Phone / WhatsApp', 'ladycorrections' ) ) ); ?></div>
						<div class="lc-info-card__value"><?php echo esc_html( $lc_phone ); ?></div>
						<div class="lc-info-card__links">
							<a class="lc-icon-btn" href="<?php echo esc_url( 'tel:' . preg_replace( '/[^\d+]/', '', $lc_phone ) ); ?>" aria-label="<?php esc_attr_e( 'Call', 'ladycorrections' ); ?>">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
							</a>
							<?php $lc_phone_digits = preg_replace( '/\D+/', '', $lc_phone ); ?>
							<?php if ( '' !== $lc_phone_digits ) : ?>
								<a class="lc-icon-btn" href="<?php echo esc_url( 'https://wa.me/' . $lc_phone_digits ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'WhatsApp', 'ladycorrections' ); ?>">
									<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm5.2 14.2c-.2.6-1.2 1.2-1.7 1.2-.4.1-1 .1-1.6-.1-.4-.1-.9-.3-1.5-.6-2.6-1.1-4.3-3.7-4.4-3.9-.1-.2-1-1.4-1-2.7s.6-1.9.9-2.2c.2-.3.5-.3.7-.3h.5c.2 0 .4 0 .6.5l.9 2.1c.1.2.1.4 0 .6l-.4.6-.5.5c-.2.2-.3.4-.1.7.2.3.9 1.5 2 2.4 1.4 1.2 2.5 1.6 2.9 1.8.3.2.5.1.7-.1l1-1.2c.2-.3.4-.2.7-.1l2 1c.3.1.5.2.6.4 0 .1 0 .7-.2 1.4z"/></svg>
								</a>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>

				<?php $lc_email = trim( (string) get_theme_mod( 'lc_contact_email', '' ) ); ?>
				<?php if ( '' !== $lc_email ) : ?>
					<div class="lc-info-card">
						<div class="lc-info-card__icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
						</div>
						<div class="lc-info-card__label"><?php echo esc_html( get_theme_mod( 'lc_contact_email_l', __( 'Email', 'ladycorrections' ) ) ); ?></div>
						<div class="lc-info-card__value"><a href="<?php echo esc_url( 'mailto:' . $lc_email ); ?>"><?php echo esc_html( $lc_email ); ?></a></div>
					</div>
				<?php endif; ?>

				<?php $lc_addr = trim( (string) get_theme_mod( 'lc_contact_addr', '' ) ); ?>
				<?php if ( '' !== $lc_addr ) : ?>
					<div class="lc-info-card">
						<div class="lc-info-card__icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
						</div>
						<div class="lc-info-card__label"><?php echo esc_html( get_theme_mod( 'lc_contact_addr_l', __( 'Address', 'ladycorrections' ) ) ); ?></div>
						<div class="lc-info-card__value"><?php echo nl2br( esc_html( $lc_addr ) ); ?></div>
					</div>
				<?php endif; ?>

				<?php $lc_hours = trim( (string) get_theme_mod( 'lc_contact_hours', '' ) ); ?>
				<?php if ( '' !== $lc_hours ) : ?>
					<div class="lc-info-card">
						<div class="lc-info-card__icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
						</div>
						<div class="lc-info-card__label"><?php echo esc_html( get_theme_mod( 'lc_contact_hours_l', __( 'Working Hours', 'ladycorrections' ) ) ); ?></div>
						<div class="lc-info-card__value"><?php echo nl2br( esc_html( $lc_hours ) ); ?></div>
					</div>
				<?php endif; ?>
			</div>

			<div class="lc-contact-grid">
				<div class="lc-contact-grid__form">
					<?php $lc_form_sc = trim( (string) get_theme_mod( 'lc_contact_form_sc', '' ) ); ?>
					<?php if ( '' !== $lc_form_sc ) : ?>
						<div class="lc-contact-form"><?php echo do_shortcode( $lc_form_sc ); ?></div>
					<?php else : ?>
						<form class="lc-contact-form" method="post" action="<?php echo esc_url( get_permalink() ); ?>" novalidate>
							<?php wp_nonce_field( 'lc_contact', 'lc_contact_nonce' ); ?>
							<?php if ( $lc_contact_sent ) : ?>
								<p class="lc-notice lc-notice--ok" role="status"><?php echo esc_html( get_theme_mod( 'lc_contact_f_ok', __( 'Thank you! Your message has been sent. We\'ll reply soon.', 'ladycorrections' ) ) ); ?></p>
							<?php else : ?>
								<?php if ( '' !== $lc_contact_error ) : ?>
									<p class="lc-notice lc-notice--err" role="alert"><?php echo esc_html( $lc_contact_error ); ?></p>
								<?php endif; ?>
								<div class="lc-field">
									<label for="lc-contact-name"><?php echo esc_html( get_theme_mod( 'lc_contact_f_name', __( 'Your name', 'ladycorrections' ) ) ); ?></label>
									<input class="lc-input" type="text" id="lc-contact-name" name="lc_contact_name" required autocomplete="name" maxlength="120" value="<?php echo esc_attr( $lc_contact_name ); ?>">
								</div>
								<div class="lc-field">
									<label for="lc-contact-email"><?php echo esc_html( get_theme_mod( 'lc_contact_f_email', __( 'Email address', 'ladycorrections' ) ) ); ?></label>
									<input class="lc-input" type="email" id="lc-contact-email" name="lc_contact_email" required autocomplete="email" maxlength="120" value="<?php echo esc_attr( $lc_contact_email ); ?>">
								</div>
								<div class="lc-field">
									<label for="lc-contact-msg"><?php echo esc_html( get_theme_mod( 'lc_contact_f_msg', __( 'Your message', 'ladycorrections' ) ) ); ?></label>
									<textarea class="lc-input" id="lc-contact-msg" name="lc_contact_msg" rows="6" required maxlength="5000"><?php echo esc_textarea( $lc_contact_msg ); ?></textarea>
								</div>
								<button type="submit" class="lc-btn lc-btn--primary"><?php echo esc_html( get_theme_mod( 'lc_contact_f_send', __( 'Send Message', 'ladycorrections' ) ) ); ?></button>
							<?php endif; ?>
						</form>
					<?php endif; ?>
				</div>

				<?php if ( '' !== $lc_whatsapp ) : ?>
					<aside class="lc-contact-grid__side">
						<div class="lc-wa-card">
							<div class="lc-wa-card__icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm5.2 14.2c-.2.6-1.2 1.2-1.7 1.2-.4.1-1 .1-1.6-.1-.4-.1-.9-.3-1.5-.6-2.6-1.1-4.3-3.7-4.4-3.9-.1-.2-1-1.4-1-2.7s.6-1.9.9-2.2c.2-.3.5-.3.7-.3h.5c.2 0 .4 0 .6.5l.9 2.1c.1.2.1.4 0 .6l-.4.6-.5.5c-.2.2-.3.4-.1.7.2.3.9 1.5 2 2.4 1.4 1.2 2.5 1.6 2.9 1.8.3.2.5.1.7-.1l1-1.2c.2-.3.4-.2.7-.1l2 1c.3.1.5.2.6.4 0 .1 0 .7-.2 1.4z"/></svg>
							</div>
							<a class="lc-wa-btn" href="<?php echo esc_url( 'https://wa.me/' . $lc_whatsapp ); ?>" target="_blank" rel="noopener">
								<?php echo esc_html( get_theme_mod( 'lc_contact_wa_btn', __( 'Order on WhatsApp', 'ladycorrections' ) ) ); ?>
							</a>
						</div>
					</aside>
				<?php endif; ?>
			</div>

		</div>
	</section>

	<section class="lc-section lc-section--blush">
		<div class="lc-container">
			<div class="lc-faq-band">
				<h2><?php echo esc_html( get_theme_mod( 'lc_contact_faq_t', __( 'Looking for quick answers?', 'ladycorrections' ) ) ); ?></h2>
				<a class="lc-btn lc-btn--outline" href="<?php echo esc_url( $lc_faq_url ); ?>"><?php echo esc_html( get_theme_mod( 'lc_contact_faq_btn', __( 'Visit the FAQ', 'ladycorrections' ) ) ); ?></a>
			</div>
		</div>
	</section>

</main>

<?php
do_action( 'lc_after_main' );
get_footer();
