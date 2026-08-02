@extends('center::layouts.app-next')

@section('title', __('center::courses.add_new'))

@section('panel-content')
    <x-ui.page-header
        title="{{ __('center::courses.add_new') }}"
        subtitle="{{ __('center::courses.create_subtitle') ?? 'قم بإدخال تفاصيل الدورة ومواعيد الحصص الدراسية' }}"
        :breadcrumb="[
            ['label' => __('center::dashboard.title'), 'url' => route('center.dashboard')],
            ['label' => __('center::courses.title'), 'url' => route('center.courses.index')],
            ['label' => __('center::courses.add_new')]
        ]"
    >
        <x-slot name="actions">
            <x-ui.button variant="outline" icon="fas fa-arrow-right" href="{{ route('center.courses.index') }}">
                {{ __('center::courses.cancel') }}
            </x-ui.button>
        </x-slot>
    </x-ui.page-header>

    <div class="max-w-4xl mx-auto">
        <x-ui.card>
            <form action="{{ route('center.courses.store') }}" method="POST" enctype="multipart/form-data" data-autosave="create-course">
                @csrf

                @if ($errors->any())
                    <div class="p-4 mb-6 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-rose-700 dark:text-rose-300 text-sm">
                        <div class="font-bold mb-1 flex items-center gap-2">
                            <i class="fas fa-exclamation-circle text-rose-500"></i>
                            <span>توجد أخطاء في المدخلات:</span>
                        </div>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-6">
                    <!-- Basic Info Section Header -->
                    <div class="border-b border-brand-border dark:border-slate-800 pb-3 mb-4 flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 flex items-center justify-center text-sm font-bold">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 font-inter">بيانات الدورة الأساسية</h3>
                    </div>
                    
                    <!-- Course Title -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            {{ __('center::courses.course_name') }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" 
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm @error('title') border-rose-500 focus:ring-rose-500/20 @enderror"
                               placeholder="مثال: دورة الرياضيات للصف الثالث الثانوي">
                        @error('title')
                            <p class="text-rose-500 text-xs mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Instructor Selection -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            {{ __('center::courses.instructor') }} <span class="text-rose-500">*</span>
                        </label>
                        <select name="instructor_id" 
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm @error('instructor_id') border-rose-500 focus:ring-rose-500/20 @enderror">
                            <option value="">{{ __('center::courses.choose_instructor') }}</option>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>{{ $instructor->name }}</option>
                            @endforeach
                        </select>
                        @error('instructor_id')
                            <p class="text-rose-500 text-xs mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price, Sessions Count, Status Row -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <!-- Price -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                                {{ __('center::courses.price') }} ({{ get_currency_symbol() }})
                            </label>
                            <input type="number" name="price" value="{{ old('price', 0) }}" 
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm @error('price') border-rose-500 @enderror" 
                                   min="0" step="0.01">
                            @error('price')
                                <p class="text-rose-500 text-xs mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sessions Count -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                                {{ __('center::courses.sessions_count') }}
                            </label>
                            <input type="number" name="sessions_count" value="{{ old('sessions_count', 0) }}" 
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm @error('sessions_count') border-rose-500 @enderror" 
                                   min="0">
                            @error('sessions_count')
                                <p class="text-rose-500 text-xs mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Option -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                                {{ __('center::courses.status_label') }}
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="status" id="status_draft" value="draft" class="peer hidden" {{ old('status', 'draft') == 'draft' ? 'checked' : '' }}>
                                    <div class="px-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 peer-checked:bg-slate-900 peer-checked:text-white peer-checked:border-slate-900 dark:peer-checked:bg-slate-100 dark:peer-checked:text-slate-900 transition-all text-center text-xs font-bold flex items-center justify-center gap-1.5">
                                        <i class="fas fa-pencil-alt"></i>
                                        <span>{{ __('center::courses.status_draft') }}</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="status" id="status_published" value="published" class="peer hidden" {{ old('status') == 'published' ? 'checked' : '' }}>
                                    <div class="px-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 transition-all text-center text-xs font-bold flex items-center justify-center gap-1.5">
                                        <i class="fas fa-check-circle"></i>
                                        <span>{{ __('center::courses.status_published') }}</span>
                                    </div>
                                </label>
                            </div>
                            @error('status')
                                <p class="text-rose-500 text-xs mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            {{ __('center::courses.course_image') }}
                        </label>
                        <input type="file" name="image" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-700 dark:text-slate-300 text-xs file:me-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all @error('image') border-rose-500 @enderror" 
                               accept="image/*">
                        @error('image')
                            <p class="text-rose-500 text-xs mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            {{ __('center::courses.description') }}
                        </label>
                        <textarea name="description" rows="3" 
                                  class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm @error('description') border-rose-500 @enderror" 
                                  placeholder="اكتب وصفاً موجزاً ومميزاً للدورة الدراسية...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-rose-500 text-xs mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Schedule Section Header -->
                    <div class="border-t border-brand-border dark:border-slate-800 pt-6 mt-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-sky-50 dark:bg-sky-950/40 text-sky-600 flex items-center justify-center text-sm font-bold">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 font-inter">{{ __('center::courses.course_schedules') }}</h3>
                            </div>
                            
                            <button type="button" id="add-schedule-btn" class="px-4 py-2 rounded-xl bg-sky-50 dark:bg-sky-950/30 text-sky-600 dark:text-sky-400 hover:bg-sky-100 dark:hover:bg-sky-900/50 text-xs font-bold transition-all flex items-center gap-2 border border-sky-200 dark:border-sky-800/40">
                                <i class="fas fa-plus"></i>
                                <span>{{ __('center::courses.add_schedule') }}</span>
                            </button>
                        </div>

                        <!-- Schedule Count Info Bar -->
                        <div id="schedule-count-info" class="p-3.5 rounded-xl text-xs font-bold mb-4 flex items-center gap-2 transition-all" style="display:none;">
                            <i class="fas fa-info-circle text-base"></i>
                            <span id="schedule-count-text"></span>
                        </div>
                        
                        <!-- Schedules Container -->
                        <div id="schedules-container" class="space-y-4">
                            <!-- Dynamic Schedules will be added here -->
                        </div>
                    </div>

                    <!-- Dynamic Schedule Template -->
                    <template id="schedule-template">
                        <div class="schedule-item p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 relative group transition-all">
                            <div class="flex items-center justify-between mb-3 border-b border-slate-200/80 dark:border-slate-700/80 pb-2">
                                <h6 class="text-xs font-bold text-emerald-600 dark:text-emerald-400 font-inter flex items-center gap-1.5">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ __('center::schedules.item_number') }} <span class="schedule-index"></span></span>
                                </h6>
                                <button type="button" class="remove-schedule w-7 h-7 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center text-xs">
                                    <i class="fas fa-times pointer-events-none"></i>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">{{ __('center::schedules.day') }}</label>
                                    <select name="schedules[INDEX][day_of_week]" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs">
                                        <option value="saturday">{{ __('center::schedules.saturday') }}</option>
                                        <option value="sunday">{{ __('center::schedules.sunday') }}</option>
                                        <option value="monday">{{ __('center::schedules.monday') }}</option>
                                        <option value="tuesday">{{ __('center::schedules.tuesday') }}</option>
                                        <option value="wednesday">{{ __('center::schedules.wednesday') }}</option>
                                        <option value="thursday">{{ __('center::schedules.thursday') }}</option>
                                        <option value="friday">{{ __('center::schedules.friday') }}</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">{{ __('center::schedules.classroom') }}</label>
                                    <select name="schedules[INDEX][classroom_id]" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs">
                                        <option value="">{{ __('center::schedules.choose_classroom') }}</option>
                                        @foreach($classrooms as $classroom)
                                            <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">{{ __('center::schedules.from') }}</label>
                                    <input type="time" name="schedules[INDEX][start_time]" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">{{ __('center::schedules.to') }}</label>
                                    <input type="time" name="schedules[INDEX][end_time]" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 text-xs">
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-brand-border dark:border-slate-800">
                        <button type="submit" id="submit-btn" 
                                class="w-full py-3.5 px-6 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md shadow-emerald-600/20 transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-check-circle"></i>
                            <span>{{ __('center::courses.save_course') }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection

@push('scripts')
@include('center::courses.partials._create-scripts')
@endpush

