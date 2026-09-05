<?php

use Illuminate\Support\Facades\Route;
use Modules\Center\Http\Controllers\AnalyticsController;
use Modules\Center\Http\Controllers\AssetController;
use Modules\Center\Http\Controllers\AssignmentController;
use Modules\Center\Http\Controllers\AttendanceController;
use Modules\Center\Http\Controllers\AuthController;
use Modules\Center\Http\Controllers\BillingController;
use Modules\Center\Http\Controllers\BookingController;
use Modules\Center\Http\Controllers\CenterController;
use Modules\Center\Http\Controllers\ClassroomController;
use Modules\Center\Http\Controllers\CourseController;
use Modules\Center\Http\Controllers\CoursePlayerController;
use Modules\Center\Http\Controllers\CurriculumController;
use Modules\Center\Http\Controllers\InstructorController;
use Modules\Center\Http\Controllers\LeaderboardController;
use Modules\Center\Http\Controllers\NotificationController;
use Modules\Center\Http\Controllers\QuestionBankController;
use Modules\Center\Http\Controllers\QuizController;
use Modules\Center\Http\Controllers\ResourceController;
use Modules\Center\Http\Controllers\SaleController;
use Modules\Center\Http\Controllers\ScheduleController;
use Modules\Center\Http\Controllers\SettingsController;
use Modules\Center\Http\Controllers\StudentController;
use Modules\Center\Http\Controllers\SubscriptionController;
use Modules\Center\Http\Controllers\TicketController;
use Modules\Center\Http\Controllers\UserController;
use Modules\Center\Http\Controllers\PublicCourseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Public Course Routes (SEO-friendly, no authentication required)
Route::get('c/{tenant}/courses', [PublicCourseController::class, 'index'])
    ->name('center.public.courses.index');
Route::get('c/{tenant}/courses/{course}', [PublicCourseController::class, 'show'])
    ->name('center.public.courses.show');

// Define the route group closure once to avoid duplication
$tenantRoutes = function () {
    // Taalimu Design System 1.0 Showcase
    Route::get('design-system', function () {
        return view('design-system.index');
    })->name('center.design-system.index');

    // Guest Routes with rate limiting
    Route::middleware(['guest', 'prevent-back-history'])->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('center.login');
        Route::post('login', [AuthController::class, 'login'])
            ->middleware('throttle:login')
            ->name('center.login.submit');
        Route::post('login/sso', [AuthController::class, 'ssoLogin'])->name('center.login.sso');

        // Magic Login (QR Code) - Support both GET (page) and POST (confirmation)
        Route::get('magic-login/{student}', [AuthController::class, 'magicLogin'])
            ->name('center.login.magic')
            ->middleware('signed');
        Route::post('magic-login/{student}', [AuthController::class, 'magicLogin'])
            ->middleware(['signed', 'throttle:10,1'])
            ->name('center.login.magic.post');
    });

    // Force Password Change Routes
    // NOTE: intentionally OUTSIDE the guest group — users with must_change_password=1
    // are authenticated; the guest middleware would bounce them back (redirect loop).
    Route::middleware(['auth', 'force_password_change'])->group(function () {
        Route::get('password/change', [AuthController::class, 'showChangePasswordForm'])->name('center.password.change');
        Route::post('password/change', [AuthController::class, 'changePassword'])->name('center.password.change.submit');
    });

    // Self-Service Online Payment — public link (signed), no login required
    // The page is gated by the online_payments feature per tenant.
    Route::get('pay/{sale}', [\Modules\Center\Http\Controllers\OnlinePaymentController::class, 'show'])
        ->middleware(['signed'])
        ->name('center.pay.show');
    Route::post('pay/{sale}', [\Modules\Center\Http\Controllers\OnlinePaymentController::class, 'pay'])
        ->middleware(['signed'])
        ->name('center.pay.submit');
    Route::get('pay/{sale}/result/{status}', [\Modules\Center\Http\Controllers\OnlinePaymentController::class, 'result'])
        ->whereIn('status', ['success', 'failed', 'paid'])
        ->name('center.pay.result');

    // Student Self-Registration via Token (Public)
    // Throttled: public, unauthenticated endpoints that create users + sales,
    // so they must be rate-limited against mass account creation / spam.
    Route::get('register/group/{token}', [\Modules\Center\Http\Controllers\StudentRegistrationController::class, 'index'])
        ->middleware('throttle:30,1')
        ->name('group.register');
    Route::post('register/group/{token}', [\Modules\Center\Http\Controllers\StudentRegistrationController::class, 'store'])
        ->middleware('throttle:registration')
        ->name('group.register.submit');
    Route::get('register/success/{user}', [\Modules\Center\Http\Controllers\StudentRegistrationController::class, 'success'])
        ->middleware('throttle:30,1')
        ->name('group.registration.success');

    // QR Attendance Mark - Public route (protected by signed URL, NOT by auth middleware)
    // Students scan this from their phone and may not be logged in
    Route::match(['get', 'post'], 'attendance/mark/{schedule}', [AttendanceController::class, 'markByQr'])
        ->middleware('throttle:scanner')
        ->name('center.attendance.markByQr');
    Route::post('attendance/mark/{schedule}/login', [AttendanceController::class, 'loginAndMark'])
        ->middleware('throttle:scanner')
        ->name('center.attendance.loginAndMark');

    // Protected Routes (Auth Only - No Subscription Check)
    Route::middleware(['auth', 'force_password_change'])->group(function () {
        Route::match(['get', 'post'], 'logout', [AuthController::class, 'logout'])->name('center.logout');

        // Two-Factor Authentication Routes (Ultimate Security Flow)
        Route::get('2fa/setup', [\App\Http\Controllers\TwoFactorController::class, 'showSetupForm'])->name('2fa.setup');
        Route::post('2fa/setup', [\App\Http\Controllers\TwoFactorController::class, 'confirmSetup'])->name('2fa.setup.confirm');

        Route::get('2fa/verify', [\App\Http\Controllers\TwoFactorController::class, 'showVerifyForm'])->name('2fa.verify');
        Route::post('2fa/verify', [\App\Http\Controllers\TwoFactorController::class, 'verify'])
            ->middleware('throttle:5,1')
            ->name('2fa.verify.post');

        // Original/Enable/Disable routes if needed elsewhere
        Route::get('2fa/enable', [\App\Http\Controllers\TwoFactorController::class, 'showSetupForm'])->name('2fa.enable');
        Route::post('2fa/disable', [\App\Http\Controllers\TwoFactorController::class, 'disable'])->name('2fa.disable');
        Route::post('2fa/store', [\App\Http\Controllers\TwoFactorController::class, 'confirmSetup'])->name('2fa.store');

        // Subscription Routes (Accessible even if subscription expired)
        Route::get('subscription', [SubscriptionController::class, 'index'])->name('center.subscription.index');
        Route::get('subscription/checkout/{package}', [SubscriptionController::class, 'checkout'])->name('center.subscription.checkout');
        Route::get('subscription/success', [SubscriptionController::class, 'success'])->name('center.subscription.success');
        Route::get('subscription/cancel', [SubscriptionController::class, 'cancel'])->name('center.subscription.cancel');

        // Onboarding Routes (Must be accessible before onboarding is complete)
        // SEC-AUTH-1: tenant-admin only — mutates tenant-wide data.
        Route::middleware('center.admin')->group(function () {
            Route::get('onboarding', [\Modules\Center\Http\Controllers\OnboardingController::class, 'show'])->name('center.onboarding.show');
            Route::post('onboarding/submit', [\Modules\Center\Http\Controllers\OnboardingController::class, 'submit'])->name('center.onboarding.submit');
            Route::post('onboarding/update-locale', [\Modules\Center\Http\Controllers\OnboardingController::class, 'updateLocale'])->name('center.onboarding.update-locale');

            // One-time fix: Creates pending invoices for students who were enrolled via old onboarding (no finance record)
            // Visit this URL once while logged in as admin, then it's safe to leave it (it's idempotent)
            Route::get('onboarding/fix-invoices', [\Modules\Center\Http\Controllers\OnboardingController::class, 'fixMissingInvoices'])->name('center.onboarding.fix-invoices');
        });
    });

    // Protected Routes with Subscription Check and Onboarding Check
    Route::middleware(['auth', 'subscription', 'force_password_change', 'onboarding.completed', '2fa', 'prevent-back-history'])->group(function () {
        // Dashboard
        Route::get('/', [CenterController::class, 'index'])->name('center.dashboard');
        Route::get('/dashboard', [CenterController::class, 'index'])->name('center.dashboard.alt');
        Route::post('/demo/seed', [\Modules\Center\Http\Controllers\DemoDataController::class, 'seed'])->name('center.demo.seed');
        Route::post('/demo/reset', [\Modules\Center\Http\Controllers\DemoDataController::class, 'destroy'])->name('center.demo.reset');

        // User Profile
        Route::get('profile', [UserController::class, 'profile'])->name('center.profile');
        Route::post('profile', [UserController::class, 'updateProfile'])->name('center.profile.update');

        // Student Management (Admin/Secretary only)
        Route::middleware(['can:view students'])->group(function () {
            Route::get('students', [StudentController::class, 'index'])->name('center.students.index');
            Route::get('students/export', [StudentController::class, 'export'])->name('center.students.export');
            // Async picker for enrollment modals (lightweight JSON, max 20 rows)
            Route::get('students/search', [StudentController::class, 'search'])
                ->middleware('throttle:60,1')
                ->name('center.students.search');
            Route::get('students/check-phone', [StudentController::class, 'checkPhone'])
                ->middleware('throttle:60,1')
                ->name('center.students.check-phone');
        });

        Route::middleware(['can:create students'])->group(function () {
            Route::get('students/create', [StudentController::class, 'create'])->name('center.students.create');
            Route::post('students', [StudentController::class, 'store'])->name('center.students.store');
            Route::get('students/template', [StudentController::class, 'downloadTemplate'])->name('center.students.template');
            Route::get('students/import', [StudentController::class, 'importForm'])->name('center.students.import');
            Route::post('students/import', [StudentController::class, 'import'])
                ->middleware('throttle:20,1')
                ->name('center.students.import.post');
        });

        Route::middleware(['can:view students'])->group(function () {
            Route::get('students/{student}', [StudentController::class, 'show'])->name('center.students.show');
        });

        Route::middleware(['can:edit students'])->group(function () {
            Route::get('students/{student}/edit', [StudentController::class, 'edit'])->name('center.students.edit');
            Route::put('students/{student}', [StudentController::class, 'update'])->name('center.students.update');
            Route::post('students/{student}/toggle-status', [StudentController::class, 'toggleStatus'])->name('center.students.toggle-status');
            Route::post('students/{student}/reset-password', [StudentController::class, 'resetPassword'])
                ->middleware('throttle:password-reset')
                ->name('center.students.reset-password');
            Route::post('students/{student}/remind-debt', [StudentController::class, 'remindDebt'])->name('center.students.remind-debt');
            Route::post('students/{student}/send-email', [StudentController::class, 'sendEmail'])->name('center.students.send-email');
            Route::get('students/{student}/id-card', [StudentController::class, 'idCard'])->name('center.students.id-card');
            Route::get('students/{student}/statement', [StudentController::class, 'statement'])->name('center.students.statement');
            Route::post('students/{id}/restore', [StudentController::class, 'restore'])->name('center.students.restore');
            Route::patch('students/{student}/courses/{course}/toggle-status', [StudentController::class, 'toggleCourseStatus'])->name('center.students.courses.toggle-status');
            Route::delete('students/{student}/courses/{course}/unenroll', [StudentController::class, 'unenrollCourse'])->name('center.students.courses.unenroll');
        });

        Route::middleware(['can:delete students'])->group(function () {
            Route::delete('students/{student}', [StudentController::class, 'destroy'])->name('center.students.destroy');
        });

        // Bulk Student Actions
        Route::middleware(['can:edit students'])->group(function () {
            Route::post('students/bulk/status', [StudentController::class, 'bulkStatus'])->name('center.students.bulk-status');
            Route::post('students/bulk/delete', [StudentController::class, 'bulkDelete'])->name('center.students.bulk-delete');
            Route::post('students/bulk/export', [StudentController::class, 'bulkExport'])->name('center.students.bulk-export');
        });

        // Guardian Lookup (Helper for Sibling Support)
        Route::get('guardians/lookup', [StudentController::class, 'lookupGuardian'])->name('center.guardians.lookup');

        // Instructor Management (Admin/Secretary only)
        Route::middleware(['can:view instructors'])->group(function () {
            Route::get('instructors', [InstructorController::class, 'index'])->name('center.instructors.index');
        });

        Route::middleware(['can:create instructors'])->group(function () {
            Route::get('instructors/create', [InstructorController::class, 'create'])->name('center.instructors.create');
            Route::post('instructors', [InstructorController::class, 'store'])->name('center.instructors.store');
        });

        Route::middleware(['can:view instructors'])->group(function () {
            Route::get('instructors/{instructor}', [InstructorController::class, 'show'])->name('center.instructors.show');
        });

        Route::middleware(['can:edit instructors'])->group(function () {
            Route::get('instructors/{instructor}/edit', [InstructorController::class, 'edit'])->name('center.instructors.edit');
            Route::put('instructors/{instructor}', [InstructorController::class, 'update'])->name('center.instructors.update');
            Route::post('instructors/{instructor}/toggle-status', [InstructorController::class, 'toggleStatus'])->name('center.instructors.toggle-status');
            Route::post('instructors/{instructor}/payout', [InstructorController::class, 'payout'])->name('center.instructors.payout');
            Route::get('instructors/{instructor}/statement', [InstructorController::class, 'statement'])->name('center.instructors.statement');
        });

        Route::middleware(['can:delete instructors'])->group(function () {
            Route::delete('instructors/{instructor}', [InstructorController::class, 'destroy'])->name('center.instructors.destroy');
        });

        // Online Classes Management
        Route::middleware(['can:manage schedule'])->group(function () {
            Route::get('online-classes', [\Modules\Center\Http\Controllers\OnlineClassController::class, 'index'])->name('center.online_classes.index');
            Route::delete('online-classes/{class}', [\Modules\Center\Http\Controllers\OnlineClassController::class, 'destroy'])->name('center.online_classes.destroy');
        });

        // Course Management
        Route::middleware(['can:view courses'])->group(function () {
            Route::get('courses', [CourseController::class, 'index'])->name('center.courses.index');
        });

        Route::middleware(['can:create courses'])->group(function () {
            Route::get('courses/create', [CourseController::class, 'create'])->name('center.courses.create');
            Route::post('courses', [CourseController::class, 'store'])->name('center.courses.store');

            // AI content assistant (external paid API — strict per-user rate limit)
            Route::post('courses/ai/outline', [\Modules\Center\Http\Controllers\AIAssistantController::class, 'generateOutline'])
                ->middleware('throttle:ai')
                ->name('center.courses.ai.outline');
            Route::post('courses/ai/quiz', [\Modules\Center\Http\Controllers\AIAssistantController::class, 'generateQuiz'])
                ->middleware('throttle:ai')
                ->name('center.courses.ai.quiz');
            Route::post('courses/ai/description', [\Modules\Center\Http\Controllers\AIAssistantController::class, 'improveDescription'])
                ->middleware('throttle:ai')
                ->name('center.courses.ai.description');
        });

        Route::middleware(['can:view courses'])->group(function () {
            Route::get('courses/{course}', [CourseController::class, 'show'])->name('center.courses.show');
        });

        Route::middleware(['can:edit courses'])->group(function () {
            Route::get('courses/{course}/edit', [CourseController::class, 'edit'])->name('center.courses.edit');
            Route::put('courses/{course}', [CourseController::class, 'update'])->name('center.courses.update');
            Route::post('courses/{course}/toggle-status', [CourseController::class, 'toggleStatus'])->name('center.courses.toggle-status');
            Route::post('courses/{course}/enroll', [CourseController::class, 'enroll'])->name('center.courses.enroll');
            Route::post('courses/{course}/quick-enroll', [CourseController::class, 'quickEnroll'])->name('center.courses.quick-enroll');
        });

        Route::middleware(['can:delete courses'])->group(function () {
            Route::delete('courses/{course}', [CourseController::class, 'destroy'])->name('center.courses.destroy');
        });

        Route::post('courses/{course}/lessons/{lesson}/complete', [CourseController::class, 'completeLesson'])->name('center.lessons.complete');

        // Course Player Route (Accessible to enrolled students)
        Route::get('courses/{course}/player/{lesson?}', [CoursePlayerController::class, 'show'])->name('center.courses.player');

        // Curriculum Management (Admin/Instructor only)
        Route::middleware(['can:edit courses'])->group(function () {
            Route::get('courses/{course}/curriculum', [CurriculumController::class, 'edit'])->name('center.curriculum.edit');
            Route::post('courses/{course}/sections', [CurriculumController::class, 'storeSection'])->name('center.sections.store');
            Route::put('sections/{section}', [CurriculumController::class, 'updateSection'])->name('center.sections.update');
            Route::delete('sections/{section}', [CurriculumController::class, 'destroySection'])->name('center.sections.destroy');
            Route::post('sections/{section}/lessons', [CurriculumController::class, 'storeLesson'])->name('center.lessons.store');
            Route::put('lessons/{lesson}', [CurriculumController::class, 'updateLesson'])->name('center.lessons.update');
            Route::delete('lessons/{lesson}', [CurriculumController::class, 'destroyLesson'])->name('center.lessons.destroy');
            Route::post('courses/{course}/reorder-sections', [CurriculumController::class, 'reorderSections'])->name('center.sections.reorder');
            Route::post('sections/{section}/reorder-lessons', [CurriculumController::class, 'reorderLessons'])->name('center.lessons.reorder');

            // Resources
            Route::post('courses/{course}/resources', [ResourceController::class, 'store'])->name('center.resources.store');
            Route::delete('resources/{resource}', [ResourceController::class, 'destroy'])->name('center.resources.destroy');
        });

        // Student/Public Resource Access
        Route::get('resources/{resource}/download', [ResourceController::class, 'download'])->name('center.resources.download');

        // Quiz Management
        Route::middleware(['feature:manage_exams'])->group(function () {
            Route::get('quizzes', [QuizController::class, 'index'])->name('center.quizzes.index');
            Route::post('lessons/{lesson}/quiz', [QuizController::class, 'store'])->name('center.quizzes.store');
            Route::get('quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('center.quizzes.edit');
            Route::put('quizzes/{quiz}', [QuizController::class, 'update'])->name('center.quizzes.update');
            Route::post('quizzes/{quiz}/questions', [QuizController::class, 'storeQuestion'])->name('center.quizzes.questions.store');
            Route::put('quiz-questions/{question}', [QuizController::class, 'updateQuestion'])->name('center.quiz.questions.update');
            Route::delete('quiz-questions/{question}', [QuizController::class, 'destroyQuestion'])->name('center.quiz.questions.destroy');
            Route::post('questions/{question}/options', [QuizController::class, 'storeOption'])->name('center.options.store');
            Route::put('options/{option}', [QuizController::class, 'updateOption'])->name('center.options.update');
            Route::delete('options/{option}', [QuizController::class, 'destroyOption'])->name('center.options.destroy');
            Route::post('options/{option}/correct', [QuizController::class, 'setCorrectOption'])->name('center.options.correct');

            // Question Bank
            Route::post('questions/categories', [QuestionBankController::class, 'storeCategory'])->name('center.questions.categories.store');
            Route::resource('questions', QuestionBankController::class)->names('center.questions');
            Route::get('leaderboard', [LeaderboardController::class, 'index'])->name('center.leaderboard.index');

            // Student Quiz Actions
            Route::get('quizzes/{quiz}', [QuizController::class, 'show'])->name('center.quizzes.show');
            Route::post('quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('center.quizzes.submit');
            Route::get('quiz-attempts/{attempt}', [QuizController::class, 'result'])->name('center.quizzes.result');
        });

        // Assignment Management
        Route::middleware(['can:edit courses'])->group(function () {
            Route::post('lessons/{lesson}/assignment', [AssignmentController::class, 'store'])->name('center.assignments.store');
            Route::get('assignments/{assignment}/edit', [AssignmentController::class, 'edit'])->name('center.assignments.edit');
            Route::put('assignments/{assignment}', [AssignmentController::class, 'update'])->name('center.assignments.update');
            Route::get('assignments/{assignment}/submissions', [AssignmentController::class, 'submissions'])->name('center.assignments.submissions');
            Route::post('submissions/{submission}/grade', [AssignmentController::class, 'grade'])->name('center.assignments.grade');
        });

        // Student Assignment Actions
        Route::get('assignments/{assignment}', [AssignmentController::class, 'show'])->name('center.assignments.show');
        Route::post('assignments/{assignment}/submit', [AssignmentController::class, 'submit'])->name('center.assignments.submit');
        Route::get('submissions/{submission}/download', [AssignmentController::class, 'download'])->name('center.assignments.download');

        // Billing & Invoices
        Route::middleware(['can:manage billing'])->group(function () {
            Route::get('billing', [BillingController::class, 'index'])->name('center.billing.index');
            Route::get('billing/create', [BillingController::class, 'create'])->name('center.billing.create');
            Route::post('billing', [BillingController::class, 'store'])->name('center.billing.store');
            Route::get('billing/{invoice}', [BillingController::class, 'show'])->name('center.billing.show');
            Route::post('billing/{invoice}/pay', [BillingController::class, 'pay'])->name('center.billing.pay');
        });

        // Support Tickets
        Route::resource('tickets', TicketController::class)->names('center.tickets');
        Route::post('tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('center.tickets.reply');
        Route::post('tickets/{ticket}/close', [TicketController::class, 'close'])->name('center.tickets.close');

        // Sales & Expenses (Core Operations for all plans)
        Route::middleware(['can:view sales'])->group(function () {
            Route::get('sales/students/lookup', [SaleController::class, 'lookupStudents'])->name('center.sales.lookup');
            Route::get('sales/overdue', [SaleController::class, 'overdue'])->name('center.sales.overdue');
            Route::get('sales/account', [SaleController::class, 'account'])->name('center.sales.account');
            Route::get('sales/student-statement/{id}', [SaleController::class, 'downloadStatement'])->name('center.sales.statement');
            Route::get('sales/student-summary/{id}', [SaleController::class, 'getStudentSummary'])->name('center.sales.student-summary');
            Route::post('sales/{sale}/payment', [SaleController::class, 'addPayment'])->name('center.sales.payment');
            Route::post('sales/mark-paid', [SaleController::class, 'markPaid'])->name('center.sales.mark-paid');
            Route::post('sales/{sale}/refund', [SaleController::class, 'refund'])->name('center.sales.refund');
            Route::get('sales/{sale}/checkout', [SaleController::class, 'checkout'])->name('center.sales.checkout');
            Route::get('payments/{payment}/receipt', [SaleController::class, 'downloadReceipt'])->name('center.payments.receipt');
            Route::resource('sales', SaleController::class)->names('center.sales');
        });

        Route::middleware(['can:manage billing'])->group(function () {
            Route::resource('expenses', \Modules\Center\Http\Controllers\ExpenseController::class)->names('center.expenses');
        });

        // Analytics
        Route::middleware(['can:view reports'])->group(function () {
            Route::get('analytics', [AnalyticsController::class, 'index'])->name('center.analytics.index');
            Route::get('analytics/students', [AnalyticsController::class, 'students'])->name('center.analytics.students');
            Route::get('analytics/instructors', [AnalyticsController::class, 'instructors'])->name('center.analytics.instructors');
            Route::get('analytics/courses', [AnalyticsController::class, 'courses'])->name('center.analytics.courses');

            Route::middleware(['feature:financial_reports', 'throttle:60,1'])->group(function () {
                Route::get('analytics/finance', [AnalyticsController::class, 'finance'])->name('center.analytics.finance');
                Route::get('analytics/finance/profit-loss', [AnalyticsController::class, 'profitLoss'])->name('center.analytics.profit_loss');
                Route::get('analytics/finance/commissions', [AnalyticsController::class, 'commissions'])->name('center.analytics.commissions');
                Route::get('analytics/finance/discounts', [AnalyticsController::class, 'discounts'])->name('center.analytics.discounts');
                Route::get('analytics/finance/taxes', [AnalyticsController::class, 'taxes'])->name('center.analytics.taxes');
            });

            Route::middleware(['feature:attendance_tracking', 'throttle:10,1'])->group(function () {
                Route::get('analytics/attendance', [AnalyticsController::class, 'attendance'])->name('center.analytics.attendance');
            });

        });

        // Attendance
        Route::middleware(['feature:attendance_tracking'])->group(function () {
            Route::get('attendance', [AttendanceController::class, 'index'])->name('center.attendance.index');
            Route::get('attendance/schedule/{schedule}', [AttendanceController::class, 'show'])->name('center.attendance.show');
            Route::post('attendance', [AttendanceController::class, 'store'])->name('center.attendance.store');

            // QR Attendance (showQr is for teachers only, markByQr moved to public routes above)
            Route::get('attendance/qr/{schedule}', [AttendanceController::class, 'showQr'])->name('center.attendance.qr');
            Route::get('attendance/qr/{schedule}/live-status', [AttendanceController::class, 'qrLiveStatus'])->name('center.attendance.qr.live-status');
            Route::post('attendance/bulk-absent/{schedule}', [AttendanceController::class, 'bulkAbsent'])->name('center.attendance.bulkAbsent');
            Route::post('attendance/offline-sync', [AttendanceController::class, 'offlineSync'])
                ->middleware('feature:offline_attendance')
                ->name('center.attendance.offlineSync');
        });

        // General Settings
        Route::middleware(['can:manage settings'])->group(function () {
            Route::get('settings', [SettingsController::class, 'index'])->name('center.settings.index');
            Route::post('settings', [SettingsController::class, 'update'])->name('center.settings.update');
            Route::post('settings/academic', [SettingsController::class, 'updateAcademic'])->name('center.settings.update-academic');
            Route::post('settings/academic/template', [SettingsController::class, 'applyAcademicTemplate'])->name('center.settings.apply-template');
            Route::post('settings/email-templates/reset', [SettingsController::class, 'resetEmailTemplates'])->name('center.settings.reset-email-templates');
            Route::post('settings/reminders', [SettingsController::class, 'updateReminders'])->name('center.settings.update-reminders');
            Route::get('activity-logs', [\Modules\Center\Http\Controllers\ActivityLogController::class, 'index'])->name('center.activity-logs.index');
        });

        // GDPR Routes (Accessible by authenticated users, primarily students)
        Route::get('/gdpr/export', [Modules\Center\Http\Controllers\GdprController::class, 'export'])->name('gdpr.export');
        Route::post('/gdpr/delete', [Modules\Center\Http\Controllers\GdprController::class, 'delete'])->name('gdpr.delete');

        // Classroom Management
        Route::middleware(['can:manage schedule'])->group(function () {
            Route::resource('classrooms', ClassroomController::class)->names('center.classrooms');
            Route::resource('inventory', AssetController::class)->names('center.assets')->parameters(['inventory' => 'asset']);
        });

        // Bookings Management
        Route::middleware(['can:manage schedule'])->group(function () {
            Route::post('bookings', [BookingController::class, 'store'])->name('center.bookings.store');
            Route::patch('bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('center.bookings.updateStatus');
            Route::delete('bookings/{booking}', [BookingController::class, 'destroy'])->name('center.bookings.destroy');
        });

        // Notifications
        Route::get('notifications', [NotificationController::class, 'index'])->name('center.notifications.index');
        Route::get('notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('center.notifications.read');
        Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('center.notifications.readAll');

        // Bug Reports (Beta Feedback)
        Route::post('bug-report', [\Modules\Center\Http\Controllers\BugReportController::class, 'store'])
            ->middleware('throttle:5,10')
            ->name('center.bug-report.store');

        // User Management
        Route::middleware(['can:manage users'])->group(function () {
            Route::resource('users', \Modules\Center\Http\Controllers\UserController::class)->names('center.users');
        });

        // Role Management
        Route::middleware(['feature:advanced_roles'])->group(function () {
            Route::resource('roles', \Modules\Center\Http\Controllers\RoleController::class)
                ->except(['show'])
                ->names('center.roles');
        });

        // Branch Management
        Route::middleware(['feature:multi_branch'])->group(function () {
            Route::resource('branches', \Modules\Center\Http\Controllers\BranchController::class)->names('center.branches');
        });

        // Schedule Management
        Route::middleware(['feature:daily_schedules'])->group(function () {
            Route::resource('schedules', ScheduleController::class)->names('center.schedules');
        });

        // Schedule Conflict API (for AJAX real-time validation)
        Route::post('api/schedules/check-conflict', [\Modules\Center\Http\Controllers\ScheduleApiController::class, 'checkConflict'])->name('center.schedules.check-conflict');
        Route::post('api/schedules/available-slots', [\Modules\Center\Http\Controllers\ScheduleApiController::class, 'getAvailableSlots'])->name('center.schedules.available-slots');
    });

    // Debug routes removed for security
};

// Register routes based on tenancy mode
$mode = config('app.tenancy_mode', 'subdomain');

if ($mode === 'path') {
    // Path-based tenancy: domain.com/c/{tenant}/...
    Route::prefix('c/{tenant}')
        ->middleware([\App\Http\Middleware\IdentifyTenant::class])
        ->group($tenantRoutes);
}

// Register subdomain routes for both 'subdomain' AND 'path' modes (hybrid support)
if ($mode === 'path' || $mode === 'subdomain') {
    $domain = config('app.tenant_domain');
    $appUrlHost = parse_url(config('app.url'), PHP_URL_HOST);

    // Fallback to APP_URL host if tenant_domain is localhost but we are on a real domain (production)
    if (($domain === 'localhost' || empty($domain)) && $appUrlHost && $appUrlHost !== 'localhost') {
        $domain = $appUrlHost;
    }

    $domains = array_unique(array_filter([
        $domain == 'localhost' ? '{tenant}.localhost' : '{tenant}.'.$domain,
        '{tenant}.localhost',
        '{tenant}.taalimu.com'
    ]));

    foreach ($domains as $d) {
        Route::domain($d)->group($tenantRoutes);
    }
}
