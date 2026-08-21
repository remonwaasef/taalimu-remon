<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- SEO Meta Tags --}}
    <title>{{ $seoData['title'] }}</title>
    <meta name="description" content="{{ $seoData['description'] }}">
    <meta name="keywords" content="{{ $seoData['keywords'] }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $seoData['title'] }}">
    <meta property="og:description" content="{{ $seoData['description'] }}">
    <meta property="og:type" content="website">
    @if($seoData['og_image'])
        <meta property="og:image" content="{{ $seoData['og_image'] }}">
    @endif

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            primary: '#4F46E5',
                            50: '#EEF2FF',
                            100: '#E0E7FF',
                            900: '#312E81',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    {{-- Header --}}
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    @if($tenant->settings['appearance']['logo'] ?? null)
                        <img src="{{ asset('storage/' . $tenant->settings['appearance']['logo']) }}" alt="{{ $tenant->name }}" class="h-10 w-10 rounded-lg object-cover">
                    @else
                        <div class="h-10 w-10 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                            {{ substr($tenant->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ $tenant->name }}</h1>
                        <p class="text-sm text-gray-500">{{ $course->title }}</p>
                    </div>
                </div>
                <a href="{{ route('center.public.courses.index', ['tenant' => $tenant->domain]) }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                    العودة للدورات
                </a>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Course Details --}}
            <div class="lg:col-span-2">
                {{-- Course Image --}}
                @if($course->image)
                    <div class="rounded-xl overflow-hidden mb-6">
                        <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}" class="w-full h-64 object-cover">
                    </div>
                @else
                    <div class="rounded-xl overflow-hidden mb-6 h-64 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                        <svg class="w-24 h-24 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                @endif

                {{-- Title --}}
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $course->title }}</h1>

                {{-- Meta Info --}}
                <div class="flex flex-wrap items-center gap-4 mb-6 text-sm text-gray-500">
                    @if($course->instructor)
                        <div class="flex items-center gap-2">
                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-sm font-medium text-indigo-600">{{ substr($course->instructor->name, 0, 1) }}</span>
                            </div>
                            <span>{{ $course->instructor->name }}</span>
                        </div>
                    @endif

                    @if($course->grade)
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full">
                            {{ $course->grade->name }}
                        </span>
                    @endif

                    @if($course->max_students)
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full">
                            {{ $course->enrollments_count ?? 0 }} / {{ $course->max_students }} طالب
                        </span>
                    @endif
                </div>

                {{-- Description --}}
                @if($course->description)
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-3">عن الدورة</h2>
                        <div class="prose prose-gray max-w-none">
                            {!! nl2br(e($course->description)) !!}
                        </div>
                    </div>
                @endif

                {{-- Schedule --}}
                @if($course->schedules && $course->schedules->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-3">جدول الحصص</h2>
                        <div class="space-y-3">
                            @foreach($course->schedules as $schedule)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <span class="font-medium text-gray-900">{{ $schedule->day_name ?? $schedule->day }}</span>
                                        <span class="text-gray-500 mr-2">{{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
                                    </div>
                                    @if($schedule->classroom)
                                        <span class="text-sm text-gray-500">قاعة {{ $schedule->classroom }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                {{-- Price Card --}}
                <div class="bg-white rounded-xl shadow-sm p-6 sticky top-4">
                    <div class="text-center mb-6">
                        @if($course->price > 0)
                            <div class="text-3xl font-bold text-indigo-600 mb-1">{{ number_format($course->price, 2) }}</div>
                            <div class="text-sm text-gray-500">جنيه مصري / كورس</div>
                        @else
                            <div class="text-3xl font-bold text-green-600 mb-1">مجاني</div>
                            <div class="text-sm text-gray-500">بلا حدود</div>
                        @endif
                    </div>

                    {{-- CTA Button --}}
                    @if($course->registration_token)
                        <a href="{{ route('group.register', ['tenant' => $tenant->domain, 'token' => $course->registration_token]) }}"
                           class="block w-full py-3 px-4 bg-indigo-600 text-white text-center font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                            سجّل الآن
                        </a>
                    @else
                        <a href="tel:{{ $tenant->phone ?? '' }}"
                           class="block w-full py-3 px-4 bg-indigo-600 text-white text-center font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                            تواصل معنا
                        </a>
                    @endif

                    {{-- Features --}}
                    <div class="mt-6 space-y-3">
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>شهادة إتمام معتمدة</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>حضور وغياب ذكي</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>اختبارات تقييمية</span>
                        </div>
                    </div>

                    {{-- Contact Info --}}
                    @if($tenant->phone)
                        <div class="mt-6 pt-6 border-t">
                            <p class="text-sm text-gray-500 text-center mb-3">للاستفسار</p>
                            <a href="tel:{{ $tenant->phone }}" class="flex items-center justify-center gap-2 text-indigo-600 font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $tenant->phone }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <p class="text-center text-sm text-gray-500">
                {{ $tenant->name }} © {{ date('Y') }} - جميع الحقوق محفوظة
            </p>
        </div>
    </footer>

    {{-- Structured Data for SEO --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Course",
        "name": "{{ $course->title }}",
        "description": "{{ $course->description }}",
        "provider": {
            "@@type": "EducationalOrganization",
            "name": "{{ $tenant->name }}"
        },
        "offers": {
            "@@type": "Offer",
            "price": "{{ $course->price }}",
            "priceCurrency": "EGP"
        }
        @if($course->schedules && $course->schedules->count() > 0),
        "schedule": {
            "@@type": "Schedule",
            "repeatFrequency": "Weekly"
        }
        @endif
    }
    </script>
</body>
</html>
