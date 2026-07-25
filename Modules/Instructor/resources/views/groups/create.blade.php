@extends('layouts.app-next')

@section('title', __('instructor::groups.create_title') ?? 'Create Group')

@section('sidebar')
    @include('instructor::partials._sidebar-next', ['active' => 'groups'])
@endsection

@section('content')
    <x-ui.page-header
        title="{{ __('instructor::groups.group_details_new') }}"
        subtitle="{{ __('instructor::groups.group_hint') }}"
        :breadcrumb="[
            ['label' => __('instructor::sidebar.groups'), 'url' => route('instructor.groups.list')],
            ['label' => __('instructor::groups.create_new')]
        ]"
    >
        <x-slot name="actions">
            <x-ui.button variant="secondary" icon="fas fa-arrow-right" href="{{ route('instructor.groups.list') }}">
                {{ __('instructor::groups.back') }}
            </x-ui.button>
        </x-slot>
    </x-ui.page-header>

    <div class="max-w-3xl mx-auto">
        <x-ui.card>
            <form action="{{ route('instructor.groups.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Group Name -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">
                        {{ __('instructor::groups.group_name_placeholder_label') }} <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        required
                        placeholder="{{ __('instructor::groups.group_name_input_placeholder') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-brand-border dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 text-sm focus:bg-white dark:focus:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all @error('title') border-red-500 @enderror"
                    />
                    @error('title')
                        <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Group Description -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">
                        {{ __('instructor::groups.group_description') }}
                    </label>
                    <textarea
                        name="description"
                        rows="3"
                        placeholder="{{ __('instructor::groups.group_description_placeholder') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-brand-border dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 text-sm focus:bg-white dark:focus:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all @error('description') border-red-500 @enderror"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price & Sessions Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Price -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">
                            {{ __('instructor::groups.group_price_label') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="relative flex items-center">
                            <input
                                type="number"
                                name="price"
                                value="{{ old('price') }}"
                                required
                                step="0.01"
                                min="0"
                                placeholder="0.00"
                                class="w-full px-4 py-2.5 pe-16 rounded-xl border border-brand-border dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 text-sm focus:bg-white dark:focus:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all @error('price') border-red-500 @enderror"
                            />
                            <span class="absolute end-3 text-xs font-bold text-slate-400 select-none">
                                {{ app('tenant')->settings['currency'] ?? __('instructor::groups.currency') }}
                            </span>
                        </div>
                        @error('price')
                            <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sessions Count -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">
                            {{ __('instructor::groups.sessions_count') }} <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            name="sessions_count"
                            value="{{ old('sessions_count') }}"
                            required
                            min="1"
                            placeholder="{{ __('instructor::groups.sessions_placeholder') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-brand-border dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 text-sm focus:bg-white dark:focus:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all @error('sessions_count') border-red-500 @enderror"
                        />
                        @error('sessions_count')
                            <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Action Buttons -->
                <div class="flex items-center gap-3 pt-4 border-t border-brand-border dark:border-slate-800">
                    <x-ui.button type="submit" variant="primary" icon="fas fa-save" size="md">
                        {{ __('instructor::groups.save_group') }}
                    </x-ui.button>
                    <x-ui.button variant="secondary" size="md" href="{{ route('instructor.groups.list') }}">
                        {{ __('instructor::groups.back') }}
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
