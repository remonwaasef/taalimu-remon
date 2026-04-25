@extends('center::layouts.hope-master')

@section('page-title', __('center::questions.title'))
@section('page-subtitle', __('center::questions.subtitle'))

@section('page-actions')
    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#categoryModal">
        <i class="fas fa-tags me-2"></i>{{ __('center::questions.categories') }}
    </button>
    <a href="{{ route('center.questions.create') }}" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus me-2"></i>{{ __('center::questions.new_question') }}
    </a>
@endsection

@section('content')
<div class="container-fluid p-0">

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 ps-4">{{ __('center::questions.content') }}</th>
                                    <th class="border-0">{{ __('center::questions.category') }}</th>
                                    <th class="border-0">{{ __('center::questions.difficulty') }}</th>
                                    <th class="border-0">{{ __('center::questions.points') }}</th>
                                    <th class="border-0">{{ __('center::questions.type') }}</th>
                                    <th class="border-0 text-end pe-4">{{ __('center::questions.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($questions as $question)
                                <tr>
                                    <td class="ps-4">
                                        <div class="text-dark fw-semibold text-truncate" style="max-width: 300px;">{{ $question->content }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark rounded-pill px-3">{{ $question->category->name ?? __('center::questions.uncategorized') }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $diffColor = [
                                                'easy' => 'success',
                                                'medium' => 'warning',
                                                'hard' => 'danger'
                                            ][$question->difficulty] ?? 'secondary';
                                            
                                            $diffLabel = [
                                                'easy' => __('center::questions.easy'),
                                                'medium' => __('center::questions.medium'),
                                                'hard' => __('center::questions.hard')
                                            ][$question->difficulty] ?? $question->difficulty;
                                        @endphp
                                        <span class="badge bg-{{ $diffColor }} bg-opacity-10 text-{{ $diffColor }} rounded-pill px-3">
                                            {{ $diffLabel }}
                                        </span>
                                    </td>
                                    <td><span class="fw-bold text-primary">{{ $question->points }}</span>{{ __('center::questions.points_suffix') }}</td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $question->type == 'mcq' ? __('center::questions.mcq') : __('center::questions.true_false') }}
                                        </small>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ route('center.questions.edit', $question) }}" class="btn btn-light btn-sm rounded-circle" title="{{ __('center::questions.edit') }}">
                                                <i class="fas fa-edit text-primary"></i>
                                            </a>
                                            <form action="{{ route('center.questions.destroy', $question) }}" method="POST" onsubmit="return confirm('{{ __('center::questions.delete_confirm') }}')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-light btn-sm rounded-circle" title="{{ __('center::questions.delete') }}">
                                                    <i class="fas fa-trash text-danger"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <img src="{{ asset('assets/img/empty-box.png') }}" class="mb-3" style="width: 80px; opacity: 0.5;">
                                        <p class="text-muted">{{ __('center::questions.no_questions') }}</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($questions->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $questions->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">{{ __('center::questions.manage_categories') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('center.questions.categories.store') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="name" class="form-control rounded-start-pill" placeholder="{{ __('center::questions.category_placeholder') }}" required>
                                <button class="btn btn-primary rounded-end-pill px-4" type="submit">{{ __('center::questions.add') }}</button>
                    </div>
                </form>
                
                <h6 class="fw-bold small text-muted mb-3 text-uppercase">{{ __('center::questions.existing_categories') }}</h6>
                <div class="d-flex flex-wrap gap-2">
                    @forelse($categories as $category)
                        <span class="badge bg-light text-dark rounded-pill py-2 px-3 border">
                            {{ $category->name }}
                        </span>
                    @empty
                        <p class="small text-muted">{{ __('center::questions.no_categories') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
