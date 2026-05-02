<?php $reshaper = app('App\Services\ArabicReshaper'); ?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            background-color: #fff;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            border: 2px solid #3A0CA3;
            padding: 20px;
            border-radius: 10px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #3A0CA3;
            margin: 0 0 5px 0;
            font-size: 20px;
        }
        .header p {
            margin: 0;
            font-size: 14px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            vertical-align: top;
            font-size: 12px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            text-align: right;
            padding: 8px;
            border-bottom: 1px solid #eee;
            font-size: 12px;
        }
        .table th {
            background-color: #f8f9fa;
            color: #3A0CA3;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .stamp {
            border: 2px solid #28a745;
            color: #28a745;
            display: inline-block;
            padding: 3px 10px;
            transform: rotate(-15deg);
            font-weight: bold;
            margin-top: 10px;
            border-radius: 5px;
            font-size: 14px;
        }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?php echo e($tenant->name); ?></h1>
            <p><?php echo e($reshaper->reshape(__('center::sales.receipt_title'))); ?></p>
        </div>

        <table class="info-table">
            <tr>
                <td class="text-right">
                    <strong><?php echo e($reshaper->reshape(__('center::sales.receipt_no'))); ?></strong> #<?php echo e($payment->id); ?><br>
                    <strong><?php echo e($reshaper->reshape(__('center::sales.payment_date'))); ?></strong> <?php echo e($payment->paid_at ? $payment->paid_at->format('Y/m/d') : $payment->created_at->format('Y/m/d')); ?>

                </td>
                <td class="text-left">
                    <strong><?php echo e($reshaper->reshape(__('center::sales.invoice_no'))); ?></strong> #<?php echo e($payment->sale_id); ?>

                </td>
            </tr>
        </table>

        <div style="margin-bottom: 15px; font-size: 13px;">
            <strong><?php echo e($reshaper->reshape(__('center::sales.received_from'))); ?></strong> <?php echo e($payment->sale->student->name); ?><br>
            <strong><?php echo e($reshaper->reshape(__('center::sales.paid_amount_label_alt'))); ?></strong> <?php echo e(number_format($payment->amount, 2)); ?> <?php echo e($reshaper->reshape(__('center::sales.currency_label', ['currency' => get_currency_symbol()]))); ?>

        </div>

        <table class="table">
            <thead>
                <tr>
                    <th><?php echo e($reshaper->reshape(__('center::sales.description'))); ?></th>
                    <th style="width: 100px;"><?php echo e($reshaper->reshape(__('center::sales.amount'))); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo e($reshaper->reshape(__('center::sales.payment_for'))); ?> 
                        <?php echo e($payment->sale->items->first()->reshaped_title ?? $reshaper->reshape(__('center::sales.educational_course'))); ?>

                        <?php if($payment->sale->items->count() > 1): ?>
                            <?php echo e($reshaper->reshape(__('center::sales.other_items'))); ?>

                        <?php endif; ?>
                    </td>
                    <td><?php echo e(number_format($payment->amount, 2)); ?> <?php echo e($reshaper->reshape(__('center::sales.currency_label', ['currency' => get_currency_symbol()]))); ?></td>
                </tr>
            </tbody>
        </table>

        <table class="info-table">
            <tr>
                <td class="text-right" style="width: 60%;">
                    <strong><?php echo e($reshaper->reshape(__('center::sales.payment_method_label'))); ?></strong> 
                    <?php
                        $method = $payment->payment_method == 'cash' ? __('center::sales.cash') : ($payment->payment_method == 'card' ? __('center::sales.card') : __('center::sales.other'));
                    ?>
                    <?php echo e($reshaper->reshape($method)); ?><br>
                    <strong><?php echo e($reshaper->reshape(__('center::sales.remaining_amount_label'))); ?></strong> <?php echo e(number_format($payment->sale->total_amount - $payment->sale->paid_amount, 2)); ?> <?php echo e($reshaper->reshape(__('center::sales.currency_label', ['currency' => get_currency_symbol()]))); ?>

                    <br>
                    <span style="font-size: 10px; color: #666;">
                        <strong><?php echo e($reshaper->reshape(__('center::sales.invoice_total'))); ?></strong> <?php echo e(number_format($payment->sale->total_amount, 2)); ?>

                        <?php if($payment->sale->discount_amount > 0): ?> | <strong><?php echo e($reshaper->reshape(__('center::sales.discount_label'))); ?></strong> <?php echo e(number_format($payment->sale->discount_amount, 2)); ?> <?php endif; ?>
                        <?php if($payment->sale->tax_amount > 0): ?> | <strong><?php echo e($reshaper->reshape(__('center::sales.tax_label'))); ?></strong> <?php echo e(number_format($payment->sale->tax_amount, 2)); ?> <?php endif; ?>
                    </span>
                </td>
                <td class="text-center" style="width: 40%;">
                    <p style="margin-bottom: 5px;"><?php echo e($reshaper->reshape(__('center::sales.center_stamp'))); ?></p>
                    <div class="stamp"><?php echo e($reshaper->reshape(__('center::sales.paid_stamp'))); ?></div>
                </td>
            </tr>
        </table>

        <div class="footer">
            <?php echo e($tenant->address ?? ''); ?> | <?php echo e($tenant->phone ?? ''); ?><br>
            <?php echo e($reshaper->reshape(__('center::sales.thank_you'))); ?>

        </div>
    </div>
</body>
</html>
<?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\sales\receipt.blade.php ENDPATH**/ ?>