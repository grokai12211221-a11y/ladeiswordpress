/**
 * LadyCorrections — AJAX shop filters.
 *
 * Intercepts the #lc-shop-filters form, fetches filtered results from the
 * lc_shop_filter AJAX endpoint, and updates the product grid, pagination,
 * active-filter chips, result count and browser history without a reload.
 *
 * Vanilla JS, no jQuery. The form still works as a plain GET submit when
 * JavaScript is disabled (server reads $_GET).
 */
(function () {
	'use strict';

	if (typeof lcShop === 'undefined') {
		return;
	}

	document.addEventListener('DOMContentLoaded', init);

	function init() {
		var form = document.getElementById('lc-shop-filters');
		if (!form) {
			return;
		}

		var results    = document.getElementById('lc-shop-results');
		var pagination = document.getElementById('lc-shop-pagination');
		var countEl    = document.getElementById('lc-result-count');
		var chipsEl    = document.getElementById('lc-active-chips');
		var applyBtn   = document.getElementById('lc-filters-apply');
		var clearBtn   = document.getElementById('lc-filters-clear');
		var openBtn    = document.getElementById('lc-filters-open');
		var closeBtn   = document.getElementById('lc-filters-close');
		var badge      = document.getElementById('lc-filters-count');

		var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		var debounceTimer = null;
		var requestSeq = 0;
		var previousHtml = '';

		/* ---------- helpers ---------- */

		function sprintf(str, n) {
			return String(str).replace(/%[ds]/, n);
		}

		function collectParams() {
			var fd = new FormData(form);
			var p = {};
			fd.forEach(function (v, k) {
				p[k] = v;
			});
			return p;
		}

		/* Drop empty/default params so URLs and chips stay clean. */
		function cleanParams(p) {
			var out = {};
			Object.keys(p).forEach(function (k) {
				var v = p[k];
				if (v === '' || v === null || typeof v === 'undefined') {
					return;
				}
				if (k === 'orderby' && (v === 'menu_order' || v === '')) {
					return;
				}
				if (k === 'paged' && (v === '1' || v === 1 || v === '')) {
					return;
				}
				out[k] = v;
			});
			return out;
		}

		function activeFilterCount(p) {
			var n = 0;
			if (p.cat) { n++; }
			if (p.on_sale) { n++; }
			if (p.in_stock) { n++; }
			if (p.min_price || p.max_price) { n++; }
			if (p.s) { n++; }
			if (p.orderby) { n++; }
			return n;
		}

		function buildUrl(p) {
			var qs = new URLSearchParams(p).toString();
			return lcShop.shopUrl + (qs ? '?' + qs : '');
		}

		function showSkeleton() {
			if (!results) {
				return;
			}
			previousHtml = results.innerHTML;
			var html = '';
			for (var i = 0; i < 8; i++) {
				html += '<div class="lc-skeleton-card" aria-hidden="true"><div class="lc-skeleton-media"></div><div class="lc-skeleton-line"></div><div class="lc-skeleton-line lc-skeleton-line--short"></div></div>';
			}
			results.classList.add('lc-loading');
			results.innerHTML = html;
		}

		function hideSkeleton() {
			if (results) {
				results.classList.remove('lc-loading');
			}
		}

		/* ---------- chips ---------- */

		function chipLabel(key, params) {
			if (key === 'cat') {
				var checked = form.querySelector('input[name="cat"]:checked');
				if (checked) {
					var span = checked.closest('label').querySelector('span');
					if (span) {
						var clone = span.cloneNode(true);
						var count = clone.querySelector('.lc-pill-count');
						if (count) {
							count.remove();
						}
						return clone.textContent.trim();
					}
				}
				return params.cat;
			}
			if (key === 'on_sale') {
				return lcShop.i18n.onSale;
			}
			if (key === 'in_stock') {
				return lcShop.i18n.inStock;
			}
			if (key === 'price') {
				var min = params.min_price || '';
				var max = params.max_price || '';
				if (min && max) {
					return min + ' – ' + max;
				}
				return min ? '≥ ' + min : '≤ ' + max;
			}
			if (key === 's') {
				return params.s;
			}
			if (key === 'orderby') {
				var sel = form.querySelector('select[name="orderby"]');
				if (sel && sel.selectedIndex > -1) {
					return sel.options[sel.selectedIndex].textContent.trim();
				}
				return params.orderby;
			}
			return key;
		}

		function renderChips(params) {
			if (!chipsEl) {
				return;
			}
			chipsEl.innerHTML = '';

			var keys = [];
			if (params.cat) { keys.push('cat'); }
			if (params.on_sale) { keys.push('on_sale'); }
			if (params.in_stock) { keys.push('in_stock'); }
			if (params.min_price || params.max_price) { keys.push('price'); }
			if (params.s) { keys.push('s'); }
			if (params.orderby) { keys.push('orderby'); }

			keys.forEach(function (key) {
				var label = chipLabel(key, params);
				var chip = document.createElement('button');
				chip.type = 'button';
				chip.className = 'lc-chip';
				chip.setAttribute('data-remove', key);
				chip.setAttribute('aria-label', sprintf(lcShop.i18n.remove, label));
				var txt = document.createElement('span');
				txt.className = 'lc-chip-label';
				txt.textContent = label;
				var x = document.createElement('span');
				x.className = 'lc-chip-x';
				x.setAttribute('aria-hidden', 'true');
				x.textContent = '×';
				chip.appendChild(txt);
				chip.appendChild(x);
				chipsEl.appendChild(chip);
			});

			if (keys.length > 0) {
				var clear = document.createElement('button');
				clear.type = 'button';
				clear.className = 'lc-chip lc-chip--clear';
				clear.setAttribute('data-remove', '__all');
				clear.textContent = lcShop.i18n.clear;
				chipsEl.appendChild(clear);
			}
		}

		function updateBadge(params) {
			if (!badge) {
				return;
			}
			var n = activeFilterCount(params);
			if (n > 0) {
				badge.textContent = n;
				badge.hidden = false;
			} else {
				badge.hidden = true;
			}
		}

		/* ---------- main filter run ---------- */

		function runFilter(push) {
			var params = cleanParams(collectParams());
			var reqId = ++requestSeq;

			showSkeleton();

			var body = new URLSearchParams();
			body.append('action', 'lc_shop_filter');
			body.append('nonce', lcShop.nonce);
			Object.keys(params).forEach(function (k) {
				body.append(k, params[k]);
			});

			fetch(lcShop.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
				body: body.toString()
			})
				.then(function (resp) { return resp.json(); })
				.then(function (data) {
					if (reqId !== requestSeq) {
						return; /* stale response */
					}
					hideSkeleton();
					if (!data || !data.success || !data.data) {
						results.innerHTML = previousHtml;
						return;
					}
					var d = data.data;
					results.innerHTML = d.html;
					if (pagination) {
						pagination.innerHTML = d.pagination || '';
					}
					if (countEl) {
						countEl.textContent = sprintf(lcShop.i18n.results, d.found);
					}
					if (applyBtn) {
						applyBtn.textContent = sprintf(lcShop.i18n.show, d.found);
					}
					renderChips(params);
					updateBadge(params);
					if (push !== false) {
						history.pushState({ lcShop: true }, '', buildUrl(params));
					}
				})
				.catch(function () {
					if (reqId !== requestSeq) {
						return;
					}
					hideSkeleton();
					results.innerHTML = previousHtml;
				});
		}

		function resetPaged() {
			var paged = form.querySelector('input[name="paged"]');
			if (paged) {
				paged.value = '1';
			}
		}

		function resetField(key) {
			if (key === 'cat') {
				var all = form.querySelector('input[name="cat"][value=""]');
				if (all) {
					all.checked = true;
				}
			} else if (key === 'on_sale' || key === 'in_stock') {
				var box = form.querySelector('input[name="' + key + '"]');
				if (box) {
					box.checked = false;
				}
			} else if (key === 'price') {
				var min = form.querySelector('input[name="min_price"]');
				var max = form.querySelector('input[name="max_price"]');
				if (min) { min.value = ''; }
				if (max) { max.value = ''; }
			} else if (key === 's') {
				var s = form.querySelector('input[name="s"]');
				if (s) { s.value = ''; }
			} else if (key === 'orderby') {
				var sel = form.querySelector('select[name="orderby"]');
				if (sel) { sel.value = 'menu_order'; }
			}
		}

		function clearAll() {
			form.reset();
			/* form.reset() restores the page-load values; force true defaults. */
			var all = form.querySelector('input[name="cat"][value=""]');
			if (all) { all.checked = true; }
			['on_sale', 'in_stock'].forEach(function (k) {
				var box = form.querySelector('input[name="' + k + '"]');
				if (box) { box.checked = false; }
			});
			['min_price', 'max_price', 's'].forEach(function (k) {
				var inp = form.querySelector('input[name="' + k + '"]');
				if (inp) { inp.value = ''; }
			});
			var sel = form.querySelector('select[name="orderby"]');
			if (sel) { sel.value = 'menu_order'; }
			resetPaged();
			runFilter(true);
		}

		/* ---------- events ---------- */

		form.addEventListener('change', function () {
			resetPaged();
			runFilter(true);
		});

		form.addEventListener('input', function (e) {
			var t = e.target;
			if (!t || !t.name) {
				return;
			}
			if (t.type === 'text' || t.type === 'search' || t.type === 'number') {
				clearTimeout(debounceTimer);
				debounceTimer = setTimeout(function () {
					resetPaged();
					runFilter(true);
				}, 350);
			}
		});

		form.addEventListener('submit', function (e) {
			e.preventDefault();
			clearTimeout(debounceTimer);
			resetPaged();
			runFilter(true);
		});

		if (chipsEl) {
			chipsEl.addEventListener('click', function (e) {
				var chip = e.target.closest('[data-remove]');
				if (!chip) {
					return;
				}
				var key = chip.getAttribute('data-remove');
				if (key === '__all') {
					clearAll();
				} else {
					resetField(key);
					resetPaged();
					runFilter(true);
				}
			});
		}

		if (clearBtn) {
			clearBtn.addEventListener('click', clearAll);
		}

		if (results) {
			results.addEventListener('click', function (e) {
				if (e.target.closest('.lc-f-empty-clear')) {
					clearAll();
				}
			});
		}

		if (pagination) {
			pagination.addEventListener('click', function (e) {
				var a = e.target.closest('a');
				if (!a || !a.href) {
					return;
				}
				e.preventDefault();
				var paged = '1';
				try {
					paged = new URL(a.href).searchParams.get('paged') || '1';
				} catch (err) {
					/* keep default */
				}
				var hidden = form.querySelector('input[name="paged"]');
				if (hidden) {
					hidden.value = paged;
				}
				runFilter(true);
				if (results) {
					results.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'start' });
				}
			});
		}

		/* Back/forward buttons: restore form state from the URL. */
		window.addEventListener('popstate', function () {
			var sp = new URLSearchParams(window.location.search);

			var catVal = sp.get('cat') || '';
			var catRadio = form.querySelector('input[name="cat"][value="' + CSS.escape(catVal) + '"]');
			if (catRadio) {
				catRadio.checked = true;
			}

			var orderSel = form.querySelector('select[name="orderby"]');
			if (orderSel) {
				var ov = sp.get('orderby') || 'menu_order';
				var valid = false;
				for (var i = 0; i < orderSel.options.length; i++) {
					if (orderSel.options[i].value === ov) {
						valid = true;
						break;
					}
				}
				orderSel.value = valid ? ov : 'menu_order';
			}

			['on_sale', 'in_stock'].forEach(function (k) {
				var box = form.querySelector('input[name="' + k + '"]');
				if (box) {
					box.checked = sp.get(k) === '1';
				}
			});

			[['min_price'], ['max_price'], ['s'], ['paged']].forEach(function (arr) {
				var k = arr[0];
				var inp = form.querySelector('input[name="' + k + '"]');
				if (inp) {
					inp.value = sp.get(k) || (k === 'paged' ? '1' : '');
				}
			});

			runFilter(false);
		});

		/* ---------- mobile drawer ---------- */

		var overlay = null;
		function ensureOverlay() {
			if (!overlay) {
				overlay = document.createElement('div');
				overlay.className = 'lc-filters-overlay';
				overlay.setAttribute('aria-hidden', 'true');
				overlay.addEventListener('click', closeDrawer);
				document.body.appendChild(overlay);
			}
			return overlay;
		}

		function openDrawer() {
			form.classList.add('open');
			document.body.classList.add('lc-filters-open');
			ensureOverlay().classList.add('show');
			if (openBtn) {
				openBtn.setAttribute('aria-expanded', 'true');
			}
			if (closeBtn) {
				closeBtn.focus();
			}
		}

		function closeDrawer() {
			form.classList.remove('open');
			document.body.classList.remove('lc-filters-open');
			if (overlay) {
				overlay.classList.remove('show');
			}
			if (openBtn) {
				openBtn.setAttribute('aria-expanded', 'false');
				openBtn.focus();
			}
		}

		function isDrawerOpen() {
			return form.classList.contains('open');
		}

		if (openBtn) {
			openBtn.addEventListener('click', openDrawer);
		}
		if (closeBtn) {
			closeBtn.addEventListener('click', closeDrawer);
		}
		if (applyBtn) {
			/* Filtering is live on mobile too; the button just closes. */
			applyBtn.addEventListener('click', function () {
				if (isDrawerOpen()) {
					closeDrawer();
				}
			});
		}
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && isDrawerOpen()) {
				closeDrawer();
			}
		});

		/* ---------- initial state ---------- */

		var initial = cleanParams(collectParams());
		renderChips(initial);
		updateBadge(initial);
	}
})();
