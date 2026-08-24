{{-- QR Registration Feature Section --}}
<section id="qr-registration" class="section-light" style="padding: 5rem 0; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1140px; margin: 0 auto;">
        
        {{-- Header --}}
        <div class="text-center mb-16" data-animate="fade-up">
            <div class="section-badge" style="margin-bottom: 1rem; display: inline-flex; background: #E6F4F3; border: 1px solid #B2DDD9; padding: 0.375rem 1rem; border-radius: 9999px;">
                <i class="fas fa-qrcode" style="color: #2E8B83; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.5rem;"></i>
                <span style="color: #25746D !important; font-weight: 800; font-size: 0.825rem;">{{ __('landing.qr_registration.badge') }}</span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.75rem, 3.5vw, 2.5rem); font-weight: 900; margin-bottom: 0.875rem; letter-spacing: -0.02em; line-height: 1.25;">
                {{ __('landing.qr_registration.title') }}
            </h2>
            <p style="color: #475569 !important; font-size: 1.05rem; max-width: 42rem; margin: 0 auto; font-weight: 500; line-height: 1.65;">
                {{ __('landing.qr_registration.subtitle') }}
            </p>
        </div>

        {{-- Visual Workflow --}}
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 2rem; max-width: 1000px; margin: 0 auto 3rem auto;" data-stagger>
            {{-- Step 1: Teacher Creates QR --}}
            <div style="flex: 1 1 200px; text-align: center;">
                <div class="workflow-step" style="
                    width: 4.5rem; height: 4.5rem; border-radius: 1.25rem; background: #E6F4F3; 
                    display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; 
                    color: #2E8B83; font-size: 1.5rem; box-shadow: 0 4px 16px rgba(46,139,131,0.15);
                ">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h3 style="color: #0f172a; font-size: 1rem; font-weight: 800; margin: 0 0 0.375rem 0;">{{ __('landing.qr_registration.step1_title') }}</h3>
                <p style="color: #64748b; font-size: 0.85rem; margin: 0;">{{ __('landing.qr_registration.step1_desc') }}</p>
            </div>
            <div class="workflow-arrow" style="color: #cbd5e1; font-size: 1.5rem; flex-shrink: 0;">
                <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></i>
            </div>
            
            {{-- Step 2: Student Scans --}}
            <div style="flex: 1 1 200px; text-align: center;">
                <div class="workflow-step" style="
                    width: 4.5rem; height: 4.5rem; border-radius: 1.25rem; background: #eff6ff; 
                    display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; 
                    color: #2563eb; font-size: 1.5rem; box-shadow: 0 4px 16px rgba(37,99,235,0.15);
                ">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3 style="color: #0f172a; font-size: 1rem; font-weight: 800; margin: 0 0 0.375rem 0;">{{ __('landing.qr_registration.step2_title') }}</h3>
                <p style="color: #64748b; font-size: 0.85rem; margin: 0;">{{ __('landing.qr_registration.step2_desc') }}</p>
            </div>
            <div class="workflow-arrow" style="color: #cbd5e1; font-size: 1.5rem; flex-shrink: 0;">
                <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></i>
            </div>
            
            {{-- Step 3: Registration Form --}}
            <div style="flex: 1 1 200px; text-align: center;">
                <div class="workflow-step" style="
                    width: 4.5rem; height: 4.5rem; border-radius: 1.25rem; background: #fffbeb; 
                    display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; 
                    color: #f59e0b; font-size: 1.5rem; box-shadow: 0 4px 16px rgba(245,158,11,0.15);
                ">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h3 style="color: #0f172a; font-size: 1rem; font-weight: 800; margin: 0 0 0.375rem 0;">{{ __('landing.qr_registration.step3_title') }}</h3>
                <p style="color: #64748b; font-size: 0.85rem; margin: 0;">{{ __('landing.qr_registration.step3_desc') }}</p>
            </div>
            <div class="workflow-arrow" style="color: #cbd5e1; font-size: 1.5rem; flex-shrink: 0;">
                <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></i>
            </div>
            
            {{-- Step 4: Student Profile --}}
            <div style="flex: 1 1 200px; text-align: center;">
                <div class="workflow-step" style="
                    width: 4.5rem; height: 4.5rem; border-radius: 1.25rem; background: #f0fdf4; 
                    display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; 
                    color: #16a34a; font-size: 1.5rem; box-shadow: 0 4px 16px rgba(22,163,74,0.15);
                ">
                    <i class="fas fa-id-card"></i>
                </div>
                <h3 style="color: #0f172a; font-size: 1rem; font-weight: 800; margin: 0 0 0.375rem 0;">{{ __('landing.qr_registration.step4_title') }}</h3>
                <p style="color: #64748b; font-size: 0.85rem; margin: 0;">{{ __('landing.qr_registration.step4_desc') }}</p>
            </div>
            <div class="workflow-arrow" style="color: #cbd5e1; font-size: 1.5rem; flex-shrink: 0;">
                <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></i>
            </div>
            
            {{-- Step 5: Dashboard --}}
            <div style="flex: 1 1 200px; text-align: center;">
                <div class="workflow-step" style="
                    width: 4.5rem; height: 4.5rem; border-radius: 1.25rem; background: #f3e8ff; 
                    display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; 
                    color: #9333ea; font-size: 1.5rem; box-shadow: 0 4px 16px rgba(147,51,234,0.15);
                ">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <h3 style="color: #0f172a; font-size: 1rem; font-weight: 800; margin: 0 0 0.375rem 0;">{{ __('landing.qr_registration.step5_title') }}</h3>
                <p style="color: #64748b; font-size: 0.85rem; margin: 0;">{{ __('landing.qr_registration.step5_desc') }}</p>
            </div>
        </div>

        {{-- Benefits Grid --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem;" data-stagger>
            @foreach(__('landing.qr_registration.benefits') as $benefit)
            <div style="
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 1rem;
                padding: 1.5rem;
                display: flex;
                align-items: flex-start;
                gap: 1rem;
                transition: all 0.2s ease;
            " onmouseover="this.style.borderColor='#2E8B83'; this.style.boxShadow='0 8px 24px rgba(46,139,131,0.08)'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                <div style="
                    width: 3rem; height: 3rem; border-radius: 0.75rem; 
                    background: {{ $benefit['color'] }}; 
                    display: flex; align-items: center; justify-content: center; 
                    flex-shrink: 0; color: #ffffff; font-size: 1.25rem;
                ">
                    <i class="fas {{ $benefit['icon'] }}"></i>
                </div>
                <div>
                    <h4 style="color: #0f172a; font-size: 0.95rem; font-weight: 800; margin: 0 0 0.375rem 0;">{{ $benefit['title'] }}</h4>
                    <p style="color: #64748b; font-size: 0.85rem; margin: 0; line-height: 1.55;">{{ $benefit['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- QR Visual Demo --}}
        <div class="text-center mt-12" data-animate="fade-up" data-delay="300">
            <div style="display: inline-block; position: relative;">
                <div style="
                    width: 200px; height: 200px; background: #ffffff; border: 2px solid #B2DDD9; 
                    border-radius: 1rem; display: flex; align-items: center; justify-content: center;
                    box-shadow: 0 12px 32px rgba(46,139,131,0.12);
                ">
                    <div id="hero-qr-code" style="width: 160px; height: 160px;"></div>
                </div>
                <p style="margin-top: 1rem; color: #64748b; font-size: 0.9rem; font-weight: 600;">{{ __('landing.qr_registration.scan_to_try') }}</p>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof QRCode !== 'undefined' && document.getElementById('hero-qr-code')) {
        new QRCode(document.getElementById('hero-qr-code'), {
            text: 'https://taalimu.com/register?source=qr_demo',
            width: 160,
            height: 160,
            colorDark: '#2E8B83',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    }
});
</script>