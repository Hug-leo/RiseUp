/* Rise Up Scholarship — student-map.js
 * Interactive choropleth for Bản đồ Vươn Lên (student origin map).
 * Requires: window.vuonlenMap (set via wp_localize_script)
 * Reads:    #student-map-63 SVG paths by province ISO code
 * Note: SVG is a static theme asset — no user-supplied content is rendered.
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var mapData = window.vuonlenMap;
    if (!mapData || !mapData.students) { return; }

    var tooltip         = document.getElementById('student-map-tooltip');
    var activeContainer = document.getElementById('student-map-63');

    // Apply fill classes to provinces with student data
    function applyData(container, students) {
      if (!container) { return; }
      // Reset all province fills first
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

    // Tooltip show/hide
    function showTooltip(e, path) {
      if (!tooltip) { return; }
      var lang  = mapData.lang || 'vi';
      var label = lang === 'en' ? (path.dataset.labelEn || path.dataset.labelVi) : path.dataset.labelVi;
      var count = path.dataset.count;
      var unit  = lang === 'en' ? ' students' : ' sinh viên';
      tooltip.textContent = label + ': ' + count + unit;
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

    // Bind hover events to active container
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

    // Initial render
    applyData(activeContainer, mapData.students);
    bindEvents(activeContainer);
  });
}());
