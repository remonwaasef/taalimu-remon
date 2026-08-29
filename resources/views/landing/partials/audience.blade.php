{{-- Audience Section — Interactive Segmented Target Experience --}}
<section class="py-20 lg:py-28 bg-white" id="audience" x-data="{ selectedAudience: 'center' }">
  <div class="container mx-auto px-4 lg:px-12 max-w-6xl">
    
    <div class="text-center max-w-2xl mx-auto mb-10">
      <span class="inline-block text-xs font-black text-[#2E8B83] bg-teal-50 px-3 py-1 rounded-full border border-teal-200 mb-3">
        حلول مخصصة لطبيعة عملك
      </span>
      <h2 class="text-3xl sm:text-4xl font-black text-slate-900 leading-tight mb-3">
        صُمم خصيصاً ليناسب <span class="text-[#2E8B83]">حجم إدارتك</span>
      </h2>
      <p class="text-sm sm:text-base text-slate-600 font-medium">
        سواء كنت تدير مركزاً تعليمياً متعدد الفروع والموظفين، أو مدرساً مستقلاً تدير مجموعاتك الخاصة، Taalimu تمنحك التجربة المثالية.
      </p>
    </div>

    {{-- Interactive Audience Switcher Tabs --}}
    <div class="flex justify-center mb-10">
      <div class="inline-flex p-1.5 rounded-2xl bg-slate-100 border border-slate-200 shadow-inner">
        <button type="button" 
                @click="selectedAudience = 'center'"
                :class="selectedAudience === 'center' ? 'bg-[#2E8B83] text-white shadow-md' : 'text-slate-700 hover:text-slate-900'"
                class="flex items-center gap-2.5 px-6 py-3 rounded-xl font-bold text-sm transition-all duration-200">
          <i class="fas fa-building text-base"></i>
          <span>أصحاب المراكز والسناتر التعليمية</span>
        </button>
        <button type="button" 
                @click="selectedAudience = 'teacher'"
                :class="selectedAudience === 'teacher' ? 'bg-[#2E8B83] text-white shadow-md' : 'text-slate-700 hover:text-slate-900'"
                class="flex items-center gap-2.5 px-6 py-3 rounded-xl font-bold text-sm transition-all duration-200">
          <i class="fas fa-chalkboard-teacher text-base"></i>
          <span>المدرسين والمحاضرين المستقلين</span>
        </button>
      </div>
    </div>

    {{-- Panel: Center Owner --}}
    <div x-show="selectedAudience === 'center'" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="bg-gradient-to-br from-slate-50 to-teal-50/40 rounded-3xl p-8 lg:p-12 border-2 border-teal-600/20 shadow-xl">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
        <div class="lg:col-span-7 text-start">
          <div class="inline-flex items-center gap-2 text-xs font-black text-teal-800 bg-teal-100/80 px-3 py-1 rounded-md mb-4">
            <i class="fas fa-cubes"></i>
            <span>إدارة شاملة للمنشآت والمقرات</span>
          </div>
          <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mb-4">
            التحكم الكامل في الفروع، القاعات، والموظفين من شاشة واحدة
          </h3>
          <p class="text-slate-600 font-medium text-sm leading-relaxed mb-6">
            تخلص من الصداع الإداري وحسابات نهاية الشهر. وزّع نسب المدرسين تلقائياً، حدد صلاحيات موظفي الاستقبال، وراقب إيرادات ومصروفات كل فرع وقاعة بالقرش.
          </p>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
            <div class="flex items-center gap-2.5 text-xs font-bold text-slate-800 bg-white p-3 rounded-xl border border-slate-200/80 shadow-2xs">
              <i class="fas fa-check-circle text-[#2E8B83]"></i>
              <span>حساب نسب وأرباح المدرسين والمساعدين آلياً</span>
            </div>
            <div class="flex items-center gap-2.5 text-xs font-bold text-slate-800 bg-white p-3 rounded-xl border border-slate-200/80 shadow-2xs">
              <i class="fas fa-check-circle text-[#2E8B83]"></i>
              <span>إدارة الفروع وتوزيع القاعات والجداول</span>
            </div>
            <div class="flex items-center gap-2.5 text-xs font-bold text-slate-800 bg-white p-3 rounded-xl border border-slate-200/80 shadow-2xs">
              <i class="fas fa-check-circle text-[#2E8B83]"></i>
              <span>نقطة بيع مذكرات ومستلزمات (POS) سريعة</span>
            </div>
            <div class="flex items-center gap-2.5 text-xs font-bold text-slate-800 bg-white p-3 rounded-xl border border-slate-200/80 shadow-2xs">
              <i class="fas fa-check-circle text-[#2E8B83]"></i>
              <span>صلاحيات دقيقة (استقبال، أمن، حسابات)</span>
            </div>
          </div>

          <a href="{{ route('register', ['type' => 'center']) }}" class="btn btn-primary btn-xl font-bold shadow-lg shadow-teal-700/20">
            ابدأ تجربة المركز التعليمي الآن ←
          </a>
        </div>

        <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-200 shadow-md">
          <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <span class="text-xs font-black text-slate-900">تقرير الإيراد ونسب المدرسين</span>
            <span class="text-[10px] bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded">نهاية الشهر</span>
          </div>
          <div class="space-y-3 text-xs">
            <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded-xl">
              <div>
                <strong class="text-slate-900 block font-bold">أ/ أحمد عبد العزيز (كيمياء)</strong>
                <span class="text-[10px] text-slate-500">142 طالباً • نسبة المركز 25%</span>
              </div>
              <span class="font-mono font-bold text-emerald-700">18,400 ج.م</span>
            </div>
            <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded-xl">
              <div>
                <strong class="text-slate-900 block font-bold">أ/ سارة محمود (لغة عربية)</strong>
                <span class="text-[10px] text-slate-500">98 طالباً • نسبة المركز 20%</span>
              </div>
              <span class="font-mono font-bold text-emerald-700">12,150 ج.م</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Panel: Teacher --}}
    <div x-show="selectedAudience === 'teacher'" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="bg-gradient-to-br from-slate-50 to-emerald-50/40 rounded-3xl p-8 lg:p-12 border-2 border-emerald-600/20 shadow-xl"
         style="display: none;">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
        <div class="lg:col-span-7 text-start">
          <div class="inline-flex items-center gap-2 text-xs font-black text-emerald-800 bg-emerald-100/80 px-3 py-1 rounded-md mb-4">
            <i class="fas fa-user-graduate"></i>
            <span>حل مرن للمدرس والمحاضر المستقل</span>
          </div>
          <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mb-4">
            ركّز في شرحك، ودع Taalimu تدير طلابك وحضورك وأموالك
          </h3>
          <p class="text-slate-600 font-medium text-sm leading-relaxed mb-6">
            سواء كنت تعطي دروسك في قاعات خارجية أو أونلاين، Taalimu تتيح لك طباعة كروت QR لطلابك، تسجيل الغياب فورياً، وإرسال تنبيهات المذكرات والواجبات لأولياء الأمور تلقائياً.
          </p>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
            <div class="flex items-center gap-2.5 text-xs font-bold text-slate-800 bg-white p-3 rounded-xl border border-slate-200/80 shadow-2xs">
              <i class="fas fa-check-circle text-emerald-600"></i>
              <span>حضور وغياب مجموعاتك بكود QR من هاتفك</span>
            </div>
            <div class="flex items-center gap-2.5 text-xs font-bold text-slate-800 bg-white p-3 rounded-xl border border-slate-200/80 shadow-2xs">
              <i class="fas fa-check-circle text-emerald-600"></i>
              <span>متابعة اشتراكات الطلاب والمتبقي بدون حرج</span>
            </div>
            <div class="flex items-center gap-2.5 text-xs font-bold text-slate-800 bg-white p-3 rounded-xl border border-slate-200/80 shadow-2xs">
              <i class="fas fa-check-circle text-emerald-600"></i>
              <span>إرسال درجات الاختبارات الدورية لأولياء الأمور</span>
            </div>
            <div class="flex items-center gap-2.5 text-xs font-bold text-slate-800 bg-white p-3 rounded-xl border border-slate-200/80 shadow-2xs">
              <i class="fas fa-check-circle text-emerald-600"></i>
              <span>تسليم واستلام المذكرات والواجبات بسهولة</span>
            </div>
          </div>

          <a href="{{ route('register', ['type' => 'instructor']) }}" class="btn btn-primary btn-xl font-bold shadow-lg shadow-emerald-700/20 bg-emerald-600 border-emerald-600 hover:bg-emerald-700">
            ابدأ كـ مدرس مستقل الآن ←
          </a>
        </div>

        <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-200 shadow-md">
          <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <span class="text-xs font-black text-slate-900">مجموعات اليوم (الأحد)</span>
            <span class="text-[10px] bg-teal-100 text-teal-800 font-bold px-2 py-0.5 rounded">مجموعتان</span>
          </div>
          <div class="space-y-3 text-xs">
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
              <div class="flex justify-between font-bold text-slate-900 mb-1">
                <span>مجموعة ثانوية عامة (أ)</span>
                <span class="text-emerald-600">حضر 28 / 30</span>
              </div>
              <p class="text-[10px] text-slate-500 m-0">الساعة 04:00 م • تم إشعار أولياء الأمور تلقائياً</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
              <div class="flex justify-between font-bold text-slate-900 mb-1">
                <span>مجموعة لغات (ب)</span>
                <span class="text-slate-500">تبدأ 06:30 م</span>
              </div>
              <p class="text-[10px] text-slate-500 m-0">22 طالباً مسجلاً • جاهز للمسح بـ QR</p>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>