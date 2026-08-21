<footer class="landing-footer" style="background: #0f172a; color: #cbd5e1; padding: 4.5rem 0 2.5rem 0; border-top: 1px solid #1e293b;">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <!-- Brand Column -->
            <div class="lg:col-span-2">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-4">
                    <img src="{{ asset('images/brand/logo-full.png?v=3') }}" alt="Taalimu" class="h-8 w-auto brightness-0 invert">
                </a>
                <p style="color: #94a3b8; font-size: 0.95rem; line-height: 1.7; max-width: 24rem; margin-bottom: 1.5rem;">
                    {{ __('landing.footer.description') }}
                </p>
                <div style="display: flex; gap: 0.75rem;">
                    <a href="https://wa.me/" target="_blank" style="width: 2.25rem; height: 2.25rem; border-radius: 0.5rem; background: #1e293b; color: #2E8B83; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="https://facebook.com/" target="_blank" style="width: 2.25rem; height: 2.25rem; border-radius: 0.5rem; background: #1e293b; color: #38bdf8; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 style="color: #ffffff; font-weight: 800; font-size: 1rem; margin-bottom: 1.25rem;">
                    {{ __('landing.footer.quick_links') }}
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.75rem;">
                    <li><a href="#showcase" style="color: #94a3b8; font-size: 0.9rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#94a3b8'">{{ __('landing.footer.features_link') }}</a></li>
                    <li><a href="#whatsapp" style="color: #94a3b8; font-size: 0.9rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#94a3b8'">{{ __('landing.nav.whatsapp') }}</a></li>
                    <li><a href="#excel" style="color: #94a3b8; font-size: 0.9rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#94a3b8'">{{ __('landing.nav.excel_migration') }}</a></li>
                    <li><a href="#pricing" style="color: #94a3b8; font-size: 0.9rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#94a3b8'">{{ __('landing.footer.pricing_link') }}</a></li>
                    <li><a href="#faq" style="color: #94a3b8; font-size: 0.9rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#94a3b8'">{{ __('landing.footer.faq_link') }}</a></li>
                </ul>
            </div>

            <!-- Portal Links -->
            <div>
                <h4 style="color: #ffffff; font-weight: 800; font-size: 1rem; margin-bottom: 1.25rem;">
                    {{ __('landing.footer.login_link') }}
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.75rem;">
                    <li><a href="{{ route('login.portal') }}" style="color: #94a3b8; font-size: 0.9rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#94a3b8'">{{ __('landing.footer.login_link') }}</a></li>
                    <li><a href="{{ route('register') }}" style="color: #2E8B83; font-weight: 700; font-size: 0.9rem; text-decoration: none;">{{ __('landing.footer.register_link') }}</a></li>
                    <li><a href="{{ route('privacy') }}" style="color: #94a3b8; font-size: 0.9rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#94a3b8'">{{ __('landing.footer.privacy') }}</a></li>
                    <li><a href="{{ route('terms') }}" style="color: #94a3b8; font-size: 0.9rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#94a3b8'">{{ __('landing.footer.terms') }}</a></li>
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div style="border-top: 1px solid #1e293b; padding-top: 1.75rem; text-align: center; font-size: 0.85rem; color: #64748b;">
            <p style="margin: 0;">{{ __('landing.footer.rights') }}</p>
        </div>
    </div>
</footer>
