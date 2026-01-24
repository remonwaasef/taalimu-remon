<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\GdprService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GdprController extends Controller
{
    protected $gdprService;

    public function __construct(GdprService $gdprService)
    {
        $this->gdprService = $gdprService;
    }

    /**
     * Download student data as JSON.
     */
    public function export(Request $request)
    {
        $user = auth()->user();
        
        // Ensure only students can export their data via this route
        if (!$user->hasRole('student')) {
            return redirect()->back()->with('error', 'Only students can export data.');
        }

        try {
            $data = $this->gdprService->exportStudentData($user);
            
            $fileName = 'my_data_' . $user->id . '_' . now()->format('Y_m_d') . '.json';
            
            return response()->streamDownload(function () use ($data) {
                echo json_encode($data, JSON_PRETTY_PRINT);
            }, $fileName);

        } catch (\Exception $e) {
            Log::error('GDPR Export Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to generate data export. Please try again.');
        }
    }

    /**
     * Permanently delete account.
     */
    public function delete(Request $request)
    {
        $user = auth()->user();

        // 1. Validate Password for safety
        $request->validate([
            'password' => ['required', 'current_password'],
            'confirm_delete' => ['required', 'accepted']
        ]);

        if (!$user->hasRole('student')) {
            return redirect()->back()->with('error', 'Only students can delete their account.');
        }

        try {
            $this->gdprService->deleteStudentAccount($user);
            
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with('success', 'Your account has been permanently deleted.');

        } catch (\Exception $e) {
            Log::error('GDPR Deletion Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete account. Please contact support.');
        }
    }
}
