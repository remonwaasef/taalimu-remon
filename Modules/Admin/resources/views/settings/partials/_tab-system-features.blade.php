                    <div class="tab-pane fade" id="system-features" role="tabpanel">
                        
                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="fw-bold mb-1"><i class="bi bi-puzzle me-2"></i> إدارة ميزات النظام</h5>
                                <p class="text-muted small mb-0">أضف ميزات جديدة وتحكم في أي خطة تظهر فيها</p>
                            </div>
                            <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addFeatureModal">
                                <i class="bi bi-plus-lg me-1"></i> ميزة جديدة
                            </button>
                        </div>

                        <!-- How it works - Guide -->
                        <div class="alert border-0 rounded-4 shadow-sm mb-4 p-0 overflow-hidden" style="background: linear-gradient(135deg, #f0f4ff 0%, #e8f5e9 100%);">
                            <div class="p-3">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-lightbulb text-warning fs-4 me-2"></i>
                                    <h6 class="fw-bold mb-0">كيف يعمل النظام؟ (3 خطوات فقط)</h6>
                                    <button class="btn btn-sm btn-link text-muted ms-auto p-0" type="button" data-bs-toggle="collapse" data-bs-target="#howItWorks">
                                        <i class="bi bi-chevron-down"></i>
                                    </button>
                                </div>
                                <div class="collapse show" id="howItWorks">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="bg-white rounded-3 p-3 h-100 border text-center">
                                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:40px;height:40px;">
                                                    <span class="fw-bold text-primary">1</span>
                                                </div>
                                                <h6 class="fw-bold small mb-1">أضف الميزة</h6>
                                                <p class="text-muted mb-0" style="font-size:0.75rem;">اضغط "ميزة جديدة" واكتب اسمها</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="bg-white rounded-3 p-3 h-100 border text-center">
                                                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:40px;height:40px;">
                                                    <span class="fw-bold text-success">2</span>
                                                </div>
                                                <h6 class="fw-bold small mb-1">اختر الخطط</h6>
                                                <p class="text-muted mb-0" style="font-size:0.75rem;">فعّلها في الخطط التي تريدها</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="bg-white rounded-3 p-3 h-100 border text-center">
                                                <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:40px;height:40px;">
                                                    <span class="fw-bold text-info">3</span>
                                                </div>
                                                <h6 class="fw-bold small mb-1">تلقائياً!</h6>
                                                <p class="text-muted mb-0" style="font-size:0.75rem;">الميزة تظهر/تختفي تلقائياً للعملاء</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php
                            $groupedFeatures = $features->groupBy('category');
                            $categoryIcons = [
                                'core' => 'bi-cpu',
                                'smart' => 'bi-stars text-warning',
                                'analysis' => 'bi-graph-up-arrow text-info',
                                'academic' => 'bi-journal-text text-primary',
                                'default' => 'bi-box'
                            ];
                            $categoryNames = [
                                'core' => 'الأساسيات (Core)',
                                'smart' => 'الميزات الذكية (Smart)',
                                'analysis' => 'التحليلات (Analysis)',
                                'academic' => 'الأكاديمي (Academic)'
                            ];
                        @endphp

                        @forelse($groupedFeatures as $category => $catFeatures)
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3 pb-2 border-bottom d-flex align-items-center">
                                    <i class="bi {{ $categoryIcons[$category] ?? $categoryIcons['default'] }} me-2 fs-5"></i> 
                                    {{ $categoryNames[$category] ?? ucfirst($category) }}
                                    <span class="badge bg-light text-muted ms-auto rounded-pill border small">{{ $catFeatures->count() }}</span>
                                </h6>
                                <div class="row g-3">
                                    @foreach($catFeatures as $feature)
                                    @php
                                        $assignedPackages = $feature->packages ?? collect();
                                    @endphp
                                    <div class="col-md-6 col-xl-4">
                                        <div class="card h-100 border-0 shadow-sm rounded-4" style="background: #f8f9fa;">
                                            <div class="card-body p-3">
                                                <!-- Feature Name & Actions -->
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="bg-white p-2 rounded-3 shadow-sm border">
                                                            <i class="bi {{ $feature->type == 'limit' ? 'bi-sliders text-info' : 'bi-toggle-on text-success' }} fs-5"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="fw-bold mb-0 lh-sm">{{ $feature->name }}</h6>
                                                            <small class="text-muted" dir="ltr">{{ $feature->name_en }}</small>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-link text-muted p-0 border-0" type="button" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-3 text-end">
                                                            <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal" data-bs-target="#editFeatureModal{{ $feature->id }}"><i class="bi bi-pencil me-2 text-primary"></i> تعديل</a></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><a class="dropdown-item py-2 text-danger" href="#" onclick="if(confirm('هل أنت متأكد من حذف هذه الميزة؟')) document.getElementById('delete-feature-{{ $feature->id }}').submit()"><i class="bi bi-trash me-2"></i> حذف</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                
                                                <!-- Assigned Plans -->
                                                <div class="mt-3 pt-2 border-top">
                                                    <small class="text-muted d-block mb-2"><i class="bi bi-link-45deg me-1"></i> مفعّلة في:</small>
                                                    @if($assignedPackages->count() > 0)
                                                        <div class="d-flex flex-wrap gap-1">
                                                            @foreach($assignedPackages as $pkg)
                                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2 py-1" style="font-size:0.7rem;">
                                                                    <i class="bi bi-check-circle-fill me-1"></i>{{ $pkg->name }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-2 py-1" style="font-size:0.7rem;">
                                                            <i class="bi bi-exclamation-triangle me-1"></i> غير مفعّلة في أي خطة
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-puzzle text-muted opacity-25" style="font-size: 4rem;"></i>
                                <p class="text-muted mt-3 mb-1">لا توجد ميزات مضافة حتى الآن</p>
                                <p class="text-muted small">اضغط على "ميزة جديدة" لإنشاء أول ميزة</p>
                            </div>
                        @endforelse
                    </div>
