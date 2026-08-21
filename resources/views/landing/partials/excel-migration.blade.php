{{-- Integrated Excel Migration & Core Center Features Section --}}
<section id="excel" class="section-alt" style="padding: 5.5rem 0; background: linear-gradient(180deg, #f8fafc 0%, #E6F4F3 100%); border-bottom: 1px solid #B2DDD9;">
    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1240px; margin: 0 auto;">
        
        {{-- Section Header --}}
        <div class="max-w-4xl mx-auto text-center mb-12" data-animate>
            <div class="section-badge" style="margin-bottom: 1.25rem; display: inline-flex; background: #ffffff; border: 1px solid #B2DDD9; padding: 0.375rem 1.25rem; border-radius: 9999px; box-shadow: 0 2px 4px rgba(46, 139, 131, 0.05);">
                <i class="fas fa-file-excel" style="color: #16a34a; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.375rem;"></i>
                <span style="color: #25746D !important; font-weight: 800; font-size: 0.825rem;">{{ __('landing.excel_migration.badge') }}</span>
            </div>
            
            <h2 style="color: #0f172a !important; font-size: clamp(1.875rem, 4vw, 3rem); font-weight: 900; margin-bottom: 0.75rem; letter-spacing: -0.025em; line-height: 1.2;">
                {{ __('landing.excel_migration.title') }}
                <span style="color: #2E8B83; display: block; margin-top: 0.25rem;">{{ __('landing.excel_migration.title_sub') }}</span>
            </h2>
            
            <p style="color: #475569 !important; font-size: 1.15rem; max-width: 38rem; margin: 0 auto; font-weight: 500; line-height: 1.7;">
                {{ __('landing.excel_migration.subtitle') }}
            </p>
        </div>

        {{-- 3-Step Simple Import Process --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; max-width: 1050px; margin: 0 auto 3rem auto;" data-stagger>
            <div style="background: #ffffff; border: 1px solid #B2DDD9; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 12px rgba(46, 139, 131, 0.06); text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};">
                <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; background: #E6F4F3; color: #2E8B83; font-weight: 900; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; margin-bottom: 0.875rem;">1</div>
                <h3 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin: 0 0 0.35rem 0;">{{ __('landing.excel_migration.step1') }}</h3>
                <p style="font-size: 0.85rem; color: #64748b; margin: 0; line-height: 1.5;">ارفع ملفك الحالي مع أرقام الطلاب وأولياء الأمور والمجموعات.</p>
            </div>

            <div style="background: #ffffff; border: 1px solid #B2DDD9; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 12px rgba(46, 139, 131, 0.06); text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};">
                <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; background: #E6F4F3; color: #2E8B83; font-weight: 900; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; margin-bottom: 0.875rem;">2</div>
                <h3 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin: 0 0 0.35rem 0;">{{ __('landing.excel_migration.step2') }}</h3>
                <p style="font-size: 0.85rem; color: #64748b; margin: 0; line-height: 1.5;">مطابقة تلقائية وسريعة لكافة الأعمدة والبيانات بدون تعقيد.</p>
            </div>

            <div style="background: #ffffff; border: 1px solid #B2DDD9; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 12px rgba(46, 139, 131, 0.06); text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};">
                <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; background: #E6F4F3; color: #2E8B83; font-weight: 900; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; margin-bottom: 0.875rem;">3</div>
                <h3 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin: 0 0 0.35rem 0;">{{ __('landing.excel_migration.step3') }}</h3>
                <p style="font-size: 0.85rem; color: #64748b; margin: 0; line-height: 1.5;">ابدأ فوراً بتسجيل الحضور، كشف المتأخرات، وإرسال تنبيهات الواتساب.</p>
            </div>
        </div>

        {{-- Core Platform Power Cards --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; max-width: 1050px; margin: 0 auto 2.5rem auto;">
            <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 0.875rem; padding: 1.25rem; display: flex; align-items: flex-start; gap: 0.75rem;">
                <div style="width: 2.25rem; height: 2.25rem; border-radius: 0.5rem; background: #E6F4F3; color: #2E8B83; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-qrcode"></i>
                </div>
                <div>
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem 0;">حضور فوري بـ QR</h4>
                    <p style="font-size: 0.8rem; color: #64748b; margin: 0; line-height: 1.4;">مسح سريع لكل طالب مع إشعار فوري لولي الأمر.</p>
                </div>
            </div>

            <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 0.875rem; padding: 1.25rem; display: flex; align-items: flex-start; gap: 0.75rem;">
                <div style="width: 2.25rem; height: 2.25rem; border-radius: 0.5rem; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-money-check-alt"></i>
                </div>
                <div>
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem 0;">كشف المتأخرات</h4>
                    <p style="font-size: 0.8rem; color: #64748b; margin: 0; line-height: 1.4;">معرفة فوريّة لمن دفع ومن لم يسدد اشتراكه.</p>
                </div>
            </div>

            <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 0.875rem; padding: 1.25rem; display: flex; align-items: flex-start; gap: 0.75rem;">
                <div style="width: 2.25rem; height: 2.25rem; border-radius: 0.5rem; background: #fef2f2; color: #dc2626; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div>
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem 0;">صلاحيات المساعدين</h4>
                    <p style="font-size: 0.8rem; color: #64748b; margin: 0; line-height: 1.4;">تحكم كامل بما يراه كل موظف وحماية المالية.</p>
                </div>
            </div>

            <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 0.875rem; padding: 1.25rem; display: flex; align-items: flex-start; gap: 0.75rem;">
                <div style="width: 2.25rem; height: 2.25rem; border-radius: 0.5rem; background: #f0fdf4; color: #16a34a; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-calculator"></i>
                </div>
                <div>
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem 0;">أرباح ونسب المدرسين</h4>
                    <p style="font-size: 0.8rem; color: #64748b; margin: 0; line-height: 1.4;">حساب تلقائي دقيق للمستحقات الشهرية بدون أخطاء.</p>
                </div>
            </div>
        </div>

        {{-- CTA Button --}}
        <div class="text-center">
            <a href="{{ route('register') }}" style="
                display: inline-flex;
                align-items: center;
                gap: 0.75rem;
                padding: 1.125rem 2.5rem;
                background: #2E8B83;
                color: #ffffff !important;
                font-weight: 800;
                font-size: 1.05rem;
                border-radius: 0.875rem;
                text-decoration: none;
                box-shadow: 0 10px 25px rgba(46, 139, 131, 0.3);
                transition: transform 0.2s;
            " onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                <span>{{ __('landing.excel_migration.cta') }}</span>
                <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i>
            </a>
        </div>

    </div>
</section>
