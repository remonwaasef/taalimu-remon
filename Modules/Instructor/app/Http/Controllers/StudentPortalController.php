<?php

namespace Modules\Instructor\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Modules\Center\Models\Attendance;
use App\Models\Sale;
use Illuminate\Http\Request;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class StudentPortalController extends Controller
{
    /**
     * Show the student portal
     */
    public function index($identifier)
    {
        $user = User::where('qr_identifier', $identifier)->firstOrFail();
        $student = $user->student;

        if (!$student) {
            abort(404, 'البيانات غير مكتملة لهذا الطالب.');
        }

        // Get latest 10 attendances
        $attendances = Attendance::where('student_id', $student->id)
            ->with(['course', 'schedule'])
            ->latest('session_date')
            ->take(10)
            ->get();

        // Get latest 10 payments
        $sales = Sale::where('student_id', $student->id)
            ->latest()
            ->take(10)
            ->get();

        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_MARKUP_SVG,
            'eccLevel'   => QRCode::ECC_L,
            'addQuietzone' => true,
        ]);
        
        $qrCode = (new QRCode($options))->render($user->qr_identifier);

        return view('instructor::student_portal', compact('student', 'user', 'attendances', 'sales', 'qrCode'));
    }
}
