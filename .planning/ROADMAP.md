# Roadmap: Rise Up Scholarship Website

## Overview

Milestone v1 — Section Beautification & UI Polish. Fully realize all 5 content sections from the HBVL proposal with polished UI, self-hosted assets, section-specific layouts, and updated documentation. Starting from an already-functional WordPress theme (`charity-hcm` v2.2.0) where all 5 sections are defined but need visual polish and distinct layouts.

## Phases

- [ ] **Phase 1: CSS Cleanup & Foundation Polish** - Remove CSS duplication and fix specificity issues to lay a clean foundation
- [x] **Phase 2: Section Cards UI & Homepage Visual Polish** - Add icons, color accents, and animated section cards to the homepage (completed 2026-05-16)
- [ ] **Phase 3: Section-Specific Category Page Templates** - Distinct layouts for Song Library, Profile showcase, and Tips grid
- [x] **Phase 4: Navigation, Social Links & SEO** - Wire social links via Customizer and add Open Graph meta tags (completed 2026-05-16)
- [ ] **Phase 5: README & Documentation Update** - Accurate README with updated screenshots and content architecture docs
- [ ] **Phase 6: Contact Section Update & Interactive Student Map** - Update contact info and add interactive Vietnam map with 34/63 province toggle

## Phase Details

### Phase 1: CSS Cleanup & Foundation Polish
**Goal**: Fix CSS specificity bugs, remove orphan rule fragments, and consolidate duplicate media query blocks in `main.css` to lay a clean foundation.
**Depends on**: Nothing (first phase)
**Requirements**: UI-01, UI-03
**Success Criteria** (what must be TRUE):
  1. `.btn--red:hover` selector exists and `.btn--primary:hover` appears exactly once
  2. `.cp-group-card` and `.cp-group-item` each have exactly one rule block (base + transition + hover combined)
  3. The "Phase 2: UI Polish" orphan comment section is removed; its rules merged into base blocks
  4. All `@media (max-width: 768px)` cp-* rules are consolidated into a single 768px block

Plans:
- [ ] 01-01: Audit and remove duplicated CSS blocks in main.css
- [ ] 01-02: Verify visual output and fix any regressions after cleanup

### Phase 2: Section Cards UI & Homepage Visual Polish
**Goal**: Make the 5 content group cards visually distinctive with icons, color accents, hover animations, and a locally-hosted Vietnam map with animated pins.
**Depends on**: Phase 1
**Requirements**: SECT-04, SECT-05, HOME-05, HOME-06, HOME-07, UI-02, UI-04, UI-05
**Success Criteria** (what must be TRUE):
  1. Each section group card has a distinct SVG icon
  2. Each section group has a unique accent color applied to its card
  3. Card hover lifts with shadow — smooth CSS transition
  4. Vietnam map uses a local image file (no external CDN request)
  5. Map pins animate with a CSS pulse effect
  6. Mobile nav works correctly for all 5 section dropdowns

Plans:
- [x] 02-01: Add section icons and color accents to cp-group-card components
- [x] 02-02: Polish card hover animations and animate-in scroll triggers
- [x] 02-03: Self-host Vietnam map image and add animated CSS pins

### Phase 3: Section-Specific Category Page Templates
**Goal**: Give Song Library, Profile showcase, and Tips sections custom category page layouts that match their content type.
**Depends on**: Phase 2
**Requirements**: SECT-06, SEC-03, SEC-04, SEC-05
**Success Criteria** (what must be TRUE):
  1. `tong-hop-bai-hat` category page shows a song card grid layout (not generic post list)
  2. `guong-mat-vuon-len` category page shows a profile card grid with photo and achievement summary
  3. `bi-kip` and `the-gioi-quanh-ta` category pages show a compact tips card grid
  4. All new layouts are responsive on mobile

Plans:
- [ ] 03-01: Add song library layout to tong-hop-bai-hat category page
- [ ] 03-02: Add profile card layout to guong-mat-vuon-len category page
- [ ] 03-03: Add compact tips card layout to bi-kip and the-gioi-quanh-ta category pages

### Phase 4: Navigation, Social Links & SEO
**Goal**: Wire social links via WordPress Customizer and add Open Graph meta tags for social sharing.
**Depends on**: Phase 2
**Requirements**: NAV-03, SEO-01, SEO-02
**Success Criteria** (what must be TRUE):
  1. Facebook, YouTube, Instagram links in topbar point to real URLs set via WordPress Customizer
  2. Sharing a post URL shows correct og:title, og:description, og:image
  3. Category pages have correct og:title and og:description

Plans:
- [x] 04-01: Add WordPress Customizer settings for social links and wire to header.php
- [x] 04-02: Add Open Graph meta tags to header.php for posts and category pages

### Phase 5: README & Documentation Update
**Goal**: Update README.md with accurate 5-section content architecture, new screenshots, and correct setup instructions.
**Depends on**: Phase 2, Phase 3
**Requirements**: DOC-01, DOC-02
**Success Criteria** (what must be TRUE):
  1. README accurately describes all 5 sections with names, slugs, and purpose
  2. Screenshots in README show the updated UI (post Phase 2)
  3. Quick-start instructions are accurate for the current setup

Plans:
- [ ] 05-01: Update README.md content sections table, highlights, and structure
- [ ] 05-02: Capture new screenshots and update docs/screenshots/

## Progress

| Phase | Plans Complete | Status | Completed |
|-------|----------------|--------|-----------|
| 1. CSS Cleanup & Foundation Polish | 0/2 | Not started | - |
| 2. Section Cards UI & Homepage Visual Polish | 3/3 | Complete   | 2026-05-16 |
| 3. Section-Specific Category Page Templates | 0/3 | Not started | - |
| 4. Navigation, Social Links & SEO | 2/2 | Complete   | 2026-05-16 |
| 5. README & Documentation Update | 0/2 | Not started | - |
| 6. Contact Section Update & Interactive Student Map | 0/3 | Not started | - |
| 7. Interactive Province Map - Province Detail Pages | 0/3 | Not started | - |

---

## Backlog (Post-Milestone)

- Interactive Vietnam map with Leaflet.js (MAP-01 through MAP-03)
- Full song library with lyrics and search (SONG-01 through SONG-03)
- Member profile pages (COMM-01)
- Newsletter subscription (COMM-02)

### Phase 6: Contact Section Update & Interactive Student Map
**Goal**: Update the contact section with the real school address and phone number, then build an interactive Vietnam student origin map with sample student data and a toggle between the new 34-province and classic 63-province administrative maps.
**Depends on**: Phase 2
**Requirements**: CONTACT-01, MAP-01, MAP-02, MAP-03
**Success Criteria** (what must be TRUE):
  1. Contact section shows correct address: 43D/46 Hồ Văn Huê, P. Đức Nhuận, TP. HCM and phone 084.3214.142
  2. Interactive student origin map is displayed with sample student pins across provinces
  3. Map toggle switches between Vietnam 34-province (new 2025) and 63-province (classic) administrative divisions
  4. Map is mobile-responsive and visually polished with smooth province-fill hover states
  5. All new strings use `charity_t($vi, $en)` bilingual helper

Plans:
- [ ] 06-01: Update contact section address and phone number
- [ ] 06-02: Build interactive student origin map with province-based choropleth and sample data
- [ ] 06-03: Add 34-province / 63-province toggle and mobile polish

### Phase 7: Interactive Province Map - Province Detail Pages with Signature Photos and Member Contacts

**Goal**: Extend the interactive province map (Phase 6) so that clicking a highlighted province navigates to a dedicated detail page. Each province detail page shows a locally-hosted signature JPG photo and a contact table of Đông Du members in that province.
**Depends on**: Phase 6
**Requirements**: MAP-DETAIL-01, MAP-DETAIL-02, MAP-DETAIL-03
**Success Criteria** (what must be TRUE):
  1. Clicking a province with members on the SVG map navigates to `/tinh/{slug}/`
  2. Each province detail page shows a hero image (province signature JPG) and a 3-column member contact table
  3. 34 province photos downloaded locally to `assets/img/provinces/`, JPG format, each ≤ 150KB
  4. Non-member provinces still show tooltip only (no navigation)
  5. Province detail template uses `charity_t()` for all bilingual strings

Plans:
- [x] 07-01: Extend contact-dongdu.json with slugs + download/compress 34 province photos
- [x] 07-02: WordPress rewrite rule + page-tinh.php province detail template
- [x] 07-03: Extend student-map.js click navigation + province detail CSS

---
*Roadmap created: 2026-05-16*
*Next step: Run `/gsd-plan-phase 1` to start execution.*
