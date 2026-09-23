<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vendor\StoreVendorRequest;
use App\Http\Requests\Vendor\UpdateVendorRequest;
use App\Models\PaymentTerm;
use App\Models\Vendor;
use Faker\Provider\Payment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    /**
     * Display vendors.
     */
    public function index()
    {
        $vendors = Vendor::with('paymentTerm')->latest()->paginate(10);
        return view('vendors.index', compact('vendors'));
    }


    /**
     * Show create form.
     */
    public function create()
    {
        $paymentTerms = PaymentTerm::orderBy('name')->get();
        return view('vendors.create',compact('paymentTerms'));
    }


    /**
     * Store vendor.
     */
    public function store(StoreVendorRequest $request)
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');
        try{
            Vendor::create([
                'name' => $validated['name'],
                'company_name' => $validated['company_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'ntn'=>$validated['ntn'],
                'contact_person' => $validated['contact_person'],
                'address' => $validated['address'] ?? null,
                'payment_term_id'=>$validated['payment_term_id']??null,
                'is_active' => $validated['is_active'],
            ]);
            return response()->json(['message' => 'Vendor created successfully.'], 201);
        }catch(\Exception $e){
            return response()->json(['success'=>false,'message' => 'Unable to create vendor.', 'error'=>$e->getMessage()], 500);
        }

    }


    /**
     * Display vendor.
     */
    public function show(Vendor $vendor)
    {
        $vendor->load(['paymentTerm']);
        return view('vendors.show', compact('vendor'));
    }


    /**
     * Show edit form.
     */
    public function edit(Vendor $vendor)
    {
        $paymentTerms = PaymentTerm::orderBy('name')->get();
        $vendor->load('paymentTerm');
        return view('vendors.edit', compact('vendor','paymentTerms'));
    }


    /**
     * Update vendor.
     */
    public function update(UpdateVendorRequest $request, Vendor $vendor)
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');

        try{

            $vendor->update($validated);
            return response()->json(['message' => 'Vendor updated successfully.'], 200);
            
        }catch(\Exception $e){
            return response()->json(['message' => 'Unable to update vendor.'], 500);
        }
            
    
    }


    /**
     * Delete vendor.
     */
    public function destroy(Vendor $vendor)
    {
        try {
            $vendor->delete();
            return response()->json(['message' => 'Vendor deleted successfully.'], 200);

        } catch (\Throwable $e) {

            return response()->json(['message' => 'Unable to delete vendor.'], 500);
        }
    }
}