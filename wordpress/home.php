<?php
/**
 * Homepage — RiseUp School Announcements Site
 *
 * Sections: Hero with CTA, Stats counter, Latest announcements,
 * Upcoming events preview, About section.
 */
require_once __DIR__ . '/_layout.php';

layout_header('Trang Chu');
layout_nav();
?>

<!-- ============================= Hero ============================= -->
<section class="hero hero-home">
    <h1>Hoc Bong Vuon Len</h1>
    <p>Dong Du Study Encouragement Fund — Ho tro sinh vien co uoc mo va hoai bao lon tai TP. Ho Chi Minh</p>
    <div style="margin-top:24px;display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
        <a href="announcement-feed.php" class="btn btn-primary">Xem Thong Bao</a>
        <a href="events.php" class="btn btn-outline">Lich Su Kien</a>
    </div>
</section>

<!-- ============================= Stats ============================= -->
<section class="section stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-number" data-target="30">0</span>
                <span class="stat-label">Suat Hoc Bong/Nam</span>
            </div>
            <div class="stat-card">
                <span class="stat-number" data-target="9">0</span>
                <span class="stat-label">Trieu VND/Suat</span>
            </div>
            <div class="stat-card">
                <span class="stat-number" data-target="8">0</span>
                <span class="stat-label">Nam Hoat Dong</span>
            </div>
            <div class="stat-card">
                <span class="stat-number" data-target="3">0</span>
                <span class="stat-label">Quy Hoc Bong</span>
            </div>
        </div>
    </div>
</section>

<!-- ============================= Latest announcements ============================= -->
<section class="section" style="background:#fafafa">
    <div class="container">
        <h2 class="section-title">Thong Bao Moi Nhat</h2>
        <p class="section-subtitle">Nhung thong bao quan trong nhat tu nha truong</p>
        <div class="home-cards" id="latestCards">
            <!-- Rendered by JS -->
        </div>
        <div style="text-align:center;margin-top:28px">
            <a href="announcement-feed.php" class="btn btn-primary">Xem Tat Ca Thong Bao &rsaquo;</a>
        </div>
    </div>
</section>

<!-- ============================= Upcoming events ============================= -->
<section class="section">
    <div class="container">
        <h2 class="section-title">Su Kien Sap Toi</h2>
        <p class="section-subtitle">Cac hoat dong va su kien noi bat trong thang</p>
        <div class="events-timeline" id="eventsTimeline">
            <!-- Rendered by JS -->
        </div>
        <div style="text-align:center;margin-top:28px">
            <a href="events.php" class="btn btn-primary">Xem Tat Ca Su Kien &rsaquo;</a>
        </div>
    </div>
</section>

<!-- ============================= About snippet ============================= -->
<section class="section" style="background:#fafafa">
    <div class="container about-grid">
        <div class="about-text">
            <h2 class="section-title">Ve Chung Toi</h2>
            <p style="color:#555;line-height:1.8;margin-bottom:16px">
                Dong Du Study Encouragement Fund duoc sang lap boi thay Nguyen Duc Hoe — Hieu truong Truong Nhat ngu Dong Du. Quy hoat dong tu nam 2018, moi nam trao 30 suat hoc bong Vuon Len cho sinh vien cac truong dai hoc tai TP. Ho Chi Minh.
            </p>
            <p style="color:#555;line-height:1.8;margin-bottom:20px">
                Hoc bong danh cho sinh vien co uoc mo, hoai bao lon va ke hoach ro rang de thuc hien uoc mo. Ngoai hoc bong Vuon Len, Quy con co hoc bong La Xanh va Mai Vang.
            </p>
            <a href="contact.php" class="btn btn-primary">Lien He Ngay</a>
        </div>
        <div class="about-img">
            <img src="https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=600&h=400&fit=crop" alt="School" loading="lazy">
        </div>
    </div>
</section>

<style>
    /* ===== Homepage-specific styles ===== */
    .hero-home { padding: 72px 24px; }

    /* Stats */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; text-align: center; }
    .stat-card { background: #fff; border: 1px solid #e8e8e8; border-radius: 10px; padding: 32px 16px; transition: transform .25s, box-shadow .25s; }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.08); }
    .stat-number { font-family: 'Lora', serif; font-size: 40px; font-weight: 700; color: #1565C0; display: block; }
    .stat-label { font-size: 14px; color: #777; margin-top: 6px; display: block; }

    /* Home announcement cards */
    .home-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
    .hcard { background: #fff; border: 1px solid #e8e8e8; border-radius: 10px; overflow: hidden; transition: transform .25s, box-shadow .25s; }
    .hcard:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.08); }
    .hcard-img { width: 100%; height: 160px; object-fit: cover; background: #eee; opacity: 0; transition: opacity .5s; }
    .hcard-img.loaded { opacity: 1; }
    .hcard-body { padding: 20px; }
    .hcard-badge { display: inline-block; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; color: #fff; margin-bottom: 10px; }
    .hcard-title { font-family: 'Lora', serif; font-size: 17px; font-weight: 700; line-height: 1.35; margin-bottom: 8px; color: #2d3436; }
    .hcard-title a:hover { color: #1565C0; }
    .hcard-excerpt { font-size: 13px; color: #777; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .hcard-meta { padding: 12px 20px; border-top: 1px solid #f0f0f0; font-size: 12px; color: #999; display: flex; justify-content: space-between; }

    /* Events timeline */
    .events-timeline { display: flex; flex-direction: column; gap: 0; }
    .evt-row { display: flex; gap: 20px; padding: 24px 0; border-bottom: 1px solid #e8e8e8; align-items: flex-start; }
    .evt-date { flex-shrink: 0; width: 64px; height: 64px; background: #1565C0; color: #fff; border-radius: 10px; display: flex; flex-direction: column; align-items: center; justify-content: center; font-weight: 700; }
    .evt-date .day { font-size: 22px; line-height: 1; }
    .evt-date .month { font-size: 11px; text-transform: uppercase; opacity: .8; }
    .evt-info h3 { font-family: 'Lora', serif; font-size: 17px; font-weight: 700; color: #2d3436; margin-bottom: 4px; }
    .evt-info p { font-size: 13px; color: #777; line-height: 1.6; }
    .evt-info .evt-tag { display: inline-block; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 4px; margin-top: 6px; }

    /* About grid */
    .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: center; }
    .about-img img { border-radius: 10px; width: 100%; height: auto; }

    /* Scroll reveal */
    .reveal { opacity: 0; transform: translateY(20px); transition: opacity .5s, transform .5s; }
    .reveal.visible { opacity: 1; transform: translateY(0); }

    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .home-cards { grid-template-columns: 1fr; }
        .about-grid { grid-template-columns: 1fr; }
        .about-img { order: -1; }
        .hero-home { padding: 48px 20px; }
    }
    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
        .stat-number { font-size: 30px; }
        .stat-card { padding: 20px 12px; }
        .evt-row { flex-direction: column; gap: 12px; }
    }
</style>

<script>
/* ===== Sample data (latest 3 announcements) ===== */
const latest = [
    { title:"Lich Thi Giua Ky II - Nam Hoc 2025-2026", badge:"Quan Trong", color:"#e74c3c", date:"10/04/2026", image:"https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&h=300&fit=crop", excerpt:"Thong bao lich thi giua ky hoc ky II cho tat ca cac khoi lop." },
    { title:"Chuong Trinh Van Nghe Chao Mung 30/4 - 1/5", badge:"Su Kien", color:"#f39c12", date:"08/04/2026", image:"https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&h=300&fit=crop", excerpt:"Chuong trinh van nghe dac sac nhan dip ky niem ngay le lon." },
    { title:"Ket Qua Hoc Sinh Gioi Cap Thanh Pho", badge:"Hoc Tap", color:"#27ae60", date:"02/04/2026", image:"https://images.unsplash.com/photo-1523050854058-8df90110c476?w=600&h=300&fit=crop", excerpt:"Chuc mung 12 em hoc sinh dat giai trong ky thi cap thanh pho." }
];

/* ===== Upcoming events ===== */
const events = [
    { day:"12", month:"Th04", title:"Hop Phu Huynh Giua Hoc Ky II", desc:"Luc 8h00 tai hoi truong. De nghi phu huynh tham du day du.", tag:"Quan Trong", tagColor:"#e74c3c" },
    { day:"15", month:"Th04", title:"Han Cuoi Dang Ky Tiet Muc Van Nghe", desc:"Cac lop nop phieu dang ky cho Doan truong.", tag:"Su Kien", tagColor:"#f39c12" },
    { day:"18", month:"Th04", title:"Han Nop De Tai Khoa Hoc Ky Thuat", desc:"Nhom 2-3 hoc sinh nop de tai tai phong Hoc Vu.", tag:"Hoc Tap", tagColor:"#3498db" },
    { day:"25", month:"Th04", title:"Cuoc Thi Khoa Hoc Ky Thuat Cap Truong", desc:"Thi tai hoi truong. Giai thuong hap dan cho cac doi xuat sac.", tag:"Su Kien", tagColor:"#f39c12" }
];

/* ===== Render latest cards ===== */
const cardsEl = document.getElementById('latestCards');
latest.forEach(a => {
    const card = document.createElement('div');
    card.className = 'hcard reveal';
    card.innerHTML = `
        ${a.image ? `<img class="hcard-img" data-src="${a.image}" alt="" loading="lazy">` : ''}
        <div class="hcard-body">
            <span class="hcard-badge" style="background:${a.color}">${a.badge}</span>
            <h3 class="hcard-title"><a href="announcement-feed.php">${a.title}</a></h3>
            <p class="hcard-excerpt">${a.excerpt}</p>
        </div>
        <div class="hcard-meta"><span>${a.date}</span><a href="announcement-feed.php" style="color:#1565C0;font-weight:600">Xem &rsaquo;</a></div>`;
    cardsEl.appendChild(card);
});

/* ===== Render events timeline ===== */
const timeEl = document.getElementById('eventsTimeline');
events.forEach(e => {
    const row = document.createElement('div');
    row.className = 'evt-row reveal';
    row.innerHTML = `
        <div class="evt-date"><span class="day">${e.day}</span><span class="month">${e.month}</span></div>
        <div class="evt-info">
            <h3>${e.title}</h3>
            <p>${e.desc}</p>
            <span class="evt-tag" style="background:${e.tagColor}15;color:${e.tagColor}">${e.tag}</span>
        </div>`;
    timeEl.appendChild(row);
});

/* ===== Animated stat counter ===== */
function animateCounters() {
    document.querySelectorAll('.stat-number').forEach(el => {
        const target = +el.dataset.target;
        const dur = 1600;
        const start = performance.now();
        function tick(now) {
            const progress = Math.min((now - start) / dur, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.round(target * eased).toLocaleString();
            if (progress < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    });
}

/* ===== Intersection observers ===== */
const revealObs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); revealObs.unobserve(e.target); } });
}, { threshold: 0.1 });
document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));

/* Stats counter triggers once */
const statsObs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { animateCounters(); statsObs.unobserve(e.target); } });
}, { threshold: 0.3 });
const statsEl = document.querySelector('.stats-section');
if (statsEl) statsObs.observe(statsEl);

/* Lazy images */
const imgObs = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.src = e.target.dataset.src;
            e.target.onload = () => e.target.classList.add('loaded');
            imgObs.unobserve(e.target);
        }
    });
}, { rootMargin: '200px' });
document.querySelectorAll('img[data-src]').forEach(img => imgObs.observe(img));
</script>

<?php layout_footer(); ?>
