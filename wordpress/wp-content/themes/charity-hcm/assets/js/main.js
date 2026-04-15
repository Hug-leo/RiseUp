/* Vuon Len Scholarship — main.js (v2 — vertical feed) */
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

    document.addEventListener('click', (e) => {
      if (!nav.contains(e.target) && !toggle.contains(e.target) && nav.classList.contains('open')) {
        nav.classList.remove('open');
        toggle.classList.remove('active');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      }
    });

    // Mobile sub-menu accordion
    nav.querySelectorAll('.nav-menu > li').forEach((item) => {
      const sub = item.querySelector('.sub-menu');
      if (!sub) return;
      const link = item.querySelector('a');
      if (link) {
        link.addEventListener('click', (e) => {
          if (window.innerWidth <= 768) {
            e.preventDefault();
            item.classList.toggle('open');
          }
        });
      }
    });
  }

  // ── Feed Category Tabs ──────────────────────────────────────────────────
  const feedTabs = document.querySelectorAll('.feed-tabs__btn');
  const feedContainer = document.getElementById('stories-feed');
  const loadMoreBtn = document.getElementById('news-load-more');

  if (feedTabs.length && feedContainer && typeof charityHCM !== 'undefined') {
    feedTabs.forEach((tab) => {
      tab.addEventListener('click', () => {
        feedTabs.forEach((t) => t.classList.remove('active'));
        tab.classList.add('active');

        const catId = tab.dataset.cat || '0';
        if (loadMoreBtn) {
          loadMoreBtn.dataset.cat = catId;
          loadMoreBtn.dataset.page = '1';
        }

        // Load fresh feed for category
        feedContainer.style.opacity = '0.5';

        const formData = new FormData();
        formData.append('action', 'load_more_posts');
        formData.append('nonce', charityHCM.nonce);
        formData.append('page', '1');
        formData.append('cat', catId);

        fetch(charityHCM.ajaxurl, { method: 'POST', body: formData })
          .then((r) => r.json())
          .then((data) => {
            if (data.success) {
              feedContainer.innerHTML = data.data.html;
              if (loadMoreBtn) {
                loadMoreBtn.dataset.page = '2';
                loadMoreBtn.style.display = data.data.hasMore ? '' : 'none';
                loadMoreBtn.textContent = charityHCM.loadMoreText || 'Load more stories';
              }
              initAnimations();
              initLikeButtons();
            } else {
              feedContainer.innerHTML = '<p class="no-content">No posts found.</p>';
              if (loadMoreBtn) loadMoreBtn.style.display = 'none';
            }
          })
          .catch(() => {
            feedContainer.innerHTML = '<p class="no-content">Error loading posts.</p>';
          })
          .finally(() => {
            feedContainer.style.opacity = '1';
          });
      });
    });
  }

  // ── Load More Posts (AJAX) ───────────────────────────────────────────────
  const morePostsEl = document.getElementById('news-more-posts');

  if (loadMoreBtn && morePostsEl && typeof charityHCM !== 'undefined') {
    let loading = false;

    loadMoreBtn.addEventListener('click', () => {
      if (loading) return;
      loading = true;
      loadMoreBtn.classList.add('loading');
      const origText = loadMoreBtn.textContent;
      loadMoreBtn.textContent = 'Loading…';

      const currentPage = parseInt(loadMoreBtn.dataset.page || '2', 10);
      const formData = new FormData();
      formData.append('action', 'load_more_posts');
      formData.append('nonce', charityHCM.nonce);
      formData.append('page', String(currentPage));
      formData.append('cat', loadMoreBtn.dataset.cat || '0');

      fetch(charityHCM.ajaxurl, { method: 'POST', body: formData })
        .then((r) => r.json())
        .then((data) => {
          if (data.success) {
            const frag = document.createElement('div');
            frag.innerHTML = data.data.html;
            Array.from(frag.children).forEach((el) => {
              el.classList.add('animate-in');
              morePostsEl.appendChild(el);
              requestAnimationFrame(() => {
                el.classList.add('visible');
              });
            });
            loadMoreBtn.dataset.page = String(currentPage + 1);
            if (!data.data.hasMore) {
              loadMoreBtn.style.display = 'none';
            } else {
              loadMoreBtn.textContent = origText;
            }
            initLikeButtons();
          } else {
            loadMoreBtn.textContent = 'No more posts';
            loadMoreBtn.disabled = true;
          }
        })
        .catch(() => {
          loadMoreBtn.textContent = 'Error loading';
        })
        .finally(() => {
          loading = false;
          loadMoreBtn.classList.remove('loading');
        });
    });
  }

  // ── Like / Reaction buttons ─────────────────────────────────────────────
  function initLikeButtons() {
    document.querySelectorAll('.reaction-like-btn').forEach((btn) => {
      if (btn.dataset.bound) return;
      btn.dataset.bound = '1';

      const postId = btn.dataset.postId;
      // Check cookie for liked state
      if (document.cookie.includes('vuonlen_liked_' + postId + '=')) {
        btn.classList.add('liked');
      }

      btn.addEventListener('click', (e) => {
        e.preventDefault();
        if (typeof charityHCM === 'undefined') return;

        const formData = new FormData();
        formData.append('action', 'toggle_post_like');
        formData.append('nonce', charityHCM.nonce);
        formData.append('post_id', postId);

        fetch(charityHCM.ajaxurl, { method: 'POST', body: formData })
          .then((r) => r.json())
          .then((data) => {
            if (data.success) {
              const countEl = btn.querySelector('.like-count');
              if (countEl) {
                countEl.textContent = data.data.likes > 0 ? data.data.likes : '';
              }
              btn.classList.toggle('liked', data.data.liked);

              // Animate
              btn.style.transform = 'scale(1.2)';
              setTimeout(() => { btn.style.transform = ''; }, 200);
            }
          });
      });
    });

    // Also handle single-post reaction buttons
    document.querySelectorAll('.reaction-btn[data-post-id]').forEach((btn) => {
      if (btn.dataset.bound) return;
      btn.dataset.bound = '1';

      const postId = btn.dataset.postId;
      if (document.cookie.includes('vuonlen_liked_' + postId + '=')) {
        btn.classList.add('active');
      }

      btn.addEventListener('click', (e) => {
        e.preventDefault();
        if (typeof charityHCM === 'undefined') return;

        const formData = new FormData();
        formData.append('action', 'toggle_post_like');
        formData.append('nonce', charityHCM.nonce);
        formData.append('post_id', postId);

        fetch(charityHCM.ajaxurl, { method: 'POST', body: formData })
          .then((r) => r.json())
          .then((data) => {
            if (data.success) {
              const countEl = btn.querySelector('.reaction-count');
              if (countEl) {
                countEl.textContent = data.data.likes;
              }
              btn.classList.toggle('active', data.data.liked);
              btn.style.transform = 'scale(1.1)';
              setTimeout(() => { btn.style.transform = ''; }, 200);
            }
          });
      });
    });
  }

  // ── Scroll animations (IntersectionObserver) ─────────────────────────────
  function initAnimations() {
    if (!('IntersectionObserver' in window)) return;

    const targets = document.querySelectorAll('.animate-in:not(.visible)');
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    targets.forEach((el) => observer.observe(el));
  }

  // ── Back to Top Button ───────────────────────────────────────────────
  const backToTop = document.getElementById('back-to-top');
  if (backToTop) {
    window.addEventListener('scroll', () => {
      backToTop.classList.toggle('visible', window.scrollY > 400);
    }, { passive: true });

    backToTop.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // ── Stat Counter Animation ──────────────────────────────────────────
  const counters = document.querySelectorAll('.stat-card__number[data-count]');
  if (counters.length && 'IntersectionObserver' in window) {
    const counterObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const el = entry.target;
          const target = parseInt(el.getAttribute('data-count'), 10);
          const suffix = el.textContent.replace(/[0-9]/g, '');
          const duration = 1500;
          const startTime = performance.now();

          const animate = (now) => {
            const elapsed = now - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
            const current = Math.round(eased * target);
            el.textContent = current + suffix;
            if (progress < 1) requestAnimationFrame(animate);
          };
          requestAnimationFrame(animate);
          counterObserver.unobserve(el);
        }
      });
    }, { threshold: 0.3 });

    counters.forEach((el) => counterObserver.observe(el));
  }

  // ── Init on load ───────────────────────────────────────────────────────
  initAnimations();
  initLikeButtons();

})();