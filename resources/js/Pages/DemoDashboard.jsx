import React from 'react';

export default function DemoDashboard({ user, stats, leaderboard }) {
    return (
        <div className="min-h-screen bg-slate-900 text-slate-100 font-sans p-6 md:p-12 selection:bg-indigo-500 selection:text-white">
            {/* Header */}
            <header className="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-12">
                <div>
                    <span className="text-xs font-semibold tracking-wider text-indigo-400 uppercase bg-indigo-500/10 px-3 py-1 rounded-full">
                        Taalimu Next-Gen UI
                    </span>
                    <h1 className="text-3xl md:text-4xl font-extrabold mt-2 tracking-tight bg-gradient-to-r from-white via-slate-200 to-slate-400 bg-clip-text text-transparent">
                        مرحباً بك، {user?.name || 'أستاذنا'} 👋
                    </h1>
                    <p className="text-sm text-slate-400 mt-1">
                        هذه عينة حية للواجهة الاحترافية القادمة المبنية بـ React & Inertia.js.
                    </p>
                </div>
                <div className="flex gap-3">
                    <button className="bg-slate-800 hover:bg-slate-700 text-slate-200 px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 border border-slate-700">
                        الإعدادات
                    </button>
                    <button className="bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white px-5 py-2 rounded-xl text-sm font-medium shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/30 transition-all duration-200">
                        إنشاء كورس جديد
                    </button>
                </div>
            </header>

            {/* Stats Cards */}
            <main className="max-w-7xl mx-auto">
                <section className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                    {[
                        { title: 'الطلاب النشطين', value: stats?.activeStudents || '1,248', change: '+12% هذا الشهر', color: 'indigo' },
                        { title: 'الكورسات المنشورة', value: stats?.activeCourses || '18', change: 'تم تحديثها مؤخراً', color: 'emerald' },
                        { title: 'الأرباح الشهرية', value: stats?.monthlyRevenue || '$4,850', change: '+8.4% نمو متوقع', color: 'rose' },
                        { title: 'معدل الحضور العام', value: `${stats?.attendanceRate || '94'}%`, change: 'ممتاز ومستقر', color: 'amber' },
                    ].map((card, idx) => (
                        <div key={idx} className="relative group bg-slate-800/40 backdrop-blur-md p-6 rounded-2xl border border-slate-800 hover:border-slate-700 transition-all duration-300">
                            <div className="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                            <h3 className="text-xs font-semibold text-slate-400 tracking-wider uppercase mb-2">{card.title}</h3>
                            <div className="text-3xl font-bold text-white mb-2">{card.value}</div>
                            <span className="text-xs text-indigo-400 font-medium">{card.change}</span>
                        </div>
                    ))}
                </section>

                {/* Dashboard grid */}
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Leaderboard Card */}
                    <div className="lg:col-span-2 bg-slate-800/30 backdrop-blur-md rounded-2xl border border-slate-800 p-6">
                        <div className="flex justify-between items-center mb-6">
                            <h2 className="text-lg font-bold text-white">لوحة الشرف والمتميزين 🏆</h2>
                            <span className="text-xs text-slate-400">أعلى 5 طلاب هذا الأسبوع</span>
                        </div>
                        <div className="space-y-4">
                            {leaderboard && leaderboard.length > 0 ? (
                                leaderboard.map((row, idx) => (
                                    <div key={idx} className="flex items-center justify-between p-3 bg-slate-800/40 rounded-xl hover:bg-slate-800/60 transition-colors duration-200">
                                        <div className="flex items-center gap-3">
                                            <span className="font-bold text-sm text-indigo-400 w-5">#{idx + 1}</span>
                                            <div className="w-8 h-8 rounded-full bg-indigo-500/10 flex items-center justify-center text-xs font-bold text-indigo-300">
                                                {row.name ? row.name[0].toUpperCase() : '?'}
                                            </div>
                                            <div>
                                                <div className="text-sm font-semibold text-white">{row.name}</div>
                                                <div className="text-xs text-slate-400">طالب نشط</div>
                                            </div>
                                        </div>
                                        <span className="text-xs font-bold text-indigo-400 bg-indigo-500/10 px-3 py-1 rounded-full">
                                            {row.points || 0} نقطة
                                        </span>
                                    </div>
                                ))
                            ) : (
                                <p className="text-sm text-slate-400">لا يوجد بيانات لوحة شرف حالياً.</p>
                            )}
                        </div>
                    </div>

                    {/* Quick Actions & System Info */}
                    <div className="bg-slate-800/30 backdrop-blur-md rounded-2xl border border-slate-800 p-6 flex flex-col justify-between">
                        <div>
                            <h2 className="text-lg font-bold text-white mb-4">أداء النظام وحالة الحماية 🛡️</h2>
                            <div className="space-y-4">
                                <div className="flex justify-between items-center text-xs text-slate-400 border-b border-slate-800 pb-2">
                                    <span>عزل المستأجرين (Tenancy)</span>
                                    <span className="text-emerald-400 font-semibold">نشط ومؤمن 🟢</span>
                                </div>
                                <div className="flex justify-between items-center text-xs text-slate-400 border-b border-slate-800 pb-2">
                                    <span>رؤوس الأمان (CSP & Headers)</span>
                                    <span className="text-emerald-400 font-semibold">مدمجة ومفعلة 🟢</span>
                                </div>
                                <div className="flex justify-between items-center text-xs text-slate-400 border-b border-slate-800 pb-2">
                                    <span>مزود الفرونت إند الجديد</span>
                                    <span className="text-indigo-400 font-semibold">React + Inertia</span>
                                </div>
                            </div>
                        </div>

                        <div className="mt-8 bg-slate-800/60 p-4 rounded-xl border border-slate-700/50">
                            <h3 className="text-xs font-bold text-white mb-2">💡 نصيحة التصميم الجديد</h3>
                            <p className="text-xs text-slate-400 leading-relaxed">
                                نستخدم هنا تباينات عالية وألوان داكنة مشبعة لتقليل إجهاد العين أثناء الدراسة ليلاً مع إضافة تلميحات ثلاثية الأبعاد خفيفة جداً.
                            </p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    );
}
