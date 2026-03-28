@extends('center::layouts.hope-master')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-dark mb-1">{{ isset($asset) ? __('center::assets.edit') : __('center::assets.add_new') }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('center.assets.index') }}" class="text-muted text-decoration-none">{{ __('center::assets.title') }}</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">{{ isset($asset) ? __('center::messages.blade_0105') : __('center::messages.blade_0106') }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form action="{{ isset($asset) ? route('center.assets.update', $asset) : route('center.assets.store') }}" method="POST">
                @csrf
                @if(isset($asset)) @method('PUT') @endif

                <div class="row g-3 mr-1">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control rounded-3" id="name" placeholder="Name" value="{{ old('name', $asset->name ?? '') }}" required>
                            <label for="name">{{ __('center::assets.name') }} *</label>
                            @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="code" class="form-control rounded-3" id="code" placeholder="Code" value="{{ old('code', $asset->code ?? '') }}">
                            <label for="code">{{ __('center::assets.code') }}</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <select class="form-select rounded-3" id="type" name="type" required>
                                <option value="equipment" {{ (old('type', $asset->type ?? '') == 'equipment') ? 'selected' : '' }}>{{ __('center::assets.equipment') }}</option>
                                <option value="furniture" {{ (old('type', $asset->type ?? '') == 'furniture') ? 'selected' : '' }}>{{ __('center::assets.furniture') }}</option>
                                <option value="electronics" {{ (old('type', $asset->type ?? '') == 'electronics') ? 'selected' : '' }}>{{ __('center::assets.electronics') }}</option>
                                <option value="other" {{ (old('type', $asset->type ?? '') == 'other') ? 'selected' : '' }}>{{ __('center::assets.other') }}</option>
                            </select>
                            <label for="type">{{ __('center::assets.type') }}</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <select class="form-select rounded-3" id="status" name="status" required>
                                <option value="active" {{ (old('status', $asset->status ?? '') == 'active') ? 'selected' : '' }}>{{ __('center::assets.active') }}</option>
                                <option value="maintenance" {{ (old('status', $asset->status ?? '') == 'maintenance') ? 'selected' : '' }}>{{ __('center::assets.maintenance') }}</option>
                                <option value="broken" {{ (old('status', $asset->status ?? '') == 'broken') ? 'selected' : '' }}>{{ __('center::assets.broken') }}</option>
                                <option value="lost" {{ (old('status', $asset->status ?? '') == 'lost') ? 'selected' : '' }}>{{ __('center::assets.lost') }}</option>
                            </select>
                            <label for="status">{{ __('center::assets.status') }}</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <select class="form-select rounded-3" id="classroom_id" name="classroom_id">
                                <option value="">{{ __('center::assets.none') }}</option>
                                @foreach($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}" {{ (old('classroom_id', $asset->classroom_id ?? request('classroom_id')) == $classroom->id) ? 'selected' : '' }}>{{ $classroom->name }}</option>
                                @endforeach
                            </select>
                            <label for="classroom_id">{{ __('center::assets.classroom') }}</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="date" name="purchase_date" class="form-control rounded-3" id="purchase_date" value="{{ old('purchase_date', isset($asset) && $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : '') }}">
                            <label for="purchase_date">{{ __('center::assets.purchase_date') }}</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="number" step="0.01" name="cost" class="form-control rounded-3" id="cost" placeholder="Cost" value="{{ old('cost', $asset->cost ?? '') }}">
                            <label for="cost">{{ __('center::assets.cost') }}</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating mb-4">
                            <textarea name="notes" class="form-control rounded-3" id="notes" style="height: 100px">{{ old('notes', $asset->notes ?? '') }}</textarea>
                            <label for="notes">{{ __('center::assets.notes') }}</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-sm fw-bold">{{ __('center::messages.blade_0103') }}</button>
                    <a href="{{ route('center.assets.index') }}" class="btn btn-light px-4 rounded-pill border">{{ __('center::messages.blade_0104') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
