---
plan: 03-02
phase: 03-section-specific-category-page-templates
status: complete
commit: d71ca2f
---

# Plan 03-02 Summary — Category Dispatch + CSS Layouts

## What Was Built

### `category.php` — Two changes

**Change 1 — SECT-06: Group icon in page-banner**
Added `page-banner__icon` div before the `<h1>` in `.page-banner__inner`. Calls `charity_group_icon( $group['slug'] )` — a hardcoded trusted function — and outputs the SVG only when a parent group is set. `phpcs:ignore` comment documents the intentional escaping exception for the trusted SVG output.

**Change 2 — SEC-03/04/05: Section-specific dispatch blocks**
Added three new `if/elseif` blocks between the `ban-do-vuon-len` block and the generic `category-posts` section:

| Slug(s) | Section | Template Part | Grid Class |
|---------|---------|---------------|------------|
| `tong-hop-bai-hat` | `.category-song` | `content-song` | `.song-grid` |
| `guong-mat-vuon-len` | `.category-profile` | `content-profile` | `.profile-grid` |
| `bi-kip`, `the-gioi-quanh-ta` | `.category-tips` | `content-tip` | `.tips-grid` |

Generic `category-posts` section preserved as fallback for all other slugs.

### `main.css` — New CSS block appended

Added ~260 lines of CSS at the end of `main.css` under the "Phase 3" comment block:

- `.page-banner__icon` — 56×56 circular container, white stroke SVG
- `.song-grid` / `.song-card` — 3-column grid, card with icon + text layout
- `.profile-grid` / `.profile-card` — 3-column grid, card with photo aspect-ratio + body
- `.tips-grid` / `.tip-card` — 3-column compact grid, card with left red border accent
- Category section headers shared styles
- `@media (max-width: 768px)` — collapses all grids to 2 columns
- `@media (max-width: 480px)` — collapses all grids to 1 column

## Security

- All dynamic output uses `esc_html()`, `esc_url()`, `esc_attr()`
- `in_array()` uses strict mode (`true` as 3rd argument) for slug comparison
- `charity_group_icon()` SVG output documented as trusted with phpcs ignore

## CSS Variable Usage

Used actual theme variables confirmed from `:root`: `--bg`, `--shadow-card`, `--shadow-md`, `--shadow-lg`, `--radius`, `--transition`, `--primary`, `--text`, `--text-muted` (no `--white`/`--space-*` as those don't exist in this theme).
