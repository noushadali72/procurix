<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest\StorePurchaseRequest;
use App\Http\Requests\PurchaseRequest\UpdatePurchaseRequest;
use App\Models\PurchaseRequest;
use App\Models\RawMaterial;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseRequestController extends Controller
{
    /**
     * Display purchase requests.
     */
    public function index()
    {
        $purchaseRequests = PurchaseRequest::with([
            'items.rawMaterial',
            'items.unit',
        ])
            ->latest()
            ->paginate(10);

        return view(
            'purchase_requests.index',
            compact('purchaseRequests')
        );
    }


    /**
     * Show create form.
     */
    public function create()
    {
        $rawMaterials = RawMaterial::with('unit.unitCategory')
            ->orderBy('name')
            ->get();

        $units = Unit::with('unitCategory')
            ->orderBy('name')
            ->get();

        return view(
            'purchase_requests.create',
            compact('rawMaterials', 'units')
        );
    }


    /**
     * Store purchase request.
     */
    public function store(
        StorePurchaseRequest $request
    ): JsonResponse {

        $validated = $request->validated();


        $purchaseRequest = DB::transaction(
            function () use ($validated) {

                $purchaseRequest = PurchaseRequest::create([
                    'request_number' => $this->generateRequestNumber(),
                    'status' => $validated['status'],
                    'notes' => $validated['notes'] ?? null,
                ]);


                foreach ($validated['items'] as $item) {

                    $purchaseRequest->items()->create([
                        'raw_material_id' => $item['raw_material_id'],
                        'qty' => $item['qty'],
                        'unit_id' => $item['unit_id'],
                    ]);
                }


                return $purchaseRequest;
            }
        );


        return response()->json([
            'message' => 'Purchase request created successfully.',
            'id' => $purchaseRequest->id,
            'redirect' => route('purchase-requests.index'),
        ], 201);
    }


    /**
     * Display purchase request.
     */
    public function show(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->load([
            'items.rawMaterial',
            'items.unit',
        ]);


        return view(
            'purchase_requests.show',
            compact('purchaseRequest')
        );
    }


    /**
     * Show edit form.
     */
    public function edit(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->load([
            'items.rawMaterial',
            'items.unit',
        ]);


        $rawMaterials = RawMaterial::with('unit.unitCategory')
            ->orderBy('name')
            ->get();


        $units = Unit::with('unitCategory')
            ->orderBy('name')
            ->get();


        return view(
            'purchase_requests.edit',
            compact(
                'purchaseRequest',
                'rawMaterials',
                'units'
            )
        );
    }


    /**
     * Update purchase request.
     */
    public function update(
        UpdatePurchaseRequest $request,
        PurchaseRequest $purchaseRequest
    ): JsonResponse {

        $validated = $request->validated();


        DB::transaction(
            function () use ($validated, $purchaseRequest) {

                $purchaseRequest->update([
                    'status' => $validated['status'],
                    'notes' => $validated['notes'] ?? null,
                ]);


                /*
                 * Simple approach:
                 * delete existing items and recreate submitted ones.
                 */
                $purchaseRequest->items()->delete();


                foreach ($validated['items'] as $item) {

                    $purchaseRequest->items()->create([
                        'raw_material_id' => $item['raw_material_id'],
                        'qty' => $item['qty'],
                        'unit_id' => $item['unit_id'],
                    ]);
                }
            }
        );


        return response()->json([
            'message' => 'Purchase request updated successfully.',
            'id' => $purchaseRequest->id,
            'redirect' => route('purchase-requests.index'),
        ]);
    }


    /**
     * Delete purchase request.
     */
    public function destroy(
        PurchaseRequest $purchaseRequest
    ): JsonResponse {

        try {

            $purchaseRequest->delete();


            return response()->json([
                'message' => 'Purchase request deleted successfully.',
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'message' => 'Unable to delete purchase request.',
            ], 500);
        }
    }


    /**
     * Get raw material details.
     */
    public function rawMaterial(
        RawMaterial $rawMaterial
    ): JsonResponse {

        $rawMaterial->load('unit.unitCategory');


        return response()->json([
            'id' => $rawMaterial->id,
            'name' => $rawMaterial->name,
            'sku' => $rawMaterial->sku,
            'cost_price' => $rawMaterial->cost_price,
            'stock' => $rawMaterial->stock,
            'minimum_stock' => $rawMaterial->minimum_stock,

            'unit_id' => $rawMaterial->unit_id,
            'unit_name' => $rawMaterial->unit?->name,
            'unit_short_name' => $rawMaterial->unit?->short_name,

            'unit_category_id' =>
            $rawMaterial->unit?->unit_category_id,

            'description' => $rawMaterial->description,
        ]);
    }


    /**
     * Generate unique purchase request number.
     */
    private function generateRequestNumber(): string
    {
        do {

            $requestNumber =
                'PR-' . strtoupper(Str::random(7));
        } while (
            PurchaseRequest::where(
                'request_number',
                $requestNumber
            )->exists()
        );


        return $requestNumber;
    }
}
