<aside class="sidebar sidebar-default navs-rounded-all sidebar-base" style="background: #ffffff !important; border-left: 1px solid rgba(0,0,0,0.05) !important;">
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
