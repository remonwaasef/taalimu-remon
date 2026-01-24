@extends('layouts.landing-new')

@section('content')
<div class="container mx-auto px-4" style="padding-top: 140px; padding-bottom: 60px;">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl md:text-4xl font-bold text-foreground mb-6">Privacy Policy</h1>
        <div class="prose prose-lg max-w-none">
            <p class="text-muted-foreground">{{ __('policies.privacy.last_updated') }}: {{ date('F d, Y') }}</p>
            
            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4">{{ __('policies.privacy.introduction.title') }}</h2>
                <p class="text-muted-foreground mb-4">{{ __('policies.privacy.introduction.content') }}</p>
            </section>

            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4">{{ __('policies.privacy.data_collection.title') }}</h2>
                <p class="text-muted-foreground mb-4">{{ __('policies.privacy.data_collection.content') }}</p>
                <ul class="list-disc list-inside text-muted-foreground space-y-2 mb-4">
                    <li>{{ __('policies.privacy.data_collection.items.name') }}</li>
                    <li>{{ __('policies.privacy.data_collection.items.email') }}</li>
                    <li>{{ __('policies.privacy.data_collection.items.contact') }}</li>
                    <li>{{ __('policies.privacy.data_collection.items.usage') }}</li>
                </ul>
            </section>

            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4">{{ __('policies.privacy.usage.title') }}</h2>
                <p class="text-muted-foreground mb-4">{{ __('policies.privacy.usage.content') }}</p>
            </section>

            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4">{{ __('policies.privacy.rights.title') }}</h2>
                <p class="text-muted-foreground mb-4">{{ __('policies.privacy.rights.content') }}</p>
                <ul class="list-disc list-inside text-muted-foreground space-y-2">
                    <li>{{ __('policies.privacy.rights.items.access') }}</li>
                    <li>{{ __('policies.privacy.rights.items.rectification') }}</li>
                    <li>{{ __('policies.privacy.rights.items.deletion') }}</li>
                    <li>{{ __('policies.privacy.rights.items.portability') }}</li>
                    <li>{{ __('policies.privacy.rights.items.objection') }}</li>
                </ul>
            </section>

            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4">{{ __('policies.privacy.security.title') }}</h2>
                <p class="text-muted-foreground mb-4">{{ __('policies.privacy.security.content') }}</p>
            </section>

            <section class="mt-8">
                <h2 class="text-2xl font-bold text-foreground mb-4">{{ __('policies.privacy.contact.title') }}</h2>
                <p class="text-muted-foreground">{{ __('policies.privacy.contact.content') }}</p>
            </section>
        </div>
    </div>
</div>
@endsection
