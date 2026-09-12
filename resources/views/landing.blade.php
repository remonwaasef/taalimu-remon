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
            <a href="{{ route('lang.switch', 'ar') }}" class="lang-switch flex items-center gap-1.5" aria-label="اللغة">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                <span>AR</span>
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
                        <div class="wa-badge-icon" style="color: #25D366;">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.072.043.419-.101.824z"/></svg>
                        </div>
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
                        <svg class="w-4 h-4 text-brand-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>تجربة مجانية 14 يوم</span>
                    </div>
                    <div class="trust-badge-item">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>لا تحتاج بطاقة بنكية</span>
                    </div>
                    <div class="trust-badge-item">
                        <svg class="w-4 h-4 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>إلغاء في أي وقت</span>
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
                        <div class="benefit-icon-svg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </div>
                        <div class="benefit-info">
                            <h3>حضور بالـQR في ثوانٍ</h3>
                            <p>بدون نداء، بدون ورق.</p>
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon-svg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        <div class="benefit-info">
                            <h3>أولياء الأمور يعرفون أولاً بأول</h3>
                            <p>إشعارات واتساب تلقائية فور الحضور.</p>
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon-svg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <div class="benefit-info">
                            <h3>إدارة مالية واضحة</h3>
                            <p>الأقساط، المدفوعات، والمستحقات المعلقة.</p>
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon-svg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div class="benefit-info">
                            <h3>تقارير ولوحات تحكم لحظية</h3>
                            <p>اعرف إحصائيات مركزك في أي لحظة.</p>
                        </div>
                    </div>
                </div>

                <div class="dashboard-mockup-wrapper">
                    <img src="{{ asset('images/hero-dashboard.webp') }}" alt="لوحة تحكم منصة Taalimu">
                </div>
            </div>
        </div>
    </section>

    {{-- INTERACTIVE ROI TIME-SAVER CALCULATOR SECTION --}}
    <section class="calculator-section section" id="roi-calculator" x-data="{
        students: 250,
        get hoursSaved() { return Math.round(this.students * 0.12); },
        get moneySaved() { return Math.round(this.students * 180 * 0.15).toLocaleString(); },
        get messagesSent() { return Math.round(this.students * 8).toLocaleString(); }
    }">
        <div class="container">
            <div class="section-title-wrap">
                <h2>كم ستوفر مع Taalimu شهرياً؟</h2>
                <p>حرك المؤشر حسب عدد طلاب مركزك وشاهد العائد الفعلي على وقتك وأرباحك.</p>
            </div>

            <div class="calculator-card">
                <div class="calc-grid">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-800 text-lg">عدد طلاب المركز:</span>
                            <span class="text-2xl font-black text-brand-primary font-mono" x-text="students + ' طالب'">250 طالب</span>
                        </div>
                        <div class="calc-slider-wrap">
                            <input type="range" min="30" max="1500" step="10" x-model="students" class="calc-range" aria-label="عدد طلاب المركز">
                            <div class="flex justify-between text-xs text-slate-400 mt-2 font-mono">
                                <span>30 طالب</span>
                                <span>500 طالب</span>
                                <span>1,500 طالب</span>
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            💡 الإحصائيات مستندة إلى متوسط دراسة سلوك المراكز التعليمية: توفير وقت نداء الحضور، تقليل التسرب المالي، وأتمتة رسائل أولياء الأمور عبر واتساب.
                        </p>
                    </div>

                    <div class="calc-result-box">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                <span class="text-xs font-bold text-slate-600">⏱️ ساعات توفرها شهرياً:</span>
                                <span class="text-xl font-black text-emerald-600" x-text="hoursSaved + ' ساعة'">30 ساعة</span>
                            </div>
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                <span class="text-xs font-bold text-slate-600">💰 أقساط متأخرة يتم تحصيلها:</span>
                                <span class="text-xl font-black text-brand-primary" x-text="moneySaved + ' ج.م'">6,750 ج.م</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-600">💬 إشعارات واتساب تلقائية:</span>
                                <span class="text-lg font-black text-sky-600" x-text="messagesSent + ' إشعار'">2,000 إشعار</span>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-200/60">
                            <a href="{{ route('register') }}" class="btn btn-primary w-full text-center block py-3 rounded-xl font-bold shadow-md">
                                ابدأ تجربة مجانية لمركزك الآن
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- WORKFLOW SECTION --}}
    <section id="workflow" class="workflow-section section">
        <div class="container">
            <div class="section-title-wrap">
                <h2>كيف يعمل Taalimu؟</h2>
                <p>دورة عمل يومية ذكية تلغي العمل اليدوي في 5 خطوات ميسرة.</p>
            </div>

            <div class="workflow-steps">
                <div class="workflow-step">
                    <div class="step-icon-svg mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </div>
                    <h3>امسح QR الطالب</h3>
                    <p>يتم تسجيل حضوره في ثانية واحدة</p>
                </div>

                <div class="workflow-step">
                    <div class="step-icon-svg mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3>تحديث الحضور</h3>
                    <p>يظهر في لوحة المشرف لحظيًا</p>
                </div>

                <div class="workflow-step">
                    <div class="step-icon-svg mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <h3>إشعار ولي الأمر</h3>
                    <p>يصل تنبيه واتساب فوري للأهل</p>
                </div>

                <div class="workflow-step">
                    <div class="step-icon-svg mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3>تحديث مالي تلقائي</h3>
                    <p>خصم الحصة وتسجيل رصيد الطالب</p>
                </div>

                <div class="workflow-step">
                    <div class="step-icon-svg mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                    </div>
                    <h3>تقرير فوري</h3>
                    <p>إحصائيات إيرادات ونسب حضور دقيقة</p>
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
