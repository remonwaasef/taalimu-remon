<?php

namespace App\Http\Controllers\Growth;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\DemandRequest;
use App\Models\Enrollment;
use App\Models\PublicProfile;
use App\Models\Waitlist;
use App\Services\GrowthEventService;
use App\Services\GrowthNotificationService;
use App\Services\GrowthScoreService;
use Illuminate\Support\Facades\DB;

class GrowthDashboardController extends Controller
{
    public function __construct(
        protected GrowthEventService $eventService,
        protected GrowthScoreService $scoreService,
        protected GrowthNotificationService $notificationService
    ) {}

    public function index()
    {
        $tenant = app('tenant');
        $user = auth()->user();

        $profile = PublicProfile::where('tenant_id', $tenant->id)->first();

        $profileViews = $profile
            ? $this->eventService->getProfileViewCount($profile)
            : 0;

        $sources = $profile
            ? $this->eventService->getAcquisitionSources($profile)
            : [];

        $demandCount = DemandRequest::where('tenant_id', $tenant->id)->count();
        $newDemandCount = DemandRequest::where('tenant_id', $tenant->id)
            ->where('status', 'new')
            ->count();

        $waitlistCount = Waitlist::where('tenant_id', $tenant->id)
            ->where('status', 'pending')
            ->count();

        $enrollmentCount = Enrollment::where('tenant_id', $tenant->id)->count();

        $publishedCourses = Course::where('tenant_id', $tenant->id)
            ->where('published', true)
            ->count();

        $growthScore = $this->scoreService->calculate($tenant);

        $notifications = $this->notificationService->getRecent($user, $tenant->id, 5);
        $unreadCount = $this->notificationService->getUnreadCount($user, $tenant->id);

        $recentDemand = DemandRequest::where('tenant_id', $tenant->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $seoData = [
            'title' => __('Growth Dashboard') . ' — ' . $tenant->name,
            'description' => __('Track your growth, demand, and acquisition.'),
        ];

        return view('growth.dashboard.index', compact(
            'profile',
            'profileViews',
            'sources',
            'demandCount',
            'newDemandCount',
            'waitlistCount',
            'enrollmentCount',
            'publishedCourses',
            'growthScore',
            'notifications',
            'unreadCount',
            'recentDemand',
            'seoData'
        ));
    }

    public function notifications()
    {
        $tenant = app('tenant');
        $user = auth()->id();

        $notifications = DB::table('teacher_notifications')
            ->where('tenant_id', $tenant->id)
            ->where('user_id', $user)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        return view('growth.dashboard.notifications', compact('notifications'));
    }

    public function markNotificationRead(int $id)
    {
        $tenant = app('tenant');

        DB::table('teacher_notifications')
            ->where('id', $id)
            ->where('tenant_id', $tenant->id)
            ->where('user_id', auth()->id())
            ->update(['read_at' => now()]);

        return back();
    }

    public function markAllNotificationsRead()
    {
        $tenant = app('tenant');
        $user = auth()->user();

        $this->notificationService->markAllAsRead($user, $tenant->id);

        return back();
    }
}
