@extends('center::layouts.master')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-4">
        <a href="{{ route('center.questions.index') }}" class="text-muted text-decoration-none small">
            <i class="fas fa-arrow-right me-1"></i>{{ __('center::messages.blade_0486') }}</a>
        <h4 class="fw-bold mt-2">{{ __('center::messages.blade_0487') }}</h4>
    </div>

    <form action="{{ route('center.questions.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('center::messages.blade_0488') }}</label>
                            <textarea name="content" class="form-control rounded-4" rows="4" placeholder="{{ __('center::messages.blade_0504') }}" required></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('center::messages.blade_0489') }}</label>
                            <div id="optionsContainer">
                                <div class="option-row mb-3 d-flex gap-3 align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="correct_option" value="0" checked>
                                    </div>
                                    <input type="text" name="options[0][content]" class="form-control rounded-pill" placeholder="{{ __('center::messages.blade_0505') }}" required>
                                </div>
                                <div class="option-row mb-3 d-flex gap-3 align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="correct_option" value="1">
                                    </div>
                                    <input type="text" name="options[1][content]" class="form-control rounded-pill" placeholder="{{ __('center::messages.blade_0506') }}" required>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 mt-2" onclick="addOption()">
                                <i class="fas fa-plus me-1"></i>{{ __('center::messages.blade_0490') }}</button>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold">{{ __('center::messages.blade_0491') }}</label>
                            <textarea name="explanation" class="form-control rounded-4" rows="2" placeholder="{{ __('center::messages.blade_0507') }}"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h6 class="fw-bold mb-0">{{ __('center::messages.blade_0492') }}</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">{{ __('center::messages.blade_0493') }}</label>
                            <select name="category_id" class="form-select rounded-pill">
                                <option value="">{{ __('center::messages.blade_0494') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">{{ __('center::messages.blade_0495') }}</label>
                            <select name="difficulty" class="form-select rounded-pill">
                                <option value="easy">{{ __('center::messages.blade_0496') }}</option>
                                <option value="medium" selected>{{ __('center::messages.blade_0497') }}</option>
                                <option value="hard">{{ __('center::messages.blade_0498') }}</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">{{ __('center::messages.blade_0499') }}</label>
                            <input type="number" name="points" class="form-control rounded-pill" value="1" min="1">
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">{{ __('center::messages.blade_0500') }}</label>
                            <select name="type" class="form-select rounded-pill" onchange="toggleType(this.value)">
                                <option value="mcq">{{ __('center::messages.blade_0501') }}</option>
                                <option value="true_false">{{ __('center::messages.blade_0502') }}</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 shadow-sm">
                            <i class="fas fa-save me-2"></i>{{ __('center::messages.blade_0503') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    let optionCount = 2;
    function addOption() {
        const container = document.getElementById('optionsContainer');
        const div = document.createElement('div');
        div.className = 'option-row mb-3 d-flex gap-3 align-items-center';
        div.innerHTML = `
            <div class="form-check">
                <input class="form-check-input" type="radio" name="correct_option" value="${optionCount}">
            </div>
            <input type="text" name="options[${optionCount}][content]" class="form-control rounded-pill" placeholder="{{ __('center::messages.blade_0508') }}" required>
            <button type="button" class="btn btn-link text-danger p-0" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(div);
        optionCount++;
    }

    function toggleType(type) {
        const container = document.getElementById('optionsContainer');
        const addBtn = document.querySelector('button[onclick="addOption()"]');
        
        if (type === 'true_false') {
            container.innerHTML = `
                <div class="option-row mb-3 d-flex gap-3 align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="correct_option" value="0" checked>
                    </div>
                    <input type="text" name="options[0][content]" class="form-control rounded-pill" value="{{ __('center::messages.blade_0509') }}" readonly>
                </div>
                <div class="option-row mb-3 d-flex gap-3 align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="correct_option" value="1">
                    </div>
                    <input type="text" name="options[1][content]" class="form-control rounded-pill" value="{{ __('center::messages.blade_0510') }}" readonly>
                </div>
            `;
            addBtn.style.display = 'none';
        } else {
            // Reset to MCQ
            addBtn.style.display = 'inline-block';
            // keep existing logic if needed or reset
        }
    }
</script>
@endsection
