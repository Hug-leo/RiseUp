# Development Plan — Rise Up Scholarship

## Current Version: 2.1

---

## Completed (v2.0 → v2.1)

- [x] Custom homepage with content pillar grid (4 groups)
- [x] Bilingual system (VI/EN) with cookie-based persistence
- [x] Announcement feed with category filtering + AJAX loading
- [x] Frontend post submission form (pending review)
- [x] Contact page with form + info cards + map
- [x] Post reactions (like/unlike) system
- [x] Custom Post Types: Events, Programs
- [x] Responsive design (mobile / tablet / desktop)
- [x] Scroll-reveal animations (IntersectionObserver)
- [x] i18n rebrand: Nuoitoi → Vươn Lên (VI) / Rise Up (EN)
- [x] CSS variable cleanup: `--red` → `--primary`
- [x] UI polish: hover effects, text shadows, mobile tweaks
- [x] Full documentation: README, dev guide, translation guide

---

## Next Priorities (v2.2)

| Priority | Feature | Description |
|----------|---------|-------------|
| 1 | **Media Gallery** | Photo/video gallery for Art and Nature Photography content |
| 2 | **Advanced Filters** | Multi-select category filtering on announcements page |
| 3 | **Search** | Full-text search across all content types |
| 4 | **Social Sharing** | Share buttons (Facebook, Zalo, copy link) on single posts |
| 5 | **SEO Meta** | Open Graph tags, JSON-LD schema for scholarship content |

---

## Backlog (Future)

- [ ] Dark mode toggle
- [ ] Email newsletter subscription
- [ ] Event calendar view (monthly/weekly)
- [ ] Alumni directory
- [ ] Admin dashboard widget for pending submissions count
- [ ] PWA support (offline reading for scholarship students)
- [ ] Performance: lazy load images, critical CSS inlining
- [ ] Accessibility audit (WCAG 2.1 AA)
- [ ] Multi-language URL slugs (`/su-kien/` vs `/events/`)

---

## Content Expansion Plan

The homepage content pillar grid is designed for easy expansion:

1. Edit the `$content_groups` array in `front-page.php`
2. Each group needs: `title_vi`, `title_en`, and 3 items with `vi`, `en`, `desc_vi`, `desc_en`
3. The CSS grid auto-adapts — no layout changes needed
4. Create a matching WordPress category for the announcements filter

### Planned Additional Groups

| Group (VI) | Group (EN) | When |
|------------|-----------|------|
| GIÁO DỤC | EDUCATION | v2.2 |
| CỘNG ĐỒNG | COMMUNITY | v2.3 |
| THỂ THAO | SPORTS | v2.3 |

---

## Technical Debt

| Item | Severity | Notes |
|------|----------|-------|
| `date('Y')` in footer.php | Low | Should use `wp_date()` for i18n |
| No rate limiting on submit form | Medium | Add throttle to prevent spam |
| Category names are single-language | Medium | Consider term meta for bilingual names |
| No image optimization pipeline | Low | Consider WebP conversion on upload |
