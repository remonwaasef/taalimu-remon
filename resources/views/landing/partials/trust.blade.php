<section class="py-20 lg:py-28 bg-white relative">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16" data-animate="fade-in">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200/80 text-emerald-700 text-xs font-bold mb-4 shadow-sm">
                <i class="fas fa-shield-alt text-xs"></i>
                <span>{{ __('landing.trust.badge') }}</span>
            </div>

            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 leading-tight mb-4">
                {{ __('landing.trust.title') }}
            </h2>

            <p class="text-slate-600 font-medium text-base">
                {{ __('landing.trust.subtitle') }}
            </p>
        </div>

        <!-- Trust Pillars Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Pillar 1: Tenant Isolation -->
            <div class="bg-slate-50/80 rounded-3xl p-6 border border-slate-200/80 hover:shadow-xl transition-all" data-animate="fade-in">
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-[#2E8B83] flex items-center justify-center text-xl mb-4 font-bold">
                    <i class="fas fa-database"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">
                    {{ __('landing.trust.isolation_title') }}
                </h3>
                <p class="text-xs text-slate-600 font-medium leading-relaxed">
                    {{ __('landing.trust.isolation_desc') }}
                </p>
            </div>

            <!-- Pillar 2: RBAC Role Permissions -->
            <div class="bg-slate-50/80 rounded-3xl p-6 border border-slate-200/80 hover:shadow-xl transition-all" data-animate="fade-in">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl mb-4 font-bold">
                    <i class="fas fa-user-lock"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">
                    {{ __('landing.trust.roles_title') }}
                </h3>
                <p class="text-xs text-slate-600 font-medium leading-relaxed">
                    {{ __('landing.trust.roles_desc') }}
                </p>
            </div>

            <!-- Pillar 3: Daily Backups -->
            <div class="bg-slate-50/80 rounded-3xl p-6 border border-slate-200/80 hover:shadow-xl transition-all" data-animate="fade-in">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl mb-4 font-bold">
                    <i class="fas fa-history text-[#2E8B83]"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">
                    {{ __('landing.trust.backup_title') }}
                </h3>
                <p class="text-xs text-slate-600 font-medium leading-relaxed">
                    {{ __('landing.trust.backup_desc') }}
                </p>
            </div>

            <!-- Pillar 4: SSL Encryption -->
            <div class="bg-slate-50/80 rounded-3xl p-6 border border-slate-200/80 hover:shadow-xl transition-all" data-animate="fade-in">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-4 font-bold">
                    <i class="fas fa-lock"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">
                    {{ __('landing.trust.https_title') }}
                </h3>
                <p class="text-xs text-slate-600 font-medium leading-relaxed">
                    {{ __('landing.trust.https_desc') }}
                </p>
            </div>

        </div>

    </div>
</section>