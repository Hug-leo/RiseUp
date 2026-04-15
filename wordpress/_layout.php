<?php
/**
 * Shared layout helpers for the standalone RiseUp site.
 * Include this file at the top of each page, then call
 * layout_header($title, $activeNav) and layout_footer().
 */

/** Detect which page is active based on filename */
function current_page(): string {
    return basename($_SERVER['SCRIPT_NAME']);
}

/**
 * Render the <head>, navbar and opening <main> wrapper.
 *
 * @param string $pageTitle  Browser tab title
 * @param string $activePage Filename of the active nav link (e.g. 'home.php')
 */
function layout_header(string $pageTitle = 'Học Bổng Vươn Lên', string $activePage = ''): void {
    if (!$activePage) {
        $activePage = current_page();
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES) ?> — Học Bổng Vươn Lên</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ===== Reset & Base ===== */
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
        html{scroll-behavior:smooth}
        body{font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;color:#2d3436;background:#fff;line-height:1.6;-webkit-font-smoothing:antialiased}
        a{color:inherit;text-decoration:none}
        img{display:block;max-width:100%}

        /* ===== Navbar ===== */
        .navbar{position:sticky;top:0;z-index:1000;background:#fff;border-bottom:1px solid #e8e8e8}
        .nav-inner{max-width:1100px;margin:0 auto;padding:0 24px;display:flex;align-items:center;justify-content:space-between;height:60px}
        .nav-brand{font-family:'Lora',serif;font-size:22px;font-weight:700;color:#1565C0;letter-spacing:-.5px}
        .nav-links{display:flex;gap:28px}
        .nav-links a{font-size:14px;font-weight:500;color:#636e72;transition:color .2s;padding:4px 0}
        .nav-links a:hover{color:#2d3436}
        .nav-links a.active{color:#2d3436;border-bottom:2px solid #4f46e5}
        .nav-toggle{display:none;background:none;border:none;cursor:pointer;padding:8px}
        .nav-toggle span{display:block;width:22px;height:2px;background:#2d3436;margin:5px 0;transition:.3s}

        /* ===== Shared hero ===== */
        .hero{background:linear-gradient(135deg,#1565C0,#0D47A1);color:#fff;text-align:center;padding:56px 24px}
        .hero h1{font-family:'Lora',serif;font-size:clamp(26px,4.5vw,42px);font-weight:700;margin-bottom:10px;opacity:0;animation:fadeUp .7s ease forwards}
        .hero p{font-size:clamp(14px,2vw,17px);opacity:0;animation:fadeUp .7s .15s ease forwards}
        @keyframes fadeUp{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:translateY(0)}}

        /* ===== Container ===== */
        .container{max-width:1100px;margin:0 auto;padding:0 24px}
        .section{padding:60px 0}
        .section-title{font-family:'Lora',serif;font-size:28px;font-weight:700;margin-bottom:8px;color:#2d3436}
        .section-subtitle{font-size:15px;color:#777;margin-bottom:32px}

        /* ===== Footer ===== */
        .site-footer{background:#1a1a2e;color:#ccc;padding:48px 24px 24px}
        .footer-inner{max-width:1100px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:32px}
        .footer-col h4{font-family:'Lora',serif;font-size:16px;color:#fff;margin-bottom:14px}
        .footer-col a{display:block;font-size:13px;color:#aaa;margin-bottom:8px;transition:color .2s}
        .footer-col a:hover{color:#fff}
        .footer-col p{font-size:13px;line-height:1.7}
        .footer-bottom{max-width:1100px;margin:24px auto 0;padding-top:20px;border-top:1px solid #333;text-align:center;font-size:12px;color:#666}

        /* ===== Buttons ===== */
        .btn{display:inline-block;padding:10px 24px;border-radius:6px;font-size:14px;font-weight:600;cursor:pointer;transition:all .2s;border:none}
        .btn-primary{background:#4f46e5;color:#fff}
        .btn-primary:hover{background:#4338ca}
        .btn-outline{background:transparent;color:#fff;border:2px solid rgba(255,255,255,.5)}
        .btn-outline:hover{background:rgba(255,255,255,.1);border-color:#fff}

        /* ===== Responsive ===== */
        @media(max-width:768px){
            .nav-links{display:none;flex-direction:column;position:absolute;top:60px;left:0;right:0;background:#fff;border-bottom:1px solid #e8e8e8;padding:16px 24px;gap:16px;box-shadow:0 4px 12px rgba(0,0,0,.08)}
            .nav-links.open{display:flex}
            .nav-toggle{display:block}
            .hero{padding:40px 20px}
            .section{padding:40px 0}
            .footer-inner{grid-template-columns:1fr 1fr}
        }
        @media(max-width:480px){
            .nav-inner{padding:0 16px}
            .hero{padding:32px 16px}
            .container{padding:0 16px}
            .footer-inner{grid-template-columns:1fr}
        }
        @media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:0s!important;transition-duration:0s!important}}
    </style>
<?php }

/**
 * Render everything between </head><body> opening through the navbar.
 */
function layout_nav(): void {
    $page = current_page();
?>
</head>
<body>
<nav class="navbar">
    <div class="nav-inner">
        <a href="home.php" class="nav-brand">Vươn Lên</a>
        <button class="nav-toggle" aria-label="Toggle menu" onclick="document.querySelector('.nav-links').classList.toggle('open')">
            <span></span><span></span><span></span>
        </button>
        <div class="nav-links">
            <a href="home.php"               class="<?= $page === 'home.php' ? 'active' : '' ?>">Trang Chu</a>
            <a href="announcement-feed.php"   class="<?= $page === 'announcement-feed.php' ? 'active' : '' ?>">Thong Bao</a>
            <a href="events.php"             class="<?= $page === 'events.php' ? 'active' : '' ?>">Su Kien</a>
            <a href="contact.php"            class="<?= $page === 'contact.php' ? 'active' : '' ?>">Lien He</a>
        </div>
    </div>
</nav>
<?php }

/**
 * Render the site footer and close </body></html>.
 */
function layout_footer(): void {
?>
<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-col">
            <h4>Vươn Lên</h4>
            <p>Quỹ Khuyến Học Đông Du.<br>Hỗ trợ sinh viên có ước mơ và hoài bão lớn tại TP. Hồ Chí Minh.</p>
        </div>
        <div class="footer-col">
            <h4>Trang</h4>
            <a href="home.php">Trang Chu</a>
            <a href="announcement-feed.php">Thong Bao</a>
            <a href="events.php">Su Kien</a>
            <a href="contact.php">Lien He</a>
        </div>
        <div class="footer-col">
            <h4>Lien He</h4>
            <p>Email: quykhuyenhocdongdu@gmail.com</p>
            <p>SDT: 084 3214 142</p>
            <p>Dia chi: 43D/46 Ho Van Hue, Phu Nhuan, TP.HCM</p>
        </div>
        <div class="footer-col">
            <h4>Theo Doi</h4>
            <a href="#">Facebook</a>
            <a href="#">Zalo</a>
            <a href="#">YouTube</a>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; <?= date('Y') ?> Học Bổng Vươn Lên &mdash; Quỹ Khuyến Học Đông Du
    </div>
</footer>
</body>
</html>
<?php }
