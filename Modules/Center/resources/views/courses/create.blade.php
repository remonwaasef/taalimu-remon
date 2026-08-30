@extends('center::layouts.app-next')

@section('title', __('center::courses.add_new'))

@section('panel-content')
    <x-ui.page-header
        title="{{ __('center::courses.add_new') }}"
        subtitle="{{ __('center::courses.create_subtitle') ?? 'قم بإدخال تفاصيل الدورة ومواعيد الحصص الدراسية' }}"
        :breadcrumb="[
            __('center::dashboard.title') => route('center.dashboard'),
            __('center::courses.title') => route('center.courses.index'),
            __('center::courses.add_new') => null
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
            <form action="{{ route('center.courses.store') }}" method="POST" enctype="multipart/form-data">
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
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                {{ __('center::courses.instructor') }} <span class="text-rose-500">*</span>
                            </label>
                            <button type="button" data-quick-instructor-trigger class="text-xs text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 font-bold flex items-center gap-1">
                                <i class="fas fa-plus-circle"></i> + إضافة معلم جديد
                            </button>
                        </div>

                        @if($instructors->isEmpty())
                            <div data-empty-instructors-notice class="p-4 mb-3 rounded-xl border border-amber-200 dark:border-amber-800/40 bg-amber-50 dark:bg-amber-950/30 text-amber-800 dark:text-amber-300 text-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-start gap-2.5">
                                    <i class="fas fa-info-circle mt-0.5 text-base"></i>
                                    <div>
                                        <div class="font-bold mb-1">لا يوجد مدرسون بعد</div>
                                        <p class="text-amber-700 dark:text-amber-400 leading-relaxed">يمكنك إضافة مدرس جديد بسرعة من الزر أدناه دون مغادرة الصفحة.</p>
                                    </div>
                                </div>
                                <button type="button" data-quick-instructor-trigger class="btn btn-primary btn-sm shrink-0">
                                    <i class="fas fa-user-plus me-1"></i> إضافة مدرس جديد
                                </button>
                            </div>
                        @endif

                        <select name="instructor_id" 
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm @error('instructor_id') border-rose-500 focus:ring-rose-500/20 @enderror">
                            <option value="">{{ $instructors->isEmpty() ? 'اختر المعلم' : __('center::courses.choose_instructor') }}</option>
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
                            <input type="number" name="sessions_count" id="sessions-count-input" value="{{ old('sessions_count', 0) }}" 
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm @error('sessions_count') border-rose-500 @enderror" 
                                   min="0">
                            @error('sessions_count')
                                <p class="text-rose-500 text-xs mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                                {{ __('center::courses.status') }}
                            </label>
                            <select name="status" 
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-sm">
                                <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>{{ __('center::courses.published') }}</option>
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>{{ __('center::courses.draft') }}</option>
                            </select>
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

                    <!-- Weekly Schedules Section -->
                    <div class="pt-6 border-t border-brand-border dark:border-slate-800">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">
                                    {{ __('center::courses.schedules_weekly') }}
                                </h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    {{ __('center::courses.schedules_desc') }}
                                </p>
                            </div>
                            <button type="button" id="add-schedule-btn" 
                                    class="py-2 px-3.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:hover:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 font-bold text-xs transition-all flex items-center gap-1.5 border border-emerald-200 dark:border-emerald-800/50">
                                <i class="fas fa-plus"></i>
                                <span>{{ __('center::courses.add_schedule') }}</span>
                            </button>
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
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="block text-xs font-medium text-slate-500 dark:text-slate-400">{{ __('center::schedules.classroom') }}</label>
                                        <button type="button" data-quick-classroom-trigger class="text-[11px] text-emerald-600 hover:underline font-bold">+ قاعة جديدة</button>
                                    </div>
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

    @include('center::partials._quick-instructor-modal')
    @include('center::partials._quick-classroom-modal')
@endsection

@push('scripts')
@include('center::courses.partials._create-scripts')
@endpush
