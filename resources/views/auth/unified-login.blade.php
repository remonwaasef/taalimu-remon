@extends('layouts.landing-new')

@section('content')
<div class="min-h-screen flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8 bg-background">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-card border border-border rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold gradient-text mb-2">{{ __('auth.login.title') }}</h2>
                <p class="text-muted-foreground">{{ __('auth.login.subtitle') }}</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 text-red-600 rounded-lg p-4 mb-6 flex items-center">
                    <i class="fas fa-exclamation-circle me-3"></i>
                    <span class="text-sm font-medium">{{ $errors->first() }}</span>
                </div>
            @endif

            @if (session('info'))
                <div class="bg-blue-500/10 border border-blue-500/20 text-blue-600 rounded-lg p-4 mb-6 flex items-center">
                    <i class="fas fa-info-circle me-3"></i>
                    <span class="text-sm font-medium">{{ session('info') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('unified.login.submit') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-bold text-muted-foreground mb-2">{{ __('auth.login.email_or_phone') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 ps-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-muted-foreground"></i>
                        </div>
                        <input type="text" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               class="block w-full ps-10 py-3 bg-muted/30 border border-border rounded-xl text-foreground focus:ring-2 focus:ring-primary focus:border-primary transition-colors @error('email') border-red-500 @enderror" 
                               placeholder="{{ __('auth.login.email_or_phone') }}"
                               required 
                               autofocus>
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-muted-foreground mb-2">{{ __('auth.login.password') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 ps-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-muted-foreground"></i>
                        </div>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="block w-full ps-10 py-3 bg-muted/30 border border-border rounded-xl text-foreground focus:ring-2 focus:ring-primary focus:border-primary transition-colors @error('password') border-red-500 @enderror" 
                               placeholder="••••••••"
                               required>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-primary-foreground bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    {{ __('auth.login.login_button') }} 
                    <i class="fas fa-arrow-left ms-2 rtl:rotate-180 transform transition-transform"></i>
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-muted-foreground mb-3">{{ __('auth.login.no_account') }}</p>
                <a href="{{ route('register') }}" class="inline-block px-6 py-2 border-2 border-primary text-primary font-bold rounded-full hover:bg-primary hover:text-white transition-colors text-sm">
                    {{ __('auth.login.register_now') }}
                </a>
            </div>

            <div class="mt-8 pt-6 border-t border-border text-center">
                <a href="{{ route('admin.login') }}" class="inline-flex items-center text-sm text-muted-foreground hover:text-primary transition-colors">
                    <i class="fas fa-user-shield me-2"></i> {{ __('auth.login.admin_login') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
