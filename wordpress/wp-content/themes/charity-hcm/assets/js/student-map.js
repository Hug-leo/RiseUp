/* Rise Up Scholarship — student-map.js
 * Interactive choropleth for Bản đồ Vươn Lên (student origin map).
 * Requires: window.vuonlenMap (set via wp_localize_script)
 *
 * Province codes follow VietMap administrative data:
 *   github.com/vietmap-company/vietnam_administrative_address
 *   Old 63 provinces: p11–p73  |  New 34 provinces: p11–p44
 *
 * Note: SVG files are static theme assets — no user-supplied content rendered.
 */
(function () {
  'use strict';

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

    // Resolve province label from lookup table (falls back to path id)
    function getLabel(pathId, is34) {
      var lang      = mapData.lang || 'vi';
      var lookupKey = is34 ? 'provinces_34' : 'provinces';
      var lookup    = mapData[lookupKey] || {};
      var entry     = lookup[pathId];
      if (!entry) { return pathId; }
      return lang === 'en' ? (entry['en'] || entry['vi']) : entry['vi'];
    }

    // Apply fill classes to provinces with student data
    function applyData(container, students, is34) {
      if (!container) { return; }
      container.querySelectorAll('path').forEach(function (path) {
        path.classList.remove('province--low', 'province--mid', 'province--high', 'province--active');
        // Enable hover for ALL provinces using name lookup
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

    // Tooltip
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

    function bindEvents(container, is34) {
      if (!container) { return; }
      container.querySelectorAll('path').forEach(function (path) {
        path.addEventListener('mouseenter', function (e) { showTooltip(e, path, is34); });
        path.addEventListener('mousemove',  function (e) { positionTooltip(e); });
        path.addEventListener('mouseleave', hideTooltip);
        path.addEventListener('touchstart', function (e) {
          e.preventDefault();
          showTooltip(e, path, is34);
        }, { passive: false });
        path.addEventListener('touchend', function () {
          setTimeout(hideTooltip, 1800);
        });
      });
    }

    // Toggle between 63 and 34 province maps
    function switchMap(toKey) {
      if (!containers[toKey]) { return; }
      if (toKey === activeMap) { return; }
      hideTooltip();
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
