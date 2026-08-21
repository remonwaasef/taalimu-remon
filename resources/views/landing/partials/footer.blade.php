{{-- Premium Light SaaS Footer with High Contrast & Perfect Alignment --}}
<footer class="landing-footer" style="background: #f8fafc !important; color: #334155 !important; padding: 3.5rem 0 2rem 0; border-top: 1px solid #e2e8f0;">
    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1140px; margin: 0 auto;">
        
        {{-- Footer Main Content Grid --}}
        <div style="
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 2.5rem;
            margin-bottom: 2.5rem;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        ">
            <!-- Brand Column -->
            <div style="flex: 1 1 320px; max-width: 380px;">
                <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 0.875rem; text-decoration: none;">
                    <img src="{{ asset('images/brand/logo-full.png?v=3') }}" alt="Taalimu Logo" style="height: 2rem; width: auto; display: block;">
                </a>
                <p style="color: #64748b !important; font-size: 0.9rem; line-height: 1.6; margin: 0 0 1.25rem 0;">
                    {{ __('landing.footer.description') }}
                </p>
                <div style="display: flex; gap: 0.625rem;">
                    <a href="https://wa.me/" target="_blank" style="width: 2.25rem; height: 2.25rem; border-radius: 0.5rem; background: #E6F4F3; color: #2E8B83; display: flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid #B2DDD9; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="fab fa-whatsapp" style="font-size: 1rem;"></i>
                    </a>
                    <a href="https://facebook.com/" target="_blank" style="width: 2.25rem; height: 2.25rem; border-radius: 0.5rem; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid #bfdbfe; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="fab fa-facebook-f" style="font-size: 0.95rem;"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div style="flex: 1 1 180px; min-width: 150px;">
                <h4 style="color: #0f172a !important; font-weight: 800; font-size: 0.95rem; margin: 0 0 1rem 0;">
                    {{ __('landing.footer.quick_links') }}
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.625rem;">
                    <li>
                        <a href="#outcome" style="color: #64748b !important; font-size: 0.875rem; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#64748b'">
                            {{ __('landing.outcome.badge') }}
                        </a>
                    </li>
                    <li>
                        <a href="#whatsapp" style="color: #64748b !important; font-size: 0.875rem; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#64748b'">
                            {{ __('landing.nav.whatsapp') }}
                        </a>
                    </li>
                    <li>
                        <a href="#excel" style="color: #64748b !important; font-size: 0.875rem; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#64748b'">
                            {{ __('landing.nav.excel_migration') }}
                        </a>
                    </li>
                    <li>
                        <a href="#pricing" style="color: #64748b !important; font-size: 0.875rem; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#64748b'">
                            {{ __('landing.footer.pricing_link') }}
                        </a>
                    </li>
                    <li>
                        <a href="#faq" style="color: #64748b !important; font-size: 0.875rem; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#64748b'">
                            {{ __('landing.footer.faq_link') }}
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Portal Links -->
            <div style="flex: 1 1 180px; min-width: 150px;">
                <h4 style="color: #0f172a !important; font-weight: 800; font-size: 0.95rem; margin: 0 0 1rem 0;">
                    {{ __('landing.footer.login_link') }}
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.625rem;">
                    <li>
                        <a href="{{ route('login.portal') }}" style="color: #64748b !important; font-size: 0.875rem; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#64748b'">
                            {{ __('landing.footer.login_link') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('register') }}" style="color: #2E8B83 !important; font-weight: 700; font-size: 0.875rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#10b981'" onmouseout="this.style.color='#2E8B83'">
                            {{ __('landing.footer.register_link') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('privacy') }}" style="color: #64748b !important; font-size: 0.875rem; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#64748b'">
                            {{ __('landing.footer.privacy') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('terms') }}" style="color: #64748b !important; font-size: 0.875rem; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#64748b'">
                            {{ __('landing.footer.terms') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Copyright Bar -->
        <div style="border-top: 1px solid #e2e8f0; padding-top: 1.5rem; text-align: center; font-size: 0.825rem; color: #94a3b8 !important;">
            <p style="margin: 0;">{{ __('landing.footer.rights') }}</p>
        </div>
    </div>
</footer>
