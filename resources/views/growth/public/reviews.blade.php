<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $profile->title }} — {{ __('Reviews') }} — Taalimu</title>
    @vite(['resources/css/tailwind.css'])
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        <div class="mb-8">
            <a href="{{ route('growth.discover.teachers') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">&larr; {{ __('Back to Discovery') }}</a>
            <h1 class="text-3xl font-bold text-slate-900 mt-2">{{ $profile->title }}</h1>
            @if($profile->headline)
                <p class="text-slate-500 mt-1">{{ $profile->headline }}</p>
            @endif
        </div>

        {{-- Rating Summary --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex items-center gap-6">
                <div class="text-center">
                    <p class="text-4xl font-bold text-slate-900">{{ $averageRating ? number_format($averageRating, 1) : '—' }}</p>
                    <div class="flex justify-center my-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $averageRating && $i <= round($averageRating) ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-sm text-slate-500">{{ $reviews->total() }} {{ __('reviews') }}</p>
                </div>
                <div class="flex-1">
                    @for($i = 5; $i >= 1; $i--)
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs text-slate-500 w-3">{{ $i }}</span>
                            <div class="flex-1 bg-slate-200 rounded-full h-2">
                                <div class="bg-amber-400 h-2 rounded-full" style="width: {{ $reviews->total() > 0 ? ($distribution[$i] / $reviews->total() * 100) : 0 }}%"></div>
                            </div>
                            <span class="text-xs text-slate-400 w-6 text-right">{{ $distribution[$i] }}</span>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- Review Form --}}
        @auth
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <h3 class="font-bold text-slate-900 mb-4">{{ __('Write a Review') }}</h3>
                @if(session('success'))
                    <div class="bg-green-50 text-green-700 p-3 rounded-xl mb-4 text-sm">{{ session('success') }}</div>
                @endif
                <form method="POST" action="{{ route('growth.reviews.store', $profile->slug) }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">{{ __('Rating') }}</label>
                        <div class="flex gap-1" x-data="{ rating: {{ old('rating', 5) }} }">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" @click="rating = {{ $i }}" class="focus:outline-none">
                                    <svg class="w-8 h-8 transition-colors" :class="rating >= {{ $i }} ? 'text-amber-400' : 'text-slate-200'" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </button>
                            @endfor
                            <input type="hidden" name="rating" :value="rating" x-model="rating">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Comment') }} ({{ __('optional') }})</label>
                        <textarea name="comment" rows="3" maxlength="1000"
                            class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="{{ __('Share your experience...') }}">{{ old('comment') }}</textarea>
                    </div>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                        {{ __('Submit Review') }}
                    </button>
                </form>
            </div>
        @endauth

        {{-- Reviews List --}}
        <div class="space-y-4">
            @forelse($reviews as $review)
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="font-bold text-indigo-600">{{ substr($review->reviewer->name ?? '?', 0, 1) }}</span>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-slate-900">{{ $review->reviewer->name ?? 'Anonymous' }}</p>
                            <p class="text-xs text-slate-400">{{ $review->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                    </div>
                    @if($review->verified)
                        <span class="inline-block px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded-full mb-2">{{ __('Verified Student') }}</span>
                    @endif
                    @if($review->comment)
                        <p class="text-sm text-slate-600">{{ $review->comment }}</p>
                    @endif
                    @if($review->response)
                        <div class="mt-3 p-3 bg-slate-50 rounded-xl">
                            <p class="text-xs font-medium text-slate-500 mb-1">{{ __('Response from teacher:') }}</p>
                            <p class="text-sm text-slate-700">{{ $review->response }}</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                    <p class="text-slate-500">{{ __('No reviews yet. Be the first to review!') }}</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $reviews->links() }}
        </div>
    </div>

    <div class="bg-white border-t border-slate-100 py-6 text-center mt-12">
        <p class="text-sm text-slate-400">Powered by <span class="font-semibold text-slate-600">Taalimu</span></p>
    </div>
</body>
</html>
