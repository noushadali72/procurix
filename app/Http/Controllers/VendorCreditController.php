<?php

namespace App\Http\Controllers;

use App\Models\VendorBill;
use App\Models\VendorCredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorCreditController extends Controller
{
    /**
     * Display vendor credits.
     */
    public function index()
    {
        $vendorCredits = VendorCredit::with([
            'vendor',
            'purchaseReturn',
            'applications',
            'refunds',
        ])
            ->latest()
            ->paginate(15);

        return view(
            'vendor_credits.index',
            compact('vendorCredits')
        );
    }


    /**
     * Display a vendor credit.
     */
    // public function show(VendorCredit $vendorCredit)
    // {
    //     $vendorCredit->load([
    //         'vendor.vendorBills.vendorPayments',
    //         'vendor.vendorBills.creditApplications',

    //         'purchaseReturn.goodsReceipt.purchaseOrder',

    //         'vendorBill',

    //         'applications.vendorBill',

    //         'refunds',
    //     ]);

    //     return view(
    //         'vendor_credits.show',
    //         compact('vendorCredit')
    //     );
    // }

    public function show(VendorCredit $vendorCredit)
{
    $vendorCredit->load([
        'vendor',
        'purchaseReturn.goodsReceipt.purchaseOrder',
        'vendorBill',
        'applications.vendorBill',
        'refunds',
    ]);

    $vendorBills = VendorBill::where(
            'vendor_id',
            $vendorCredit->vendor_id
        )
        ->with([
            'vendorPayments',
            'creditApplications',
        ])
        ->latest()
        ->get()
        ->filter(function ($bill) {
            return $bill->due_amount > 0;
        });

    return view(
        'vendor_credits.show',
        compact('vendorCredit', 'vendorBills')
    );
}


    /**
     * Apply available vendor credit against a Vendor Bill.
     */
    public function apply(
        Request $request,
        VendorCredit $vendorCredit
    ) {
        $validated = $request->validate([
            'vendor_bill_id' => [
                'required',
                'exists:vendor_bills,id',
            ],

            'amount' => [
                'required',
                'numeric',
                'gt:0',
            ],
        ]);

        try {

            DB::transaction(function () use (
                $validated,
                $vendorCredit
            ) {

                /*
                 * Lock credit because two requests must not
                 * consume the same available credit.
                 */
                $credit = VendorCredit::query()
                    ->lockForUpdate()
                    ->findOrFail($vendorCredit->id);


                /*
                 * Lock target bill as well.
                 */
                $bill = VendorBill::query()
                    ->lockForUpdate()
                    ->findOrFail(
                        $validated['vendor_bill_id']
                    );


                /*
                 * Credit can only be applied to bills
                 * belonging to the same vendor.
                 */
                if (
                    $bill->vendor_id
                    !== $credit->vendor_id
                ) {
                    throw new \RuntimeException(
                        'This credit cannot be applied to a bill from another vendor.'
                    );
                }


                /*
                 * Calculate available credit from history.
                 */
                $availableCredit =
                    (float) $credit->remaining_amount;


                if ($availableCredit <= 0) {
                    throw new \RuntimeException(
                        'This vendor credit has no remaining balance.'
                    );
                }


                $amount = (float) $validated['amount'];


                if ($amount > $availableCredit) {
                    throw new \RuntimeException(
                        'Amount cannot exceed the available credit of '
                            . number_format(
                                $availableCredit,
                                2
                            )
                            . '.'
                    );
                }


                /*
                 * due_amount now already considers:
                 *
                 * total
                 * - successful payments
                 * - previously applied credits
                 */
                $billDue = (float) $bill->due_amount;


                if ($billDue <= 0) {
                    throw new \RuntimeException(
                        'The selected vendor bill has no outstanding amount.'
                    );
                }


                if ($amount > $billDue) {
                    throw new \RuntimeException(
                        'Credit amount cannot exceed the bill due amount of '
                            . number_format(
                                $billDue,
                                2
                            )
                            . '.'
                    );
                }


                /*
                 * Create application history.
                 *
                 * This record itself is the source of truth.
                 */
                $credit->applications()->create([
                    'vendor_bill_id' => $bill->id,

                    'amount' => $amount,

                    'applied_date' =>
                    now()->toDateString(),
                ]);


                /*
                 * Refresh so accessors see the newly
                 * created application.
                 */
                $credit->refresh();
                $bill->refresh();


                /*
                 * Update credit status.
                 */
                $this->updateCreditStatus($credit);


                /*
                 * Update bill status.
                 *
                 * A bill can become fully settled through:
                 *
                 * payment
                 * +
                 * vendor credit
                 */
                $this->updateBillStatus($bill);
            });


            return response()->json([
                'success' => true,
                'message' =>
                'Vendor credit applied successfully.',
            ]);
        } catch (\RuntimeException $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' =>
                'Unable to apply vendor credit.',
            ], 500);
        }
    }


    /**
     * Record money refunded by vendor against
     * an available Vendor Credit.
     */
    public function refund(
        Request $request,
        VendorCredit $vendorCredit
    ) {
        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'refund_date' => [
                'required',
                'date',
            ],

            'refund_method' => [
                'nullable',
                'string',
                'max:100',
            ],

            'transaction_id' => [
                'nullable',
                'string',
                'max:255',
            ],

            'reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);


        try {

            DB::transaction(function () use (
                $validated,
                $vendorCredit
            ) {

                /*
                 * Lock credit before calculating remaining balance.
                 */
                $credit = VendorCredit::query()
                    ->lockForUpdate()
                    ->findOrFail(
                        $vendorCredit->id
                    );


                $availableCredit =
                    (float) $credit->remaining_amount;

                $amount =
                    (float) $validated['amount'];


                if ($availableCredit <= 0) {
                    throw new \RuntimeException(
                        'This vendor credit has no remaining balance.'
                    );
                }


                if ($amount > $availableCredit) {
                    throw new \RuntimeException(
                        'Refund amount cannot exceed the available credit of '
                            . number_format(
                                $availableCredit,
                                2
                            )
                            . '.'
                    );
                }


                /*
                 * Create refund history.
                 *
                 * Do NOT increment refunded_amount manually.
                 */
                $credit->refunds()->create([
                    'amount' =>
                    $amount,

                    'refund_date' =>
                    $validated['refund_date'],

                    'refund_method' =>
                    $validated['refund_method']
                        ?? null,

                    'transaction_id' =>
                    $validated['transaction_id']
                        ?? null,

                    'reference' =>
                    $validated['reference']
                        ?? null,

                    'notes' =>
                    $validated['notes']
                        ?? null,
                ]);


                $credit->refresh();


                /*
                 * Recalculate credit status from history.
                 */
                $this->updateCreditStatus(
                    $credit
                );
            });


            return response()->json([
                'success' => true,
                'message' =>
                'Vendor refund recorded successfully.',
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
                'message' =>
                'Unable to record vendor refund.',
            ], 500);
        }
    }


    /**
     * Recalculate Vendor Credit status.
     */
    private function updateCreditStatus(
        VendorCredit $credit
    ): void {

        $applied =
            (float) $credit->applied_amount;

        $refunded =
            (float) $credit->refunded_amount;

        $remaining =
            (float) $credit->remaining_amount;


        /*
         * Nothing has happened yet.
         */
        if (
            $applied <= 0
            && $refunded <= 0
        ) {

            $status = 'open';
        }

        /*
         * Entire credit was refunded and none
         * of it was applied to bills.
         */ elseif (
            $remaining <= 0
            && $refunded > 0
            && $applied <= 0
        ) {

            $status = 'refunded';
        }

        /*
         * Credit fully consumed.
         *
         * This includes:
         *
         * fully applied
         *
         * OR
         *
         * partly applied + partly refunded.
         *
         * Your current enum doesn't have a
         * "settled" status, so "applied"
         * is used here.
         */ elseif ($remaining <= 0) {

            $status = 'applied';
        }

        /*
         * Some credit has been consumed but
         * balance still remains.
         */ else {

            $status = 'partially_applied';
        }


        $credit->update([
            'status' => $status,
        ]);
    }


    /**
     * Recalculate Vendor Bill status.
     */
    private function updateBillStatus(
        VendorBill $bill
    ): void {

        /*
         * due_amount already considers both
         * payments and credit applications.
         */
        $dueAmount =
            (float) $bill->due_amount;


        if ($dueAmount <= 0) {

            $status = 'paid';
        } else {

            $settledAmount =
                (float) $bill->paid_amount
                + (float) $bill->credited_amount;


            $status = $settledAmount > 0
                ? 'partially_paid'
                : 'unpaid';
        }


        $bill->update([
            'status' => $status,
        ]);
    }
}
