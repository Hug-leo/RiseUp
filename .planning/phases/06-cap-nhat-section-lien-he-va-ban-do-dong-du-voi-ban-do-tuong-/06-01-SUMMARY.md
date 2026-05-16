# Plan 06-01 Summary: Update Contact Address & Phone

**Phase:** 06-contact-and-student-map
**Plan:** 01
**Status:** Complete
**Commit:** 7ba725c

## What Was Done

Updated `page-contact.php` with the correct school contact details:

- **Address**: Changed from `Phú Nhuận, TP. Hồ Chí Minh` to `P. Đức Nhuận, TP. HCM` (with full address wrapped in `charity_t()` for bilingual support)
  - VI: `43D/46 Hồ Văn Huê, P. Đức Nhuận, TP. HCM`
  - EN: `43D/46 Ho Van Hue, Duc Nhuan Ward, HCMC`
- **Phone**: Changed from `084 3214 142` (space-separated) to `084.3214.142` (dot-separated)

## Files Modified

- `wordpress/wp-content/themes/charity-hcm/page-contact.php` (lines 76, 83)

## Verification

- `grep "Đức Nhuận\|084\.3214\.142" page-contact.php` → 2 matches confirmed
- Old strings `Phú Nhuận` and `084 3214 142` fully removed
- Form logic, nonce handling, and all other contact cards untouched
