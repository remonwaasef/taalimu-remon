{{-- Section: WhatsApp Notifications Dedicated Interactive Sandbox --}}
<section id="whatsapp" class="py-20 lg:py-28 bg-slate-50 relative overflow-hidden border-y border-slate-200/80" 
         x-data="{
             activeTab: 'attendance',
             cases: {
                 attendance: {
                     title: 'إشعار حضور الحصة بـ QR',
                     badge: 'حضور فوري ✓',
                     time: '10:15 ص',
                     text: 'السلام عليكم أ/ محمد، نحيطكم علماً بوصول وحضور الطالب (أحمد عمر) الآن لحصة الرياضيات في القاعة الرئيسية. 📚',
                     extra: 'الرصيد المتبقي: 3 حصص'
                 },
                 absence: {
                     title: 'تنبيه غياب أو تأخير',
                     badge: 'تنبيه غياب ⚠️',
                     time: '11:00 ص',
                     text: 'نود إعلامكم بأن الطالب (أحمد عمر) لم يسجل حضوره اليوم في حصة اللغة الإنجليزية المقررة الساعة 10:30 ص. نرجو الاطمئنان عليه.',
                     extra: 'يمكنكم التواصل مع الإدارة للاستفسار'
                 },
                 payment: {
                     title: 'إيصال سداد رسوم / قسط',
                     badge: 'سداد مالي 💳',
                     time: '01:45 م',
                     text: 'تم بنجاح استلام مبلغ 500 ج.م لاشتراك شهر مارس لحساب الطالب (أحمد عمر). تم تصفية الرصيد المستحق.',
                     extra: 'رقم الإيصال: #REC-9821'
                 },
                 exam: {
                     title: 'تقرير درجات الاختبار الشهري',
                     badge: 'نتيجة اختبار 🏆',
                     time: '05:20 م',
                     text: 'نهنئكم بحصول الطالب (أحمد عمر) على درجة 48/50 في الاختبار التراكمي لمادة الكيمياء. أداء ممتاز ومستوى متقدم!',
                     extra: 'الترتيب: الثاني على المجموعة'
                 }
             }
         }">
    <div class="container mx-auto px-4 lg:px-12 max-w-7xl">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">
            
            {{-- Right Column on RTL: High Converting Value Proposition --}}
            <div class="lg:col-span-6 text-center lg:text-start order-1 lg:order-2">
                
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-100/70 border border-emerald-300/60 text-[#2E8B83] text-xs font-extrabold mb-4 shadow-xs">
                    <i class="fab fa-whatsapp text-sm text-emerald-600"></i>
                    <span>إشعارات WhatsApp التلقائية للأهالي</span>
                </div>

                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 leading-tight mb-4">
                    ولي الأمر يعرف…<br>
                    <span class="text-[#2E8B83]">قبل أن يسألك.</span>
                </h2>

                <p class="text-base sm:text-lg text-slate-600 font-medium leading-relaxed mb-6 max-w-xl mx-auto lg:mx-0">
                    اربط مركزك بخدمة إشعارات <strong>Meta Cloud API الرسمية</strong>، لتوثيق كل حدث مهم في هاتف ولي الأمر فور حدوثه بدون اتصالات متكررة أو رسائل يدوية.
                </p>

                {{-- Interactive Tab Selectors --}}
                <div class="grid grid-cols-2 gap-2.5 max-w-lg mx-auto lg:mx-0 mb-6 text-start">
                    <button type="button" 
                            @click="activeTab = 'attendance'"
                            :class="activeTab === 'attendance' ? 'bg-[#2E8B83] text-white shadow-md border-[#2E8B83]' : 'bg-white text-slate-700 hover:bg-slate-100 border-slate-200'"
                            class="p-3 rounded-xl border text-xs font-bold flex items-center gap-2 transition-all">
                        <i class="fas fa-qrcode text-sm"></i>
                        <span>1. حضور بالـ QR</span>
                    </button>

                    <button type="button" 
                            @click="activeTab = 'absence'"
                            :class="activeTab === 'absence' ? 'bg-[#2E8B83] text-white shadow-md border-[#2E8B83]' : 'bg-white text-slate-700 hover:bg-slate-100 border-slate-200'"
                            class="p-3 rounded-xl border text-xs font-bold flex items-center gap-2 transition-all">
                        <i class="fas fa-user-times text-sm"></i>
                        <span>2. تنبيه غياب</span>
                    </button>

                    <button type="button" 
                            @click="activeTab = 'payment'"
                            :class="activeTab === 'payment' ? 'bg-[#2E8B83] text-white shadow-md border-[#2E8B83]' : 'bg-white text-slate-700 hover:bg-slate-100 border-slate-200'"
                            class="p-3 rounded-xl border text-xs font-bold flex items-center gap-2 transition-all">
                        <i class="fas fa-receipt text-sm"></i>
                        <span>3. إيصال سداد</span>
                    </button>

                    <button type="button" 
                            @click="activeTab = 'exam'"
                            :class="activeTab === 'exam' ? 'bg-[#2E8B83] text-white shadow-md border-[#2E8B83]' : 'bg-white text-slate-700 hover:bg-slate-100 border-slate-200'"
                            class="p-3 rounded-xl border text-xs font-bold flex items-center gap-2 transition-all">
                        <i class="fas fa-award text-sm"></i>
                        <span>4. تقرير درجات</span>
                    </button>
                </div>

                {{-- Key Benefits List --}}
                <div class="space-y-2 text-slate-700 text-xs font-bold max-w-lg mx-auto lg:mx-0 text-start">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-emerald-600"></i>
                        <span>حساب رسمي موثق يحمي رقمك من الحظر نهائياً.</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-emerald-600"></i>
                        <span>قوالب رسائل مخصصة باسم الطالب، المادة، والمبلغ المالي.</span>
                    </div>
                </div>

            </div>

            {{-- Left Column on RTL: Realistic Phone Mockup --}}
            <div class="lg:col-span-6 flex justify-center order-2 lg:order-1">
                <div class="relative w-full max-w-sm">
                    
                    {{-- WhatsApp Verified Float Badge --}}
                    <div class="absolute -top-3 -start-3 z-30 bg-emerald-500 text-white w-12 h-12 rounded-2xl shadow-xl flex items-center justify-center text-2xl border-2 border-white">
                        <i class="fab fa-whatsapp"></i>
                    </div>

                    {{-- Smartphone Frame --}}
                    <div class="relative bg-slate-900 rounded-[2.5rem] p-3 shadow-2xl border-[3px] border-slate-800">
                        
                        {{-- Phone Screen Display --}}
                        <div class="relative bg-[#efeae2] rounded-[2rem] overflow-hidden p-4 pt-6 min-h-[460px] flex flex-col justify-start border border-slate-300/80">
                            
                            {{-- WhatsApp Header --}}
                            <div class="bg-[#075e54] text-white rounded-xl p-3 shadow-sm mb-4 flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-white/20 text-white flex items-center justify-center text-sm font-bold shrink-0">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div class="leading-tight text-start flex-1">
                                    <div class="flex items-center gap-1.5">
                                        <h5 class="text-xs font-black text-white">منظومة Taalimu التعليمية</h5>
                                        <i class="fas fa-check-circle text-emerald-300 text-[10px]"></i>
                                    </div>
                                    <span class="text-[9px] text-emerald-100">حساب تجاري رسمي موثق</span>
                                </div>
                            </div>

                            {{-- Chat Bubble Date Pill --}}
                            <div class="text-center mb-3">
                                <span class="bg-white/80 backdrop-blur-xs text-slate-600 text-[9px] font-bold px-2.5 py-1 rounded-full shadow-2xs">
                                    اليوم
                                </span>
                            </div>

                            {{-- Dynamic Animated WhatsApp Message Bubble --}}
                            <div class="bg-white rounded-2xl rounded-tr-none p-3.5 shadow-sm border border-slate-200/60 mb-4 text-start relative transition-all duration-300">
                                
                                <div class="flex items-center justify-between mb-2 pb-1.5 border-b border-slate-100">
                                    <span class="text-[10px] font-black text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-md"
                                          x-text="cases[activeTab].badge">
                                        حضور فوري ✓
                                    </span>
                                    <span class="text-[9px] text-slate-400 font-mono" x-text="cases[activeTab].time">10:15 ص</span>
                                </div>

                                <p class="text-xs font-bold text-slate-800 leading-relaxed mb-2" x-text="cases[activeTab].text">
                                    السلام عليكم أ/ محمد، نحيطكم علماً بوصول وحضور الطالب (أحمد عمر) الآن لحصة الرياضيات في القاعة الرئيسية. 📚
                                </p>

                                <div class="bg-slate-50 p-2 rounded-lg border border-slate-100 flex items-center justify-between text-[10px] text-slate-600 font-semibold">
                                    <span x-text="cases[activeTab].extra">الرصيد المتبقي: 3 حصص</span>
                                    <span class="text-emerald-600 flex items-center gap-0.5">
                                        <i class="fas fa-check-double text-[10px]"></i>
                                        <span>مقروءة</span>
                                    </span>
                                </div>
                            </div>

                            {{-- Notice note at bottom --}}
                            <div class="mt-auto text-center bg-white/60 p-2 rounded-xl text-[10px] text-slate-500 font-medium border border-slate-200/40">
                                <i class="fas fa-bolt text-amber-500 ms-1"></i>
                                تُرسل الرسالة آلياً في أقل من ثانيتين من إجراء العملية
                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
</section>