# TESTING.md — Testing

## Current State

**No automated tests exist** in this project. There is no:
- PHPUnit test suite
- Jest / Vitest tests
- End-to-end tests (Playwright, Cypress)
- CI/CD configuration (no `.github/workflows/`, no `Makefile`)

## Manual Testing

All testing is manual via localhost:
- Setup: import `database/sampleweb_wp_export.sql`, run Apache + MySQL, open `http://localhost/sampleweb/wordpress`
- Verify: navigation, front-page sections, category pages, post submission, bilingual toggle, AJAX load-more, like reactions

## Test Coverage: Zero (0%)

## What Should Be Tested (Priority)

1. **`charity_content_groups()`** — the central data function; changes here affect nav, categories, front-page, and category.php
2. **AJAX handlers** (`charity_ajax_load_more`, `charity_ajax_toggle_like`) — nonce and input validation
3. **Category auto-creation** (`init` hook at priority 20) — idempotency (re-running should not duplicate categories)
4. **Bilingual helper** `charity_t()` — correct string returned per language state
5. **Front-page responsive layout** — visual regression on mobile/tablet

## Recommended Testing Approach (Future)

- PHPUnit for `charity_content_groups()` and helper functions (WordPress unit test scaffold)
- Browser smoke test: manually verify each of the 5 section pages load with correct content after any theme change
