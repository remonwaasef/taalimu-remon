{{-- WhatsApp Notifications Feature Section --}}
<section id="whatsapp-notifications" class="section-alt" style="padding: 5rem 0; background: #ffffff; border-bottom: 1px solid #e2e8f0;"
         x-data="{ activeCase: 'attendance' }">
    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1140px; margin: 0 auto;">
        
        {{-- Header --}}
        <div class="text-center mb-12" data-animate="fade-up">
            <div class="section-badge" style="margin-bottom: 1rem; display: inline-flex; background: #dcfce7; border: 1px solid #bbf7d0; padding: 0.375rem 1rem; border-radius: 9999px;">
                <i class="fab fa-whatsapp" style="color: #16a34a; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.5rem;"></i>
                <span style="color: #15803d !important; font-weight: 800; font-size: 0.825rem;">{{ __('landing.whatsapp.badge') }}</span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.75rem, 3.5vw, 2.5rem); font-weight: 900; margin-bottom: 0.875rem; letter-spacing: -0.02em; line-height: 1.25;">
                {{ __('landing.whatsapp.title') }}
            </h2>
            <p style="color: #475569 !important; font-size: 1.05rem; max-width: 42rem; margin: 0 auto; font-weight: 500; line-height: 1.65;">
                {{ __('landing.whatsapp.subtitle') }}
            </p>
        </div>

        {{-- Interactive Layout --}}
        <div style="display: flex; flex-wrap: wrap; align-items: flex-start; justify-content: space-between; gap: 3rem; max-width: 1000px; margin: 0 auto;" data-stagger>
            
            {{-- Left: Case Selectors --}}
            <div style="flex: 1 1 400px; min-width: 320px;">
                @foreach(__('landing.whatsapp.cases') as $key => $case)
                <button 
                    @click="activeCase = '{{ $key }}'"
                    type="button"
                    style="
                        width: 100%;
                        padding: 1rem 1.25rem;
                        border-radius: 1rem;
                        margin-bottom: 0.75rem;
                        transition: all 0.2s ease;
                        display: flex;
                        align-items: center;
                        gap: 1rem;
                        border: 1px solid;
                        cursor: pointer;
                        text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
                    "
                    :style="activeCase === '{{ $key }}' ? 'background: #f0fdf4; border-color: #2E8B83; box-shadow: 0 4px 16px rgba(46,139,131,0.1);' : 'background: #ffffff; border-color: #e2e8f0;'"
                >
                    <div style="
                        width: 3rem; height: 3rem; border-radius: 0.75rem; 
                        display: flex; align-items: center; justify-content: center; 
                        font-size: 1.25rem; flex-shrink: 0; transition: all 0.2s;
                    "
                    :style="activeCase === '{{ $key }}' ? 'background: #2E8B83; color: #ffffff;' : 'background: #f1f5f9; color: #64748b;'">
                        @if($key === 'attendance') <i class="fas fa-user-check"></i>
                        @elseif($key === 'absence') <i class="fas fa-user-times"></i>
                        @elseif($key === 'payment_due') <i class="fas fa-calendar-alt"></i>
                        @elseif($key === 'payment_overdue') <i class="fas fa-exclamation-triangle"></i>
                        @elseif($key === 'exam_result') <i class="fas fa-award"></i>
                        @else <i class="fas fa-bullhorn"></i>
                        @endif
                    </div>
                    
                    <div style="flex: 1; overflow: hidden;">
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; margin-bottom: 0.25rem;">
                            <span style="font-size: 0.95rem; font-weight: 800; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $case['title'] }}</span>
                            <span style="font-size: 0.7rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 9999px; background: #e2e8f0; color: #475569; flex-shrink: 0;"
                                  :style="activeCase === '{{ $key }}' ? 'background: #dcfce7; color: #15803d;' : ''">
                                {{ $case['tag'] }}
                            </span>
                        </div>
                        <p style="font-size: 0.825rem; color: #64748b; margin: 0; line-height: 1.4;">{{ $case['desc'] }}</p>
                    </div>
                </button>
                @endforeach
            </div>

            {{-- Right: WhatsApp Phone Mockup --}}
            <div style="flex: 1 1 380px; min-width: 320px; display: flex; justify-content: center;">
                <div style="width: 100%; max-width: 360px;">
                    {{-- Phone Frame --}}
                    <div style="
                        background: #ffffff;
                        border-radius: 2rem;
                        border: 8px solid #0f172a;
                        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
                        overflow: hidden;
                        position: relative;
                    ">
                        {{-- Notch --}}
                        <div style="background: #0f172a; height: 1.5rem; display: flex; align-items: center; justify-content: center;">
                            <div style="width: 4rem; height: 0.375rem; border-radius: 9999px; background: #334155;"></div>
                        </div>

                        {{-- WhatsApp Header --}}
                        <div style="background: #075e54; padding: 0.75rem 1rem; display: flex; align-items: center; justify-content: space-between; color: #ffffff;">
                            <div style="display: flex; align-items: center; gap: 0.625rem;">
                                <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}" style="font-size: 0.875rem;"></i>
                                <div style="width: 2.5rem; height: 2.5rem; border-radius: 50%; background: #25d366; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem; color: #ffffff;">
                                    T
                                </div>
                                <div>
                                    <div style="font-weight: 700; font-size: 0.875rem; line-height: 1.2;">Taalimu Education Center</div>
                                    <div style="font-size: 0.675rem; color: #a7f3d0;">Verified Business</div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 0.875rem; font-size: 0.875rem;">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                        </div>

                        {{-- Chat Area --}}
                        <div style="
                            background: #efeae2;
                            background-image: radial-gradient(#d1d7db 1px, transparent 1px);
                            background-size: 16px 16px;
                            padding: 1.25rem 1rem;
                            min-height: 340px;
                            display: flex;
                            flex-direction: column;
                            justify-content: flex-end;
                            gap: 1rem;
                        ">
                            {{-- Encryption Notice --}}
                            <div style="text-align: center; margin-bottom: 0.5rem;">
                                <span style="background: rgba(255,255,255,0.9); font-size: 0.65rem; color: #54656f; padding: 0.25rem 0.75rem; border-radius: 0.5rem; display: inline-block;">
                                    <i class="fas fa-lock" style="font-size: 0.55rem;"></i>
                                    الرسائل مشفرة تماماً
                                </span>
                            </div>

                            {{-- Dynamic Message Bubble --}}
                            @foreach(__('landing.whatsapp.cases') as $key => $case)
                            <div 
                                x-show="activeCase === '{{ $key }}'"
                                x-transition:enter="transition ease-out duration-250"
                                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                style="
                                    background: #ffffff;
                                    border-radius: 1rem;
                                    border-top-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}-radius: 0;
                                    padding: 1rem 1.125rem;
                                    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
                                    max-width: 85%;
                                    align-self: flex-start;
                                    position: relative;
                                "
                            >
                                <div style="font-weight: 800; font-size: 0.75rem; color: #128c7e; margin-bottom: 0.375rem; display: flex; align-items: center; gap: 0.375rem;">
                                    <i class="fas fa-bell"></i>
                                    <span>{{ $case['title'] }}</span>
                                </div>
                                <p style="font-size: 0.875rem; color: #111b21; margin: 0 0 0.5rem 0; line-height: 1.5;">{{ $case['msg'] }}</p>
                                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.25rem; font-size: 0.65rem; color: #667781;">
                                    <span>{{ now()->format('h:i A') }}</span>
                                    <i class="fas fa-check-double" style="color: #53bdeb;"></i>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Input Bar --}}
                        <div style="background: #f0f2f5; padding: 0.5rem 0.875rem; display: flex; align-items: center; gap: 0.5rem; border-top: 1px solid #d1d7db;">
                            <div style="flex: 1; background: #ffffff; border-radius: 1.5rem; padding: 0.375rem 1rem; font-size: 0.725rem; color: #8696a0;">
                                رسالة تلقائية من النظام...
                            </div>
                            <div style="width: 2.25rem; height: 2.25rem; border-radius: 50%; background: #00a884; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 0.75rem;">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Key Features Row --}}
        <div style="margin-top: 3.5rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem;" data-stagger>
            @foreach(__('landing.whatsapp.features') as $feature)
            <div style="
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 1rem;
                padding: 1.5rem;
                text-align: center;
                transition: all 0.2s ease;
            " onmouseover="this.style.background='#ffffff'; this.style.borderColor='#2E8B83'; this.style.boxShadow='0 8px 24px rgba(46,139,131,0.08)'" onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                <div style="width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; font-size: 1.5rem;">
                    <i class="fas {{ $feature['icon'] }}"></i>
                </div>
                <h4 style="color: #0f172a; font-size: 0.95rem; font-weight: 800; margin: 0 0 0.5rem 0;">{{ $feature['title'] }}</h4>
                <p style="color: #64748b; font-size: 0.85rem; margin: 0; line-height: 1.55;">{{ $feature['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>