{{-- Final High-Converting Call To Action Section --}}
<section class="final-cta py-16 bg-white" id="start">
  <div class="container">
    <div class="final-box bg-gradient-to-br from-slate-900 via-slate-800 to-teal-950 text-white rounded-3xl p-8 sm:p-14 shadow-2xl relative overflow-hidden border border-slate-700/80" data-animate="scale-in">
      
      {{-- Glowing Accent --}}
      <div class="absolute top-0 end-0 w-96 h-96 bg-teal-500/20 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute bottom-0 start-0 w-64 h-64 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

      <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
        <div class="lg:col-span-8 text-start">
          <span class="inline-flex items-center gap-2 text-xs font-black text-teal-400 bg-teal-500/10 border border-teal-500/20 px-3.5 py-1.5 rounded-full mb-4">
            <i class="fas fa-rocket"></i>
            <span>{{ __('landing.cta.badge') !== 'landing.cta.badge' ? __('landing.cta.badge') : 'جاهز لنقلة حقيقية في إدارة مركزك؟' }}</span>
          </span>
          <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white leading-tight mb-4 tracking-tight">
            ابدأ تجربتك المجانية الكاملة اليوم<br>
            <span class="text-teal-400">وشاهد الفارق من أول حصة.</span>
          </h2>
          <p class="text-sm sm:text-base text-slate-300 font-medium leading-relaxed max-w-2xl mb-0">
            انضم لمئات المراكز والمدرسين الذين وفروا ساعات من الجهد اليومي وأراحوا أولياء الأمور وحموا أموالهم من التسريب. بدون بطاقة بنكية وبدون أي التزامات.
          </p>
        </div>

        <div class="lg:col-span-4 flex flex-col sm:flex-row lg:flex-col gap-3 justify-center">
          <a class="btn btn-primary btn-xl font-black text-center shadow-xl shadow-teal-500/30 flex items-center justify-center gap-2 text-sm" 
             href="{{ route('register') }}" 
             data-track="cta_primary">
            <span>ابدأ مجانًا الآن (30 يوماً)</span>
            <i class="fas fa-arrow-left text-xs"></i>
          </a>
          <button type="button" 
                  class="btn btn-outline btn-xl font-bold text-center text-white border-slate-600 hover:bg-white/10 text-xs flex items-center justify-center gap-2" 
                  onclick="window.dispatchEvent(new CustomEvent('open-demo-modal'))">
            <i class="fas fa-play text-teal-400"></i>
            <span>طلب جلسة عرض تجريبي</span>
          </button>
          <div class="text-center text-[11px] text-slate-400 font-bold pt-2">
            ✓ تفعيل فوري للحساب • استيراد مجاني للبيانات
          </div>
        </div>
      </div>

    </div>
  </div>
</section>