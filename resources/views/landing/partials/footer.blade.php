<footer class="landing-footer" style="background:#f8fafc; padding-top:5rem; padding-bottom:2.5rem; border-top:1px solid #e2e8f0;">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="grid lg:grid-cols-5 gap-12 mb-16">
            {{-- Brand --}}
            <div class="lg:col-span-2" style="display:flex; flex-direction:column; gap:1.5rem;">
                <a href="{{ route('home') }}" style="display:flex; align-items:center; gap:0.75rem; text-decoration:none;">
                    <img src="{{ asset('images/brand/logo-full.png?v=3') }}" alt="{{ config('app.name') }}" style="height:2.25rem; width:auto;">
                    <span style="font-weight:900; font-size:1.25rem; color:#0f172a; letter-spacing:-0.025em;">
                        {{ \App\Models\SiteSetting::get('site_name', 'Taalimu') }}
                    </span>
                </a>
                <p style="color:#64748b !important; font-weight:500; line-height:1.7; max-width:20rem; font-size:0.875rem;">
                    {{ \App\Models\SiteSetting::get('site_description_' . app()->getLocale(), __('landing.hero.subtitle')) }}
                </p>
                {{-- Social --}}
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    @foreach(['facebook-f', 'linkedin-in', 'twitter', 'instagram'] as $social)
                    <a href="#" style="width:2.5rem; height:2.5rem; border-radius:0.75rem; background:#e2e8f0; display:flex; align-items:center; justify-content:center; color:#64748b; transition:all 0.3s; text-decoration:none;" onmouseover="this.style.background='#059669'; this.style.color='#ffffff'" onmouseout="this.style.background='#e2e8f0'; this.style.color='#64748b'">
                        <i class="fab fa-{{ $social }}" style="font-size:0.875rem;"></i>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Links --}}
            <div>
                <h4 style="color:#0f172a !important; font-weight:700; font-size:0.875rem; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:1.5rem;">{{ __('landing.footer.product.title') }}</h4>
                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:0.75rem;">
                    @foreach(['features', 'pricing', 'integrations', 'updates'] as $link)
                    <li><a href="#" style="color:#64748b; font-weight:500; font-size:0.875rem; text-decoration:none; transition:color 0.2s;" onmouseover="this.style.color='#059669'" onmouseout="this.style.color='#64748b'">{{ __("landing.footer.product.$link") }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 style="color:#0f172a !important; font-weight:700; font-size:0.875rem; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:1.5rem;">{{ __('landing.footer.resources.title') }}</h4>
                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:0.75rem;">
                    @foreach(['help', 'docs', 'blog', 'api'] as $link)
                    <li><a href="#" style="color:#64748b; font-weight:500; font-size:0.875rem; text-decoration:none; transition:color 0.2s;" onmouseover="this.style.color='#059669'" onmouseout="this.style.color='#64748b'">{{ __("landing.footer.resources.$link") }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 style="color:#0f172a !important; font-weight:700; font-size:0.875rem; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:1.5rem;">{{ __('landing.footer.legal.title') }}</h4>
                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:0.75rem;">
                    @foreach(['privacy', 'terms', 'cookies'] as $link)
                    <li><a href="{{ route($link) }}" style="color:#64748b; font-weight:500; font-size:0.875rem; text-decoration:none; transition:color 0.2s;" onmouseover="this.style.color='#059669'" onmouseout="this.style.color='#64748b'">{{ __("landing.footer.legal.$link") }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div style="padding-top:2rem; border-top:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; gap:1.5rem; flex-wrap:wrap;">
            <div style="color:#64748b; font-size:0.875rem; font-weight:500;">
                {{ str_replace(config('app.name'), \App\Models\SiteSetting::get('site_name', config('app.name')), __('landing.footer.copyright')) }}
            </div>

            <div style="display:flex; align-items:center; gap:0.5rem; padding:0.375rem 1rem; border-radius:9999px; background:#ecfdf5; border:1px solid #a7f3d0;">
                <span style="width:0.5rem; height:0.5rem; border-radius:50%; background:#22c55e; display:inline-block; animation:pulse 2s infinite;"></span>
                <span style="font-size:0.65rem; font-weight:700; color:#047857; text-transform:uppercase; letter-spacing:0.08em;">All Systems Operational</span>
            </div>
        </div>
    </div>
</footer>
