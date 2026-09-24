<?php

namespace App\Http\Controllers;

use App\Http\Requests\VendorPayment\StoreVendorPaymentRequest;
use App\Models\VendorBill;
use App\Models\VendorPayment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $unpaidBills = VendorBill::with([
    //         'vendor',
    //         'vendorPayments',
    //     ])->where('status', '!=', 'paid')->latest()->get();

    //     $vendorPayments = VendorPayment::with([
    //         'vendorBill',
    //         'vendorBill.vendor',
    //     ])->latest()->paginate(10);

    //     return view(
    //         'vendor_payments.index',
    //         compact('vendorPayments', 'unpaidBills')
    //     );
    // }


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

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    // public function store(StoreVendorPaymentRequest $request, VendorBill $vendorBill)
    // {
    //     $validated = $request->validated();

    //     $paidAmount = $vendorBill->vendorPayments()
    //         ->where('status', 'successful')
    //         ->sum('amount');

    //     $dueAmount = max($vendorBill->total - $paidAmount, 0);

    //     if ($validated['amount'] > $dueAmount) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Payment amount cannot exceed the outstanding amount.',
    //             'errors' => [
    //                 'amount' => [
    //                     'Amount cannot exceed the due amount: ' . number_format($dueAmount, 2),
    //                 ],
    //             ],
    //         ], 422);
    //     }

    //     try {
    //         $path = null;
    //         if($request->hasFile('payment_proof')){
    //             $path = $request->file('payment_proof')->store('images/payment_proof','public');
    //         }

    //         $vendorBill->vendorPayments()->create([
    //             'transaction_id' => $validated['transaction_id'] ?? null,
    //             'amount' => $validated['amount'],
    //             'payment_method' => $validated['payment_method'],
    //             'payment_date' => $validated['payment_date'] ?? now()->toDateString(),
    //             'payment_proof'=>$path,
    //             'status' => 'successful',
    //             'references' => $validated['references'] ?? null,
    //             'notes' => $validated['notes'] ?? null,
    //         ]);

    //         $paidAmount += $validated['amount'];

    //         $vendorBill->update([
    //             'status' => $paidAmount >= $vendorBill->total
    //                 ? 'paid'
    //                 : 'partially_paid',
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Payment recorded successfully.',
    //         ], 201);
    //     } catch (\Throwable $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Unable to make payment.',
    //             'error'=>$e->getMessage()
    //         ], 500);
    //     }
    // }

    public function store(
        StoreVendorPaymentRequest $request,
        VendorBill $vendorBill
    ) {
        $validated = $request->validated();

        /*
     * due_amount already considers:
     *
     * total
     * - successful payments
     * - applied vendor credits
     */
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

                'message' =>
                'Payment amount cannot exceed the outstanding amount.',

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

                /*
             * Lock bill while recording payment.
             */
                $bill = VendorBill::query()
                    ->lockForUpdate()
                    ->findOrFail($vendorBill->id);

                /*
             * Recalculate after locking.
             */
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

                $bill->vendorPayments()->create([
                    'transaction_id' =>
                    $validated['transaction_id'] ?? null,

                    'amount' =>
                    $validated['amount'],

                    'payment_method' =>
                    $validated['payment_method'],

                    'payment_date' =>
                    $validated['payment_date']
                        ?? now()->toDateString(),

                    'payment_proof' =>
                    $path,

                    'status' =>
                    'successful',

                    'references' =>
                    $validated['references'] ?? null,

                    'notes' =>
                    $validated['notes'] ?? null,
                ]);

                /*
             * Recalculate AFTER payment creation.
             */
                $bill->refresh();

                $remainingDue = (float) $bill->due_amount;

                if ($remainingDue <= 0) {

                    $bill->update([
                        'status' => 'paid',
                    ]);
                } else {

                    $settledAmount =
                        (float) $bill->paid_amount
                        + (float) $bill->credited_amount;

                    $bill->update([
                        'status' => $settledAmount > 0
                            ? 'partially_paid'
                            : 'unpaid',
                    ]);
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

    /**
     * Display the specified resource.
     */
    public function show(VendorPayment $vendorPayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VendorPayment $vendorPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VendorPayment $vendorPayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VendorPayment $vendorPayment)
    {
        //
    }
}
