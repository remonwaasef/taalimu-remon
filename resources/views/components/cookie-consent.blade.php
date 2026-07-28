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
