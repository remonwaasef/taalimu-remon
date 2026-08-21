{{-- Killer Feature Section — WhatsApp Automation Mockup --}}
<section id="whatsapp" class="section-light" style="padding: 6.5rem 0; background: #ffffff; border-bottom: 1px solid #e2e8f0;"
         x-data="{ activeCase: 'attendance' }">
    <div class="container mx-auto px-4 lg:px-12">
        
        {{-- Section Header --}}
        <div class="text-center mb-16" data-animate>
            <div class="section-badge" style="margin-bottom: 1.25rem; display: inline-flex; background: #dcfce7; border: 1px solid #bbf7d0; padding: 0.375rem 1rem; border-radius: 9999px;">
                <span style="color: #15803d !important; font-weight: 700; font-size: 0.825rem;">
                    <i class="fab fa-whatsapp" style="margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.375rem;"></i>
                    {{ __('landing.whatsapp_killer.badge') }}
                </span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.875rem, 4vw, 3rem); font-weight: 900; margin-bottom: 1.25rem; letter-spacing: -0.025em; line-height: 1.25;">
                {{ __('landing.whatsapp_killer.title') }}
            </h2>
            <p style="color: #475569 !important; font-size: 1.125rem; max-width: 44rem; margin: 0 auto; font-weight: 500; line-height: 1.7;">
                {{ __('landing.whatsapp_killer.subtitle') }}
            </p>
        </div>

        {{-- WhatsApp Interactive Showcase --}}
        <div class="grid lg:grid-cols-12 gap-8 max-w-6xl mx-auto items-center" data-stagger>
            
            {{-- Left Column: Interactive Scenario Selectors (6 Cases) --}}
            <div class="lg:col-span-6 flex flex-col gap-3">
                @foreach(__('landing.whatsapp_killer.cases') as $key => $case)
                <button 
                    @click="activeCase = '{{ $key }}'"
                    type="button"
                    class="text-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"
                    style="
                        padding: 1.25rem 1.5rem;
                        border-radius: 1rem;
                        transition: all 0.25s ease;
                        display: flex;
                        align-items: flex-start;
                        gap: 1rem;
                        border: 1px solid;
                    "
                    :style="activeCase === '{{ $key }}' ? 'background: #f0fdf4; border-color: #2E8B83; box-shadow: 0 4px 15px rgba(46,139,131,0.12);' : 'background: #ffffff; border-color: #e2e8f0;'"
                >
                    <div style="
                        width: 2.25rem;
                        height: 2.25rem;
                        border-radius: 0.5rem;
                        display: flex;
                        align-items: center;
                        justify-content: center;
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
                    
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; margin-bottom: 0.25rem;">
                            <span style="font-size: 1rem; font-weight: 800; color: #0f172a;">{{ $case['title'] }}</span>
                            <span style="font-size: 0.7rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: 9999px; background: #e2e8f0; color: #475569;"
                                  :style="activeCase === '{{ $key }}' ? 'background: #dcfce7; color: #15803d;' : ''">
                                {{ $case['tag'] }}
                            </span>
                        </div>
                        <p style="font-size: 0.85rem; color: #64748b; margin: 0; line-height: 1.5;">
                            {{ $case['desc'] }}
                        </p>
                    </div>
                </button>
                @endforeach
            </div>

            {{-- Right Column: Realistic WhatsApp Phone Mockup --}}
            <div class="lg:col-span-6 flex justify-center">
                <div style="
                    width: 100%;
                    max-width: 380px;
                    background: #ffffff;
                    border-radius: 2rem;
                    border: 8px solid #0f172a;
                    box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.25);
                    overflow: hidden;
                    position: relative;
                ">
                    {{-- Phone Top Notch --}}
                    <div style="background: #0f172a; height: 1.5rem; display: flex; align-items: center; justify-content: center;">
                        <div style="width: 4rem; height: 0.35rem; border-radius: 9999px; background: #334155;"></div>
                    </div>

                    {{-- WhatsApp Header --}}
                    <div style="background: #075e54; padding: 0.75rem 1rem; display: flex; align-items: center; justify-content: space-between; color: #ffffff;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}" style="font-size: 0.875rem;"></i>
                            <div style="width: 2.25rem; height: 2.25rem; border-radius: 50%; background: #25d366; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.875rem; color: #ffffff;">
                                T
                            </div>
                            <div>
                                <div style="font-weight: 700; font-size: 0.875rem; line-height: 1.2;">Taalimu Education Center</div>
                                <div style="font-size: 0.65rem; color: #a7f3d0;">Verified Official Service</div>
                            </div>
                        </div>
                        <div style="display: flex; gap: 0.875rem; font-size: 0.875rem;">
                            <i class="fas fa-phone-alt"></i>
                            <i class="fas fa-ellipsis-v"></i>
                        </div>
                    </div>

                    {{-- WhatsApp Chat Stream Area --}}
                    <div style="
                        background: #efeae2;
                        background-image: radial-gradient(#d1d7db 1px, transparent 1px);
                        background-size: 16px 16px;
                        padding: 1.25rem 1rem;
                        min-height: 380px;
                        display: flex;
                        flex-direction: column;
                        justify-content: flex-end;
                        gap: 1rem;
                    ">
                        {{-- Security Notice --}}
                        <div style="text-align: center; margin-bottom: 0.5rem;">
                            <span style="background: rgba(255,255,255,0.85); font-size: 0.65rem; color: #54656f; padding: 0.25rem 0.75rem; border-radius: 0.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05); display: inline-block;">
                                <i class="fas fa-lock" style="font-size: 0.55rem; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.25rem;"></i>
                                الرسائل مشفرة تمامًا بين الطرفين
                            </span>
                        </div>

                        {{-- Dynamic Message Bubble --}}
                        @foreach(__('landing.whatsapp_killer.cases') as $key => $case)
                        <div 
                            x-show="activeCase === '{{ $key }}'"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            style="
                                background: #ffffff;
                                border-radius: 0.75rem;
                                border-top-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}-radius: 0;
                                padding: 0.875rem 1rem;
                                box-shadow: 0 2px 5px rgba(0,0,0,0.08);
                                max-width: 90%;
                                align-self: flex-start;
                                position: relative;
                            "
                        >
                            <div style="font-weight: 800; font-size: 0.75rem; color: #128c7e; margin-bottom: 0.35rem; display: flex; align-items: center; gap: 0.35rem;">
                                <i class="fas fa-bell"></i>
                                <span>{{ $case['title'] }}</span>
                            </div>
                            <p style="font-size: 0.825rem; color: #111b21; margin: 0 0 0.5rem 0; line-height: 1.5;">
                                {{ $case['msg'] }}
                            </p>
                            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.25rem; font-size: 0.65rem; color: #667781;">
                                <span>{{ now()->format('h:i A') }}</span>
                                <i class="fas fa-check-double" style="color: #53bdeb;"></i>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- WhatsApp Input Bar --}}
                    <div style="background: #f0f2f5; padding: 0.5rem 0.75rem; display: flex; align-items: center; gap: 0.5rem; border-top: 1px solid #d1d7db;">
                        <i class="far fa-smile" style="color: #54656f;"></i>
                        <div style="flex: 1; background: #ffffff; border-radius: 1.5rem; padding: 0.35rem 0.75rem; font-size: 0.75rem; color: #8696a0;">
                            إرسال تلقائي عبر النظام...
                        </div>
                        <div style="width: 1.75rem; height: 1.75rem; border-radius: 50%; background: #00a884; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 0.75rem;">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</section>
