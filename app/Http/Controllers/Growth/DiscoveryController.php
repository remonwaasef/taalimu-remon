<?php

namespace App\Http\Controllers\Growth;

use App\Http\Controllers\Controller;
use App\Services\DiscoveryService;

class DiscoveryController extends Controller
{
    public function __construct(protected DiscoveryService $discoveryService) {}

    public function teachers()
    {
        $filters = request()->only(['subject', 'search', 'page']);
        $results = $this->discoveryService->searchTeachers($filters);

        return view('growth.discovery.teachers', compact('results', 'filters'));
    }

    public function centers()
    {
        $filters = request()->only(['search', 'page']);
        $results = $this->discoveryService->searchCenters($filters);

        return view('growth.discovery.centers', compact('results', 'filters'));
    }
}
