@extends('layouts.landing-new')

@section('content')
<div class="container mx-auto px-4" style="padding-top: 140px; padding-bottom: 60px;">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl md:text-4xl font-bold text-foreground mb-6">Terms of Service</h1>
        <div class="prose prose-lg max-w-none">
            <p class="text-muted-foreground">{{ __('policies.terms.last_updated') }}: {{ date('F d, Y') }}</p>
            
            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4">{{ __('policies.terms.acceptance.title') }}</h2>
                <p class="text-muted-foreground mb-4">{{ __('policies.terms.acceptance.content') }}</p>
            </section>

            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4">{{ __('policies.terms.services.title') }}</h2>
                <p class="text-muted-foreground mb-4">{{ __('policies.terms.services.content') }}</p>
            </section>

            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4">{{ __('policies.terms.user_obligations.title') }}</h2>
                <p class="text-muted-foreground mb-4">{{ __('policies.terms.user_obligations.content') }}</p>
            </section>

            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4">{{ __('policies.terms.liability.title') }}</h2>
                <p class="text-muted-foreground mb-4">{{ __('policies.terms.liability.content') }}</p>
            </section>

            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4">{{ __('policies.terms.contact.title') }}</h2>
                <p class="text-muted-foreground">{{ __('policies.terms.contact.content') }}</p>
            </section>
        </div>
    </div>
</div>
@endsection
