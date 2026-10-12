<?php
/**
 * Theme header.
 *
 * @package LadyCorrections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Elementor Pro Theme Builder header location takes over when set. */
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'header' ) ) {
	return;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php do_action( 'lc_before_header' ); ?>

<a class="lc-skip-link" href="#lc-main"><?php esc_html_e( 'Skip to content', 'ladycorrections' ); ?></a>

<?php if ( get_theme_mod( 'lc_announcement_toggle', true ) ) : ?>
	<?php $announce = apply_filters( 'lc_announcement_text', get_theme_mod( 'lc_announcement_text', '' ) ); ?>
	<?php if ( $announce ) : ?>
		<div class="lc-announce" id="lc-announce" role="region" aria-label="<?php esc_attr_e( 'Announcement', 'ladycorrections' ); ?>">
			<p><?php echo wp_kses_post( $announce ); ?></p>
			<button class="lc-announce__close" id="lc-announce-close" aria-label="<?php esc_attr_e( 'Dismiss announcement', 'ladycorrections' ); ?>">&times;</button>
		</div>
	<?php endif; ?>
<?php endif; ?>

<header class="lc-header" id="lc-header">
	<div class="lc-container lc-header__inner">

		<button class="lc-icon-btn lc-hamburger" id="lc-drawer-open" aria-label="<?php esc_attr_e( 'Open menu', 'ladycorrections' ); ?>" aria-expanded="false" aria-controls="lc-drawer">
			<span aria-hidden="true"></span>
		</button>

		<div class="lc-logo">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a class="lc-logo__text" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<?php esc_html_e( 'Lady', 'ladycorrections' ); ?><span><?php esc_html_e( 'Corrections', 'ladycorrections' ); ?></span>
				</a>
			<?php endif; ?>
		</div>

		<nav class="lc-nav" aria-label="<?php esc_attr_e( 'Primary menu', 'ladycorrections' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => '',
					'fallback_cb'    => 'lc_primary_menu_fallback',
					'depth'          => 2,
				)
			);
			?>
		</nav>

		<div class="lc-header__icons">
			<button class="lc-icon-btn" id="lc-search-open" aria-label="<?php esc_attr_e( 'Search', 'ladycorrections' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
			</button>
			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<a class="lc-icon-btn lc-icon-btn--account" href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" aria-label="<?php esc_attr_e( 'My account', 'ladycorrections' ); ?>">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
				</a>
				<a class="lc-icon-btn" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="<?php esc_attr_e( 'View cart', 'ladycorrections' ); ?>">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6"/></svg>
					<?php lc_cart_count_badge(); ?>
				</a>
			<?php endif; ?>
		</div>

	</div>
</header>

<?php do_action( 'lc_after_header' ); ?>

<?php
/**
 * Fallback primary menu when no menu is assigned: Home + Shop (if Woo) + Blog.
 */
function lc_primary_menu_fallback() {
	echo '<ul>';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'ladycorrections' ) . '</a></li>';
	if ( class_exists( 'WooCommerce' ) ) {
		$shop_id = get_option( 'woocommerce_shop_page_id' );
		if ( $shop_id ) {
			echo '<li><a href="' . esc_url( get_permalink( $shop_id ) ) . '">' . esc_html__( 'Shop', 'ladycorrections' ) . '</a></li>';
		}
	}
	if ( get_option( 'page_for_posts' ) ) {
		echo '<li><a href="' . esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) . '">' . esc_html__( 'Blog', 'ladycorrections' ) . '</a></li>';
	}
	echo '</ul>';
}
?>

<div class="lc-drawer-veil" id="lc-drawer-veil" aria-hidden="true"></div>
<aside class="lc-drawer" id="lc-drawer" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Mobile menu', 'ladycorrections' ); ?>">
	<div class="lc-drawer__head">
		<span class="lc-logo__text" style="font-size:20px;"><?php esc_html_e( 'Lady', 'ladycorrections' ); ?><span><?php esc_html_e( 'Corrections', 'ladycorrections' ); ?></span></span>
		<button class="lc-icon-btn" id="lc-drawer-close" aria-label="<?php esc_attr_e( 'Close menu', 'ladycorrections' ); ?>">&times;</button>
	</div>

	<div class="lc-drawer__search">
		<form class="lc-drawer__search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php $lc_search_ph = get_theme_mod( 'lc_drawer_search_ph', __( 'Search products…', 'ladycorrections' ) ); ?>
			<input class="lc-drawer__search-input" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php echo esc_attr( $lc_search_ph ); ?>" aria-label="<?php echo esc_attr( $lc_search_ph ); ?>">
			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<input type="hidden" name="post_type" value="product">
			<?php endif; ?>
			<button class="lc-drawer__search-btn" type="submit" aria-label="<?php esc_attr_e( 'Search', 'ladycorrections' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
			</button>
		</form>
	</div>

	<?php if ( class_exists( 'WooCommerce' ) ) : ?>
		<?php
		$lc_top_cats = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'parent'     => 0,
				'hide_empty' => true,
			)
		);
		?>
		<?php if ( ! is_wp_error( $lc_top_cats ) && ! empty( $lc_top_cats ) ) : ?>
		<div class="lc-drawer__sec">
			<h2 class="lc-drawer__title" id="lc-drawer-cats-h"><?php echo esc_html( get_theme_mod( 'lc_drawer_cats_h', __( 'Shop by Category', 'ladycorrections' ) ) ); ?></h2>
			<ul class="lc-drawer__cats" aria-labelledby="lc-drawer-cats-h">
				<?php foreach ( $lc_top_cats as $lc_top_cat ) : ?>
					<?php
					$lc_child_terms = get_terms(
						array(
							'taxonomy'   => 'product_cat',
							'parent'     => $lc_top_cat->term_id,
							'hide_empty' => true,
						)
					);
					$lc_has_kids = ! is_wp_error( $lc_child_terms ) && ! empty( $lc_child_terms );
					?>
					<li class="lc-drawer__cat">
						<?php if ( $lc_has_kids ) : ?>
							<button class="lc-acc-toggle" type="button" aria-expanded="false" aria-controls="lc-acc-<?php echo esc_attr( (string) $lc_top_cat->term_id ); ?>">
								<span class="lc-acc-toggle__name"><?php echo esc_html( $lc_top_cat->name ); ?></span>
								<span class="lc-acc-count"><?php echo esc_html( (string) $lc_top_cat->count ); ?></span>
								<span class="lc-acc-chev" aria-hidden="true">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
								</span>
							</button>
							<ul class="lc-acc-panel" id="lc-acc-<?php echo esc_attr( (string) $lc_top_cat->term_id ); ?>">
								<?php foreach ( $lc_child_terms as $lc_child_term ) : ?>
									<li>
										<a href="<?php echo esc_url( get_term_link( $lc_child_term ) ); ?>">
											<?php echo esc_html( $lc_child_term->name ); ?>
											<span class="lc-acc-count"><?php echo esc_html( (string) $lc_child_term->count ); ?></span>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php else : ?>
							<a class="lc-acc-toggle lc-acc-toggle--link" href="<?php echo esc_url( get_term_link( $lc_top_cat ) ); ?>">
								<span class="lc-acc-toggle__name"><?php echo esc_html( $lc_top_cat->name ); ?></span>
								<span class="lc-acc-count"><?php echo esc_html( (string) $lc_top_cat->count ); ?></span>
							</a>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>
	<?php endif; ?>

	<nav class="lc-drawer__nav lc-drawer__sec" aria-labelledby="lc-drawer-menu-h">
		<h2 class="lc-drawer__title" id="lc-drawer-menu-h"><?php echo esc_html( get_theme_mod( 'lc_drawer_menu_h', __( 'Menu', 'ladycorrections' ) ) ); ?></h2>
		<?php
		if ( has_nav_menu( 'mobile' ) ) {
			wp_nav_menu(
				array(
					'theme_location' => 'mobile',
					'container'      => false,
					'depth'          => 2,
				)
			);
		} else {
			lc_primary_menu_fallback();
		}
		?>
	</nav>

	<div class="lc-drawer__links">
		<?php if ( class_exists( 'WooCommerce' ) ) : ?>
			<a class="lc-drawer__link" href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
				<?php echo esc_html( get_theme_mod( 'lc_drawer_account', __( 'My Account', 'ladycorrections' ) ) ); ?>
			</a>
		<?php endif; ?>
		<a class="lc-drawer__link" href="#">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21.2l7.8-7.8 1-1a5.5 5.5 0 0 0 0-7.8z"/></svg>
			<?php echo esc_html( get_theme_mod( 'lc_drawer_wishlist', __( 'Wishlist', 'ladycorrections' ) ) ); ?>
		</a>
	</div>

	<div class="lc-drawer__foot">
		<?php
		$lc_wa_number = get_theme_mod( 'lc_whatsapp', '' );
		$lc_wa_number = preg_replace( '/\D+/', '', (string) $lc_wa_number );
		?>
		<?php if ( $lc_wa_number ) : ?>
			<a class="lc-drawer__wa" href="<?php echo esc_url( 'https://wa.me/' . $lc_wa_number . '?text=' . rawurlencode( __( 'Hello LadyCorrections', 'ladycorrections' ) ) ); ?>" target="_blank" rel="noopener">
				<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.5 14.4c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.5 0 1.47 1.07 2.9 1.22 3.1.15.2 2.11 3.22 5.1 4.51.71.31 1.27.49 1.7.63.72.23 1.37.2 1.88.12.57-.09 1.76-.72 2-1.41.25-.7.25-1.29.17-1.41-.07-.13-.27-.2-.57-.35M12.04 2a9.9 9.9 0 0 0-8.4 15.16L2.6 21.4l4.34-1.04A9.9 9.9 0 1 0 12.04 2"/></svg>
				<?php echo esc_html( get_theme_mod( 'lc_drawer_wa', __( 'Chat on WhatsApp', 'ladycorrections' ) ) ); ?>
			</a>
		<?php endif; ?>
	</div>
</aside>

<div class="lc-search-veil" id="lc-search-veil" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Site search', 'ladycorrections' ); ?>">
	<div class="lc-search-box">
		<button class="lc-search-close" id="lc-search-close" aria-label="<?php esc_attr_e( 'Close search', 'ladycorrections' ); ?>">&times;</button>
		<?php get_search_form(); ?>
	</div>
</div>
