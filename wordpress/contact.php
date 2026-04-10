<?php
/**
 * Contact page — School contact info, map placeholder, and contact form.
 */
require_once __DIR__ . '/_layout.php';

/* Handle form submission (display a simple confirmation) */
$formSent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_name'])) {
    /* Validate CSRF-like token */
    if (!isset($_POST['_token']) || $_POST['_token'] !== session_id()) {
        http_response_code(403);
        exit('Invalid request.');
    }
    /* In production, send an email or store in DB here. */
    $formSent = true;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

layout_header('Lien He');
layout_nav();
?>

<!-- ============================= Hero ============================= -->
<section class="hero">
    <h1>Lien He Voi Chung Toi</h1>
    <p>Gui thac mac, y kien dong gop hoac yeu cau ho tro — chung toi luon san sang giup do</p>
</section>

<!-- ============================= Contact info cards ============================= -->
<section class="section">
    <div class="container">
        <div class="contact-cards">
            <div class="ccard reveal">
                <div class="ccard-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <h3>Dia Chi</h3>
                <p>43D/46 Ho Van Hue<br>Phu Nhuan, TP. Ho Chi Minh</p>
            </div>
            <div class="ccard reveal">
                <div class="ccard-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.86 19.86 0 0 1 3.09 5.18 2 2 0 0 1 5.11 3h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 11.91a16 16 0 0 0 6 6l2.27-2.27a2 2 0 0 1 2.11-.45c.907.339 1.85.574 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </div>
                <h3>Dien Thoai</h3>
                <p>084 3214 142<br>(Thanh Ven)</p>
            </div>
            <div class="ccard reveal">
                <div class="ccard-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </div>
                <h3>Email</h3>
                <p>quykhuyenhocdongdu@gmail.com</p>
            </div>
            <div class="ccard reveal">
                <div class="ccard-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <h3>Gio Lam Viec</h3>
                <p>Thu 2 — Thu 6: 7:00 — 17:00<br>Thu 7: 7:00 — 11:30</p>
            </div>
        </div>
    </div>
</section>

<!-- ============================= Map + Form ============================= -->
<section class="section" style="background:#fafafa">
    <div class="container contact-grid">
        <!-- Map placeholder -->
        <div class="map-wrap reveal">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3920.024!2d106.7!3d10.73!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTDCsDQzJzQ4LjAiTiAxMDbCsDQyJzAwLjAiRQ!5e0!3m2!1svi!2s!4v1"
                width="100%" height="100%" style="border:0;border-radius:10px;min-height:360px" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="School location map"></iframe>
        </div>

        <!-- Contact form -->
        <div class="form-wrap reveal">
            <?php if ($formSent): ?>
                <div class="form-success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#27ae60" stroke-width="2" style="width:48px;height:48px;margin-bottom:12px"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <h3>Gui Thanh Cong!</h3>
                    <p>Cam on ban da lien he. Chung toi se phan hoi trong thoi gian som nhat.</p>
                    <a href="contact.php" class="btn btn-primary" style="margin-top:16px">Gui Lai</a>
                </div>
            <?php else: ?>
                <h2 style="font-family:'Lora',serif;font-size:24px;font-weight:700;margin-bottom:6px">Gui Tin Nhan</h2>
                <p style="font-size:14px;color:#777;margin-bottom:24px">Dien thong tin ben duoi, chung toi se lien lac som nhat co the.</p>
                <form method="POST" action="contact.php" class="contact-form" id="contactForm">
                    <input type="hidden" name="_token" value="<?= htmlspecialchars(session_id(), ENT_QUOTES) ?>">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="cname">Ho va Ten *</label>
                            <input type="text" id="cname" name="contact_name" required placeholder="Nguyen Van A">
                        </div>
                        <div class="form-group">
                            <label for="cphone">So Dien Thoai *</label>
                            <input type="tel" id="cphone" name="contact_phone" required placeholder="0909 123 456">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cemail">Email</label>
                        <input type="email" id="cemail" name="contact_email" placeholder="email@example.com">
                    </div>
                    <div class="form-group">
                        <label for="csubject">Chu De</label>
                        <select id="csubject" name="contact_subject">
                            <option value="">-- Chon chu de --</option>
                            <option value="academic">Hoc Tap</option>
                            <option value="event">Su Kien</option>
                            <option value="tuition">Hoc Phi</option>
                            <option value="feedback">Gop Y</option>
                            <option value="other">Khac</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cmessage">Noi Dung *</label>
                        <textarea id="cmessage" name="contact_message" rows="5" required placeholder="Noi dung tin nhan..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;padding:12px">Gui Tin Nhan &rsaquo;</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ============================= FAQ ============================= -->
<section class="section">
    <div class="container" style="max-width:780px">
        <h2 class="section-title" style="text-align:center">Cau Hoi Thuong Gap</h2>
        <p class="section-subtitle" style="text-align:center">Nhung thac mac pho bien tu phu huynh</p>
        <div class="faq-list" id="faqList">
            <!-- Rendered by JS -->
        </div>
    </div>
</section>

<style>
    /* ===== Contact info cards ===== */
    .contact-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
    .ccard { background: #fff; border: 1px solid #e8e8e8; border-radius: 10px; padding: 28px 20px; text-align: center; transition: transform .25s, box-shadow .25s; }
    .ccard:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.08); }
    .ccard-icon { margin-bottom: 14px; }
    .ccard-icon svg { width: 32px; height: 32px; margin: 0 auto; }
    .ccard h3 { font-family: 'Lora', serif; font-size: 16px; font-weight: 700; margin-bottom: 8px; }
    .ccard p { font-size: 13px; color: #777; line-height: 1.6; }

    /* ===== Map + Form grid ===== */
    .contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; align-items: start; }
    .map-wrap { border-radius: 10px; overflow: hidden; min-height: 400px; }

    /* ===== Form ===== */
    .form-wrap { background: #fff; border: 1px solid #e8e8e8; border-radius: 10px; padding: 32px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 6px; }
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 6px;
        font-size: 14px; font-family: inherit; transition: border-color .2s; background: #fff;
    }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus { outline: none; border-color: #1565C0; box-shadow: 0 0 0 3px rgba(79,70,229,.1); }
    .form-group textarea { resize: vertical; }

    /* Success state */
    .form-success { text-align: center; padding: 40px 20px; }
    .form-success h3 { font-family: 'Lora', serif; font-size: 22px; margin-bottom: 8px; color: #27ae60; }
    .form-success p { font-size: 14px; color: #777; }

    /* ===== FAQ ===== */
    .faq-item { border-bottom: 1px solid #e8e8e8; }
    .faq-q { width: 100%; background: none; border: none; padding: 20px 0; display: flex; justify-content: space-between; align-items: center; cursor: pointer; text-align: left; font-size: 15px; font-weight: 600; color: #2d3436; }
    .faq-q svg { width: 18px; height: 18px; stroke: #999; flex-shrink: 0; transition: transform .3s; }
    .faq-q.open svg { transform: rotate(180deg); }
    .faq-a { max-height: 0; overflow: hidden; transition: max-height .3s ease; }
    .faq-a-inner { padding: 0 0 20px; font-size: 14px; color: #666; line-height: 1.7; }

    /* Scroll reveal */
    .reveal { opacity: 0; transform: translateY(20px); transition: opacity .5s, transform .5s; }
    .reveal.visible { opacity: 1; transform: translateY(0); }

    @media (max-width: 768px) {
        .contact-cards { grid-template-columns: 1fr 1fr; }
        .contact-grid { grid-template-columns: 1fr; }
        .form-row { grid-template-columns: 1fr; }
    }
    @media (max-width: 480px) {
        .contact-cards { grid-template-columns: 1fr; }
    }
</style>

<script>
/* ===== FAQ data ===== */
const faqs = [
    { q: "Lam sao de theo doi thong bao cua nha truong?", a: "Phu huynh co the truy cap trang Thong Bao tren website nay bat cu luc nao. Cac thong bao moi se duoc cap nhat lien tuc." },
    { q: "Thoi gian nop hoc phi nhu the nao?", a: "Hoc phi duoc thu theo tung hoc ky. Thong thuong han nop la ngay 20 cua thang dau hoc ky. Chi tiet se duoc thong bao cu the qua trang Thong Bao." },
    { q: "Toi muon gap giao vien chu nhiem thi lam sao?", a: "Phu huynh co the lien he qua so dien thoai cua truong hoac gui tin nhan qua muc Lien He. Nha truong se sap xep lich hen gap." },
    { q: "Con toi nghi hoc thi bao the nao?", a: "Khi hoc sinh nghi hoc, phu huynh can thong bao cho giao vien chu nhiem qua dien thoai hoac tin nhan truoc 7h30 sang ngay nghi." },
    { q: "Truong co chuong trinh ngoai khoa nao khong?", a: "Truong co nhieu cau lac bo va hoat dong ngoai khoa nhu: Khoa hoc, Van nghe, The thao, Tieng Anh, Robotics. Chi tiet xem tai muc Su Kien." }
];

/* Render FAQ accordion */
const faqList = document.getElementById('faqList');
faqs.forEach(f => {
    const item = document.createElement('div');
    item.className = 'faq-item';
    item.innerHTML = `
        <button class="faq-q" type="button">
            ${f.q}
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="faq-a"><div class="faq-a-inner">${f.a}</div></div>`;
    faqList.appendChild(item);
});

/* Toggle accordion */
document.querySelectorAll('.faq-q').forEach(btn => {
    btn.addEventListener('click', () => {
        const answer = btn.nextElementSibling;
        const isOpen = btn.classList.contains('open');
        /* Close all */
        document.querySelectorAll('.faq-q.open').forEach(b => {
            b.classList.remove('open');
            b.nextElementSibling.style.maxHeight = null;
        });
        if (!isOpen) {
            btn.classList.add('open');
            answer.style.maxHeight = answer.scrollHeight + 'px';
        }
    });
});

/* Scroll reveal */
const revealObs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); revealObs.unobserve(e.target); } });
}, { threshold: 0.1 });
document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));
</script>

<?php layout_footer(); ?>
