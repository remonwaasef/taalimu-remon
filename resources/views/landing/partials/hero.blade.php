{{-- Hero — Professional Split Layout --}}
<section class="hero relative overflow-hidden pt-8 pb-16 lg:pt-14 lg:pb-24" x-data="{
    scanned: false,
    scanning: false,
    scanSuccess: false,
    studentName: '{{ __("landing.hero.demo_student_name") !== "landing.hero.demo_student_name" ? __("landing.hero.demo_student_name") : "عمر خالد المنصوري" }}',
    courseName: '{{ __("landing.hero.demo_course_name") !== "landing.hero.demo_course_name" ? __("landing.hero.demo_course_name") : "الفيزياء المتقدمة - قاعة 3" }}',
    timeNow: '{{ __("landing.hero.demo_time") !== "landing.hero.demo_time" ? __("landing.hero.demo_time") : "الآن 04:15 م" }}',
    triggerScan() {
        if (this.scanning) return;
        this.scanning = true;
        this.scanned = false;
        this.scanSuccess = false;
        setTimeout(() => {
            this.scanning = false;
            this.scanned = true;
            this.scanSuccess = true;
        }, 900);
    },
    resetScan() {
        this.scanned = false;
        this.scanning = false;
        this.scanSuccess = false;
    }
}">
  <div class="container hero-grid items-center">

    {{-- Left/Main Copy Column --}}
    <div class="hero-copy text-start">

      {{-- Eyebrow Badge --}}
      <div class="eyebrow inline-flex items-center gap-2 mb-4">
        <span class="w-2 h-2 rounded-full bg-[#2E8B83] animate-pulse"></span>
        <span>{{ __('landing.hero.eyebrow') !== 'landing.hero.eyebrow' ? __('landing.hero.eyebrow') : 'منصة إدارة المراكز التعليمية والمدرسين المتكاملة' }}</span>
      </div>

      {{-- Main Headline --}}
      <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 leading-tight mb-4 tracking-tight">
        {{ __('landing.hero.title_line1') !== 'landing.hero.title_line1' ? __('landing.hero.title_line1') : 'ادخل حصتك في ميعادها..' }}<br>
        <span class="text-[#2E8B83]">{{ __('landing.hero.title_line2') !== 'landing.hero.title_line2' ? __('landing.hero.title_line2') : 'وسيب الحضور والفلوس ومتابعة الأهالي لـ تعليمو.' }}</span>
      </h1>

      {{-- Subheading --}}
      <p class="hero-lead text-base sm:text-lg text-slate-600 font-medium leading-relaxed max-w-xl mb-6">
        {{ __('landing.hero.subtitle') !== 'landing.hero.subtitle' ? __('landing.hero.subtitle') : 'المنظومة الأسهل للمراكز التعليمية والمدرسين: مسح كود QR في ثانية، رسالة WhatsApp تطمئن ولي الأمر فوراً، وتصفية الخزينة ونسب المدرسين بضغطة زر.' }}
      </p>

      {{-- Action Buttons --}}
      <div class="hero-actions flex flex-wrap items-center gap-3 mb-6">
        <a class="btn btn-primary btn-xl font-extrabold shadow-lg shadow-teal-700/25 px-7 py-3.5 rounded-xl hover:-translate-y-0.5 transition-all" href="{{ route('register') }}" data-track="hero_primary_cta">
          {{ __('landing.hero.cta_primary') !== 'landing.hero.cta_primary' ? __('landing.hero.cta_primary') : 'ابدأ تجربة مركزك مجاناً (30 يوماً)' }} <span class="ms-1 font-bold rtl:inline-block ltr:hidden">←</span><span class="ms-1 font-bold rtl:hidden ltr:inline-block">→</span>
        </a>
        <button type="button" class="btn btn-outline btn-xl font-bold px-5 py-3.5 rounded-xl hover:bg-slate-100 transition-all text-slate-700" onclick="window.dispatchEvent(new CustomEvent('open-demo-modal'))">
          <span class="play text-[#2E8B83] ms-1">▶</span> {{ __('landing.hero.cta_secondary') !== 'landing.hero.cta_secondary' ? __('landing.hero.cta_secondary') : 'شاهد النظام أثناء العمل (90 ثانية)' }}
        </button>
      </div>

      {{-- Trust Checkmarks --}}
      <div class="trust-row flex flex-wrap gap-4 text-xs font-bold text-slate-600 pt-3 border-t border-slate-200/70">
        <span class="inline-flex items-center gap-1.5"><i class="fas fa-check-circle text-[#2E8B83]"></i> {{ __('landing.hero.check_nocard') }}</span>
        <span class="inline-flex items-center gap-1.5"><i class="fas fa-check-circle text-[#2E8B83]"></i> {{ __('landing.hero.check_setup') }}</span>
        <span class="inline-flex items-center gap-1.5"><i class="fas fa-check-circle text-[#2E8B83]"></i> {{ __('landing.hero.check_import') !== 'landing.hero.check_import' ? __('landing.hero.check_import') : 'مساعدة مجانية في نقل شيتاتك من Excel' }}</span>
      </div>

      {{-- Micro Testimonial --}}
      <div class="mt-5 flex items-start gap-3 bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm max-w-lg" data-animate="fade-in">
        <div class="w-10 h-10 rounded-xl bg-[#E6F4F3] text-[#2E8B83] flex items-center justify-center font-bold text-lg shrink-0">
          أ
        </div>
        <div>
          <p class="text-xs text-slate-700 font-bold leading-relaxed m-0">
            {{ __('landing.hero.testimonial_text') !== 'landing.hero.testimonial_text' ? __('landing.hero.testimonial_text') : '"وفّرت عليّ ساعتين يومياً في متابعة الحضور والمدفوعات. أولياء الأمور أصبحوا أكثر رضا."' }}
          </p>
          <span class="text-[10px] text-slate-500 font-semibold">
            {{ __('landing.hero.testimonial_author') !== 'landing.hero.testimonial_author' ? __('landing.hero.testimonial_author') : '— أ/ أحمد عبد العزيز، مدير مركز النور التعليمي' }}
          </span>
        </div>
      </div>
    </div>

    {{-- Right Interactive Simulator Column --}}
    <div class="hero-visual relative flex items-center justify-center min-h-[480px]">

      {{-- Subtle Ambient Glows --}}
      <div class="absolute -top-10 -right-10 w-72 h-72 bg-teal-100/60 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-10 -left-10 w-72 h-72 bg-emerald-100/50 rounded-full blur-3xl pointer-events-none"></div>

      {{-- Interactive Device & System Box --}}
      <div class="w-full max-w-md bg-white rounded-3xl p-5 shadow-2xl border border-slate-200/90 relative z-10">

        {{-- Header Bar --}}
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
          <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-rose-400"></span>
            <span class="w-3 h-3 rounded-full bg-amber-400"></span>
            <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
            <span class="text-xs font-bold text-slate-700 ms-1">{{ __('landing.hero.sim_title') !== 'landing.hero.sim_title' ? __('landing.hero.sim_title') : 'نقطة تسجيل الحضور الذكية' }}</span>
          </div>
          <span class="text-[11px] font-bold text-teal-700 bg-teal-50 px-2.5 py-0.5 rounded-full">{{ __('landing.hero.sim_live') !== 'landing.hero.sim_live' ? __('landing.hero.sim_live') : 'مباشر الآن' }}</span>
        </div>

        {{-- Simulated Student QR Card --}}
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-4 text-white shadow-md relative overflow-hidden mb-4">
          <div class="flex items-start justify-between relative z-10">
            <div>
              <span class="text-[10px] uppercase tracking-wider text-teal-400 font-bold">{{ __('landing.hero.sim_card_label') !== 'landing.hero.sim_card_label' ? __('landing.hero.sim_card_label') : 'بطاقة الطالب الإلكترونية' }}</span>
              <h4 class="text-base font-black mt-0.5" x-text="studentName"></h4>
              <p class="text-xs text-slate-300 font-medium" x-text="courseName"></p>
            </div>
            <div class="relative bg-white p-2 rounded-xl text-slate-900 flex flex-col items-center shrink-0">
              <i class="fas fa-qrcode text-3xl text-slate-900"></i>
              <span class="text-[8px] font-bold mt-1 text-slate-500">ID: #8492</span>

              {{-- Laser Line when Scanning --}}
              <div x-show="scanning"
                   class="absolute left-0 right-0 h-0.5 bg-emerald-500 shadow-[0_0_8px_#10b981] animate-bounce"
                   style="top: 50%;"></div>
            </div>
          </div>

          <div class="mt-3 pt-2.5 border-t border-slate-700/80 flex items-center justify-between text-[11px] text-slate-300">
            <span>{{ __('landing.hero.sim_subscription') !== 'landing.hero.sim_subscription' ? __('landing.hero.sim_subscription') : 'الاشتراك:' }} <strong class="text-emerald-400">{{ __('landing.hero.sim_remaining') !== 'landing.hero.sim_remaining' ? __('landing.hero.sim_remaining') : 'ساري (باقي 3 حصص)' }}</strong></span>
            <span class="text-slate-400 font-mono">2026/2027</span>
          </div>
        </div>

        {{-- Interactive Scanner Trigger --}}
        <div class="mb-4">
          <button type="button"
                  @click="scanned ? resetScan() : triggerScan()"
                  :disabled="scanning"
                  class="w-full py-2.5 px-4 rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition-all duration-200"
                  :class="scanning ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : (scanned ? 'bg-emerald-50 text-emerald-800 border border-emerald-300 hover:bg-emerald-100' : 'bg-[#2E8B83] text-white shadow-md hover:bg-[#25746D] active:scale-[0.99]')">
            <template x-if="!scanning && !scanned">
              <span class="flex items-center gap-2">
                <i class="fas fa-camera text-sm"></i>
                <span>{{ __('landing.hero.sim_scan_cta') !== 'landing.hero.sim_scan_cta' ? __('landing.hero.sim_scan_cta') : 'اضغط لتجربة مسح كود الـ QR لحظيًا' }}</span>
              </span>
            </template>
            <template x-if="scanning">
              <span class="flex items-center gap-2">
                <i class="fas fa-spinner fa-spin text-sm"></i>
                <span>{{ __('landing.hero.sim_scanning') !== 'landing.hero.sim_scanning' ? __('landing.hero.sim_scanning') : 'جارٍ قراءة الكود والتحقق من النظام...' }}</span>
              </span>
            </template>
            <template x-if="scanned">
              <span class="flex items-center gap-2">
                <i class="fas fa-redo-alt text-xs"></i>
                <span>{{ __('landing.hero.sim_success') !== 'landing.hero.sim_success' ? __('landing.hero.sim_success') : 'تم المسح بنجاح! اضغط للإعادة' }}</span>
              </span>
            </template>
          </button>
        </div>

        {{-- Live Result / Output Feedback --}}
        <div class="space-y-2.5">
          {{-- Attendance Badge Result --}}
          <div class="flex items-center justify-between p-2.5 rounded-xl border text-xs transition-all duration-300"
               :class="scanned ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-slate-50 border-slate-200/80 text-slate-500'">
            <div class="flex items-center gap-2">
              <div class="w-6 h-6 rounded-full flex items-center justify-center shrink-0"
                   :class="scanned ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-400'">
                <i class="fas" :class="scanned ? 'fa-check' : 'fa-clock'"></i>
              </div>
              <div>
                <span class="font-bold block" x-text="scanned ? '{{ __("landing.hero.sim_result_success") !== "landing.hero.sim_result_success" ? __("landing.hero.sim_result_success") : "تم تسجيل الحضور وخصم الحصة" }}' : '{{ __("landing.hero.sim_result_waiting") !== "landing.hero.sim_result_waiting" ? __("landing.hero.sim_result_waiting") : "في انتظار مسح الكود..." }}'"></span>
                <span class="text-[10px]" x-show="scanned" x-text="'{{ __("landing.hero.sim_time_label") !== "landing.hero.sim_time_label" ? __("landing.hero.sim_time_label") : "الوقت:" }} ' + timeNow + ' {{ __("landing.hero.sim_balance") !== "landing.hero.sim_balance" ? __("landing.hero.sim_balance") : "(الرصيد المتبقي: حصتان)" }}'"></span>
              </div>
            </div>
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md"
                  :class="scanned ? 'bg-emerald-200/70 text-emerald-900' : 'bg-slate-200 text-slate-600'"
                  x-text="scanned ? '{{ __("landing.hero.sim_status_present") !== "landing.hero.sim_status_present" ? __("landing.hero.sim_status_present") : "حاضر ✓" }}' : '{{ __("landing.hero.sim_status_pending") !== "landing.hero.sim_status_pending" ? __("landing.hero.sim_status_pending") : "معلق" }}'"></span>
          </div>

          {{-- Simulated WhatsApp Push Notification --}}
          <div x-show="scanned"
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="opacity-0 transform translate-y-2"
               x-transition:enter-end="opacity-100 transform translate-y-0"
               class="bg-white border-2 border-emerald-400/80 rounded-xl p-3 shadow-md text-start relative overflow-hidden">
            <div class="flex items-center justify-between mb-1">
              <div class="flex items-center gap-1.5 text-emerald-700 text-xs font-black">
                <i class="fab fa-whatsapp text-sm text-emerald-600"></i>
                <span>{{ __('landing.hero.sim_wa_title') !== 'landing.hero.sim_wa_title' ? __('landing.hero.sim_wa_title') : 'رسالة WhatsApp فورية لولي الأمر' }}</span>
              </div>
              <span class="text-[9px] text-slate-400 font-mono">{{ __('landing.hero.sim_wa_now') !== 'landing.hero.sim_wa_now' ? __('landing.hero.sim_wa_now') : 'الآن' }}</span>
            </div>
            <p class="text-xs text-slate-800 font-bold leading-relaxed m-0 bg-emerald-50/60 p-2 rounded-lg border border-emerald-100">
              {{ __('landing.hero.sim_wa_message') !== 'landing.hero.sim_wa_message' ? __('landing.hero.sim_wa_message') : '« السلام عليكم أ/ خالد، نود إعلامكم بحضور ابنكم عمر لحصة الفيزياء اليوم الساعة 04:15 م بنجاح. 📚 »' }}
            </p>
          </div>
        </div>

      </div>

    </div>

  </div>
</section>