@extends('layouts.auth-minimal')

@section('content')
<div class="min-h-screen bg-slate-50/50 flex justify-center p-4 lg:p-8 mesh-gradient-soft noise-overlay register-page-offset" 
     x-data="{ 
        otp: ['', '', '', '', '', ''],
        timer: 60,
        canResend: false,
        init() {
            this.startTimer();
            this.$nextTick(() => this.$refs.otp0.focus());
        },
        startTimer() {
            this.canResend = false;
            this.timer = 60;
            let interval = setInterval(() => {
                if (this.timer > 0) {
                    this.timer--;
                } else {
                    this.canResend = true;
                    clearInterval(interval);
                }
            }, 1000);
        },
        handleInput(e, index) {
            const val = e.target.value;
            if (val.length > 1) {
                // Handle paste
                const pasteData = val.slice(0, 6).split('');
                pasteData.forEach((char, i) => {
                    if (index + i < 6) this.otp[index + i] = char;
                });
                this.$nextTick(() => {
                    const nextIdx = Math.min(index + pasteData.length, 5);
                    this.$refs['otp' + nextIdx].focus();
                });
            } else {
                if (val && index < 5) {
                    this.$refs['otp' + (index + 1)].focus();
                }
            }
            this.updateHiddenInput();
        },
        handleKeydown(e, index) {
            if (e.key === 'Backspace' && !this.otp[index] && index > 0) {
                this.$refs['otp' + (index - 1)].focus();
            }
        },
        updateHiddenInput() {
            this.$refs.hiddenCode.value = this.otp.join('');
        }
     }">
    <div class="w-full max-w-[450px] space-y-6">
        <!-- Logo & Header -->
        <div class="text-center space-y-2 animate-fade-in-down">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-emerald-100/50 text-emerald-600 rounded-3xl mb-4 shadow-sm border border-emerald-100">
                <i class="bi bi-whatsapp text-4xl"></i>
            </div>
            <h1 class="text-3xl font-black text-slate-900 font-arabic tracking-tight">
                {{ app()->getLocale() == 'ar' ? 'تفعيل الحساب' : 'Verify Account' }}
            </h1>
            <p class="text-slate-500 font-arabic font-bold text-sm max-w-[300px] mx-auto leading-relaxed">
                {{ app()->getLocale() == 'ar' ? 'لقد أرسلنا كود تفعيل مكون من 6 أرقام إلى رقم الواتساب الخاص بك' : 'We sent a 6-digit verification code to your WhatsApp number' }}
            </p>
            <div class="pt-2">
                <span class="px-4 py-1.5 bg-slate-100 text-slate-700 rounded-full text-sm font-black tracking-wide border border-slate-200">
                    {{ auth()->user()->phone }}
                </span>
            </div>
        </div>

        <!-- Verification Form -->
        <div class="bg-white/80 backdrop-blur-xl border border-slate-200 p-8 lg:p-10 rounded-[2.5rem] shadow-xl shadow-slate-200/50 animate-scale-in relative overflow-hidden">
            @if (session('status'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-black rounded-2xl animate-shake font-arabic">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-700 text-xs font-black rounded-2xl animate-shake font-arabic">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('verification.phone.verify') }}" method="POST" class="space-y-8">
                @csrf
                <input type="hidden" name="code" x-ref="hiddenCode">

                <!-- OTP Inputs -->
                <div class="flex justify-between gap-2" dir="ltr">
                    <template x-for="(i, index) in otp" :key="index">
                        <input :x-ref="'otp' + index" 
                               type="text" 
                               inputmode="numeric" 
                               maxlength="6"
                               x-model="otp[index]"
                               @input="handleInput($event, index)"
                               @keydown="handleKeydown($event, index)"
                               class="w-12 h-14 text-center text-2xl font-black bg-slate-50 border-2 border-slate-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 focus:bg-white transition-all shadow-inner">
                    </template>
                </div>

                <button type="submit" 
                        class="w-full h-14 bg-emerald-500 hover:bg-emerald-600 text-white font-black rounded-2xl shadow-lg shadow-emerald-500/20 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 group">
                    <span class="font-arabic">{{ app()->getLocale() == 'ar' ? 'تفعيل الآن' : 'Verify Now' }}</span>
                    <i class="bi bi-chevron-left animate-bounce-x group-hover:translate-x-1 transition-transform rtl:group-hover:-translate-x-1"></i>
                </button>
            </form>

            <!-- Resend Logic -->
            <div class="mt-8 text-center pt-6 border-t border-slate-100">
                <form action="{{ route('verification.phone.resend') }}" method="POST" @submit="startTimer()">
                    @csrf
                    <p class="text-[11px] text-slate-400 font-arabic font-black uppercase tracking-wider mb-2">
                        {{ app()->getLocale() == 'ar' ? 'لم يصلك الكود؟' : 'Didn\'t receive the code?' }}
                    </p>
                    
                    <button type="submit" 
                            x-show="canResend"
                            class="text-sm font-black text-emerald-600 hover:text-emerald-700 hover:underline transition-colors font-arabic">
                        {{ app()->getLocale() == 'ar' ? 'إعادة إرسال الكود' : 'Resend Code' }}
                    </button>

                    <div x-show="!canResend" class="text-sm font-black text-slate-400 font-arabic">
                        {{ app()->getLocale() == 'ar' ? 'يمكنك إعادة الإرسال خلال' : 'Resend available in' }}
                        <span class="text-emerald-500 ml-1 font-sans" x-text="timer + 's'"></span>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs font-black text-slate-400 hover:text-red-500 transition-colors uppercase tracking-widest font-arabic">
                    {{ app()->getLocale() == 'ar' ? 'تسجيل الخروج' : 'Log Out' }}
                </button>
            </form>
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
.noise-overlay { position: relative; }
.noise-overlay::before {
    content: "";
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
    opacity: 0.02;
    pointer-events: none;
    z-index: 1;
}
</style>
@endsection
