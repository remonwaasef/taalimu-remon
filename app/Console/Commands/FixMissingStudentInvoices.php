<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixMissingStudentInvoices extends Command
{
    protected $signature = 'finance:fix-missing-invoices
                            {--tenant= : Tenant ID to fix (or all if not specified)}
                            {--dry-run : Preview without making changes}';

    protected $description = 'Creates pending sale invoices for students who have enrollments but no sales record';

    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $tenantId = $this->option('tenant');

        if ($isDryRun) {
            $this->warn('DRY RUN - No changes will be made.');
        }

        // Find students with enrollments but no sales
        $query = Student::has('enrollments')
            ->doesntHave('sales')
            ->with('enrollments.course');

        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }

        $students = $query->get();

        if ($students->isEmpty()) {
            $this->info('No students found with missing invoices.');
            return 0;
        }

        $this->info("Found {$students->count()} student(s) with enrollments but no invoice:");

        foreach ($students as $student) {
            $this->line("  → [{$student->tenant_id}] {$student->name} (ID: {$student->id}) - " . $student->enrollments->count() . " enrollment(s)");

            if (!$isDryRun) {
                DB::transaction(function () use ($student) {
                    foreach ($student->enrollments as $enrollment) {
                        $course = $enrollment->course;
                        if (!$course) continue;

                        // Check if a sale already exists for this student+course
                        $exists = Sale::where('tenant_id', $student->tenant_id)
                            ->where('student_id', $student->id)
                            ->whereHas('items', fn($q) => $q->where('item_id', $course->id)->where('item_type', Course::class))
                            ->exists();

                        if ($exists) {
                            $this->line("    ✓ Sale already exists for course: {$course->title}");
                            continue;
                        }

                        $price = $course->price ?? 0;

                        // Create the sale (pending - unpaid)
                        $sale = Sale::create([
                            'tenant_id'      => $student->tenant_id,
                            'student_id'     => $student->id,
                            'subtotal_amount'=> $price,
                            'discount_amount'=> 0,
                            'tax_amount'     => 0,
                            'total_amount'   => $price,
                            'paid_amount'    => 0,
                            'status'         => $price > 0 ? 'pending' : 'paid',
                            'payment_method' => 'cash',
                            'notes'          => 'تم إنشاؤها تلقائياً لتصحيح سجلات التسجيل',
                        ]);

                        // Create the sale item
                        SaleItem::create([
                            'sale_id'   => $sale->id,
                            'item_type' => Course::class,
                            'item_id'   => $course->id,
                            'price'     => $price,
                            'quantity'  => 1,
                        ]);

                        $this->info("    ✓ Created invoice #{$sale->id} for {$student->name} → {$course->title} ({$price})");
                    }
                });
            }
        }

        if ($isDryRun) {
            $this->warn('DRY RUN complete. Run without --dry-run to apply changes.');
        } else {
            $this->info('Done! All missing invoices have been created.');
        }

        return 0;
    }
}
