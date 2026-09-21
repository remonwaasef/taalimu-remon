<!-- Onboarding Quick Setup Modals -->

<!-- Modal 1: Teaching System & Curriculum -->
<div id="teachingSystemModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
    <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden transform transition-all animate-fade-in-up" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-brand-primary/10 text-brand-primary flex items-center justify-center text-base">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900 dark:text-slate-100 font-arabic">نظام التدريس والتعليم</h3>
                    <p class="text-xs text-slate-400 font-arabic">حدد طريقة التدريس والمنهج الدراسي لمنصتك</p>
                </div>
            </div>
            <button type="button" onclick="closeTeachingSystemModal()" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 hover:text-slate-600 flex items-center justify-center text-sm transition-colors cursor-pointer">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="teachingSystemForm" onsubmit="saveTeachingSystem(event)" class="p-5 space-y-4">
            @csrf

            <!-- Account Profile Segmented Switch -->
            <div>
                <label class="block text-xs font-black text-slate-700 dark:text-slate-300 font-arabic mb-2">
                    <i class="fas fa-user-tag text-brand-primary me-1"></i> نوع وطبيعة النشاط
                </label>
                <div class="grid grid-cols-2 p-1 bg-slate-100 dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80">
                    <label class="flex items-center justify-center gap-2 py-2 px-3 rounded-xl cursor-pointer transition-all text-xs font-black font-arabic text-center account-type-tab" id="tab_instructor_label">
                        <input type="radio" name="account_type" value="instructor" class="sr-only" {{ ($accountType ?? 'instructor') === 'instructor' ? 'checked' : '' }} onchange="switchAccountTypeTab('instructor')">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>مدرس مستقل</span>
                    </label>
                    <label class="flex items-center justify-center gap-2 py-2 px-3 rounded-xl cursor-pointer transition-all text-xs font-black font-arabic text-center account-type-tab" id="tab_center_label">
                        <input type="radio" name="account_type" value="center" class="sr-only" {{ ($accountType ?? '') === 'center' ? 'checked' : '' }} onchange="switchAccountTypeTab('center')">
                        <i class="fas fa-school"></i>
                        <span>مركز تعليمي / سنتر</span>
                    </label>
                </div>
            </div>

            <!-- Section 1A: Instructor Teaching Modes (When "مدرس مستقل") -->
            <div id="instructor_teaching_modes_container">
                <label class="block text-xs font-black text-slate-700 dark:text-slate-300 font-arabic mb-2">
                    <i class="fas fa-laptop-house text-brand-primary me-1"></i> طريقة وتواجد تدريس المدرس
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <label class="relative flex flex-col p-3 rounded-2xl border cursor-pointer transition-all teaching-mode-card hover:border-brand-primary/60 border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                        <input type="radio" name="teaching_mode" value="online_independent" class="sr-only" {{ in_array(($teachingMode ?? 'online_independent'), ['online', 'online_independent']) ? 'checked' : '' }} onchange="handleTeachingModeChange('online_independent')">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-laptop text-brand-primary text-sm"></i>
                            <span class="text-xs font-black text-slate-800 dark:text-slate-200 font-arabic">أونلاين مستقل (منصتي الخاصة)</span>
                        </div>
                        <span class="text-[10px] text-slate-400 leading-tight">بث مباشر، تسجيلات ومجموعات افتراضية خاصة بي</span>
                    </label>

                    <label class="relative flex flex-col p-3 rounded-2xl border cursor-pointer transition-all teaching-mode-card hover:border-brand-primary/60 border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                        <input type="radio" name="teaching_mode" value="in_centers" class="sr-only" {{ in_array(($teachingMode ?? ''), ['in_person', 'in_centers']) ? 'checked' : '' }} onchange="handleTeachingModeChange('in_centers')">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-store-alt text-emerald-600 text-sm"></i>
                            <span class="text-xs font-black text-slate-800 dark:text-slate-200 font-arabic">أدرّس داخل مراكز وسناتر</span>
                        </div>
                        <span class="text-[10px] text-slate-400 leading-tight">حصص حضورية داخل سناتر ومراكز تعليمية</span>
                    </label>

                    <label class="relative flex flex-col p-3 rounded-2xl border cursor-pointer transition-all teaching-mode-card hover:border-brand-primary/60 border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                        <input type="radio" name="teaching_mode" value="private_hall" class="sr-only" {{ ($teachingMode ?? '') === 'private_hall' ? 'checked' : '' }} onchange="handleTeachingModeChange('private_hall')">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-door-open text-purple-600 text-sm"></i>
                            <span class="text-xs font-black text-slate-800 dark:text-slate-200 font-arabic">قاعة خاصة بي (دروس خصوصية)</span>
                        </div>
                        <span class="text-[10px] text-slate-400 leading-tight">مقر ومجموعات خاصة بإشرافي وحضور بالباركود</span>
                    </label>

                    <label class="relative flex flex-col p-3 rounded-2xl border cursor-pointer transition-all teaching-mode-card hover:border-brand-primary/60 border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                        <input type="radio" name="teaching_mode" value="hybrid" class="sr-only" {{ ($teachingMode ?? '') === 'hybrid' ? 'checked' : '' }} onchange="handleTeachingModeChange('hybrid')">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-sync-alt text-amber-500 text-sm"></i>
                            <span class="text-xs font-black text-slate-800 dark:text-slate-200 font-arabic">نظام هجين (سنتر + أونلاين)</span>
                        </div>
                        <span class="text-[10px] text-slate-400 leading-tight">الجمع بين حصص السنتر والمتابعة وبث الحصص أونلاين</span>
                    </label>
                </div>

                <!-- Sub-Section: Centers System & Details -->
                <div id="centers_system_details" class="mt-3 p-3.5 rounded-2xl bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-200/80 dark:border-emerald-800/60 space-y-2.5 transition-all">
                    <label class="block text-xs font-black text-emerald-900 dark:text-emerald-200 font-arabic">
                        <i class="fas fa-building text-emerald-600 me-1"></i> اختيار وتحديد نظام المراكز
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="relative flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer transition-all bg-white dark:bg-slate-900 text-xs font-bold border-slate-200 dark:border-slate-700 center-relation-card">
                            <input type="radio" name="center_relation" value="single_center" class="sr-only" {{ ($centerRelation ?? 'single_center') === 'single_center' ? 'checked' : '' }} onchange="highlightCenterRelationCards()">
                            <i class="fas fa-check-circle text-emerald-600 text-xs"></i>
                            <span class="text-slate-800 dark:text-slate-200">سنتر رئيسي واحد</span>
                        </label>
                        <label class="relative flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer transition-all bg-white dark:bg-slate-900 text-xs font-bold border-slate-200 dark:border-slate-700 center-relation-card">
                            <input type="radio" name="center_relation" value="multiple_centers" class="sr-only" {{ ($centerRelation ?? '') === 'multiple_centers' ? 'checked' : '' }} onchange="highlightCenterRelationCards()">
                            <i class="fas fa-check-circle text-emerald-600 text-xs"></i>
                            <span class="text-slate-800 dark:text-slate-200">عدة سناتر ومراكز</span>
                        </label>
                    </div>
                    <div>
                        <input type="text" name="center_names" value="{{ $centerNames ?? '' }}" placeholder="اسم السنتر أو المراكز التي تدرّس بها (مثال: سنتر الأوائل، سنتر النور)" class="w-full h-9 px-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-arabic">
                    </div>
                </div>
            </div>

            <!-- Section 1B: Center Management Modes (When "مركز تعليمي / سنتر") -->
            <div id="center_teaching_modes_container" class="hidden">
                <label class="block text-xs font-black text-slate-700 dark:text-slate-300 font-arabic mb-2">
                    <i class="fas fa-school text-brand-primary me-1"></i> نمط تشغيل وإدارة المركز
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                    <label class="relative flex flex-col p-3 rounded-2xl border cursor-pointer transition-all teaching-mode-card hover:border-brand-primary/60 border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                        <input type="radio" name="teaching_mode" value="center_in_person" class="sr-only" {{ ($teachingMode ?? '') === 'center_in_person' ? 'checked' : '' }} onchange="highlightSelectedCards()">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-building text-emerald-600 text-sm"></i>
                            <span class="text-xs font-black text-slate-800 dark:text-slate-200 font-arabic">سنتر وقاعات</span>
                        </div>
                        <span class="text-[10px] text-slate-400 leading-tight">إدارة قاعات، بوابات باركود وحسابات معلمين</span>
                    </label>

                    <label class="relative flex flex-col p-3 rounded-2xl border cursor-pointer transition-all teaching-mode-card hover:border-brand-primary/60 border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                        <input type="radio" name="teaching_mode" value="center_online" class="sr-only" {{ ($teachingMode ?? '') === 'center_online' ? 'checked' : '' }} onchange="highlightSelectedCards()">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-globe text-brand-primary text-sm"></i>
                            <span class="text-xs font-black text-slate-800 dark:text-slate-200 font-arabic">أكاديمية أونلاين</span>
                        </div>
                        <span class="text-[10px] text-slate-400 leading-tight">فصول افتراضية وبث مباشر لكادر المعلمين</span>
                    </label>

                    <label class="relative flex flex-col p-3 rounded-2xl border cursor-pointer transition-all teaching-mode-card hover:border-brand-primary/60 border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                        <input type="radio" name="teaching_mode" value="center_hybrid" class="sr-only" {{ ($teachingMode ?? '') === 'center_hybrid' ? 'checked' : '' }} onchange="highlightSelectedCards()">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-layer-group text-amber-500 text-sm"></i>
                            <span class="text-xs font-black text-slate-800 dark:text-slate-200 font-arabic">مركز هجين متكامل</span>
                        </div>
                        <span class="text-[10px] text-slate-400 leading-tight">قاعات فعلية + منصة تعليم إلكتروني موحدة</span>
                    </label>
                </div>
            </div>

            <!-- Section 2: Education Curriculum / System -->
            <div>
                <label class="block text-xs font-black text-slate-700 dark:text-slate-300 font-arabic mb-2">
                    <i class="fas fa-book-reader text-brand-primary me-1"></i> نظام المنهج والتعليم
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <label class="relative flex items-center gap-2.5 p-3 rounded-2xl border cursor-pointer transition-all edu-system-card hover:border-brand-primary/60 border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                        <input type="radio" name="education_system" value="general" class="sr-only" {{ ($educationSystem ?? 'general') === 'general' ? 'checked' : '' }} onchange="highlightSelectedCards()">
                        <span class="text-lg">🇪🇬</span>
                        <div>
                            <span class="block text-xs font-black text-slate-800 dark:text-slate-200 font-arabic">التعليم العام (مصري)</span>
                            <span class="block text-[10px] text-slate-400">وزارة التربية والتعليم والتعليم الفني</span>
                        </div>
                    </label>

                    <label class="relative flex items-center gap-2.5 p-3 rounded-2xl border cursor-pointer transition-all edu-system-card hover:border-brand-primary/60 border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                        <input type="radio" name="education_system" value="azhar" class="sr-only" {{ ($educationSystem ?? '') === 'azhar' ? 'checked' : '' }} onchange="highlightSelectedCards()">
                        <span class="text-lg">🕌</span>
                        <div>
                            <span class="block text-xs font-black text-slate-800 dark:text-slate-200 font-arabic">التعليم الأزهري</span>
                            <span class="block text-[10px] text-slate-400">قطاع المعاهد الأزهرية</span>
                        </div>
                    </label>

                    <label class="relative flex items-center gap-2.5 p-3 rounded-2xl border cursor-pointer transition-all edu-system-card hover:border-brand-primary/60 border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                        <input type="radio" name="education_system" value="languages" class="sr-only" {{ ($educationSystem ?? '') === 'languages' ? 'checked' : '' }} onchange="highlightSelectedCards()">
                        <span class="text-lg">🌐</span>
                        <div>
                            <span class="block text-xs font-black text-slate-800 dark:text-slate-200 font-arabic">مدارس اللغات والتجريبي</span>
                            <span class="block text-[10px] text-slate-400">Languages & Experimental Schools</span>
                        </div>
                    </label>

                    <label class="relative flex items-center gap-2.5 p-3 rounded-2xl border cursor-pointer transition-all edu-system-card hover:border-brand-primary/60 border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                        <input type="radio" name="education_system" value="international" class="sr-only" {{ ($educationSystem ?? '') === 'international' ? 'checked' : '' }} onchange="highlightSelectedCards()">
                        <span class="text-lg">🌍</span>
                        <div>
                            <span class="block text-xs font-black text-slate-800 dark:text-slate-200 font-arabic">المناهج الدولية</span>
                            <span class="block text-[10px] text-slate-400">IGCSE / American / IB</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2">
                <button type="button" onclick="closeTeachingSystemModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">إلغاء</button>
                <button type="submit" id="saveTeachingSystemBtn" class="px-5 py-2 rounded-xl bg-brand-primary text-white text-xs font-black shadow-md shadow-brand-primary/20 hover:bg-brand-primary/90 transition-all">حفظ ومتابعة ←</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Connect Live Stream (Meet / Zoom) -->
<div id="meetingLinkModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
    <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden transform transition-all animate-fade-in-up" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400 flex items-center justify-center text-base">
                    <i class="fas fa-video"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900 dark:text-slate-100 font-arabic">ربط البث المباشر</h3>
                    <p class="text-xs text-slate-400 font-arabic">رابط Google Meet أو Zoom الدائم لحصصك</p>
                </div>
            </div>
            <button type="button" onclick="closeMeetingLinkModal()" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 hover:text-slate-600 flex items-center justify-center text-sm transition-colors cursor-pointer">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="meetingLinkForm" onsubmit="saveMeetingLink(event)" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 font-arabic mb-1.5">
                    الرابط الافتراضي للبث المباشر (Google Meet أو Zoom)
                </label>
                <div class="relative group" dir="ltr">
                    <input type="url" name="default_meeting_link" id="default_meeting_link_input" 
                           value="{{ $defaultMeetingLink ?? '' }}"
                           required
                           placeholder="https://meet.google.com/xxx-xxxx-xxx"
                           class="w-full h-11 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-xs font-mono text-slate-800 dark:text-slate-200 focus:outline-none focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all">
                </div>
                <p class="text-[11px] text-slate-400 mt-1.5 leading-relaxed font-arabic">
                    💡 سيتم إدراج هذا الرابط تلقائياً لطلابك في كل حصة مباشرة دون الحاجة لإنشاء رابط جديد في كل مرة.
                </p>
            </div>

            <!-- Quick template buttons -->
            <div class="flex items-center gap-2 pt-1">
                <span class="text-[11px] font-bold text-slate-400 font-arabic">أمثلة:</span>
                <button type="button" onclick="document.getElementById('default_meeting_link_input').value='https://meet.google.com/new'" class="text-[11px] px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-brand-primary font-bold transition-colors">
                    <i class="fab fa-google text-red-500 me-1"></i> Google Meet
                </button>
                <button type="button" onclick="document.getElementById('default_meeting_link_input').value='https://zoom.us/j/'" class="text-[11px] px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-blue-500 font-bold transition-colors">
                    <i class="fas fa-video text-blue-500 me-1"></i> Zoom
                </button>
            </div>

            <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2">
                <button type="button" onclick="closeMeetingLinkModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">إلغاء</button>
                <button type="submit" id="saveMeetingLinkBtn" class="px-5 py-2 rounded-xl bg-emerald-600 text-white text-xs font-black shadow-md shadow-emerald-600/20 hover:bg-emerald-700 transition-all">حفظ الرابط الدائم ✓</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 3: Share Registration Link with Students -->
<div id="shareLinkModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
    <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden transform transition-all animate-fade-in-up" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 dark:bg-teal-950/30 dark:text-teal-400 flex items-center justify-center text-base">
                    <i class="fas fa-share-alt"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900 dark:text-slate-100 font-arabic">مشاركة رابط التسجيل مع الطلاب</h3>
                    <p class="text-xs text-slate-400 font-arabic">انسخ الرابط وشاركه مع طلابك في ثوانٍ</p>
                </div>
            </div>
            <button type="button" onclick="closeShareLinkModal()" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 hover:text-slate-600 flex items-center justify-center text-sm transition-colors cursor-pointer">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="p-5 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 font-arabic mb-1.5">
                    رابط المنصة للتسجيل المباشر
                </label>
                <div class="flex items-center gap-2">
                    <input type="text" id="share_link_input" 
                           value="{{ $firstGroupRegistrationUrl ?? tenant_url('/') }}" 
                           readonly dir="ltr"
                           class="w-full h-11 px-3 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-600 dark:text-slate-300 font-mono select-all">
                    <button type="button" onclick="copyShareLink()" id="copyShareLinkBtn" 
                            class="h-11 px-4 bg-brand-primary text-white text-xs font-bold rounded-xl hover:bg-brand-primary/90 transition-all flex items-center gap-1.5 shrink-0 shadow-xs cursor-pointer">
                        <i class="fas fa-copy"></i>
                        <span id="copyBtnText">نسخ</span>
                    </button>
                </div>
            </div>

            <div class="space-y-2">
                <span class="block text-xs font-bold text-slate-400 font-arabic">مشاركة سريعة عبر التطبيقات:</span>
                <div class="grid grid-cols-2 gap-2">
                    @php
                        $shareMsg = "أهلاً بكم! يمكنكم الآن التسجيل وحضور الحصص عبر منصتي التعليمية من خلال الرابط التالي:\n" . ($firstGroupRegistrationUrl ?? tenant_url('/'));
                    @endphp
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($shareMsg) }}" target="_blank"
                       class="flex items-center justify-center gap-2 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 font-bold text-xs hover:bg-emerald-100 transition-colors">
                        <i class="fab fa-whatsapp text-base"></i>
                        <span>واتساب (WhatsApp)</span>
                    </a>
                    <a href="https://t.me/share/url?url={{ urlencode($firstGroupRegistrationUrl ?? tenant_url('/')) }}&text={{ urlencode('سجل الآن في الحصص التعليمية') }}" target="_blank"
                       class="flex items-center justify-center gap-2 p-3 rounded-xl bg-sky-50 dark:bg-sky-950/40 text-sky-700 dark:text-sky-300 font-bold text-xs hover:bg-sky-100 transition-colors">
                        <i class="fab fa-telegram text-base"></i>
                        <span>تليجرام (Telegram)</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function switchAccountTypeTab(type) {
    const instructorContainer = document.getElementById('instructor_teaching_modes_container');
    const centerContainer = document.getElementById('center_teaching_modes_container');
    const instructorTabLabel = document.getElementById('tab_instructor_label');
    const centerTabLabel = document.getElementById('tab_center_label');

    if (!instructorContainer || !centerContainer) return;

    if (type === 'instructor') {
        instructorContainer.classList.remove('hidden');
        centerContainer.classList.add('hidden');
        
        instructorTabLabel.classList.add('bg-white', 'dark:bg-slate-900', 'text-brand-primary', 'shadow-xs');
        instructorTabLabel.classList.remove('text-slate-500');
        
        centerTabLabel.classList.remove('bg-white', 'dark:bg-slate-900', 'text-brand-primary', 'shadow-xs');
        centerTabLabel.classList.add('text-slate-500');

        const activeMode = instructorContainer.querySelector('input[name="teaching_mode"]:checked')?.value || 'online_independent';
        handleTeachingModeChange(activeMode);
    } else {
        instructorContainer.classList.add('hidden');
        centerContainer.classList.remove('hidden');
        
        centerTabLabel.classList.add('bg-white', 'dark:bg-slate-900', 'text-brand-primary', 'shadow-xs');
        centerTabLabel.classList.remove('text-slate-500');
        
        instructorTabLabel.classList.remove('bg-white', 'dark:bg-slate-900', 'text-brand-primary', 'shadow-xs');
        instructorTabLabel.classList.add('text-slate-500');

        const checked = centerContainer.querySelector('input[name="teaching_mode"]:checked');
        if (!checked) {
            const first = centerContainer.querySelector('input[name="teaching_mode"]');
            if (first) { first.checked = true; }
        }
    }
    highlightSelectedCards();
}

function handleTeachingModeChange(mode) {
    const centersBox = document.getElementById('centers_system_details');
    if (centersBox) {
        if (mode === 'in_centers' || mode === 'hybrid') {
            centersBox.classList.remove('hidden');
        } else {
            centersBox.classList.add('hidden');
        }
    }
    highlightSelectedCards();
    highlightCenterRelationCards();
}

function highlightCenterRelationCards() {
    document.querySelectorAll('.center-relation-card').forEach(card => {
        const input = card.querySelector('input');
        if (input && input.checked) {
            card.classList.add('border-emerald-500', 'bg-emerald-50/50', 'ring-2', 'ring-emerald-500/20');
            card.classList.remove('border-slate-200', 'dark:border-slate-700');
        } else {
            card.classList.remove('border-emerald-500', 'bg-emerald-50/50', 'ring-2', 'ring-emerald-500/20');
            card.classList.add('border-slate-200', 'dark:border-slate-700');
        }
    });
}

function highlightSelectedCards() {
    document.querySelectorAll('.teaching-mode-card').forEach(card => {
        const input = card.querySelector('input');
        if (input && input.checked) {
            card.classList.add('border-brand-primary', 'bg-brand-primary/5', 'ring-2', 'ring-brand-primary/20');
            card.classList.remove('border-slate-200', 'dark:border-slate-700');
        } else {
            card.classList.remove('border-brand-primary', 'bg-brand-primary/5', 'ring-2', 'ring-brand-primary/20');
            card.classList.add('border-slate-200', 'dark:border-slate-700');
        }
    });

    document.querySelectorAll('.edu-system-card').forEach(card => {
        const input = card.querySelector('input');
        if (input && input.checked) {
            card.classList.add('border-brand-primary', 'bg-brand-primary/5', 'ring-2', 'ring-brand-primary/20');
            card.classList.remove('border-slate-200', 'dark:border-slate-700');
        } else {
            card.classList.remove('border-brand-primary', 'bg-brand-primary/5', 'ring-2', 'ring-brand-primary/20');
            card.classList.add('border-slate-200', 'dark:border-slate-700');
        }
    });
}

function openTeachingSystemModal() {
    document.getElementById('teachingSystemModal').classList.remove('hidden');
    const activeType = document.querySelector('input[name="account_type"]:checked')?.value || 'instructor';
    switchAccountTypeTab(activeType);
    highlightSelectedCards();
    highlightCenterRelationCards();
}

function closeTeachingSystemModal() {
    document.getElementById('teachingSystemModal').classList.add('hidden');
}

function openMeetingLinkModal() {
    document.getElementById('meetingLinkModal').classList.remove('hidden');
}

function closeMeetingLinkModal() {
    document.getElementById('meetingLinkModal').classList.add('hidden');
}

function openShareLinkModal() {
    document.getElementById('shareLinkModal').classList.remove('hidden');
}

function closeShareLinkModal() {
    document.getElementById('shareLinkModal').classList.add('hidden');
}

function copyShareLink() {
    const input = document.getElementById('share_link_input');
    navigator.clipboard.writeText(input.value).then(() => {
        const btnText = document.getElementById('copyBtnText');
        const originalText = btnText.innerText;
        btnText.innerText = 'تم النسخ ✓';
        setTimeout(() => { btnText.innerText = originalText; }, 2000);
    });
}

async function saveTeachingSystem(e) {
    e.preventDefault();
    const btn = document.getElementById('saveTeachingSystemBtn');
    btn.disabled = true;
    btn.innerText = 'جاري الحفظ...';

    const form = document.getElementById('teachingSystemForm');
    const formData = new FormData(form);

    try {
        const res = await fetch('{{ route("instructor.quick-setup.teaching-system") }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        });

        if (res.ok) {
            window.location.reload();
        } else {
            alert('حدث خطأ أثناء الحفظ. يرجى المحاولة مرة أخرى.');
            btn.disabled = false;
            btn.innerText = 'حفظ ومتابعة ←';
        }
    } catch(err) {
        console.error(err);
        btn.disabled = false;
        btn.innerText = 'حفظ ومتابعة ←';
    }
}

async function saveMeetingLink(e) {
    e.preventDefault();
    const btn = document.getElementById('saveMeetingLinkBtn');
    btn.disabled = true;
    btn.innerText = 'جاري الحفظ...';

    const form = document.getElementById('meetingLinkForm');
    const formData = new FormData(form);

    try {
        const res = await fetch('{{ route("instructor.quick-setup.meeting-link") }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        });

        if (res.ok) {
            window.location.reload();
        } else {
            alert('حدث خطأ أثناء الحفظ. تأكد من إدخال رابط صحيح (http:// أو https://)');
            btn.disabled = false;
            btn.innerText = 'حفظ الرابط الدائم ✓';
        }
    } catch(err) {
        console.error(err);
        btn.disabled = false;
        btn.innerText = 'حفظ الرابط الدائم ✓';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    highlightSelectedCards();
});
</script>
