/* Rise Up Scholarship — student-map.js
 * Interactive choropleth for Bản đồ Vươn Lên (student origin map).
 * Requires: window.vuonlenMap (set via wp_localize_script)
 *
 * Province codes follow VietMap administrative data:
 *   github.com/vietmap-company/vietnam_administrative_address
 *   Old 63 provinces: p11–p73  |  New 34 provinces: p11–p44
 *
 * Note: SVG files are static theme assets — no user-supplied content rendered.
 * Contact data (vuonlenMap.contacts) is server-side JSON, not user input.
 */
(function () {
  'use strict';

  // Escape HTML special characters — prevents XSS if data ever changes source.
  function esc(s) {
    var d = document.createElement('div');
    d.textContent = String(s || '');
    return d.innerHTML;
  }

  // Allow only relative URLs (starts with / or #) for contact links.
  function safeHref(url) {
    var s = String(url || '#');
    return /^[/#]/.test(s) ? s : '#';
  }

  document.addEventListener('DOMContentLoaded', function () {
    var mapData = window.vuonlenMap;
    if (!mapData || !mapData.students) { return; }

    var tooltip    = document.getElementById('student-map-tooltip');
    var containers = {
      '63': document.getElementById('student-map-63'),
      '34': document.getElementById('student-map-34'),
    };
    var toggleBtns = document.querySelectorAll('.map-toggle-btn');
    var activeMap  = '63';

    // ── Province contact popup ────────────────────────────────────────────────
    var popup = document.getElementById('province-popup');
    if (!popup) {
      popup = document.createElement('div');
      popup.id        = 'province-popup';
      popup.className = 'province-popup';
      popup.setAttribute('role',            'dialog');
      popup.setAttribute('aria-modal',      'true');
      popup.setAttribute('aria-labelledby', 'province-popup-title');
      popup.innerHTML =
        '<button class="province-popup__close" aria-label="Đóng">&#x2715;</button>' +
        '<h3 class="province-popup__title" id="province-popup-title"></h3>' +
        '<ul class="province-popup__members" aria-label="Danh sách thành viên"></ul>';
      document.body.appendChild(popup);
    }

    var popupTitle   = popup.querySelector('.province-popup__title');
    var popupMembers = popup.querySelector('.province-popup__members');
    var popupClose   = popup.querySelector('.province-popup__close');

    // Derive initials from the last word of a Vietnamese name (given name).
    function getInitials(name) {
      var parts = String(name).trim().split(/\s+/);
      return parts[parts.length - 1].charAt(0).toUpperCase();
    }

    function openPopup(pathId, is34) {
      var label    = getLabel(pathId, is34);
      var contacts = (mapData.contacts && mapData.contacts[pathId]) || null;
      var members  = contacts ? (contacts.members || []) : [];

      popupTitle.textContent = label;

      if (!members.length) {
        popupMembers.innerHTML =
          '<li class="province-popup__empty">Chưa có thông tin liên hệ</li>';
      } else {
        popupMembers.innerHTML = members.map(function (m) {
          return (
            '<li class="province-popup__member">' +
              '<span class="province-popup__avatar">' + esc(getInitials(m.name)) + '</span>' +
              '<span class="province-popup__info">' +
                '<span class="province-popup__name">' + esc(m.name) + '</span>' +
                '<span class="province-popup__role">' + esc(m.role) + '</span>' +
                '<a class="province-popup__link" href="' + safeHref(m.contact) + '">Liên hệ ›</a>' +
              '</span>' +
            '</li>'
          );
        }).join('');
      }

      popup.classList.add('province-popup--open');
      popupClose.focus();
    }

    function closePopup() {
      popup.classList.remove('province-popup--open');
    }

    popupClose.addEventListener('click', closePopup);

    // Close on outside click
    document.addEventListener('click', function (e) {
      if (
        popup.classList.contains('province-popup--open') &&
        !popup.contains(e.target) &&
        !e.target.closest('.student-map__svg-container')
      ) {
        closePopup();
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') { closePopup(); }
    });

    // ── Province label helper ─────────────────────────────────────────────────
    function getLabel(pathId, is34) {
      var lang      = mapData.lang || 'vi';
      var lookupKey = is34 ? 'provinces_34' : 'provinces';
      var lookup    = mapData[lookupKey] || {};
      var entry     = lookup[pathId];
      if (!entry) { return pathId; }
      return lang === 'en' ? (entry['en'] || entry['vi']) : entry['vi'];
    }

    // ── Apply student data fill classes ──────────────────────────────────────
    function applyData(container, students, is34) {
      if (!container) { return; }
      container.querySelectorAll('path').forEach(function (path) {
        path.classList.remove('province--low', 'province--mid', 'province--high', 'province--active');
        path.classList.add('province--hover');
      });
      students.forEach(function (entry) {
        var path = container.querySelector('#' + entry.code);
        if (!path) { return; }
        path.dataset.count = entry.count;
        path.classList.add('province--active');
        path.classList.remove('province--hover');
        if (entry.count >= 8) {
          path.classList.add('province--high');
        } else if (entry.count >= 4) {
          path.classList.add('province--mid');
        } else {
          path.classList.add('province--low');
        }
      });
    }

    // ── Tooltip (hover) ───────────────────────────────────────────────────────
    function showTooltip(e, path, is34) {
      if (!tooltip) { return; }
      var label = getLabel(path.id, is34);
      var count = path.dataset.count;
      var lang  = mapData.lang || 'vi';
      var text  = count
        ? label + ': ' + count + (lang === 'en' ? ' students' : ' sinh viên')
        : label;
      tooltip.textContent = text;
      tooltip.classList.add('visible');
      positionTooltip(e);
    }

    function hideTooltip() {
      if (tooltip) { tooltip.classList.remove('visible'); }
    }

    function positionTooltip(e) {
      if (!tooltip) { return; }
      var wrap = document.querySelector('.student-map__wrap');
      var rect = wrap ? wrap.getBoundingClientRect() : { left: 0, top: 0 };
      var x    = (e.clientX || (e.touches && e.touches[0] && e.touches[0].clientX) || 0) - rect.left + 12;
      var y    = (e.clientY || (e.touches && e.touches[0] && e.touches[0].clientY) || 0) - rect.top  - 36;
      tooltip.style.left = x + 'px';
      tooltip.style.top  = y + 'px';
    }

    // ── Event binding ─────────────────────────────────────────────────────────
    function bindEvents(container, is34) {
      if (!container) { return; }
      container.querySelectorAll('path').forEach(function (path) {
        path.style.cursor = 'pointer';

        path.addEventListener('mouseenter', function (e) { showTooltip(e, path, is34); });
        path.addEventListener('mousemove',  function (e) { positionTooltip(e); });
        path.addEventListener('mouseleave', hideTooltip);

        // Add province-clickable class for provinces with a slug (enables cursor CSS)
        var contactInfo = mapData.contacts && mapData.contacts[path.id];
        if (contactInfo && contactInfo.slug) {
          path.classList.add('province-clickable');
        }

        path.addEventListener('click', function () {
          var code = path.id;
          var contactData = mapData.contacts && mapData.contacts[code];
          if (contactData && contactData.slug) {
            window.location.href = '/tinh/' + contactData.slug + '/';
            return; // Navigate — do not show popup
          }
          hideTooltip();
          openPopup(path.id, is34);
        });

        path.addEventListener('touchstart', function (e) {
          e.preventDefault();
          showTooltip(e, path, is34);
        }, { passive: false });

        path.addEventListener('touchend', function () {
          var code = path.id;
          var contactData = mapData.contacts && mapData.contacts[code];
          if (contactData && contactData.slug) {
            window.location.href = '/tinh/' + contactData.slug + '/';
            return; // Navigate on touch for member provinces
          }
          hideTooltip();
          openPopup(path.id, is34);
        });
      });
    }

    // ── Map toggle ────────────────────────────────────────────────────────────
    function switchMap(toKey) {
      if (!containers[toKey] || toKey === activeMap) { return; }
      hideTooltip();
      closePopup();
      Object.keys(containers).forEach(function (key) {
        var c = containers[key];
        if (!c) { return; }
        c.classList.toggle('active', key === toKey);
        c.setAttribute('aria-hidden', key !== toKey ? 'true' : 'false');
      });
      toggleBtns.forEach(function (btn) {
        btn.classList.toggle('active', btn.dataset.map === toKey);
      });
      activeMap = toKey;
    }

    // Init both maps
    applyData(containers['63'], mapData.students    || [], false);
    bindEvents(containers['63'], false);
    applyData(containers['34'], mapData.students_34 || [], true);
    bindEvents(containers['34'], true);

    // Wire toggle buttons
    toggleBtns.forEach(function (btn) {
      btn.addEventListener('click', function () { switchMap(btn.dataset.map); });
    });
  });
}());
