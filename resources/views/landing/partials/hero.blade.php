{{-- Hero Section — Pure Inline CSS for Bulletproof Layout & Contrast --}}
<section class="hero-section" dir="rtl" style="
    position: relative;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #020617 100%);
    padding: 7rem 0 5rem 0;
    overflow: hidden;
    color: #ffffff;
">
    {{-- Subtle Ambient Glows (NO heavy grid lines) --}}
    <div style="position: absolute; inset: 0; pointer-events: none; overflow: hidden; z-index: 1;">
        <div style="position: absolute; top: -100px; right: 10%; width: 450px; height: 450px; background: radial-gradient(circle, rgba(16, 185, 129, 0.15) 0%, transparent 70%);"></div>
        <div style="position: absolute; bottom: -100px; left: 10%; width: 450px; height: 450px; background: radial-gradient(circle, rgba(14, 165, 233, 0.1) 0%, transparent 70%);"></div>
    </div>

    {{-- Main Container --}}
    <div style="
        position: relative;
        z-index: 10;
        max-width: 1240px;
        margin: 0 auto;
        padding: 0 1.5rem;
    ">
        <div style="
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 3rem;
        ">
            
            {{-- RIGHT COLUMN: Text & Actions --}}
            <div style="
                flex: 1 1 520px;
                max-width: 600px;
                width: 100%;
                text-align: right;
            ">
                
                {{-- 1. Badge --}}
                <div style="
                    display: inline-flex;
                    align-items: center;
                    gap: 0.625rem;
                    padding: 0.5rem 1.25rem;
                    border-radius: 9999px;
                    background: rgba(16, 185, 129, 0.12);
                    border: 1px solid rgba(16, 185, 129, 0.3);
                    margin-bottom: 1.5rem;
                ">
                    <span style="width: 0.5rem; height: 0.5rem; border-radius: 50%; background: #34d399; display: inline-block;"></span>
                    <span style="color: #34d399 !important; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                        منصة SaaS رقم 1 لإدارة المراكز التعليمية
                    </span>
                    <i class="fas fa-sparkles" style="color: #fbbf24; font-size: 0.75rem;"></i>
                </div>

                {{-- 2. Headline --}}
                <h1 style="
                    color: #ffffff !important;
                    font-size: clamp(2rem, 4vw, 3.25rem);
                    font-weight: 900;
                    line-height: 1.2;
                    margin: 0 0 1.5rem 0;
                    letter-spacing: -0.02em;
                ">
                    أدر مركزك التعليمي بالكامل
                    <span style="
                        color: #34d399 !important;
                        display: block;
                        margin-top: 0.375rem;
                    ">
                        من منصة واحدة ذكية
                    </span>
                </h1>

                {{-- 3. Paragraph --}}
                <p style="
                    color: #cbd5e1 !important;
                    font-size: 1.125rem;
                    font-weight: 400;
                    line-height: 1.7;
                    margin: 0 0 2rem 0;
                    max-width: 540px;
                ">
                    نظام متكامل يجمع إدارة الطلاب، الحضور الذكي بـ <strong style="color: #ffffff;">QR Code</strong>، الفواتير والاشتراكات، والتقارير المالية، مع أتمتة كاملة لإشعارات <strong style="color: #34d399;">الواتساب</strong> لأولياء الأمور.
                </p>

                {{-- 4. CTA Buttons --}}
                <div style="
                    display: flex;
                    flex-wrap: wrap;
                    gap: 1rem;
                    align-items: center;
                    margin-bottom: 2rem;
                ">
                    <a href="{{ route('register') }}" style="
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        gap: 0.75rem;
                        padding: 1rem 2.25rem;
                        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
                        color: #ffffff !important;
                        font-weight: 800;
                        font-size: 1.05rem;
                        border-radius: 0.875rem;
                        text-decoration: none;
                        box-shadow: 0 10px 25px rgba(5, 150, 105, 0.35);
                        transition: transform 0.2s;
                    " onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                        <span>ابدأ التجربة المجانية</span>
                        <i class="fas fa-arrow-left" style="font-size: 0.875rem;"></i>
                    </a>

                    <a href="#features" style="
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        gap: 0.625rem;
                        padding: 1rem 1.75rem;
                        background: rgba(255, 255, 255, 0.08);
                        border: 1px solid rgba(255, 255, 255, 0.2);
                        color: #ffffff !important;
                        font-weight: 700;
                        font-size: 1rem;
                        border-radius: 0.875rem;
                        text-decoration: none;
                        transition: background 0.2s;
                    " onmouseover="this.style.background='rgba(255, 255, 255, 0.15)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.08)'">
                        <i class="fas fa-play-circle" style="color: #34d399; font-size: 1.125rem;"></i>
                        <span>شاهد عرضاً لمدة دقيقتين</span>
                    </a>
                </div>

                {{-- 5. Benefits Checklist --}}
                <div style="
                    display: flex;
                    flex-wrap: wrap;
                    gap: 1.25rem;
                    padding-top: 1.5rem;
                    border-top: 1px solid rgba(255, 255, 255, 0.12);
                    margin-bottom: 1.75rem;
                ">
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: #e2e8f0; font-size: 0.85rem; font-weight: 600;">
                        <i class="fas fa-check-circle" style="color: #34d399;"></i>
                        <span>إعداد خلال دقيقتين</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: #e2e8f0; font-size: 0.85rem; font-weight: 600;">
                        <i class="fas fa-check-circle" style="color: #34d399;"></i>
                        <span>بدون بطاقة بنكية</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: #e2e8f0; font-size: 0.85rem; font-weight: 600;">
                        <i class="fas fa-check-circle" style="color: #34d399;"></i>
                        <span>دعم عربي دائم</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: #e2e8f0; font-size: 0.85rem; font-weight: 600;">
                        <i class="fas fa-check-circle" style="color: #34d399;"></i>
                        <span>تجربة مجانية 14 يوم</span>
                    </div>
                </div>

                {{-- 6. Trust Metrics --}}
                <div style="
                    display: flex;
                    align-items: center;
                    gap: 2rem;
                ">
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 900; color: #ffffff;">+5,000</div>
                        <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;">طالب ومُتدرّب</div>
                    </div>
                    <div style="width: 1px; height: 2rem; background: rgba(255, 255, 255, 0.15);"></div>
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 900; color: #ffffff;">+120</div>
                        <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;">مركز تعليمي</div>
                    </div>
                    <div style="width: 1px; height: 2rem; background: rgba(255, 255, 255, 0.15);"></div>
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 900; color: #34d399;">99.9%</div>
                        <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;">استقرار وتشغيل</div>
                    </div>
                </div>

            </div>

            {{-- LEFT COLUMN: SaaS Visual Mockup Composition --}}
            <div style="
                flex: 1 1 480px;
                max-width: 560px;
                width: 100%;
                position: relative;
            ">
                {{-- Mac Browser Frame --}}
                <div style="
                    position: relative;
                    width: 100%;
                    border-radius: 1rem;
                    overflow: hidden;
                    border: 1px solid rgba(255, 255, 255, 0.15);
                    box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.6);
                    background: #0f172a;
                ">
                    {{-- Browser Header Bar --}}
                    <div style="
                        padding: 0.625rem 1rem;
                        background: #1e293b;
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
                    ">
                        <div style="display: flex; gap: 0.375rem;">
                            <span style="width: 0.625rem; height: 0.625rem; border-radius: 50%; background: #ef4444; display: inline-block;"></span>
                            <span style="width: 0.625rem; height: 0.625rem; border-radius: 50%; background: #f59e0b; display: inline-block;"></span>
                            <span style="width: 0.625rem; height: 0.625rem; border-radius: 50%; background: #22c55e; display: inline-block;"></span>
                        </div>
                        <div style="
                            padding: 0.2rem 0.75rem;
                            border-radius: 0.375rem;
                            background: #090d16;
                            border: 1px solid rgba(255, 255, 255, 0.1);
                            font-size: 0.7rem;
                            color: #94a3b8;
                            font-family: monospace;
                            display: flex;
                            align-items: center;
                            gap: 0.375rem;
                        ">
                            <i class="fas fa-lock" style="font-size: 0.55rem; color: #22c55e;"></i>
                            <span>app.taalimu.com</span>
                        </div>
                        <div style="width: 2rem;"></div>
                    </div>

                    {{-- Image Mockup --}}
                    <div style="position: relative; background: #0f172a; overflow: hidden;">
                        <img src="{{ asset('images/hero-dashboard.webp') }}" alt="Taalimu Dashboard" style="width: 100%; height: auto; display: block; opacity: 0.95;">
                    </div>
                </div>

                {{-- Floating Mini Cards (Cleanly Positioned, Small & Non-overlapping) --}}
                
                {{-- Card 1: WhatsApp Notification (Top Right in RTL) --}}
                <div style="
                    position: absolute;
                    top: -1.25rem;
                    right: -1rem;
                    background: rgba(15, 23, 42, 0.95);
                    border: 1px solid rgba(16, 185, 129, 0.35);
                    border-radius: 0.875rem;
                    padding: 0.75rem 1rem;
                    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
                    z-index: 20;
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                    width: 210px;
                    backdrop-filter: blur(8px);
                ">
                    <div style="
                        width: 2.25rem;
                        height: 2.25rem;
                        border-radius: 0.5rem;
                        background: rgba(16, 185, 129, 0.2);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        flex-shrink: 0;
                    ">
                        <i class="fab fa-whatsapp" style="color: #22c55e; font-size: 1.125rem;"></i>
                    </div>
                    <div style="overflow: hidden;">
                        <div style="font-size: 0.75rem; font-weight: 700; color: #ffffff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">إشعار أولياء الأمور</div>
                        <div style="font-size: 0.65rem; color: #34d399; font-weight: 600;">تم إرسال الفاتورة تلقائياً</div>
                    </div>
                </div>

                {{-- Card 2: QR Attendance (Bottom Left in RTL) --}}
                <div style="
                    position: absolute;
                    bottom: -1.25rem;
                    left: -1rem;
                    background: rgba(15, 23, 42, 0.95);
                    border: 1px solid rgba(59, 130, 246, 0.35);
                    border-radius: 0.875rem;
                    padding: 0.75rem 1rem;
                    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
                    z-index: 20;
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                    width: 200px;
                    backdrop-filter: blur(8px);
                ">
                    <div style="
                        width: 2.25rem;
                        height: 2.25rem;
                        border-radius: 0.5rem;
                        background: rgba(59, 130, 246, 0.2);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        flex-shrink: 0;
                    ">
                        <i class="fas fa-qrcode" style="color: #60a5fa; font-size: 1rem;"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; font-weight: 700; color: #ffffff;">حضور بـ QR Code</div>
                        <div style="font-size: 0.65rem; color: #94a3b8;">أحمد علي - 09:00 ص</div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>