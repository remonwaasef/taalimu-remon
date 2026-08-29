{{-- Trust & Social Proof Strip — Enhanced with animated stats --}}
<section class="proof border-y border-slate-200 bg-white py-5" x-data="{
    counters: { centers: 0, students: 0, scans: 0 },
    targets: { centers: 200, students: 15000, scans: 850000 },
    animated: false,
    startCounters() {
        if (this.animated) return;
        this.animated = true;
        const duration = 1800;
        const steps = 40;
        const interval = duration / steps;
        let step = 0;
        const timer = setInterval(() => {
            step++;
            const progress = step / steps;
            const ease = 1 - Math.pow(1 - progress, 3);
            this.counters.centers = Math.round(this.targets.centers * ease);
            this.counters.students = Math.round(this.targets.students * ease);
            this.counters.scans = Math.round(this.targets.scans * ease);
            if (step >= steps) clearInterval(timer);
        }, interval);
    }
}" x-intersect.once="startCounters()">
  <div class="container">

    {{-- Stats Row --}}
    <div class="flex flex-wrap items-center justify-between gap-6">

      {{-- Left: Main Trust Message --}}
      <div class="flex items-center gap-3">
        <span class="inline-flex items-center gap-2 text-xs font-black text-[#2E8B83] bg-teal-50 px-3.5 py-1.5 rounded-full border border-teal-200 shadow-sm">
          🚀 {{ __('landing.trust.badge') !== 'landing.trust.badge' ? __('landing.trust.badge') : 'انضمام فوري' }}
        </span>
        <span class="text-sm font-bold text-slate-800 hidden sm:inline">
          {{ __('landing.trust.headline') !== 'landing.trust.headline' ? __('landing.trust.headline') : 'انضم لأكثر من' }}
          <strong class="text-[#2E8B83]" x-text="counters.centers.toLocaleString('ar-EG') + '+'">200+</strong>
          {{ __('landing.trust.headline_suffix') !== 'landing.trust.headline_suffix' ? __('landing.trust.headline_suffix') : 'مركز تعليمي ومدرس يعتمدون على Taalimu' }}
        </span>
      </div>

      {{-- Right: Quick Stats + Benefits --}}
      <div class="flex flex-wrap items-center gap-4 text-xs font-bold text-slate-600">
        <span class="inline-flex items-center gap-1.5">
          <i class="fas fa-user-graduate text-[#2E8B83]"></i>
          <span x-text="counters.students.toLocaleString('ar-EG')">15,000</span>
          {{ __('landing.trust.students') !== 'landing.trust.students' ? __('landing.trust.students') : 'طالب مسجّل' }}
        </span>
        <span class="text-slate-300 hidden sm:inline">•</span>
        <span class="inline-flex items-center gap-1.5">
          <i class="fas fa-gift text-[#2E8B83]"></i>
          {{ __('landing.trust.free_trial') !== 'landing.trust.free_trial' ? __('landing.trust.free_trial') : 'تجربة كاملة مجاناً 30 يوماً' }}
        </span>
        <span class="text-slate-300 hidden sm:inline">•</span>
        <span class="inline-flex items-center gap-1.5">
          <i class="fas fa-credit-card text-[#2E8B83]"></i>
          {{ __('landing.trust.no_card') !== 'landing.trust.no_card' ? __('landing.trust.no_card') : 'بدون بطاقة بنكية' }}
        </span>
        <span class="text-slate-300 hidden sm:inline">•</span>
        <span class="inline-flex items-center gap-1.5">
          <i class="fas fa-headset text-[#2E8B83]"></i>
          {{ __('landing.trust.support') !== 'landing.trust.support' ? __('landing.trust.support') : 'دعم فني مباشر' }}
        </span>
      </div>

    </div>
  </div>
</section>