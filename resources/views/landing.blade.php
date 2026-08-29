<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Taalimu تساعدك على إدارة مركزك التعليمي بسهولة، من الحضور والطلاب حتى الأقساط والتواصل مع أولياء الأمور.">
    <title>Taalimu — ركز على تعليم طلابك.. والإدارة علينا</title>
    <link rel="icon" type="image/png" href="{{ asset('images/brand/logo-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/landing.css', 'resources/js/landing.js'])
</head>
<body>

{{-- HEADER --}}
<header class="site-header">
    <div class="container nav">
        <a href="{{ route('home') }}" class="brand" aria-label="Taalimu">
            <div class="brand-icon">T</div>
            <div class="brand-text">
                <strong>Taalimu</strong>
                <small>إدارة تعليمية أسهل</small>
            </div>
        </a>

        <nav class="desktop-nav" aria-label="التنقل الرئيسي">
            <a href="{{ route('home') }}" class="active">الرئيسية</a>
            <a href="#features">المميزات</a>
            <a href="#pricing">الأسعار</a>
            <a href="#workflow">من نحن</a>
            <a href="#faq">الأسئلة الشائعة</a>
        </nav>

        <div class="nav-actions">
            <a href="{{ route('lang.switch', 'ar') }}" class="lang-switch">
                <span>🌐</span> AR
            </a>
            <a class="btn btn-outline" href="{{ route('login.portal') }}">تسجيل الدخول</a>
            <a class="btn btn-primary" href="{{ route('register') }}">ابدأ تجربتك مجانًا</a>
        </div>

        <button class="mobile-menu-toggle" type="button" aria-label="فتح القائمة" id="mobileMenuBtn">☰</button>
    </div>
</header>

<main>
    {{-- HERO SECTION --}}
    <section class="hero">
        <div class="container hero-grid">
            <div class="hero-visual">
                <div class="hero-photo-wrapper">
                    <img src="{{ asset('images/landing/hero-teacher-main.jpg') }}" alt="مدرس Taalimu في بيئة تعليمية">
                    
                    {{-- Floating WhatsApp Card --}}
                    <div class="floating-wa-card">
                        <div class="wa-badge-icon">💬</div>
                        <div class="wa-badge-content">
                            <strong>تم تسجيل حضور أحمد</strong>
                            <time>اليوم 8:15 ص</time>
                            <small>✓ مجموعة الرياضيات - الصف الثاني</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hero-content">
                <h1>ركز على تعليم طلابك..<br><span>والإدارة علينا</span></h1>
                <p class="hero-description">
                    Taalimu تساعدك على إدارة مركزك التعليمي بسهولة، من الحضور والطلاب حتى الأقساط والتواصل مع أولياء الأمور.
                </p>

                <div class="hero-ctas">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                        ابدأ تجربتك المجانية <span>←</span>
                    </a>
                    <a href="#workflow" class="btn btn-video btn-lg">
                        <span class="play-icon">▶</span>
                        شاهد كيف يعمل النظام
                    </a>
                </div>

                <div class="trust-badges">
                    <div class="trust-badge-item">
                        <span>📅</span> تجربة مجانية 14 يوم
                    </div>
                    <div class="trust-badge-item">
                        <span>🛡️</span> لا تحتاج بطاقة بنكية
                    </div>
                    <div class="trust-badge-item">
                        <span>🔄</span> إلغاء في أي وقت
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- PROBLEM SECTION --}}
    <section class="problem-section section">
        <div class="container">
            <div class="section-title-wrap">
                <h2>هل هذا يومك كل يوم؟</h2>
                <p>إدارة المركز تتطلب وقتك... والوقت لا يكفي.</p>
            </div>

            <div class="problem-grid">
                {{-- Moment 1 --}}
                <article class="problem-card">
                    <span class="time-pill">08:00 ص</span>
                    <div class="problem-img-wrap">
                        <img src="{{ asset('images/landing/problem-1-attendance.jpg') }}" alt="نداء الأسماء وتسجيل الحضور">
                    </div>
                    <h3>تبدأ الحصة بنداء الأسماء</h3>
                    <p>وضياع وقت الحضور</p>
                </article>

                {{-- Moment 2 --}}
                <article class="problem-card">
                    <span class="time-pill">11:00 ص</span>
                    <div class="problem-img-wrap">
                        <img src="{{ asset('images/landing/problem-2-ledger.jpg') }}" alt="تسجيل الدفعات يدوياً">
                    </div>
                    <h3>تسجيل الدفعات يدويًا</h3>
                    <p>ومراجعة متكررة</p>
                </article>

                {{-- Moment 3 --}}
                <article class="problem-card">
                    <span class="time-pill">02:00 م</span>
                    <div class="problem-img-wrap">
                        <img src="{{ asset('images/landing/problem-3-phone.jpg') }}" alt="اتصالات أولياء الأمور">
                    </div>
                    <h3>اتصالات من أولياء الأمور</h3>
                    <p>للاستفسار عن أبنائهم</p>
                </article>

                {{-- Moment 4 --}}
                <article class="problem-card">
                    <span class="time-pill">06:00 م</span>
                    <div class="problem-img-wrap">
                        <img src="{{ asset('images/landing/problem-4-excel.jpg') }}" alt="تقارير Excel معقدة">
                    </div>
                    <h3>تقارير معقدة في Excel</h3>
                    <p>وتحديث يدوي مرهق</p>
                </article>

                {{-- Moment 5 --}}
                <article class="problem-card">
                    <span class="time-pill">10:00 م</span>
                    <div class="problem-img-wrap">
                        <img src="{{ asset('images/landing/problem-5-stressed.jpg') }}" alt="تحليل أداء المركز">
                    </div>
                    <h3>تحليل معرفة أداء المركز</h3>
                    <p>بعد يوم طويل</p>
                </article>
            </div>

            <div class="problem-footer-note">
                تضييع الوقت في الإدارة... يعني وقت أقل في التعليم ونمو أبطأ للمركز.
            </div>
        </div>
    </section>

    {{-- SOLUTION SECTION --}}
    <section id="features" class="solution-section section">
        <div class="container">
            <div class="section-title-wrap">
                <h2>مع Taalimu... كل شيء في مكان واحد</h2>
                <p>بساطة في الإدارة، وضوح في البيانات، تواصل أفضل، وقت أكثر للتعليم.</p>
            </div>

            <div class="solution-grid">
                <div class="solution-benefits">
                    <div class="benefit-item">
                        <div class="benefit-icon">📱</div>
                        <div class="benefit-info">
                            <h3>حضور بالـQR في ثوانٍ</h3>
                            <p>بدون نداء، بدون ورق.</p>
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon">🔔</div>
                        <div class="benefit-info">
                            <h3>أولياء الأمور يعرفون أولاً بأول</h3>
                            <p>إشعارات واتساب تلقائية.</p>
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon">💳</div>
                        <div class="benefit-info">
                            <h3>إدارة مالية واضحة</h3>
                            <p>الأقساط، المدفوعات، المستحقات.</p>
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon">📊</div>
                        <div class="benefit-info">
                            <h3>تقارير ولوحات تحكم لحظية</h3>
                            <p>اعرف مركزك في أي لحظة.</p>
                        </div>
                    </div>
                </div>

                <div class="dashboard-mockup-wrapper">
                    <img src="{{ asset('images/hero-dashboard.webp') }}" alt="لوحة تحكم منصة Taalimu">
                </div>
            </div>
        </div>
    </section>

    {{-- WORKFLOW SECTION --}}
    <section id="workflow" class="workflow-section section">
        <div class="container">
            <div class="section-title-wrap">
                <h2>كيف يعمل Taalimu؟</h2>
            </div>

            <div class="workflow-steps">
                <div class="workflow-step">
                    <div class="step-icon-bubble">📱</div>
                    <h3>امسح QR الطالب</h3>
                    <p>يتم تسجيل حضوره فورًا</p>
                </div>

                <div class="workflow-step">
                    <div class="step-icon-bubble">👥</div>
                    <h3>تحديث الحضور</h3>
                    <p>يظهر في النظام لحظيًا</p>
                </div>

                <div class="workflow-step">
                    <div class="step-icon-bubble">💬</div>
                    <h3>إشعار ولي الأمر</h3>
                    <p>يصل عبر واتساب تلقائيًا</p>
                </div>

                <div class="workflow-step">
                    <div class="step-icon-bubble">💳</div>
                    <h3>تحديث مالي تلقائي</h3>
                    <p>تتحدث الدفعات والمستحقات</p>
                </div>

                <div class="workflow-step">
                    <div class="step-icon-bubble">📊</div>
                    <h3>تقرير فوري</h3>
                    <p>اعرف أداء مركزك في ثانية</p>
                </div>
            </div>
        </div>
    </section>

    {{-- TESTIMONIAL SECTION --}}
    <section class="testimonial-section section">
        <div class="container">
            <div class="testimonial-banner">
                <div class="testimonial-photo-side">
                    <img src="{{ asset('images/landing/testimonial-story.jpg') }}" alt="قصة نجاح مدرس مع Taalimu">
                </div>
                <div class="testimonial-quote-side">
                    <div class="quote-mark">❝</div>
                    <blockquote>
                        من أول يوم في Taalimu، بقي عندي وقت أكثر لطلابي، ونظام المركز كله بقى تحت السيطرة.
                    </blockquote>
                    <div class="testimonial-author">
                        <strong>أ. أحمد محمود</strong>
                        <small>مدير مركز تعليمي</small>
                    </div>

                    <div class="testimonial-controls">
                        <div class="carousel-dots">
                            <span class="active"></span>
                            <span></span>
                            <span></span>
                        </div>
                        <div class="carousel-arrows">
                            <button class="arrow-btn" aria-label="السابق">‹</button>
                            <button class="arrow-btn" aria-label="التالي">›</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FINAL CTA SECTION --}}
    <section class="final-cta-section">
        <div class="container">
            <div class="cta-box-center">
                <h2>ابدأ الآن وامنح طلابك تجربة تعليمية أفضل</h2>
                <p>جرب Taalimu مجانًا لمدة 14 يوم، بدون أي التزام.</p>
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                    ابدأ تجربتك المجانية <span>←</span>
                </a>
            </div>
        </div>
    </section>
</main>

{{-- FOOTER --}}
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="{{ route('home') }}" class="brand" aria-label="Taalimu">
                    <div class="brand-icon">T</div>
                    <div class="brand-text">
                        <strong style="color: #fff;">Taalimu</strong>
                        <small style="color: #8c9ba9;">إدارة تعليمية أسهل</small>
                    </div>
                </a>
                <p>المنصة المتكاملة لإدارة المراكز التعليمية والمدرسين المستقلين بكل بساطة واحترافية.</p>
            </div>

            <div class="footer-col">
                <h4>المنتج</h4>
                <a href="#features">المميزات</a>
                <a href="#workflow">كيف يعمل</a>
                <a href="#pricing">الأسعار</a>
            </div>

            <div class="footer-col">
                <h4>الشركة</h4>
                <a href="{{ route('privacy') }}">سياسة الخصوصية</a>
                <a href="{{ route('terms') }}">الشروط والأحكام</a>
                <a href="{{ route('cookies') }}">ملفات تعريف الارتباط</a>
            </div>

            <div class="footer-col">
                <h4>الحساب</h4>
                <a href="{{ route('login.portal') }}">تسجيل الدخول</a>
                <a href="{{ route('register') }}">إنشاء حساب جديد</a>
            </div>
        </div>

        <div class="footer-bottom">
            <span>© {{ date('Y') }} Taalimu. جميع الحقوق محفوظة.</span>
            <span>صُنع للتعليم بشكل أبسط وأذكى 🚀</span>
        </div>
    </div>
</footer>

</body>
</html>
