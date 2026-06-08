<aside class="sidebar sidebar-default navs-rounded-all sidebar-base" style="background: #ffffff !important; border-left: 1px solid rgba(0,0,0,0.05) !important;">
<style>
/* Premium Light Sidebar Theme */
.sidebar-base {
    box-shadow: 2px 0 24px rgba(0,0,0,0.03) !important;
}
.sidebar-base .sidebar-header {
    background: transparent !important;
    border-bottom: 1px solid rgba(0,0,0,0.04);
}
.sidebar-base .logo-title {
    color: #0f172a !important;
    font-weight: 800 !important;
    letter-spacing: -0.5px;
}
.sidebar-base .nav-link {
    color: #475569 !important;
    border-radius: 10px !important;
    margin: 4px 8px !important;
    padding: 10px 12px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    font-weight: 600;
}
.sidebar-base .nav-link:hover {
    background: #f8fafc !important;
    color: #0f172a !important;
    transform: translateX(4px);
}
html[dir="rtl"] .sidebar-base .nav-link:hover {
    transform: translateX(-4px);
}
.sidebar-base .nav-link.active {
    background: rgba(16, 185, 129, 0.1) !important;
    color: #059669 !important;
    box-shadow: inset 3px 0 0 #10b981;
}
html[dir="rtl"] .sidebar-base .nav-link.active {
    box-shadow: inset -3px 0 0 #10b981;
}
.sidebar-base .nav-link i, .sidebar-base .nav-link svg {
    color: #94a3b8 !important;
    transition: color 0.3s ease;
}
.sidebar-base .nav-link:hover i {
    color: #475569 !important;
}
.sidebar-base .nav-link.active i {
    color: #059669 !important;
}
.sidebar-base .sub-nav .nav-link {
    font-size: 0.9rem !important;
    padding: 10px 0 !important;
    font-weight: 500;
    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;
    direction: rtl !important;
}
html[dir="rtl"] .sidebar-base .sub-nav .nav-link {
    padding-right: 1.2rem !important;
    padding-left: 0.5rem !important;
    margin: 0 !important;
}
.sidebar-base .sub-nav .sidenav-mini-icon,
.sidebar-base .sub-nav .nav-link::before,
.sidebar-base .sub-nav .nav-link::after,
.sidebar-base .sub-nav .nav-link .right-icon {
    display: none !important;
    content: none !important;
}
.sidebar-base .sub-nav .item-name {
    white-space: nowrap !important;
    overflow: visible !important;
    text-overflow: unset !important;
    margin: 0 !important;
    padding: 0 !important;
    text-align: right !important;
    flex-grow: 1;
}
.sidebar-base .hr-horizontal {
    border-color: rgba(0,0,0,0.05) !important;
    margin: 1rem 0;
}
/* Premium Logo Container */
.brand-logo-container {
    background: #ffffff !important;
    border: 1px solid rgba(0,0,0,0.08) !important;
    box-shadow: 0 4px 10px rgba(0,0,0,0.03);
}
.brand-logo-fallback {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    color: white !important;
    box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
}
.sidebar-toggle .icon svg {
    color: #64748b;
    transition: color 0.3s ease;
}
.sidebar-toggle:hover .icon svg {
    color: #0f172a;
}
</style>
    <div class="sidebar-header d-flex align-items-center justify-content-between px-4 py-3">
        <a href="{{ $homeUrl ?? '#' }}" class="navbar-brand d-flex align-items-center m-0">
            @if(isset($logo))
                {{ $logo }}
            @endif
        </a>
        <div class="sidebar-toggle border-0" data-toggle="sidebar" data-active="true">
            <i class="icon">
                <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </i>
        </div>
    </div>
    
    <div class="sidebar-body pt-0 data-scrollbar">
        <div class="sidebar-list" id="sidebar">
            <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
                {{ $slot }}
            </ul>
        </div>
    </div>
    <div class="sidebar-footer"></div>
</aside>
