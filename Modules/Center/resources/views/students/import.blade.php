@extends('center::layouts.hope-master')

@section('title', __('center::students.import.title'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">{{ __('center::students.import.header') }}</h2>
        <a href="{{ route('center.students.index', ['tenant' => app('tenant')->domain]) }}" class="btn btn-outline-secondary rounded-pill px-4">
            ← العودة للقائمة
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <h5 class="fw-bold mb-0">استيراد بيانات الطلاب</h5>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success rounded-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger rounded-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Tabs -->
                    <ul class="nav nav-pills mb-4" id="importTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill px-4" id="paste-tab" data-bs-toggle="pill" data-bs-target="#paste-panel" type="button" role="tab">
                                📋 لصق من Excel (مُوصى به)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-4 ms-2" id="file-tab" data-bs-toggle="pill" data-bs-target="#file-panel" type="button" role="tab">
                                📁 رفع ملف CSV
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="importTabsContent">
                        <!-- Tab 1: Paste from Excel -->
                        <div class="tab-pane fade show active" id="paste-panel" role="tabpanel">
                            <form action="{{ route('center.students.import.post', ['tenant' => app('tenant')->domain]) }}" method="POST" id="pasteForm">
                                @csrf
                                <input type="hidden" name="import_method" value="paste">

                                <!-- Download Template -->
                                <div class="alert alert-light border rounded-4 mb-4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="fw-bold mb-1">📥 تحميل ملف نموذجي</h6>
                                            <small class="text-muted">{{ __('center::students.import.desc') }}</small>
                                        </div>
                                        <a href="{{ route('center.students.template', ['tenant' => app('tenant')->domain]) }}" class="btn btn-outline-primary rounded-pill px-4">{{ __('center::students.import.download_template') }}</a>
                                    </div>
                                </div>

                                <div class="alert alert-success bg-success bg-opacity-10 border-0 rounded-4 mb-3">
                                    <h6 class="fw-bold mb-2">✅ الطريقة الأسهل والأسرع:</h6>
                                    <ol class="mb-0 pe-3" style="font-size: 14px;">
                                        <li class="mb-1">حمّل الملف النموذجي أعلاه وافتحه في Excel</li>
                                        <li class="mb-1">أضف بيانات طلابك (الاسم، الإيميل، الهاتف، الصف)</li>
                                        <li class="mb-1">حدد جميع البيانات <strong>(بدون صف العناوين)</strong> ثم انسخها <kbd>Ctrl+C</kbd></li>
                                        <li>الصقها في المربع أدناه <kbd>Ctrl+V</kbd> ثم اضغط "استيراد"</li>
                                    </ol>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">الصق بيانات الطلاب هنا:</label>
                                    <textarea name="paste_data" id="pasteArea" class="form-control" rows="10" required
                                        style="font-family: monospace; font-size: 13px; direction: ltr;"
                                        placeholder="Ahmed Ali&#9;ahmed@example.com&#9;01012345678&#9;الصف الأول الابتدائي&#10;Sara Khaled&#9;sara@example.com&#9;01023456789&#9;الصف الثاني الابتدائي"></textarea>
                                    <small class="form-text text-muted mt-1 d-block">
                                        كل سطر = طالب واحد. الأعمدة مفصولة بـ Tab (تلقائي عند النسخ من Excel).
                                    </small>
                                </div>

                                <!-- Live Preview -->
                                <div id="previewSection" class="mb-4 d-none">
                                    <h6 class="fw-bold mb-2">👀 معاينة البيانات (<span id="previewCount">0</span> طالب):</h6>
                                    <div class="border rounded" style="max-height: 250px; overflow-y: auto; overflow-x: hidden; position: relative;">
                                        <table class="table table-sm table-bordered table-hover mb-0" id="previewTable" style="table-layout: fixed; width: 100%; font-size: 14px;">
                                            <thead class="table-dark" style="position: sticky; top: 0; z-index: 1;">
                                                <tr>
                                                    <th style="width: 50px;">#</th>
                                                    <th>الاسم</th>
                                                    <th>الإيميل</th>
                                                    <th>الهاتف</th>
                                                    <th>الصف</th>
                                                </tr>
                                            </thead>
                                            <tbody id="previewBody"></tbody>
                                        </table>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary rounded-pill px-5 py-2" id="pasteSubmitBtn" disabled>
                                    <span class="me-2">📤</span>{{ __('center::students.import.start_import') }}
                                </button>
                            </form>
                        </div>

                        <!-- Tab 2: File Upload (CSV only) -->
                        <div class="tab-pane fade" id="file-panel" role="tabpanel">
                            <form action="{{ route('center.students.import.post', ['tenant' => app('tenant')->domain]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="import_method" value="file">

                                <div class="mb-4">
                                    <label for="file" class="form-label fw-bold">اختر ملف CSV</label>
                                    <input type="file" class="form-control rounded-pill" id="file" name="file" required accept=".csv,.txt">
                                    <small class="form-text text-muted mt-2 d-block">
                                        الحقول الإلزامية: <strong>name, email</strong>. الحقول الاختيارية: phone, grade_level.
                                    </small>
                                </div>

                                <div class="alert alert-info rounded-4 bg-info bg-opacity-10 border-0">
                                    <h6 class="fw-bold text-info mb-2">📋 صيغة الملف المطلوبة:</h6>
                                    <div class="bg-white p-3 rounded-3 border">
                                        <div class="mb-2">
                                            <strong>{{ __('center::students.import.first_row_headers') }}</strong><br>
                                            <code class="text-dark">name,email,phone,grade_level</code>
                                        </div>
                                        <div>
                                            <strong>{{ __('center::students.import.data_example') }}</strong><br>
                                            <code class="text-dark">Ahmed Ali,ahmed@example.com,0501234567,الصف الأول الابتدائي</code>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary rounded-pill px-5 py-2">
                                    <span class="me-2">📤</span>{{ __('center::students.import.start_import') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Guide -->
            <div class="card border-0 shadow-sm rounded-4 mt-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">💡 إرشادات سريعة</h6>
                    <ul class="mb-0">
                        <li class="mb-2">{{ __('center::students.import.headers_must_match') }}</li>
                        <li class="mb-2">{{ __('center::students.import.unique_email_tip') }}</li>
                        <li>{{ __('center::students.import.default_password_tip') }}<code>'.Str::random(12).'</code> (تُنشأ عشوائياً)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pasteArea = document.getElementById('pasteArea');
    const previewSection = document.getElementById('previewSection');
    const previewBody = document.getElementById('previewBody');
    const previewCount = document.getElementById('previewCount');
    const submitBtn = document.getElementById('pasteSubmitBtn');

    pasteArea.addEventListener('input', function() {
        const text = this.value.trim();
        if (!text) {
            previewSection.classList.add('d-none');
            submitBtn.disabled = true;
            return;
        }

        const lines = text.split('\n').filter(line => line.trim());
        previewBody.innerHTML = '';
        let count = 0;
        lines.forEach(function(line, i) {
            // Split by tab (Excel clipboard) or comma
            let cols = line.split('\t');
            if (cols.length < 2) cols = line.split(',');

            const name = (cols[0] || '').trim();
            const email = (cols[1] || '').trim();
            const phone = (cols[2] || '').trim();
            const grade = (cols[3] || '').trim();

            if (name && email) {
                count++;
                const tr = document.createElement('tr');
                
                const td1 = document.createElement('td');
                td1.textContent = count;
                tr.appendChild(td1);
                
                const td2 = document.createElement('td');
                td2.textContent = name;
                tr.appendChild(td2);
                
                const td3 = document.createElement('td');
                td3.dir = 'ltr';
                td3.textContent = email;
                tr.appendChild(td3);
                
                const td4 = document.createElement('td');
                td4.dir = 'ltr';
                td4.textContent = phone;
                tr.appendChild(td4);
                
                const td5 = document.createElement('td');
                td5.textContent = grade;
                tr.appendChild(td5);
                
                previewBody.appendChild(tr);
            }
        });

        previewCount.textContent = count;
        previewSection.classList.toggle('d-none', count === 0);
        submitBtn.disabled = (count === 0);
    });

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
</script>
@endpush
