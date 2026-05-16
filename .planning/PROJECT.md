# Rise Up Scholarship Website

## What This Is

A WordPress-based bilingual (VI/EN) website for **Quỹ Khuyến Học Đông Du** (Dong Du Study Encouragement Fund) that serves as the digital home for the **Học Bổng Vươn Lên (Rise Up Scholarship)** community. It organizes content into 5 main sections — News, Dong Du Journeys, Knowledge Handbook, Book Corner, and Community Activities — and allows members and alumni to read, submit stories, and connect.

## Core Value

**A living community archive**: members can discover, share, and feel belonging through the 5 content sections that reflect who the community is — not just what it does.

## Requirements

### Validated

- ✓ 5 content sections defined and auto-creating WordPress categories — existing (functions.php)
- ✓ Bilingual VI/EN toggle with cookie persistence — existing (charity_t() helper)
- ✓ Custom post types: event, program — existing
- ✓ Front-page with hero, section overview, Vietnam map placeholder, recent posts — existing
- ✓ category.php with banner, breadcrumb, subsection panels, and map demo — existing
- ✓ Frontend post submission (pending moderation) — existing
- ✓ AJAX load-more and like/reaction system — existing
- ✓ Responsive navigation with dropdown menus — existing

### Active

- [ ] **Beautiful section UI**: Each of the 5 sections needs richer, more polished visual design — icons, section-specific color accents, and layout improvements
- [ ] **Section-specific category page templates**: Distinct UI for each group (e.g., Song Library has a different layout than News, Map has interactive pins)
- [ ] **Self-hosted Vietnam map image**: Replace external CDN URL with local asset; lay groundwork for interactive map (Bản đồ Vươn Lên)
- [ ] **Content pillar icons**: Add visual icons to each section group card on the front page
- [ ] **Improved homepage section cards**: More visually compelling `cp-group-card` layout with icons, accent colors, hover states
- [ ] **Song Library template**: Special layout for "Tổng hợp bài hát" category — searchable, with lyrics display
- [ ] **Map placeholder upgrade**: Interactive mockup for Bản đồ Vươn Lên with province hover states
- [ ] **CSS deduplication**: ~200+ lines of duplicated CSS blocks need cleanup
- [ ] **README.md update**: Reflect new section structure, screenshots, and current theme capabilities
- [ ] **Social links**: Wire Facebook, YouTube, Instagram in header topbar
- [ ] **SEO meta tags**: Open Graph and basic meta description support

### Out of Scope

- Full interactive map with real member coordinates — requires user accounts, privacy considerations, and backend data model (Phase 3+)
- Plugin dependencies — keep theme self-contained; no new WP plugins
- Complete test suite — not yet needed at this project scale
- Payment/donation integration — not part of this content-focused milestone

## Context

- **Branch:** `CONGPHAT`, ahead of origin by 3 commits
- **Theme version:** 2.2.0, WordPress 6.x, PHP 8+
- **Idea document:** `Y_Tuong_De_Muc_Website_HBVL.md` — the canonical source for all 5 sections and their subsections
- **Content groups** are already fully defined in `charity_content_groups()` in `functions.php` — this is the single source of truth
- External map image URL (meeymap.com) is a known fragile dependency
- CSS has duplication (~200+ lines); main.css is 2309 lines flat (no preprocessor)
- No automated tests exist; all testing is manual via localhost
- Vietnamese is the primary language; English is secondary

## Constraints

- **Tech stack**: WordPress 6.x + PHP 8 + vanilla CSS/JS — no npm, no build tool, no new frameworks
- **Existing data**: Must not delete or rename existing categories/slugs (backward compat with DB)
- **Bilingual**: All new user-facing strings must use `charity_t($vi, $en)` pattern
- **Security**: All AJAX handlers must use `check_ajax_referer()` and sanitize inputs
- **Self-hosted assets**: New images/icons should be local (not external CDN)

## Key Decisions

| Decision | Rationale | Outcome |
|----------|-----------|---------|
| `charity_content_groups()` as single source of truth | Avoids drift between nav, categories, and front-page | ✓ Good |
| Vanilla CSS (no preprocessor) | Zero build tooling, easy for contributors | — Pending (complexity growing) |
| Cookie-based reactions | Quick MVP, no auth required | ⚠️ Revisit (easily gamed) |
| External map image URL | Quick placeholder | ⚠️ Revisit (fragile dependency) |
| BEM-ish + `cp-*` prefix | Clear namespacing for content-pillar CSS | ✓ Good |

## Evolution

This document evolves at phase transitions and milestone boundaries.

**After each phase transition** (via `/gsd-transition`):
1. Requirements invalidated? → Move to Out of Scope with reason
2. Requirements validated? → Move to Validated with phase reference
3. New requirements emerged? → Add to Active
4. Decisions to log? → Add to Key Decisions
5. "What This Is" still accurate? → Update if drifted

**After each milestone** (via `/gsd-complete-milestone`):
1. Full review of all sections
2. Core Value check — still the right priority?
3. Audit Out of Scope — reasons still valid?
4. Update Context with current state

---
*Last updated: 2026-05-16 after initialization*
