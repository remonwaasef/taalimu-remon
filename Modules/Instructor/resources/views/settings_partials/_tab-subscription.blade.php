                        <div class="tab-pane fade" id="subscription" role="tabpanel">
                            @php
                                $subscription = $tenant->currentSubscription;
                                $package = $subscription ? $subscription->resolved_package : null;
                                $service = app(\App\Services\SubscriptionService::class);
                            @endphp

                            {{-- 1. Consumption Overview (Status) --}}
                            <div class="bg-light rounded-4 p-4 border mb-5">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h6 class="fw-bold mb-0 text-primary"><i class="fas fa-chart-pie me-2"></i> {{ __('instructor::settings.resource_consumption') }}</h6>
                                    @if($subscription)
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-success py-2 px-3 rounded-pill shadow-sm"><i class="fas fa-check-circle me-1"></i> {{ __('instructor::settings.active_subscription') }}</span>
                                            @if($subscription->ends_at)
                                                <small class="text-muted fw-bold x-small">{{ __('instructor::settings.expires_on', ['date' => $subscription->ends_at->format('d/m/Y')]) }}</small>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <div class="row g-4">
                                    @php
                                        $features = [
                                            ['code' => 'max_students', 'label' => __('instructor::settings.students'), 'icon' => 'fa-user-graduate'],
                                            ['code' => 'max_courses', 'label' => __('instructor::settings.groups'), 'icon' => 'fa-users'],
                                            ['code' => 'max_instructors', 'label' => __('instructor::settings.assistants'), 'icon' => 'fa-chalkboard-teacher'],
                                        ];
                                    @endphp

                                    @foreach($features as $f)
                                        @php
                                            $limit = $service->getFeatureValue($tenant, $f['code']);
                                            $usage = 0;
                                            if($f['code'] == 'max_students') $usage = $tenant->users()->where('role', 'student')->count();
                                            if($f['code'] == 'max_courses') $usage = \App\Models\Course::where('tenant_id', $tenant->id)->count();
                                            if($f['code'] == 'max_instructors') $usage = \App\Models\Instructor::where('tenant_id', $tenant->id)->count();
                                            
                                            $isUnlimited = $limit === 'unlimited' || $limit == -1;
                                            $percent = 0;
                                            if (!$isUnlimited && is_numeric($limit) && $limit > 0) {
                                                $percent = min(100, ($usage / (float)$limit) * 100);
                                            } elseif (!$isUnlimited) {
                                                $percent = 100;
                                            }
                                            $color = $percent > 90 ? 'danger' : ($percent > 70 ? 'warning' : 'success');
                                        @endphp
                                        <div class="col-md-4">
                                            <div class="bg-white rounded-4 p-3 border shadow-sm h-100">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                                            <i class="fas {{ $f['icon'] }} x-small"></i>
                                                        </div>
                                                        <span class="small fw-bold">{{ $f['label'] }}</span>
                                                    </div>
                                                    <span class="x-small text-muted fw-bold" dir="ltr">{{ $usage }} / {{ $isUnlimited ? '∞' : $limit }}</span>
                                                </div>
                                                <div class="progress rounded-pill shadow-none mb-1" style="height: 6px; background: #f1f5f9;">
                                                    <div class="progress-bar bg-{{ $color }} rounded-pill" role="progressbar" style="width: {{ $percent }}%"></div>
                                                </div>
                                                <div class="text-start">
                                                    <span class="x-small text-{{ $color }} fw-bold">{{ round($percent) }}%</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div x-data="{ billingCycle: 'monthly' }">
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="fas fa-layer-group small"></i>
                                        </div>
                                        <h5 class="fw-bold mb-0">{{ __('instructor::settings.plans_and_upgrades') }}</h5>
                                    </div>

                                    <!-- Cycle Switcher -->
                                    <div class="bg-light p-1 rounded-pill d-flex shadow-sm" style="width: 280px;">
                                        <button type="button" @click="billingCycle = 'monthly'" 
                                                class="btn btn-sm flex-grow-1 rounded-pill font-bold transition-all"
                                                :class="billingCycle === 'monthly' ? 'btn-primary shadow-sm' : 'btn-link text-muted text-decoration-none'">
                                            {{ __('instructor::settings.monthly') }}
                                        </button>
                                        <button type="button" @click="billingCycle = 'term'" 
                                                class="btn btn-sm flex-grow-1 rounded-pill font-bold transition-all"
                                                :class="billingCycle === 'term' ? 'btn-primary shadow-sm' : 'btn-link text-muted text-decoration-none'">
                                            {{ __('instructor::settings.term') }}
                                        </button>
                                        <button type="button" @click="billingCycle = 'yearly'" 
                                                class="btn btn-sm flex-grow-1 rounded-pill font-bold transition-all"
                                                :class="billingCycle === 'yearly' ? 'btn-primary shadow-sm' : 'btn-link text-muted text-decoration-none'">
                                            {{ __('instructor::settings.yearly') }}
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="row g-4">
                                    @foreach($packages as $pkg)
                                        @php
                                            $isCurrent = $package && $package->id == $pkg->id;
                                        @endphp
                                        <div class="col-md-4">
                                            <div class="card border rounded-4 shadow-sm h-100 {{ $isCurrent ? 'border-primary border-2' : ($pkg->is_featured ? 'border-primary' : '') }} position-relative overflow-hidden transition-all hover-shadow">
                                                
                                                @if($isCurrent)
                                                    <div class="bg-primary text-white text-center py-2 fw-bold" style="font-size: 11px;">
                                                        <i class="fas fa-star me-1"></i> {{ __('instructor::settings.current_plan') }}
                                                    </div>
                                                @elseif($pkg->is_featured)
                                                    <div class="bg-secondary text-white text-center py-1 position-absolute w-100" style="top: 0; left: 0; font-size: 10px; font-weight: bold; z-index: 10;">
                                                        {{ __('instructor::settings.recommended') }}
                                                    </div>
                                                @endif

                                                <div class="card-body p-4 {{ $isCurrent ? 'pt-4' : 'pt-5' }}">
                                                    <h5 class="fw-bold mb-2">{{ $pkg->name }}</h5>
                                                     <div class="mb-4">
                                                        <div x-show="billingCycle === 'monthly'" class="animate-fade-in">
                                                            <div class="d-flex align-items-baseline gap-1">
                                                                <span class="fs-4 fw-bold text-primary">{{ number_format((float)$pkg->price) }}</span>
                                                                <small class="text-muted x-small">{{ app('tenant')->settings['currency'] ?? 'EGP' }} / {{ __('instructor::settings.monthly') }}</small>
                                                            </div>
                                                        </div>
                                                        @if($pkg->term_price)
                                                        <div x-show="billingCycle === 'term'" class="animate-fade-in" style="display: none;">
                                                            <div class="d-flex align-items-baseline gap-1">
                                                                <span class="fs-4 fw-bold text-primary">{{ number_format((float)$pkg->term_price) }}</span>
                                                                <small class="text-muted x-small">{{ app('tenant')->settings['currency'] ?? 'EGP' }} / {{ __('instructor::settings.term') }}</small>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        @if($pkg->yearly_price)
                                                        <div x-show="billingCycle === 'yearly'" class="animate-fade-in" style="display: none;">
                                                            <div class="d-flex align-items-baseline gap-1">
                                                                <span class="fs-4 fw-bold text-primary">{{ number_format((float)$pkg->yearly_price) }}</span>
                                                                <small class="text-muted x-small">{{ app('tenant')->settings['currency'] ?? 'EGP' }} / {{ __('instructor::settings.yearly') }}</small>
                                                            </div>
                                                        </div>
                                                        @endif
                                                     </div>
                                                    
                                                    <hr class="opacity-25 mb-4">

                                                    <ul class="list-unstyled mb-4">
                                                        @foreach($pkg->features as $feature)
                                                            @php
                                                                $val = $feature->pivot->value;
                                                                $displayVal = $val;
                                                                if($val == '-1' || $val == 'unlimited') $displayVal = __('instructor::settings.unlimited');
                                                                
                                                                $icon = 'fa-check-circle text-success';
                                                                if($feature->type == 'boolean') {
                                                                    $displayVal = filter_var($val, FILTER_VALIDATE_BOOLEAN) ? __('instructor::settings.available') : __('instructor::settings.not_available');
                                                                    $icon = filter_var($val, FILTER_VALIDATE_BOOLEAN) ? 'fa-check-circle text-success' : 'fa-times-circle text-danger';
                                                                }
                                                            @endphp
                                                            <li class="small mb-2 d-flex align-items-center gap-2">
                                                                <i class="fas {{ $icon }}" style="font-size: 12px;"></i>
                                                                <span class="text-muted">{{ $feature->name }}:</span>
                                                                <span class="fw-bold">{{ $displayVal }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>

                                                    @if($isCurrent)
                                                        <div class="alert alert-primary bg-opacity-10 border-0 mb-0 py-3 text-center rounded-4">
                                                            <span class="fw-bold small text-primary"><i class="fas fa-check-circle me-1"></i> {{ __('instructor::settings.active_subscription') }}</span>
                                                            @if($subscription->ends_at)
                                                                 <div class="x-small text-muted mt-1">{{ __('instructor::settings.expires_on', ['date' => $subscription->ends_at->format('d/m/Y')]) }}</div>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <a :href="'{{ route('center.subscription.checkout', ['package' => $pkg->id, 'tenant' => $tenant->domain ?? $tenant->id]) }}?cycle=' + billingCycle" class="btn btn-outline-primary rounded-pill w-100 fw-bold py-2">{{ __('instructor::settings.subscribe_now') }}</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
