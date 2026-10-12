/**
 * LadyCorrections theme interactions — vanilla JS, no jQuery.
 */
(function () {
  'use strict';

  var body = document.body;

  /* ---------- Sticky header shadow ---------- */
  var header = document.getElementById('lc-header');
  function onScrollHeader() {
    if (!header) return;
    header.classList.toggle('is-scrolled', window.scrollY > 8);
  }
  window.addEventListener('scroll', onScrollHeader, { passive: true });
  onScrollHeader();

  /* ---------- Announcement dismiss (remembered in localStorage) ---------- */
  var announce = document.getElementById('lc-announce');
  var announceClose = document.getElementById('lc-announce-close');
  try {
    if (announce && localStorage.getItem('lc_announce_dismissed') === '1') {
      announce.classList.add('is-hidden');
    }
  } catch (e) { /* storage unavailable */ }
  if (announceClose) {
    announceClose.addEventListener('click', function () {
      announce.classList.add('is-hidden');
      try { localStorage.setItem('lc_announce_dismissed', '1'); } catch (e) { /* ignore */ }
    });
  }

  /* ---------- Mobile drawer (premium) ---------- */
  var drawer = document.getElementById('lc-drawer');
  var drawerOpen = document.getElementById('lc-drawer-open');
  var drawerClose = document.getElementById('lc-drawer-close');
  var veil = document.getElementById('lc-drawer-veil');
  var lcLastFocus = null;

  function openDrawer() {
    lcLastFocus = document.activeElement;
    body.classList.add('drawer-open');
    if (drawerOpen) drawerOpen.setAttribute('aria-expanded', 'true');
    if (drawerClose) drawerClose.focus();
  }
  function closeDrawer() {
    body.classList.remove('drawer-open');
    if (drawerOpen) drawerOpen.setAttribute('aria-expanded', 'false');
    if (lcLastFocus && lcLastFocus.focus) {
      try { lcLastFocus.focus(); } catch (e) { /* ignore */ }
    }
    lcLastFocus = null;
  }

  /* Focus trap: Tab cycles within the drawer while it is open. */
  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Tab' || !drawer || !body.classList.contains('drawer-open')) return;
    var focusables = drawer.querySelectorAll('a[href], button:not([disabled]), input:not([disabled]), [tabindex]:not([tabindex="-1"])');
    var visible = [];
    for (var i = 0; i < focusables.length; i++) {
      if (focusables[i].offsetParent !== null) visible.push(focusables[i]);
    }
    if (!visible.length) return;
    var first = visible[0];
    var last = visible[visible.length - 1];
    if (e.shiftKey && document.activeElement === first) {
      e.preventDefault();
      last.focus();
    } else if (!e.shiftKey && document.activeElement === last) {
      e.preventDefault();
      first.focus();
    }
  });

  /* Category accordion: only one panel open at a time. */
  if (drawer) {
    var accToggles = drawer.querySelectorAll('.lc-acc-toggle');
    Array.prototype.forEach.call(accToggles, function (btn) {
      if (btn.tagName !== 'BUTTON') return;
      btn.addEventListener('click', function () {
        var isOpen = btn.getAttribute('aria-expanded') === 'true';
        Array.prototype.forEach.call(accToggles, function (other) {
          if (other.tagName !== 'BUTTON') return;
          other.setAttribute('aria-expanded', 'false');
          var otherItem = other.closest('.lc-drawer__cat');
          if (otherItem) {
            otherItem.classList.remove('is-open');
            var otherPanel = otherItem.querySelector('.lc-acc-panel');
            if (otherPanel) otherPanel.style.maxHeight = '0px';
          }
        });
        if (!isOpen) {
          var item = btn.closest('.lc-drawer__cat');
          btn.setAttribute('aria-expanded', 'true');
          if (item) {
            item.classList.add('is-open');
            var panel = item.querySelector('.lc-acc-panel');
            if (panel) panel.style.maxHeight = panel.scrollHeight + 'px';
          }
        }
      });
    });
  }

  if (drawerOpen) drawerOpen.addEventListener('click', openDrawer);
  if (drawerClose) drawerClose.addEventListener('click', closeDrawer);
  if (veil) veil.addEventListener('click', closeDrawer);
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') { closeDrawer(); closeSearch(); }
  });

  /* ---------- Search overlay ---------- */
  var searchVeil = document.getElementById('lc-search-veil');
  var searchOpen = document.getElementById('lc-search-open');
  var searchClose = document.getElementById('lc-search-close');
  function openSearch() {
    body.classList.add('search-open');
    var input = searchVeil ? searchVeil.querySelector('input[type="search"]') : null;
    if (input) setTimeout(function () { input.focus(); }, 120);
  }
  function closeSearch() { body.classList.remove('search-open'); }
  if (searchOpen) searchOpen.addEventListener('click', openSearch);
  if (searchClose) searchClose.addEventListener('click', closeSearch);
  if (searchVeil) {
    searchVeil.addEventListener('click', function (e) {
      if (e.target === searchVeil) closeSearch();
    });
  }

  /* ---------- Flash countdown ---------- */
  var cd = document.getElementById('lc-countdown');
  var flashEnd = (window.lcData && window.lcData.flashEnd) ? window.lcData.flashEnd : '';
  if (cd && flashEnd) {
    var end = new Date(flashEnd.replace('T', ' ')).getTime();
    var dEl = cd.querySelector('[data-lc-days]');
    var hEl = cd.querySelector('[data-lc-hours]');
    var mEl = cd.querySelector('[data-lc-mins]');
    var sEl = cd.querySelector('[data-lc-secs]');
    function pad(n) { return (n < 10 ? '0' : '') + n; }
    function tick() {
      var diff = end - Date.now();
      if (diff <= 0) {
        if (dEl) dEl.textContent = '00';
        if (hEl) hEl.textContent = '00';
        if (mEl) mEl.textContent = '00';
        if (sEl) sEl.textContent = '00';
        clearInterval(timer);
        return;
      }
      var d = Math.floor(diff / 86400000);
      var h = Math.floor(diff % 86400000 / 3600000);
      var m = Math.floor(diff % 3600000 / 60000);
      var s = Math.floor(diff % 60000 / 1000);
      if (dEl) dEl.textContent = pad(d);
      if (hEl) hEl.textContent = pad(h);
      if (mEl) mEl.textContent = pad(m);
      if (sEl) sEl.textContent = pad(s);
    }
    var timer = setInterval(tick, 1000);
    tick();
  }

  /* ---------- Back to top ---------- */
  var toTop = document.getElementById('lc-to-top');
  function onScrollTop() {
    if (!toTop) return;
    toTop.classList.toggle('is-visible', window.scrollY > 600);
  }
  window.addEventListener('scroll', onScrollTop, { passive: true });
  onScrollTop();
  if (toTop) {
    toTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ---------- Newsletter fake-submit ---------- */
  var newsForm = document.getElementById('lc-newsletter-form');
  var newsMsg = document.getElementById('lc-newsletter-msg');
  var msgInvalid = (window.lcData && window.lcData.invalidEmail) ? window.lcData.invalidEmail : 'Please enter a valid email address.';
  var msgSubscribed = (window.lcData && window.lcData.subscribed) ? window.lcData.subscribed : 'Thank you! Your 10% code is on its way to %s.';
  if (newsForm) {
    newsForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var email = newsForm.querySelector('input[type="email"]');
      if (!email || !email.value || email.value.indexOf('@') < 0) {
        if (newsMsg) {
          newsMsg.textContent = msgInvalid;
          newsMsg.style.color = '#fff';
        }
        return;
      }
      if (newsMsg) {
        newsMsg.textContent = msgSubscribed.replace('%s', email.value);
        newsMsg.style.color = '#fff';
      }
      newsForm.reset();
    });
  }

  /* ---------- Wishlist heart toggle + localStorage persistence ----------
     Key 'lc_wishlist' holds a JSON array of product ID strings so the
     Wishlist page template can render saved items via AJAX. */
  var WISH_KEY = 'lc_wishlist';
  function lcWishRead() {
    try {
      var raw = localStorage.getItem(WISH_KEY);
      var arr = raw ? JSON.parse(raw) : [];
      return Array.isArray(arr) ? arr : [];
    } catch (e) { return []; }
  }
  function lcWishWrite(ids) {
    try { localStorage.setItem(WISH_KEY, JSON.stringify(ids)); } catch (e) { /* ignore */ }
  }
  function lcWishSync() {
    var ids = lcWishRead();
    document.querySelectorAll('.lc-wishlist-btn[data-product-id]').forEach(function (btn) {
      var pid = btn.getAttribute('data-product-id');
      var on = pid && ids.indexOf(pid) !== -1;
      btn.classList.toggle('is-active', !!on);
      btn.setAttribute('aria-pressed', on ? 'true' : 'false');
    });
  }
  document.addEventListener('click', function (e) {
    var btn = e.target.closest ? e.target.closest('.lc-wishlist-btn') : null;
    if (!btn) return;
    var active = btn.classList.toggle('is-active');
    btn.setAttribute('aria-pressed', active ? 'true' : 'false');
    var pid = btn.getAttribute('data-product-id');
    if (pid) {
      var ids = lcWishRead();
      var i = ids.indexOf(pid);
      if (active && i === -1) ids.push(pid);
      if (!active && i !== -1) ids.splice(i, 1);
      lcWishWrite(ids);
    }
  });
  lcWishSync();

  /* ---------- Reveal on scroll ---------- */
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var revealEls = document.querySelectorAll('.lc-reveal');
  if (revealEls.length && 'IntersectionObserver' in window && !reduceMotion) {
    var revealObs = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          revealObs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });
    revealEls.forEach(function (el) { revealObs.observe(el); });
  } else {
    revealEls.forEach(function (el) { el.classList.add('is-visible'); });
  }

  /* ---------- Animated counters ---------- */
  function animateCount(el) {
    var target = parseFloat(el.getAttribute('data-lc-count'));
    if (isNaN(target)) return;
    var decimals = parseInt(el.getAttribute('data-lc-decimals') || '0', 10);
    var dur = 1400, start = null;
    function fmt(n) { return n.toFixed(decimals); }
    function step(ts) {
      if (!start) start = ts;
      var p = Math.min((ts - start) / dur, 1);
      var eased = 1 - Math.pow(1 - p, 3);
      el.textContent = fmt(target * eased);
      if (p < 1) requestAnimationFrame(step);
      else el.textContent = fmt(target);
    }
    if (reduceMotion) { el.textContent = fmt(target); return; }
    requestAnimationFrame(step);
  }
  var countEls = document.querySelectorAll('[data-lc-count]');
  if (countEls.length && 'IntersectionObserver' in window) {
    var countObs = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) { animateCount(entry.target); countObs.unobserve(entry.target); }
      });
    }, { threshold: 0.5 });
    countEls.forEach(function (el) { countObs.observe(el); });
  } else {
    countEls.forEach(animateCount);
  }

  /* ---------- 3D tilt + parallax floats (fine pointers only) ---------- */
  var finePointer = window.matchMedia('(pointer: fine)').matches;
  if (finePointer && !reduceMotion) {
    document.querySelectorAll('[data-lc-tilt]').forEach(function (tilt) {
      var raf = null;
      tilt.addEventListener('mousemove', function (e) {
        if (raf) return;
        raf = requestAnimationFrame(function () {
          raf = null;
          var r = tilt.getBoundingClientRect();
          var px = (e.clientX - r.left) / r.width - 0.5;
          var py = (e.clientY - r.top) / r.height - 0.5;
          tilt.style.transform = 'rotateY(' + (px * 7).toFixed(2) + 'deg) rotateX(' + (-py * 7).toFixed(2) + 'deg)';
          tilt.style.setProperty('--lc-px', (px * 16).toFixed(1) + 'px');
          tilt.style.setProperty('--lc-py', (py * 16).toFixed(1) + 'px');
        });
      });
      tilt.addEventListener('mouseleave', function () {
        tilt.style.transform = '';
        tilt.style.setProperty('--lc-px', '0px');
        tilt.style.setProperty('--lc-py', '0px');
      });
    });
  }

  /* ---------- Quick view modal ---------- */
  var qvVeil = document.getElementById('lc-qv-veil');
  var qvClose = document.getElementById('lc-qv-close');
  var qvImg = document.getElementById('lc-qv-img');
  var qvTitle = document.getElementById('lc-qv-title');
  var qvStars = document.getElementById('lc-qv-stars');
  var qvRating = document.getElementById('lc-qv-rating');
  var qvPrice = document.getElementById('lc-qv-price');
  var qvLink = document.getElementById('lc-qv-link');
  function openQV(trigger) {
    if (!qvVeil) return;
    if (qvImg) { qvImg.src = trigger.getAttribute('data-image') || ''; qvImg.alt = trigger.getAttribute('data-title') || ''; }
    if (qvTitle) qvTitle.textContent = trigger.getAttribute('data-title') || '';
    if (qvStars) qvStars.innerHTML = trigger.getAttribute('data-stars') || '';
    if (qvRating) qvRating.textContent = trigger.getAttribute('data-rating') || '';
    if (qvPrice) qvPrice.innerHTML = trigger.getAttribute('data-price') || '';
    if (qvLink) qvLink.href = trigger.getAttribute('data-url') || '#';
    qvVeil.setAttribute('aria-hidden', 'false');
    body.classList.add('lc-qv-open');
    if (qvClose) qvClose.focus();
  }
  function closeQV() {
    if (!qvVeil) return;
    qvVeil.setAttribute('aria-hidden', 'true');
    body.classList.remove('lc-qv-open');
  }
  document.addEventListener('click', function (e) {
    var trigger = e.target.closest ? e.target.closest('[data-lc-qv]') : null;
    if (trigger) { e.preventDefault(); e.stopPropagation(); openQV(trigger); return; }
    if (qvVeil && e.target === qvVeil) closeQV();
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' || e.key === ' ') {
      var trigger = e.target.closest ? e.target.closest('[data-lc-qv]') : null;
      if (trigger) { e.preventDefault(); openQV(trigger); }
    }
    if (e.key === 'Escape') closeQV();
  });
  if (qvClose) qvClose.addEventListener('click', closeQV);

})();
