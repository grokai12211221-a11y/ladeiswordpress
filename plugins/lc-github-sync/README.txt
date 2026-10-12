=== LC GitHub Product Sync ===
Contributors: LadyCorrections
Tags: woocommerce, products, sync, github, csv
Requires at least: 5.8
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later

Syncs WooCommerce products from a simple CSV file hosted on GitHub.

== Description ==

Edit one file — `products.csv` — in your GitHub repository and your store
updates itself. Add rows to add products, edit cells to update them, write
"delete" in the Action column to trash a product.

* SKU is the unique key linking CSV rows to products.
* Hourly automatic sync via WP-Cron (toggleable) + manual "Sync Now" button.
* Product images are downloaded once and only re-downloaded when the Image URL changes.
* Sync log keeps the last 20 runs (created / updated / trashed / errors).

IMPORTANT: the CSV is the source of truth — products edited in wp-admin
will be overwritten by the next GitHub sync.

== CSV format ==

SKU,Name,Status,Regular Price,Sale Price,Stock,Categories,Short Description,Description,Image URL,Action

* Status: publish or draft
* Categories: single category name (created if missing)
* Action: leave empty, or "delete" to move the product to trash

== Installation ==

1. Upload the `lc-github-sync` folder to `/wp-content/plugins/` or install the zip via Plugins → Add New → Upload.
2. Activate the plugin.
3. Go to WooCommerce → GitHub Sync, check the CSV URL, press "Sync Now".
