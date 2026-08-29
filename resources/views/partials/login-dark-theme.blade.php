{{--
    Login-page dark mode (Arabic market phase — dashboard item: dark login).
    Reuses the same theme preference as app layouts: localStorage('theme') => light | dark | system.
    Requires marker classes on the login view: .login-page-wrap (outer wrapper) and .login-card (card).
--}}
<style>
    /* Page backdrop + hide the white landing chrome on login pages */
    html.dark body { background: #020617 !important; color: #f1f5f9 !important; }
    html.dark .landing-header,
    html.dark .landing-footer { display: none !important; }

    html.dark .login-page-wrap {
        background:
            radial-gradient(1200px 600px at 15% -10%, rgba(16,185,129,.07), transparent 55%),
            radial-gradient(900px 500px at 90% 100%, rgba(22,143,124,.06), transparent 55%),
            #020617 !important;
    }

    /* Card */
    html.dark .login-card {
        background: #0f172a !important;
        border-color: rgba(30, 41, 59, .55) !important;
        box-shadow: 0 25px 70px -15px rgba(0, 0, 0, .75) !important;
    }

    /* Typography */
    html.dark .login-card .text-slate-900 { color: #f1f5f9 !important; }
    html.dark .login-card .text-slate-700,
    html.dark .login-card .text-gray-700 { color: #e2e8f0 !important; }
    html.dark .login-card .text-slate-500,
    html.dark .login-card .text-gray-500 { color: #94a3b8 !important; }
    html.dark .login-card .text-slate-400,
    html.dark .login-card .text-gray-400,
    html.dark .login-card .text-muted-foreground { color: #94a3b8 !important; }

    /* Inputs */
    html.dark .login-card input,
    html.dark .login-card .input-compact {
        background: #1e293b !important;
        border-color: #334155 !important;
        color: #f1f5f9 !important;
    }
    html.dark .login-card input:focus,
    html.dark .login-card .input-compact:focus {
        background: #1e293b !important;
        border-color: #10b981 !important;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, .14) !important;
    }
    html.dark .login-card ::placeholder { color: #64748b !important; }

    /* Dividers, borders */
    html.dark .login-card .border-slate-100,
    html.dark .login-card .border-slate-200,
    html.dark .login-card .border-gray-200,
    html.dark .login-card .border-gray-300,
    html.dark .login-card .border-red-200 { border-color: #1e293b !important; }

    /* White surfaces inside the card (separator chip, Google button) */
    html.dark .login-card .bg-white { background: #0f172a !important; }
    html.dark .login-card a.bg-white { border-color: #334155 !important; }
    html.dark .login-card a.bg-white:hover { background: #1e293b !important; }

    /* Error alert */
    html.dark .login-card .bg-red-50 { background: rgba(127, 29, 29, .28) !important; }
    html.dark .login-card .text-red-800,
    html.dark .login-card .text-red-600 { color: #fca5a5 !important; }
    html.dark .login-card .text-red-500 { color: #f87171 !important; }

    /* Admin submit + logo chip (was slate-900) — lift with brand indigo on dark */
    html.dark .login-card .bg-slate-900,
    html.dark .login-card .bg-slate-900:hover { background-color: #168F7C !important; }

    /* Fixed toggle button (corner; RTL/LTR aware) */
    #taalimu-theme-toggle {
        position: fixed; top: 1rem; inset-inline-end: 1rem; z-index: 100001;
        width: 2.75rem; height: 2.75rem; border-radius: 9999px; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center;
        background: rgba(255, 255, 255, .92); border: 1px solid #e2e8f0; color: #475569;
        box-shadow: 0 6px 18px rgba(2, 6, 23, .12); transition: transform .25s; font-size: 1rem;
    }
    #taalimu-theme-toggle:hover { transform: translateY(-1px); }
    html.dark #taalimu-theme-toggle {
        background: rgba(30, 41, 59, .85); border-color: #334155; color: #fbbf24;
    }
    #taalimu-theme-toggle .fa-sun { display: none; }
    html.dark #taalimu-theme-toggle .fa-moon { display: none; }
    html.dark #taalimu-theme-toggle .fa-sun { display: inline-block; }
</style>

<script>
    // Same preference as app layouts: localStorage('theme') = light | dark | system
    (function () {
        var theme = null;
        try { theme = localStorage.getItem('theme'); } catch (e) {}
        var dark = theme === 'dark' ||
            ((theme === null || theme === 'system') && window.matchMedia('(prefers-color-scheme: dark)').matches);
        if (dark) document.documentElement.classList.add('dark');

        if (theme === null || theme === 'system') {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
                document.documentElement.classList.toggle('dark', e.matches);
            });
        }

        var meta = document.querySelector('meta[name="theme-color"]');
        if (meta) meta.content = document.documentElement.classList.contains('dark') ? '#020617' : '#2E8B83';
    })();

    function taalimuToggleTheme() {
        var dark = document.documentElement.classList.toggle('dark');
        try { localStorage.setItem('theme', dark ? 'dark' : 'light'); } catch (e) {}
        var meta = document.querySelector('meta[name="theme-color"]');
        if (meta) meta.content = dark ? '#020617' : '#2E8B83';
    }
</script>

<button type="button" id="taalimu-theme-toggle" onclick="taalimuToggleTheme()" aria-label="Toggle dark mode" title="الوضع الداكن">
    <i class="fa-solid fa-moon"></i>
    <i class="fa-solid fa-sun"></i>
</button>