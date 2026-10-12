/**
 * LadyCorrections — Wishlist page JS.
 *
 * Reads saved product IDs from localStorage ("lc_wishlist", same key used by
 * the .lc-wishlist-btn toggles in main.js), fetches the rendered product
 * cards via the lc_get_wishlist_products AJAX action, and manages remove /
 * clear-all. Vanilla JS, no dependencies. Expects the localized `lcWish`
 * object (ajaxUrl, nonce) provided by the theme.
 */
(function () {
  'use strict';

  var KEY = 'lc_wishlist';

  function read() {
    try {
      var raw = localStorage.getItem(KEY);
      var arr = raw ? JSON.parse(raw) : [];
      return Array.isArray(arr) ? arr : [];
    } catch (e) {
      return [];
    }
  }

  function write(ids) {
    try {
      localStorage.setItem(KEY, JSON.stringify(ids));
    } catch (e) {
      /* storage unavailable — ignore */
    }
  }

  function onReady(fn) {
    if (document.readyState !== 'loading') {
      fn();
    } else {
      document.addEventListener('DOMContentLoaded', fn);
    }
  }

  onReady(function () {
    var grid = document.getElementById('lc-wish-grid');
    if (!grid) return;

    var empty = document.getElementById('lc-wish-empty');
    var countNum = document.getElementById('lc-wish-count-num');
    var clearBtn = document.getElementById('lc-wish-clear');
    var removeLabel = grid.getAttribute('data-remove-label') || 'Remove';

    var cfg = (typeof lcWish !== 'undefined' && lcWish) ? lcWish : {};
    var ajaxUrl = cfg.ajaxUrl || '';
    var nonce = cfg.nonce || '';

    function cleanIds() {
      var seen = {};
      return read()
        .map(function (id) { return String(id); })
        .filter(function (id) {
          if (!id || seen[id]) return false;
          seen[id] = true;
          return true;
        });
    }

    function setCount(n) {
      if (countNum) countNum.textContent = String(n);
    }

    function showEmpty() {
      grid.innerHTML = '';
      grid.hidden = true;
      grid.setAttribute('aria-busy', 'false');
      if (empty) empty.hidden = false;
      setCount(0);
      if (clearBtn) clearBtn.disabled = true;
    }

    function enhanceCards() {
      var cards = grid.querySelectorAll('li.product');
      cards.forEach(function (li) {
        var id = '';
        li.className.split(/\s+/).forEach(function (cls) {
          var m = cls.match(/^post-(\d+)$/);
          if (m) id = m[1];
        });
        if (!id || li.querySelector('.lc-wish-remove')) return;

        var titleEl = li.querySelector('.woocommerce-loop-product__title');
        var title = titleEl ? titleEl.textContent.trim() : '';

        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'lc-wish-remove';
        btn.setAttribute('data-id', id);
        btn.setAttribute('aria-label', title ? removeLabel + ': ' + title : removeLabel);
        var cross = document.createElement('span');
        cross.setAttribute('aria-hidden', 'true');
        cross.textContent = '×';
        btn.appendChild(cross);

        btn.addEventListener('click', function () {
          var ids = cleanIds().filter(function (x) { return x !== String(id); });
          write(ids);
          render();
        });

        li.appendChild(btn);
      });
    }

    function render() {
      var ids = cleanIds();

      if (!ids.length || !ajaxUrl) {
        showEmpty();
        return;
      }

      grid.hidden = false;
      grid.setAttribute('aria-busy', 'true');
      if (empty) empty.hidden = true;
      if (clearBtn) clearBtn.disabled = false;

      var data = new URLSearchParams();
      data.append('action', 'lc_get_wishlist_products');
      data.append('nonce', nonce);
      ids.forEach(function (id) { data.append('ids[]', id); });

      fetch(ajaxUrl, {
        method: 'POST',
        credentials: 'same-origin',
        body: data
      })
        .then(function (res) { return res.json(); })
        .then(function (res) {
          var html = res && res.success && res.data ? res.data.html : '';
          if (!html) {
            showEmpty();
            return;
          }
          grid.innerHTML = html;
          grid.setAttribute('aria-busy', 'false');
          enhanceCards();
          var n = (res.data.count != null) ? res.data.count : ids.length;
          setCount(n);
        })
        .catch(function () {
          showEmpty();
        });
    }

    if (clearBtn) {
      clearBtn.addEventListener('click', function () {
        write([]);
        render();
      });
    }

    render();
  });
})();
