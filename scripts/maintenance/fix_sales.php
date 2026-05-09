<?php

use App\Models\Sale;
use App\Models\Student;
use App\Models\Payment;

$sales = Sale::doesntHave('items')->where('status', 'paid')->get();
echo "Found " . $sales->count() . " fake sales.\n";

foreach ($sales as $fakeSale) {
    $amount = $fakeSale->paid_amount;
    $student = Student::find($fakeSale->student_id);
    
    if (!$student) continue;

    $unpaidSales = Sale::where('student_id', $student->id)
        ->whereRaw('paid_amount < total_amount')
        ->orderBy('created_at', 'asc')
        ->get();

    foreach ($unpaidSales as $realSale) {
        if ($amount <= 0) break;

        $remaining = $realSale->total_amount - $realSale->paid_amount;
        $pay = min($remaining, $amount);

        $realSale->paid_amount += $pay;

        if ($realSale->paid_amount >= $realSale->total_amount) {
            $realSale->status = 'paid';
        } elseif ($realSale->paid_amount > 0) {
            $realSale->status = 'partial';
        }

        $realSale->save();

        Payment::where('sale_id', $fakeSale->id)->update([
            'sale_id' => $realSale->id,
            'amount' => $pay
        ]);

        $amount -= $pay;
    }

    $fakeSale->delete();
}

echo "Fixed!\n";
