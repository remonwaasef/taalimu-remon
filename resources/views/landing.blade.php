<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Taalimu تساعدك على إدارة مركزك التعليمي بسهولة، من الحضور والطلاب إلى المدفوعات والتواصل مع أولياء الأمور.">
    <title>Taalimu — ركّز على تعليم طلابك والإدارة علينا</title>
    @vite(['resources/css/landing.css', 'resources/js/landing.js'])
</head>
<body class="bg-cream text-ink antialiased">

<header class="site-header">
    <div class="container nav">
        <a href="{{ route('home') }}" class="brand" aria-label="Taalimu">
            <span class="brand-mark">T</span>
            <span><strong>Taalimu</strong><small>إدارة تعليمية أسهل</small></span>
        </a>

        <nav class="desktop-nav" aria-label="التنقل الرئيسي">
            <a href="#features">المميزات</a>
            <a href="#workflow">كيف يعمل</a>
            <a href="#pricing">الأسعار</a>
            <a href="#faq">الأسئلة الشائعة</a>
            <a href="#contact">تواصل معنا</a>
        </nav>

        <div class="nav-actions">
            <a class="btn btn-outline" href="{{ route('login.portal') }}">تسجيل الدخول</a>
            <a class="btn btn-primary" href="{{ route('register') }}">ابدأ تجربتك مجانًا</a>
        </div>

        <button class="mobile-menu" type="button" aria-label="فتح القائمة" aria-expanded="false">☰</button>
    </div>
</header>

<main>
    {{-- HERO --}}
    <section class="hero">
        <div class="container hero-grid">
            <div class="hero-copy reveal">
                <span class="eyebrow">إدارة تعليمية أسهل</span>
                <h1>ركّز على تعليم طلابك..<br><span>والإدارة علينا</span></h1>
                <p class="hero-lead">
                    Taalimu تجمع كل ما تحتاجه لإدارة مركزك التعليمي بسهولة؛
                    من الطلاب والحضور إلى الأقساط والتواصل مع أولياء الأمور.
                </p>

                <div class="hero-actions">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">ابدأ تجربتك المجانية <span>←</span></a>
                    <a href="#workflow" class="btn btn-soft btn-lg">شاهد كيف يعمل النظام <span>▶</span></a>
                </div>

                <div class="trust-points">
                    <span>✓ تجربة مجانية 14 يوم</span>
                    <span>✓ لا تحتاج بطاقة بنكية</span>
                    <span>✓ إلغاء في أي وقت</span>
                </div>
            </div>

            <div class="hero-visual reveal">
                <div class="photo-card">
                    <img src="{{ asset('images/landing/hero-realistic.webp') }}" alt="مدرس Taalimu" class="photo-img">
                </div>

                <div class="floating-card attendance">
                    <div class="mini-icon">⌁</div>
                    <div>
                        <strong>تم تسجيل حضور أحمد</strong>
                        <small>اليوم · 8:15 ص</small>
                    </div>
                </div>

                <div class="floating-card whatsapp">
                    <div class="wa-icon">◔</div>
                    <div>
                        <strong>إشعار ولي الأمر</strong>
                        <small>تم تسجيل الحضور بنجاح</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- HUMAN PROBLEM --}}
    <section class="daily-problem section">
        <div class="container">
            <div class="section-heading reveal">
                <span class="eyebrow">يومك الحقيقي</span>
                <h2>هل هذا يومك كل يوم؟</h2>
                <p>إدارة المركز تتطلب وقتك... والوقت لا يكفي.</p>
            </div>

            <div class="timeline">
                <article class="moment reveal"><span>08:00 ص</span><div class="moment-icon">👥</div><h3>تبدأ الحصة</h3><p>تضيّع وقتًا في تسجيل الحضور.</p></article>
                <article class="moment reveal"><span>11:00 ص</span><div class="moment-icon">▤</div><h3>تسجيل الدفعات</h3><p>ومراجعة متكررة للأقساط.</p></article>
                <article class="moment reveal"><span>02:00 م</span><div class="moment-icon">☎</div><h3>اتصالات أولياء الأمور</h3><p>للاستفسار عن حضور أبنائهم.</p></article>
                <article class="moment reveal"><span>06:00 م</span><div class="moment-icon">▦</div><h3>تقارير مبنية على Excel</h3><p>وتحديث يدوي مرهق.</p></article>
                <article class="moment reveal"><span>10:00 م</span><div class="moment-icon">⌕</div><h3>تحاول معرفة أداء المركز</h3><p>بعد يوم طويل من الإدارة.</p></article>
            </div>

            <p class="problem-bottom reveal">تضيع وقتك في الإدارة... بدل ما تستثمره في التعليم وتنمو بمركزك.</p>
        </div>
    </section>

    {{-- SOLUTION --}}
    <section class="solution section">
        <div class="container solution-grid">
            <div class="solution-copy reveal">
                <span class="eyebrow">مع Taalimu</span>
                <h2>كل شيء في مكان واحد.</h2>
                <p>ببساطة، ووضوح في الإدارة، تواصل أفضل، ووقت أكبر للتعليم.</p>

                <ul class="benefit-list">
                    <li><span>✓</span><div><strong>حضور بالـQR في ثوانٍ</strong><small>بدون دفاتر وبدون وقت ضائع.</small></div></li>
                    <li><span>✓</span><div><strong>أولياء الأمور يعرفون أولًا بأول</strong><small>إشعارات واتساب تلقائية.</small></div></li>
                    <li><span>✓</span><div><strong>إدارة مالية واضحة</strong><small>الأقساط، المدفوعات والمستحقات.</small></div></li>
                    <li><span>✓</span><div><strong>تقارير ولوحات تحكم لحظية</strong><small>اعرف حال مركزك بسرعة.</small></div></li>
                </ul>
            </div>

            <div class="dashboard reveal" aria-label="نموذج لوحة تحكم">
                <div class="dashboard-top"><span>مرحبًا، مدير المركز</span><span class="status-dot"></span></div>
                <div class="stats">
                    <div><small>عدد الطلاب</small><strong>236</strong><em>↑ 12%</em></div>
                    <div><small>التحصيل اليوم</small><strong>32,450</strong><em>ج.م</em></div>
                    <div><small>نسبة الحضور</small><strong>92.4%</strong><em>اليوم</em></div>
                </div>
                <div class="chart">
                    <div class="chart-title">أداء المركز</div>
                    <div class="bars"><i style="height:35%"></i><i style="height:48%"></i><i style="height:42%"></i><i style="height:62%"></i><i style="height:57%"></i><i style="height:78%"></i><i style="height:70%"></i><i style="height:88%"></i></div>
                </div>
                <div class="dashboard-menu"><span>الرئيسية</span><span>الطلاب</span><span>الحضور</span><span>المدفوعات</span><span>التقارير</span></div>
            </div>
        </div>
    </section>

    {{-- WORKFLOW --}}
    <section id="workflow" class="workflow section">
        <div class="container">
            <div class="section-heading reveal">
                <span class="eyebrow">ببساطة</span>
                <h2>كيف يعمل Taalimu؟</h2>
                <p>من لحظة دخول الطالب حتى تعرف حال مركزك، كل شيء يتحرك تلقائيًا.</p>
            </div>

            <div class="steps">
                <article class="step reveal"><b>1</b><div class="step-icon">▦</div><h3>امسح QR الطالب</h3><p>يتم تسجيل حضوره فورًا.</p></article>
                <article class="step reveal"><b>2</b><div class="step-icon">◉</div><h3>تحديث الحضور</h3><p>يظهر في النظام لحظيًا.</p></article>
                <article class="step reveal"><b>3</b><div class="step-icon">◔</div><h3>إشعار ولي الأمر</h3><p>يصل عبر واتساب تلقائيًا.</p></article>
                <article class="step reveal"><b>4</b><div class="step-icon">▣</div><h3>تحديث مالي تلقائي</h3><p>تتحدث المدفوعات والمستحقات.</p></article>
                <article class="step reveal"><b>5</b><div class="step-icon">⌁</div><h3>تقرير فوري</h3><p>اعرف أداء مركزك في ثوانٍ.</p></article>
            </div>
        </div>
    </section>

    {{-- FEATURES --}}
    <section id="features" class="features section">
        <div class="container">
            <div class="section-heading reveal">
                <span class="eyebrow">كل ما تحتاجه</span>
                <h2>إدارة مركزك بدون فوضى.</h2>
                <p>أهم أدوات الإدارة اليومية في تجربة واحدة بسيطة.</p>
            </div>

            <div class="feature-grid">
                <article class="feature-card reveal">
                    <div class="feature-shot">
                        <img src="{{ asset('images/landing_fixed/hero-dashboard.png') }}" alt="لوحة التحكم" class="feature-img">
                    </div>
                    <h3>لوحة التحكم</h3>
                    <p>نظرة سريعة على مركزك في أي وقت.</p>
                    <a href="{{ route('register') }}">عرض التفاصيل ←</a>
                </article>
                <article class="feature-card reveal">
                    <div class="feature-shot">
                        <img src="{{ asset('images/landing_fixed/roles.png') }}" alt="إدارة مالية متكاملة" class="feature-img">
                    </div>
                    <h3>إدارة مالية متكاملة</h3>
                    <p>الأقساط، المدفوعات ومستحقات المدرسين.</p>
                    <a href="{{ route('register') }}">عرض التفاصيل ←</a>
                </article>
                <article class="feature-card reveal">
                    <div class="feature-shot">
                        <img src="{{ asset('images/landing_fixed/whatsapp.png') }}" alt="التواصل عبر WhatsApp" class="feature-img">
                    </div>
                    <h3>التواصل عبر WhatsApp</h3>
                    <p>إشعارات تلقائية بكل ما يهم أولياء الأمور.</p>
                    <a href="{{ route('register') }}">عرض التفاصيل ←</a>
                </article>
                <article class="feature-card reveal">
                    <div class="feature-shot">
                        <img src="{{ asset('images/landing_fixed/attendance.png') }}" alt="الحضور بالـQR" class="feature-img">
                    </div>
                    <h3>الحضور بالـQR</h3>
                    <p>حضور سريع بدون تسجيل يدوي.</p>
                    <a href="{{ route('register') }}">عرض التفاصيل ←</a>
                </article>
                <article class="feature-card reveal">
                    <div class="feature-shot">
                        <img src="{{ asset('images/automation/step1.webp') }}" alt="إدارة الطلاب" class="feature-img">
                    </div>
                    <h3>إدارة الطلاب</h3>
                    <p>بيانات الطلاب والمجموعات والمستحقات في مكان واحد.</p>
                    <a href="{{ route('register') }}">عرض التفاصيل ←</a>
                </article>
            </div>
        </div>
    </section>

    {{-- TESTIMONIAL --}}
    <section class="testimonial section">
        <div class="container testimonial-box reveal">
            <div class="testimonial-photo">
                <img src="{{ asset('images/portals/teacher.jpg') }}" alt="أ. أحمد محمود" class="testimonial-img">
            </div>
            <div>
                <span class="eyebrow">تجربة من الواقع</span>
                <blockquote>«من أول يوم في Taalimu، بقي عندي وقت أكتر لطلابي، ونظام المركز كله بقى تحت السيطرة.»</blockquote>
                <strong>أ. أحمد محمود</strong>
                <small>مدير مركز تعليمي</small>
            </div>
        </div>
    </section>

    {{-- PRICING --}}
    <section id="pricing" class="pricing section">
        <div class="container">
            <div class="section-heading reveal">
                <span class="eyebrow">بسيطة وواضحة</span>
                <h2>خطط تناسب جميع المراكز</h2>
                <p>ابدأ بالحجم المناسب لك، وغيّر خطتك مع نمو مركزك.</p>
            </div>

            <div class="pricing-grid">
                <article class="price-card reveal"><h3>البداية</h3><div class="price">450 <small>ج.م / شهريًا</small></div><ul><li>إدارة الطلاب</li><li>حضور QR</li><li>تواصل أساسي مع أولياء الأمور</li></ul><a class="btn btn-dark" href="{{ route('register') }}">ابدأ تجربتك مجانًا</a></article>
                <article class="price-card featured reveal"><span class="popular">الأكثر استخدامًا</span><h3>النمو</h3><div class="price">950 <small>ج.م / شهريًا</small></div><ul><li>كل مميزات البداية</li><li>إدارة مالية متكاملة</li><li>WhatsApp وإشعارات</li><li>تقارير متقدمة</li></ul><a class="btn btn-primary" href="{{ route('register') }}">ابدأ تجربتك مجانًا</a></article>
                <article class="price-card reveal"><h3>المتقدم</h3><div class="price">1,950 <small>ج.م / شهريًا</small></div><ul><li>كل مميزات النمو</li><li>فروع متعددة</li><li>صلاحيات متقدمة</li><li>تقارير إدارية موسعة</li></ul><a class="btn btn-dark" href="{{ route('register') }}">ابدأ تجربتك مجانًا</a></article>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section id="faq" class="faq section">
        <div class="container faq-grid">
            <div class="section-heading reveal"><span class="eyebrow">قبل أن تبدأ</span><h2>كل اللي محتاج تعرفه.</h2><p>إجابات سريعة على الأسئلة الأكثر شيوعًا.</p></div>
            <div class="faq-list reveal">
                <details><summary>هل توجد تجربة مجانية؟ <span>+</span></summary><p>نعم، يمكنك تجربة Taalimu لمدة 14 يومًا بدون بطاقة بنكية.</p></details>
                <details><summary>هل تناسب المدرس والمركز؟ <span>+</span></summary><p>نعم، يمكن إعداد التجربة حسب طريقة عملك وحجم المركز.</p></details>
                <details><summary>هل يمكن لأولياء الأمور استقبال إشعارات؟ <span>+</span></summary><p>نعم، يدعم النظام إرسال إشعارات مرتبطة بالأحداث التعليمية المهمة.</p></details>
                <details><summary>هل أستطيع تغيير الخطة لاحقًا؟ <span>+</span></summary><p>نعم، يمكنك ترقية أو تغيير الخطة حسب احتياجات مركزك.</p></details>
            </div>
        </div>
    </section>

    {{-- FINAL CTA --}}
    <section id="trial" class="final-cta section">
        <div class="container final-cta-box reveal">
            <span class="eyebrow">ابدأ من النهارده</span>
            <h2>ابدأ الآن وامنح طلابك تجربة تعليمية أفضل.</h2>
            <p>جرّب Taalimu مجانًا لمدة 14 يومًا، بدون التزام.</p>
            <a href="{{ route('register') }}" class="btn btn-light btn-lg">ابدأ تجربتك المجانية ←</a>
        </div>
    </section>
</main>

<footer id="contact" class="footer">
    <div class="container footer-grid">
        <div><a class="brand footer-brand" href="{{ route('home') }}"><span class="brand-mark">T</span><span><strong>Taalimu</strong><small>إدارة تعليمية أسهل</small></span></a><p>منصة تساعد المراكز والمدرسين على إدارة أعمالهم التعليمية ببساطة وكفاءة.</p></div>
        <div><h4>المنتج</h4><a href="#features">المميزات</a><a href="#pricing">الأسعار</a><a href="#workflow">كيف يعمل</a></div>
        <div><h4>الشركة</h4><a href="{{ route('privacy') }}">سياسة الخصوصية</a><a href="{{ route('terms') }}">الشروط والأحكام</a><a href="{{ route('cookies') }}">ملفات تعريف الارتباط</a></div>
        <div><h4>الدعم</h4><a href="#faq">الأسئلة الشائعة</a><a href="#contact">تواصل معنا</a><a href="{{ route('login.portal') }}">تسجيل الدخول</a></div>
    </div>
    <div class="container footer-bottom"><span>© {{ date('Y') }} Taalimu</span><span>صُنع للتعليم بشكل أبسط</span></div>
</footer>
</body>
</html>
