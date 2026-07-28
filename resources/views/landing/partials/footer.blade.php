<footer class="relative bg-gradient-to-b from-slate-900 via-slate-900 to-slate-950 pt-20 pb-8 overflow-hidden landing-footer">
    {{-- Decorative Elements --}}
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-emerald-500/50 to-transparent"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-500/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-500/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>

    <div class="container mx-auto px-4 lg:px-12 relative z-10">
        {{-- Top Section: Brand + Link Columns --}}
        <div class="grid lg:grid-cols-12 gap-12 mb-16">
            {{-- Brand Column --}}
            <div class="lg:col-span-4 space-y-6">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:shadow-emerald-500/40 transition-shadow duration-300">
                        <img src="{{ asset('images/brand/logo-full.png?v=3') }}" alt="{{ config('app.name') }}" class="h-6 w-auto brightness-0 invert">
                    </div>
                    <span class="font-black text-xl text-white leading-tight tracking-tight">
                        {{ \App\Models\SiteSetting::get('site_name', 'Taalimu') }}
                    </span>
                </a>
                <p class="text-slate-400 font-medium leading-relaxed max-w-sm text-sm">
                    {{ \App\Models\SiteSetting::get('site_description_' . app()->getLocale(), __('landing.hero.subtitle')) }}
                </p>

                {{-- Social Icons --}}
                <div class="flex items-center gap-3 pt-2">
                    @foreach([
                        ['icon' => 'facebook-f', 'color' => 'hover:bg-blue-600'],
                        ['icon' => 'linkedin-in', 'color' => 'hover:bg-blue-700'],
                        ['icon' => 'twitter', 'color' => 'hover:bg-sky-500'],
                        ['icon' => 'instagram', 'color' => 'hover:bg-gradient-to-br hover:from-purple-600 hover:to-pink-500']
                    ] as $social)
                    <a href="#" class="w-10 h-10 rounded-xl bg-slate-800/80 border border-slate-700/50 flex items-center justify-center text-slate-400 {{ $social['color'] }} hover:text-white hover:border-transparent hover:scale-110 hover:shadow-lg transition-all duration-300">
                        <i class="fab fa-{{ $social['icon'] }} text-sm"></i>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Link Columns --}}
            <div class="lg:col-span-8 grid sm:grid-cols-3 gap-10">
                {{-- Product Links --}}
                <div>
                    <h4 class="text-white font-bold text-xs uppercase tracking-widest mb-6 flex items-center gap-2">
                        <span class="w-6 h-px bg-emerald-500"></span>
                        {{ __('landing.footer.product.title') }}
                    </h4>
                    <ul class="space-y-3.5">
                        @foreach(['features', 'pricing', 'integrations', 'updates'] as $link)
                        <li>
                            <a href="#" class="text-slate-400 hover:text-emerald-400 font-medium text-sm transition-colors duration-200 flex items-center gap-2 group">
                                <span class="w-0 group-hover:w-2 h-px bg-emerald-500 transition-all duration-200"></span>
                                {{ __("landing.footer.product.$link") }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Resources Links --}}
                <div>
                    <h4 class="text-white font-bold text-xs uppercase tracking-widest mb-6 flex items-center gap-2">
                        <span class="w-6 h-px bg-emerald-500"></span>
                        {{ __('landing.footer.resources.title') }}
                    </h4>
                    <ul class="space-y-3.5">
                        @foreach(['help', 'docs', 'blog', 'api'] as $link)
                        <li>
                            <a href="#" class="text-slate-400 hover:text-emerald-400 font-medium text-sm transition-colors duration-200 flex items-center gap-2 group">
                                <span class="w-0 group-hover:w-2 h-px bg-emerald-500 transition-all duration-200"></span>
                                {{ __("landing.footer.resources.$link") }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Legal Links --}}
                <div>
                    <h4 class="text-white font-bold text-xs uppercase tracking-widest mb-6 flex items-center gap-2">
                        <span class="w-6 h-px bg-emerald-500"></span>
                        {{ __('landing.footer.legal.title') }}
                    </h4>
                    <ul class="space-y-3.5">
                        @foreach(['privacy', 'terms', 'cookies'] as $link)
                        <li>
                            <a href="{{ route($link) }}" class="text-slate-400 hover:text-emerald-400 font-medium text-sm transition-colors duration-200 flex items-center gap-2 group">
                                <span class="w-0 group-hover:w-2 h-px bg-emerald-500 transition-all duration-200"></span>
                                {{ __("landing.footer.legal.$link") }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="pt-8 border-t border-slate-800/80">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-slate-500 text-sm font-medium">
                    {{ str_replace(config('app.name'), \App\Models\SiteSetting::get('site_name', config('app.name')), __('landing.footer.copyright')) }}
                </div>

                <div class="flex items-center gap-4">
                    {{-- System Status Badge --}}
                    <div class="flex items-center gap-2.5 px-4 py-2 rounded-full bg-slate-800/60 border border-slate-700/40 backdrop-blur-sm">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">All Systems Operational</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
