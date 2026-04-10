<?php
/**
 * Events page — Full listing of school events with filterable cards.
 */
require_once __DIR__ . '/_layout.php';

layout_header('Su Kien');
layout_nav();
?>

<!-- ============================= Hero ============================= -->
<section class="hero">
    <h1>Su Kien & Hoat Dong</h1>
    <p>Lich trinh cac su kien, hoat dong ngoai khoa va cac ngay le quan trong</p>
</section>

<!-- ============================= Filter tabs ============================= -->
<div class="evt-filter">
    <div class="evt-filter-inner">
        <button class="ef-btn active" data-filter="all">Tat Ca</button>
        <button class="ef-btn" data-filter="upcoming">Sap Toi</button>
        <button class="ef-btn" data-filter="past">Da Qua</button>
    </div>
</div>

<!-- ============================= Events grid ============================= -->
<section class="section">
    <div class="container">
        <div class="events-grid" id="eventsGrid">
            <!-- Rendered by JS -->
        </div>
    </div>
</section>

<!-- ============================= CTA ============================= -->
<section class="section" style="background:#1565C0;color:#fff;text-align:center">
    <div class="container">
        <h2 style="font-family:'Lora',serif;font-size:28px;font-weight:700;margin-bottom:10px">Ban Muon De Xuat Su Kien?</h2>
        <p style="font-size:15px;opacity:.9;margin-bottom:24px">Gui y tuong hoat dong cho nha truong de chung toi co the to chuc cac su kien phu hop voi nhu cau cua phu huynh va hoc sinh.</p>
        <a href="contact.php" class="btn" style="background:#fff;color:#1565C0">Lien He Ngay &rsaquo;</a>
    </div>
</section>

<style>
    /* ===== Event filter bar ===== */
    .evt-filter { border-bottom: 1px solid #e8e8e8; background: #fff; }
    .evt-filter-inner { max-width: 1100px; margin: 0 auto; padding: 0 24px; display: flex; gap: 24px; }
    .ef-btn { background: none; border: none; padding: 14px 0; font-size: 14px; font-weight: 600; color: #999; cursor: pointer; position: relative; transition: color .2s; }
    .ef-btn:hover { color: #2d3436; }
    .ef-btn.active { color: #2d3436; }
    .ef-btn.active::after { content: ''; position: absolute; bottom: -1px; left: 0; right: 0; height: 2px; background: #1565C0; }

    /* ===== Events grid ===== */
    .events-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
    .ecard { background: #fff; border: 1px solid #e8e8e8; border-radius: 10px; overflow: hidden; transition: transform .25s, box-shadow .25s; opacity: 0; transform: translateY(20px); transition: opacity .5s, transform .5s, box-shadow .25s; }
    .ecard.visible { opacity: 1; transform: translateY(0); }
    .ecard:hover { box-shadow: 0 8px 24px rgba(0,0,0,.08); transform: translateY(-4px); }
    .ecard-img { width: 100%; height: 180px; object-fit: cover; background: #eee; opacity: 0; transition: opacity .5s; }
    .ecard-img.loaded { opacity: 1; }
    .ecard-body { padding: 20px; }
    .ecard-date { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; font-size: 13px; color: #777; }
    .ecard-date svg { width: 15px; height: 15px; stroke: #1565C0; fill: none; stroke-width: 2; }
    .ecard-title { font-family: 'Lora', serif; font-size: 18px; font-weight: 700; color: #2d3436; margin-bottom: 8px; line-height: 1.35; }
    .ecard-desc { font-size: 13px; color: #777; line-height: 1.6; margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .ecard-footer { display: flex; justify-content: space-between; align-items: center; }
    .ecard-tag { font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; }
    .ecard-link { font-size: 13px; font-weight: 600; color: #1565C0; }
    .ecard-link:hover { opacity: .7; }

    /* Empty state */
    .empty-state { grid-column: 1/-1; text-align: center; padding: 60px 20px; color: #999; font-size: 15px; }

    @media (max-width: 768px) {
        .events-grid { grid-template-columns: 1fr; }
        .ecard-img { height: 160px; }
    }
    @media (max-width: 480px) {
        .evt-filter-inner { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .ef-btn { white-space: nowrap; font-size: 13px; }
    }
</style>

<script>
/* ===== Events data ===== */
const allEvents = [
    {
        title: "Hop Phu Huynh Giua Hoc Ky II",
        date: "2026-04-12",
        dateDisplay: "12/04/2026 — 8:00 SA",
        image: "https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=600&h=300&fit=crop",
        desc: "Truong to chuc hop phu huynh giua hoc ky II. De nghi phu huynh sap xep thoi gian tham du de nam bat tinh hinh hoc tap cua con em.",
        tag: "Quan Trong", tagBg: "#fde8e8", tagColor: "#e74c3c",
        status: "upcoming"
    },
    {
        title: "Kham Suc Khoe Dinh Ky Hoc Ky II",
        date: "2026-04-14",
        dateDisplay: "14-18/04/2026",
        image: "https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=600&h=300&fit=crop",
        desc: "Chuong trinh kham suc khoe dinh ky cho hoc sinh. Phu huynh ky xac nhan phieu dong y kham suc khoe.",
        tag: "Suc Khoe", tagBg: "#e8f8f0", tagColor: "#27ae60",
        status: "upcoming"
    },
    {
        title: "Han Dang Ky Tiet Muc Van Nghe 30/4",
        date: "2026-04-15",
        dateDisplay: "15/04/2026",
        image: "https://images.unsplash.com/photo-1514320291840-2e0a9bf2a9ae?w=600&h=300&fit=crop",
        desc: "Han cuoi dang ky tiet muc van nghe chao mung ngay 30/4 - 1/5. Cac lop nop phieu cho Doan truong.",
        tag: "Su Kien", tagBg: "#fef3e2", tagColor: "#f39c12",
        status: "upcoming"
    },
    {
        title: "Nop Hoc Phi Hoc Ky II (Han Cuoi)",
        date: "2026-04-20",
        dateDisplay: "20/04/2026",
        image: "",
        desc: "Han cuoi nop hoc phi hoc ky II. Ho tro chuyen khoan hoac thanh toan tai phong tai vu.",
        tag: "Tai Chinh", tagBg: "#e8ecf8", tagColor: "#3498db",
        status: "upcoming"
    },
    {
        title: "Cuoc Thi Khoa Hoc Ky Thuat Cap Truong",
        date: "2026-04-25",
        dateDisplay: "25/04/2026 — 7:30 SA",
        image: "https://images.unsplash.com/photo-1567168544813-cc03465b4fa8?w=600&h=300&fit=crop",
        desc: "Cuoc thi Khoa Hoc Ky Thuat cap truong. Giai thuong hap dan danh cho cac doi xuat sac.",
        tag: "Hoc Tap", tagBg: "#e8ecf8", tagColor: "#3498db",
        status: "upcoming"
    },
    {
        title: "Chuong Trinh Van Nghe Chao Mung 30/4 - 1/5",
        date: "2026-04-29",
        dateDisplay: "29/04/2026 — 14:00",
        image: "https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&h=300&fit=crop",
        desc: "Chuong trinh van nghe dac sac tai hoi truong truong nhan dip ky niem ngay Giai Phong va Quoc Te Lao Dong.",
        tag: "Su Kien", tagBg: "#fef3e2", tagColor: "#f39c12",
        status: "upcoming"
    },
    {
        title: "Thi Giua Ky Hoc Ky II",
        date: "2026-03-25",
        dateDisplay: "25-28/03/2026",
        image: "https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&h=300&fit=crop",
        desc: "Ky thi giua ky hoc ky II cho tat ca cac khoi lop da hoan thanh tot dep.",
        tag: "Hoc Tap", tagBg: "#e8ecf8", tagColor: "#3498db",
        status: "past"
    },
    {
        title: "Ngay Hoi Sach 2026",
        date: "2026-03-15",
        dateDisplay: "15/03/2026",
        image: "https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=600&h=300&fit=crop",
        desc: "Ngay hoi sach thuong nien voi nhieu hoat dong giao luu, trao doi sach giua cac lop.",
        tag: "Van Hoa", tagBg: "#f3e8ff", tagColor: "#0D47A1",
        status: "past"
    }
];

let currentEvtFilter = "all";

/**
 * Render event cards based on the active filter.
 */
function renderEvents() {
    const grid = document.getElementById('eventsGrid');
    grid.innerHTML = '';

    const filtered = currentEvtFilter === 'all'
        ? allEvents
        : allEvents.filter(e => e.status === currentEvtFilter);

    if (!filtered.length) {
        grid.innerHTML = '<div class="empty-state">Khong co su kien nao.</div>';
        return;
    }

    filtered.forEach(e => {
        const card = document.createElement('div');
        card.className = 'ecard';
        card.innerHTML = `
            ${e.image ? `<img class="ecard-img" data-src="${e.image}" alt="" loading="lazy">` : ''}
            <div class="ecard-body">
                <div class="ecard-date">
                    <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    ${e.dateDisplay}
                </div>
                <h3 class="ecard-title">${e.title}</h3>
                <p class="ecard-desc">${e.desc}</p>
                <div class="ecard-footer">
                    <span class="ecard-tag" style="background:${e.tagBg};color:${e.tagColor}">${e.tag}</span>
                    <a href="#" class="ecard-link">Chi Tiet &rsaquo;</a>
                </div>
            </div>`;
        grid.appendChild(card);
    });

    /* Observe for scroll reveal */
    document.querySelectorAll('.ecard:not(.visible)').forEach(el => revealObs.observe(el));
    /* Lazy images */
    document.querySelectorAll('.ecard-img[data-src]').forEach(img => imgObs.observe(img));
}

/* ===== Observers ===== */
const revealObs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); revealObs.unobserve(e.target); } });
}, { threshold: 0.08 });

const imgObs = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.src = e.target.dataset.src;
            e.target.onload = () => e.target.classList.add('loaded');
            imgObs.unobserve(e.target);
        }
    });
}, { rootMargin: '200px' });

/* ===== Filter tabs ===== */
document.querySelectorAll('.ef-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.ef-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentEvtFilter = btn.dataset.filter;
        renderEvents();
    });
});

/* ===== Initial render ===== */
renderEvents();
</script>

<?php layout_footer(); ?>
