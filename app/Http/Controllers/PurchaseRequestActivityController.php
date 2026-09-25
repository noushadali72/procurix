<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestActivity;
use Illuminate\Http\Request;

class PurchaseRequestActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PurchaseRequest $pr)
    {
        $activities = PurchaseRequestActivity::where(
            'purchase_request_id',
            $pr->id
        )
            ->with([
                'user:id,name',
                'vendor:id,name,company_name',
            ])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Activities fetched successfully.',
            'activities' => $activities
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseRequestActivity $purchaseRequestActivity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseRequestActivity $purchaseRequestActivity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseRequestActivity $purchaseRequestActivity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseRequestActivity $purchaseRequestActivity)
    {
        //
    }
}
