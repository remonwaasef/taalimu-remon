<?php

namespace App\Http\Controllers\Growth;

use App\Http\Controllers\Controller;
use App\Services\DemandForecastService;
use App\Services\InsightService;
use App\Services\OpportunityScoringService;

class GrowthInsightsController extends Controller
{
    public function __construct(
        protected InsightService $insightService,
        protected DemandForecastService $forecastService,
        protected OpportunityScoringService $opportunityService
    ) {}

    public function index()
    {
        $tenant = app('tenant');

        $insights = $this->insightService->getInsights($tenant->id);
        $forecast = $this->forecastService->forecast($tenant->id);
        $opportunities = $this->opportunityService->getOpportunities($tenant->id);

        $seoData = [
            'title' => __('Growth Insights') . ' — ' . $tenant->name,
            'description' => __('AI-powered insights and recommendations for your growth.'),
        ];

        return view('growth.dashboard.insights', compact('insights', 'forecast', 'opportunities', 'seoData'));
    }
}
