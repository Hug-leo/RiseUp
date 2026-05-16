/* Rise Up Scholarship — student-map.js
 * Interactive choropleth for Bản đồ Vươn Lên (student origin map).
 * Requires: window.vuonlenMap (set via wp_localize_script)
 * Reads:    #student-map-63 and #student-map-34 SVG paths
 * Note: SVG files are static theme assets — no user-supplied content is rendered.
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

    // Apply fill classes to provinces with student data
    function applyData(container, students) {
      if (!container) { return; }
      container.querySelectorAll('path').forEach(function (path) {
        path.classList.remove('province--low', 'province--mid', 'province--high', 'province--active');
      });
      students.forEach(function (entry) {
        var path = container.querySelector('#' + entry.code);
        if (!path) { return; }
        path.dataset.labelVi = entry.label_vi;
        path.dataset.labelEn = entry.label_en;
        path.dataset.count   = entry.count;
        path.classList.add('province--active');
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
    function showTooltip(e, path) {
      if (!tooltip) { return; }
      var lang  = mapData.lang || 'vi';
      var label = lang === 'en' ? (path.dataset.labelEn || path.dataset.labelVi) : path.dataset.labelVi;
      var unit  = lang === 'en' ? ' students' : ' sinh viên';
      tooltip.textContent = label + ': ' + path.dataset.count + unit;
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

    function bindEvents(container) {
      if (!container) { return; }
      container.querySelectorAll('path.province--active').forEach(function (path) {
        path.addEventListener('mouseenter', function (e) { showTooltip(e, path); });
        path.addEventListener('mousemove',  function (e) { positionTooltip(e); });
        path.addEventListener('mouseleave', hideTooltip);
        path.addEventListener('touchstart', function (e) {
          e.preventDefault();
          showTooltip(e, path);
        }, { passive: false });
        path.addEventListener('touchend', function () {
          setTimeout(hideTooltip, 1800);
        });
      });
    }

    // Toggle between 63 and 34 province maps
    function switchMap(toKey) {
      // Only accept known map keys to prevent unexpected DOM manipulation
      if (!containers[toKey]) { return; }
      if (toKey === activeMap) { return; }
      hideTooltip();
      // Swap SVG containers
      Object.keys(containers).forEach(function (key) {
        var c = containers[key];
        if (!c) { return; }
        c.classList.toggle('active', key === toKey);
        c.setAttribute('aria-hidden', key !== toKey ? 'true' : 'false');
      });
      // Swap toggle button active state
      toggleBtns.forEach(function (btn) {
        btn.classList.toggle('active', btn.dataset.map === toKey);
      });
      activeMap = toKey;
    }

    // Init: apply data + bind events for both maps
    var students63 = mapData.students    || [];
    var students34 = mapData.students_34 || [];

    applyData(containers['63'], students63);
    bindEvents(containers['63']);
    applyData(containers['34'], students34);
    bindEvents(containers['34']);

    // Wire toggle buttons
    toggleBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        switchMap(btn.dataset.map);
      });
    });
  });
}());
