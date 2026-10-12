<?php
/**
 * Homepage template — premium 3D SaaS sections, all driven by the Customizer.
 *
 * @package LadyCorrections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

do_action( 'lc_before_main' );

$has_woo  = class_exists( 'WooCommerce' );
$shop_url = $has_woo && get_option( 'woocommerce_shop_page_id' ) ? get_permalink( get_option( 'woocommerce_shop_page_id' ) ) : home_url( '/' );
?>

<?php /* ---------- HERO (3D) ---------- */ ?>
<?php if ( get_theme_mod( 'lc_sec_hero', true ) ) : ?>
<section class="lc-hero" aria-label="<?php esc_attr_e( 'Featured', 'ladycorrections' ); ?>">
	<span class="lc-hero__blob lc-hero__blob--a" aria-hidden="true"></span>
	<span class="lc-hero__blob lc-hero__blob--b" aria-hidden="true"></span>
	<div class="lc-container lc-hero__inner">
		<div class="lc-hero__copy">
			<?php $eyebrow = get_theme_mod( 'lc_hero_kicker', __( 'New collection 2026', 'ladycorrections' ) ); ?>
			<?php if ( $eyebrow ) : ?>
				<span class="lc-hero__eyebrow lc-reveal">
					<span class="lc-hero__eyebrow-dot" aria-hidden="true">&#10022;</span>
					<?php echo esc_html( $eyebrow ); ?>
				</span>
			<?php endif; ?>
			<h1 class="lc-reveal" data-lc-delay="1"><?php echo wp_kses_post( apply_filters( 'lc_hero_heading', get_theme_mod( 'lc_hero_heading', __( 'Beauty that feels <span class="hl">made for you</span>', 'ladycorrections' ) ) ) ); ?></h1>
			<p class="lc-hero__sub lc-reveal" data-lc-delay="2"><?php echo wp_kses_post( get_theme_mod( 'lc_hero_subtext', __( 'Premium skincare, makeup and fragrance — curated for her, delivered across Iraq with cash on delivery.', 'ladycorrections' ) ) ); ?></p>
			<div class="lc-hero__ctas lc-reveal" data-lc-delay="3">
				<?php $cta1t = get_theme_mod( 'lc_hero_cta1_text', __( 'Shop Now', 'ladycorrections' ) ); ?>
				<?php if ( $cta1t ) : ?>
					<a class="lc-btn lc-btn--primary" href="<?php echo esc_url( get_theme_mod( 'lc_hero_cta1_url', '' ) ? get_theme_mod( 'lc_hero_cta1_url', '' ) : $shop_url ); ?>">
						<?php echo esc_html( $cta1t ); ?>
						<span aria-hidden="true">&rarr;</span>
					</a>
				<?php endif; ?>
				<?php $cta2t = get_theme_mod( 'lc_hero_cta2_text', __( 'Explore Categories', 'ladycorrections' ) ); ?>
				<?php if ( $cta2t ) : ?>
					<a class="lc-btn lc-btn--outline" href="<?php echo esc_url( get_theme_mod( 'lc_hero_cta2_url', '' ) ? get_theme_mod( 'lc_hero_cta2_url', '' ) : $shop_url ); ?>">
						<?php echo esc_html( $cta2t ); ?>
					</a>
				<?php endif; ?>
			</div>
			<div class="lc-hero__stats lc-reveal" data-lc-delay="4">
				<div class="lc-hero__stat">
					<span class="lc-hero__stat-icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.3 6.9.8-5.1 4.7 1.4 6.8L12 17.3 5.9 20.6l1.4-6.8L2.2 9.1l6.9-.8z"/></svg>
					</span>
					<div>
						<b><span data-lc-count="4.9" data-lc-decimals="1">4.9</span>/5</b>
						<span class="lc-hero__stars" aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
						<span><?php echo esc_html( get_theme_mod( 'lc_hero_stat1_label', __( 'Average rating', 'ladycorrections' ) ) ); ?></span>
					</div>
				</div>
				<div class="lc-hero__stat">
					<span class="lc-hero__stat-icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
					</span>
					<div>
						<b><?php echo esc_html( get_theme_mod( 'lc_hero_stat2_num', __( '10k+', 'ladycorrections' ) ) ); ?></b>
						<span><?php echo esc_html( get_theme_mod( 'lc_hero_stat2_label', __( 'Happy customers', 'ladycorrections' ) ) ); ?></span>
					</div>
				</div>
				<div class="lc-hero__stat">
					<span class="lc-hero__stat-icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>
					</span>
					<div>
						<b><?php echo esc_html( get_theme_mod( 'lc_hero_stat3_num', __( '100%', 'ladycorrections' ) ) ); ?></b>
						<span><?php echo esc_html( get_theme_mod( 'lc_hero_stat3_label', __( 'Authentic products', 'ladycorrections' ) ) ); ?></span>
					</div>
				</div>
			</div>
		</div>
		<div class="lc-hero__visual lc-reveal" data-lc-delay="2">
			<span class="lc-hero__dots" aria-hidden="true"></span>
			<div class="lc-hero__tilt" data-lc-tilt>
				<div class="lc-hero__card" role="img" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<?php
					$hero_img = get_theme_mod( 'lc_hero_image', '' );
					if ( ! $hero_img ) {
						/* Bundled default hero banner (no Customizer image set). */
						$lc_hero_default_file = LC_DIR . '/assets/img/hero-default.jpg';
						if ( file_exists( $lc_hero_default_file ) ) {
							$hero_img = LC_URI . '/assets/img/hero-default.jpg';
						}
					}
					?>
					<?php if ( $hero_img ) : ?>
						<img class="lc-hero__img" src="<?php echo esc_url( $hero_img ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<?php else : ?>
						<div class="lc-hero__fallback" aria-hidden="true">
							<span class="fb-ring fb-ring--1"></span>
							<span class="fb-ring fb-ring--2"></span>
							<span class="fb-orb fb-orb--1"></span>
							<span class="fb-orb fb-orb--2"></span>
							<span class="fb-mono">LC</span>
							<span class="fb-chip"><?php esc_html_e( 'LadyCorrections', 'ladycorrections' ); ?></span>
						</div>
					<?php endif; ?>
				</div>
				<div class="lc-hero__floats">
					<div class="lc-hero__float lc-hero__float--sale" style="--lc-depth:1.5">
						<span class="lc-hero__float-ic" aria-hidden="true">%</span>
						<div>
							<b><?php echo esc_html( get_theme_mod( 'lc_hero_float_sale', __( '-30%', 'ladycorrections' ) ) ); ?></b>
							<small><?php echo esc_html( get_theme_mod( 'lc_hero_float_sale_label', __( 'Mega Sale live now', 'ladycorrections' ) ) ); ?></small>
						</div>
					</div>
					<div class="lc-hero__float lc-hero__float--rating" style="--lc-depth:1">
						<span class="lc-hero__float-ic" aria-hidden="true">&#9733;</span>
						<div>
							<b><?php echo esc_html( get_theme_mod( 'lc_hero_float_rating_num', __( '4.9 / 5', 'ladycorrections' ) ) ); ?></b>
							<small><span class="lc-hero__stars" aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733;</span> <?php echo esc_html( get_theme_mod( 'lc_hero_float_rating_label', __( '2,400+ reviews', 'ladycorrections' ) ) ); ?></small>
						</div>
					</div>
					<div class="lc-hero__float lc-hero__float--ship" style="--lc-depth:0.7">
						<span class="lc-hero__float-ic" aria-hidden="true">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M1 8h14v9H1zM15 11h4l4 4v2h-8z"/><circle cx="5.5" cy="18.5" r="1.8"/><circle cx="18.5" cy="18.5" r="1.8"/></svg>
						</span>
						<div>
							<b><?php echo esc_html( get_theme_mod( 'lc_hero_float_ship', __( 'Free Delivery', 'ladycorrections' ) ) ); ?></b>
							<small><?php echo esc_html( get_theme_mod( 'lc_hero_float_ship_label', __( 'Across Iraq', 'ladycorrections' ) ) ); ?></small>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="lc-hero__wave" aria-hidden="true">
		<svg viewBox="0 0 1440 56" preserveAspectRatio="none"><path d="M0,32 C240,56 480,8 720,24 C960,40 1200,56 1440,28 L1440,56 L0,56 Z" fill="#4A071E" opacity="0.06"/><path d="M0,40 C260,58 520,20 760,34 C1000,48 1220,58 1440,38 L1440,56 L0,56 Z" fill="#ffffff"/></svg>
	</div>
</section>
<?php endif; ?>

<?php /* ---------- TRUST MARQUEE ---------- */ ?>
<?php if ( get_theme_mod( 'lc_sec_marquee', true ) ) : ?>
	<?php
	$marquee_raw = get_theme_mod( 'lc_marquee_items', '' );
	$marquee_items = array_filter( array_map( 'trim', explode( "\n", $marquee_raw ) ) );
	if ( empty( $marquee_items ) ) {
		$marquee_items = array(
			__( '100% Authentic Products', 'ladycorrections' ),
			__( 'Cash on Delivery', 'ladycorrections' ),
			__( 'Fast Delivery Across Iraq', 'ladycorrections' ),
			__( 'Easy 7-Day Returns', 'ladycorrections' ),
			__( 'New Arrivals Every Week', 'ladycorrections' ),
		);
	}
	?>
<div class="lc-marquee" aria-label="<?php esc_attr_e( 'Store benefits', 'ladycorrections' ); ?>">
	<div class="lc-marquee__track">
		<?php for ( $r = 0; $r < 2; $r++ ) : ?>
			<?php foreach ( $marquee_items as $item ) : ?>
				<span class="lc-marquee__item"><i aria-hidden="true"></i><?php echo esc_html( $item ); ?></span>
			<?php endforeach; ?>
		<?php endfor; ?>
	</div>
</div>
<?php endif; ?>

<?php /* ---------- SHOP BY CATEGORY (3D cards) ---------- */ ?>
<?php if ( get_theme_mod( 'lc_sec_categories', true ) && $has_woo ) : ?>
	<?php
	$cats = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'number'     => 6,
			'exclude'    => array( get_option( 'default_product_cat', 0 ) ),
		)
	);
	?>
	<?php if ( ! is_wp_error( $cats ) && ! empty( $cats ) ) : ?>
	<section class="lc-section" aria-label="<?php esc_attr_e( 'Shop by category', 'ladycorrections' ); ?>">
		<div class="lc-container">
			<div class="lc-section-head lc-reveal">
				<span class="lc-kicker"><?php echo esc_html( get_theme_mod( 'lc_cats_kicker', __( 'Categories', 'ladycorrections' ) ) ); ?></span>
				<h2><?php echo wp_kses_post( get_theme_mod( 'lc_cats_heading', __( 'Shop by <span class="hl">category</span>', 'ladycorrections' ) ) ); ?></h2>
				<p><?php echo esc_html( get_theme_mod( 'lc_cats_sub', __( 'Find exactly what your routine needs.', 'ladycorrections' ) ) ); ?></p>
			</div>
			<div class="lc-cats__grid">
				<?php $ci = 0; ?>
				<?php foreach ( $cats as $cat ) : ?>
					<?php
					$ci++;
					$thumb_id  = get_term_meta( $cat->term_id, 'thumbnail_id', true );
					$thumb_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'medium_large' ) : '';
					?>
					<a class="lc-cat-card lc-reveal" data-lc-delay="<?php echo esc_attr( (string) ( $ci % 3 ) ); ?>" href="<?php echo esc_url( get_term_link( $cat ) ); ?>">
						<span class="lc-cat-card__img" aria-hidden="true">
							<?php if ( $thumb_url ) : ?>
								<img src="<?php echo esc_url( $thumb_url ); ?>" alt="" loading="lazy">
							<?php endif; ?>
						</span>
						<span class="lc-cat-card__arrow" aria-hidden="true">&rarr;</span>
						<span class="lc-cat-card__body">
							<h3><?php echo esc_html( $cat->name ); ?></h3>
							<small>
								<?php
								/* translators: %d: product count. */
								printf( esc_html( _n( '%d product', '%d products', $cat->count, 'ladycorrections' ) ), (int) $cat->count );
								?>
							</small>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>
<?php endif; ?>

<?php /* ---------- FLASH DEALS (3D berry panel) ---------- */ ?>
<?php if ( get_theme_mod( 'lc_sec_flash', true ) && get_theme_mod( 'lc_flash_toggle', true ) ) : ?>
<section class="lc-section" aria-label="<?php esc_attr_e( 'Flash deals', 'ladycorrections' ); ?>">
	<div class="lc-container">
		<div class="lc-flash lc-reveal">
			<span class="lc-flash__orb lc-flash__orb--a" aria-hidden="true"></span>
			<span class="lc-flash__orb lc-flash__orb--b" aria-hidden="true"></span>
			<div class="lc-flash__inner">
				<div>
					<span class="lc-flash__badge">&#9889; <?php esc_html_e( 'Limited time', 'ladycorrections' ); ?></span>
					<h2><?php echo wp_kses_post( get_theme_mod( 'lc_flash_title', __( 'Flash <span class="hl">Deals</span>', 'ladycorrections' ) ) ); ?></h2>
					<p><?php echo wp_kses_post( get_theme_mod( 'lc_flash_subtext', __( 'Up to 50% off selected bestsellers. Hurry — when the timer ends, so do the deals.', 'ladycorrections' ) ) ); ?></p>
					<div class="lc-countdown" id="lc-countdown" role="timer" aria-label="<?php esc_attr_e( 'Time left', 'ladycorrections' ); ?>">
						<div class="lc-countdown__cell"><b data-lc-days>00</b><span><?php esc_html_e( 'Days', 'ladycorrections' ); ?></span></div>
						<div class="lc-countdown__cell"><b data-lc-hours>00</b><span><?php esc_html_e( 'Hours', 'ladycorrections' ); ?></span></div>
						<div class="lc-countdown__cell"><b data-lc-mins>00</b><span><?php esc_html_e( 'Mins', 'ladycorrections' ); ?></span></div>
						<div class="lc-countdown__cell"><b data-lc-secs>00</b><span><?php esc_html_e( 'Secs', 'ladycorrections' ); ?></span></div>
					</div>
					<a class="lc-btn lc-btn--white" href="<?php echo esc_url( get_theme_mod( 'lc_flash_link', '' ) ? get_theme_mod( 'lc_flash_link', '' ) : $shop_url ); ?>">
						<?php echo esc_html( get_theme_mod( 'lc_flash_btn', __( 'Shop the deals', 'ladycorrections' ) ) ); ?>
						<span aria-hidden="true">&rarr;</span>
					</a>
				</div>
				<?php if ( $has_woo ) : ?>
				<div class="lc-flash__products">
					<?php echo do_shortcode( '[products limit="4" columns="2" on_sale="1" orderby="popularity"]' ); ?>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<?php /* ---------- BEST SELLERS ---------- */ ?>
<?php if ( get_theme_mod( 'lc_sec_bestsellers', true ) && $has_woo ) : ?>
<section class="lc-section lc-section--blush" aria-label="<?php esc_attr_e( 'Best sellers', 'ladycorrections' ); ?>">
	<div class="lc-container">
		<div class="lc-section-head lc-reveal">
			<span class="lc-kicker"><?php echo esc_html( get_theme_mod( 'lc_best_kicker', __( 'Loved by customers', 'ladycorrections' ) ) ); ?></span>
			<h2><?php echo wp_kses_post( get_theme_mod( 'lc_best_heading', __( 'Best <span class="hl">sellers</span>', 'ladycorrections' ) ) ); ?></h2>
			<p><?php echo esc_html( get_theme_mod( 'lc_best_sub', __( 'The products everyone keeps reordering.', 'ladycorrections' ) ) ); ?></p>
		</div>
		<div class="lc-reveal" data-lc-delay="1">
			<?php echo do_shortcode( '[products limit="8" columns="4" orderby="popularity"]' ); ?>
		</div>
		<div class="lc-text-center lc-reveal" style="margin-top:36px;">
			<a class="lc-btn lc-btn--dark" href="<?php echo esc_url( $shop_url ); ?>">
				<?php echo esc_html( get_theme_mod( 'lc_best_btn', __( 'View all products', 'ladycorrections' ) ) ); ?>
				<span aria-hidden="true">&rarr;</span>
			</a>
		</div>
	</div>
</section>
<?php endif; ?>

<?php /* ---------- PROMO BANNERS (3D) ---------- */ ?>
<?php if ( get_theme_mod( 'lc_sec_promos', true ) ) : ?>
<section class="lc-section" style="padding-top:0;" aria-label="<?php esc_attr_e( 'Promotions', 'ladycorrections' ); ?>">
	<div class="lc-container lc-promos__grid">
		<?php for ( $i = 1; $i <= 2; $i++ ) : ?>
			<?php
			$p_tag   = get_theme_mod( "lc_promo{$i}_tag", $i === 1 ? __( 'Bestseller', 'ladycorrections' ) : __( 'New In', 'ladycorrections' ) );
			$p_title = get_theme_mod( "lc_promo{$i}_title", $i === 1 ? __( 'Skincare Essentials', 'ladycorrections' ) : __( 'Makeup Must-Haves', 'ladycorrections' ) );
			$p_text  = get_theme_mod( "lc_promo{$i}_text", $i === 1 ? __( 'Glow routines from 15,000 IQD', 'ladycorrections' ) : __( 'Bestselling shades back in stock', 'ladycorrections' ) );
			$p_link  = get_theme_mod( "lc_promo{$i}_link", '' );
			$p_img   = get_theme_mod( "lc_promo{$i}_image", '' );
			?>
			<a class="lc-promo lc-promo--<?php echo $i === 1 ? 'a' : 'b'; ?> lc-reveal" data-lc-delay="<?php echo esc_attr( (string) ( $i - 1 ) ); ?>" href="<?php echo esc_url( $p_link ? $p_link : $shop_url ); ?>">
				<?php if ( $p_img ) : ?>
					<img class="lc-promo__img" src="<?php echo esc_url( $p_img ); ?>" alt="<?php echo esc_attr( $p_title ); ?>" loading="lazy">
				<?php endif; ?>
				<span class="lc-promo__shape lc-promo__shape--1" aria-hidden="true"></span>
				<span class="lc-promo__shape lc-promo__shape--2" aria-hidden="true"></span>
				<span class="lc-promo__body">
					<?php if ( $p_tag ) : ?><span class="lc-promo__tag"><?php echo esc_html( $p_tag ); ?></span><?php endif; ?>
					<h3><?php echo esc_html( $p_title ); ?></h3>
					<p><?php echo esc_html( $p_text ); ?></p>
					<span class="lc-btn lc-btn--white lc-btn--sm"><?php echo esc_html( get_theme_mod( 'lc_promo_btn', __( 'Shop now', 'ladycorrections' ) ) ); ?></span>
				</span>
			</a>
		<?php endfor; ?>
	</div>
</section>
<?php endif; ?>

<?php /* ---------- BRAND STORY (stacked 3D) ---------- */ ?>
<?php if ( get_theme_mod( 'lc_sec_story', true ) ) : ?>
<section class="lc-section lc-section--blush" aria-label="<?php esc_attr_e( 'Our story', 'ladycorrections' ); ?>">
	<div class="lc-container lc-story__inner">
		<div class="lc-story__media lc-reveal">
			<?php $story_img = get_theme_mod( 'lc_story_image', '' ); ?>
			<div class="lc-story__img-main">
				<?php if ( $story_img ) : ?>
					<img src="<?php echo esc_url( $story_img ); ?>" alt="<?php echo esc_attr( get_theme_mod( 'lc_story_heading', '' ) ); ?>" loading="lazy">
				<?php else : ?>
					<div class="lc-story__img-fallback" aria-hidden="true">LC</div>
				<?php endif; ?>
			</div>
			<?php $story_img2 = get_theme_mod( 'lc_story_image2', '' ); ?>
			<div class="lc-story__img-second">
				<?php if ( $story_img2 ) : ?>
					<img src="<?php echo esc_url( $story_img2 ); ?>" alt="" loading="lazy">
				<?php else : ?>
					<div class="lc-story__img-fallback" aria-hidden="true">&#10022;</div>
				<?php endif; ?>
			</div>
			<div class="lc-story__badge">
				<b><span data-lc-count="<?php echo esc_attr( get_theme_mod( 'lc_story_badge_num', '10' ) ); ?>">10</span>k+</b>
				<span><?php echo esc_html( get_theme_mod( 'lc_story_badge_label', __( 'happy customers across Iraq', 'ladycorrections' ) ) ); ?></span>
			</div>
		</div>
		<div class="lc-reveal" data-lc-delay="1">
			<span class="lc-kicker"><?php echo esc_html( get_theme_mod( 'lc_story_kicker', __( 'Our story', 'ladycorrections' ) ) ); ?></span>
			<h2><?php echo wp_kses_post( get_theme_mod( 'lc_story_heading', __( 'Made for her, with <span class="hl">love</span> in every detail', 'ladycorrections' ) ) ); ?></h2>
			<p><?php echo wp_kses_post( get_theme_mod( 'lc_story_text', __( 'LadyCorrections curates premium beauty essentials — authentic products, fair prices, and delivery you can trust, anywhere in Iraq.', 'ladycorrections' ) ) ); ?></p>
			<ul class="lc-story__points">
				<li><span class="tick" aria-hidden="true">&#10003;</span><?php esc_html_e( 'Curated authentic brands', 'ladycorrections' ); ?></li>
				<li><span class="tick" aria-hidden="true">&#10003;</span><?php esc_html_e( 'Fair, honest pricing in IQD', 'ladycorrections' ); ?></li>
				<li><span class="tick" aria-hidden="true">&#10003;</span><?php esc_html_e( 'Support in English and Arabic', 'ladycorrections' ); ?></li>
			</ul>
			<a class="lc-btn lc-btn--primary" href="<?php echo esc_url( $shop_url ); ?>">
				<?php echo esc_html( get_theme_mod( 'lc_story_btn', __( 'Start shopping', 'ladycorrections' ) ) ); ?>
				<span aria-hidden="true">&rarr;</span>
			</a>
		</div>
	</div>
</section>
<?php endif; ?>

<?php /* ---------- TESTIMONIALS (glass 3D) ---------- */ ?>
<?php if ( get_theme_mod( 'lc_sec_testimonials', true ) ) : ?>
<section class="lc-section" aria-label="<?php esc_attr_e( 'Testimonials', 'ladycorrections' ); ?>">
	<div class="lc-container">
		<div class="lc-section-head lc-reveal">
			<span class="lc-kicker"><?php echo esc_html( get_theme_mod( 'lc_testi_kicker', __( 'Reviews', 'ladycorrections' ) ) ); ?></span>
			<h2><?php echo wp_kses_post( get_theme_mod( 'lc_testi_heading', __( 'What our <span class="hl">customers</span> say', 'ladycorrections' ) ) ); ?></h2>
		</div>
		<div class="lc-testis__grid">
			<?php $ti = 0; ?>
			<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
				<?php
				$t_text = get_theme_mod( "lc_testi_{$i}_text", '' );
				$t_name = get_theme_mod( "lc_testi_{$i}_name", '' );
				$t_role = get_theme_mod( "lc_testi_{$i}_role", '' );
				?>
				<?php if ( $t_text ) : ?>
					<?php $ti++; ?>
				<article class="lc-testi lc-reveal" data-lc-delay="<?php echo esc_attr( (string) ( $ti % 3 ) ); ?>">
					<div class="lc-testi__stars" aria-label="<?php esc_attr_e( 'Rated 5 out of 5', 'ladycorrections' ); ?>">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
					<p>&ldquo;<?php echo wp_kses_post( $t_text ); ?>&rdquo;</p>
					<div class="lc-testi__who">
						<span class="lc-testi__avatar" aria-hidden="true"><?php echo esc_html( function_exists( 'mb_substr' ) ? mb_substr( $t_name, 0, 1 ) : substr( $t_name, 0, 1 ) ); ?></span>
						<div>
							<strong><?php echo esc_html( $t_name ); ?></strong>
							<span><?php echo esc_html( $t_role ); ?></span>
						</div>
					</div>
				</article>
				<?php endif; ?>
			<?php endfor; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php /* ---------- NEWSLETTER (3D gradient panel) ---------- */ ?>
<?php if ( get_theme_mod( 'lc_sec_newsletter', true ) ) : ?>
<section class="lc-section lc-news" aria-label="<?php esc_attr_e( 'Newsletter', 'ladycorrections' ); ?>">
	<div class="lc-container">
		<div class="lc-news__box lc-reveal">
			<span class="lc-news__shape lc-news__shape--1" aria-hidden="true"></span>
			<span class="lc-news__shape lc-news__shape--2" aria-hidden="true"></span>
			<span class="lc-news__shape lc-news__shape--3" aria-hidden="true"></span>
			<h2><?php echo esc_html( get_theme_mod( 'lc_news_heading', __( 'Get 10% off your first order', 'ladycorrections' ) ) ); ?></h2>
			<p><?php echo esc_html( get_theme_mod( 'lc_news_text', __( 'Join our newsletter for new arrivals, beauty tips and exclusive deals.', 'ladycorrections' ) ) ); ?></p>
			<form class="lc-news__form" id="lc-newsletter-form" action="#" method="post" novalidate>
				<label class="screen-reader-text" for="lc-news-email"><?php esc_html_e( 'Email address', 'ladycorrections' ); ?></label>
				<input type="email" id="lc-news-email" name="email" class="lc-input" placeholder="<?php echo esc_attr( get_theme_mod( 'lc_news_ph', __( 'Your email address', 'ladycorrections' ) ) ); ?>" required>
				<button type="submit" class="lc-btn lc-btn--dark"><?php echo esc_html( get_theme_mod( 'lc_news_btn', __( 'Subscribe', 'ladycorrections' ) ) ); ?></button>
			</form>
			<p class="lc-news__msg" id="lc-newsletter-msg" role="status" aria-live="polite"></p>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
do_action( 'lc_after_main' );
get_footer();
