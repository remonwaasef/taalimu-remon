                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin::admin.site_name') }}</label>
                                <input type="text" class="form-control rounded-4 shadow-sm border-light" name="site_name" value="{{ \App\Models\SiteSetting::get('site_name', 'EduCentral') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('admin::admin.admin_email') }}</label>
                                <input type="email" class="form-control rounded-4 shadow-sm border-light" name="admin_email" value="{{ \App\Models\SiteSetting::get('admin_email', 'admin@educentral.com') }}">
                            </div>
                            <div class="col-12" x-data="{ 
                                activeLang: '{{ app()->getLocale() }}',
                                arVal: {{ Js::from(\App\Models\SiteSetting::get('site_description_ar', \App\Models\SiteSetting::get('site_description', __('landing.hero.subtitle', [], 'ar')))) }},
                                enVal: {{ Js::from(\App\Models\SiteSetting::get('site_description_en', __('landing.hero.subtitle', [], 'en'))) }},
                                frVal: {{ Js::from(\App\Models\SiteSetting::get('site_description_fr', __('landing.hero.subtitle', [], 'fr'))) }}
                            }">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label fw-bold mb-0">{{ __('admin::admin.site_description') }} (<span x-text="activeLang.toUpperCase()"></span>)</label>
                                    <div class="d-flex gap-1 bg-light p-1 rounded-pill border">
                                        <button type="button" @click="activeLang = 'ar'" :class="activeLang === 'ar' ? 'btn-primary shadow-sm' : 'btn-light text-muted border-0'" class="btn btn-sm py-0 px-3 rounded-pill position-relative transition-all" style="font-size: 0.75rem; font-weight: 700;">
                                            AR
                                            <span x-show="!arVal" class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" title="{{ __('admin.missing_translation') }}"></span>
                                        </button>
                                        <button type="button" @click="activeLang = 'en'" :class="activeLang === 'en' ? 'btn-primary shadow-sm' : 'btn-light text-muted border-0'" class="btn btn-sm py-0 px-3 rounded-pill position-relative transition-all" style="font-size: 0.75rem; font-weight: 700;">
                                            EN
                                            <span x-show="!enVal" class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" title="{{ __('admin.missing_translation') }}"></span>
                                        </button>
                                        <button type="button" @click="activeLang = 'fr'" :class="activeLang === 'fr' ? 'btn-primary shadow-sm' : 'btn-light text-muted border-0'" class="btn btn-sm py-0 px-3 rounded-pill position-relative transition-all" style="font-size: 0.75rem; font-weight: 700;">
                                            FR
                                            <span x-show="!frVal" class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" title="{{ __('admin.missing_translation') }}"></span>
                                        </button>
                                    </div>
                                </div>
                                <div x-show="activeLang === 'ar'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                    <textarea class="form-control rounded-4 shadow-sm border-light" name="site_description_ar" rows="2" dir="rtl" x-model="arVal"></textarea>
                                </div>
                                <div x-show="activeLang === 'en'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display: none;">
                                    <textarea class="form-control rounded-4 shadow-sm border-light" name="site_description_en" rows="2" dir="ltr" x-model="enVal"></textarea>
                                </div>
                                <div x-show="activeLang === 'fr'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display: none;">
                                    <textarea class="form-control rounded-4 shadow-sm border-light" name="site_description_fr" rows="2" dir="ltr" x-model="frVal"></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">مدة باقة الترم (بالأيام)</label>
                                <input type="number" class="form-control rounded-4 shadow-sm border-light" name="term_duration_days" value="{{ \App\Models\SiteSetting::get('term_duration_days', 150) }}">
                                <small class="text-muted">الافتراضي: 150 يوم</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('admin::admin.currency_symbol') }}</label>
                                <input type="text" class="form-control rounded-4 shadow-sm border-light" name="currency_symbol" value="{{ \App\Models\SiteSetting::get('currency_symbol', 'جنيه') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">{{ __('admin::admin.currency_code') }}</label>
                                <input type="text" class="form-control rounded-4 shadow-sm border-light" name="currency_code" value="{{ \App\Models\SiteSetting::get('currency_code', 'EGP') }}">
                            </div>
                        </div>
                    </div>
