{{-- Features — Asymmetric Bento Grid — Professional --}}
<section class="section features" id="features">
  <div class="container">
    <div class="section-head center" data-animate="fade-in">
      <span class="kicker">{{ __('landing.bento.badge') }}</span>
      <h2>{{ __('landing.bento.title') !== 'landing.bento.title' ? __('landing.bento.title') : 'أهم 6 ميزات تشغيلية' }} <span>{{ __('landing.bento.title_highlight') !== 'landing.bento.title_highlight' ? __('landing.bento.title_highlight') : 'في منصة Taalimu' }}</span></h2>
      <p>{{ __('landing.bento.subtitle') !== 'landing.bento.subtitle' ? __('landing.bento.subtitle') : 'صُممت بعناية لتغطية الدورة العملياتية للمراكز التعليمية بدون تعقيد ERP القديم.' }}</p>
    </div>

    {{-- Bento Grid: 2 large (top) + 4 small (bottom) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" data-animate="fade-in">

      {{-- Feature 1 — Large: QR Attendance (spans 2 cols on lg) --}}
      <article class="lg:col-span-2 bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-6 sm:p-8 text-white relative overflow-hidden group transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
        <div class="absolute top-0 end-0 w-48 h-48 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-11 h-11 rounded-xl bg-teal-500/20 text-teal-400 flex items-center justify-center text-lg group-hover:bg-teal-500 group-hover:text-white transition-colors">
              <i class="fas fa-qrcode"></i>
            </div>
            <span class="text-[10px] font-bold text-teal-400 bg-teal-500/10 px-2.5 py-1 rounded-full uppercase tracking-wider">{{ __('landing.bento.featured_label') !== 'landing.bento.featured_label' ? __('landing.bento.featured_label') : 'الميزة الأبرز' }}</span>
          </div>
          <h3 class="text-xl sm:text-2xl font-black text-white mb-2">{{ __('landing.bento.qr_title') !== 'landing.bento.qr_title' ? __('landing.bento.qr_title') : 'حضور سريع بـ QR Code' }}</h3>
          <p class="text-sm text-slate-300 leading-relaxed max-w-lg mb-4">{{ __('landing.bento.qr_desc') }}</p>
          <div class="flex flex-wrap gap-2">
            <span class="text-[10px] font-bold bg-white/10 text-slate-200 px-2.5 py-1 rounded-lg border border-white/10">{{ __('landing.bento.qr_tag1') !== 'landing.bento.qr_tag1' ? __('landing.bento.qr_tag1') : 'أقل من ثانية' }}</span>
            <span class="text-[10px] font-bold bg-white/10 text-slate-200 px-2.5 py-1 rounded-lg border border-white/10">{{ __('landing.bento.qr_tag2') !== 'landing.bento.qr_tag2' ? __('landing.bento.qr_tag2') : 'يعمل بدون إنترنت' }}</span>
            <span class="text-[10px] font-bold bg-white/10 text-slate-200 px-2.5 py-1 rounded-lg border border-white/10">{{ __('landing.bento.qr_tag3') !== 'landing.bento.qr_tag3' ? __('landing.bento.qr_tag3') : 'خصم حصص تلقائي' }}</span>
          </div>
        </div>
      </article>

      {{-- Feature 2 — Small: Student Management --}}
      <article class="bg-white rounded-2xl p-6 border border-slate-200/80 relative group transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-teal-200">
        <b class="absolute top-4 end-4 text-[10px] text-slate-300 font-bold">01</b>
        <div class="w-11 h-11 rounded-xl bg-[#E6F4F3] text-[#2E8B83] flex items-center justify-center text-lg mb-4 group-hover:bg-[#2E8B83] group-hover:text-white transition-colors">
          <i class="fas fa-users-cog"></i>
        </div>
        <h3 class="text-[15px] font-black text-slate-900 mb-1.5">{{ __('landing.bento.student_mgmt_title') !== 'landing.bento.student_mgmt_title' ? __('landing.bento.student_mgmt_title') : 'إدارة الطلاب والمجموعات' }}</h3>
        <p class="text-[11px] text-slate-500 leading-relaxed m-0">{{ __('landing.bento.student_mgmt_desc') }}</p>
      </article>

      {{-- Feature 3 — Small: WhatsApp --}}
      <article class="bg-gradient-to-br from-emerald-50 to-white rounded-2xl p-6 border border-emerald-200/60 relative group transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
        <b class="absolute top-4 end-4 text-[10px] text-emerald-300 font-bold">02</b>
        <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
          <i class="fab fa-whatsapp"></i>
        </div>
        <h3 class="text-[15px] font-black text-slate-900 mb-1.5">{{ __('landing.bento.whatsapp_title') !== 'landing.bento.whatsapp_title' ? __('landing.bento.whatsapp_title') : 'إشعارات WhatsApp للأهل' }}</h3>
        <p class="text-[11px] text-slate-500 leading-relaxed m-0">{{ __('landing.bento.whatsapp_desc') }}</p>
      </article>

      {{-- Feature 4 — Small: Finance & POS --}}
      <article class="bg-white rounded-2xl p-6 border border-slate-200/80 relative group transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-teal-200">
        <b class="absolute top-4 end-4 text-[10px] text-slate-300 font-bold">03</b>
        <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg mb-4 group-hover:bg-amber-500 group-hover:text-white transition-colors">
          <i class="fas fa-cash-register"></i>
        </div>
        <h3 class="text-[15px] font-black text-slate-900 mb-1.5">{{ __('landing.bento.finance_title') !== 'landing.bento.finance_title' ? __('landing.bento.finance_title') : 'المالية ونقطة البيع (POS)' }}</h3>
        <p class="text-[11px] text-slate-500 leading-relaxed m-0">{{ __('landing.bento.finance_desc') }}</p>
      </article>

      {{-- Feature 5 — Large: Teacher Management (spans 2 cols on lg) --}}
      <article class="lg:col-span-2 bg-gradient-to-br from-[#E6F4F3] to-white rounded-2xl p-6 sm:p-8 border border-teal-200/60 relative group transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
          <div class="flex-1">
            <div class="flex items-center gap-3 mb-3">
              <div class="w-11 h-11 rounded-xl bg-[#2E8B83] text-white flex items-center justify-center text-lg">
                <i class="fas fa-user-tie"></i>
              </div>
              <span class="text-[10px] font-bold text-[#2E8B83] bg-teal-100 px-2.5 py-1 rounded-full">{{ __('landing.bento.teacher_badge') !== 'landing.bento.teacher_badge' ? __('landing.bento.teacher_badge') : 'تقارير آلية' }}</span>
            </div>
            <h3 class="text-xl font-black text-slate-900 mb-2">{{ __('landing.bento.teacher_title') !== 'landing.bento.teacher_title' ? __('landing.bento.teacher_title') : 'إدارة المدرسين وأرباحهم' }}</h3>
            <p class="text-sm text-slate-600 leading-relaxed m-0 max-w-lg">{{ __('landing.bento.teacher_desc') !== 'landing.bento.teacher_desc' ? __('landing.bento.teacher_desc') : 'حساب تلقائي دقيق لعمولات المدرسين ونسبهم وحصصهم الشهريّة. تصفية الحسابات بضغطة واحدة.' }}</p>
          </div>
          <div class="hidden sm:block w-48 h-32 bg-white rounded-xl border border-teal-100 p-3 shadow-sm">
            <div class="text-[10px] font-bold text-slate-500 mb-2">{{ __('landing.bento.teacher_report_label') !== 'landing.bento.teacher_report_label' ? __('landing.bento.teacher_report_label') : 'مثال: تقرير شهري' }}</div>
            <div class="space-y-1.5">
              <div class="flex justify-between text-[10px]">
                <span class="text-slate-600 font-bold">{{ __('landing.bento.teacher_example_name') !== 'landing.bento.teacher_example_name' ? __('landing.bento.teacher_example_name') : 'أ/ أحمد' }}</span>
                <span class="text-emerald-700 font-bold font-mono">18,400 {{ __('landing.bento.currency') !== 'landing.bento.currency' ? __('landing.bento.currency') : 'ج.م' }}</span>
              </div>
              <div class="h-1.5 bg-emerald-100 rounded-full"><div class="h-full bg-emerald-500 rounded-full" style="width: 78%"></div></div>
              <div class="flex justify-between text-[10px]">
                <span class="text-slate-600 font-bold">{{ __('landing.bento.teacher_example_name2') !== 'landing.bento.teacher_example_name2' ? __('landing.bento.teacher_example_name2') : 'أ/ سارة' }}</span>
                <span class="text-emerald-700 font-bold font-mono">12,150 {{ __('landing.bento.currency') !== 'landing.bento.currency' ? __('landing.bento.currency') : 'ج.م' }}</span>
              </div>
              <div class="h-1.5 bg-emerald-100 rounded-full"><div class="h-full bg-emerald-500 rounded-full" style="width: 52%"></div></div>
            </div>
          </div>
        </div>
      </article>

      {{-- Feature 6 — Small: Reports --}}
      <article class="bg-white rounded-2xl p-6 border border-slate-200/80 relative group transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-teal-200">
        <b class="absolute top-4 end-4 text-[10px] text-slate-300 font-bold">04</b>
        <div class="w-11 h-11 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-lg mb-4 group-hover:bg-sky-500 group-hover:text-white transition-colors">
          <i class="fas fa-chart-line"></i>
        </div>
        <h3 class="text-[15px] font-black text-slate-900 mb-1.5">{{ __('landing.bento.reports_title') !== 'landing.bento.reports_title' ? __('landing.bento.reports_title') : 'تقارير الإيرادات والمصروفات' }}</h3>
        <p class="text-[11px] text-slate-500 leading-relaxed m-0">{{ __('landing.bento.reports_desc') }}</p>
      </article>

    </div>
  </div>
</section>