@extends('layouts.landing-new')

@section('content')
<div class="container mx-auto px-4 pb-20 lg:pb-32" style="padding-top: 140px;">
    <div class="max-w-xl mx-auto">
        <div class="bg-card border border-border rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8 lg:p-10">
                <div class="text-center mb-10">
                    <h3 class="text-2xl font-bold text-primary mb-2">بوابة الدخول الموحدة</h3>
                    <p class="text-muted-foreground">اختر طريقة الدخول المناسبة لك</p>
                </div>

                <div class="space-y-6">
                    <!-- Tenant Login -->
                    <div class="p-6 bg-muted/30 rounded-xl border border-border">
                        <div class="flex items-center mb-4">
                            <div class="bg-primary/10 text-primary rounded-full flex items-center justify-center w-12 h-12 me-4">
                                <span class="text-2xl">🏢</span>
                            </div>
                            <div>
                                <h5 class="font-bold text-foreground mb-1">دخول المراكز والطلاب</h5>
                                <p class="text-sm text-muted-foreground">اختر مركزك التعليمي للدخول</p>
                            </div>
                        </div>
                        
                        <!-- Tenant List -->
                        <div class="space-y-3">
                            @forelse($tenants as $tenant)
                                 <a href="{{ tenant_url('login', $tenant) }}" class="flex items-center justify-between w-full px-4 py-3 bg-background border border-border rounded-lg hover:border-primary hover:text-primary transition-colors group">
                                    <span class="font-medium">{{ $tenant->name }}</span>
                                    <span class="text-muted-foreground group-hover:text-primary transition-colors">→</span>
                                </a>
                            @empty
                                <p class="text-muted-foreground text-center text-sm py-2">لا توجد مراكز مسجلة بعد</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Admin Login -->
                    <a href="{{ route('admin.login') }}" class="block group">
                        <div class="p-6 bg-muted/30 rounded-xl border border-border group-hover:border-primary transition-colors">
                            <div class="flex items-center">
                                <div class="bg-yellow-500/10 text-yellow-600 rounded-full flex items-center justify-center w-12 h-12 me-4">
                                    <span class="text-2xl">🛡️</span>
                                </div>
                                <div>
                                    <h5 class="font-bold text-foreground mb-1">دخول المشرف العام</h5>
                                    <p class="text-sm text-muted-foreground">لوحة تحكم إدارة النظام</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
