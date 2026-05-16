# Requirements: Rise Up Scholarship Website

**Defined:** 2026-05-16
**Core Value:** A living community archive where members discover, share, and feel belonging through the 5 content sections.

## v1 Requirements — Current Milestone

Requirements for the current improvement milestone. Goal: realize all sections beautifully, add missing UI polish, and update README.

### Content Sections (5 Pillars)

- [x] **SECT-01**: All 5 content sections are defined as WordPress categories (auto-created at theme init) — *already done*
- [x] **SECT-02**: Navigation menu shows all 5 sections with dropdown subsections — *already done*
- [x] **SECT-03**: Front-page displays all 5 section group cards with title, summary, and subsection list — *already done*
- [ ] **SECT-04**: Each section group card has a distinct visual icon representing its theme
- [ ] **SECT-05**: Section group cards on the front page use accent colors or visual differentiation per group
- [ ] **SECT-06**: Category archive pages for each section show a rich header with icon, description, and child section links

### Homepage UI

- [x] **HOME-01**: Hero section with logo, tagline, headline, subtitle, and two CTA buttons — *already done*
- [x] **HOME-02**: Content roadmap section shows all 5 groups in a grid — *already done*
- [x] **HOME-03**: Vietnam map placeholder section (Bản đồ Vươn Lên) with pins — *already done*
- [x] **HOME-04**: Recent posts grid (latest 4 posts) — *already done*
- [ ] **HOME-05**: Section group cards have smooth hover animations and feel visually polished (not flat/plain)
- [ ] **HOME-06**: Map section uses a locally hosted Vietnam map image (not external CDN)
- [ ] **HOME-07**: Map section pins animate (pulse effect) to suggest interactivity

### Section-Specific UX

- [x] **SEC-01**: Category pages show breadcrumb + page banner with section description — *already done*
- [x] **SEC-02**: Bản đồ Vươn Lên category page shows map demo with context text — *already done*
- [ ] **SEC-03**: Tổng hợp bài hát category page shows a song library layout (searchable list, song cards)
- [ ] **SEC-04**: Bí kíp category page has a tips/card grid layout distinct from regular post list
- [ ] **SEC-05**: Gương mặt Vươn Lên category page has a profile-card layout (not generic post list)

### Visual / UI Polish

- [ ] **UI-01**: CSS deduplication — remove ~200+ lines of repeated CSS blocks in main.css
- [ ] **UI-02**: Section group cards on front-page have section-specific emoji or SVG icons
- [ ] **UI-03**: Consistent spacing and visual hierarchy across all section pages
- [ ] **UI-04**: Mobile nav works correctly for all 5 section dropdowns
- [ ] **UI-05**: Smooth `animate-in` scroll animations on section cards (currently CSS class exists but may need JS trigger)

### Navigation & Structure

- [x] **NAV-01**: Primary menu renders all 5 sections + subsections from `charity_content_groups()` — *already done*
- [x] **NAV-02**: Footer menu includes section links — *already done*
- [ ] **NAV-03**: Social links (Facebook, YouTube, Instagram) in topbar are wired to real URLs via WordPress Customizer

### SEO & Meta

- [ ] **SEO-01**: Each page has a unique `<title>` tag (already via WordPress `title-tag` support)
- [ ] **SEO-02**: Open Graph meta tags (og:title, og:description, og:image) on post and category pages

### README & Documentation

- [ ] **DOC-01**: README.md reflects all 5 content sections, updated screenshots, and current theme version
- [ ] **DOC-02**: README.md includes quick-start instructions that are accurate for current setup

### Province Detail Pages (Phase 7)

- [ ] **MAP-DETAIL-01**: Clicking a highlighted province on the student map navigates to a dedicated province detail page at `/tinh/{slug}/`
- [ ] **MAP-DETAIL-02**: Each province detail page displays a signature JPG photo and a contact table listing Đông Du members in that province
- [ ] **MAP-DETAIL-03**: Province signature photos are stored locally in `assets/img/provinces/`, JPG format, compressed to ≤ 150KB each

## v2 Requirements — Future Milestone

Deferred but tracked. Not in current roadmap.

### Interactive Map (Bản đồ Vươn Lên)
- **MAP-01**: Interactive Vietnam map (Leaflet.js) with member location pins
- **MAP-02**: Members can register their province/location via profile settings
- **MAP-03**: Filter pins by member type (active, alumni) and purpose (study, work, travel)

### Song Library Full Feature
- **SONG-01**: Song cards with lyrics display (expandable)
- **SONG-02**: Search/filter by song type (traditional, community, upbeat)
- **SONG-03**: Song submission by members (pending moderation)

### Community Features
- **COMM-01**: Member profile pages (beyond WP default author pages)
- **COMM-02**: Newsletter subscription for new posts per section
- **COMM-03**: Activity feed showing recent member contributions across all 5 sections

## Out of Scope

| Feature | Reason |
|---------|--------|
| Full interactive member location map | Requires user accounts, privacy policy, and Phase 3 backend |
| Plugin dependencies | Keep theme self-contained; no new WP plugins for this milestone |
| Payment / donation integration | Not part of content milestone |
| Full automated test suite | Not yet needed at this project scale |
| React/Vue frontend rewrite | Existing PHP theme is working; not worth migration cost |

## Traceability

| Requirement | Phase | Status |
|-------------|-------|--------|
| SECT-04, SECT-05 | Phase 2 | Pending |
| SECT-06 | Phase 2 | Pending |
| HOME-05, HOME-06, HOME-07 | Phase 2 | Pending |
| SEC-03, SEC-04, SEC-05 | Phase 3 | Pending |
| UI-01, UI-02, UI-03, UI-04, UI-05 | Phase 2 | Pending |
| NAV-03 | Phase 4 | Pending |
| SEO-01, SEO-02 | Phase 4 | Pending |
| DOC-01, DOC-02 | Phase 5 | Pending |
| MAP-DETAIL-01, MAP-DETAIL-02, MAP-DETAIL-03 | Phase 7 | Pending |

**Coverage:**
- v1 requirements: 24 total
- Already done (existing): 11
- To implement: 13
- Mapped to phases: 13
- Unmapped: 0 ✓

---
*Requirements defined: 2026-05-16*
*Last updated: 2026-05-16 after initialization*
