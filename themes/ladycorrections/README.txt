=== LadyCorrections ===
Contributors: Muse
Tags: e-commerce, woocommerce, elementor, rtl-language-support, custom-colors, custom-logo, custom-menu, featured-images, translation-ready, blog
Requires at least: 6.0
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 2.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Premium 3D SaaS-modern WooCommerce-ready cosmetics store theme with full
Elementor support. Royal Rose design system, layered depth, floating glass
cards, gradient meshes, mobile-first responsive, RTL ready, English + Arabic
translation-ready.

== Description ==

LadyCorrections is a custom-built WordPress theme for a ladies cosmetics
e-commerce store (Iraq market, IQD, cash on delivery). Highlights:

* Royal Rose design system (pink #E91663, rose, blush, berry, champagne gold)
* Premium 3D SaaS aesthetic: layered shadows, floating glass cards,
  gradient mesh backgrounds, mousemove 3D tilt, reveal-on-scroll animations
* Glassmorphism sticky header, gradient pill CTAs with press effect
* 3D hero: eyebrow badge, big Playfair headline, dual CTAs, trust stats row,
  tilting visual card with floating sale / rating / delivery glass cards
  (built-in premium banner photo assets/img/hero-default.jpg when no hero
  image is set — never placeholder text)
* Infinite trust marquee strip (Customizer-editable items)
* 3D category cards with hover lift + image zoom + arrow reveal
* Flash deals on a deep berry 3D panel with glass countdown boxes + on-sale products
* Daraz-style vertical product cards, now premium: hover lift, sheen sweep,
  quick-view modal (no page reload)
* 3D promo banners, stacked brand-story images, glass testimonial cards,
  gradient newsletter panel with floating shapes
* Full Customizer panel (Appearance > Customize > LadyCorrections):
  Getting Started checklist, brand colors (live preview), announcement bar,
  hero (+ trust stats + floating cards), marquee, flash deals, promos, story,
  testimonials, Site Texts & Buttons (every heading, button and message on the
  site is editable — zero hardcoded content), footer + WhatsApp — no code needed
* Professional shop page with AJAX filters: category pills, sort, on-sale /
  in-stock toggles, price range, search, active-filter chips, skeleton loading,
  mobile filter drawer — works with and without JavaScript
* Premium cart & checkout: card-style cart rows with quantity steppers, sticky
  3D totals card, styled coupon field, beautiful empty-cart state; two-column
  checkout with sticky order summary, payment methods as selectable cards
  (COD highlighted — this store is COD-first), animated thank-you page
* Premium mobile drawer: glassmorphism slide-in panel with product search,
  expandable category accordion, menu links, account links, WhatsApp button
* Plugin-ready architecture: wp_body_open, body_class/post_class everywhere,
  never deregisters jQuery; action hooks lc_before_header / lc_after_header /
  lc_before_main / lc_after_main / lc_before_footer / lc_after_footer /
  lc_before_product_card / lc_after_product_card; overridable
  lc_wishlist_button action; apply_filters on announcement text & hero heading;
  Gutenberg supports (align-wide, responsive embeds, block styles, brand color
  palette in the editor)
* Elementor compatible: works with Elementor & Elementor Pro Theme Builder
  (header / footer / archive / single locations registered)
* RTL stylesheet included; translation template (languages/ladycorrections.pot)
* Works with AND without WooCommerce (all Woo calls are guarded)

== Installation ==

1. In WordPress admin go to Appearance > Themes > Add New > Upload Theme.
2. Choose ladycorrections.zip and click Install Now, then Activate.
3. Install required plugins: Plugins > Add New > search and install
   "WooCommerce" and "Elementor" (Elementor Pro optional, for Theme Builder).
4. Run the WooCommerce setup wizard (currency: Iraqi Dinar, IQD).
   Enable "Cash on delivery" under WooCommerce > Settings > Payments.

== Menu setup ==

1. Go to Appearance > Menus and create these menus:
   - Main menu: Home, Shop, Categories, Offers, About, Blog, Contact
   - Mobile menu: same (or shorter) for the drawer
   - Footer Shop: category links
   - Footer Help: FAQ, Shipping, Returns, Track Order, Contact
   - Footer Policies: Privacy Policy, Terms, Refund Policy
2. At the bottom of the Menus screen assign each to its Display Location:
   Primary Menu, Mobile Drawer Menu, Footer: Shop Links, Footer: Help Links,
   Footer: Policy Links.

== Customizer walkthrough ==

Appearance > Customize > LadyCorrections:

* Brand Colors — change Primary (buttons/accents) and Berry (footer/dark).
* Announcement Bar — toggle + text shown above the header.
* Homepage Sections — turn each homepage block on/off.
* Hero — badge, heading (HTML allowed), subtext, two CTA buttons, hero image.
* Flash Deals — title, subtext, end date/time (countdown), button link.
* Promo Banners — two banners: title, text, link, image.
* Brand Story — kicker, heading, text, image.
* Testimonials — three customer reviews (text, name, city).
* Footer — brand blurb and copyright line.

Set your logo under Appearance > Customize > Site Identity > Logo.

== Elementor ==

The theme is built Elementor-first: every template calls wp_head(),
wp_footer() and the_content(), so Elementor sections work on any page.

With Elementor Pro you can override the theme parts:
Templates > Theme Builder > add Header / Footer / Archive / Single.
When an Elementor location is active it automatically replaces the
theme's header/footer — no settings needed.

== Syncing menus, extensions & Elementor ==

* Menus: everything in the header, drawer and footer renders from
  Appearance > Menus, so a menu change updates the whole site at once.
* WooCommerce: products, categories, coupons and orders all render through
  WooCommerce templates styled by assets/css/woocommerce.css — admin changes
  appear on the storefront immediately.
* Plugins: the theme declares WooCommerce gallery support (zoom, lightbox,
  slider) and uses standard WordPress hooks, so wishlist, review, WhatsApp
  and SEO plugins integrate without conflicts.

== Recommended & tested plugins ==

Any standard WordPress plugin works with this theme: it follows WordPress
coding standards, never deregisters jQuery, calls wp_head()/wp_footer()/
wp_body_open() everywhere, and exposes the lc_* action hooks listed above.
Recommended:

* WooCommerce — the shop engine. Products, categories, coupons, orders.
  The theme styles every WooCommerce template and adds -X% sale badges,
  quick view, AJAX shop filters and a premium cart/checkout.
* Elementor (+ Pro) — drag & drop page design. Pro's Theme Builder can
  override the header / footer / archive / single via registered locations.
* Rank Math SEO — meta titles, sitemaps, breadcrumbs (theme has a
  breadcrumb hook area via lc_before_main).
* UpdraftPlus — scheduled backups to cloud storage.
* Wordfence — firewall + login protection.
* LiteSpeed Cache — page cache, image WebP, database optimization.
* Polylang — Arabic/English multilingual. The theme is translation-ready
  (languages/ladycorrections.pot) and ships rtl.css.
* TI WooCommerce Wishlist — replaces the theme's heart button automatically:
  the button fires the lc_wishlist_button action, so the plugin can override
  it without template edits.
* Contact Form 7 — contact page forms; styled by the theme's form CSS.

== Notes ==

* The newsletter form shows a success message without sending (front-end
  demo). Connect it to Mailchimp/Brevo via any newsletter plugin.
* The heart icon on product cards fires the lc_wishlist_button action —
  install TI WooCommerce Wishlist (or similar) and it takes over automatically.
* For Arabic: install Polylang (or similar), translate strings with the
  included .pot file; the theme ships an rtl.css that loads automatically.

== Changelog ==

= 2.1.0 =
* Real default hero banner: premium AI-generated cosmetics photo
  (assets/img/hero-default.jpg) shows when no Customizer hero image is set;
  pure-CSS artwork kept only as a last-resort fallback.
* Customizer overhaul: new "Getting Started" 5-step checklist section,
  one-line descriptions on every section, explicit ordering, live color
  preview (postMessage), all v2.0 option IDs unchanged.
* Full-site editability: every visible string is now Customizer-editable via
  the new "Site Texts & Buttons" section (headings, subtexts, button labels,
  newsletter, drawer, 404, search empty state) plus footer headings, payment
  badges, WhatsApp number and quick-view labels.
* Premium mobile drawer rebuilt: glassmorphism slide-in panel, product search,
  expandable category accordion, menu/account links, WhatsApp button, focus
  trap, ESC/overlay close.
* Professional shop page with AJAX filters (category pills, sort, on-sale /
  in-stock toggles, price range, search, chips, skeleton loading, mobile
  drawer, URL sync, no-JS fallback).
* Cart & checkout redesigned: card-style cart rows, sticky 3D totals card,
  styled coupon, empty-cart state; two-column checkout with sticky summary,
  payment-method cards with COD highlighted, animated thank-you page.
* Plugin compatibility: wp_body_open/body_class/post_class everywhere,
  Gutenberg supports (align-wide, responsive embeds, block styles, brand
  editor palette), lc_* action hooks throughout, overridable
  lc_wishlist_button action, apply_filters on announcement & hero heading.
* New template override woocommerce/content-product.php (adds product-card
  hooks; otherwise identical to WooCommerce core).

= 2.0.0 =
* Premium 3D SaaS redesign: layered shadow system, floating glass cards,
  gradient mesh backgrounds, mousemove 3D tilt + parallax, reveal-on-scroll,
  animated counters.
* New 3D hero: eyebrow badge, trust stats row, tilting visual card with
  floating sale/rating/delivery cards; pure-CSS fallback artwork when no
  hero image is set (no placeholder text on the frontend, ever).
* New infinite trust marquee strip (Customizer-editable).
* Category cards rebuilt as 3D cards with hover lift, image zoom, arrow reveal.
* Flash deals on deep berry 3D panel with glass countdown boxes + on-sale products.
* Product cards: hover lift, sheen sweep, quick-view modal.
* Promo banners, brand story (stacked images), testimonials (glass cards),
  newsletter (gradient 3D panel) all rebuilt with depth.
* Customizer extended: hero trust stats, floating card texts, marquee items,
  story second image + badge, promo tag pills. All v1 option IDs unchanged.
* RTL stylesheet extended for new layouts; .pot updated.

= 1.0.0 =
* Initial release.
