---
phase: 07-province-detail-pages
plan: 01
subsystem: data
tags: [json, provinces, images, assets, slug]

requires: []
provides:
  - contact-dongdu.json with slug fields on all 34 provinces
  - assets/img/provinces/ with 34 compressed JPEG photos (all ≤150KB)
affects: [07-02, 07-03, page-tinh]

tech-stack:
  added: []
  patterns: [slug field convention: Vietnamese province name → kebab-case ASCII slug]

key-files:
  created:
    - wordpress/wp-content/themes/charity-hcm/assets/img/provinces/ (34 .jpg files)
  modified:
    - wordpress/wp-content/themes/charity-hcm/data/contact-dongdu.json

key-decisions:
  - "Slug field placed immediately after 'name' in each province object"
  - "Images sourced from Wikimedia Commons via MediaWiki API search (original plan URLs were invalid filenames)"
  - "Compressed with macOS sips: 1200px max dimension, quality 70→60→50 until ≤150KB"

patterns-established:
  - "Province slug convention: kebab-case ASCII (ha-noi, ho-chi-minh, etc.)"

requirements-completed:
  - MAP-DETAIL-03

duration: 25min
completed: 2026-05-16
---

# Plan 07-01 Summary

**Added URL slug fields to all 34 provinces and created compressed province hero photos.**

## Performance

- **Duration:** ~25 min
- **Completed:** 2026-05-16
- **Tasks:** 2
- **Files modified:** 35 (1 JSON + 34 JPGs)

## Accomplishments

1. **contact-dongdu.json**: Added `"slug"` field to all 34 province entries using the mapping (p11→ha-noi through p44→vinh-long). Field placed after `"name"`.

2. **assets/img/provinces/**: Created directory with 34 valid JPEG files. Wikimedia Commons plan URLs were invalid — used MediaWiki search API to find actual file names. Compressed with sips until all files ≤ 150KB.

## Verification

- `python3 -c "import json; d=json.load(open('wordpress/.../contact-dongdu.json')); ..."` → 34 provinces, 34 with slugs ✓
- `ls assets/img/provinces/*.jpg | wc -l` → 34 ✓
- `find assets/img/provinces -name "*.jpg" -size +150k` → no output ✓
- All files valid JPEG format ✓
