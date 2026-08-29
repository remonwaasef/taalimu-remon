{{-- Hero — Interactive Outcome-Driven Experience --}}
<section class="hero relative overflow-hidden pt-8 pb-16 lg:pt-14 lg:pb-24" x-data="{
    scanned: false,
    scanning: false,
    scanSuccess: false,
    studentName: 'عمر خالد المنصوري',
    courseName: 'الفيزياء المتقدمة - قاعة 3',
    timeNow: 'الآن 04:15 م',
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
      <div class="eyebrow inline-flex items-center gap-2 mb-4">
        <span class="w-2 h-2 rounded-full bg-[#2E8B83] animate-pulse"></span>
        <span>منصة إدارة المراكز التعليمية والمدرسين المتكاملة</span>
      </div>

      <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 leading-tight mb-4 tracking-tight">
        ودّع الدفاتر وExcel<br>
        <span class="text-[#2E8B83]">ومتابعة أولياء الأمور يدويًا.</span>
      </h1>

      <p class="hero-lead text-base sm:text-lg text-slate-600 font-medium leading-relaxed max-w-xl mb-6">
        Taalimu تمنحك السيطرة الكاملة على مركزك التعليمي: تسجيل الطلاب بكود <strong>QR</strong> في ثوانٍ، رصد الحضور لحظياً، تنظيم الأقساط والمدفوعات، وإشعار أولياء الأمور تلقائياً عبر <strong>WhatsApp</strong>.
      </p>

      <div class="hero-actions flex flex-wrap items-center gap-3 mb-6">
        <a class="btn btn-primary btn-xl font-bold shadow-lg shadow-teal-700/20" href="{{ route('register') }}" data-track="hero_primary_cta">
          {{ __('landing.pricing.cta_free') }} <span class="ms-1 font-bold">←</span>
        </a>
        <button type="button" class="btn btn-outline btn-xl font-bold" onclick="window.dispatchEvent(new CustomEvent('open-demo-modal'))">
          <span class="play text-teal-600 ms-1">▶</span> {{ __('landing.hero.cta_secondary') }}
        </button>
      </div>

      <div class="trust-row flex flex-wrap gap-4 text-xs font-semibold text-slate-600 pt-2 border-t border-slate-200/70">
        <span class="inline-flex items-center gap-1.5"><i class="fas fa-check-circle text-[#2E8B83]"></i> {{ __('landing.hero.check_nocard') }}</span>
        <span class="inline-flex items-center gap-1.5"><i class="fas fa-check-circle text-[#2E8B83]"></i> {{ __('landing.hero.check_setup') }}</span>
        <span class="inline-flex items-center gap-1.5"><i class="fas fa-check-circle text-[#2E8B83]"></i> استيراد مجاني لبياناتك من Excel</span>
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
            <span class="text-xs font-bold text-slate-700 ms-1">نقطة تسجيل الحضور الذكية</span>
          </div>
          <span class="text-[11px] font-bold text-teal-700 bg-teal-50 px-2.5 py-0.5 rounded-full">مباشر الآن</span>
        </div>

        {{-- Simulated Student QR Card --}}
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-4 text-white shadow-md relative overflow-hidden mb-4">
          <div class="flex items-start justify-between relative z-10">
            <div>
              <span class="text-[10px] uppercase tracking-wider text-teal-400 font-bold">بطاقة الطالب الإلكترونية</span>
              <h4 class="text-base font-black mt-0.5" x-text="studentName">عمر خالد المنصوري</h4>
              <p class="text-xs text-slate-300 font-medium" x-text="courseName">الفيزياء المتقدمة - قاعة 3</p>
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
            <span>الاشتراك: <strong class="text-emerald-400">ساري (باقي 3 حصص)</strong></span>
            <span class="text-slate-400 font-mono">2026/2027</span>
          </div>
        </div>

        {{-- Interactive Scanner Trigger --}}
        <div class="mb-4">
          <button type="button" 
                  @click="triggerScan()"
                  :disabled="scanning"
                  class="w-full py-2.5 px-4 rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition-all duration-200"
                  :class="scanning ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : (scanned ? 'bg-emerald-50 text-emerald-800 border border-emerald-300 hover:bg-emerald-100' : 'bg-[#2E8B83] text-white shadow-md hover:bg-[#25746D] active:scale-[0.99]')">
            <template x-if="!scanning && !scanned">
              <span class="flex items-center gap-2">
                <i class="fas fa-camera text-sm"></i>
                <span>اضغط لتجربة مسح كود الـ QR لحظيًا</span>
              </span>
            </template>
            <template x-if="scanning">
              <span class="flex items-center gap-2">
                <i class="fas fa-spinner fa-spin text-sm"></i>
                <span>جارٍ قراءة الكود والتحقق من النظام...</span>
              </span>
            </template>
            <template x-if="scanned">
              <span class="flex items-center gap-2">
                <i class="fas fa-redo-alt text-xs"></i>
                <span>تم المسح بنجاح! اضغط للإعادة</span>
              </span>
            </template>
          </button>
        </div>

        {{-- Live Result / Output Feedback (Shows immediately after scan) --}}
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
                <span class="font-bold block" x-text="scanned ? 'تم تسجيل الحضور وخصم الحصة' : 'في انتظار مسح الكود...'">في انتظار مسح الكود...</span>
                <span class="text-[10px]" x-show="scanned" x-text="'الوقت: ' + timeNow + ' (الرصيد المتبقي: حصتان)'"></span>
              </div>
            </div>
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md"
                  :class="scanned ? 'bg-emerald-200/70 text-emerald-900' : 'bg-slate-200 text-slate-600'"
                  x-text="scanned ? 'حاضر ✓' : 'معلق'">معلق</span>
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
                <span>رسالة WhatsApp فورية لولي الأمر</span>
              </div>
              <span class="text-[9px] text-slate-400 font-mono">الآن</span>
            </div>
            <p class="text-xs text-slate-800 font-bold leading-relaxed m-0 bg-emerald-50/60 p-2 rounded-lg border border-emerald-100">
              « السلام عليكم أ/ خالد، نود إعلامكم بحضور ابنكم <span class="text-[#2E8B83] font-black">عمر</span> لحصة الفيزياء اليوم الساعة 04:15 م بنجاح. 📚 »
            </p>
          </div>
        </div>

      </div>

    </div>

  </div>
</section>