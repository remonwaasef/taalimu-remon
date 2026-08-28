{{-- Integrated Excel Migration & Core Center Features Section --}}
<section id="excel" class="section-alt" style="padding: 3.75rem 0; background: linear-gradient(180deg, #f8fafc 0%, #E6F4F3 100%); border-bottom: 1px solid #B2DDD9;">
    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1140px; margin: 0 auto;">
        
        {{-- Section Header --}}
        <div class="max-w-3xl mx-auto text-center mb-8" data-animate>
            <div class="section-badge" style="margin-bottom: 0.875rem; display: inline-flex; background: #ffffff; border: 1px solid #B2DDD9; padding: 0.3rem 0.85rem; border-radius: 9999px; box-shadow: 0 2px 4px rgba(46, 139, 131, 0.04);">
                <i class="fas fa-file-excel" style="color: #16a34a; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.35rem;"></i>
                <span style="color: #25746D !important; font-weight: 800; font-size: 0.775rem;">{{ __('landing.excel_migration.badge') }}</span>
            </div>
            
            <h2 style="color: #0f172a !important; font-size: clamp(1.5rem, 2.8vw, 2.15rem); font-weight: 900; margin-bottom: 0.5rem; letter-spacing: -0.02em; line-height: 1.25;">
                {{ __('landing.excel_migration.title') }}
                <span style="color: #2E8B83; display: inline-block;">{{ __('landing.excel_migration.title_sub') }}</span>
            </h2>
            
            <p style="color: #475569 !important; font-size: 0.975rem; max-width: 36rem; margin: 0 auto; font-weight: 500; line-height: 1.6;">
                {{ __('landing.excel_migration.subtitle') }}
            </p>
        </div>

        {{-- 3-Step Simple Import Process --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1rem; max-width: 950px; margin: 0 auto 2rem auto;" data-stagger>
            <div style="background: #ffffff; border: 1px solid #B2DDD9; border-radius: 0.875rem; padding: 1.125rem 1.25rem; box-shadow: 0 2px 8px rgba(46, 139, 131, 0.04); text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};">
                <div style="width: 2rem; height: 2rem; border-radius: 0.4rem; background: #E6F4F3; color: #2E8B83; font-weight: 900; font-size: 0.95rem; display: flex; align-items: center; justify-content: center; margin-bottom: 0.65rem;">1</div>
                <h3 style="font-size: 0.925rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem 0;">{{ __('landing.excel_migration.step1') }}</h3>
                <p style="font-size: 0.8rem; color: #64748b; margin: 0; line-height: 1.45;">{{ __('landing.excel_migration.step1_desc') }}</p>
            </div>

            <div style="background: #ffffff; border: 1px solid #B2DDD9; border-radius: 0.875rem; padding: 1.125rem 1.25rem; box-shadow: 0 2px 8px rgba(46, 139, 131, 0.04); text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};">
                <div style="width: 2rem; height: 2rem; border-radius: 0.4rem; background: #E6F4F3; color: #2E8B83; font-weight: 900; font-size: 0.95rem; display: flex; align-items: center; justify-content: center; margin-bottom: 0.65rem;">2</div>
                <h3 style="font-size: 0.925rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem 0;">{{ __('landing.excel_migration.step2') }}</h3>
                <p style="font-size: 0.8rem; color: #64748b; margin: 0; line-height: 1.45;">{{ __('landing.excel_migration.step2_desc') }}</p>
            </div>

            <div style="background: #ffffff; border: 1px solid #B2DDD9; border-radius: 0.875rem; padding: 1.125rem 1.25rem; box-shadow: 0 2px 8px rgba(46, 139, 131, 0.04); text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};">
                <div style="width: 2rem; height: 2rem; border-radius: 0.4rem; background: #E6F4F3; color: #2E8B83; font-weight: 900; font-size: 0.95rem; display: flex; align-items: center; justify-content: center; margin-bottom: 0.65rem;">3</div>
                <h3 style="font-size: 0.925rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem 0;">{{ __('landing.excel_migration.step3') }}</h3>
                <p style="font-size: 0.8rem; color: #64748b; margin: 0; line-height: 1.45;">{{ __('landing.excel_migration.step3_desc') }}</p>
            </div>
        </div>

        {{-- Core Platform Power Cards --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; max-width: 950px; margin: 0 auto 2rem auto;">
            <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 0.75rem; padding: 1rem; display: flex; align-items: flex-start; gap: 0.625rem;">
                <div style="width: 2rem; height: 2rem; border-radius: 0.4rem; background: #E6F4F3; color: #2E8B83; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.9rem;">
                    <i class="fas fa-qrcode"></i>
                </div>
                <div>
                    <h4 style="font-size: 0.875rem; font-weight: 800; color: #0f172a; margin: 0 0 0.15rem 0;">{{ __('landing.excel_migration.power_qr_title') }}</h4>
                    <p style="font-size: 0.775rem; color: #64748b; margin: 0; line-height: 1.35;">{{ __('landing.excel_migration.power_qr_desc') }}</p>
                </div>
            </div>

            <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 0.75rem; padding: 1rem; display: flex; align-items: flex-start; gap: 0.625rem;">
                <div style="width: 2rem; height: 2rem; border-radius: 0.4rem; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.9rem;">
                    <i class="fas fa-money-check-alt"></i>
                </div>
                <div>
                    <h4 style="font-size: 0.875rem; font-weight: 800; color: #0f172a; margin: 0 0 0.15rem 0;">{{ __('landing.excel_migration.power_debts_title') }}</h4>
                    <p style="font-size: 0.775rem; color: #64748b; margin: 0; line-height: 1.35;">{{ __('landing.excel_migration.power_debts_desc') }}</p>
                </div>
            </div>

            <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 0.75rem; padding: 1rem; display: flex; align-items: flex-start; gap: 0.625rem;">
                <div style="width: 2rem; height: 2rem; border-radius: 0.4rem; background: #fef2f2; color: #dc2626; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.9rem;">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div>
                    <h4 style="font-size: 0.875rem; font-weight: 800; color: #0f172a; margin: 0 0 0.15rem 0;">{{ __('landing.excel_migration.power_roles_title') }}</h4>
                    <p style="font-size: 0.775rem; color: #64748b; margin: 0; line-height: 1.35;">{{ __('landing.excel_migration.power_roles_desc') }}</p>
                </div>
            </div>

            <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 0.75rem; padding: 1rem; display: flex; align-items: flex-start; gap: 0.625rem;">
                <div style="width: 2rem; height: 2rem; border-radius: 0.4rem; background: #f0fdf4; color: #16a34a; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.9rem;">
                    <i class="fas fa-calculator"></i>
                </div>
                <div>
                    <h4 style="font-size: 0.875rem; font-weight: 800; color: #0f172a; margin: 0 0 0.15rem 0;">{{ __('landing.excel_migration.power_payouts_title') }}</h4>
                    <p style="font-size: 0.775rem; color: #64748b; margin: 0; line-height: 1.35;">{{ __('landing.excel_migration.power_payouts_desc') }}</p>
                </div>
            </div>
        </div>

        {{-- CTA Buttons --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 text-center">
            <a href="{{ route('register') }}" data-track="landing_migration_cta_clicked" style="
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.85rem 1.85rem;
                background: #2E8B83;
                color: #ffffff !important;
                font-weight: 800;
                font-size: 0.95rem;
                border-radius: 0.75rem;
                text-decoration: none;
                box-shadow: 0 8px 20px rgba(46, 139, 131, 0.25);
                transition: transform 0.2s;
            " onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                <span>{{ __('landing.excel_migration.cta') }}</span>
                <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i>
            </a>

            <a href="{{ asset('templates/students_import_template.xlsx') }}" download class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border border-slate-300 bg-white text-slate-700 font-bold text-xs hover:bg-slate-50 transition-colors text-decoration-none shadow-sm">
                <i class="fas fa-file-download text-emerald-600"></i>
                <span>{{ __('landing.excel_template_btn') }}</span>
            </a>
        </div>

    </div>
</section>
