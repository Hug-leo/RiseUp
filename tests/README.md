# RiseUp Charity Website - Comprehensive Test Suite

## Overview
Complete functional test suite for the RiseUp charity WordPress site, covering all user and admin features.

## Test Coverage

### Section 1: Static Pages & Basic Content
- ✓ Homepage (home.php)
- ✓ Announcements Feed (announcement-feed.php)
- ✓ Events (events.php)
- ✓ Contact Page (contact.php)
- ✓ Contact Form Submission (with CSRF validation)

### Section 2: WordPress Content Categories
- ✓ News category (tin-tuc)
- ✓ Journeys category (dong-du-ky)
- ✓ Handbook category (so-tay-kien-thuc)
- ✓ Books category (goc-sach-hay)
- ✓ Activities category (sinh-hoat)

### Section 3: Search & Navigation
- ✓ Search functionality (/?s=query)
- ✓ Bilingual support (Vietnamese/English switching)
- ✓ Province map pages (/tinh/{slug}/)
- ✓ Submit post link (Google Drive redirect)

### Section 4: Admin Features (Requires Credentials)
- ✓ WordPress admin login (wp-login.php)
- ✓ Pending posts management
- ✓ Post editing/publishing

## Quick Start

### Without Admin Testing
```bash
python tests/functional_tests.py -b http://your-server/wordpress
```

### With Admin Testing
```bash
python tests/functional_tests.py -b http://your-server/wordpress \
  --admin-user your_admin_username \
  --admin-pass your_admin_password
```

### With Custom Timeout
```bash
python tests/functional_tests.py -b http://your-server/wordpress --timeout 15
```

## Output Format

The test suite provides:
1. **Per-Test Results**: [PASS], [FAIL], or [SKIP] status for each test
2. **Detailed Details**: Individual test outcomes listed under each section
3. **Summary Statistics**: 
   - Pass/Fail/Skip counts by section
   - Total pass/fail/skip counts across all sections
4. **Exit Codes**:
   - Exit code 0: All tests passed
   - Exit code 2: One or more tests failed

## Example Output
```
================================================================================
RISEUP COMPREHENSIVE FUNCTIONAL TEST SUITE
================================================================================
Target: http://localhost:8000/wordpress
================================================================================

[SECTION 1] STATIC PAGES & BASIC CONTENT
--------------------------------------------------------------------------------
OK  GET Homepage (home.php) -> HTTP 200
FAIL contact POST -> success message not found
...

[FINAL SUMMARY]
Static Pages & Contact:
  [PASS] Passed:  4
  [FAIL] Failed:  1
  [SKIP] Skipped: 0
```

## Requirements

- Python 3.7+
- No external dependencies (uses urllib from standard library)

## Test Details

Each test function includes:
- **Docstring**: Identifies which feature is being tested
- **Error Handling**: Graceful error messages for failures
- **Return Value**: Boolean (True/Pass, False/Fail, None/Skip)
- **Logging**: Detailed status messages for debugging

## Common Issues

### 404 Errors
If getting 404 errors:
1. Verify server URL is correct
2. Check that WordPress is running
3. Ensure paths match site structure (see DEVELOPMENT_GUIDE.md)

### Admin Login Fails
If admin tests skip or fail:
1. Verify credentials are correct
2. Check WordPress is in normal mode (not maintenance)
3. Confirm wp-admin pages are accessible

### Bilingual Tests
Bilingual test is somewhat lenient as it checks for common English markers. If failing:
1. Check that language switching is working
2. Verify both Vietnamese and English content is served

## Files

- `smoke_test.py`: Quick HTTP error detection (legacy)
- `functional_tests.py`: Comprehensive feature testing (current)
- `README.md`: This file

## Next Steps

1. Run test suite against your local/production server
2. Review failures with developer team
3. Prioritize fixes based on failure count and severity
4. Re-run suite to verify fixes

## Support

For test failures or questions:
- Check test output for specific error messages
- Review site code in `/wordpress` directory
- Consult DEVELOPMENT_GUIDE.md for architecture details
- Check functions.php for AJAX handlers and custom post types
