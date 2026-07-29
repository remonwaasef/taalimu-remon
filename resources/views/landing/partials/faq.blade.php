{{-- FAQ Section --}}
<section id="faq" class="section-light" style="padding:6rem 0;">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="text-center mb-16" data-animate>
            <h2 style="color:#0f172a !important; font-size:clamp(1.875rem, 4vw, 3rem); font-weight:900; margin-bottom:1.5rem; letter-spacing:-0.025em; line-height:1.2;">
                {{ __('landing.faq.title_prefix') }} <span style="color:#059669 !important;">{{ __('landing.faq.title_highlight') }}</span>
            </h2>
            @php $siteName = \App\Models\SiteSetting::get('site_name', 'Taalimu'); @endphp
            <p style="color:#475569 !important; font-size:1.125rem; max-width:42rem; margin:0 auto; font-weight:500; line-height:1.7;">
                {{ str_replace(config('app.name'), $siteName, __('landing.faq.subtitle')) }}
            </p>
        </div>

        <div class="max-w-3xl mx-auto" style="display:flex; flex-direction:column; gap:0.75rem;" x-data="{ active: null }" data-stagger>
            @for ($index = 0; $index < 8; $index++)
                @php
                    $question = str_replace(config('app.name'), $siteName, __("landing.faq.items.$index.question"));
                    $answer = str_replace(config('app.name'), $siteName, __("landing.faq.items.$index.answer"));
                    if ($question === "landing.faq.items.$index.question") break;
                @endphp
                <div class="faq-item"
                     :class="{ 'active': active === {{ $index }} }">
                    <button
                        @click="active = (active === {{ $index }} ? null : {{ $index }})"
                        style="display:flex; align-items:center; justify-content:space-between; width:100%; text-align:start; padding:1.25rem 1.5rem; background:transparent; border:none; cursor:pointer;"
                    >
                        <span class="faq-question" style="font-size:1rem; font-weight:700; color:#0f172a;" :style="active === {{ $index }} ? 'color:#059669' : 'color:#0f172a'">
                            {{ $question }}
                        </span>
                        <div style="width:1.75rem; height:1.75rem; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}:1rem; transition:all 0.3s;"
                             :style="active === {{ $index }} ? 'background:#059669; color:#ffffff; transform:rotate(180deg);' : 'background:#f1f5f9; color:#64748b;'">
                            <i class="fas fa-chevron-down" style="font-size:0.625rem;"></i>
                        </div>
                    </button>
                    <div
                        x-show="active === {{ $index }}"
                        style="display: none;"
                        x-collapse
                    >
                        <div style="padding:0 1.5rem 1.25rem; color:#475569; font-size:0.9rem; line-height:1.7;">
                            {{ $answer }}
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</section>
