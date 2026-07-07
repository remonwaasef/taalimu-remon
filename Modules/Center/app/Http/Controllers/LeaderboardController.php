<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\GamificationService;
use App\Models\PointLog;

class LeaderboardController extends Controller
{
    protected $gamificationService;

    public function __construct(GamificationService $gamificationService)
    {
        $this->gamificationService = $gamificationService;
    }

    public function index()
    {
        $tenantId = app('tenant')->id;
        $leaderboard = $this->gamificationService->getLeaderboard($tenantId, 50);
        $recentLogs = PointLog::with('user')->latest()->take(5)->get();

        return view('center::leaderboard.index', compact('leaderboard', 'recentLogs'));
    }
}
