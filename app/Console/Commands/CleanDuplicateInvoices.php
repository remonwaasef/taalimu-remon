<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Enrollment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanDuplicateInvoices extends Command
{
    protected $signature = 'finance:clean-duplicates {student_id?}';
    protected $description = 'Removes duplicate invoices for a student where multiple sales exist for the same course enrollment';

    public function handle()
    {
        $studentId = $this->argument('student_id');

        if ($studentId) {
            $this->cleanForStudent($studentId);
        } else {
            $this->info("Scanning all students for duplicates...");
            $students = Student::has('sales', '>', 1)->get();
            foreach ($students as $student) {
                $this->cleanForStudent($student->id);
            }
        }

        return 0;
    }

    private function cleanForStudent($id)
    {
        $student = Student::find($id);
        if (!$student) {
            $this->error("Student $id not found");
            return;
        }

        $this->info("Checking Student: {$student->name} (ID: {$student->id})");

        $sales = Sale::where('student_id', $student->id)
            ->with('items')
            ->orderBy('created_at', 'asc')
            ->get();

        $seenCourses = [];
        $deletedCount = 0;

        foreach ($sales as $sale) {
            $isDuplicate = false;
            foreach ($sale->items as $item) {
                if ($item->item_type === \App\Models\Course::class) {
                    if (isset($seenCourses[$item->item_id])) {
                        $isDuplicate = true;
                        break;
                    }
                }
            }

            if ($isDuplicate) {
                $this->warn("  → Deleting Duplicate Sale #{$sale->id} (Created at: {$sale->created_at})");
                
                DB::transaction(function() use ($sale) {
                    // Delete items first
                    $sale->items()->delete();
                    // Delete payments if unpaid/auto-created
                    if ($sale->paid_amount == 0) {
                        $sale->payments()->delete();
                    }
                    $sale->delete();
                });
                
                $deletedCount++;
            } else {
                foreach ($sale->items as $item) {
                    if ($item->item_type === \App\Models\Course::class) {
                        $seenCourses[$item->item_id] = $sale->id;
                    }
                }
            }
        }

        if ($deletedCount > 0) {
            $this->info("  ✓ Deleted $deletedCount duplicate sales for {$student->name}");
        } else {
            $this->line("  - No duplicates found.");
        }
    }
}
