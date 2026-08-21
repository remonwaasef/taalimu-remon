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
        <meta property="og:image" content="{{ asset('storage/' . $seoData['og_image']) }}">
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
                        <p class="text-sm text-gray-500">الدورات المتاحة</p>
                    </div>
                </div>
                <a href="{{ url('/') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                    الرئيسية
                </a>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Page Header --}}
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900">الدورات المتاحة</h2>
            <p class="text-gray-600 mt-1">تصفح دوراتنا التعليمية المتنوعة</p>
        </div>

        {{-- Courses Grid --}}
        @if($courses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($courses as $course)
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        {{-- Course Image --}}
                        @if($course->image)
                            <div class="h-48 bg-gray-200">
                                <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="h-48 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                        @endif

                        <div class="p-5">
                            {{-- Course Title --}}
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $course->title }}</h3>

                            {{-- Instructor --}}
                            @if($course->instructor)
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="h-6 w-6 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <span class="text-xs font-medium text-indigo-600">{{ substr($course->instructor->name, 0, 1) }}</span>
                                    </div>
                                    <span class="text-sm text-gray-600">{{ $course->instructor->name }}</span>
                                </div>
                            @endif

                            {{-- Grade --}}
                            @if($course->grade)
                                <div class="mb-3">
                                    <span class="inline-block px-2 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">
                                        {{ $course->grade->name }}
                                    </span>
                                </div>
                            @endif

                            {{-- Description --}}
                            @if($course->description)
                                <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ Str::limit($course->description, 100) }}</p>
                            @endif

                            {{-- Price --}}
                            <div class="flex items-center justify-between">
                                <div>
                                    @if($course->price > 0)
                                        <span class="text-xl font-bold text-indigo-600">{{ number_format($course->price, 2) }}</span>
                                        <span class="text-sm text-gray-500">ج.م</span>
                                    @else
                                        <span class="text-lg font-bold text-green-600">مجاني</span>
                                    @endif
                                </div>
                                <a href="{{ route('center.public.courses.show', ['tenant' => $tenant->domain, 'course' => $course->id]) }}"
                                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                                    التفاصيل
                                    <svg class="w-4 h-4 mr-1 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $courses->links() }}
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد دورات متاحة</h3>
                <p class="text-gray-500">لم يتم العثور على دورات حالياً</p>
            </div>
        @endif
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
        "@@type": "EducationalOrganization",
        "name": "{{ $tenant->name }}",
        "description": "{{ $seoData['description'] }}",
        "url": "{{ url('/') }}"
    }
    </script>
</body>
</html>
