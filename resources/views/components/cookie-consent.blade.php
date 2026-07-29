<div id="gdprCookieBanner" class="cookie-banner-container" style="
    position: fixed;
    bottom: 1.5rem;
    left: 1.5rem;
    max-width: 460px;
    background: #0f172a;
    color: #ffffff;
    border: 1px solid #334155;
    border-radius: 1rem;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    z-index: 999999;
    display: none;
    flex-direction: column;
    gap: 1rem;
    transition: opacity 0.4s ease, transform 0.4s ease;
    opacity: 0;
    transform: translateY(20px);
">
    <div class="cookie-content" style="color: #cbd5e1; font-size: 0.85rem; line-height: 1.6;">
        <strong style="color: #ffffff; font-size: 0.95rem;">نحن نهتم بخصوصيتك. 🍪</strong><br>
        نستخدم ملفات تعريف الارتباط (Cookies) لتحسين تجربتك على منصتنا وتخصيص المحتوى. بالنقر على "قبول الكل"، فإنك توافق على استخدامنا لملفات تعريف الارتباط وفقاً لـ
        <a href="{{ url('/privacy') }}" style="color: #34d399; text-decoration: underline; font-weight: 700;">سياسة الخصوصية</a>.
    </div>
    <div class="cookie-buttons" style="display: flex; gap: 0.75rem; justify-content: flex-end;">
        <button id="declineCookies" style="
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: #e2e8f0;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        " onmouseover="this.style.background='rgba(255,255,255,0.18)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">رفض غير الضرورية</button>
        
        <button id="acceptCookies" style="
            padding: 0.5rem 1.25rem;
            border-radius: 0.5rem;
            background: #059669;
            border: none;
            color: #ffffff;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(5,150,105,0.3);
            transition: background 0.2s;
        " onmouseover="this.style.background='#047857'" onmouseout="this.style.background='#059669'">قبول الكل</button>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const banner = document.getElementById('gdprCookieBanner');
        const btnAccept = document.getElementById('acceptCookies');
        const btnDecline = document.getElementById('declineCookies');

        if (!banner) return;

        // Check if user already made a choice
        const cookieChoice = localStorage.getItem('gdpr_cookie_consent');

        if (!cookieChoice) {
            setTimeout(() => {
                banner.style.display = 'flex';
                requestAnimationFrame(() => {
                    banner.style.opacity = '1';
                    banner.style.transform = 'translateY(0)';
                });
            }, 800);
        }

        btnAccept?.addEventListener('click', function() {
            localStorage.setItem('gdpr_cookie_consent', 'accepted');
            banner.style.opacity = '0';
            banner.style.transform = 'translateY(20px)';
            setTimeout(() => banner.style.display = 'none', 400);
        });

        btnDecline?.addEventListener('click', function() {
            localStorage.setItem('gdpr_cookie_consent', 'declined');
            banner.style.opacity = '0';
            banner.style.transform = 'translateY(20px)';
            setTimeout(() => banner.style.display = 'none', 400);
        });
    });
</script>
