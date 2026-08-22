<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taalimu Design System 1.0 — Visual Architecture & Live Showcase</title>

    <!-- Google Fonts: Tajawal, Cairo, Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Tajawal:wght@300;400;500;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Vite Assets -->
    @vite(['resources/css/tailwind.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Tajawal', 'Inter', system-ui, sans-serif; }
        [dir="ltr"] body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>
<body
    x-data="{
        darkMode: false,
        isRtl: true,
        toggleTheme() {
            this.darkMode = !this.darkMode;
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        },
        toggleDirection() {
            this.isRtl = !this.isRtl;
            document.documentElement.setAttribute('dir', this.isRtl ? 'rtl' : 'ltr');
            document.documentElement.setAttribute('lang', this.isRtl ? 'ar' : 'en');
        }
    }"
    class="min-h-screen bg-[#F8FAFC] dark:bg-[#0B0F19] text-slate-900 dark:text-slate-100 flex transition-colors duration-200"
>

    <!-- =========================================================================
         1. LEFT/RIGHT DARK SAAS SIDEBAR
         ========================================================================= -->
    <aside class="w-64 bg-[#0B0F19] text-white flex flex-col justify-between shrink-0 min-h-screen sticky top-0 h-screen z-30 select-none border-e border-slate-800/80">
        <div class="flex-1 flex flex-col min-h-0 overflow-y-auto scrollbar-none p-4">
            <!-- Brand Logo -->
            <div class="flex items-center gap-3 px-2 py-3 mb-4">
                <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white text-lg shadow-lg shadow-indigo-600/30 shrink-0">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                    <h2 class="text-base font-black tracking-tight leading-none text-white">Taalimu</h2>
                    <span class="text-[10px] text-slate-400 font-medium">Manage. Teach. Inspire.</span>
                </div>
            </div>

            <!-- Overview Active Pill -->
            <div class="mb-4">
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs shadow-md shadow-indigo-600/30 transition-all">
                    <i class="fas fa-th-large w-4 text-center"></i>
                    <span>Overview</span>
                </a>
            </div>

            <!-- Main Nav Section -->
            <div class="mb-5">
                <div class="px-3 pb-2 text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">MAIN</div>
                <div class="space-y-1">
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <i class="fas fa-chart-pie w-4 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <i class="fas fa-user-graduate w-4 text-center"></i>
                        <span>Students</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <i class="fas fa-chalkboard-teacher w-4 text-center"></i>
                        <span>Teachers</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <i class="fas fa-door-open w-4 text-center"></i>
                        <span>Classes</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <i class="fas fa-clipboard-check w-4 text-center"></i>
                        <span>Attendance</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <i class="fas fa-credit-card w-4 text-center"></i>
                        <span>Subscriptions</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <i class="fas fa-wallet w-4 text-center"></i>
                        <span>Payments</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <i class="fas fa-chart-line w-4 text-center"></i>
                        <span>Reports</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <i class="fas fa-envelope w-4 text-center"></i>
                        <span>Messages</span>
                    </a>
                </div>
            </div>

            <!-- Advanced Nav Section -->
            <div class="mb-4">
                <div class="px-3 pb-2 text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">ADVANCED</div>
                <div class="space-y-1">
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <i class="fas fa-book-reader w-4 text-center"></i>
                        <span>Learning</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <i class="fas fa-file-alt w-4 text-center"></i>
                        <span>Exams</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <i class="fas fa-medal w-4 text-center"></i>
                        <span>Achievements</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <i class="fas fa-shield-alt w-4 text-center"></i>
                        <span>Reputation</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <i class="fas fa-certificate w-4 text-center"></i>
                        <span>Certificates</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <i class="fas fa-cog w-4 text-center"></i>
                        <span>Settings</span>
                    </a>
                </div>
            </div>

            <!-- Promo Gradient Card -->
            <div class="mt-auto mb-3 p-4 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-700 text-white relative overflow-hidden shadow-lg">
                <div class="relative z-10">
                    <h5 class="text-xs font-bold leading-tight">Empowering education centers to achieve more.</h5>
                    <a href="/" class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 bg-white text-indigo-700 rounded-xl text-[11px] font-bold shadow-xs hover:bg-slate-100 transition-colors">
                        <span>View Taalimu Platform</span>
                        <i class="fas fa-arrow-right text-[9px] rtl:rotate-180"></i>
                    </a>
                </div>
                <div class="absolute -bottom-2 -end-2 opacity-20 text-5xl">
                    <i class="fas fa-graduation-cap"></i>
                </div>
            </div>
        </div>

        <!-- User Profile Footer -->
        <div class="p-3 border-t border-slate-800 bg-[#070A10] flex items-center justify-between">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-8 h-8 rounded-full bg-indigo-500/20 text-indigo-400 font-bold flex items-center justify-center text-xs border border-indigo-500/30 shrink-0">
                    RW
                </div>
                <div class="min-w-0">
                    <h6 class="text-xs font-bold text-white truncate leading-none">Remon Wasef</h6>
                    <span class="text-[10px] text-slate-400">Administrator</span>
                </div>
            </div>
            <button class="text-slate-400 hover:text-white p-1">
                <i class="fas fa-ellipsis-v text-xs"></i>
            </button>
        </div>
    </aside>

    <!-- =========================================================================
         2. MAIN SHOWCASE CONTENT AREA
         ========================================================================= -->
    <main class="flex-1 min-w-0 p-6 lg:p-8 space-y-8 overflow-y-auto">

        <!-- Top Header with Theme and RTL Toggles -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                    Taalimu Design System 1.0
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 font-medium">
                    A unified design language for a better education experience.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <!-- Theme Toggle Button -->
                <button
                    @click="toggleTheme()"
                    class="h-9 px-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/80 transition-all flex items-center gap-2 shadow-xs"
                >
                    <i class="fas" :class="darkMode ? 'fa-moon text-indigo-400' : 'fa-sun text-amber-500'"></i>
                    <span x-text="darkMode ? 'Dark' : 'Light'"></span>
                </button>

                <!-- RTL/LTR Toggle Button -->
                <button
                    @click="toggleDirection()"
                    class="h-9 px-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/80 transition-all flex items-center gap-2 shadow-xs"
                >
                    <i class="fas fa-exchange-alt text-xs text-indigo-500"></i>
                    <span x-text="isRtl ? 'RTL' : 'LTR'"></span>
                </button>
            </div>
        </div>

        <!-- =====================================================================
             FOUNDATIONS ROW: Color System, Typography, Spacing, Radius, Design Tokens
             ===================================================================== -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            <!-- 1. Color System Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-xs font-tajawal">
                <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 mb-1">Color System</h3>
                <p class="text-[11px] text-slate-400 dark:text-slate-500 mb-4">Semantic color palette with 10 steps</p>

                <div class="space-y-2.5 text-[10px] font-semibold text-slate-500">
                    <!-- Primary (Indigo) -->
                    <div>
                        <div class="flex justify-between mb-1"><span>Primary</span><span class="font-mono text-indigo-600 font-bold">500</span></div>
                        <div class="grid grid-cols-10 gap-0.5 h-3.5 rounded-md overflow-hidden">
                            <div class="bg-indigo-50"></div><div class="bg-indigo-100"></div><div class="bg-indigo-200"></div><div class="bg-indigo-300"></div><div class="bg-indigo-400"></div><div class="bg-indigo-500 ring-1 ring-black/20"></div><div class="bg-indigo-600"></div><div class="bg-indigo-700"></div><div class="bg-indigo-800"></div><div class="bg-indigo-950"></div>
                        </div>
                    </div>
                    <!-- Secondary (Slate) -->
                    <div>
                        <div class="flex justify-between mb-1"><span>Secondary</span></div>
                        <div class="grid grid-cols-10 gap-0.5 h-3.5 rounded-md overflow-hidden">
                            <div class="bg-slate-50"></div><div class="bg-slate-100"></div><div class="bg-slate-200"></div><div class="bg-slate-300"></div><div class="bg-slate-400"></div><div class="bg-slate-500"></div><div class="bg-slate-600"></div><div class="bg-slate-700"></div><div class="bg-slate-800"></div><div class="bg-slate-950"></div>
                        </div>
                    </div>
                    <!-- Success (Emerald) -->
                    <div>
                        <div class="flex justify-between mb-1"><span>Success</span></div>
                        <div class="grid grid-cols-10 gap-0.5 h-3.5 rounded-md overflow-hidden">
                            <div class="bg-emerald-50"></div><div class="bg-emerald-100"></div><div class="bg-emerald-200"></div><div class="bg-emerald-300"></div><div class="bg-emerald-400"></div><div class="bg-emerald-500"></div><div class="bg-emerald-600"></div><div class="bg-emerald-700"></div><div class="bg-emerald-800"></div><div class="bg-emerald-950"></div>
                        </div>
                    </div>
                    <!-- Warning (Amber) -->
                    <div>
                        <div class="flex justify-between mb-1"><span>Warning</span></div>
                        <div class="grid grid-cols-10 gap-0.5 h-3.5 rounded-md overflow-hidden">
                            <div class="bg-amber-50"></div><div class="bg-amber-100"></div><div class="bg-amber-200"></div><div class="bg-amber-300"></div><div class="bg-amber-400"></div><div class="bg-amber-500"></div><div class="bg-amber-600"></div><div class="bg-amber-700"></div><div class="bg-amber-800"></div><div class="bg-amber-950"></div>
                        </div>
                    </div>
                    <!-- Error (Red) -->
                    <div>
                        <div class="flex justify-between mb-1"><span>Error</span></div>
                        <div class="grid grid-cols-10 gap-0.5 h-3.5 rounded-md overflow-hidden">
                            <div class="bg-red-50"></div><div class="bg-red-100"></div><div class="bg-red-200"></div><div class="bg-red-300"></div><div class="bg-red-400"></div><div class="bg-red-500"></div><div class="bg-red-600"></div><div class="bg-red-700"></div><div class="bg-red-800"></div><div class="bg-red-950"></div>
                        </div>
                    </div>
                    <!-- Info (Sky) -->
                    <div>
                        <div class="flex justify-between mb-1"><span>Info</span></div>
                        <div class="grid grid-cols-10 gap-0.5 h-3.5 rounded-md overflow-hidden">
                            <div class="bg-sky-50"></div><div class="bg-sky-100"></div><div class="bg-sky-200"></div><div class="bg-sky-300"></div><div class="bg-sky-400"></div><div class="bg-sky-500"></div><div class="bg-sky-600"></div><div class="bg-sky-700"></div><div class="bg-sky-800"></div><div class="bg-sky-950"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Typography Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-xs font-tajawal">
                <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 mb-1">Typography</h3>
                <p class="text-[11px] text-slate-400 dark:text-slate-500 mb-3">Tajawal (Arabic) + Inter (English)</p>

                <div class="flex items-baseline gap-4 mb-3">
                    <span class="text-4xl font-black text-slate-900 dark:text-white font-tajawal">Aa</span>
                    <div class="space-y-0.5 text-[11px] text-slate-500">
                        <div class="flex justify-between gap-3"><span>Display 1</span><span class="font-mono">48/56</span></div>
                        <div class="flex justify-between gap-3"><span>Heading 1</span><span class="font-mono">28/36</span></div>
                        <div class="flex justify-between gap-3"><span>Body Large</span><span class="font-mono">16/24</span></div>
                        <div class="flex justify-between gap-3"><span>Caption</span><span class="font-mono">11/16</span></div>
                    </div>
                </div>

                <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-700/60 text-xs">
                    <p class="font-bold text-slate-800 dark:text-slate-200">أبجد هوز حطي كلمن</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">تصميم يركز على الوضوح وسهولة القراءة</p>
                </div>
            </div>

            <!-- 3. Spacing & Radius Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-xs font-tajawal">
                <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 mb-1">Spacing & Radius</h3>
                <p class="text-[11px] text-slate-400 dark:text-slate-500 mb-3">8px base unit + Consistent depth</p>

                <div class="grid grid-cols-2 gap-3 text-[11px]">
                    <div class="space-y-1 font-mono text-slate-600 dark:text-slate-300">
                        <div class="flex items-center gap-2"><span class="w-2 h-2 bg-indigo-500 rounded-sm"></span> 1 (8px)</div>
                        <div class="flex items-center gap-2"><span class="w-3 h-2 bg-indigo-500 rounded-sm"></span> 2 (16px)</div>
                        <div class="flex items-center gap-2"><span class="w-4 h-2 bg-indigo-500 rounded-sm"></span> 3 (24px)</div>
                        <div class="flex items-center gap-2"><span class="w-5 h-2 bg-indigo-500 rounded-sm"></span> 4 (32px)</div>
                    </div>
                    <div class="space-y-1 font-mono text-slate-600 dark:text-slate-300">
                        <div class="flex items-center justify-between"><span>sm</span><span class="text-slate-400">4px</span></div>
                        <div class="flex items-center justify-between"><span>md</span><span class="text-slate-400">8px</span></div>
                        <div class="flex items-center justify-between"><span>lg</span><span class="text-indigo-600 font-bold">12px</span></div>
                        <div class="flex items-center justify-between"><span>xl</span><span class="text-slate-400">16px</span></div>
                    </div>
                </div>
            </div>

            <!-- 4. Design Tokens Reference Box -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-xs font-mono text-[11px]">
                <h3 class="text-sm font-bold font-tajawal text-slate-900 dark:text-slate-100 mb-1">Design Tokens</h3>
                <p class="text-[11px] font-tajawal text-slate-400 dark:text-slate-500 mb-3">Core design tokens</p>

                <div class="space-y-1 text-slate-600 dark:text-slate-400">
                    <div class="flex justify-between"><span class="text-indigo-600 dark:text-indigo-400">color.primary.500</span><span class="text-slate-800 dark:text-slate-200 font-bold">#4F46E5</span></div>
                    <div class="flex justify-between"><span class="text-emerald-600 dark:text-emerald-400">color.success.500</span><span class="text-slate-800 dark:text-slate-200 font-bold">#22C55E</span></div>
                    <div class="flex justify-between"><span class="text-amber-600 dark:text-amber-400">color.warning.500</span><span class="text-slate-800 dark:text-slate-200 font-bold">#F59E0B</span></div>
                    <div class="flex justify-between"><span class="text-red-600 dark:text-red-400">color.error.500</span><span class="text-slate-800 dark:text-slate-200 font-bold">#EF4444</span></div>
                    <div class="flex justify-between"><span class="text-sky-600 dark:text-sky-400">color.info.500</span><span class="text-slate-800 dark:text-slate-200 font-bold">#0EA5E9</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">radius.lg</span><span class="text-slate-800 dark:text-slate-200 font-bold">12px</span></div>
                </div>
            </div>

        </div>

        <!-- =====================================================================
             COMPONENTS SECTION: Buttons, Inputs, Selects, Cards, Badges, Alerts
             ===================================================================== -->
        <div>
            <h2 class="text-lg font-black text-slate-900 dark:text-white mb-4">Components</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-6">

                <!-- Buttons Col -->
                <div class="space-y-2.5">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-2">Buttons</span>
                    <x-ui.button variant="primary" class="w-full" icon="fas fa-plus">زر أساسي</x-ui.button>
                    <x-ui.button variant="outline" class="w-full">زر ثانوي</x-ui.button>
                    <x-ui.button variant="ghost" class="w-full">زر نصي</x-ui.button>
                    <x-ui.button variant="success" class="w-full" icon="fas fa-check">نجاح</x-ui.button>
                    <x-ui.button variant="danger" class="w-full" icon="fas fa-trash-alt">حذف</x-ui.button>
                    <div class="flex items-center justify-between gap-2 pt-1">
                        <x-ui.button variant="outline" size="icon-sm" icon="fas fa-edit" />
                        <x-ui.button variant="outline" size="icon-sm" icon="fas fa-pen" />
                        <x-ui.button variant="danger" size="icon-sm" icon="fas fa-trash" />
                        <x-ui.button variant="ghost" size="icon-sm" icon="fas fa-ellipsis-h" />
                    </div>
                </div>

                <!-- Inputs Col -->
                <div class="space-y-2.5">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-2">Inputs</span>
                    <x-ui.input placeholder="أدخل النص" size="sm" />
                    <x-ui.input placeholder="حالة التركيز" size="sm" class="border-indigo-500 ring-2 ring-indigo-500/20" />
                    <x-ui.input value="نص مدخل مسبقاً" size="sm" />
                    <x-ui.input placeholder="حقل به خطأ" size="sm" :error="true" />
                    <x-ui.input placeholder="ابحث عن طالب..." icon="fas fa-search" size="sm" />
                </div>

                <!-- Selects & Filters Col -->
                <div class="space-y-2.5">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-2">Selects & Filters</span>
                    <x-ui.select size="sm">
                        <option>اختر الصف</option>
                        <option>الصف الأول الثانوي</option>
                    </x-ui.select>
                    <div class="flex flex-wrap gap-1 p-1.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs">
                        <span class="px-2 py-0.5 rounded-md bg-indigo-50 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 font-bold flex items-center gap-1 text-[11px]">
                            الرياضيات <i class="fas fa-times text-[9px] cursor-pointer"></i>
                        </span>
                        <span class="px-2 py-0.5 rounded-md bg-indigo-50 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 font-bold flex items-center gap-1 text-[11px]">
                            العلوم <i class="fas fa-times text-[9px] cursor-pointer"></i>
                        </span>
                    </div>
                    <x-ui.input type="text" placeholder="اختر التاريخ" iconRight="fas fa-calendar" size="sm" />
                    <div class="flex flex-wrap gap-1.5 pt-1">
                        <x-ui.badge variant="brand" size="sm">+ إضافة</x-ui.badge>
                        <x-ui.badge variant="success" size="sm">نشط</x-ui.badge>
                        <x-ui.badge variant="neutral" size="sm">غير نشط</x-ui.badge>
                    </div>
                </div>

                <!-- Cards Col -->
                <div class="space-y-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-2">Cards</span>
                    <x-education.teacher-card />
                    <x-education.class-card />
                </div>

                <!-- Badges Col -->
                <div class="space-y-3">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-2">Badges</span>
                    <div class="flex flex-wrap gap-2">
                        <x-ui.badge variant="success" size="md">نشط</x-ui.badge>
                        <x-ui.badge variant="brand" size="md">مدفوع</x-ui.badge>
                        <x-ui.badge variant="danger" size="md">غير مدفوع</x-ui.badge>
                        <x-ui.badge variant="info" size="md">مكتمل</x-ui.badge>
                        <x-ui.badge variant="warning" size="md">قيد المراجعة</x-ui.badge>
                        <x-ui.badge variant="neutral" size="md">جديد</x-ui.badge>
                    </div>
                </div>

                <!-- Alerts Col -->
                <div class="space-y-2.5">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-2">Alerts</span>
                    <x-ui.alert type="success" :dismissible="true">تم حفظ البيانات بنجاح</x-ui.alert>
                    <x-ui.alert type="warning" :dismissible="true">يرجى مراجعة البيانات المدخلة</x-ui.alert>
                    <x-ui.alert type="error" :dismissible="true">حدث خطأ ما، يرجى المحاولة مرة أخرى</x-ui.alert>
                    <x-ui.alert type="info" :dismissible="true">هذه رسالة معلومات توضيحية</x-ui.alert>
                </div>

            </div>
        </div>

        <!-- =====================================================================
             PRODUCT UI EXAMPLES SECTION: Stats & Chart, Student, Progress, Activity
             ===================================================================== -->
        <div>
            <h2 class="text-lg font-black text-slate-900 dark:text-white mb-4">Product UI Examples</h2>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- 1. Left: Dashboard Stats & Curve Chart (5 cols) -->
                <div class="lg:col-span-5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-xs font-tajawal">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100">إحصائيات المنصة</h4>
                        <select class="text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-2.5 py-1 text-slate-600 dark:text-slate-300 font-bold outline-none">
                            <option>هذا الشهر</option>
                            <option>الشهر السابق</option>
                        </select>
                    </div>

                    <!-- 4 Mini Stats Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 py-3 border-y border-slate-100 dark:border-slate-800">
                        <div>
                            <span class="text-xs text-slate-400 block">إجمالي الطلاب</span>
                            <span class="text-lg font-extrabold text-slate-900 dark:text-slate-100 block mt-0.5">1,248</span>
                            <span class="text-[10px] font-bold text-emerald-600">+12% من الشهر السابق</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block">معدل الحضور</span>
                            <span class="text-lg font-extrabold text-slate-900 dark:text-slate-100 block mt-0.5">86%</span>
                            <span class="text-[10px] font-bold text-emerald-600">+5%</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block">المستحقات</span>
                            <span class="text-lg font-extrabold text-slate-900 dark:text-slate-100 block mt-0.5">24,980 <span class="text-xs font-normal">EGP</span></span>
                            <span class="text-[10px] font-bold text-emerald-600">+15%</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block">الإيرادات</span>
                            <span class="text-lg font-extrabold text-slate-900 dark:text-slate-100 block mt-0.5">18,600 <span class="text-xs font-normal">EGP</span></span>
                            <span class="text-[10px] font-bold text-emerald-600">+10%</span>
                        </div>
                    </div>

                    <!-- Smooth Interactive Curve Chart (SVG) -->
                    <div class="pt-5">
                        <div class="text-xs font-bold text-slate-500 mb-2">الطلاب الجدد</div>
                        <div class="h-44 w-full relative">
                            <svg class="w-full h-full overflow-visible" viewBox="0 0 500 150">
                                <defs>
                                    <linearGradient id="chartGrad" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#4F46E5" stop-opacity="0.25" />
                                        <stop offset="100%" stop-color="#4F46E5" stop-opacity="0.0" />
                                    </linearGradient>
                                </defs>
                                <!-- Grid Lines -->
                                <line x1="0" y1="30" x2="500" y2="30" stroke="#e2e8f0" stroke-dasharray="3 3" opacity="0.4" />
                                <line x1="0" y1="75" x2="500" y2="75" stroke="#e2e8f0" stroke-dasharray="3 3" opacity="0.4" />
                                <line x1="0" y1="120" x2="500" y2="120" stroke="#e2e8f0" stroke-dasharray="3 3" opacity="0.4" />

                                <!-- Gradient Area -->
                                <path d="M 0 130 Q 80 110, 150 70 T 300 80 T 420 40 T 500 60 L 500 150 L 0 150 Z" fill="url(#chartGrad)" />

                                <!-- Main Line -->
                                <path d="M 0 130 Q 80 110, 150 70 T 300 80 T 420 40 T 500 60" fill="none" stroke="#4F46E5" stroke-width="3.5" stroke-linecap="round" />

                                <!-- Data Dots -->
                                <circle cx="150" cy="70" r="4.5" fill="#4F46E5" stroke="#ffffff" stroke-width="2" />
                                <circle cx="300" cy="80" r="4.5" fill="#4F46E5" stroke="#ffffff" stroke-width="2" />
                                <circle cx="420" cy="40" r="4.5" fill="#4F46E5" stroke="#ffffff" stroke-width="2" />
                                <circle cx="500" cy="60" r="4.5" fill="#4F46E5" stroke="#ffffff" stroke-width="2" />
                            </svg>
                        </div>
                        <div class="flex justify-between text-[10px] text-slate-400 font-mono mt-2">
                            <span>1 مايو</span><span>8 مايو</span><span>15 مايو</span><span>22 مايو</span><span>29 مايو</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Middle: Student Card & Class Progress (4 cols) -->
                <div class="lg:col-span-4 space-y-6">
                    <x-education.student-profile-card />
                    <x-education.class-progress-card />
                </div>

                <!-- 3. Right: Recent Activity, Mobile Preview & Dark Preview (3 cols) -->
                <div class="lg:col-span-3 space-y-6">
                    <x-education.recent-activity-card />

                    <!-- Dark Mode Mini Card Preview -->
                    <div class="p-4 rounded-2xl bg-[#0B0F19] text-white border border-slate-800 shadow-xl font-tajawal">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-6 h-6 rounded-lg bg-indigo-600 flex items-center justify-center text-xs">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <span class="text-xs font-bold">Taalimu Dark Preview</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-center text-xs">
                            <div class="p-2 rounded-xl bg-slate-900 border border-slate-800">
                                <span class="text-[10px] text-slate-400 block">إجمالي الطلاب</span>
                                <span class="text-sm font-black text-white">1,248</span>
                            </div>
                            <div class="p-2 rounded-xl bg-slate-900 border border-slate-800">
                                <span class="text-[10px] text-slate-400 block">معدل الحضور</span>
                                <span class="text-sm font-black text-emerald-400">86%</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- =====================================================================
             FOOTER PILLARS (6 Brand Standards)
             ===================================================================== -->
        <div class="pt-8 border-t border-slate-200 dark:border-slate-800 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 text-center font-tajawal">
            <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-sm mx-auto mb-2">
                    <i class="fas fa-globe"></i>
                </div>
                <h5 class="text-xs font-bold text-slate-900 dark:text-slate-100">RTL First</h5>
                <span class="text-[10px] text-slate-400">جاهز للغة العربية</span>
            </div>

            <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-sm mx-auto mb-2">
                    <i class="fas fa-universal-access"></i>
                </div>
                <h5 class="text-xs font-bold text-slate-900 dark:text-slate-100">Accessible</h5>
                <span class="text-[10px] text-slate-400">متوافق مع معايير الوصول</span>
            </div>

            <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-sm mx-auto mb-2">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h5 class="text-xs font-bold text-slate-900 dark:text-slate-100">Responsive</h5>
                <span class="text-[10px] text-slate-400">يعمل على جميع الأجهزة</span>
            </div>

            <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-sm mx-auto mb-2">
                    <i class="fas fa-layer-group"></i>
                </div>
                <h5 class="text-xs font-bold text-slate-900 dark:text-slate-100">Consistent</h5>
                <span class="text-[10px] text-slate-400">نظام تصميم موحد</span>
            </div>

            <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-sm mx-auto mb-2">
                    <i class="fas fa-expand-arrows-alt"></i>
                </div>
                <h5 class="text-xs font-bold text-slate-900 dark:text-slate-100">Scalable</h5>
                <span class="text-[10px] text-slate-400">قابل للتوسع والنمو</span>
            </div>

            <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-sm mx-auto mb-2">
                    <i class="fas fa-sparkles"></i>
                </div>
                <h5 class="text-xs font-bold text-slate-900 dark:text-slate-100">Modern</h5>
                <span class="text-[10px] text-slate-400">تصميم حديث واحترافي</span>
            </div>
        </div>

    </main>

</body>
</html>
