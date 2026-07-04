                <!-- Tab: Financial Records -->
                <div class="tab-pane fade" id="pills-sales">
                    <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <h4 class="fw-bold mb-0">{{ __('center::students.profile.financial.title') }}</h4>
                            <div class="d-flex gap-2">
                                <form action="{{ route('center.students.remind-debt', $student->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success rounded-pill px-4 fw-bold shadow-sm" onclick="return confirm('{{ __('center::students.whatsapp_reminder_confirm') }}');">
                                        <i class="fab fa-whatsapp me-2"></i>{{ __('center::students.send_reminder') }}
                                    </button>
                                </form>
                                <a href="{{ route('center.students.statement', $student->id) }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                    <i class="fas fa-file-invoice-dollar me-2"></i>{{ __('center::students.student_ledger') }}
                                </a>
                            </div>
                        </div>

                        <!-- Financial Summary Cards -->
                        <div class="row g-4 mb-5">
                            <div class="col-md-4">
                                <div class="bg-primary bg-opacity-10 rounded-4 p-4 text-center border border-primary border-opacity-10 h-100">
                                    <div class="text-primary small fw-bold mb-2 text-uppercase">{{ __('center::students.profile.financial.total') }}</div>
                                    <div class="h3 fw-bold text-dark mb-0">{{ number_format($sales->sum('total_amount'), 2) }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-success bg-opacity-10 rounded-4 p-4 text-center border border-success border-opacity-10 h-100">
                                    <div class="text-success small fw-bold mb-2 text-uppercase">{{ __('center::students.profile.financial.paid') }}</div>
                                    <div class="h3 fw-bold text-dark mb-0">{{ number_format($sales->sum('paid_amount'), 2) }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                @php $debt = $sales->sum('total_amount') - $sales->sum('paid_amount'); @endphp
                                <div class="{{ $debt > 0 ? 'bg-danger bg-opacity-10 border-danger' : 'bg-light border-secondary' }} rounded-4 p-4 text-center border border-opacity-10 h-100">
                                    <div class="{{ $debt > 0 ? 'text-danger' : 'text-muted' }} small fw-bold mb-2 text-uppercase">{{ __('center::students.profile.financial.remaining') }}</div>
                                    <div class="h3 fw-bold {{ $debt > 0 ? 'text-danger' : 'text-dark' }} mb-0">{{ number_format($debt, 2) }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive mb-0">
                            <table class="table table-hover align-middle border-top mb-0">
                                <thead>
                                    <tr class="text-muted small">
                                        <th class="px-3 py-3">{{ __('center::students.profile.financial.invoice_id') }}</th>
                                        <th>{{ __('center::students.profile.financial.total') }}</th>
                                        <th>{{ __('center::students.profile.financial.paid') }}</th>
                                        <th>{{ __('center::students.profile.financial.remaining') }}</th>
                                        <th>{{ __('center::students.profile.financial.date') }}</th>
                                        <th class="text-center">{{ __('center::students.profile.financial.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sales as $sale)
                                        <tr class="cursor-pointer hover-bg-light transition-all" onclick="window.location='{{ route('center.sales.show', $sale->id) }}'">
                                            <td class="px-3 fw-bold"><span class="text-primary">#{{ $sale->id }}</span></td>
                                            <td class="fw-bold text-dark">{{ number_format($sale->total_amount, 2) }}</td>
                                            <td class="text-success fw-bold">{{ number_format($sale->paid_amount, 2) }}</td>
                                            <td class="text-danger fw-bold">{{ number_format($sale->total_amount - $sale->paid_amount, 2) }}</td>
                                            <td class="small text-muted">{{ $sale->created_at->format('Y-m-d') }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $sale->status == 'paid' ? 'success' : ($sale->status == 'partial' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $sale->status == 'paid' ? 'success' : ($sale->status == 'partial' ? 'warning' : 'danger') }} rounded-pill px-3 fw-bold">
                                                    {{ __('center::sales.status_' . ($sale->status == 'pending' ? 'unpaid' : $sale->status)) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center py-5 text-muted">{{ __('center::students.profile.financial.no_records') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Payments History Section -->
                        <div class="mt-0 pt-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm align-middle">
                                    <thead class="bg-light">
                                        <tr class="text-muted small">
                                            <th class="px-3 py-2">{{ __('center::students.profile.financial.date') ?? 'التاريخ' }}</th>
                                            <th>{{ __('center::students.profile.financial.invoice_id') ?? 'رقم الفاتورة' }}</th>
                                            <th>{{ __('center::students.profile.financial.method') ?? 'طريقة الدفع' }}</th>
                                            <th class="text-center">{{ __('center::students.profile.financial.total') ?? 'المبلغ' }}</th>
                                            <th>{{ __('center::students.profile.financial.received_by') ?? 'استلام بواسطة' }}</th>
                                            <th class="text-end px-3">{{ __('center::students.profile.financial.receipt') ?? 'الإيصال' }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($payments as $payment)
                                            <tr class="small border-bottom-0">
                                                <td class="px-3">{{ ($payment->paid_at ?? $payment->created_at)->format('Y-m-d') }}</td>
                                                <td><span class="text-primary fw-bold">#{{ $payment->sale_id }}</span></td>
                                                <td><span class="badge bg-light text-dark fw-normal">{{ $payment->payment_method }}</span></td>
                                                <td class="text-center fw-bold text-success">{{ number_format($payment->amount, 2) }}</td>
                                                <td class="text-muted">{{ $payment->receiver->name ?? '---' }}</td>
                                                <td class="text-end px-3">
                                                    <a href="{{ route('center.payments.receipt', $payment->id) }}" class="btn btn-sm btn-outline-success border-0 py-0">
                                                        <i class="fas fa-file-download me-1"></i>{{ __('center::students.profile.financial.download_receipt') ?? 'تحميل الإيصال' }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" class="text-center py-4 text-muted small">{{ __('center::students.profile.financial.no_payments') ?? 'لا يوجد سجل مدفوعات حالياً' }}</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
