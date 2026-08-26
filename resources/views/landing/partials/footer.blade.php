<footer class="bg-slate-900 text-slate-400 py-16 border-t border-slate-800">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-slate-800">

            <!-- Brand Column -->
            <div class="lg:col-span-2">
                <a href="{{ route('home') }}" class="inline-block mb-4 text-decoration-none">
                    <img src="{{ asset('images/brand/logo-full.png') }}" alt="Taalimu" class="h-8 w-auto brightness-200">
                </a>
                <p class="text-xs sm:text-sm text-slate-400 font-medium leading-relaxed max-w-sm mb-6">
                    {{ __('landing.footer.description') }}
                </p>
            </div>

            <!-- Solutions Links -->
            <div>
                <h4 class="text-sm font-bold text-white mb-4 uppercase tracking-wider">
                    {{ __('landing.footer.col_solutions') }}
                </h4>
                <ul class="space-y-2.5 text-xs font-medium">
                    <li><a href="#qr-registration" class="hover:text-emerald-400 transition-colors text-decoration-none">{{ __('landing.footer.links.qr_registration') }}</a></li>
                    <li><a href="#whatsapp-notifications" class="hover:text-emerald-400 transition-colors text-decoration-none">{{ __('landing.footer.links.whatsapp_alerts') }}</a></li>
                    <li><a href="#solutions" class="hover:text-emerald-400 transition-colors text-decoration-none">{{ __('landing.footer.links.centers_dashboard') }}</a></li>
                    <li><a href="#solutions" class="hover:text-emerald-400 transition-colors text-decoration-none">{{ __('landing.footer.links.teachers_portal') }}</a></li>
                    <li><a href="#solutions" class="hover:text-emerald-400 transition-colors text-decoration-none">{{ __('landing.footer.links.parent_student_portal') }}</a></li>
                </ul>
            </div>

            <!-- Features Links -->
            <div>
                <h4 class="text-sm font-bold text-white mb-4 uppercase tracking-wider">
                    {{ __('landing.footer.col_features') }}
                </h4>
                <ul class="space-y-2.5 text-xs font-medium">
                    <li><a href="#features" class="hover:text-emerald-400 transition-colors text-decoration-none">{{ __('landing.footer.links.smart_attendance') }}</a></li>
                    <li><a href="#features" class="hover:text-emerald-400 transition-colors text-decoration-none">{{ __('landing.footer.links.finance_pos') }}</a></li>
                    <li><a href="#excel" class="hover:text-emerald-400 transition-colors text-decoration-none">{{ __('landing.footer.links.excel_import') }}</a></li>
                    <li><a href="#features" class="hover:text-emerald-400 transition-colors text-decoration-none">{{ __('landing.footer.links.quizzes') }}</a></li>
                    <li><a href="#pricing" class="hover:text-emerald-400 transition-colors text-decoration-none">{{ __('landing.footer.links.plans_pricing') }}</a></li>
                </ul>
            </div>

            <!-- Quick Links & Auth -->
            <div>
                <h4 class="text-sm font-bold text-white mb-4 uppercase tracking-wider">
                    {{ __('landing.footer.col_company') }}
                </h4>
                <ul class="space-y-2.5 text-xs font-medium">
                    <li><a href="#how-it-works" class="hover:text-emerald-400 transition-colors text-decoration-none">{{ __('landing.footer.links.get_started') }}</a></li>
                    <li><a href="#faq" class="hover:text-emerald-400 transition-colors text-decoration-none">{{ __('landing.footer.links.faq') }}</a></li>
                    <li><a href="{{ route('login.portal') }}" class="hover:text-emerald-400 transition-colors text-decoration-none">{{ __('landing.footer.links.login') }}</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-emerald-400 transition-colors text-decoration-none">{{ __('landing.footer.links.create_account') }}</a></li>
                </ul>
            </div>

        </div>

        <!-- Copyright & Legal Bottom Bar -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-medium text-slate-500">
            <p>{{ __('landing.footer.copyright') }}</p>
            <div class="flex items-center gap-6">
                <a href="{{ route('privacy') }}" class="hover:text-slate-400 transition-colors text-decoration-none">{{ __('landing.footer.privacy') }}</a>
                <a href="{{ route('terms') }}" class="hover:text-slate-400 transition-colors text-decoration-none">{{ __('landing.footer.terms') }}</a>
            </div>
        </div>

    </div>
</footer>
