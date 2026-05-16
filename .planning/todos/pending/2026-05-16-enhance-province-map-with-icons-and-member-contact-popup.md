---
created: 2026-05-16T15:46:36.448Z
title: Enhance province map with icons and member contact popup
area: ui
files:
  - wordpress/wp-content/themes/charity-hcm/assets/img/vietnam-63-provinces.svg
  - wordpress/wp-content/themes/charity-hcm/assets/img/vietnam-34-provinces.svg
  - wordpress/wp-content/themes/charity-hcm/assets/js/student-map.js
---

## Problem

The SVG province maps currently show generic colored rectangles/squares for province icons inside the tooltip popup — they look ugly and non-representative. Additionally, when a user clicks on a province they only see a generic tooltip without rich member information.

Two improvements needed:
1. **Province icons/images**: Each province should have a recognizable visual (flag, landmark photo, or illustrated icon) instead of a plain colored square. Research options: local SVG icons per province, a CDN icon set, or thumbnail images for each of the 63 (old) / 34 (new) provinces.
2. **Member contact popup**: Clicking a province should open a panel/modal listing the Đông Du student representatives from that province, each with their name and a link to their contact page/profile.

## Solution

### Part A — Province representative images
- Research whether a free icon/image set exists for Vietnamese provinces (e.g., province coat-of-arms SVGs, landmark thumbnails).
- Fallback: generate simple illustrated province icons (color-coded + province abbreviation) as inline SVG, one per province code.
- Integrate into tooltip render in `student-map.js` — replace the current `<div class="province-icon">` square with `<img>` or inline SVG.

### Part B — Member contact data structure
Create a data file at `wordpress/wp-content/themes/charity-hcm/data/contact-dongdu.md` (or `.json`) with structure:

```
province_code: p01  (VietMap numeric code)
province_name: Hà Nội
members:
  - name: Nguyễn Văn A
    role: Đại diện tỉnh
    contact_url: /lien-he/nguyen-van-a
  - name: Nguyễn Thị B
    role: Thành viên
    contact_url: /lien-he/nguyen-thi-b
```

Generate **mock data** for all 34 new-map provinces (2–3 members each) as placeholder until real data is provided. Names follow Vietnamese naming patterns: Nguyễn / Trần / Lê / Phạm family names.

### Part C — Popup UI
- On province `click` event in `student-map.js`, fetch/render the member list from the data source.
- Show a styled side-panel or modal with member cards (name + role + "Liên hệ" button → contact_url).
- Consider a WordPress REST endpoint or PHP-injected JS variable (`window.dongduContacts`) to pass contact data to the frontend.

### Implementation order
1. Research + decide on province icon approach
2. Create `contact-dongdu` data file with mock data
3. Wire contact data into `student-map.js` popup
4. Replace province icons in tooltip
5. Style the popup panel

### Sample mock data (to seed the file)
```
Nguyễn Văn An    — Hà Nội
Nguyễn Thị Bình  — Hà Nội
Trần Hải Châu    — Hà Nội

Lê Văn Dũng      — TP. Hồ Chí Minh
Phạm Thị Hoa     — TP. Hồ Chí Minh

Hoàng Văn Minh   — Đà Nẵng
Nguyễn Thị Lan   — Đà Nẵng
... (2–3 per province for all 34)
```
