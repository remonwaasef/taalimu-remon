<style>
    .cookie-banner-container {
        position: fixed;
        bottom: -150px; /* Initially hidden */
        left: 0;
        right: 0;
        background: rgba(17, 24, 39, 0.95);
        backdrop-filter: blur(10px);
        color: #fff;
        padding: 1rem 2rem;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        transition: bottom 0.5s ease-in-out;
        box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
    .cookie-banner-container.show {
        bottom: 0;
    }
    .cookie-content {
        font-size: 0.9rem;
        text-align: center;
    }
    .cookie-content a {
        color: #60a5fa;
        text-decoration: underline;
    }
    .cookie-buttons {
        display: flex;
        gap: 1rem;
    }
    .cookie-btn {
        padding: 0.5rem 1.5rem;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }
    .btn-accept {
        background-color: #10b981;
        color: white;
    }
    .btn-accept:hover {
        background-color: #059669;
    }
    .btn-decline {
        background-color: transparent;
        color: #d1d5db;
        border: 1px solid #4b5563;
    }
    .btn-decline:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    @media(min-width: 768px) {
        .cookie-banner-container {
            flex-direction: row;
            text-align: left;
            padding: 1.5rem 3rem;
        }
        .cookie-content {
            text-align: left;
        }
    }
</style>

<div id="gdprCookieBanner" class="cookie-banner-container">
    <div class="cookie-content">
        <strong>نحن نهتم بخصوصيتك.</strong> 🍪 <br>
        نستخدم ملفات تعريف الارتباط (Cookies) لتحسين تجربتك على منصتنا، وتخصيص المحتوى، وتحليل الزيارات.
        بالنقر على "قبول الكل"، فإنك توافق على استخدامنا لملفات تعريف الارتباط وفقاً لـ
        <a href="{{ url('/privacy') }}">سياسة الخصوصية</a> الخاصة بنا.
    </div>
    <div class="cookie-buttons">
        <button id="declineCookies" class="cookie-btn btn-decline">رفض غير الضرورية</button>
        <button id="acceptCookies" class="cookie-btn btn-accept">قبول الكل</button>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const banner = document.getElementById('gdprCookieBanner');
        const btnAccept = document.getElementById('acceptCookies');
        const btnDecline = document.getElementById('declineCookies');

        // Check if user already made a choice
        const cookieChoice = localStorage.getItem('gdpr_cookie_consent');

        if (!cookieChoice) {
            // Delay banner slightly for better UX
            setTimeout(() => {
                banner.classList.add('show');
            }, 1000);
        }

        btnAccept.addEventListener('click', function() {
            localStorage.setItem('gdpr_cookie_consent', 'accepted');
            banner.classList.remove('show');
            // TODO: Initialize Google Analytics / Facebook Pixel here
        });

        btnDecline.addEventListener('click', function() {
            localStorage.setItem('gdpr_cookie_consent', 'declined');
            banner.classList.remove('show');
            // System will only use strictly necessary cookies
        });
    });
</script>
