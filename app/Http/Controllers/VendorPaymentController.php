<?php

namespace App\Http\Controllers;

use App\Http\Requests\VendorPayment\StoreVendorPaymentRequest;
use App\Models\Account;
use App\Models\VendorBill;
use App\Models\VendorPayment;
use App\Services\JournalEntryService;
use App\Services\PurchaseRequestActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorPaymentController extends Controller
{
    public function __construct(
        private PurchaseRequestActivityService $activity,
        private JournalEntryService $journalEntryService
    ) {}

    public function index()
    {
        $unpaidBills = VendorBill::with([
            'vendor',
            'vendorPayments',
            'creditApplications',
        ])
            ->where('status', '!=', 'paid')
            ->latest()
            ->get();

        $vendorPayments = VendorPayment::with([
            'vendorBill',
            'vendorBill.vendor',
        ])
            ->latest()
            ->paginate(10);

        return view(
            'vendor_payments.index',
            compact(
                'vendorPayments',
                'unpaidBills'
            )
        );
    }

    public function store(StoreVendorPaymentRequest $request, VendorBill $vendorBill)
    {
        $validated = $request->validated();

        $dueAmount = (float) $vendorBill->due_amount;

        if ($dueAmount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'This vendor bill has no outstanding amount.',
            ], 422);
        }

        if ((float) $validated['amount'] > $dueAmount) {
            return response()->json([
                'success' => false,
                'message' => 'Payment amount cannot exceed the outstanding amount.',
                'errors' => [
                    'amount' => [
                        'Amount cannot exceed the due amount: '
                            . number_format($dueAmount, 2),
                    ],
                ],
            ], 422);
        }

        try {
            DB::transaction(function () use (
                $request,
                $validated,
                $vendorBill
            ) {
                $bill = VendorBill::query()
                    ->lockForUpdate()
                    ->findOrFail($vendorBill->id);

                $previousStatus = $bill->status;

                $dueAmount = (float) $bill->due_amount;

                if ((float) $validated['amount'] > $dueAmount) {
                    throw new \RuntimeException(
                        'Payment amount cannot exceed the outstanding amount of '
                            . number_format($dueAmount, 2)
                            . '.'
                    );
                }

                $path = null;

                if ($request->hasFile('payment_proof')) {
                    $path = $request
                        ->file('payment_proof')
                        ->store(
                            'images/payment_proof',
                            'public'
                        );
                }

                $payment = $bill->vendorPayments()->create([
                    'transaction_id' => $validated['transaction_id'] ?? null,
                    'amount' => $validated['amount'],
                    'payment_method' => $validated['payment_method'],
                    'payment_date' => $validated['payment_date']
                        ?? now()->toDateString(),
                    'payment_proof' => $path,
                    'status' => 'successful',
                    'references' => $validated['references'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                ]);

                /*
                 * Recalculate bill status after payment.
                 */
                $bill->refresh();

                $remainingDue = (float) $bill->due_amount;

                if ($remainingDue <= 0) {
                    $newStatus = 'paid';
                } else {
                    $settledAmount =
                        (float) $bill->paid_amount
                        + (float) $bill->credited_amount;

                    $newStatus = $settledAmount > 0
                        ? 'partially_paid'
                        : 'unpaid';
                }

                if ($bill->status !== $newStatus) {
                    $bill->update([
                        'status' => $newStatus,
                    ]);
                }



                // Journal Entry
                $bankAccount = Account::where('code', '1200')->firstOrFail();
                $accountsPayable = Account::where('code', '2100')->firstOrFail();

                $paymentAmount = round((float) $payment->amount, 2);

                $this->journalEntryService->post(
                    [
                        'entry_date' => $payment->payment_date,
                        'description' => "Vendor payment for Bill {$bill->bill_number}.",
                        // 'reference' => $payment->transaction_id ?? $bill->bill_number,
                    ],
                    [
                        [
                            'account_id' => $accountsPayable->id,
                            'debit' => $paymentAmount,
                            'credit' => 0,
                            'description' => "Payment against Vendor Bill {$bill->bill_number}.",
                        ],
                        [
                            'account_id' => $bankAccount->id,
                            'debit' => 0,
                            'credit' => $paymentAmount,
                            'description' => "Bank payment for Vendor Bill {$bill->bill_number}.",
                        ],
                    ]
                );

                /*
                 * Payment activity.
                 */
                $this->activity->log(
                    $bill->purchaseOrder->purchaseRequest,
                    Auth::user(),
                    $bill->vendor,
                    'Vendor payment recorded',
                    "Payment of Rs. {$payment->amount} was recorded against Vendor Bill {$bill->bill_number}.",
                    $payment
                );

                /*
                 * Bill status activity.
                 *
                 * Only log when the payment actually changes
                 * the financial status of the bill.
                 */
                if ($previousStatus !== $newStatus) {

                    if ($newStatus === 'paid') {
                        $this->activity->log(
                            $bill->purchaseOrder->purchaseRequest,
                            Auth::user(),
                            $bill->vendor,
                            'Vendor bill paid',
                            "Vendor Bill {$bill->bill_number} was fully paid.",
                            $bill
                        );
                    } elseif ($newStatus === 'partially_paid') {
                        $this->activity->log(
                            $bill->purchaseOrder->purchaseRequest,
                            Auth::user(),
                            $bill->vendor,
                            'Vendor bill partially paid',
                            "Vendor Bill {$bill->bill_number} was partially paid.",
                            $bill
                        );
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Payment recorded successfully.',
            ], 201);
        } catch (\RuntimeException $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Unable to make payment.',
            ], 500);
        }
    }

    public function show(VendorPayment $vendorPayment)
    {
        //
    }

    public function edit(VendorPayment $vendorPayment)
    {
        //
    }

    public function update(Request $request, VendorPayment $vendorPayment)
    {
        //
    }

    public function destroy(VendorPayment $vendorPayment)
    {
        //
    }
}
