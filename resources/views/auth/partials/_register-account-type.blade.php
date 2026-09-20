<!-- Account Type: Clean Segmented Tab -->
<div class="flex items-center justify-center mb-3">
    <div class="inline-flex p-1 bg-slate-100/80 rounded-xl border border-slate-200/60 shadow-inner">
        <button type="button" @click="accountType = 'instructor'"
                class="flex items-center gap-1.5 px-4 py-1.5 rounded-lg text-xs font-bold transition-all duration-200"
                :class="accountType === 'instructor' 
                    ? 'bg-white text-emerald-800 shadow-sm font-black ring-1 ring-slate-200/80' 
                    : 'text-slate-500 hover:text-slate-700'">
            <i class="bi bi-person-video3 text-sm"></i>
            {{ __('auth.registration_steps.tutor') }}
        </button>
        <button type="button" @click="accountType = 'center'"
                class="flex items-center gap-1.5 px-4 py-1.5 rounded-lg text-xs font-bold transition-all duration-200"
                :class="accountType === 'center' 
                    ? 'bg-white text-emerald-800 shadow-sm font-black ring-1 ring-slate-200/80' 
                    : 'text-slate-500 hover:text-slate-700'">
            <i class="bi bi-building text-sm"></i>
            {{ __('auth.registration_steps.center') }}
        </button>
    </div>
</div>

