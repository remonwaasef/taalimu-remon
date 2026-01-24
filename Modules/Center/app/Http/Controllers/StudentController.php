<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\StudentService;
use App\Queries\StudentQuery;
use App\Http\Requests\Center\StoreStudentRequest;
use App\Http\Requests\Center\UpdateStudentRequest;
use App\DTOs\StudentData;
use App\Traits\HandlesFileUploads;
use Illuminate\Support\Facades\Cache;

class StudentController extends Controller
{
    use HandlesFileUploads;
    
    protected $studentService;
    protected $studentQuery;

    public function __construct(StudentService $studentService, StudentQuery $studentQuery)
    {
        $this->studentService = $studentService;
        $this->studentQuery = $studentQuery;
    }
    public function index(Request $request)
    {
        $this->authorize('viewAny', Student::class);
        
        $query = Student::query();
        $query = $this->studentQuery->apply($query, $request->all());

        $students = $query->with('grade.stage')->latest()->paginate(10);
        
        $stages = \App\Models\Stage::getCached();

        return view('center::students.index', compact('students', 'stages'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Student::class);
        
        $stages = \App\Models\Stage::getCached();
        
        return view('center::students.create', compact('stages'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $this->authorize('create', Student::class);

        if (!app('tenant')->hasFeature('max_students')) {
            return redirect()->back()->with('error', __('center::students.max_limit_reached'));
        }

        $data = $request->validated();

        // Handle Profile Photo Upload using trait
        $data['profile_photo'] = $this->handleFileUpload(
            $request,
            'profile_photo',
            null,
            'students/photos'
        );

        $result = $this->studentService->registerStudent(StudentData::fromArray($data), auth()->user());
        
        // Store info in session to display to the user
        session()->flash('generated_password', $result['generated_password']);
        session()->flash('student_name', $result['student']->name);
        session()->flash('student_phone', $result['student']->phone);
        session()->flash('student_email', $result['student']->email);

        return redirect()->route('center.students.index', ['tenant' => app('tenant')->domain])->with('success', 'تم تسجيل الطالب وإنشاء حساب دخول له بنجاح');
    }


    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $student = Student::where('tenant_id', app('tenant')->id)
            ->with(['grade.stage', 'user', 'tenant'])
            ->findOrFail($id);
            
        $this->authorize('view', $student);
            
        $data = $this->studentService->getProfileData($student);

        return view('center::students.show', array_merge(['student' => $student], $data));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $student = Student::where('tenant_id', app('tenant')->id)->findOrFail($id);
        $this->authorize('update', $student);
        
        $stages = \App\Models\Stage::getCached();
        
        return view('center::students.edit', compact('student', 'stages'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, $id): RedirectResponse
    {
        $student = Student::where('tenant_id', app('tenant')->id)->findOrFail($id);
        $this->authorize('update', $student);

        // Pass user_id to exclude to the request validator
        // $request->merge(['user_id_to_exclude' => $student->user_id]);

        $data = $request->validated();
        
        // Handle Profile Photo Upload using trait
        $data['profile_photo'] = $this->handleFileUpload(
            $request,
            'profile_photo',
            $student->profile_photo,
            'students/photos'
        );

        $this->studentService->updateStudent($student, StudentData::fromArray($data), auth()->user());

        return redirect()->route('center.students.index', ['tenant' => app('tenant')->domain])->with('success', 'تم تحديث بيانات الطالب بنجاح');
    }

    public function resetPassword($id): RedirectResponse
    {
        $student = Student::where('tenant_id', app('tenant')->id)->findOrFail($id);
        $this->authorize('update', $student);

        $newPassword = $this->studentService->resetPassword($student->user);

        return redirect()->back()->with('success', 'تم إعادة تعيين كلمة المرور بنجاح')
            ->with('generated_password', $newPassword)
            ->with('student_name', $student->name);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $student = Student::where('tenant_id', app('tenant')->id)->findOrFail($id);
        $this->authorize('delete', $student);

        // Delete profile photo
        if ($student->profile_photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($student->profile_photo);
        }

        $this->studentService->deleteStudent($student, auth()->user());

        return redirect()->route('center.students.index', ['tenant' => app('tenant')->domain])->with('success', 'تم حذف الطالب بنجاح');
    }

    public function export()
    {
        $this->authorize('viewAny', Student::class);
        
        return response()->streamDownload(function () {
            $students = $this->studentService->getExportData();
            $csvHeader = ['ID', 'Name', 'Email', 'Phone', 'Grade Level', 'School', 'Section', 'Status'];
            $handle = fopen('php://output', 'w');
            
            // Add BOM for Excel compatibility with Arabic
            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            fputcsv($handle, $csvHeader);
            foreach ($students as $row) {
                fputcsv($handle, $row);
            }
            
            fclose($handle);
        }, 'students_export.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
    /**
     * Show the import form.
     */
    public function importForm()
    {
        $this->authorize('create', Student::class);
        return view('center::students.import');
    }

    /**
     * Handle the CSV import.
     */
    public function import(Request $request)
    {
        // Authorization: ensure user can create students
        $this->authorize('create', Student::class);
        
        if (!app('tenant')->hasFeature('max_students')) {
            return redirect()->back()->with('error', 'لقد وصلت للحد الأقصى من الطلاب المسموح به في باقتك.');
        }

        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120', // Up to 5MB
        ]);

        $path = $request->file('file')->store('temp/imports');
        
        \App\Jobs\ImportStudentsJob::dispatch($path, app('tenant')->id, auth()->id());

        return redirect()->route('center.students.index', ['tenant' => app('tenant')->domain])
            ->with('success', 'بدأت عملية الاستيراد في الخلفية. ستتلقى إشعاراً عند اكتمالها.');
    }

    /**
     * Look up a guardian by phone number.
     */
    public function lookupGuardian(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $guardian = \App\Models\Guardian::where('tenant_id', app('tenant')->id)
            ->where('phone', $request->phone)
            ->first();

        if (!$guardian) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found' => true,
            'guardian' => $guardian
        ]);
    }
}
