{{-- Security & Infrastructure Trust Box --}}
<section class="section security py-16 bg-white" id="security">
  <div class="container">
    <div class="security-box bg-gradient-to-br from-teal-50/60 via-white to-emerald-50/40 border-2 border-teal-600/20 rounded-3xl p-8 lg:p-12 shadow-sm" data-animate="fade-in">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
        <div class="lg:col-span-6 text-start">
          <div class="inline-flex items-center gap-2 text-xs font-black text-[#2E8B83] bg-teal-50 px-3 py-1 rounded-full border border-teal-200 mb-3">
            <i class="fas fa-shield-alt"></i>
            <span>{{ __('landing.security.badge') !== 'landing.security.badge' ? __('landing.security.badge') : 'الأمان والخصوصية' }}</span>
          </div>
          <h2 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight mb-3">
            بيانات مركزك وطلابك <span class="text-[#2E8B83]">في أيدٍ أمينة ومشفرة</span>
          </h2>
          <p class="text-sm text-slate-600 font-medium leading-relaxed mb-0">
            نطبق أعلى معايير العزل التام لبيانات كل مؤسسة (Multi-Tenancy Isolation)، مع نسخ احتياطي تلقائي مشفر وحماية من الاختراق على مدار الساعة.
          </p>
        </div>
        
        <div class="lg:col-span-6 grid grid-cols-1 sm:grid-cols-2 gap-3.5">
          <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base shrink-0 font-bold">
              <i class="fas fa-user-shield"></i>
            </div>
            <div>
              <strong class="text-xs font-black text-slate-900 block">صلاحيات دقيقة للأدوار</strong>
              <span class="text-[11px] text-slate-500 font-medium">كل موظف يرى ما يخصه فقط</span>
            </div>
          </div>

          <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-teal-50 text-[#2E8B83] flex items-center justify-center text-base shrink-0 font-bold">
              <i class="fas fa-database"></i>
            </div>
            <div>
              <strong class="text-xs font-black text-slate-900 block">عزل بيانات تام</strong>
              <span class="text-[11px] text-slate-500 font-medium">قاعدة بيانات معزولة ومحمية</span>
            </div>
          </div>

          <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-base shrink-0 font-bold">
              <i class="fas fa-cloud-download-alt"></i>
            </div>
            <div>
              <strong class="text-xs font-black text-slate-900 block">نسخ احتياطي يومي آلي</strong>
              <span class="text-[11px] text-slate-500 font-medium">حفظ مشفر في سحابة مستقلة</span>
            </div>
          </div>

          <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-base shrink-0 font-bold">
              <i class="fas fa-lock"></i>
            </div>
            <div>
              <strong class="text-xs font-black text-slate-900 block">تشفير SSL 256-bit</strong>
              <span class="text-[11px] text-slate-500 font-medium">حماية كاملة أثناء النقل</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- FAQ Section with Professional Accordion --}}
<section class="section faq py-20 bg-slate-50 border-t border-slate-200" id="faq">
  <div class="container">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
      
      {{-- FAQ Left Info Column --}}
      <div class="lg:col-span-5 text-start" data-animate="fade-in">
        <div class="inline-flex items-center gap-2 text-xs font-black text-[#2E8B83] bg-teal-50 px-3.5 py-1.5 rounded-full border border-teal-200 mb-3 shadow-xs">
          <i class="fas fa-question-circle"></i>
          <span>{{ __('landing.faq.badge') !== 'landing.faq.badge' ? __('landing.faq.badge') : 'الأسئلة الشائعة' }}</span>
        </div>
        <h2 class="text-3xl font-black text-slate-900 leading-tight mb-4">
          كل ما تحتاج معرفته <br><span class="text-[#2E8B83]">قبل أن تبدأ معنا</span>
        </h2>
        <p class="text-sm text-slate-600 font-medium leading-relaxed mb-6">
          هل لديك استفسار محدد أو ترغب في استشارة مخصصة لتشغيل مركزك؟ فريقنا جاهز للتواصل معك ومساعدتك خطوة بخطوة.
        </p>
        
        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-sm">
          <h4 class="text-sm font-black text-slate-900 mb-2">تريد تجربة حية مع خبير؟</h4>
          <p class="text-xs text-slate-500 mb-4 leading-relaxed">احجز جلسة عرض توضيحي (Demo) مدتها 15 دقيقة نريك فيها كيف يعمل النظام عملياً على شاشتك.</p>
          <button type="button" 
                  class="btn btn-outline w-full font-bold text-xs" 
                  @click.prevent="$dispatch('open-demo-modal')">
            <i class="fas fa-play text-teal-600 ms-1"></i> احجز عرضًا توضيحيًا مجانيًا
          </button>
        </div>
      </div>

      {{-- FAQ Right Accordion List --}}
      <div class="lg:col-span-7 space-y-3" data-animate="fade-in">
        <details class="group bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs transition-all duration-200" open>
          <summary class="cursor-pointer font-black text-sm text-slate-900 flex justify-between items-center list-none select-none">
            <span>هل أحتاج إلى بطاقة بنكية للتسجيل وبدء التجربة؟</span>
            <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 group-open:bg-[#2E8B83] group-open:text-white flex items-center justify-center text-xs transition-colors">+</span>
          </summary>
          <p class="mt-3 text-xs font-semibold text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
            لا، يمكنك البدء فوراً بتجربة مجانية كاملة الميزات لمدة 30 يوماً دون إدخال أي بيانات دفع، ولن يتم خصم أي مبالغ منك مطلقاً.
          </p>
        </details>

        <details class="group bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs transition-all duration-200">
          <summary class="cursor-pointer font-black text-sm text-slate-900 flex justify-between items-center list-none select-none">
            <span>هل يعمل نظام حضور QR من خلال هاتف المدرس أو موظف الاستقبال؟</span>
            <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 group-open:bg-[#2E8B83] group-open:text-white flex items-center justify-center text-xs transition-colors">+</span>
          </summary>
          <p class="mt-3 text-xs font-semibold text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
            نعم، النظام متوافق تماماً مع كاميرا أي هاتف ذكي أو تابلت أو قارئ باركود USB للكمبيوتر. يكفي فتح الكاميرا وتمرير كود الطالب.
          </p>
        </details>

        <details class="group bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs transition-all duration-200">
          <summary class="cursor-pointer font-black text-sm text-slate-900 flex justify-between items-center list-none select-none">
            <span>لدي بيانات طلاب قديمة في شيت Excel، هل يمكن استيرادها؟</span>
            <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 group-open:bg-[#2E8B83] group-open:text-white flex items-center justify-center text-xs transition-colors">+</span>
          </summary>
          <p class="mt-3 text-xs font-semibold text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
            بالتأكيد! يوفر النظام أداة استيراد سريعة لملفات Excel بضغطة زر مع إنشاء أكواد QR لجميع الطلاب تلقائياً دفعة واحدة، كما يساعدك فريق الدعم مجاناً في نقل بياناتك.
          </p>
        </details>

        <details class="group bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs transition-all duration-200">
          <summary class="cursor-pointer font-black text-sm text-slate-900 flex justify-between items-center list-none select-none">
            <span>كيف يتم إرسال رسائل الواتساب لأولياء الأمور؟</span>
            <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 group-open:bg-[#2E8B83] group-open:text-white flex items-center justify-center text-xs transition-colors">+</span>
          </summary>
          <p class="mt-3 text-xs font-semibold text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
            المنصة ترتبط مباشرة بواجهة WhatsApp Cloud API الرسمية، مما يضمن وصول الإشعار في ثانية واحدة دون أي احتمالية لحظر الرقم، وبشكل آلي تماماً فور مسح كود الطالب أو تسجيل الدفعة.
          </p>
        </details>

        <details class="group bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs transition-all duration-200">
          <summary class="cursor-pointer font-black text-sm text-slate-900 flex justify-between items-center list-none select-none">
            <span>هل يمكنني إلغاء أو ترقية خطتي في أي وقت؟</span>
            <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 group-open:bg-[#2E8B83] group-open:text-white flex items-center justify-center text-xs transition-colors">+</span>
          </summary>
          <p class="mt-3 text-xs font-semibold text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
            نعم وبكل سهولة من لوحة التحكم الخاصة بك. يمكنك الترقية لاستيعاب طلاب إضافيين أو تغيير دورة الدفع دون أي قيود أو شروط جزائية.
          </p>
        </details>
      </div>

    </div>
  </div>
</section>
