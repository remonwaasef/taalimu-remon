<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Expense::class);

        $query = Expense::query();

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $expenses = $query->with('creator')->latest('date')->paginate(20);
        $categories = Expense::select('category')->distinct()->pluck('category');

        return view('center::expenses.index', compact('expenses', 'categories'));
    }

    public function create()
    {
        $this->authorize('create', Expense::class);
        return view('center::expenses.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Expense::class);

        $validated = $request->validate([
            'category' => 'nullable|string|max:255',
            'amount' => 'nullable|numeric|min:0',
            'date' => 'nullable|date',
            'description' => 'nullable|string|max:500',
            'payment_method' => 'nullable|string',
            'attachment' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('expenses/attachments', 'public');
        }

        Expense::create($validated);

        return redirect()->route('center.expenses.index')->with('success', 'تم تسجيل المصروف بنجاح');
    }

    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);
        return view('center::expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $this->authorize('update', $expense);

        $validated = $request->validate([
            'category' => 'nullable|string|max:255',
            'amount' => 'nullable|numeric|min:0',
            'date' => 'nullable|date',
            'description' => 'nullable|string|max:500',
            'payment_method' => 'nullable|string',
            'attachment' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('attachment')) {
            if ($expense->attachment) {
                Storage::disk('public')->delete($expense->attachment);
            }
            $validated['attachment'] = $request->file('attachment')->store('expenses/attachments', 'public');
        }

        $expense->update($validated);

        return redirect()->route('center.expenses.index')->with('success', 'تم تحديث المصروف بنجاح');
    }

    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);

        if ($expense->attachment) {
            Storage::disk('public')->delete($expense->attachment);
        }

        $expense->delete();

        return redirect()->route('center.expenses.index')->with('success', 'تم حذف المصروف بنجاح');
    }
}
