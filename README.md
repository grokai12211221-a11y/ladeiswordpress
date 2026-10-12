# Lady Corrections — WordPress Store

Official source for the **Lady Corrections** ladies-cosmetics store at
**https://lad.mbiinventra.com** (WordPress + WooCommerce + Elementor).

## What's in this repo

| Path | What it is |
|---|---|
| `themes/ladycorrections/` | The **LadyCorrections** WordPress theme (v2.1.0) — Royal Rose design, WooCommerce + Elementor ready, EN/AR RTL. Install via **WP Pusher** using subdirectory `themes/ladycorrections` with Push-to-Deploy on, or upload the zip from WordPress admin. |
| `plugins/lc-github-sync/` | **LC GitHub Product Sync** — syncs products from `products.csv` into WooCommerce (hourly auto-sync + manual "Sync Now"). **GitHub CSV is the source of truth.** |
| `plugins/lc-store-setup/` | **LC Store Setup** — one-click setup: creates 9 bilingual (EN/AR) pages, 5 menus, homepage/blog settings, WELCOME10 coupon, COD first, Iraq shipping (5,000 IQD flat, free over 50,000 IQD). |
| `products.csv` | **Product master list** (20 products). Edit/add/delete rows here, then run **Sync Now** in WordPress admin → WooCommerce → GitHub Sync. |
| `lc-products-20.csv` | WooCommerce-format reference copy of the same 20 products. |
| `product-images/` | Product photos (`p01.jpg` … `p20.jpg`) served as the image source for the sync. |

## Editing products (no code needed)

1. Open `products.csv` on GitHub (click the file → pencil icon).
2. Edit a row, add a new row, or set `Action=delete` on a row to remove that product.
3. Commit the change.
4. In WordPress admin go to **WooCommerce → GitHub Sync → Sync Now**.
5. (Optional) Turn on **hourly auto-sync** so you don't even need step 4.

### Product photos

- Put the photo in `product-images/` (square JPG, e.g. `p21.jpg`).
- In `products.csv`, set the Image URL to
  `https://raw.githubusercontent.com/grokai12211221-a11y/ladeiswordpress/main/product-images/p21.jpg`
- On sync, the photo is copied into the WordPress Media Library automatically.

### CSV columns

`SKU, Name EN, Name AR, Price IQD, Sale Price IQD, Category, Stock, Featured (yes/no), Image URL, Short Description EN, Short Description AR, Action`

- Leave `Action` empty for normal create/update. `Action=delete` trashes the product.
- Products are matched by **SKU** — never change an existing SKU, it creates a duplicate.

## Theme updates via WP Pusher

1. Install the **WP Pusher** plugin on the site.
2. WP Pusher → GitHub tab → **Obtain a GitHub Token** → Save.
3. WP Pusher → **Install Theme** → repository `grokai12211221-a11y/ladeiswordpress`,
   subdirectory `themes/ladycorrections`, **Push-to-Deploy ON** → Install.
4. Every push to this repo then auto-updates the theme on the live site.
