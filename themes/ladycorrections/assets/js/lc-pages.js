/**
 * LadyCorrections — full-store page templates JS.
 *
 * FAQ page: accessible accordion toggle + live search filtering.
 * Vanilla JS, no dependencies.
 */
(function () {
  'use strict';

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function onReady(fn) {
    if (document.readyState !== 'loading') {
      fn();
    } else {
      document.addEventListener('DOMContentLoaded', fn);
    }
  }

  function setFaqOpen(item, open) {
    var btn = item.querySelector('.lc-faq-item__btn');
    var panel = item.querySelector('.lc-faq-item__panel');
    if (!btn || !panel) return;
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    item.classList.toggle('is-open', open);
    if (open) {
      panel.hidden = false;
    } else {
      // Let the collapse animation finish before removing from AT tree.
      window.setTimeout(function () {
        if (btn.getAttribute('aria-expanded') === 'false') {
          panel.hidden = true;
        }
      }, reduceMotion ? 0 : 400);
    }
  }

  onReady(function () {
    var list = document.getElementById('lc-faq-list');
    if (!list) return;

    var items = Array.prototype.slice.call(list.querySelectorAll('[data-lc-faq-item]'));
    var noneEl = document.getElementById('lc-faq-none');
    var noneMsg = list.getAttribute('data-none-msg') || '';
    if (noneEl && noneMsg) {
      noneEl.textContent = noneMsg;
    }

    /* ---------- Accordion: one open at a time ---------- */
    items.forEach(function (item) {
      var btn = item.querySelector('.lc-faq-item__btn');
      if (!btn) return;
      btn.addEventListener('click', function () {
        var willOpen = btn.getAttribute('aria-expanded') !== 'true';
        items.forEach(function (other) {
          if (other !== item) setFaqOpen(other, false);
        });
        setFaqOpen(item, willOpen);
      });
    });

    /* ---------- Live search: filter by question + answer text ---------- */
    var search = document.getElementById('lc-faq-search');
    if (search) {
      search.addEventListener('input', function () {
        var q = (search.value || '').trim().toLowerCase();
        var visible = 0;
        items.forEach(function (item) {
          var hay = (item.textContent || '').toLowerCase();
          var hit = !q || hay.indexOf(q) !== -1;
          item.hidden = !hit;
          if (hit) visible++;
        });
        if (noneEl) {
          noneEl.hidden = visible !== 0;
        }
      });
    }
  });
})();
