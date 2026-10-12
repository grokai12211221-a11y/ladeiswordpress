<?php
/**
 * Template Name: LC FAQ
 *
 * FAQ page: hero band, live search, accessible accordion of 8 Customizer
 * Q&As, contact CTA band.
 *
 * @package LadyCorrections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$lc_faq_defaults = array(
	1 => array(
		__( 'How long does delivery take?', 'ladycorrections' ),
		__( 'Orders are delivered within 2–4 business days across Iraq. Baghdad orders often arrive in 1–2 days.', 'ladycorrections' ),
	),
	2 => array(
		__( 'Do you offer Cash on Delivery?', 'ladycorrections' ),
		__( 'Yes! Pay in cash when your order arrives — no advance payment needed.', 'ladycorrections' ),
	),
	3 => array(
		__( 'Are your products 100% original?', 'ladycorrections' ),
		__( 'Absolutely. We source directly from authorized distributors, and every product is guaranteed authentic.', 'ladycorrections' ),
	),
	4 => array(
		__( 'What is your return policy?', 'ladycorrections' ),
		__( 'You have 7 days from delivery to return unused products in their original packaging for a full refund.', 'ladycorrections' ),
	),
	5 => array(
		__( 'How can I track my order?', 'ladycorrections' ),
		__( 'Use the Track Order page with your order ID and billing email, or message us on WhatsApp anytime.', 'ladycorrections' ),
	),
	6 => array(
		__( 'Do you ship outside Iraq?', 'ladycorrections' ),
		__( 'Currently we deliver within Iraq only.', 'ladycorrections' ),
	),
	7 => array(
		__( 'How do I use a coupon code?', 'ladycorrections' ),
		__( 'Enter your code in the coupon field on the cart page and click Apply.', 'ladycorrections' ),
	),
	8 => array(
		__( 'Which payment methods do you accept?', 'ladycorrections' ),
		__( 'Cash on Delivery and bank transfer. Card payments are coming soon.', 'ladycorrections' ),
	),
);

$lc_faq_items = array();
for ( $lc_i = 1; $lc_i <= 8; $lc_i++ ) {
	$lc_q = trim( (string) get_theme_mod( "lc_faq_{$lc_i}_q", $lc_faq_defaults[ $lc_i ][0] ) );
	if ( '' === $lc_q ) {
		continue;
	}
	$lc_a             = (string) get_theme_mod( "lc_faq_{$lc_i}_a", $lc_faq_defaults[ $lc_i ][1] );
	$lc_faq_items[]   = array(
		'q' => $lc_q,
		'a' => $lc_a,
	);
}

$lc_contact_page = get_page_by_path( 'contact' );
$lc_contact_url  = $lc_contact_page ? get_permalink( $lc_contact_page ) : '#';
$lc_search_ph    = get_theme_mod( 'lc_faq_search_ph', __( 'Search questions…', 'ladycorrections' ) );

get_header();

do_action( 'lc_before_main' );
?>

<main id="lc-main" class="lc-main">

	<div class="lc-pagehead">
		<div class="lc-container">
			<h1><?php echo esc_html( get_theme_mod( 'lc_faq_h', __( 'Frequently Asked Questions', 'ladycorrections' ) ) ); ?></h1>
			<p><?php echo esc_html( get_theme_mod( 'lc_faq_sub', __( 'Quick answers to the questions we hear most.', 'ladycorrections' ) ) ); ?></p>
		</div>
	</div>

	<section class="lc-section">
		<div class="lc-container">

			<div class="lc-faq-search">
				<span class="lc-faq-search__icon" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
				</span>
				<input
					type="search"
					id="lc-faq-search"
					class="lc-input lc-faq-search__input"
					placeholder="<?php echo esc_attr( $lc_search_ph ); ?>"
					aria-label="<?php echo esc_attr( $lc_search_ph ); ?>"
					autocomplete="off"
				>
			</div>

			<div class="lc-faq-list" id="lc-faq-list" data-none-msg="<?php echo esc_attr( get_theme_mod( 'lc_faq_none', __( 'No questions match your search.', 'ladycorrections' ) ) ); ?>">
				<?php foreach ( $lc_faq_items as $lc_idx => $lc_item ) : ?>
					<?php
					$lc_q_id = 'lc-faq-q-' . $lc_idx;
					$lc_a_id = 'lc-faq-a-' . $lc_idx;
					?>
					<div class="lc-faq-item" data-lc-faq-item>
						<h2 class="lc-faq-item__head">
							<button type="button" class="lc-faq-item__btn" id="<?php echo esc_attr( $lc_q_id ); ?>" aria-expanded="false" aria-controls="<?php echo esc_attr( $lc_a_id ); ?>">
								<span class="lc-faq-item__q"><?php echo esc_html( $lc_item['q'] ); ?></span>
								<span class="lc-faq-item__icon" aria-hidden="true"></span>
							</button>
						</h2>
						<div class="lc-faq-item__panel" id="<?php echo esc_attr( $lc_a_id ); ?>" role="region" aria-labelledby="<?php echo esc_attr( $lc_q_id ); ?>" hidden>
							<div class="lc-faq-item__body"><?php echo wp_kses_post( $lc_item['a'] ); ?></div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<p class="lc-faq-none" id="lc-faq-none" role="status" hidden></p>

		</div>
	</section>

	<section class="lc-section lc-section--blush">
		<div class="lc-container">
			<div class="lc-cta-band">
				<h2><?php echo esc_html( get_theme_mod( 'lc_faq_cta_t', __( 'Still have questions?', 'ladycorrections' ) ) ); ?></h2>
				<a class="lc-btn lc-btn--primary" href="<?php echo esc_url( $lc_contact_url ); ?>"><?php echo esc_html( get_theme_mod( 'lc_faq_cta_btn', __( 'Contact Us', 'ladycorrections' ) ) ); ?></a>
			</div>
		</div>
	</section>

</main>

<?php
do_action( 'lc_after_main' );
get_footer();
