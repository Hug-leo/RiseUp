<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoc Bong Vuon Len - Thong Bao</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ===== Reset & Base ===== */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #2d3436;
            background: #fff;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; text-decoration: none; }
        img { display: block; max-width: 100%; }

        /* ===== Navbar ===== */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: #fff;
            border-bottom: 1px solid #e8e8e8;
        }
        .nav-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 60px;
        }
        .nav-brand {
            font-family: 'Lora', serif;
            font-size: 22px;
            font-weight: 700;
            color: #1565C0;
            letter-spacing: -0.5px;
        }
        .nav-links { display: flex; gap: 32px; }
        .nav-links a {
            font-size: 14px;
            font-weight: 500;
            color: #636e72;
            transition: color .2s;
        }
        .nav-links a:hover, .nav-links a.active { color: #2d3436; }
        .nav-links a.active { border-bottom: 2px solid #1565C0; padding-bottom: 2px; }

        /* Mobile hamburger */
        .nav-toggle { display: none; background: none; border: none; cursor: pointer; padding: 8px; }
        .nav-toggle span { display: block; width: 22px; height: 2px; background: #2d3436; margin: 5px 0; transition: .3s; }

        /* ===== Hero banner ===== */
        .hero {
            background: linear-gradient(135deg, #1565C0, #0D47A1);
            color: #fff;
            text-align: center;
            padding: 56px 24px;
        }
        .hero h1 {
            font-family: 'Lora', serif;
            font-size: clamp(26px, 4.5vw, 42px);
            font-weight: 700;
            margin-bottom: 10px;
            opacity: 0;
            animation: fadeUp .7s ease forwards;
        }
        .hero p {
            font-size: clamp(14px, 2vw, 17px);
            opacity: 0;
            animation: fadeUp .7s .15s ease forwards;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ===== Sub-nav tabs (Featured / Activity feed style) ===== */
        .sub-nav {
            border-bottom: 1px solid #e8e8e8;
            background: #fff;
        }
        .sub-nav-inner {
            max-width: 780px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            gap: 28px;
        }
        .sub-nav button {
            background: none;
            border: none;
            padding: 14px 0;
            font-size: 14px;
            font-weight: 600;
            color: #999;
            cursor: pointer;
            position: relative;
            transition: color .2s;
        }
        .sub-nav button:hover { color: #2d3436; }
        .sub-nav button.active { color: #2d3436; }
        .sub-nav button.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 2px;
            background: #1565C0;
        }

        /* ===== Feed container ===== */
        .feed {
            max-width: 780px;
            margin: 0 auto;
            padding: 0 24px 60px;
        }

        /* ===== Section heading (like "Winning stories") ===== */
        .section-heading {
            font-family: 'Lora', serif;
            font-size: 15px;
            font-weight: 600;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 36px 0 6px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e8e8e8;
        }

        /* ===== Story / Announcement card ===== */
        .card {
            padding: 28px 0;
            border-bottom: 1px solid #e8e8e8;
            /* Scroll-triggered animation */
            opacity: 0;
            transform: translateY(20px);
            transition: opacity .5s ease, transform .5s ease;
        }
        .card.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .card-title {
            font-family: 'Lora', serif;
            font-size: 20px;
            font-weight: 700;
            line-height: 1.35;
            margin-bottom: 12px;
            color: #2d3436;
            transition: color .2s;
        }
        .card-title a:hover { color: #1565C0; }

        /* Author row */
        .card-author {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
        }
        .card-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            background: #eee;
            flex-shrink: 0;
        }
        /* Placeholder avatar with initials */
        .card-avatar-placeholder {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            color: #fff;
        }
        .card-author-name {
            font-size: 13px;
            font-weight: 600;
            color: #1565C0;
        }
        .card-author-meta {
            font-size: 12px;
            color: #999;
        }

        /* Featured image */
        .card-image {
            width: 100%;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 14px;
            background: #f5f5f5;
            position: relative;
        }
        .card-image img {
            width: 100%;
            height: 240px;
            object-fit: cover;
            opacity: 0;
            transition: opacity .6s ease;
        }
        .card-image img.loaded { opacity: 1; }
        .card-image img:hover { transform: scale(1.02); transition: transform .4s ease, opacity .6s ease; }

        /* Excerpt */
        .card-excerpt {
            font-size: 14.5px;
            color: #555;
            line-height: 1.7;
            margin-bottom: 14px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Footer: likes, comments, read link */
        .card-footer {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 13px;
            color: #999;
        }
        .card-stat {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .card-stat svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; }
        .card-read {
            margin-left: auto;
            font-weight: 600;
            color: #1565C0;
            transition: opacity .2s;
        }
        .card-read:hover { opacity: .7; }

        /* ===== Load more ===== */
        .load-more-wrap { text-align: center; padding: 30px 0 50px; }
        .load-more-btn {
            padding: 10px 28px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #fff;
            font-size: 14px;
            font-weight: 600;
            color: #555;
            cursor: pointer;
            transition: all .2s;
        }
        .load-more-btn:hover { border-color: #1565C0; color: #1565C0; }

        /* ===== Footer site  ===== */
        .site-footer {
            background: #fafafa;
            border-top: 1px solid #e8e8e8;
            padding: 40px 24px;
            text-align: center;
            font-size: 13px;
            color: #999;
        }

        /* ===== Responsive ===== */
        @media (max-width: 768px) {
            .nav-links { display: none; flex-direction: column; position: absolute; top: 60px; left: 0; right: 0; background: #fff; border-bottom: 1px solid #e8e8e8; padding: 16px 24px; gap: 16px; }
            .nav-links.open { display: flex; }
            .nav-toggle { display: block; }
            .hero { padding: 40px 20px; }
            .sub-nav-inner { gap: 20px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .sub-nav button { white-space: nowrap; font-size: 13px; }
            .card { padding: 22px 0; }
            .card-title { font-size: 17px; }
            .card-image img { height: 180px; }
            .card-excerpt { font-size: 13.5px; -webkit-line-clamp: 3; }
        }
        @media (max-width: 480px) {
            .nav-inner { padding: 0 16px; }
            .hero { padding: 32px 16px; }
            .feed { padding: 0 16px 40px; }
            .sub-nav-inner { padding: 0 16px; }
            .card-title { font-size: 16px; }
            .card-image img { height: 150px; }
            .card-footer { flex-wrap: wrap; gap: 12px; }
            .card-read { margin-left: 0; }
        }

        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: 0s !important; transition-duration: 0s !important; }
        }
    </style>
</head>
<body>

<!-- ============================== Navbar ============================== -->
<nav class="navbar">
    <div class="nav-inner">
        <a href="home.php" class="nav-brand">Vươn Lên</a>
        <button class="nav-toggle" aria-label="Toggle menu">
            <span></span><span></span><span></span>
        </button>
        <div class="nav-links">
            <a href="home.php">Trang Chu</a>
            <a href="announcement-feed.php" class="active">Thong Bao</a>
            <a href="events.php">Su Kien</a>
            <a href="contact.php">Lien He</a>
        </div>
    </div>
</nav>

<!-- ============================== Hero ============================== -->
<section class="hero">
    <h1>Thong Bao Tu Nha Truong</h1>
    <p>Cap nhat tin tuc va thong bao quan trong cho phu huynh va hoc sinh</p>
</section>

<!-- ============================== Sub-nav tabs ============================== -->
<div class="sub-nav">
    <div class="sub-nav-inner">
        <button class="active" data-filter="all">Tat Ca</button>
        <button data-filter="pinned">Ghim</button>
        <button data-filter="event">Su Kien</button>
        <button data-filter="academic">Hoc Tap</button>
    </div>
</div>

<!-- ============================== Feed ============================== -->
<main class="feed" id="feed">
    <!-- Cards rendered by JS below -->
</main>

<!-- Load more -->
<div class="load-more-wrap">
    <button class="load-more-btn" id="loadMoreBtn">Tai Them</button>
</div>

<!-- ============================== Footer ============================== -->
<footer class="site-footer">
    Học Bổng Vươn Lên &copy; 2026 &mdash; <a href="home.php" style="color:#ccc">Trang Chu</a> &middot; <a href="events.php" style="color:#ccc">Su Kien</a> &middot; <a href="contact.php" style="color:#ccc">Lien He</a>
</footer>

<script>
/**
 * Announcement data.
 * Replace or extend this array with real content.
 * Each object follows the structure shown below.
 */
const announcements = [
    {
        id: 1,
        title: "Lich Thi Giua Ky II - Nam Hoc 2025-2026",
        author: "Ban Giam Hieu",
        badge: "Quan Trong",
        date: "10/04/2026",
        category: "pinned",
        color: "#e74c3c",
        image: "https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800&h=400&fit=crop",
        excerpt: "Thong bao lich thi giua ky hoc ky II cho tat ca cac khoi lop. Phu huynh vui long ho tro con em on tap dung tien do de dat ket qua tot nhat. Lich thi chi tiet duoc dinh kem ben duoi.",
        likes: 128,
        comments: 34
    },
    {
        id: 2,
        title: "Chuong Trinh Van Nghe Chao Mung 30/4 - 1/5",
        author: "Doan Truong",
        badge: "Su Kien",
        date: "08/04/2026",
        category: "event",
        color: "#f39c12",
        image: "https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&h=400&fit=crop",
        excerpt: "Nhan dip ky niem ngay Giai Phong Mien Nam va Quoc Te Lao Dong, truong se to chuc chuong trinh van nghe dac sac. Cac lop dang ky tiet muc truoc ngay 15/04.",
        likes: 95,
        comments: 21
    },
    {
        id: 3,
        title: "Huong Dan Nop Hoc Phi Hoc Ky II",
        author: "Phong Tai Vu",
        badge: "Thong Tin",
        date: "05/04/2026",
        category: "academic",
        color: "#3498db",
        image: "",
        excerpt: "Phu huynh vui long hoan thanh hoc phi hoc ky II truoc ngay 20/04/2026. Ho tro chuyen khoan qua ngan hang hoac thanh toan truc tiep tai phong tai vu. Chi tiet tai khoan duoc gui kem theo thong bao nay.",
        likes: 67,
        comments: 15
    },
    {
        id: 4,
        title: "Ket Qua Ky Thi Hoc Sinh Gioi Cap Thanh Pho",
        author: "Phong Hoc Vu",
        badge: "Hoc Tap",
        date: "02/04/2026",
        category: "academic",
        color: "#27ae60",
        image: "https://images.unsplash.com/photo-1523050854058-8df90110c476?w=800&h=400&fit=crop",
        excerpt: "Chuc mung cac em hoc sinh dat giai trong ky thi hoc sinh gioi cap thanh pho nam 2026. Truong co 12 em dat giai, trong do 3 giai Nhat, 4 giai Nhi va 5 giai Ba. Danh sach chi tiet nhu sau.",
        likes: 213,
        comments: 56
    },
    {
        id: 5,
        title: "Thong Bao Nghi Le 30/4 - 1/5",
        author: "Ban Giam Hieu",
        badge: "Quan Trong",
        date: "01/04/2026",
        category: "pinned",
        color: "#e74c3c",
        image: "",
        excerpt: "Hoc sinh duoc nghi le tu ngay 30/04 den het ngay 01/05/2026. Thoi gian hoc lai binh thuong tu thu Hai ngay 04/05. Phu huynh luu y dam bao an toan cho con em trong thoi gian nghi le.",
        likes: 89,
        comments: 7
    },
    {
        id: 6,
        title: "Kham Suc Khoe Dinh Ky Hoc Ky II",
        author: "Phong Y Te",
        badge: "Thong Tin",
        date: "28/03/2026",
        category: "academic",
        color: "#3498db",
        image: "https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=800&h=400&fit=crop",
        excerpt: "Chuong trinh kham suc khoe dinh ky cho hoc sinh se dien ra tu ngay 14/04 den 18/04. Phu huynh vui long ky xac nhan vao phieu dong y kham suc khoe va nop lai cho giao vien chu nhiem.",
        likes: 45,
        comments: 11
    },
    {
        id: 7,
        title: "Cuoc Thi Khoa Hoc Ky Thuat Cap Truong",
        author: "To Khoa Hoc",
        badge: "Su Kien",
        date: "25/03/2026",
        category: "event",
        color: "#f39c12",
        image: "https://images.unsplash.com/photo-1567168544813-cc03465b4fa8?w=800&h=400&fit=crop",
        excerpt: "Cuoc thi Khoa Hoc Ky Thuat cap truong nam 2026 se dien ra ngay 25/04. Hoc sinh dang ky tham gia theo nhom 2-3 em. Han cuoi nop de tai: 18/04. Giai thuong hap dan danh cho cac doi xuat sac.",
        likes: 72,
        comments: 19
    },
    {
        id: 8,
        title: "Hop Phu Huynh Giua Hoc Ky II",
        author: "Ban Giam Hieu",
        badge: "Quan Trong",
        date: "22/03/2026",
        category: "pinned",
        color: "#e74c3c",
        image: "",
        excerpt: "Truong to chuc hop phu huynh giua hoc ky II vao ngay 12/04/2026, luc 8h00 sang. De nghi phu huynh sap xep thoi gian tham du de nam bat tinh hinh hoc tap cua con em.",
        likes: 156,
        comments: 42
    }
];

/* Current state */
let currentFilter = "all";
let visibleCount = 6;
const BATCH_SIZE = 4;

/**
 * Render the feed based on current filter and visible count.
 * Clears existing cards and rebuilds from data array.
 */
function renderFeed() {
    const feed = document.getElementById("feed");
    feed.innerHTML = "";

    const filtered = currentFilter === "all"
        ? announcements
        : announcements.filter(a => a.category === currentFilter);

    const toShow = filtered.slice(0, visibleCount);

    /* Group cards by category section headers */
    let lastSection = "";
    const sectionOrder = { pinned: "Ghim", event: "Su Kien", academic: "Hoc Tap" };

    if (currentFilter === "all") {
        /* When showing all, group pinned first, then the rest chronologically */
        const pinned = toShow.filter(a => a.category === "pinned");
        const rest = toShow.filter(a => a.category !== "pinned");

        if (pinned.length) {
            const heading = document.createElement("h3");
            heading.className = "section-heading";
            heading.textContent = "📌 Ghim";
            feed.appendChild(heading);
            pinned.forEach(a => feed.appendChild(createCard(a)));
        }
        if (rest.length) {
            const heading = document.createElement("h3");
            heading.className = "section-heading";
            heading.textContent = "📰 Tat Ca Thong Bao";
            feed.appendChild(heading);
            rest.forEach(a => feed.appendChild(createCard(a)));
        }
    } else {
        const label = sectionOrder[currentFilter] || currentFilter;
        const heading = document.createElement("h3");
        heading.className = "section-heading";
        heading.textContent = label;
        feed.appendChild(heading);
        toShow.forEach(a => feed.appendChild(createCard(a)));
    }

    /* Toggle load-more button visibility */
    const btn = document.getElementById("loadMoreBtn");
    btn.style.display = visibleCount >= filtered.length ? "none" : "";

    /* Trigger scroll-reveal for newly added cards */
    observeCards();
}

/**
 * Build a single card DOM element from an announcement object.
 * @param {Object} data - Announcement data object
 * @returns {HTMLElement} The card element
 */
function createCard(data) {
    const card = document.createElement("article");
    card.className = "card";
    card.dataset.category = data.category;

    /* Build inner HTML */
    let html = "";

    /* Title */
    html += `<h2 class="card-title"><a href="#">${esc(data.title)}</a></h2>`;

    /* Author row */
    const initial = data.author.charAt(0).toUpperCase();
    html += `
        <div class="card-author">
            <div class="card-avatar-placeholder" style="background:${data.color}">${initial}</div>
            <div>
                <div class="card-author-name">${esc(data.author)}</div>
                <div class="card-author-meta">${esc(data.date)} &middot; <span style="color:${data.color};font-weight:600">${esc(data.badge)}</span></div>
            </div>
        </div>`;

    /* Image (lazy loaded with fade-in) */
    if (data.image) {
        html += `
            <div class="card-image">
                <img data-src="${esc(data.image)}" alt="${esc(data.title)}" loading="lazy">
            </div>`;
    }

    /* Excerpt */
    html += `<p class="card-excerpt">${esc(data.excerpt)}</p>`;

    /* Footer stats */
    html += `
        <div class="card-footer">
            <span class="card-stat">
                <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                ${data.likes}
            </span>
            <span class="card-stat">
                <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                ${data.comments}
            </span>
            <a href="#" class="card-read">Xem chi tiet &rsaquo;</a>
        </div>`;

    card.innerHTML = html;
    return card;
}

/**
 * Simple HTML-escape to prevent XSS when inserting user-provided text.
 * @param {string} str - Raw string to escape
 * @returns {string} Escaped string safe for innerHTML
 */
function esc(str) {
    const el = document.createElement("span");
    el.textContent = str;
    return el.innerHTML;
}

/* ===== Intersection Observer: scroll reveal cards ===== */
let cardObserver;
function observeCards() {
    if (cardObserver) cardObserver.disconnect();
    cardObserver = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add("visible");
                cardObserver.unobserve(e.target);
            }
        });
    }, { threshold: 0.08 });
    document.querySelectorAll(".card:not(.visible)").forEach(c => cardObserver.observe(c));
}

/* ===== Intersection Observer: lazy-load images with fade ===== */
const imgObserver = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            const img = e.target;
            img.src = img.dataset.src;
            img.onload = () => img.classList.add("loaded");
            imgObserver.unobserve(img);
        }
    });
}, { rootMargin: "200px" });

/* Watch for new images added to the DOM */
const domObserver = new MutationObserver(() => {
    document.querySelectorAll(".card-image img[data-src]:not(.loaded)").forEach(img => {
        if (!img.src || img.src === location.href) imgObserver.observe(img);
    });
});
domObserver.observe(document.getElementById("feed"), { childList: true, subtree: true });

/* ===== Sub-nav filter tabs ===== */
document.querySelectorAll(".sub-nav button").forEach(btn => {
    btn.addEventListener("click", () => {
        document.querySelectorAll(".sub-nav button").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
        currentFilter = btn.dataset.filter;
        visibleCount = 6;
        renderFeed();
        /* Scroll feed into view smoothly */
        document.getElementById("feed").scrollIntoView({ behavior: "smooth", block: "start" });
    });
});

/* ===== Load more button ===== */
document.getElementById("loadMoreBtn").addEventListener("click", () => {
    visibleCount += BATCH_SIZE;
    renderFeed();
});

/* ===== Mobile nav toggle ===== */
document.querySelector(".nav-toggle").addEventListener("click", () => {
    document.querySelector(".nav-links").classList.toggle("open");
});

/* ===== Initial render ===== */
renderFeed();
</script>
</body>
</html>
