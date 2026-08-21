{{-- Payments & Collections Section --}}
<section id="payments" class="section-alt" style="padding: 6rem 0; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1240px; margin: 0 auto;">
        <div style="
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 3rem;
            max-width: 1100px;
            margin: 0 auto;
        ">
            
            {{-- Text Column --}}
            <div style="flex: 1 1 480px; min-width: 300px;" data-animate>
                <div class="section-badge" style="margin-bottom: 1.25rem; display: inline-flex; background: #E6F4F3; border: 1px solid #B2DDD9; padding: 0.375rem 1rem; border-radius: 9999px;">
                    <span style="color: #25746D !important; font-weight: 700; font-size: 0.825rem;">{{ __('landing.payments_section.badge') }}</span>
                </div>
                <h2 style="color: #0f172a !important; font-size: clamp(1.875rem, 3.5vw, 2.75rem); font-weight: 900; margin-bottom: 1.25rem; letter-spacing: -0.025em; line-height: 1.25;">
                    {{ __('landing.payments_section.title') }}
                </h2>
                <p style="color: #475569 !important; font-size: 1.1rem; margin-bottom: 2rem; font-weight: 500; line-height: 1.7;">
                    {{ __('landing.payments_section.subtitle') }}
                </p>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
                    @foreach(__('landing.payments_section.points') as $point)
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 0.875rem; padding: 1.25rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <i class="fas fa-check-circle" style="color: #2E8B83;"></i>
                            <h3 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0;">{{ $point['title'] }}</h3>
                        </div>
                        <p style="font-size: 0.825rem; color: #64748b; margin: 0; line-height: 1.5;">{{ $point['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Visual / Stat Breakdown Card --}}
            <div style="flex: 1 1 450px; min-width: 300px;" data-animate>
                <div style="
                    background: #ffffff;
                    border: 1px solid #cbd5e1;
                    border-radius: 1.25rem;
                    padding: 2rem;
                    box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.1);
                ">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #f1f5f9;">
                        <div>
                            <div style="font-size: 0.8rem; font-weight: 700; color: #64748b;">ملخص تحصيلات المركز (أكتوبر)</div>
                            <div style="font-size: 1.5rem; font-weight: 900; color: #0f172a;">48,500 <span style="font-size: 0.9rem; color: #2E8B83;">ج.م</span></div>
                        </div>
                        <span style="background: #dcfce7; color: #15803d; font-size: 0.75rem; font-weight: 800; padding: 0.25rem 0.75rem; border-radius: 9999px;">
                            محدث الآن
                        </span>
                    </div>

                    {{-- Mini Debtor Row Simulation --}}
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; background: #f8fafc; border-radius: 0.75rem; border: 1px solid #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 2rem; height: 2rem; border-radius: 50%; background: #E6F4F3; color: #2E8B83; font-weight: 800; font-size: 0.75rem; display: flex; align-items: center; justify-content: center;">ي</div>
                                <div>
                                    <div style="font-size: 0.85rem; font-weight: 800; color: #0f172a;">يوسف أحمد — مجموعة الكيمياء</div>
                                    <div style="font-size: 0.7rem; color: #64748b;">قسط أكتوبر (مستحق غداً)</div>
                                </div>
                            </div>
                            <div style="text-align: {{ app()->getLocale() == 'ar' ? 'left' : 'right' }};">
                                <span style="color: #d97706; font-weight: 800; font-size: 0.85rem;">500 ج.م</span>
                                <div style="font-size: 0.65rem; color: #2E8B83; font-weight: 700;">تم إرسال تذكير</div>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; background: #f8fafc; border-radius: 0.75rem; border: 1px solid #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 2rem; height: 2rem; border-radius: 50%; background: #f0fdf4; color: #16a34a; font-weight: 800; font-size: 0.75rem; display: flex; align-items: center; justify-content: center;">م</div>
                                <div>
                                    <div style="font-size: 0.85rem; font-weight: 800; color: #0f172a;">مريم حسن — مجموعة الفيزياء</div>
                                    <div style="font-size: 0.7rem; color: #16a34a;">تم السداد بنجاح</div>
                                </div>
                            </div>
                            <div style="text-align: {{ app()->getLocale() == 'ar' ? 'left' : 'right' }};">
                                <span style="color: #16a34a; font-weight: 800; font-size: 0.85rem;">450 ج.م</span>
                                <div style="font-size: 0.65rem; color: #64748b;">إيصال #1084</div>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; background: #fef2f2; border-radius: 0.75rem; border: 1px solid #fecaca;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 2rem; height: 2rem; border-radius: 50%; background: #fee2e2; color: #dc2626; font-weight: 800; font-size: 0.75rem; display: flex; align-items: center; justify-content: center;">ك</div>
                                <div>
                                    <div style="font-size: 0.85rem; font-weight: 800; color: #0f172a;">كريم تامر — الرياضيات</div>
                                    <div style="font-size: 0.7rem; color: #dc2626;">متأخر 5 أيام</div>
                                </div>
                            </div>
                            <div style="text-align: {{ app()->getLocale() == 'ar' ? 'left' : 'right' }};">
                                <span style="color: #dc2626; font-weight: 800; font-size: 0.85rem;">600 ج.م</span>
                                <div style="font-size: 0.65rem; color: #dc2626; font-weight: 700;">مطلوب متابعة</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- Smart Attendance Section --}}
<section id="attendance" class="section-light" style="padding: 6rem 0; background: #ffffff; border-bottom: 1px solid #e2e8f0;">
    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1240px; margin: 0 auto;">
        <div style="
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 3rem;
            max-width: 1100px;
            margin: 0 auto;
        ">
            
            {{-- Attendance Visual / QR Card --}}
            <div style="flex: 1 1 450px; min-width: 300px;" data-animate>
                <div style="
                    background: #ffffff;
                    border: 1px solid #cbd5e1;
                    border-radius: 1.25rem;
                    padding: 2.25rem 2rem;
                    box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.1);
                    text-align: center;
                ">
                    <div style="display: inline-block; padding: 1.5rem; background: #f8fafc; border-radius: 1rem; border: 2px dashed #cbd5e1; margin-bottom: 1.25rem;">
                        <i class="fas fa-qrcode" style="font-size: 5.5rem; color: #2E8B83;"></i>
                    </div>
                    <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem;">بطاقة الطالب الذكية</h3>
                    <p style="font-size: 0.875rem; color: #64748b; margin-bottom: 1.25rem;">مسح سريع في 1 ثانية بكاميرا الهاتف أو قارئ الباركود</p>
                    <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 9999px; background: #dcfce7; color: #15803d; font-size: 0.825rem; font-weight: 700;">
                        <i class="fas fa-check"></i>
                        <span>تم تسجيل الحضور وإرسال إشعار فوري لولي الأمر</span>
                    </div>
                </div>
            </div>

            {{-- Text Column --}}
            <div style="flex: 1 1 480px; min-width: 300px;" data-animate>
                <div class="section-badge" style="margin-bottom: 1.25rem; display: inline-flex; background: #eff6ff; border: 1px solid #bfdbfe; padding: 0.375rem 1rem; border-radius: 9999px;">
                    <span style="color: #1d4ed8 !important; font-weight: 700; font-size: 0.825rem;">{{ __('landing.attendance_section.badge') }}</span>
                </div>
                <h2 style="color: #0f172a !important; font-size: clamp(1.875rem, 3.5vw, 2.75rem); font-weight: 900; margin-bottom: 1.25rem; letter-spacing: -0.025em; line-height: 1.25;">
                    {{ __('landing.attendance_section.title') }}
                </h2>
                <p style="color: #475569 !important; font-size: 1.1rem; margin-bottom: 2rem; font-weight: 500; line-height: 1.7;">
                    {{ __('landing.attendance_section.subtitle') }}
                </p>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
                    @foreach(__('landing.attendance_section.points') as $point)
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.875rem; padding: 1.25rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <i class="fas fa-check-circle" style="color: #2563eb;"></i>
                            <h3 style="font-size: 0.95rem; font-weight: 800; color: #0f172a; margin: 0;">{{ $point['title'] }}</h3>
                        </div>
                        <p style="font-size: 0.825rem; color: #64748b; margin: 0; line-height: 1.5;">{{ $point['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>
