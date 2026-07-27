@php
    $tenantData = $tenant ?? (app()->bound('tenant') ? app('tenant') : null);
    $layout = 'center::layouts.app-next';
    
    // Use precise path matching to avoid confusing /instructors (center) with /instructor/ (instructor module)
    $currentPath = request()->path();
    
    if (str_starts_with($currentPath, 'admin/') || $currentPath === 'admin') {
        $layout = 'admin::layouts.app-next';
        $hasLayout = true;
    } elseif (str_starts_with($currentPath, 'campus') || (auth()->check() && auth()->user()->hasRole('student'))) {
        $layout = 'layouts.app-next';
        $hasLayout = true;
    } elseif (str_starts_with($currentPath, 'instructor/') || $currentPath === 'instructor') {
        $layout = 'layouts.app-next';
        $hasLayout = true;
    } else {
        $hasLayout = $tenantData !== null;
    }
@endphp

@if($hasLayout)
    @extends($layout)

    @section('title', 'تعذر الوصول')
    @section('page-title', 'صلاحيات غير كافية')

    @section('panel-content')
    <div class="row justify-content-center align-items-center" style="min-height: 60vh;">
        <div class="col-lg-6 col-md-8 text-center">
            <div class="card border-0 shadow-sm rounded-4 text-center p-5 position-relative overflow-hidden">
                
                <!-- Decorative element -->
                <div class="position-absolute top-0 end-0 p-3 opacity-10">
                    <i class="fas fa-lock" style="font-size: 15rem; transform: rotate(15deg); margin-top: -50px; margin-right: -50px; color: #0f8b65;"></i>
                </div>

                <div class="position-relative z-index-1">
                    <div class="mb-4">
                        <div class="d-inline-flex p-4 rounded-circle mb-3 shadow-sm" style="background: rgba(15, 139, 101, 0.05); border: 1px solid rgba(15, 139, 101, 0.1);">
                            <i class="fas fa-user-shield" style="font-size: 4rem; color: #0f8b65;"></i>
                        </div>
                    </div>
                    <h1 class="display-1 fw-bold mb-0" style="color: #2b3445; letter-spacing: -2px;">403</h1>
                    <h3 class="fw-bold mb-3" style="color: #0f8b65;">عفواً، لا تملك الصلاحية</h3>
                    
                    <p class="text-muted mb-4 mx-auto" style="max-width: 450px; font-size: 1.1rem; line-height: 1.6;">
                        يبدو أنك تحاول الوصول إلى صفحة أو تنفيذ إجراء يتطلب صلاحيات أعلى في النظام.<br>
                        يرجى التواصل مع الإدارة إذا كنت تعتقد أن هذا الإجراء يجب أن يكون متاحاً لك.
                    </p>
                    
                    <div class="d-flex gap-3 justify-content-center mt-2">
                        <a href="javascript:history.back()" class="btn btn-primary px-4 py-2 rounded-3 fw-bold shadow-sm" style="background: #0f8b65; border-color: #0f8b65;">
                            <i class="fas fa-arrow-right me-2"></i> العودة للصفحة السابقة
                        </a>
                        <a href="/" class="btn btn-light px-4 py-2 rounded-3 fw-bold border">
                            <i class="fas fa-home me-2"></i> لوحة التحكم
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
@else
    <!DOCTYPE html>
    <html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>تعذر الوصول - 403</title>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
        <style>
            body { font-family: 'Cairo', sans-serif; background-color: #f8f9fa; height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; }
            .error-card { background: white; padding: 3rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); text-align: center; max-width: 500px; width: 90%; }
        </style>
    </head>
    <body>
        <div class="error-card">
            <i class="fas fa-user-shield mb-4" style="font-size: 4rem; color: #0f8b65;"></i>
            <h1 class="display-3 fw-bold mb-2">403</h1>
            <h4 class="fw-bold mb-3">عفواً، لا تملك الصلاحية</h4>
            <p class="text-muted mb-4">أنت لا تملك الصلاحيات الكافية للوصول إلى هذه الصفحة.</p>
            <a href="javascript:history.back()" class="btn btn-primary" style="background: #0f8b65; border-color: #0f8b65;"><i class="fas fa-arrow-right me-2"></i> العودة</a>
        </div>
    </body>
    </html>
@endif
