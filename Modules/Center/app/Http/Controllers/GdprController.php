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
            return redirect()->back()->with('error', __('center::messages.msg_043'));
        }

        try {
            $data = $this->gdprService->exportStudentData($user);
            
            $fileName = 'my_data_' . $user->id . '_' . now()->format('Y_m_d') . '.json';
            
            return response()->json($data, 200, [
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
            ])->setEncodingOptions(JSON_PRETTY_PRINT);

        } catch (\Exception $e) {
            Log::error('GDPR Export Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', __('center::messages.msg_044'));
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
            return redirect()->back()->with('error', __('center::messages.msg_045'));
        }

        try {
            $this->gdprService->deleteStudentAccount($user);
            
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with('success', __('center::messages.msg_046'));

        } catch (\Exception $e) {
            Log::error('GDPR Deletion Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', __('center::messages.msg_047'));
        }
    }
}
