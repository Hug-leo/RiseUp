/* Charity HCM — main.js */
(function () {
  'use strict';

  // ── Sticky header shadow ───────────────────────────────────────────────
  const header = document.getElementById('masthead');
  if (header) {
    window.addEventListener('scroll', () => {
      header.classList.toggle('scrolled', window.scrollY > 40);
    }, { passive: true });
  }

  // ── Mobile nav toggle ──────────────────────────────────────────────────
  const toggle = document.querySelector('.nav-toggle');
  const nav    = document.getElementById('site-navigation');

  if (toggle && nav) {
    toggle.addEventListener('click', () => {
      const open = nav.classList.toggle('open');
      toggle.classList.toggle('active', open);
      toggle.setAttribute('aria-expanded', String(open));
      document.body.style.overflow = open ? 'hidden' : '';
    });

    // Close on outside click
    document.addEventListener('click', (e) => {
      if (!nav.contains(e.target) && !toggle.contains(e.target) && nav.classList.contains('open')) {
        nav.classList.remove('open');
        toggle.classList.remove('active');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      }
    });

    // Mobile sub-menu accordion
    const topItems = nav.querySelectorAll('.nav-menu > li');
    topItems.forEach((item) => {
      const sub = item.querySelector('.sub-menu');
      if (!sub) return;

      const link = item.querySelector('a');
      if (link && window.innerWidth <= 768) {
        link.addEventListener('click', (e) => {
          if (window.innerWidth <= 768) {
            e.preventDefault();
            item.classList.toggle('open');
          }
        });
      }
    });
  }

  // ── Events carousel ─────────────────────────────────────────────────────
  const track    = document.getElementById('events-track');
  const prevBtn  = document.getElementById('events-prev');
  const nextBtn  = document.getElementById('events-next');
  const SCROLL_AMOUNT = 300;

  if (track) {
    if (prevBtn) prevBtn.addEventListener('click', () => track.scrollBy({ left: -SCROLL_AMOUNT, behavior: 'smooth' }));
    if (nextBtn) nextBtn.addEventListener('click', () => track.scrollBy({ left:  SCROLL_AMOUNT, behavior: 'smooth' }));

    // Drag-to-scroll
    let isDown = false, startX, scrollLeft;

    track.addEventListener('mousedown', (e) => {
      isDown = true;
      track.classList.add('grabbing');
      startX     = e.pageX - track.offsetLeft;
      scrollLeft = track.scrollLeft;
    });
    track.addEventListener('mouseleave', () => { isDown = false; track.classList.remove('grabbing'); });
    track.addEventListener('mouseup',    () => { isDown = false; track.classList.remove('grabbing'); });
    track.addEventListener('mousemove',  (e) => {
      if (!isDown) return;
      e.preventDefault();
      const x = e.pageX - track.offsetLeft;
      track.scrollLeft = scrollLeft - (x - startX) * 1.5;
    });

    // Touch swipe
    let touchStartX = 0;
    track.addEventListener('touchstart', (e) => { touchStartX = e.touches[0].clientX; }, { passive: true });
    track.addEventListener('touchmove',  (e) => {
      const diff = touchStartX - e.touches[0].clientX;
      track.scrollLeft += diff * 0.8;
      touchStartX = e.touches[0].clientX;
    }, { passive: true });

    // Show/hide nav arrows based on scroll position
    const updateNavArrows = () => {
      if (!prevBtn || !nextBtn) return;
      prevBtn.style.opacity = track.scrollLeft > 10 ? '1' : '0.3';
      nextBtn.style.opacity = (track.scrollLeft + track.clientWidth) < (track.scrollWidth - 10) ? '1' : '0.3';
    };
    track.addEventListener('scroll', updateNavArrows, { passive: true });
    updateNavArrows();
  }

  // ── Load More Posts (AJAX) ───────────────────────────────────────────────
  const loadMoreBtn  = document.getElementById('news-load-more');
  const morePostsEl  = document.getElementById('news-more-posts');

  if (loadMoreBtn && morePostsEl && typeof charityHCM !== 'undefined') {
    let currentPage = 2;
    let loading     = false;

    loadMoreBtn.addEventListener('click', () => {
      if (loading) return;
      loading = true;
      loadMoreBtn.classList.add('loading');
      loadMoreBtn.textContent = 'Đang tải…';

      const formData = new FormData();
      formData.append('action', 'load_more_posts');
      formData.append('nonce',  charityHCM.nonce);
      formData.append('page',   String(currentPage));
      formData.append('cat',    loadMoreBtn.dataset.cat || '0');

      fetch(charityHCM.ajaxurl, { method: 'POST', body: formData })
        .then((r) => r.json())
        .then((data) => {
          if (data.success) {
            const frag = document.createElement('div');
            frag.innerHTML = data.data.html;
            // animate in
            Array.from(frag.children).forEach((el) => {
              el.style.opacity = '0';
              el.style.transform = 'translateY(20px)';
              morePostsEl.appendChild(el);
              requestAnimationFrame(() => {
                el.style.transition = 'opacity .4s ease, transform .4s ease';
                el.style.opacity    = '1';
                el.style.transform  = 'translateY(0)';
              });
            });
            currentPage++;
            if (!data.data.hasMore) {
              loadMoreBtn.style.display = 'none';
            } else {
              loadMoreBtn.textContent = 'Xem thêm tin tức';
            }
          } else {
            loadMoreBtn.textContent = 'Không còn bài nào';
            loadMoreBtn.disabled    = true;
          }
        })
        .catch(() => {
          loadMoreBtn.textContent = 'Lỗi tải dữ liệu';
        })
        .finally(() => {
          loading = false;
          loadMoreBtn.classList.remove('loading');
        });
    });
  }

  // ── Smooth entrance animations (IntersectionObserver) ────────────────────
  if ('IntersectionObserver' in window) {
    const targets = document.querySelectorAll(
      '.stat-card, .news-card, .event-card, .program-card, .about__text, .about__stats'
    );
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.style.animation = 'fadeUp .5s ease forwards';
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    targets.forEach((el) => {
      el.style.opacity = '0';
      observer.observe(el);
    });
  }

  // ── Add fadeUp animation keyframe dynamically ─────────────────────────
  const styleEl = document.createElement('style');
  styleEl.textContent = `
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0); }
    }
  `;
  document.head.appendChild(styleEl);

})();
