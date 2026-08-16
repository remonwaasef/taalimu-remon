<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Center\StoreInstructorRequest;
use App\Http\Requests\Center\UpdateInstructorRequest;
use App\Models\Instructor;
use App\Services\PayoutService;
use App\Traits\HandlesFileUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    use HandlesFileUploads;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Instructor::class);
        
        $activeCount = Instructor::where('status', 'active')->count();

        $query = Instructor::query()->withCount('courses');

        if ($request->has('search')) {
            $search = \App\Helpers\QueryHelper::escapeLike($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('specialization', 'like', '%'.$search.'%');
            });
        }

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
    public function store(StoreInstructorRequest $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $this->authorize('create', Instructor::class);

        if (! app('tenant')->hasFeature('max_instructors')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => __('center::messages.msg_048')], 422);
            }
            return redirect()->back()->with('error', __('center::messages.msg_048'));
        }

        try {
            $imagePath = $this->handleFileUpload($request, 'image', null, 'instructors');

            $tenant = app('tenant');
            $plainPassword = \Illuminate\Support\Str::random(12);
            $email = $request->email ?: 'instructor_'.time().'_'.rand(100, 999).'@'.$tenant->domain;

            $user = \App\Models\User::create([
                'tenant_id' => $tenant->id,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $email,
                'password' => \Illuminate\Support\Facades\Hash::make($plainPassword),
                'role' => 'instructor',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ]);

            $instructor = Instructor::create([
                'tenant_id' => $tenant->id,
                'user_id' => $user->id,
                'name' => $request->name,
                'email' => $user->email,
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

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('center::messages.msg_049'),
                    'instructor' => [
                        'id' => $instructor->id,
                        'name' => $instructor->name,
                    ],
                ]);
            }

            if ($request->email) {
                try {
                    \Illuminate\Support\Facades\Mail::to($user->email)->queue(
                        new \App\Mail\WelcomeTeacherMail(
                            $instructor,
                            $plainPassword,
                            $tenant->name ?? 'المنصة',
                            url('/login')
                        )
                    );
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to send welcome email to instructor: '.$e->getMessage());
                }
            }

            // Notify Admins
            $admins = \App\Models\User::where('tenant_id', app('tenant')->id)
                ->whereIn('role', ['admin', 'center_admin'])
                ->get();

            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\GeneralNotification(
                'instructor_registered', // Translation key
                __('center::instructors.new_instructor_registered', ['name' => $request->name]),
                route('center.instructors.index'), // Link to instructors list
                'fas fa-chalkboard-teacher',
                auth()->user()->name // Created By
            ));

            // Smart Onboarding Routing: If this is the first instructor, guide them to create a course
            $instructorCount = Instructor::where('tenant_id', app('tenant')->id)->count();
            if ($instructorCount === 1) {
                return redirect()->route('center.courses.create')->with('success', __('center::messages.first_instructor_onboarding'));
            }

            return redirect()->route('center.instructors.index')->with('success', __('center::messages.msg_049'));
        } catch (\Exception $e) {
            \Log::error('Instructor creation failed: '.$e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => __('center::messages.error_unexpected')], 500);
            }

            return redirect()->back()->withInput()->with('error', __('center::messages.error_unexpected'));
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $instructor = Instructor::where('tenant_id', app('tenant')->id)->withCount('courses')->findOrFail($id);
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
        $instructor = Instructor::where('tenant_id', app('tenant')->id)->findOrFail($id);
        $this->authorize('update', $instructor);

        return view('center::instructors.edit', compact('instructor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstructorRequest $request, $id): RedirectResponse
    {
        $instructor = Instructor::where('tenant_id', app('tenant')->id)->findOrFail($id);
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

        if ($instructor->user_id) {
            \App\Models\User::where('id', $instructor->user_id)->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email ?: $instructor->email,
            ]);
        }

        return redirect()->route('center.instructors.index')->with('success', __('center::messages.msg_050'));
    }

    /**
     * Toggle instructor status (Active/Inactive).
     */
    public function toggleStatus($id)
    {
        $instructor = Instructor::where('tenant_id', app('tenant')->id)->findOrFail($id);
        $this->authorize('update', $instructor);

        $instructor->update(['status' => $instructor->status === 'active' ? 'inactive' : 'active']);

        return back()->with('success', __('center::messages.msg_050'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $instructor = Instructor::where('tenant_id', app('tenant')->id)->findOrFail($id);
        $this->authorize('delete', $instructor);

        $this->deleteFile($instructor->image, 'public');

        $instructor->delete();

        return redirect()->route('center.instructors.index')->with('success', __('center::messages.msg_051'));
    }

    /**
     * Process a payout for the instructor.
     */
    public function payout(Request $request, $id, PayoutService $payoutService)
    {
        $instructor = Instructor::where('tenant_id', app('tenant')->id)->findOrFail($id);
        $this->authorize('update', $instructor); // Using update perm for financial settlement

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:'.($instructor->total_earned + 0.01),
            'payment_method' => 'required|in:cash,bank_transfer,online,other',
            'payout_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        try {
            $payoutService->processPayout($instructor, $validated);

            return redirect()->back()->with('success', __('center::instructors.payout_success'));
        } catch (\Exception $e) {
            \Log::error('Instructor payout failed: '.$e->getMessage());

            return redirect()->back()->with('error', __('center::instructors.payout_error', ['message' => '']));
        }
    }

    /**
     * View instructor financial statement (Ledger).
     */
    public function statement($id)
    {
        $instructor = Instructor::where('tenant_id', app('tenant')->id)->findOrFail($id);
        $this->authorize('view', $instructor);

        // Fetch recent commissions (Credits) - Limited to prevent memory exhaustion
        $commissions = $instructor->commissions()
            ->with(['sale.student', 'saleItem.item'])
            ->latest()
            ->limit(500)
            ->get()
            ->map(function ($c) {
                return [
                    'date' => $c->created_at,
                    'type' => 'commission',
                    'amount' => $c->amount,
                    'description' => __('center::instructors.commission_sales', [
                        'student' => $c->sale->student->name ?? __('center::instructors.table_student'),
                        'invoice' => $c->sale_id,
                    ]),
                    'is_credit' => true,
                    'status' => $c->status,
                ];
            });

        // Fetch recent payouts (Debits) - Limited to prevent memory exhaustion
        $payouts = $instructor->payouts()
            ->with('processor')
            ->latest()
            ->limit(500)
            ->get()
            ->map(function ($p) {
                return [
                    'date' => $p->payout_date instanceof \Illuminate\Support\Carbon ? $p->payout_date : \Carbon\Carbon::parse($p->payout_date),
                    'type' => 'payout',
                    'amount' => $p->amount,
                    'description' => __('center::instructors.payout').': '.(__('center::instructors.'.$p->payment_method) ?? $p->payment_method).($p->notes ? ' - '.$p->notes : ''),
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
