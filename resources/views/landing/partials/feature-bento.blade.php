{{-- Feature Bento Grid Section --}}
<section id="features" class="section-light" style="padding: 5rem 0; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1140px; margin: 0 auto;">
        
        {{-- Header --}}
        <div class="text-center mb-12" data-animate="fade-up">
            <div class="section-badge" style="margin-bottom: 1rem; display: inline-flex; background: #E6F4F3; border: 1px solid #B2DDD9; padding: 0.375rem 1rem; border-radius: 9999px;">
                <span style="color: #25746D !important; font-weight: 800; font-size: 0.825rem;">{{ __('landing.bento.badge') }}</span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.75rem, 3.5vw, 2.5rem); font-weight: 900; margin-bottom: 0.875rem; letter-spacing: -0.02em; line-height: 1.25;">
                {{ __('landing.bento.title') }}
            </h2>
            <p style="color: #475569 !important; font-size: 1.05rem; max-width: 42rem; margin: 0 auto; font-weight: 500; line-height: 1.65;">
                {{ __('landing.bento.subtitle') }}
            </p>
        </div>

        {{-- Bento Grid --}}
        <div style="
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            grid-auto-rows: minmax(180px, auto);
            gap: 1.25rem;
        " data-stagger>
            
            {{-- Large: QR Registration --}}
            <div class="bento-card bento-large" style="
                grid-column: span 6;
                grid-row: span 2;
                background: linear-gradient(135deg, #E6F4F3 0%, #f0fdf4 100%);
                border: 1px solid #B2DDD9;
                border-radius: 1.25rem;
                padding: 2rem;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                position: relative;
                overflow: hidden;
            " data-animate="fade-up">
                <div style="position: absolute; top: -2rem; right: -2rem; width: 8rem; height: 8rem; background: radial-gradient(circle, rgba(46,139,131,0.1) 0%, transparent 70%); pointer-events: none;"></div>
                <div style="position: relative; z-index: 1;">
                    <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.375rem 0.875rem; background: #ffffff; border: 1px solid #B2DDD9; border-radius: 9999px; margin-bottom: 1rem; width: fit-content;">
                        <i class="fas fa-qrcode" style="color: #2E8B83;"></i>
                        <span style="color: #25746D; font-weight: 800; font-size: 0.75rem;">{{ __('landing.bento.featured') }}</span>
                    </div>
                    <h3 style="color: #0f172a; font-size: 1.5rem; font-weight: 900; margin: 0 0 0.75rem 0; line-height: 1.3;">{{ __('landing.bento.qr.title') }}</h3>
                    <p style="color: #475569; font-size: 0.95rem; margin: 0 0 1.5rem 0; line-height: 1.6;">{{ __('landing.bento.qr.desc') }}</p>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        @foreach(__('landing.bento.qr.highlights') as $h)
                        <span style="font-size: 0.75rem; font-weight: 700; color: #2E8B83; background: #ffffff; padding: 0.375rem 0.75rem; border-radius: 9999px; border: 1px solid #B2DDD9;">{{ $h }}</span>
                        @endforeach
                    </div>
                </div>
                <div style="position: relative; z-index: 1; text-align: center; margin-top: 1.5rem;">
                    <div id="bento-qr-code" style="width: 100px; height: 100px; margin: 0 auto;"></div>
                    <p style="margin-top: 0.5rem; color: #64748b; font-size: 0.75rem; font-weight: 600;">{{ __('landing.bento.qr.scan_label') }}</p>
                </div>
            </div>

            {{-- Large: WhatsApp Notifications --}}
            <div class="bento-card bento-large" style="
                grid-column: span 6;
                grid-row: span 2;
                background: linear-gradient(135deg, #dcfce7 0%, #f0fdf4 100%);
                border: 1px solid #bbf7d0;
                border-radius: 1.25rem;
                padding: 2rem;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                position: relative;
                overflow: hidden;
            " data-animate="fade-up" data-delay="100">
                <div style="position: absolute; top: -2rem; left: -2rem; width: 8rem; height: 8rem; background: radial-gradient(circle, rgba(34,197,94,0.1) 0%, transparent 70%); pointer-events: none;"></div>
                <div style="position: relative; z-index: 1;">
                    <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.375rem 0.875rem; background: #ffffff; border: 1px solid #bbf7d0; border-radius: 9999px; margin-bottom: 1rem; width: fit-content;">
                        <i class="fab fa-whatsapp" style="color: #16a34a;"></i>
                        <span style="color: #15803d; font-weight: 800; font-size: 0.75rem;">{{ __('landing.bento.featured') }}</span>
                    </div>
                    <h3 style="color: #0f172a; font-size: 1.5rem; font-weight: 900; margin: 0 0 0.75rem 0; line-height: 1.3;">{{ __('landing.bento.whatsapp.title') }}</h3>
                    <p style="color: #475569; font-size: 0.95rem; margin: 0 0 1.5rem 0; line-height: 1.6;">{{ __('landing.bento.whatsapp.desc') }}</p>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        @foreach(__('landing.bento.whatsapp.highlights') as $h)
                        <span style="font-size: 0.75rem; font-weight: 700; color: #16a34a; background: #ffffff; padding: 0.375rem 0.75rem; border-radius: 9999px; border: 1px solid #bbf7d0;">{{ $h }}</span>
                        @endforeach
                    </div>
                </div>
                <div style="position: relative; z-index: 1; margin-top: 1.5rem;">
                    <div style="background: #ffffff; border: 1px solid #bbf7d0; border-radius: 1rem; padding: 1rem; min-height: 120px;">
                        <div style="display: flex; gap: 0.75rem; margin-bottom: 0.75rem;">
                            <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.625rem; background: #dcfce7; display: flex; align-items: center; justify-content: center; color: #16a34a; font-size: 1.25rem; flex-shrink: 0;"><i class="fas fa-user-check"></i></div>
                            <div style="flex: 1;">
                                <div style="font-weight: 800; color: #0f172a; font-size: 0.825rem;">{{ __('landing.bento.whatsapp.demo_title') }}</div>
                                <div style="font-size: 0.7rem; color: #64748b;">{{ __('landing.bento.whatsapp.demo_sub') }}</div>
                            </div>
                        </div>
                        <div style="background: #f0fdf4; border-radius: 0.75rem; padding: 0.75rem; font-size: 0.75rem; color: #15803d; line-height: 1.5;">{{ __('landing.bento.whatsapp.demo_msg') }}</div>
                    </div>
                </div>
            </div>

            {{-- Large: Student Management --}}
            <div class="bento-card bento-large" style="
                grid-column: span 6;
                grid-row: span 2;
                background: linear-gradient(135deg, #fefce8 0%, #fffbeb 100%);
                border: 1px solid #fde68a;
                border-radius: 1.25rem;
                padding: 2rem;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                position: relative;
                overflow: hidden;
            " data-animate="fade-up" data-delay="200">
                <div style="position: absolute; top: -2rem; right: -2rem; width: 8rem; height: 8rem; background: radial-gradient(circle, rgba(245,158,11,0.1) 0%, transparent 70%); pointer-events: none;"></div>
                <div style="position: relative; z-index: 1;">
                    <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.375rem 0.875rem; background: #ffffff; border: 1px solid #fde68a; border-radius: 9999px; margin-bottom: 1rem; width: fit-content;">
                        <i class="fas fa-users" style="color: #f59e0b;"></i>
                        <span style="color: #b45309; font-weight: 800; font-size: 0.75rem;">{{ __('landing.bento.core') }}</span>
                    </div>
                    <h3 style="color: #0f172a; font-size: 1.5rem; font-weight: 900; margin: 0 0 0.75rem 0; line-height: 1.3;">{{ __('landing.bento.student_mgmt.title') }}</h3>
                    <p style="color: #475569; font-size: 0.95rem; margin: 0 0 1.5rem 0; line-height: 1.6;">{{ __('landing.bento.student_mgmt.desc') }}</p>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        @foreach(__('landing.bento.student_mgmt.highlights') as $h)
                        <span style="font-size: 0.75rem; font-weight: 700; color: #f59e0b; background: #ffffff; padding: 0.375rem 0.75rem; border-radius: 9999px; border: 1px solid #fde68a;">{{ $h }}</span>
                        @endforeach
                    </div>
                </div>
                <div style="position: relative; z-index: 1; margin-top: 1.5rem;">
                    <div style="background: #ffffff; border: 1px solid #fde68a; border-radius: 1rem; padding: 1rem;">
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; text-align: center;">
                            @foreach(__('landing.bento.student_mgmt.stats') as $stat)
                            <div>
                                <div style="font-size: 1.5rem; font-weight: 900; color: #f59e0b;">{{ $stat['value'] }}</div>
                                <div style="font-size: 0.7rem; color: #64748b; font-weight: 600;">{{ $stat['label'] }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Medium: Attendance --}}
            <div class="bento-card" style="
                grid-column: span 6;
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 1rem;
                padding: 1.5rem;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            " data-animate="fade-up" data-delay="300">
                <div>
                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; font-size: 1.25rem;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h4 style="color: #0f172a; font-size: 1.1rem; font-weight: 800; margin: 0 0 0.5rem 0;">{{ __('landing.bento.attendance.title') }}</h4>
                    <p style="color: #64748b; font-size: 0.875rem; margin: 0; line-height: 1.5;">{{ __('landing.bento.attendance.desc') }}</p>
                </div>
                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #f1f5f9; display: flex; gap: 1rem; font-size: 0.75rem; color: #64748b;">
                    <span><i class="fas fa-check-circle" style="color: #22c55e; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.25rem;"></i>{{ __('landing.bento.attendance.feature1') }}</span>
                    <span><i class="fas fa-qrcode" style="color: #2E8B83; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.25rem;"></i>{{ __('landing.bento.attendance.feature2') }}</span>
                </div>
            </div>

            {{-- Medium: Payments --}}
            <div class="bento-card" style="
                grid-column: span 6;
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 1rem;
                padding: 1.5rem;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            " data-animate="fade-up" data-delay="400">
                <div>
                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: #fefce8; color: #f59e0b; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; font-size: 1.25rem;">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h4 style="color: #0f172a; font-size: 1.1rem; font-weight: 800; margin: 0 0 0.5rem 0;">{{ __('landing.bento.payments.title') }}</h4>
                    <p style="color: #64748b; font-size: 0.875rem; margin: 0; line-height: 1.5;">{{ __('landing.bento.payments.desc') }}</p>
                </div>
                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #f1f5f9; display: flex; gap: 1rem; font-size: 0.75rem; color: #64748b;">
                    <span><i class="fas fa-receipt" style="color: #2E8B83; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.25rem;"></i>{{ __('landing.bento.payments.feature1') }}</span>
                    <span><i class="fas fa-chart-line" style="color: #16a34a; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.25rem;"></i>{{ __('landing.bento.payments.feature2') }}</span>
                </div>
            </div>

            {{-- Medium: Reports --}}
            <div class="bento-card" style="
                grid-column: span 6;
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 1rem;
                padding: 1.5rem;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            " data-animate="fade-up" data-delay="500">
                <div>
                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: #e0e7ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; font-size: 1.25rem;">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h4 style="color: #0f172a; font-size: 1.1rem; font-weight: 800; margin: 0 0 0.5rem 0;">{{ __('landing.bento.reports.title') }}</h4>
                    <p style="color: #64748b; font-size: 0.875rem; margin: 0; line-height: 1.5;">{{ __('landing.bento.reports.desc') }}</p>
                </div>
                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #f1f5f9; display: flex; gap: 1rem; font-size: 0.75rem; color: #64748b;">
                    <span><i class="fas fa-chart-bar" style="color: #4f46e5; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.25rem;"></i>{{ __('landing.bento.reports.feature1') }}</span>
                    <span><i class="fas fa-download" style="color: #2E8B83; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.25rem;"></i>{{ __('landing.bento.reports.feature2') }}</span>
                </div>
            </div>

            {{-- Medium: Performance --}}
            <div class="bento-card" style="
                grid-column: span 6;
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 1rem;
                padding: 1.5rem;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            " data-animate="fade-up" data-delay="600">
                <div>
                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: #fce7f3; color: #ec4899; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; font-size: 1.25rem;">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h4 style="color: #0f172a; font-size: 1.1rem; font-weight: 800; margin: 0 0 0.5rem 0;">{{ __('landing.bento.performance.title') }}</h4>
                    <p style="color: #64748b; font-size: 0.875rem; margin: 0; line-height: 1.5;">{{ __('landing.bento.performance.desc') }}</p>
                </div>
                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #f1f5f9; display: flex; gap: 1rem; font-size: 0.75rem; color: #64748b;">
                    <span><i class="fas fa-tachometer-alt" style="color: #ec4899; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.25rem;"></i>{{ __('landing.bento.performance.feature1') }}</span>
                    <span><i class="fas fa-shield-alt" style="color: #2E8B83; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.25rem;"></i>{{ __('landing.bento.performance.feature2') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof QRCode !== 'undefined' && document.getElementById('bento-qr-code')) {
        new QRCode(document.getElementById('bento-qr-code'), {
            text: 'https://taalimu.com/register?source=bento_qr',
            width: 100,
            height: 100,
            colorDark: '#2E8B83',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    }
});
</script>