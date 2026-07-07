import React, { useEffect, useRef } from 'react';

export default function StudentPortal({ student, user, attendances, sales, course, onlineClasses, locale, currency }) {
    const qrContainerRef = useRef(null);
    const isAr = locale === 'ar';

    useEffect(() => {
        // Dynamically load qrcodejs from whitelisted CDN
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js';
        script.async = true;
        script.onload = () => {
            if (qrContainerRef.current && user?.qr_identifier) {
                qrContainerRef.current.innerHTML = '';
                new window.QRCode(qrContainerRef.current, {
                    text: user.qr_identifier,
                    width: 180,
                    height: 180,
                    colorDark : "#000000",
                    colorLight : "#ffffff",
                    correctLevel : window.QRCode.CorrectLevel.H
                });
            }
        };
        document.body.appendChild(script);
        return () => {
            document.body.removeChild(script);
        };
    }, [user]);

    const totalDue = sales?.reduce((sum, sale) => sum + parseFloat(sale.total_amount || 0), 0) || 0;
    const totalPaid = sales?.reduce((sum, sale) => sum + parseFloat(sale.paid_amount || 0), 0) || 0;
    const balance = totalDue - totalPaid;

    const downloadQR = () => {
        const qrCanvas = qrContainerRef.current?.querySelector('canvas');
        if (!qrCanvas) {
            alert(isAr ? 'لم يتم تحميل رمز QR بعد.' : 'QR Code not loaded yet.');
            return;
        }

        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const padding = 20;
        const qrSize = 400; 
        const textHeight = 140;
        const totalWidth = qrSize + (padding * 2);
        const totalHeight = qrSize + textHeight + (padding * 2);

        canvas.width = totalWidth;
        canvas.height = totalHeight;

        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(qrCanvas, padding, padding, qrSize, qrSize);

        ctx.fillStyle = '#000000';
        ctx.font = 'bold 24px Cairo, Arial, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText(student.name, totalWidth / 2, padding + qrSize + 30);
        
        ctx.font = '18px Cairo, Arial, sans-serif';
        ctx.fillStyle = '#64748b';
        let currentY = padding + qrSize + 60;
        
        if (course?.title) {
            ctx.fillText(`${isAr ? 'المجموعة' : 'Group'}: ${course.title}`, totalWidth / 2, currentY);
            currentY += 25;
        }
        
        if (course?.instructor?.name) {
            ctx.fillText(`${isAr ? 'المعلم' : 'Instructor'}: ${course.instructor.name}`, totalWidth / 2, currentY);
            currentY += 25;
        }
        
        ctx.font = '20px monospace';
        ctx.fillStyle = '#4f46e5';
        ctx.fillText(`#${user.qr_identifier}`, totalWidth / 2, currentY + 10);

        const dataUrl = canvas.toDataURL("image/png");
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = dataUrl;
        a.download = `QR_${user.qr_identifier}.png`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    };

    return (
        <div className="min-h-screen bg-slate-900 text-slate-100 font-sans p-6 md:p-12 selection:bg-indigo-500 selection:text-white" dir={isAr ? 'rtl' : 'ltr'}>
            {/* Header section with Indigo Gradient */}
            <header className="max-w-7xl mx-auto bg-gradient-to-r from-indigo-900/60 to-violet-900/60 backdrop-blur-md border border-slate-800 rounded-3xl p-8 mb-12 shadow-xl">
                <span className="text-xs font-semibold tracking-wider text-indigo-400 uppercase bg-indigo-500/10 px-3 py-1 rounded-full">
                    {isAr ? 'بوابة الطالب الإلكترونية' : 'Student Portal'}
                </span>
                <h1 className="text-3xl md:text-4xl font-extrabold mt-3 tracking-tight bg-gradient-to-r from-white via-slate-200 to-slate-400 bg-clip-text text-transparent">
                    {student.name}
                </h1>
                <p className="text-sm text-slate-400 mt-2 flex items-center gap-2">
                    <i className="fas fa-phone text-indigo-400"></i> {student.phone}
                </p>
            </header>

            {/* Grid Layout */}
            <main className="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
                {/* Sidebar */}
                <div className="space-y-8">
                    {/* QR Card */}
                    <div className="bg-slate-800/30 backdrop-blur-md rounded-2xl border border-slate-800 p-6 text-center shadow-lg">
                        <h2 className="text-sm font-bold text-slate-400 mb-4">{isAr ? 'رمز الحضور الشخصي (QR)' : 'Personal Attendance QR'}</h2>
                        <div className="bg-white p-4 rounded-2xl inline-block shadow-inner mb-4">
                            <div ref={qrContainerRef} className="flex justify-center w-[180px] h-[180px] align-middle">
                                <div className="text-slate-400 text-xs flex items-center">{isAr ? 'جاري التحميل...' : 'Loading QR...'}</div>
                            </div>
                        </div>
                        <p className="text-xs text-slate-400 mb-6 px-4">
                            {isAr ? 'اعرض هذا الكود للمعلم لتسجيل حضورك في الحصة.' : 'Show this code to your instructor to mark your attendance.'}
                        </p>
                        <button onClick={downloadQR} className="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-3 px-6 rounded-xl shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/30 transition-all duration-200 flex items-center justify-center gap-2">
                            <i className="fas fa-download"></i> {isAr ? 'تحميل بطاقة QR' : 'Download QR Card'}
                        </button>
                    </div>

                    {/* Financial Summary */}
                    <div className="bg-slate-800/30 backdrop-blur-md rounded-2xl border border-slate-800 p-6 shadow-lg">
                        <h2 className="text-sm font-bold text-slate-400 mb-6 flex items-center gap-2">
                            <i className="fas fa-wallet text-indigo-400"></i> {isAr ? 'الملخص المالي' : 'Financial Summary'}
                        </h2>
                        <div className="space-y-4">
                            <div className="flex justify-between items-center border-b border-slate-800/60 pb-3">
                                <span className="text-xs text-slate-400">{isAr ? 'الإجمالي المطلوب' : 'Total Due'}</span>
                                <span className="text-sm font-semibold text-white">{totalDue.toLocaleString()} {currency}</span>
                            </div>
                            <div className="flex justify-between items-center border-b border-slate-800/60 pb-3">
                                <span className="text-xs text-slate-400">{isAr ? 'إجمالي المدفوع' : 'Total Paid'}</span>
                                <span className="text-sm font-semibold text-emerald-400">{totalPaid.toLocaleString()} {currency}</span>
                            </div>
                            <div className="flex justify-between items-center pt-2">
                                <span className="text-xs font-bold text-white">{isAr ? 'المتبقي المستحق' : 'Balance Required'}</span>
                                <span className="text-lg font-extrabold text-rose-400">{balance.toLocaleString()} {currency}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Main Content Area */}
                <div className="lg:col-span-2 space-y-8">
                    {/* Upcoming Online Classes */}
                    {onlineClasses && onlineClasses.length > 0 && (
                        <div className="bg-slate-800/30 backdrop-blur-md rounded-2xl border-r-4 border-r-emerald-500 border-t border-b border-l border-slate-800 p-6 shadow-lg">
                            <h2 className="text-lg font-bold text-white mb-4 flex items-center gap-2">
                                <i className="fas fa-video text-emerald-400 animate-pulse"></i>
                                {isAr ? 'الدروس الأونلاين القادمة' : 'Upcoming Online Classes'}
                            </h2>
                            <div className="overflow-x-auto">
                                <table className="w-full text-left border-collapse" style={{ textAlign: isAr ? 'right' : 'left' }}>
                                    <thead>
                                        <tr className="border-b border-slate-800 text-slate-400 text-xs">
                                            <th className="pb-3 font-semibold">{isAr ? 'الدرس وموعد البدء' : 'Lesson & Start Time'}</th>
                                            <th className="pb-3 font-semibold text-center">{isAr ? 'الرابط' : 'Link'}</th>
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y divide-slate-800/40">
                                        {onlineClasses.map((lesson, idx) => (
                                            <tr key={idx} className="hover:bg-slate-800/20 transition-colors">
                                                <td className="py-4">
                                                    <div className="text-sm font-bold text-white">{lesson.title}</div>
                                                    <div className="text-xs text-slate-400 mt-1 flex items-center gap-2">
                                                        <i className="far fa-clock text-indigo-400"></i>
                                                        {new Date(lesson.start_time).toLocaleString(locale)}
                                                        <span className="bg-slate-800 text-slate-300 px-2 py-0.5 rounded text-[10px] font-semibold uppercase">{lesson.platform}</span>
                                                    </div>
                                                </td>
                                                <td className="py-4 text-center">
                                                    <a href={lesson.meeting_link} target="_blank" rel="noopener noreferrer" className="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold py-2 px-4 rounded-xl shadow-md transition-all">
                                                        <i className="fas fa-play"></i> {isAr ? 'انضمام للدرس' : 'Join Lesson'}
                                                    </a>
                                                    {lesson.meeting_password && (
                                                        <div className="text-[10px] text-slate-400 mt-1">{isAr ? 'الباسورد' : 'Password'}: <code>{lesson.meeting_password}</code></div>
                                                    )}
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    )}

                    {/* Attendance Record */}
                    <div className="bg-slate-800/30 backdrop-blur-md rounded-2xl border border-slate-800 p-6 shadow-lg">
                        <h2 className="text-lg font-bold text-white mb-4 flex items-center gap-2">
                            <i className="fas fa-calendar-check text-indigo-400"></i>
                            {isAr ? 'سجل الحضور والغياب' : 'Attendance Record'}
                        </h2>
                        <div className="overflow-x-auto">
                            <table className="w-full text-left border-collapse" style={{ textAlign: isAr ? 'right' : 'left' }}>
                                <thead>
                                    <tr className="border-b border-slate-800 text-slate-400 text-xs">
                                        <th className="pb-3 font-semibold">{isAr ? 'التاريخ' : 'Date'}</th>
                                        <th className="pb-3 font-semibold">{isAr ? 'المجموعة' : 'Group'}</th>
                                        <th className="pb-3 font-semibold">{isAr ? 'الحالة' : 'Status'}</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-800/40">
                                    {attendances && attendances.length > 0 ? (
                                        attendances.map((atten, idx) => (
                                            <tr key={idx} className="hover:bg-slate-800/20 transition-colors">
                                                <td className="py-4 text-sm font-medium text-slate-200">{atten.session_date}</td>
                                                <td className="py-4 text-sm text-slate-400">{atten.course?.title}</td>
                                                <td className="py-4 text-xs font-bold">
                                                    {atten.status === 'present' ? (
                                                        <span className="bg-emerald-500/10 text-emerald-400 px-3 py-1 rounded-full border border-emerald-500/20">{isAr ? 'حضور' : 'Present'}</span>
                                                    ) : atten.status === 'late' ? (
                                                        <span className="bg-amber-500/10 text-amber-400 px-3 py-1 rounded-full border border-amber-500/20">{isAr ? 'تأخير' : 'Late'}</span>
                                                    ) : (
                                                        <span className="bg-rose-500/10 text-rose-400 px-3 py-1 rounded-full border border-rose-500/20">{isAr ? 'غياب' : 'Absent'}</span>
                                                    )}
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colspan="3" className="py-8 text-center text-sm text-slate-400">
                                                {isAr ? 'لا يوجد سجل حضور حالياً.' : 'No attendance record found.'}
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {/* Payment Record */}
                    <div className="bg-slate-800/30 backdrop-blur-md rounded-2xl border border-slate-800 p-6 shadow-lg">
                        <h2 className="text-lg font-bold text-white mb-6 flex items-center gap-2">
                            <i className="fas fa-receipt text-indigo-400"></i>
                            {isAr ? 'سجل المدفوعات' : 'Payment Record'}
                        </h2>
                        <div className="space-y-4">
                            {sales && sales.length > 0 ? (
                                sales.map((sale, idx) => (
                                    <div key={idx} className="flex justify-between items-center p-4 bg-slate-800/40 border border-slate-800/60 rounded-2xl hover:bg-slate-800/60 transition-all duration-200">
                                        <div className="flex items-center gap-3">
                                            <div className="w-10 h-10 rounded-full bg-indigo-500/10 flex items-center justify-center text-indigo-400">
                                                <i className="fas fa-money-bill-wave"></i>
                                            </div>
                                            <div>
                                                <h3 className="text-sm font-bold text-white">
                                                    {isAr ? `تحصيل ${parseFloat(sale.paid_amount).toLocaleString()} ${currency}` : `Collected ${parseFloat(sale.paid_amount).toLocaleString()} ${currency}`}
                                                </h3>
                                                <span className="text-[10px] text-slate-400 mt-1 block">
                                                    {new Date(sale.created_at).toLocaleString(locale)}
                                                </span>
                                            </div>
                                        </div>
                                        <span className="text-xs font-semibold text-slate-300 bg-slate-800 px-3 py-1 rounded-full border border-slate-700/50">
                                            {sale.payment_method}
                                        </span>
                                    </div>
                                ))
                            ) : (
                                <p className="text-center py-8 text-sm text-slate-400">
                                    {isAr ? 'لا يوجد أي مدفوعات مسجلة.' : 'No registered payments found.'}
                                </p>
                            )}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    );
}
