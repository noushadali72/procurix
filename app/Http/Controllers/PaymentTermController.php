<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentTerm\StorePaymentTermRequest;
use App\Http\Requests\PaymentTerm\UpdatePaymentTermRequest;
use App\Models\PaymentTerm;
use Illuminate\Http\JsonResponse;

class PaymentTermController extends Controller
{
    public function index()
    {
        $paymentTerms = PaymentTerm::latest()->paginate(10);
        return view('payment_terms.index', compact('paymentTerms'));
    }

    public function store(StorePaymentTermRequest $request)
    {
        PaymentTerm::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Payment term created successfully.',
        ], 201);
    }

    public function update(UpdatePaymentTermRequest $request, PaymentTerm $paymentTerm){
        $paymentTerm->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Payment term updated successfully.',
        ]);
    }

    public function destroy(PaymentTerm $paymentTerm)
    {
        try {
            $paymentTerm->delete();

            return redirect()
                ->route('payment-terms.index')
                ->with('success', 'Payment term deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('payment-terms.index')
                ->with('error', 'Unable to delete payment term.');
        }
    }
}
