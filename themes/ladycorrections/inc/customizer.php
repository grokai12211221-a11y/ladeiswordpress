<?php
/**
 * LadyCorrections Customizer options.
 *
 * @package LadyCorrections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---------- Sanitizers ---------- */
function lc_sanitize_checkbox( $value ) {
	return (bool) $value;
}
function lc_sanitize_text( $value ) {
	return wp_kses_post( $value );
}

/**
 * Helper: register a simple text/textarea/url control.
 */
function lc_add_text_option( $wp_customize, $id, $label, $default, $section, $type = 'text', $sanitize = 'sanitize_text_field', $description = '' ) {
	$wp_customize->add_setting(
		$id,
		array(
			'default'           => $default,
			'sanitize_callback' => $sanitize,
		)
	);
	$args = array(
		'label'   => $label,
		'section' => $section,
		'type'    => $type,
	);
	if ( $description ) {
		$args['description'] = $description;
	}
	$wp_customize->add_control( $id, $args );
}

/**
 * Register Customizer settings.
 */
function lc_customize_register( $wp_customize ) {

	$wp_customize->add_panel(
		'lc_panel',
		array(
			'title'       => __( 'LadyCorrections', 'ladycorrections' ),
			'description' => __( 'Design and content for your whole store — colors, homepage sections, texts and footer. Everything here updates the live preview instantly.', 'ladycorrections' ),
			'priority'    => 30,
		)
	);

	/* ----- Getting started ----- */
	$wp_customize->add_section(
		'lc_start',
		array(
			'title'       => __( 'Getting Started', 'ladycorrections' ),
			'description' => sprintf(
				/* translators: %s: line breaks between steps. */
				__( 'Welcome! Set up your store in 5 steps:%s1. Install WooCommerce — Plugins → Add New → search “WooCommerce” → Install & Activate.%s2. Install Elementor — same way, for drag & drop page design.%s3. Create menus — Appearance → Menus, then assign them to the Primary, Mobile Drawer and Footer locations.%s4. Set your hero — open the Hero section below to change the image, headline and buttons.%s5. Add products — WooCommerce → Products → Add New (or Products → Import).%sEverything visual on the site is controlled from the sections below — no code needed.', 'ladycorrections' ),
				'<br><br><strong>',
				'</strong><br><strong>',
				'</strong><br><strong>',
				'</strong><br><strong>',
				'</strong><br><strong>',
				'</strong><br><br>'
			),
			'panel'       => 'lc_panel',
			'priority'    => 1,
		)
	);

	/* ----- Colors ----- */
	$wp_customize->add_section(
		'lc_colors',
		array(
			'title'       => __( 'Brand Colors', 'ladycorrections' ),
			'description' => __( 'Your brand palette. These two colors recolor buttons, gradients, the footer and dark sections across the whole site.', 'ladycorrections' ),
			'panel'       => 'lc_panel',
			'priority'    => 10,
		)
	);
	$wp_customize->add_setting(
		'lc_primary_color',
		array(
			'default'           => '#E91663',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lc_primary_color',
			array(
				'label'   => __( 'Primary color (buttons, accents)', 'ladycorrections' ),
				'section' => 'lc_colors',
			)
		)
	);
	$wp_customize->add_setting(
		'lc_berry_color',
		array(
			'default'           => '#4A071E',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lc_berry_color',
			array(
				'label'   => __( 'Berry color (footer, dark sections)', 'ladycorrections' ),
				'section' => 'lc_colors',
			)
		)
	);

	/* ----- Announcement bar ----- */
	$wp_customize->add_section(
		'lc_announce',
		array(
			'title'       => __( 'Announcement Bar', 'ladycorrections' ),
			'description' => __( 'A slim bar above the header — perfect for sales, coupon codes and delivery notes.', 'ladycorrections' ),
			'panel'       => 'lc_panel',
			'priority'    => 20,
		)
	);
	$wp_customize->add_setting(
		'lc_announcement_toggle',
		array(
			'default'           => true,
			'sanitize_callback' => 'lc_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'lc_announcement_toggle',
		array(
			'label'   => __( 'Show announcement bar', 'ladycorrections' ),
			'section' => 'lc_announce',
			'type'    => 'checkbox',
		)
	);
	$wp_customize->add_setting(
		'lc_announcement_text',
		array(
			'default'           => __( 'Free delivery on orders over 50,000 IQD — use code WELCOME10 for 10% off', 'ladycorrections' ),
			'sanitize_callback' => 'lc_sanitize_text',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'lc_announcement_text',
		array(
			'label'   => __( 'Announcement text', 'ladycorrections' ),
			'section' => 'lc_announce',
			'type'    => 'text',
		)
	);

	/* ----- Homepage sections toggles ----- */
	$wp_customize->add_section(
		'lc_home_sections',
		array(
			'title'       => __( 'Homepage Sections', 'ladycorrections' ),
			'description' => __( 'Turn homepage sections on or off. The content of each section lives in its own section below.', 'ladycorrections' ),
			'panel'       => 'lc_panel',
			'priority'    => 30,
		)
	);
	$sections = array(
		'lc_sec_hero'         => __( 'Hero', 'ladycorrections' ),
		'lc_sec_marquee'      => __( 'Trust marquee strip', 'ladycorrections' ),
		'lc_sec_trust'        => __( 'Trust badges', 'ladycorrections' ),
		'lc_sec_categories'   => __( 'Shop by category', 'ladycorrections' ),
		'lc_sec_bestsellers'  => __( 'Best sellers', 'ladycorrections' ),
		'lc_sec_flash'        => __( 'Flash deals', 'ladycorrections' ),
		'lc_sec_promos'       => __( 'Promo banners', 'ladycorrections' ),
		'lc_sec_story'        => __( 'Brand story', 'ladycorrections' ),
		'lc_sec_testimonials' => __( 'Testimonials', 'ladycorrections' ),
		'lc_sec_newsletter'   => __( 'Newsletter', 'ladycorrections' ),
	);
	foreach ( $sections as $id => $label ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => true,
				'sanitize_callback' => 'lc_sanitize_checkbox',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => sprintf( __( 'Show: %s', 'ladycorrections' ), $label ),
				'section' => 'lc_home_sections',
				'type'    => 'checkbox',
			)
		);
	}

	/* ----- Hero ----- */
	$wp_customize->add_section(
		'lc_hero',
		array(
			'title'       => __( 'Hero', 'ladycorrections' ),
			'description' => __( 'The big banner at the top of the homepage — headline, buttons, image and floating cards.', 'ladycorrections' ),
			'panel'       => 'lc_panel',
			'priority'    => 40,
		)
	);
	$hero_fields = array(
		'lc_hero_kicker'     => array( __( 'Hero badge text', 'ladycorrections' ), __( 'New collection 2026', 'ladycorrections' ), 'text' ),
		'lc_hero_heading'    => array( __( 'Hero heading', 'ladycorrections' ), __( 'Beauty that feels <span class="hl">made for you</span>', 'ladycorrections' ), 'textarea' ),
		'lc_hero_subtext'    => array( __( 'Hero subtext', 'ladycorrections' ), __( 'Premium skincare, makeup and fragrance — curated for her, delivered across Iraq with cash on delivery.', 'ladycorrections' ), 'textarea' ),
		'lc_hero_cta1_text'  => array( __( 'Primary button text', 'ladycorrections' ), __( 'Shop Now', 'ladycorrections' ), 'text' ),
		'lc_hero_cta1_url'   => array( __( 'Primary button link', 'ladycorrections' ), '', 'url' ),
		'lc_hero_cta2_text'  => array( __( 'Secondary button text', 'ladycorrections' ), __( 'Explore Categories', 'ladycorrections' ), 'text' ),
		'lc_hero_cta2_url'   => array( __( 'Secondary button link', 'ladycorrections' ), '', 'url' ),
	);
	foreach ( $hero_fields as $id => $field ) {
		list( $label, $default, $type ) = $field;
		$args = array(
			'default'           => $default,
			'sanitize_callback' => ( 'url' === $type ) ? 'esc_url_raw' : 'lc_sanitize_text',
		);
		if ( 'lc_hero_heading' === $id ) {
			$args['transport'] = 'postMessage';
		}
		$wp_customize->add_setting( $id, $args );
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $label,
				'section' => 'lc_hero',
				'type'    => $type,
			)
		);
	}
	$wp_customize->add_setting(
		'lc_hero_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'lc_hero_image',
			array(
				'label'       => __( 'Hero image', 'ladycorrections' ),
				'description' => __( 'Upload your own banner. If empty, the theme shows its built-in premium banner image.', 'ladycorrections' ),
				'section'     => 'lc_hero',
			)
		)
	);

	/* ----- Hero trust stats (3) ----- */
	$hero_stats = array(
		'lc_hero_stat1_label' => array( __( 'Stat 1 label (under 4.9/5)', 'ladycorrections' ), __( 'Average rating', 'ladycorrections' ) ),
		'lc_hero_stat2_num'   => array( __( 'Stat 2 number', 'ladycorrections' ), __( '10k+', 'ladycorrections' ) ),
		'lc_hero_stat2_label' => array( __( 'Stat 2 label', 'ladycorrections' ), __( 'Happy customers', 'ladycorrections' ) ),
		'lc_hero_stat3_num'   => array( __( 'Stat 3 number', 'ladycorrections' ), __( '100%', 'ladycorrections' ) ),
		'lc_hero_stat3_label' => array( __( 'Stat 3 label', 'ladycorrections' ), __( 'Authentic products', 'ladycorrections' ) ),
	);
	foreach ( $hero_stats as $id => $field ) {
		list( $label, $default ) = $field;
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $default,
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $label,
				'section' => 'lc_hero',
				'type'    => 'text',
			)
		);
	}

	/* ----- Hero floating cards ----- */
	$hero_floats = array(
		'lc_hero_float_sale'         => array( __( 'Sale card number', 'ladycorrections' ), __( '-30%', 'ladycorrections' ) ),
		'lc_hero_float_sale_label'   => array( __( 'Sale card label', 'ladycorrections' ), __( 'Mega Sale live now', 'ladycorrections' ) ),
		'lc_hero_float_rating_num'   => array( __( 'Rating card number', 'ladycorrections' ), __( '4.9 / 5', 'ladycorrections' ) ),
		'lc_hero_float_rating_label' => array( __( 'Rating card label', 'ladycorrections' ), __( '2,400+ reviews', 'ladycorrections' ) ),
		'lc_hero_float_ship'         => array( __( 'Delivery card title', 'ladycorrections' ), __( 'Free Delivery', 'ladycorrections' ) ),
		'lc_hero_float_ship_label'   => array( __( 'Delivery card label', 'ladycorrections' ), __( 'Across Iraq', 'ladycorrections' ) ),
	);
	foreach ( $hero_floats as $id => $field ) {
		list( $label, $default ) = $field;
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $default,
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $label,
				'section' => 'lc_hero',
				'type'    => 'text',
			)
		);
	}

	/* ----- Trust marquee ----- */
	$wp_customize->add_section(
		'lc_marquee',
		array(
			'title'       => __( 'Trust Marquee', 'ladycorrections' ),
			'description' => __( 'The scrolling benefits strip under the hero. One item per line.', 'ladycorrections' ),
			'panel'       => 'lc_panel',
			'priority'    => 50,
		)
	);
	$wp_customize->add_setting(
		'lc_marquee_items',
		array(
			'default'           => implode(
				"\n",
				array(
					__( '100% Authentic Products', 'ladycorrections' ),
					__( 'Cash on Delivery', 'ladycorrections' ),
					__( 'Fast Delivery Across Iraq', 'ladycorrections' ),
					__( 'Easy 7-Day Returns', 'ladycorrections' ),
					__( 'New Arrivals Every Week', 'ladycorrections' ),
				)
			),
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'lc_marquee_items',
		array(
			'label'       => __( 'Marquee items', 'ladycorrections' ),
			'description' => __( 'One item per line.', 'ladycorrections' ),
			'section'     => 'lc_marquee',
			'type'        => 'textarea',
		)
	);

	/* ----- Flash deals ----- */
	$wp_customize->add_section(
		'lc_flash',
		array(
			'title'       => __( 'Flash Deals', 'ladycorrections' ),
			'description' => __( 'Limited-time deals banner with a live countdown timer.', 'ladycorrections' ),
			'panel'       => 'lc_panel',
			'priority'    => 60,
		)
	);
	$wp_customize->add_setting(
		'lc_flash_toggle',
		array(
			'default'           => true,
			'sanitize_callback' => 'lc_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'lc_flash_toggle',
		array(
			'label'   => __( 'Enable flash deals banner', 'ladycorrections' ),
			'section' => 'lc_flash',
			'type'    => 'checkbox',
		)
	);
	$wp_customize->add_setting(
		'lc_flash_title',
		array(
			'default'           => __( 'Flash Deals', 'ladycorrections' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'lc_flash_title',
		array(
			'label'   => __( 'Title', 'ladycorrections' ),
			'section' => 'lc_flash',
			'type'    => 'text',
		)
	);
	$wp_customize->add_setting(
		'lc_flash_subtext',
		array(
			'default'           => __( 'Up to 50% off selected bestsellers. Hurry — when the timer ends, so do the deals.', 'ladycorrections' ),
			'sanitize_callback' => 'lc_sanitize_text',
		)
	);
	$wp_customize->add_control(
		'lc_flash_subtext',
		array(
			'label'   => __( 'Subtext', 'ladycorrections' ),
			'section' => 'lc_flash',
			'type'    => 'textarea',
		)
	);
	$wp_customize->add_setting(
		'lc_flash_end',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'lc_flash_end',
		array(
			'label'       => __( 'Deal end date & time', 'ladycorrections' ),
			'description' => __( 'Format: YYYY-MM-DDTHH:MM, e.g. 2026-12-31T23:59', 'ladycorrections' ),
			'section'     => 'lc_flash',
			'type'        => 'datetime-local',
		)
	);
	$wp_customize->add_setting(
		'lc_flash_link',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'lc_flash_link',
		array(
			'label'   => __( 'Button link (shop page)', 'ladycorrections' ),
			'section' => 'lc_flash',
			'type'    => 'url',
		)
	);

	/* ----- Promo banners ----- */
	$wp_customize->add_section(
		'lc_promos',
		array(
			'title'       => __( 'Promo Banners', 'ladycorrections' ),
			'description' => __( 'Two promotional banners on the homepage.', 'ladycorrections' ),
			'panel'       => 'lc_panel',
			'priority'    => 70,
		)
	);
	for ( $i = 1; $i <= 2; $i++ ) {
		$wp_customize->add_setting(
			"lc_promo{$i}_tag",
			array(
				'default'           => $i === 1 ? __( 'Bestseller', 'ladycorrections' ) : __( 'New In', 'ladycorrections' ),
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			"lc_promo{$i}_tag",
			array(
				'label'   => sprintf( __( 'Banner %d tag pill', 'ladycorrections' ), $i ),
				'section' => 'lc_promos',
				'type'    => 'text',
			)
		);
		$wp_customize->add_setting(
			"lc_promo{$i}_title",
			array(
				'default'           => $i === 1 ? __( 'Skincare Essentials', 'ladycorrections' ) : __( 'Makeup Must-Haves', 'ladycorrections' ),
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			"lc_promo{$i}_title",
			array(
				'label'   => sprintf( __( 'Banner %d title', 'ladycorrections' ), $i ),
				'section' => 'lc_promos',
				'type'    => 'text',
			)
		);
		$wp_customize->add_setting(
			"lc_promo{$i}_text",
			array(
				'default'           => $i === 1 ? __( 'Glow routines from 15,000 IQD', 'ladycorrections' ) : __( 'Bestselling shades back in stock', 'ladycorrections' ),
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			"lc_promo{$i}_text",
			array(
				'label'   => sprintf( __( 'Banner %d text', 'ladycorrections' ), $i ),
				'section' => 'lc_promos',
				'type'    => 'text',
			)
		);
		$wp_customize->add_setting(
			"lc_promo{$i}_link",
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			"lc_promo{$i}_link",
			array(
				'label'   => sprintf( __( 'Banner %d link', 'ladycorrections' ), $i ),
				'section' => 'lc_promos',
				'type'    => 'url',
			)
		);
		$wp_customize->add_setting(
			"lc_promo{$i}_image",
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				"lc_promo{$i}_image",
				array(
					'label'   => sprintf( __( 'Banner %d image', 'ladycorrections' ), $i ),
					'section' => 'lc_promos',
				)
			)
		);
	}

	/* ----- Brand story ----- */
	$wp_customize->add_section(
		'lc_story',
		array(
			'title'       => __( 'Brand Story', 'ladycorrections' ),
			'description' => __( 'The “about the brand” split section with layered images.', 'ladycorrections' ),
			'panel'       => 'lc_panel',
			'priority'    => 80,
		)
	);
	$wp_customize->add_setting(
		'lc_story_kicker',
		array(
			'default'           => __( 'Our story', 'ladycorrections' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'lc_story_kicker',
		array(
			'label'   => __( 'Kicker', 'ladycorrections' ),
			'section' => 'lc_story',
			'type'    => 'text',
		)
	);
	$wp_customize->add_setting(
		'lc_story_heading',
		array(
			'default'           => __( 'Made for her, with love in every detail', 'ladycorrections' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'lc_story_heading',
		array(
			'label'   => __( 'Heading', 'ladycorrections' ),
			'section' => 'lc_story',
			'type'    => 'text',
		)
	);
	$wp_customize->add_setting(
		'lc_story_text',
		array(
			'default'           => __( 'LadyCorrections curates premium beauty essentials — authentic products, fair prices, and delivery you can trust, anywhere in Iraq.', 'ladycorrections' ),
			'sanitize_callback' => 'lc_sanitize_text',
		)
	);
	$wp_customize->add_control(
		'lc_story_text',
		array(
			'label'   => __( 'Text', 'ladycorrections' ),
			'section' => 'lc_story',
			'type'    => 'textarea',
		)
	);
	$wp_customize->add_setting(
		'lc_story_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'lc_story_image',
			array(
				'label'   => __( 'Story image (main)', 'ladycorrections' ),
				'section' => 'lc_story',
			)
		)
	);
	$wp_customize->add_setting(
		'lc_story_image2',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'lc_story_image2',
			array(
				'label'       => __( 'Story image (layered, smaller)', 'ladycorrections' ),
				'description' => __( 'Optional second image stacked over the main one.', 'ladycorrections' ),
				'section'     => 'lc_story',
			)
		)
	);
	$wp_customize->add_setting(
		'lc_story_badge_num',
		array(
			'default'           => '10',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'lc_story_badge_num',
		array(
			'label'       => __( 'Badge number', 'ladycorrections' ),
			'description' => __( 'Shown as "<number>k+", e.g. 10 becomes 10k+.', 'ladycorrections' ),
			'section'     => 'lc_story',
			'type'        => 'text',
		)
	);
	$wp_customize->add_setting(
		'lc_story_badge_label',
		array(
			'default'           => __( 'happy customers across Iraq', 'ladycorrections' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'lc_story_badge_label',
		array(
			'label'   => __( 'Badge label', 'ladycorrections' ),
			'section' => 'lc_story',
			'type'    => 'text',
		)
	);

	/* ----- Testimonials (3 slots) ----- */
	$wp_customize->add_section(
		'lc_testimonials',
		array(
			'title'       => __( 'Testimonials', 'ladycorrections' ),
			'description' => __( 'Customer reviews shown on the homepage.', 'ladycorrections' ),
			'panel'       => 'lc_panel',
			'priority'    => 90,
		)
	);
	lc_add_text_option( $wp_customize, 'lc_testi_kicker', __( 'Section kicker', 'ladycorrections' ), __( 'Reviews', 'ladycorrections' ), 'lc_testimonials' );
	lc_add_text_option( $wp_customize, 'lc_testi_heading', __( 'Section heading', 'ladycorrections' ), __( 'What our <span class="hl">customers</span> say', 'ladycorrections' ), 'lc_testimonials', 'textarea', 'lc_sanitize_text' );
	$default_testis = array(
		1 => array( __( 'The quality is amazing and delivery was so fast. My go-to beauty store now!', 'ladycorrections' ), __( 'Sara A.', 'ladycorrections' ), __( 'Baghdad', 'ladycorrections' ) ),
		2 => array( __( 'Original products at fair prices. Cash on delivery makes it so easy.', 'ladycorrections' ), __( 'Noor H.', 'ladycorrections' ), __( 'Erbil', 'ladycorrections' ) ),
		3 => array( __( 'Beautiful packaging and lovely shades. I keep coming back for more.', 'ladycorrections' ), __( 'Lina M.', 'ladycorrections' ), __( 'Basra', 'ladycorrections' ) ),
	);
	foreach ( $default_testis as $i => $testi ) {
		list( $text, $name, $role ) = $testi;
		$wp_customize->add_setting(
			"lc_testi_{$i}_text",
			array(
				'default'           => $text,
				'sanitize_callback' => 'lc_sanitize_text',
			)
		);
		$wp_customize->add_control(
			"lc_testi_{$i}_text",
			array(
				'label'   => sprintf( __( 'Testimonial %d text', 'ladycorrections' ), $i ),
				'section' => 'lc_testimonials',
				'type'    => 'textarea',
			)
		);
		$wp_customize->add_setting(
			"lc_testi_{$i}_name",
			array(
				'default'           => $name,
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			"lc_testi_{$i}_name",
			array(
				'label'   => sprintf( __( 'Testimonial %d name', 'ladycorrections' ), $i ),
				'section' => 'lc_testimonials',
				'type'    => 'text',
			)
		);
		$wp_customize->add_setting(
			"lc_testi_{$i}_role",
			array(
				'default'           => $role,
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			"lc_testi_{$i}_role",
			array(
				'label'   => sprintf( __( 'Testimonial %d location', 'ladycorrections' ), $i ),
				'section' => 'lc_testimonials',
				'type'    => 'text',
			)
		);
	}

	/* ----- Site texts & buttons ----- */
	$wp_customize->add_section(
		'lc_texts',
		array(
			'title'       => __( 'Site Texts & Buttons', 'ladycorrections' ),
			'description' => __( 'Every heading, button label and message on the site. Change any text here — no code needed.', 'ladycorrections' ),
			'panel'       => 'lc_panel',
			'priority'    => 95,
		)
	);
	$text_options = array(
		/* Section headings. */
		array( 'lc_cats_kicker', __( 'Categories: kicker', 'ladycorrections' ), __( 'Categories', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_cats_heading', __( 'Categories: heading', 'ladycorrections' ), __( 'Shop by <span class="hl">category</span>', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_cats_sub', __( 'Categories: subtext', 'ladycorrections' ), __( 'Find exactly what your routine needs.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_best_kicker', __( 'Best sellers: kicker', 'ladycorrections' ), __( 'Loved by customers', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_best_heading', __( 'Best sellers: heading', 'ladycorrections' ), __( 'Best <span class="hl">sellers</span>', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_best_sub', __( 'Best sellers: subtext', 'ladycorrections' ), __( 'The products everyone keeps reordering.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_best_btn', __( 'Best sellers: button', 'ladycorrections' ), __( 'View all products', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_flash_btn', __( 'Flash deals: button', 'ladycorrections' ), __( 'Shop the deals', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_promo_btn', __( 'Promo banners: button', 'ladycorrections' ), __( 'Shop now', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_story_btn', __( 'Brand story: button', 'ladycorrections' ), __( 'Start shopping', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		/* Newsletter. */
		array( 'lc_news_heading', __( 'Newsletter: heading', 'ladycorrections' ), __( 'Get 10% off your first order', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_news_text', __( 'Newsletter: text', 'ladycorrections' ), __( 'Join our newsletter for new arrivals, beauty tips and exclusive deals.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_news_btn', __( 'Newsletter: button', 'ladycorrections' ), __( 'Subscribe', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_news_ph', __( 'Newsletter: email placeholder', 'ladycorrections' ), __( 'Your email address', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		/* 404 + search. */
		array( 'lc_404_h', __( '404 page: heading', 'ladycorrections' ), __( 'Oops! Page not found', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_404_p', __( '404 page: text', 'ladycorrections' ), __( 'The page you are looking for may have been moved or no longer exists. Try searching, or head back home.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_404_btn', __( '404 page: button', 'ladycorrections' ), __( 'Back to home', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_search_empty_h', __( 'Search: no-results heading', 'ladycorrections' ), __( 'No results found', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_search_empty_p', __( 'Search: no-results text', 'ladycorrections' ), __( 'Try different keywords or browse the shop instead.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		/* Mobile drawer. */
		array( 'lc_drawer_search_ph', __( 'Drawer: search placeholder', 'ladycorrections' ), __( 'Search products…', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_drawer_cats_h', __( 'Drawer: categories heading', 'ladycorrections' ), __( 'Shop by Category', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_drawer_menu_h', __( 'Drawer: menu heading', 'ladycorrections' ), __( 'Menu', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_drawer_account', __( 'Drawer: my account label', 'ladycorrections' ), __( 'My Account', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_drawer_wishlist', __( 'Drawer: wishlist label', 'ladycorrections' ), __( 'Wishlist', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_drawer_wa', __( 'Drawer: WhatsApp button', 'ladycorrections' ), __( 'Chat on WhatsApp', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		/* Shop filters. */
		array( 'lc_f_title', __( 'Shop: filters title', 'ladycorrections' ), __( 'Filters', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_f_all', __( 'Shop: "all categories" pill', 'ladycorrections' ), __( 'All', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_f_sort_l', __( 'Shop: sort label', 'ladycorrections' ), __( 'Sort by', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_f_onsale', __( 'Shop: on-sale toggle', 'ladycorrections' ), __( 'On sale', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_f_instock', __( 'Shop: in-stock toggle', 'ladycorrections' ), __( 'In stock', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_f_min', __( 'Shop: min price label', 'ladycorrections' ), __( 'Min', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_f_max', __( 'Shop: max price label', 'ladycorrections' ), __( 'Max', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_f_apply', __( 'Shop: apply button', 'ladycorrections' ), __( 'Apply', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_f_clear', __( 'Shop: clear-all button', 'ladycorrections' ), __( 'Clear all filters', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_f_empty_h', __( 'Shop: empty heading', 'ladycorrections' ), __( 'No products found', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_f_empty_p', __( 'Shop: empty text', 'ladycorrections' ), __( 'Try adjusting your filters or search.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_f_close', __( 'Shop: close filters (mobile)', 'ladycorrections' ), __( 'Close filters', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		/* Cart + checkout. */
		array( 'lc_cart_empty_h', __( 'Cart: empty heading', 'ladycorrections' ), __( 'Your cart is empty', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_cart_empty_p', __( 'Cart: empty text', 'ladycorrections' ), __( 'Looks like you haven\'t added anything yet — let\'s fix that.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_cart_empty_btn', __( 'Cart: empty button', 'ladycorrections' ), __( 'Start Shopping', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_cart_continue', __( 'Cart: continue shopping link', 'ladycorrections' ), __( 'Continue shopping', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_cart_trust', __( 'Cart: trust line', 'ladycorrections' ), __( 'Cash on Delivery available • 100% authentic • Easy 7-day returns', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_co_secure', __( 'Checkout: heading', 'ladycorrections' ), __( 'Secure Checkout', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_co_trust', __( 'Checkout: trust line', 'ladycorrections' ), __( 'Cash on Delivery available across Iraq', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_co_thanks_h', __( 'Thank-you: heading', 'ladycorrections' ), __( 'Thank you for your order!', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_co_thanks_p', __( 'Thank-you: text', 'ladycorrections' ), __( 'Your order has been received and is being prepared. We\'ll contact you soon to confirm delivery.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
	);
	foreach ( $text_options as $opt ) {
		list( $id, $label, $default, $type, $sanitize ) = $opt;
		lc_add_text_option( $wp_customize, $id, $label, $default, 'lc_texts', $type, $sanitize );
	}
	/* Translatable sprintf patterns (kept in code, translators handle placeholders). */
	lc_add_text_option( $wp_customize, 'lc_f_show', __( 'Shop: "show N products" button', 'ladycorrections' ), __( 'Show %d products', 'ladycorrections' ), 'lc_texts', 'text', 'sanitize_text_field', __( 'Use %d where the number goes.', 'ladycorrections' ) );
	lc_add_text_option( $wp_customize, 'lc_f_results', __( 'Shop: result count text', 'ladycorrections' ), __( '%d products', 'ladycorrections' ), 'lc_texts', 'text', 'sanitize_text_field', __( 'Use %d where the number goes.', 'ladycorrections' ) );

	/* ----- Footer ----- */
	$wp_customize->add_section(
		'lc_footer',
		array(
			'title'       => __( 'Footer', 'ladycorrections' ),
			'description' => __( 'Footer columns, payment badges, contact options and copyright.', 'ladycorrections' ),
			'panel'       => 'lc_panel',
			'priority'    => 100,
		)
	);
	lc_add_text_option( $wp_customize, 'lc_foot_shop_h', __( 'Column heading: Shop', 'ladycorrections' ), __( 'Shop', 'ladycorrections' ), 'lc_footer' );
	lc_add_text_option( $wp_customize, 'lc_foot_help_h', __( 'Column heading: Help', 'ladycorrections' ), __( 'Help', 'ladycorrections' ), 'lc_footer' );
	lc_add_text_option( $wp_customize, 'lc_foot_pol_h', __( 'Column heading: Policies', 'ladycorrections' ), __( 'Policies', 'ladycorrections' ), 'lc_footer' );
	lc_add_text_option( $wp_customize, 'lc_foot_social_h', __( 'Social row heading', 'ladycorrections' ), __( 'Follow us', 'ladycorrections' ), 'lc_footer' );
	lc_add_text_option( $wp_customize, 'lc_pay_1', __( 'Payment badge 1', 'ladycorrections' ), __( 'Cash on Delivery', 'ladycorrections' ), 'lc_footer' );
	lc_add_text_option( $wp_customize, 'lc_pay_2', __( 'Payment badge 2', 'ladycorrections' ), __( 'Cash', 'ladycorrections' ), 'lc_footer' );
	lc_add_text_option( $wp_customize, 'lc_pay_3', __( 'Payment badge 3', 'ladycorrections' ), __( 'Bank Transfer', 'ladycorrections' ), 'lc_footer' );
	lc_add_text_option( $wp_customize, 'lc_whatsapp', __( 'WhatsApp number', 'ladycorrections' ), '', 'lc_footer', 'text', 'sanitize_text_field', __( 'Country code + number, digits only, no +. Example: 9647700000000. Shows a WhatsApp chat button in the mobile menu and footer.', 'ladycorrections' ) );
	lc_add_text_option( $wp_customize, 'lc_qv_details', __( 'Quick view: details button', 'ladycorrections' ), __( 'View full details', 'ladycorrections' ), 'lc_footer' );
	$wp_customize->add_setting(
		'lc_footer_about',
		array(
			'default'           => __( 'Premium beauty essentials for her — authentic products, fair prices, delivered across Iraq.', 'ladycorrections' ),
			'sanitize_callback' => 'lc_sanitize_text',
		)
	);
	$wp_customize->add_control(
		'lc_footer_about',
		array(
			'label'   => __( 'Brand blurb', 'ladycorrections' ),
			'section' => 'lc_footer',
			'type'    => 'textarea',
		)
	);
	$wp_customize->add_setting(
		'lc_copyright_text',
		array(
			/* translators: %s: year, %1$s: site name. */
			'default'           => sprintf( __( '© %1$s %2$s. All rights reserved.', 'ladycorrections' ), date_i18n( 'Y' ), get_bloginfo( 'name' ) ),
			'sanitize_callback' => 'lc_sanitize_text',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'lc_copyright_text',
		array(
			'label'   => __( 'Copyright text', 'ladycorrections' ),
			'section' => 'lc_footer',
			'type'    => 'text',
		)
	);

	/* ----- Selective refresh partials ----- */
	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'lc_announcement_text',
			array(
				'selector'        => '.lc-announce p',
				'render_callback' => function() {
					echo wp_kses_post( get_theme_mod( 'lc_announcement_text', '' ) );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'lc_hero_heading',
			array(
				'selector'        => '.lc-hero h1',
				'render_callback' => function() {
					echo wp_kses_post( get_theme_mod( 'lc_hero_heading', '' ) );
				},
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'lc_copyright_text',
			array(
				'selector'        => '.lc-copyright',
				'render_callback' => function() {
					echo wp_kses_post( get_theme_mod( 'lc_copyright_text', '' ) );
				},
			)
		);
	}
}
add_action( 'customize_register', 'lc_customize_register' );

	/* ----- Page templates: About / Contact / FAQ / Track / Wishlist / Info ----- */
	$page_options = array(
		/* About. */
		array( 'lc_about_title', __( 'About: page title', 'ladycorrections' ), __( 'Our Story', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_about_sub', __( 'About: subtitle', 'ladycorrections' ), __( 'Premium beauty, curated for her — and delivered across Iraq.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_about_s1n', __( 'About: stat 1 number', 'ladycorrections' ), __( '10k+', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_about_s1l', __( 'About: stat 1 label', 'ladycorrections' ), __( 'Happy customers', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_about_s2n', __( 'About: stat 2 number', 'ladycorrections' ), __( '120+', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_about_s2l', __( 'About: stat 2 label', 'ladycorrections' ), __( 'Premium products', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_about_s3n', __( 'About: stat 3 number', 'ladycorrections' ), __( '18', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_about_s3l', __( 'About: stat 3 label', 'ladycorrections' ), __( 'Governorates served', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_about_s4n', __( 'About: stat 4 number', 'ladycorrections' ), __( '4.9', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_about_s4l', __( 'About: stat 4 label', 'ladycorrections' ), __( 'Average rating', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_about_v1t', __( 'About: value 1 title', 'ladycorrections' ), __( '100% Authentic', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_about_v1d', __( 'About: value 1 text', 'ladycorrections' ), __( 'Original brands only, sourced directly from authorized distributors.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_about_v2t', __( 'About: value 2 title', 'ladycorrections' ), __( 'Fair Prices', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_about_v2d', __( 'About: value 2 text', 'ladycorrections' ), __( 'Honest pricing in IQD with no hidden markups.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_about_v3t', __( 'About: value 3 title', 'ladycorrections' ), __( 'Fast COD Delivery', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_about_v3d', __( 'About: value 3 text', 'ladycorrections' ), __( 'Cash on delivery, brought to your door across Iraq.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_about_v4t', __( 'About: value 4 title', 'ladycorrections' ), __( 'Support in EN/AR', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_about_v4d', __( 'About: value 4 text', 'ladycorrections' ), __( 'Real humans, in your language, always here to help.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_about_cta_h', __( 'About: CTA heading', 'ladycorrections' ), __( 'Ready to glow?', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_about_cta_p', __( 'About: CTA text', 'ladycorrections' ), __( 'Discover the bestsellers everyone keeps reordering.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_about_cta_btn', __( 'About: CTA button', 'ladycorrections' ), __( 'Shop Bestsellers', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		/* Contact. */
		array( 'lc_contact_h', __( 'Contact: heading', 'ladycorrections' ), __( 'Get in Touch', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_contact_sub', __( 'Contact: subtext', 'ladycorrections' ), __( 'Questions about an order or a product? We reply fast.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_contact_phone_l', __( 'Contact: phone label', 'ladycorrections' ), __( 'Phone / WhatsApp', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_contact_phone', __( 'Contact: phone number', 'ladycorrections' ), '', 'text', 'sanitize_text_field' ),
		array( 'lc_contact_email_l', __( 'Contact: email label', 'ladycorrections' ), __( 'Email', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_contact_email', __( 'Contact: email address', 'ladycorrections' ), '', 'text', 'sanitize_text_field' ),
		array( 'lc_contact_addr_l', __( 'Contact: address label', 'ladycorrections' ), __( 'Address', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_contact_addr', __( 'Contact: address', 'ladycorrections' ), '', 'textarea', 'lc_sanitize_text' ),
		array( 'lc_contact_hours_l', __( 'Contact: hours label', 'ladycorrections' ), __( 'Working Hours', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_contact_hours', __( 'Contact: hours', 'ladycorrections' ), '', 'textarea', 'lc_sanitize_text' ),
		array( 'lc_contact_form_sc', __( 'Contact: form shortcode', 'ladycorrections' ), '', 'textarea', 'sanitize_text_field' ),
		array( 'lc_contact_wa_btn', __( 'Contact: WhatsApp button', 'ladycorrections' ), __( 'Order on WhatsApp', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_contact_faq_t', __( 'Contact: FAQ teaser text', 'ladycorrections' ), __( 'Looking for quick answers?', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_contact_faq_btn', __( 'Contact: FAQ teaser button', 'ladycorrections' ), __( 'Visit the FAQ', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_contact_f_name', __( 'Contact form: name label', 'ladycorrections' ), __( 'Your name', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_contact_f_email', __( 'Contact form: email label', 'ladycorrections' ), __( 'Email', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_contact_f_msg', __( 'Contact form: message label', 'ladycorrections' ), __( 'Message', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_contact_f_send', __( 'Contact form: send button', 'ladycorrections' ), __( 'Send Message', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_contact_f_ok', __( 'Contact form: success message', 'ladycorrections' ), __( 'Thank you! Your message has been sent — we\'ll reply soon.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_contact_f_err', __( 'Contact form: error message', 'ladycorrections' ), __( 'Please fill in all fields correctly.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		/* FAQ. */
		array( 'lc_faq_h', __( 'FAQ: heading', 'ladycorrections' ), __( 'Frequently Asked Questions', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_faq_sub', __( 'FAQ: subtext', 'ladycorrections' ), __( 'Everything you need to know about ordering with us.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_faq_search_ph', __( 'FAQ: search placeholder', 'ladycorrections' ), __( 'Search questions…', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_faq_none', __( 'FAQ: no-results text', 'ladycorrections' ), __( 'No questions match your search.', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_faq_cta_t', __( 'FAQ: bottom CTA text', 'ladycorrections' ), __( 'Still have questions?', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_faq_cta_btn', __( 'FAQ: bottom CTA button', 'ladycorrections' ), __( 'Contact Us', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_faq_1_q', __( 'FAQ 1: question', 'ladycorrections' ), __( 'How long does delivery take?', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_faq_1_a', __( 'FAQ 1: answer', 'ladycorrections' ), __( 'Orders are delivered within 2–4 business days across Iraq. Baghdad orders often arrive in 1–2 days.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_faq_2_q', __( 'FAQ 2: question', 'ladycorrections' ), __( 'Do you offer Cash on Delivery?', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_faq_2_a', __( 'FAQ 2: answer', 'ladycorrections' ), __( 'Yes! Pay in cash when your order arrives — no advance payment needed.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_faq_3_q', __( 'FAQ 3: question', 'ladycorrections' ), __( 'Are your products 100% original?', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_faq_3_a', __( 'FAQ 3: answer', 'ladycorrections' ), __( 'Absolutely. We source directly from authorized distributors, and every product is guaranteed authentic.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_faq_4_q', __( 'FAQ 4: question', 'ladycorrections' ), __( 'What is your return policy?', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_faq_4_a', __( 'FAQ 4: answer', 'ladycorrections' ), __( 'You have 7 days from delivery to return unused products in their original packaging for a full refund.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_faq_5_q', __( 'FAQ 5: question', 'ladycorrections' ), __( 'How can I track my order?', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_faq_5_a', __( 'FAQ 5: answer', 'ladycorrections' ), __( 'Use the Track Order page with your order ID and billing email, or message us on WhatsApp anytime.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_faq_6_q', __( 'FAQ 6: question', 'ladycorrections' ), __( 'Do you ship outside Iraq?', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_faq_6_a', __( 'FAQ 6: answer', 'ladycorrections' ), __( 'Currently we deliver within Iraq only.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_faq_7_q', __( 'FAQ 7: question', 'ladycorrections' ), __( 'How do I use a coupon code?', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_faq_7_a', __( 'FAQ 7: answer', 'ladycorrections' ), __( 'Enter your code in the coupon field on the cart page and click Apply.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_faq_8_q', __( 'FAQ 8: question', 'ladycorrections' ), __( 'Which payment methods do you accept?', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_faq_8_a', __( 'FAQ 8: answer', 'ladycorrections' ), __( 'Cash on Delivery and bank transfer. Card payments are coming soon.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		/* Track order. */
		array( 'lc_track_h', __( 'Track: heading', 'ladycorrections' ), __( 'Track Your Order', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_track_sub', __( 'Track: subtext', 'ladycorrections' ), __( 'Enter your order ID and the billing email used at checkout.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		/* Wishlist. */
		array( 'lc_wish_h', __( 'Wishlist: heading', 'ladycorrections' ), __( 'My Wishlist', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_wish_sub', __( 'Wishlist: subtext', 'ladycorrections' ), __( 'Your saved favorites — ready when you are.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_wish_empty_h', __( 'Wishlist: empty heading', 'ladycorrections' ), __( 'Your wishlist is empty', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_wish_empty_p', __( 'Wishlist: empty text', 'ladycorrections' ), __( 'Tap the heart on any product to save it here for later.', 'ladycorrections' ), 'textarea', 'lc_sanitize_text' ),
		array( 'lc_wish_empty_btn', __( 'Wishlist: empty button', 'ladycorrections' ), __( 'Discover Products', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_wish_clear', __( 'Wishlist: clear-all button', 'ladycorrections' ), __( 'Clear all', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		array( 'lc_wish_remove', __( 'Wishlist: remove label', 'ladycorrections' ), __( 'Remove', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
		/* Info pages. */
		array( 'lc_info_updated', __( 'Info: "last updated" label', 'ladycorrections' ), __( 'Last updated', 'ladycorrections' ), 'text', 'sanitize_text_field' ),
	);
	foreach ( $page_options as $opt ) {
		list( $id, $label, $default, $type, $sanitize ) = $opt;
		lc_add_text_option( $wp_customize, $id, $label, $default, 'lc_texts', $type, $sanitize );
	}
