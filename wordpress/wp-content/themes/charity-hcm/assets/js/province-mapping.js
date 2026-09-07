export const NEW_34_TO_OLD_63 = Object.freeze({
  'Hà Nội': ['Hà Nội'],
  'Cao Bằng': ['Cao Bằng'],
  'Tuyên Quang': ['Hà Giang', 'Tuyên Quang'],
  'Điện Biên': ['Điện Biên'],
  'Lai Châu': ['Lai Châu'],
  'Sơn La': ['Sơn La'],
  'Lào Cai': ['Yên Bái', 'Lào Cai'],
  'Thái Nguyên': ['Bắc Kạn', 'Thái Nguyên'],
  'Lạng Sơn': ['Lạng Sơn'],
  'Quảng Ninh': ['Quảng Ninh'],
  'Bắc Ninh': ['Bắc Giang', 'Bắc Ninh'],
  'Phú Thọ': ['Vĩnh Phúc', 'Hòa Bình', 'Phú Thọ'],
  'Hải Phòng': ['Hải Phòng', 'Hải Dương'],
  'Hưng Yên': ['Thái Bình', 'Hưng Yên'],
  'Ninh Bình': ['Hà Nam', 'Nam Định', 'Ninh Bình'],
  'Thanh Hóa': ['Thanh Hóa'],
  'Nghệ An': ['Nghệ An'],
  'Hà Tĩnh': ['Hà Tĩnh'],
  'Quảng Trị': ['Quảng Bình', 'Quảng Trị'],
  'Huế': ['Huế'],
  'Đà Nẵng': ['Đà Nẵng', 'Quảng Nam'],
  'Quảng Ngãi': ['Kon Tum', 'Quảng Ngãi'],
  'Gia Lai': ['Bình Định', 'Gia Lai'],
  'Khánh Hòa': ['Ninh Thuận', 'Khánh Hòa'],
  'Đắk Lắk': ['Phú Yên', 'Đắk Lắk'],
  'Lâm Đồng': ['Đắk Nông', 'Bình Thuận', 'Lâm Đồng'],
  'TP. Hồ Chí Minh': ['TP. Hồ Chí Minh', 'Bà Rịa - Vũng Tàu', 'Bình Dương'],
  'Đồng Nai': ['Bình Phước', 'Đồng Nai'],
  'Tây Ninh': ['Long An', 'Tây Ninh'],
  'Cần Thơ': ['Cần Thơ', 'Sóc Trăng', 'Hậu Giang'],
  'Vĩnh Long': ['Bến Tre', 'Trà Vinh', 'Vĩnh Long'],
  'Đồng Tháp': ['Tiền Giang', 'Đồng Tháp'],
  'Cà Mau': ['Bạc Liêu', 'Cà Mau'],
  'An Giang': ['Kiên Giang', 'An Giang']
});

export const OLD_63_TO_NEW_34 = Object.freeze(
  Object.entries(NEW_34_TO_OLD_63).reduce((result, [currentProvince, oldProvinces]) => {
    oldProvinces.forEach((oldProvince) => { result[oldProvince] = currentProvince; });
    return result;
  }, {})
);

export function validateProvinceMapping() {
  const current = Object.keys(NEW_34_TO_OLD_63);
  const old = current.flatMap((name) => NEW_34_TO_OLD_63[name]);
  if (current.length !== 34 || old.length !== 63 || new Set(old).size !== 63) {
    throw new Error('Dữ liệu hành chính 34/63 không hợp lệ.');
  }
  if (current.some((name) => NEW_34_TO_OLD_63[name].length < 1)) {
    throw new Error('Một đơn vị hiện hành không có đơn vị tiền thân.');
  }
  return true;
}
