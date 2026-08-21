{{-- Killer Feature Section — Compact WhatsApp Automation Mockup --}}
<section id="whatsapp" class="section-light" style="padding: 4rem 0; background: #ffffff; border-bottom: 1px solid #e2e8f0;"
         x-data="{ activeCase: 'attendance' }">
    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1140px; margin: 0 auto;">
        
        {{-- Section Header --}}
        <div class="text-center mb-10" data-animate>
            <div class="section-badge" style="margin-bottom: 0.875rem; display: inline-flex; background: #dcfce7; border: 1px solid #bbf7d0; padding: 0.3rem 0.85rem; border-radius: 9999px;">
                <span style="color: #15803d !important; font-weight: 700; font-size: 0.775rem;">
                    <i class="fab fa-whatsapp" style="margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.35rem;"></i>
                    {{ __('landing.whatsapp_killer.badge') }}
                </span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.5rem, 2.8vw, 2.15rem); font-weight: 900; margin-bottom: 0.75rem; letter-spacing: -0.02em; line-height: 1.25;">
                {{ __('landing.whatsapp_killer.title') }}
            </h2>
            <p style="color: #475569 !important; font-size: 0.975rem; max-width: 38rem; margin: 0 auto; font-weight: 500; line-height: 1.6;">
                {{ __('landing.whatsapp_killer.subtitle') }}
            </p>
        </div>

        {{-- WhatsApp Layout Container --}}
        <div style="
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 2.25rem;
            max-width: 980px;
            margin: 0 auto;
        " data-stagger>
            
            {{-- Column 1: Interactive Scenario Selectors (6 Cases) --}}
            <div style="
                flex: 1 1 420px;
                min-width: 280px;
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            ">
                @foreach(__('landing.whatsapp_killer.cases') as $key => $case)
                <button 
                    @click="activeCase = '{{ $key }}'"
                    type="button"
                    class="text-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"
                    style="
                        width: 100%;
                        padding: 0.75rem 1rem;
                        border-radius: 0.75rem;
                        transition: all 0.2s ease;
                        display: flex;
                        align-items: center;
                        gap: 0.75rem;
                        border: 1px solid;
                        cursor: pointer;
                    "
                    :style="activeCase === '{{ $key }}' ? 'background: #f0fdf4; border-color: #2E8B83; box-shadow: 0 2px 10px rgba(46,139,131,0.08);' : 'background: #ffffff; border-color: #e2e8f0;'"
                >
                    <div style="
                        width: 1.85rem;
                        height: 1.85rem;
                        border-radius: 0.4rem;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 0.85rem;
                        flex-shrink: 0;
                        transition: all 0.2s;
                    "
                    :style="activeCase === '{{ $key }}' ? 'background: #2E8B83; color: #ffffff;' : 'background: #f1f5f9; color: #64748b;'"
                    >
                        @if($key === 'attendance') <i class="fas fa-user-check"></i>
                        @elseif($key === 'absence') <i class="fas fa-user-times"></i>
                        @elseif($key === 'payment_due') <i class="fas fa-calendar-alt"></i>
                        @elseif($key === 'payment_overdue') <i class="fas fa-exclamation-triangle"></i>
                        @elseif($key === 'exam_result') <i class="fas fa-award"></i>
                        @else <i class="fas fa-bullhorn"></i>
                        @endif
                    </div>
                    
                    <div style="flex: 1; overflow: hidden;">
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.35rem;">
                            <span style="font-size: 0.875rem; font-weight: 800; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $case['title'] }}</span>
                            <span style="font-size: 0.65rem; font-weight: 700; padding: 0.15rem 0.45rem; border-radius: 9999px; background: #e2e8f0; color: #475569; flex-shrink: 0;"
                                  :style="activeCase === '{{ $key }}' ? 'background: #dcfce7; color: #15803d;' : ''">
                                {{ $case['tag'] }}
                            </span>
                        </div>
                    </div>
                </button>
                @endforeach
            </div>

            {{-- Column 2: Compact WhatsApp Phone Mockup --}}
            <div style="
                flex: 1 1 330px;
                min-width: 260px;
                display: flex;
                justify-content: center;
            ">
                <div style="
                    width: 100%;
                    max-width: 330px;
                    background: #ffffff;
                    border-radius: 1.5rem;
                    border: 6px solid #0f172a;
                    box-shadow: 0 18px 45px -10px rgba(15, 23, 42, 0.2);
                    overflow: hidden;
                    position: relative;
                ">
                    {{-- Phone Top Notch --}}
                    <div style="background: #0f172a; height: 1.125rem; display: flex; align-items: center; justify-content: center;">
                        <div style="width: 3rem; height: 0.25rem; border-radius: 9999px; background: #334155;"></div>
                    </div>

                    {{-- WhatsApp Header --}}
                    <div style="background: #075e54; padding: 0.55rem 0.85rem; display: flex; align-items: center; justify-content: space-between; color: #ffffff;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}" style="font-size: 0.75rem;"></i>
                            <div style="width: 1.85rem; height: 1.85rem; border-radius: 50%; background: #25d366; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.75rem; color: #ffffff;">
                                T
                            </div>
                            <div>
                                <div style="font-weight: 700; font-size: 0.775rem; line-height: 1.2;">Taalimu Education Center</div>
                                <div style="font-size: 0.6rem; color: #a7f3d0;">Verified Service</div>
                            </div>
                        </div>
                        <div style="display: flex; gap: 0.65rem; font-size: 0.75rem;">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                    </div>

                    {{-- WhatsApp Chat Stream Area --}}
                    <div style="
                        background: #efeae2;
                        background-image: radial-gradient(#d1d7db 1px, transparent 1px);
                        background-size: 14px 14px;
                        padding: 1rem 0.85rem;
                        min-height: 280px;
                        display: flex;
                        flex-direction: column;
                        justify-content: flex-end;
                        gap: 0.75rem;
                    ">
                        {{-- Security Notice --}}
                        <div style="text-align: center; margin-bottom: 0.25rem;">
                            <span style="background: rgba(255,255,255,0.85); font-size: 0.6rem; color: #54656f; padding: 0.2rem 0.6rem; border-radius: 0.4rem; display: inline-block;">
                                <i class="fas fa-lock" style="font-size: 0.5rem;"></i>
                                الرسائل مشفرة تمامًا
                            </span>
                        </div>

                        {{-- Dynamic Message Bubble --}}
                        @foreach(__('landing.whatsapp_killer.cases') as $key => $case)
                        <div 
                            x-show="activeCase === '{{ $key }}'"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            style="
                                background: #ffffff;
                                border-radius: 0.65rem;
                                border-top-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}-radius: 0;
                                padding: 0.75rem 0.85rem;
                                box-shadow: 0 1px 3px rgba(0,0,0,0.06);
                                max-width: 95%;
                                align-self: flex-start;
                                position: relative;
                            "
                        >
                            <div style="font-weight: 800; font-size: 0.7rem; color: #128c7e; margin-bottom: 0.25rem; display: flex; align-items: center; gap: 0.25rem;">
                                <i class="fas fa-bell"></i>
                                <span>{{ $case['title'] }}</span>
                            </div>
                            <p style="font-size: 0.775rem; color: #111b21; margin: 0 0 0.35rem 0; line-height: 1.45;">
                                {{ $case['msg'] }}
                            </p>
                            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.2rem; font-size: 0.6rem; color: #667781;">
                                <span>{{ now()->format('h:i A') }}</span>
                                <i class="fas fa-check-double" style="color: #53bdeb;"></i>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- WhatsApp Input Bar --}}
                    <div style="background: #f0f2f5; padding: 0.4rem 0.65rem; display: flex; align-items: center; gap: 0.4rem; border-top: 1px solid #d1d7db;">
                        <div style="flex: 1; background: #ffffff; border-radius: 1rem; padding: 0.25rem 0.65rem; font-size: 0.675rem; color: #8696a0;">
                            إرسال تلقائي عبر النظام...
                        </div>
                        <div style="width: 1.5rem; height: 1.5rem; border-radius: 50%; background: #00a884; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 0.65rem;">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</section>
