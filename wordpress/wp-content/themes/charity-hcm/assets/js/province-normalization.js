const ALIASES = Object.freeze({
  'THUA THIEN HUE': 'Huế',
  'TP HUE': 'Huế',
  'TP HO CHI MINH': 'TP. Hồ Chí Minh',
  'TPHCM': 'TP. Hồ Chí Minh',
  'HO CHI MINH': 'TP. Hồ Chí Minh',
  'TP THU DUC': 'TP. Hồ Chí Minh',
  'THANH PHO THU DUC': 'TP. Hồ Chí Minh',
  'THU DUC': 'TP. Hồ Chí Minh',
  'DAC LAK': 'Đắk Lắk',
  'DAK LAK': 'Đắk Lắk',
  'TP DA NANG': 'Đà Nẵng',
  'TP HAI PHONG': 'Hải Phòng',
  'TP CAN THO': 'Cần Thơ'
});

export function foldVietnamese(value) {
  return String(value || '')
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[đĐ]/g, 'd')
    .replace(/[^a-zA-Z0-9]+/g, ' ')
    .trim()
    .replace(/\s+/g, ' ')
    .toUpperCase();
}

export function normalizeProvinceName(value, canonicalNames) {
  const folded = foldVietnamese(value);
  const alias = ALIASES[folded];
  if (alias) return alias;
  const match = canonicalNames.find((name) => foldVietnamese(name) === folded);
  return match || null;
}
