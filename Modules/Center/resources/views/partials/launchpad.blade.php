@php
    $steps = [
        'education_system' => ['icon' => 'fa-map-signs', 'color' => '#2E8B83', 'route' => 'center.settings.index'],
        'instructor' => ['icon' => 'fa-user-tie', 'color' => '#0284c7', 'route' => 'center.instructors.create'],
        'course' => ['icon' => 'fa-book-open', 'color' => '#d97706', 'route' => 'center.courses.create'],
        'student' => ['icon' => 'fa-user-graduate', 'color' => '#16a34a', 'route' => 'center.students.create'],
        'attendance' => ['icon' => 'fa-clipboard-check', 'color' => '#dc2626', 'route' => 'center.attendance.index'],
    ];

    // Find first incomplete step to highlight it
    $highlightStep = null;
    foreach($launchpadSteps as $key => $isDone) {
        if(!$isDone) {
            $highlightStep = $key;
            break;
        }
    }
@endphp

<div class="card glass-card launchpad-card mb-4 overflow-hidden position-relative" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1.25rem; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
    <!-- Decorative rocket -->
    <div style="position: absolute; top: 0.5rem; {{ app()->getLocale() == 'ar' ? 'left: 1.5rem;' : 'right: 1.5rem;' }} opacity: 0.05; pointer-events: none;">
        <i class="fas fa-rocket" style="font-size: 5rem; transform: rotate(-15deg); color: #2E8B83;"></i>
    </div>

    <div class="card-body p-4" style="position: relative; z-index: 2;">
        {{-- Header & Progress --}}
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.25rem; margin-bottom: 1.5rem;">
            <div>
                <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                    <h5 style="font-size: 1.15rem; font-weight: 900; color: #0f172a; margin: 0;">
                        🚀 {{ __('center::dashboard.launchpad.title', ['name' => auth()->user()->name]) }}
                    </h5>
                    @php
                        $hasDemoData = \App\Models\Instructor::where('tenant_id', app('tenant')->id)->where('email', 'like', '%.demo@%')->exists();
                    @endphp

                    @if($hasDemoData)
                        <form action="{{ route('center.demo.reset', ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}" method="POST" id="deleteRowForm_1" style="margin: 0;">
                            @csrf
                            <button type="button" data-confirm-delete data-form="deleteRowForm_1" style="background: #fef2f2; border: 1px dashed #fca5a5; color: #dc2626; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                                <i class="fas fa-trash-alt" style="margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.25rem;"></i>
                                {{ __('center::dashboard.launchpad.reset_demo') }}
                            </button>
                        </form>
                    @else
                        <form action="{{ route('center.demo.seed', ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}" method="POST" id="demoDataForm" style="margin: 0;">
                            @csrf
                            <button type="submit" style="background: #E6F4F3; border: 1px dashed #B2DDD9; color: #25746D; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                                <i class="fas fa-magic" style="margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.25rem;"></i>
                                {{ __('center::dashboard.launchpad.explore_demo') }}
                            </button>
                        </form>
                    @endif
                </div>
                <p style="color: #64748b; font-size: 0.85rem; margin: 0.25rem 0 0 0;">{{ __('center::dashboard.launchpad.subtitle') }}</p>
            </div>

            {{-- Progress Bar --}}
            <div style="min-width: 220px; flex: 0 1 280px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                    <span style="font-size: 0.8rem; font-weight: 700; color: #475569;">{{ __('center::dashboard.launchpad.progress') }}</span>
                    <span style="font-size: 0.8rem; font-weight: 900; color: #2E8B83; background: #E6F4F3; padding: 0.15rem 0.5rem; border-radius: 9999px;">{{ $launchpadProgress }}%</span>
                </div>
                <div style="height: 8px; border-radius: 9999px; background: #f1f5f9; overflow: hidden;">
                    <div style="height: 100%; width: {{ $launchpadProgress }}%; background: linear-gradient(90deg, #2E8B83, #10b981); border-radius: 9999px; transition: width 0.4s ease;"></div>
                </div>
            </div>
        </div>

        {{-- Horizontal Steps Grid (All 5 Steps in One Line) --}}
        <div style="
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0.875rem;
            align-items: stretch;
        ">
            @foreach($steps as $key => $data)
                @php
                    $isCompleted = $launchpadSteps[$key] ?? false;
                    $isCurrent = ($key === $highlightStep);
                @endphp
                <div style="
                    background: {{ $isCompleted ? '#f0fdf4' : ($isCurrent ? '#ffffff' : '#f8fafc') }};
                    border: 1.5px solid {{ $isCompleted ? '#bbf7d0' : ($isCurrent ? '#2E8B83' : '#e2e8f0') }};
                    border-radius: 1rem;
                    padding: 1.125rem 0.875rem;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    text-align: center;
                    position: relative;
                    box-shadow: {{ $isCurrent ? '0 8px 20px rgba(46,139,131,0.12)' : 'none' }};
                    transition: transform 0.2s, box-shadow 0.2s;
                " onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    
                    {{-- Step Icon --}}
                    <div style="
                        width: 2.75rem;
                        height: 2.75rem;
                        border-radius: 50%;
                        background: {{ $isCompleted ? '#dcfce7' : ($isCurrent ? '#E6F4F3' : '#ffffff') }};
                        color: {{ $isCompleted ? '#16a34a' : ($isCurrent ? '#2E8B83' : '#94a3b8') }};
                        border: 1px solid {{ $isCompleted ? '#86efac' : ($isCurrent ? '#B2DDD9' : '#e2e8f0') }};
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 1.15rem;
                        margin-bottom: 0.65rem;
                        flex-shrink: 0;
                    ">
                        <i class="fas {{ $isCompleted ? 'fa-check' : $data['icon'] }}"></i>
                    </div>

                    {{-- Step Title --}}
                    <h6 style="
                        font-size: 0.85rem;
                        font-weight: 800;
                        color: {{ $isCompleted ? '#15803d' : ($isCurrent ? '#0f172a' : '#475569') }};
                        margin: 0 0 0.75rem 0;
                        line-height: 1.3;
                    ">
                        {{ __('center::dashboard.launchpad.steps.'.$key.'.title') }}
                    </h6>

                    {{-- Action Button / Status --}}
                    <div style="margin-top: auto; width: 100%;">
                        @if($isCompleted)
                            <div style="color: #16a34a; font-size: 0.75rem; font-weight: 800; display: inline-flex; align-items: center; gap: 0.25rem;">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ __('center::launchpad.demo_data_ready') }}</span>
                            </div>
                        @elseif($key === 'education_system')
                            <button type="button" 
                                    style="
                                        width: 100%;
                                        padding: 0.35rem 0.65rem;
                                        border-radius: 0.5rem;
                                        font-size: 0.75rem;
                                        font-weight: 800;
                                        border: none;
                                        cursor: pointer;
                                        background: {{ $isCurrent ? '#2E8B83' : '#e2e8f0' }};
                                        color: {{ $isCurrent ? '#ffffff' : '#475569' }};
                                        transition: opacity 0.2s;
                                    "
                                    data-bs-toggle="modal" 
                                    data-bs-target="#educationSystemModal">
                               {{ __('center::dashboard.launchpad.action') }}
                            </button>
                        @else
                            <a href="{{ route($data['route'], ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}" 
                               style="
                                    width: 100%;
                                    display: block;
                                    padding: 0.35rem 0.65rem;
                                    border-radius: 0.5rem;
                                    font-size: 0.75rem;
                                    font-weight: 800;
                                    text-decoration: none;
                                    background: {{ $isCurrent ? '#2E8B83' : '#e2e8f0' }};
                                    color: {{ $isCurrent ? '#ffffff' : '#475569' }} !important;
                                    transition: opacity 0.2s;
                               ">
                               {{ __('center::dashboard.launchpad.action') }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Quick Education System Setup Modal -->
<div class="modal fade" id="educationSystemModal" tabindex="-1" aria-labelledby="educationSystemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" id="educationSystemModalLabel">
                    <i class="fas fa-map-signs text-primary me-2"></i> {{ __('center::dashboard.launchpad.steps.education_system.title') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('center.settings.apply-template', ['tenant' => app('tenant')->domain]) }}" method="POST" id="educationSystemForm">
                @csrf
                <div class="modal-body px-4 pb-4">
                    <p class="text-muted small mb-4">
                        {{ __('center::dashboard.launchpad.steps.education_system.desc') }}
                    </p>
                    
                    <label class="form-label fw-bold small text-muted mb-2">{{ __('center::launchpad.select_data_type') }}</label>
                    <select name="template_key" class="form-select rounded-pill mb-3" required>
                        <option value="">{{ __('center::launchpad.select_placeholder') }}</option>
                        @foreach(config('academic.templates', []) as $tKey => $template)
                            <option value="{{ $tKey }}">{{ __($template['name']) }}</option>
                        @endforeach
                    </select>

                    <div class="alert alert-soft-primary border-0 rounded-3 small py-2 px-3 mb-0">
                        <i class="fas fa-info-circle me-1"></i>{{ __('center::launchpad.demo_data_hint') }}</div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4 gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">{{ __('center::launchpad.cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" id="submitTemplateBtn">
                        <span class="normal-state">
                            <i class="fas fa-check-circle me-1"></i>{{ __('center::launchpad.start_generation') }}</span>
                        <span class="loading-state d-none">
                            <i class="fas fa-spinner fa-spin me-1"></i>{{ __('center::launchpad.generating') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('educationSystemForm')?.addEventListener('submit', function() {
        const btn = document.getElementById('submitTemplateBtn');
        if (btn) {
            btn.disabled = true;
            btn.querySelector('.normal-state')?.classList.add('d-none');
            btn.querySelector('.loading-state')?.classList.remove('d-none');
        }
    });
</script>
