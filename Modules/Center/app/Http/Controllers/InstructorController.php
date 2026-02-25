<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\HandlesFileUploads;
use App\Http\Requests\Center\StoreInstructorRequest;
use App\Http\Requests\Center\UpdateInstructorRequest;
use App\Services\PayoutService;

class InstructorController extends Controller
{
    use HandlesFileUploads;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Instructor::class);
        $query = Instructor::query()->withCount('courses');

        if ($request->has('search')) {
            $search = \App\Helpers\QueryHelper::escapeLike($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('specialization', 'like', '%' . $search . '%');
            });
        }

        $activeCount = (clone $query)->where('status', 'active')->count();
        $instructors = $query->latest()->paginate(10);

        return view('center::instructors.index', compact('instructors', 'activeCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Instructor::class);
        return view('center::instructors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInstructorRequest $request): RedirectResponse
    {
        $this->authorize('create', Instructor::class);

        if (!app('tenant')->hasFeature('max_instructors')) {
            return redirect()->back()->with('error', __('center::messages.msg_048'));
        }

        $imagePath = $this->handleFileUpload($request, 'image', null, 'instructors');

        $instructor = Instructor::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'specialization' => $request->specialization,
            'status' => $request->status ?? 'active',
            'commission_rate' => $request->commission_rate,
            'commission_type' => $request->commission_type,
            'national_id' => $request->national_id,
            'gender' => $request->gender,
            'hiring_date' => $request->hiring_date,
            'bio' => $request->bio,
            'image' => $imagePath ?? null,
        ]);

        // Notify Admins
        $admins = \App\Models\User::where('tenant_id', app('tenant')->id)
            ->whereIn('role', ['admin', 'center_admin'])
            ->get();
            
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\GeneralNotification(
            'instructor_registered', // Translation key
            "تم تسجيل مدرس جديد: {$request->name}",
            route('center.instructors.index'), // Link to instructors list (or show page if available)
            'fas fa-chalkboard-teacher',
            auth()->user()->name // Created By
        ));

        // Smart Onboarding Routing: If this is the first instructor, guide them to create a course
        $instructorCount = Instructor::where('tenant_id', app('tenant')->id)->count();
        if ($instructorCount === 1) {
            return redirect()->route('center.courses.create')->with('success', 'تمت إضافة المدرس بنجاح! 🎉 خطوتك التالية هي إنشاء أول دورة تعليمية لربطها به.');
        }

        return redirect()->route('center.instructors.index')->with('success', __('center::messages.msg_049'));
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $instructor = Instructor::withCount('courses')->findOrFail($id);
        $this->authorize('view', $instructor);

        // Load commission history
        $commissions = $instructor->commissions()
            ->with(['sale.student', 'saleItem'])
            ->latest()
            ->paginate(10);

        return view('center::instructors.show', compact('instructor', 'commissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $instructor = Instructor::findOrFail($id);
        $this->authorize('update', $instructor);
        return view('center::instructors.edit', compact('instructor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstructorRequest $request, $id): RedirectResponse
    {
        $instructor = Instructor::findOrFail($id);
        $this->authorize('update', $instructor);

        $instructor->name = $request->name;
        $instructor->email = $request->email;
        $instructor->phone = $request->phone;
        $instructor->specialization = $request->specialization;
        $instructor->status = $request->status;
        $instructor->commission_rate = $request->commission_rate;
        $instructor->commission_type = $request->commission_type;
        $instructor->national_id = $request->national_id;
        $instructor->gender = $request->gender;
        $instructor->hiring_date = $request->hiring_date;
        $instructor->bio = $request->bio;

        if ($request->hasFile('image')) {
             $instructor->image = $this->handleFileUpload($request, 'image', $instructor->image, 'instructors');
        }

        $instructor->save();

        return redirect()->route('center.instructors.index')->with('success', __('center::messages.msg_050'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $instructor = Instructor::findOrFail($id);
        $this->authorize('delete', $instructor);

        if ($instructor->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($instructor->image);
        }

        $instructor->delete();

        return redirect()->route('center.instructors.index')->with('success', __('center::messages.msg_051'));
    }

    /**
     * Process a payout for the instructor.
     */
    public function payout(Request $request, $id, PayoutService $payoutService)
    {
        $instructor = Instructor::findOrFail($id);
        $this->authorize('update', $instructor); // Using update perm for financial settlement

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . ($instructor->total_earned + 0.01),
            'payment_method' => 'required|in:cash,bank_transfer,online,other',
            'payout_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        try {
            $payoutService->processPayout($instructor, $request->all());
            return redirect()->back()->with('success', 'تم تسجيل عملية الصرف بنجاح وتحديث السجلات المالية.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'خطأ في عملية الصرف: ' . $e->getMessage());
        }
    }

    /**
     * View instructor financial statement (Ledger).
     */
    public function statement($id)
    {
        $instructor = Instructor::findOrFail($id);
        $this->authorize('view', $instructor);

        // Fetch commissions (Credits)
        $commissions = $instructor->commissions()
            ->with(['sale.student', 'saleItem.item'])
            ->get()
            ->map(function ($c) {
                return [
                    'date' => $c->created_at,
                    'type' => 'commission',
                    'amount' => $c->amount,
                    'description' => 'عمولة مبيعات: ' . ($c->sale->student->name ?? 'طالب') . ' - فاتورة #' . $c->sale_id,
                    'is_credit' => true,
                    'status' => $c->status,
                ];
            });

        // Fetch payouts (Debits)
        $payouts = $instructor->payouts()
            ->with('processor')
            ->get()
            ->map(function ($p) {
                return [
                    'date' => $p->payout_date instanceof \Illuminate\Support\Carbon ? $p->payout_date : \Carbon\Carbon::parse($p->payout_date),
                    'type' => 'payout',
                    'amount' => $p->amount,
                    'description' => 'صرف مستحقات: ' . ($p->payment_method ?? 'نقدي') . ($p->notes ? ' - ' . $p->notes : ''),
                    'is_credit' => false,
                    'status' => 'completed',
                ];
            });

        // Merge and sort
        $ledger = $commissions->concat($payouts)->sortBy('date')->values()->all();

        // Calculate running balance
        $balance = 0;
        foreach ($ledger as $key => $transaction) {
            if ($transaction['is_credit']) {
                $balance += $transaction['amount'];
            } else {
                $balance -= $transaction['amount'];
            }
            // Add balance to transaction array
            $ledger[$key]['balance'] = $balance;
        }

        $tenant = app('tenant');
        $ledger = collect($ledger);

        return view('center::instructors.statement', compact('instructor', 'ledger', 'tenant'));
    }
}
