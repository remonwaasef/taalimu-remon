<footer class="bg-slate-50 pt-20 pb-10 overflow-hidden relative border-t border-slate-200/60">
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>

    <div class="container mx-auto px-4 lg:px-12">
        <div class="grid lg:grid-cols-5 gap-12 mb-16">
            <!-- Brand -->
            <div class="lg:col-span-2 space-y-6">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/brand/logo-full.png?v=3') }}" alt="{{ config('app.name') }}" class="h-9 w-auto">
                    <span class="font-black text-xl text-slate-900 leading-tight tracking-tight">
                        {{ \App\Models\SiteSetting::get('site_name', 'Taalimu') }}
                    </span>
                </a>
                <p class="text-slate-600 font-medium leading-relaxed max-w-sm text-sm">
                    {{ \App\Models\SiteSetting::get('site_description_' . app()->getLocale(), __('landing.hero.subtitle')) }}
                </p>
                <!-- Social -->
                <div class="flex items-center gap-3">
                    @foreach(['facebook-f', 'linkedin-in', 'twitter', 'instagram'] as $social)
                    <a href="#" class="w-10 h-10 rounded-xl bg-slate-200/50 flex items-center justify-center text-slate-500 hover:bg-emerald-500 hover:text-white transition-all duration-300">
                        <i class="fab fa-{{ $social }} text-sm"></i>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Links -->
            <div>
                <h4 class="text-slate-900 font-bold text-sm uppercase tracking-wider mb-6">{{ __('landing.footer.product.title') }}</h4>
                <ul class="space-y-3">
                    @foreach(['features', 'pricing', 'integrations', 'updates'] as $link)
                    <li><a href="#" class="text-slate-500 hover:text-emerald-400 font-medium text-sm transition-colors">{{ __("landing.footer.product.$link") }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="text-slate-900 font-bold text-sm uppercase tracking-wider mb-6">{{ __('landing.footer.resources.title') }}</h4>
                <ul class="space-y-3">
                    @foreach(['help', 'docs', 'blog', 'api'] as $link)
                    <li><a href="#" class="text-slate-500 hover:text-emerald-400 font-medium text-sm transition-colors">{{ __("landing.footer.resources.$link") }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="text-slate-900 font-bold text-sm uppercase tracking-wider mb-6">{{ __('landing.footer.legal.title') }}</h4>
                <ul class="space-y-3">
                    @foreach(['privacy', 'terms', 'cookies'] as $link)
                    <li><a href="{{ route($link) }}" class="text-slate-500 hover:text-emerald-600 font-medium text-sm transition-colors">{{ __("landing.footer.legal.$link") }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="pt-8 border-t border-slate-200/60 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-slate-600 text-sm font-medium">
                {{ str_replace(config('app.name'), \App\Models\SiteSetting::get('site_name', config('app.name')), __('landing.footer.copyright')) }}
            </div>
            
            <div class="flex items-center gap-3 px-4 py-2 rounded-full bg-slate-100 border border-slate-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">All Systems Operational</span>
            </div>
        </div>
    </div>
</footer>
