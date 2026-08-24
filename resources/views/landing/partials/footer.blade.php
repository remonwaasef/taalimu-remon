{{-- Footer --}}
<footer class="landing-footer" style="background: #f8fafc !important; color: #334155 !important; padding: 4rem 0 2rem 0; border-top: 1px solid #e2e8f0;">
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
            {{-- Brand Column --}}
            <div style="flex: 1 1 320px; max-width: 380px;">
                <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; text-decoration: none;">
                    <img src="{{ asset('images/brand/logo-full.png?v=3') }}" alt="Taalimu Logo" style="height: 2.25rem; width: auto; display: block;">
                </a>
                <p style="color: #64748b !important; font-size: 0.95rem; line-height: 1.65; margin: 0 0 1.5rem 0;">
                    {{ __('landing.footer.description') }}
                </p>
                <div style="display: flex; gap: 0.75rem;">
                    <a href="https://wa.me/" target="_blank" rel="noopener noreferrer" style="width: 2.5rem; height: 2.5rem; border-radius: 0.625rem; background: #E6F4F3; color: #2E8B83; display: flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid #B2DDD9; transition: transform 0.2s, background 0.2s;" onmouseover="this.style.transform='scale(1.05)'; this.style.background='#d1ebe9'" onmouseout="this.style.transform='scale(1)'; this.style.background='#E6F4F3'">
                        <i class="fab fa-whatsapp" style="font-size: 1.1rem;"></i>
                    </a>
                    <a href="https://facebook.com/" target="_blank" rel="noopener noreferrer" style="width: 2.5rem; height: 2.5rem; border-radius: 0.625rem; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid #bfdbfe; transition: transform 0.2s, background 0.2s;" onmouseover="this.style.transform='scale(1.05)'; this.style.background='#dbeafe'" onmouseout="this.style.transform='scale(1)'; this.style.background='#eff6ff'">
                        <i class="fab fa-facebook-f" style="font-size: 1.05rem;"></i>
                    </a>
                    <a href="https://twitter.com/" target="_blank" rel="noopener noreferrer" style="width: 2.5rem; height: 2.5rem; border-radius: 0.625rem; background: #f1f5f9; color: #64748b; display: flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid #e2e8f0; transition: transform 0.2s, background 0.2s;" onmouseover="this.style.transform='scale(1.05)'; this.style.background='#e2e8f0'" onmouseout="this.style.transform='scale(1)'; this.style.background='#f1f5f9'">
                        <i class="fab fa-twitter" style="font-size: 1.05rem;"></i>
                    </a>
                </div>
            </div>

            {{-- Product Links --}}
            <div style="flex: 1 1 180px; min-width: 150px;">
                <h4 style="color: #0f172a !important; font-weight: 800; font-size: 1rem; margin: 0 0 1.25rem 0;">
                    {{ is_array(__('landing.footer.product')) ? (__('landing.footer.product.title') ?? 'Product') : __('landing.footer.product') }}
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.75rem;">
                    <li><a href="#qr-registration" style="color: #64748b !important; font-size: 0.9rem; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#64748b'">{{ is_array(__('landing.nav.qr_registration')) ? 'QR Registration' : __('landing.nav.qr_registration') }}</a></li>
                    <li><a href="#whatsapp-notifications" style="color: #64748b !important; font-size: 0.9rem; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#64748b'">{{ is_array(__('landing.nav.whatsapp')) ? 'WhatsApp' : __('landing.nav.whatsapp') }}</a></li>
                    <li><a href="#solutions" style="color: #64748b !important; font-size: 0.9rem; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#64748b'">{{ is_array(__('landing.nav.solutions')) ? 'Solutions' : __('landing.nav.solutions') }}</a></li>
                    <li><a href="#features" style="color: #64748b !important; font-size: 0.9rem; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#64748b'">{{ is_array(__('landing.footer.features')) ? (__('landing.footer.features_link') ?? 'Features') : __('landing.footer.features') }}</a></li>
                    <li><a href="#pricing" style="color: #64748b !important; font-size: 0.9rem; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#64748b'">{{ is_array(__('landing.footer.pricing')) ? (__('landing.footer.pricing_link') ?? 'Pricing') : __('landing.footer.pricing') }}</a></li>
                </ul>
            </div>

            {{-- Company Links --}}
            <div style="flex: 1 1 180px; min-width: 150px;">
                <h4 style="color: #0f172a !important; font-weight: 800; font-size: 1rem; margin: 0 0 1.25rem 0;">
                    {{ is_array(__('landing.footer.company')) ? (__('landing.footer.company.title') ?? 'Company') : __('landing.footer.company') }}
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.75rem;">
                    <li><a href="#about" style="color: #64748b !important; font-size: 0.9rem; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#64748b'">{{ is_array(__('landing.footer.about')) ? 'About' : __('landing.footer.about') }}</a></li>
                    <li><a href="#blog" style="color: #64748b !important; font-size: 0.9rem; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#64748b'">{{ is_array(__('landing.footer.blog')) ? 'Blog' : __('landing.footer.blog') }}</a></li>
                    <li><a href="#careers" style="color: #64748b !important; font-size: 0.9rem; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#64748b'">{{ is_array(__('landing.footer.careers')) ? 'Careers' : __('landing.footer.careers') }}</a></li>
                    <li><a href="{{ route('privacy') }}" style="color: #64748b !important; font-size: 0.9rem; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#64748b'">{{ is_array(__('landing.footer.privacy')) ? (__('landing.footer.legal.privacy') ?? 'Privacy') : __('landing.footer.privacy') }}</a></li>
                    <li><a href="{{ route('terms') }}" style="color: #64748b !important; font-size: 0.9rem; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#64748b'">{{ is_array(__('landing.footer.terms')) ? (__('landing.footer.legal.terms') ?? 'Terms') : __('landing.footer.terms') }}</a></li>
                </ul>
            </div>

            {{-- Login/Register --}}
            <div style="flex: 1 1 180px; min-width: 150px;">
                <h4 style="color: #0f172a !important; font-weight: 800; font-size: 1rem; margin: 0 0 1.25rem 0;">
                    {{ is_array(__('landing.footer.access')) ? 'Portal' : __('landing.footer.access') }}
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.75rem;">
                    <li><a href="{{ route('login.portal') }}" style="color: #64748b !important; font-size: 0.9rem; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#64748b'">{{ is_array(__('landing.footer.login')) ? 'Login' : __('landing.footer.login') }}</a></li>
                    <li><a href="{{ route('register') }}" style="color: #2E8B83 !important; font-weight: 700; font-size: 0.9rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#10b981'" onmouseout="this.style.color='#2E8B83'">{{ is_array(__('landing.footer.register')) ? 'Register' : __('landing.footer.register') }}</a></li>
                    <li><a href="#contact" style="color: #64748b !important; font-size: 0.9rem; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#64748b'">{{ is_array(__('landing.footer.contact')) ? 'Contact' : __('landing.footer.contact') }}</a></li>
                    <li><a href="#help" style="color: #64748b !important; font-size: 0.9rem; font-weight: 500; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#64748b'">{{ is_array(__('landing.footer.help')) ? 'Help' : __('landing.footer.help') }}</a></li>
                </ul>
            </div>
        </div>

        {{-- Copyright Bar --}}
        <div style="border-top: 1px solid #e2e8f0; padding-top: 1.5rem; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; font-size: 0.825rem; color: #94a3b8 !important;">
            <p style="margin: 0;">{{ is_array(__('landing.footer.rights')) ? (__('landing.footer.copyright') ?? 'All rights reserved') : __('landing.footer.rights') }}</p>
            <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; justify-content: {{ app()->getLocale() == 'ar' ? 'flex-start' : 'flex-end' }};">
                <a href="{{ route('privacy') }}" style="color: #94a3b8 !important; font-size: 0.825rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#94a3b8'">{{ is_array(__('landing.footer.privacy')) ? (__('landing.footer.legal.privacy') ?? 'Privacy') : __('landing.footer.privacy') }}</a>
                <a href="{{ route('terms') }}" style="color: #94a3b8 !important; font-size: 0.825rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#94a3b8'">{{ is_array(__('landing.footer.terms')) ? (__('landing.footer.legal.terms') ?? 'Terms') : __('landing.footer.terms') }}</a>
                <a href="{{ route('cookies') }}" style="color: #94a3b8 !important; font-size: 0.825rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#2E8B83'" onmouseout="this.style.color='#94a3b8'">{{ is_array(__('landing.footer.cookies')) ? (__('landing.footer.legal.cookie') ?? 'Cookies') : __('landing.footer.cookies') }}</a>
            </div>
        </div>
    </div>
</footer>