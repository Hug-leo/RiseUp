# ROADMAP.md

## Project: Rise Up Scholarship Website
**Milestone:** v1 — Section Beautification & UI Polish
**Goal:** Fully realize all 5 content sections with polished UI, self-hosted assets, and updated documentation.
**Branch:** CONGPHAT

---

## Phase 1 — CSS Cleanup & Foundation Polish
**Goal:** Remove CSS duplication and lay a clean visual foundation before adding new features.
**Depends on:** nothing
**Covers:** UI-01, UI-03

### Plans
1. **Audit and deduplicate `main.css`** — identify and remove ~200+ lines of repeated CSS blocks (`.nav-menu`, `.hero`, `.btn` duplicates); keep only the canonical definitions
2. **Review and fix CSS specificity conflicts** from duplication — ensure hover states and transitions work correctly
3. **Verify visual output** after cleanup — no regressions on homepage, category pages, single posts, mobile nav

**UAT:**
- All existing pages still render correctly after CSS cleanup
- No visual regressions on desktop and mobile
- `main.css` line count reduced by at least 150 lines

---

## Phase 2 — Section Cards UI & Homepage Visual Polish
**Goal:** Make the 5 content group cards on the homepage visually distinctive, polished, and engaging.
**Depends on:** Phase 1
**Covers:** SECT-04, SECT-05, HOME-05, HOME-06, HOME-07, UI-02, UI-04, UI-05

### Plans
1. **Add section icons** — design or source SVG icons for each of the 5 content groups; add to `assets/img/icons/` and integrate into `cp-group-card` in `front-page.php`
2. **Section color accents** — assign a distinct accent color per content group (e.g., blue for TIN TỨC, green for ĐÔNG DU KÝ); update CSS variables and `.cp-group-card` styles
3. **Hover animations & card polish** — improve `.cp-group-card` hover states: lift shadow, icon color shift, smooth transition; ensure `animate-in` scroll trigger works
4. **Self-host Vietnam map image** — download map image to `assets/img/vietnam-map.png`; update `charity_vietnam_map_image_url()` to return local URI; add pulsing CSS animation to map pins
5. **Mobile nav fix** — verify all 5 section dropdowns work correctly on mobile (tap to expand)

**UAT:**
- Each section group card has a visible icon and distinct visual identity
- Hovering cards shows smooth lift + shadow effect
- Vietnam map uses local image (no external HTTP request)
- Map pins pulse with CSS animation
- Mobile nav opens/closes correctly for all 5 sections

---

## Phase 3 — Section-Specific Category Page Templates
**Goal:** Give each major content section a category page that reflects its unique content type.
**Depends on:** Phase 2
**Covers:** SECT-06, SEC-03, SEC-04, SEC-05

### Plans
1. **Enrich base category.php** — add section icon to page banner; improve subsection panel grid to use card layout with icons and hover states
2. **Song Library layout** (`tong-hop-bai-hat`) — special layout: song cards in a 2-column grid, each with song title, type tag, and expandable lyrics placeholder; add search/filter bar (client-side JS)
3. **Profile showcase layout** (`guong-mat-vuon-len`) — profile-card grid layout (photo placeholder, name, achievement summary, read-more link); distinct from generic post list
4. **Tips/knowledge card layout** (`bi-kip`, `the-gioi-quanh-ta`) — compact card grid with icon, title, and 2-line excerpt; different feel from long-form news

**UAT:**
- `tong-hop-bai-hat` category page shows song card layout (not generic post list)
- `guong-mat-vuon-len` category page shows profile card grid
- `bi-kip` category page shows compact tips card grid
- All layouts are responsive on mobile

---

## Phase 4 — Navigation, Social Links & SEO
**Goal:** Wire social links, improve SEO meta tags, and polish navigation UX.
**Depends on:** Phase 2
**Covers:** NAV-03, SEO-01, SEO-02

### Plans
1. **WordPress Customizer for social links** — register Customizer settings for Facebook, YouTube, Instagram URLs; update `header.php` topbar to use `get_theme_mod()` instead of `#`
2. **Open Graph meta tags** — add `og:title`, `og:description`, `og:image` to `header.php` for single posts and category pages; use post thumbnail as og:image
3. **Breadcrumb SEO** — verify and add `aria-label` to breadcrumb nav; ensure current page doesn't have a link (accessibility)

**UAT:**
- Social links in topbar point to real URLs set via WordPress Customizer
- Sharing a post URL on Facebook/Slack shows correct title, description, and thumbnail
- Breadcrumb is accessible (aria labels correct)

---

## Phase 5 — README & Documentation Update
**Goal:** Update README.md to accurately reflect the current state of the project.
**Depends on:** Phase 2, Phase 3
**Covers:** DOC-01, DOC-02

### Plans
1. **Update README.md** — rewrite highlights section to include all 5 content sections; update structure tree; add section descriptions from `Y_Tuong_De_Muc_Website_HBVL.md`; update tech stack info
2. **New screenshots** — capture updated homepage with section cards (after Phase 2) and a sample category page; update `docs/screenshots/`
3. **Update docs/PLAN.md** — reflect new milestone goals and completed work

**UAT:**
- README accurately describes all 5 sections and their purpose
- Screenshots in README match current UI
- Tech stack section is correct
- Quick-start instructions work on a fresh local setup

---

## Milestone Success Criteria

- [ ] All 5 sections visually realized with icons, colors, and distinct layouts
- [ ] Homepage section cards are polished and animated
- [ ] Vietnam map uses local image with animated pins
- [ ] CSS is deduplicated (~150+ lines removed)
- [ ] Song Library and profile showcase have distinct category page layouts
- [ ] Social links are wired via WordPress Customizer
- [ ] Open Graph meta tags work for sharing
- [ ] README.md is accurate and updated

---

## Backlog (Post-Milestone)

- Interactive Vietnam map with Leaflet.js (MAP-01 through MAP-03)
- Full song library with lyrics and search (SONG-01 through SONG-03)
- Member profile pages (COMM-01)
- Newsletter subscription (COMM-02)

---
*Roadmap created: 2026-05-16*
*Next step: Run `/gsd-plan-phase 1` to start execution.*
