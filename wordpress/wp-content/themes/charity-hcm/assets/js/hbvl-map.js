import { NEW_34_TO_OLD_63, OLD_63_TO_NEW_34, validateProvinceMapping } from './province-mapping.js';
import { foldVietnamese } from './province-normalization.js';

const SVG_NS = 'http://www.w3.org/2000/svg';
const DATA_ROOT = new URL('../data/', import.meta.url);
const VIEW = { width: 620, height: 850, padding: 24 };

function element(tag, className, text) {
  const node = document.createElement(tag);
  if (className) node.className = className;
  if (text !== undefined) node.textContent = text;
  return node;
}

function allPoints(geometry) {
  const polygons = geometry.type === 'Polygon' ? [geometry.coordinates] : geometry.coordinates;
  return polygons.flatMap((polygon) => polygon.flatMap((ring) => ring));
}

function bounds(features) {
  const points = features.flatMap((feature) => allPoints(feature.geometry));
  return points.reduce((box, point) => ({
    minX: Math.min(box.minX, point[0]), maxX: Math.max(box.maxX, point[0]),
    minY: Math.min(box.minY, point[1]), maxY: Math.max(box.maxY, point[1])
  }), { minX: Infinity, maxX: -Infinity, minY: Infinity, maxY: -Infinity });
}

function projector(box) {
  const scale = Math.min(
    (VIEW.width - VIEW.padding * 2) / (box.maxX - box.minX),
    (VIEW.height - VIEW.padding * 2) / (box.maxY - box.minY)
  );
  const offsetX = (VIEW.width - (box.maxX - box.minX) * scale) / 2;
  const offsetY = (VIEW.height - (box.maxY - box.minY) * scale) / 2;
  return ([x, y]) => [offsetX + (x - box.minX) * scale, VIEW.height - offsetY - (y - box.minY) * scale];
}

function geometryPath(geometry, project) {
  const polygons = geometry.type === 'Polygon' ? [geometry.coordinates] : geometry.coordinates;
  return polygons.map((polygon) => polygon.map((ring) => ring.map((point, index) => {
    const [x, y] = project(point);
    return `${index ? 'L' : 'M'}${x.toFixed(2)},${y.toFixed(2)}`;
  }).join(' ') + ' Z').join(' ')).join(' ');
}

function groupMembers(members, field) {
  return members.reduce((groups, member) => {
    (groups[member[field]] ||= []).push(member);
    return groups;
  }, {});
}

function validateData(members, geometry) {
  validateProvinceMapping();
  const oldNames = Object.keys(OLD_63_TO_NEW_34);
  const currentNames = Object.keys(NEW_34_TO_OLD_63);
  const geometry34 = geometry.modes['34'].map((feature) => feature.properties.name);
  const geometry63 = geometry.modes['63'].map((feature) => feature.properties.name);
  if (geometry34.length !== 34 || new Set(geometry34).size !== 34 || currentNames.some((name) => !geometry34.includes(name))) {
    throw new Error('Hình học bản đồ 34 đơn vị không hợp lệ.');
  }
  if (geometry63.length !== 63 || new Set(geometry63).size !== 63 || oldNames.some((name) => !geometry63.includes(name))) {
    throw new Error('Hình học bản đồ 63 đơn vị không hợp lệ.');
  }
  const keys = new Set();
  members.forEach((member) => {
    if (!oldNames.includes(member.oldProvince) || OLD_63_TO_NEW_34[member.oldProvince] !== member.currentProvince) {
      throw new Error(`Địa danh thành viên không hợp lệ: ${member.oldProvince}`);
    }
    const key = `${member.name}\u0000${member.oldProvince}`;
    if (keys.has(key)) throw new Error(`Bản ghi thành viên bị lặp: ${member.name}`);
    keys.add(key);
  });
  const total34 = Object.values(groupMembers(members, 'currentProvince')).flat().length;
  const total63 = Object.values(groupMembers(members, 'oldProvince')).flat().length;
  if (total34 !== members.length || total63 !== members.length || total34 !== total63) {
    throw new Error('Tổng thành viên giữa hai chế độ không khớp.');
  }
}

export async function initHBVLMap() {
  const canvas = document.getElementById('student-map-canvas');
  if (!canvas) return;
  const [memberResponse, geometryResponse] = await Promise.all([
    fetch(new URL('hbvl-members.json', DATA_ROOT)),
    fetch(new URL('vietnam-provinces.json', DATA_ROOT))
  ]);
  if (!memberResponse.ok || !geometryResponse.ok) throw new Error('Không thể tải dữ liệu bản đồ.');
  const memberData = await memberResponse.json();
  const geometry = await geometryResponse.json();
  const members = memberData.members || [];
  validateData(members, geometry);

  const state = {
    mode: '34', selected: null,
    groups34: groupMembers(members, 'currentProvince'),
    groups63: groupMembers(members, 'oldProvince')
  };
  const title = document.getElementById('student-map-detail-title');
  const count = document.getElementById('student-map-detail-count');
  const memberList = document.getElementById('student-map-member-list');
  const constituentWrap = document.getElementById('student-map-constituents');
  const constituentList = document.getElementById('student-map-constituent-list');
  const tooltip = document.getElementById('student-map-tooltip');
  const search = document.getElementById('student-map-search');
  const results = document.getElementById('student-map-search-results');

  function provinceMembers(name) {
    return (state.mode === '34' ? state.groups34 : state.groups63)[name] || [];
  }

  function setTooltip(name, event) {
    tooltip.textContent = `${name} · ${provinceMembers(name).length} thành viên`;
    tooltip.hidden = false;
    const atlas = tooltip.parentElement.getBoundingClientRect();
    tooltip.style.left = `${Math.min(event.clientX - atlas.left + 12, atlas.width - 210)}px`;
    tooltip.style.top = `${Math.max(event.clientY - atlas.top - 34, 8)}px`;
  }

  function hideTooltip() { tooltip.hidden = true; }

  function selectProvince(name, focusPanel = false) {
    state.selected = name;
    canvas.querySelectorAll('.map-province').forEach((path) => {
      const selected = path.dataset.province === name;
      path.classList.toggle('is-selected', selected);
      path.setAttribute('aria-pressed', String(selected));
    });
    const selectedMembers = provinceMembers(name);
    title.textContent = name;
    count.textContent = `${selectedMembers.length} thành viên`;
    memberList.replaceChildren();
    constituentList.replaceChildren();
    if (state.mode === '34') {
      constituentWrap.hidden = false;
      NEW_34_TO_OLD_63[name].forEach((oldName) => {
        const oldCount = (state.groups63[oldName] || []).length;
        constituentList.append(element('li', '', `${oldName} cũ · ${oldCount} thành viên`));
      });
    } else {
      constituentWrap.hidden = true;
    }
    if (selectedMembers.length) {
      selectedMembers.forEach((member) => memberList.append(element('li', '', member.name)));
    } else {
      memberList.append(element('li', 'student-map__empty', `Hiện chưa có thành viên HBVL tại ${name}.`));
    }
    if (focusPanel) title.focus({ preventScroll: false });
  }

  function draw() {
    const features = geometry.modes[state.mode];
    const svg = document.createElementNS(SVG_NS, 'svg');
    svg.setAttribute('viewBox', `0 0 ${VIEW.width} ${VIEW.height}`);
    svg.setAttribute('class', 'student-map__svg');
    svg.setAttribute('role', 'group');
    svg.setAttribute('aria-label', state.mode === '34' ? '34 tỉnh, thành hiện hành' : '63 tỉnh, thành trước sắp xếp');
    /* The current source uses geographic coordinates; the historical source uses
     * the map publisher's projected coordinate space, so each mode needs its own extent. */
    const projectionBounds = state.mode === '34'
      ? { minX: 102, maxX: 110.8, minY: 8, maxY: 23.6 }
      : bounds(features);
    const project = projector(projectionBounds);
    features.forEach((feature) => {
      const name = feature.properties.name;
      const total = provinceMembers(name).length;
      const path = document.createElementNS(SVG_NS, 'path');
      path.setAttribute('d', geometryPath(feature.geometry, project));
      path.setAttribute('class', `map-province ${total ? 'has-members' : 'has-no-members'}`);
      path.setAttribute('tabindex', '0');
      path.setAttribute('role', 'button');
      path.setAttribute('aria-pressed', 'false');
      path.setAttribute('aria-label', `${name}, ${total} thành viên HBVL`);
      path.dataset.province = name;
      path.addEventListener('click', () => selectProvince(name));
      path.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' || event.key === ' ') {
          event.preventDefault(); selectProvince(name);
        }
      });
      path.addEventListener('pointermove', (event) => setTooltip(name, event));
      path.addEventListener('pointerleave', hideTooltip);
      path.addEventListener('focus', () => {
        tooltip.textContent = `${name} · ${total} thành viên`;
        tooltip.hidden = false;
      });
      path.addEventListener('blur', hideTooltip);
      svg.append(path);
    });
    canvas.replaceChildren(svg);
    state.selected = null;
    title.textContent = 'Chọn một tỉnh/thành';
    count.textContent = '—';
    constituentWrap.hidden = true;
    memberList.replaceChildren(element('li', 'student-map__empty', 'Nhấp hoặc dùng bàn phím để chọn một tỉnh/thành trên bản đồ.'));
    canvas.dispatchEvent(new CustomEvent('hbvl-map-ready', { detail: { mode: state.mode } }));
  }

  document.querySelectorAll('.map-toggle-btn').forEach((button) => {
    button.addEventListener('click', () => {
      state.mode = button.dataset.map;
      document.querySelectorAll('.map-toggle-btn').forEach((item) => {
        const active = item === button;
        item.classList.toggle('active', active);
        item.setAttribute('aria-pressed', String(active));
      });
      draw();
    });
  });

  function closeResults() { results.hidden = true; results.replaceChildren(); }
  search.addEventListener('input', () => {
    const query = foldVietnamese(search.value);
    closeResults();
    if (query.length < 2) return;
    const choices = [];
    const provinceNames = state.mode === '34' ? Object.keys(NEW_34_TO_OLD_63) : Object.keys(OLD_63_TO_NEW_34);
    provinceNames.filter((name) => foldVietnamese(name).includes(query)).slice(0, 5)
      .forEach((name) => choices.push({ label: name, province: name, meta: `${provinceMembers(name).length} thành viên` }));
    members.filter((member) => foldVietnamese(member.name).includes(query)).slice(0, 5)
      .forEach((member) => choices.push({
        label: member.name,
        province: state.mode === '34' ? member.currentProvince : member.oldProvince,
        meta: state.mode === '34' ? member.currentProvince : member.oldProvince
      }));
    if (!choices.length) choices.push({ label: 'Không tìm thấy kết quả', disabled: true, meta: '' });
    choices.slice(0, 8).forEach((choice) => {
      const button = element('button', 'student-map__search-result');
      button.type = 'button'; button.disabled = Boolean(choice.disabled);
      button.append(element('strong', '', choice.label), element('span', '', choice.meta));
      if (!choice.disabled) button.addEventListener('click', () => {
        selectProvince(choice.province);
        canvas.querySelector(`[data-province="${CSS.escape(choice.province)}"]`)?.focus();
        closeResults();
      });
      results.append(button);
    });
    results.hidden = false;
  });
  search.addEventListener('keydown', (event) => { if (event.key === 'Escape') closeResults(); });
  document.addEventListener('click', (event) => { if (!results.parentElement.contains(event.target)) closeResults(); });
  draw();
}
