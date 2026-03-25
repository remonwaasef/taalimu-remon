<footer class="bg-white pt-24 pb-12 border-t border-slate-100 relative overflow-hidden">
    <!-- Sophisticated Accents -->
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-slate-50 rounded-full blur-[120px] -z-10"></div>

    <div class="container mx-auto px-4 lg:px-12">
        <div class="grid lg:grid-cols-5 gap-16 mb-20">
            <!-- Brand Column -->
            <div class="lg:col-span-2 space-y-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/brand/logo-full.png?v=3') }}" alt="{{ config('app.name') }}" class="h-10 w-auto mix-blend-multiply">
                    <div class="flex flex-col">
                        <span class="font-black text-xl text-[#0f172a] leading-tight tracking-tighter">
                            {{ \App\Models\SiteSetting::get('site_name', 'Taalimu') }}
                        </span>
                        <span class="text-[10px] font-black text-[#22c55e] uppercase tracking-[0.2em]">
                            {{ __('landing.navbar.badge_short') ?? 'Smart Education' }}
                        </span>
                    </div>
                </a>
                <p class="text-slate-500 font-medium leading-relaxed max-w-sm">
                    {{ \App\Models\SiteSetting::get('site_description_' . app()->getLocale(), __('landing.hero.subtitle')) }}
                </p>
                <!-- Social Links -->
                <div class="flex items-center gap-4">
                    @foreach(['facebook-f', 'linkedin-in', 'twitter', 'instagram'] as $social)
                    <a href="#" class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-[#22c55e] hover:text-white transition-all duration-500 shadow-soft hover:shadow-premium group">
                        <i class="fab fa-{{ $social }} text-sm"></i>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Links Columns -->
            <div>
                <h4 class="text-[#0f172a] font-black text-sm uppercase tracking-[0.2em] mb-8">{{ __('landing.footer.product.title') }}</h4>
                <ul class="space-y-4">
                    @foreach(['features', 'pricing', 'integrations', 'updates'] as $link)
                    <li><a href="#" class="text-slate-500 hover:text-[#22c55e] font-bold text-sm transition-colors">{{ __("landing.footer.product.$link") }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="text-[#0f172a] font-black text-sm uppercase tracking-[0.2em] mb-8">{{ __('landing.footer.resources.title') }}</h4>
                <ul class="space-y-4">
                    @foreach(['help', 'docs', 'blog', 'api'] as $link)
                    <li><a href="#" class="text-slate-500 hover:text-[#22c55e] font-bold text-sm transition-colors">{{ __("landing.footer.resources.$link") }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="text-[#0f172a] font-black text-sm uppercase tracking-[0.2em] mb-8">{{ __('landing.footer.legal.title') }}</h4>
                <ul class="space-y-4">
                    @foreach(['privacy', 'terms', 'cookie'] as $link)
                    <li><a href="{{ route($link) }}" class="text-slate-500 hover:text-[#22c55e] font-bold text-sm transition-colors">{{ __("landing.footer.legal.$link") }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="pt-12 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="text-slate-400 text-sm font-bold">
                {{ str_replace(config('app.name'), \App\Models\SiteSetting::get('site_name', config('app.name')), __('landing.footer.copyright')) }}
            </div>
            
            <div class="flex items-center gap-4 px-5 py-2.5 rounded-full bg-slate-50 border border-slate-100">
                <span class="w-2.5 h-2.5 rounded-full bg-[#22c55e] animate-pulse"></span>
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">All Systems Operational</span>
            </div>
        </div>
    </div>
</footer>
