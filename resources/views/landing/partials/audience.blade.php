{{-- Audience Section --}}
<section id="solutions" class="section-alt" style="padding: 5rem 0; background: #ffffff; border-bottom: 1px solid #e2e8f0;">
    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1140px; margin: 0 auto;">
        
        {{-- Header --}}
        <div class="text-center mb-16" data-animate="fade-up">
            <div class="section-badge" style="margin-bottom: 1rem; display: inline-flex; background: #E6F4F3; border: 1px solid #B2DDD9; padding: 0.375rem 1rem; border-radius: 9999px;">
                <span style="color: #25746D !important; font-weight: 800; font-size: 0.825rem;">{{ __('landing.audience.badge') }}</span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.75rem, 3.5vw, 2.5rem); font-weight: 900; margin-bottom: 0.875rem; letter-spacing: -0.02em; line-height: 1.25;">
                {{ __('landing.audience.title') }}
            </h2>
            <p style="color: #475569 !important; font-size: 1.05rem; max-width: 42rem; margin: 0 auto; font-weight: 500; line-height: 1.65;">
                {{ __('landing.audience.subtitle') }}
            </p>
        </div>

        {{-- Four Audience Cards --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;" data-stagger>
            
            {{-- Teacher --}}
            <div style="
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 1.25rem;
                padding: 2rem 1.75rem;
                position: relative;
                overflow: hidden;
                transition: all 0.3s ease;
            " onmouseover="this.style.borderColor='#2E8B83'; this.style.boxShadow='0 20px 40px rgba(46,139,131,0.12)'; this.style.transform='translateY(-4px)'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'; this.style.transform='translateY(0)'">
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #2E8B83, #16a34a);"></div>
                <div style="width: 4rem; height: 4rem; border-radius: 1rem; background: #E6F4F3; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; color: #2E8B83; font-size: 1.75rem;">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h3 style="color: #0f172a; font-size: 1.25rem; font-weight: 800; margin: 0 0 0.5rem 0;">{{ __('landing.audience.teacher.title') }}</h3>
                <p style="color: #64748b; font-size: 0.9rem; margin: 0 0 1.5rem 0; line-height: 1.6;">{{ __('landing.audience.teacher.desc') }}</p>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.5rem;">
                    @foreach(__('landing.audience.teacher.items') as $item)
                    <li style="display: flex; align-items: center; gap: 0.625rem; color: #334155; font-size: 0.85rem; font-weight: 600;">
                        <i class="fas fa-check" style="color: #2E8B83; font-size: 0.7rem;"></i>
                        <span>{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Educational Center --}}
            <div style="
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 1.25rem;
                padding: 2rem 1.75rem;
                position: relative;
                overflow: hidden;
                transition: all 0.3s ease;
            " onmouseover="this.style.borderColor='#2E8B83'; this.style.boxShadow='0 20px 40px rgba(46,139,131,0.12)'; this.style.transform='translateY(-4px)'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'; this.style.transform='translateY(0)'">
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #2563eb, #0ea5e9);"></div>
                <div style="width: 4rem; height: 4rem; border-radius: 1rem; background: #eff6ff; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; color: #2563eb; font-size: 1.75rem;">
                    <i class="fas fa-building"></i>
                </div>
                <h3 style="color: #0f172a; font-size: 1.25rem; font-weight: 800; margin: 0 0 0.5rem 0;">{{ __('landing.audience.center.title') }}</h3>
                <p style="color: #64748b; font-size: 0.9rem; margin: 0 0 1.5rem 0; line-height: 1.6;">{{ __('landing.audience.center.desc') }}</p>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.5rem;">
                    @foreach(__('landing.audience.center.items') as $item)
                    <li style="display: flex; align-items: center; gap: 0.625rem; color: #334155; font-size: 0.85rem; font-weight: 600;">
                        <i class="fas fa-check" style="color: #2563eb; font-size: 0.7rem;"></i>
                        <span>{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Student --}}
            <div style="
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 1.25rem;
                padding: 2rem 1.75rem;
                position: relative;
                overflow: hidden;
                transition: all 0.3s ease;
            " onmouseover="this.style.borderColor='#2E8B83'; this.style.boxShadow='0 20px 40px rgba(46,139,131,0.12)'; this.style.transform='translateY(-4px)'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'; this.style.transform='translateY(0)'">
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #9333ea, #d946ef);"></div>
                <div style="width: 4rem; height: 4rem; border-radius: 1rem; background: #f3e8ff; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; color: #9333ea; font-size: 1.75rem;">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h3 style="color: #0f172a; font-size: 1.25rem; font-weight: 800; margin: 0 0 0.5rem 0;">{{ __('landing.audience.student.title') }}</h3>
                <p style="color: #64748b; font-size: 0.9rem; margin: 0 0 1.5rem 0; line-height: 1.6;">{{ __('landing.audience.student.desc') }}</p>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.5rem;">
                    @foreach(__('landing.audience.student.items') as $item)
                    <li style="display: flex; align-items: center; gap: 0.625rem; color: #334155; font-size: 0.85rem; font-weight: 600;">
                        <i class="fas fa-check" style="color: #9333ea; font-size: 0.7rem;"></i>
                        <span>{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Parent --}}
            <div style="
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 1.25rem;
                padding: 2rem 1.75rem;
                position: relative;
                overflow: hidden;
                transition: all 0.3s ease;
            " onmouseover="this.style.borderColor='#2E8B83'; this.style.boxShadow='0 20px 40px rgba(46,139,131,0.12)'; this.style.transform='translateY(-4px)'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'; this.style.transform='translateY(0)'">
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #f59e0b, #f97316);"></div>
                <div style="width: 4rem; height: 4rem; border-radius: 1rem; background: #fffbeb; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; color: #f59e0b; font-size: 1.75rem;">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h3 style="color: #0f172a; font-size: 1.25rem; font-weight: 800; margin: 0 0 0.5rem 0;">{{ __('landing.audience.parent.title') }}</h3>
                <p style="color: #64748b; font-size: 0.9rem; margin: 0 0 1.5rem 0; line-height: 1.6;">{{ __('landing.audience.parent.desc') }}</p>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.5rem;">
                    @foreach(__('landing.audience.parent.items') as $item)
                    <li style="display: flex; align-items: center; gap: 0.625rem; color: #334155; font-size: 0.85rem; font-weight: 600;">
                        <i class="fas fa-check" style="color: #f59e0b; font-size: 0.7rem;"></i>
                        <span>{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>