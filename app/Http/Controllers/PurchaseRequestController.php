<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest\StorePurchaseRequest;
use App\Http\Requests\PurchaseRequest\UpdatePurchaseRequest;
use App\Models\PurchaseRequest;
use App\Models\Quotation;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use App\Models\Unit;
use Exception;
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


    public function quotations(PurchaseRequest $pr)
    {
        $pr->load([
            'quotations.vendor',
            'quotations.items',
        ]);

        return view('purchase_requests.quotations', compact('pr'));
    }


    public function compareQuotations(PurchaseRequest $pr)
    {
        $quotationIds = request()->input('quotations', []);
        abort_if(count($quotationIds) < 2, 422);
        $pr->load([
            'items.rawMaterial',
            'items.unit',
        ]);
        $quotations = $pr->quotations()
            ->whereIn('id', $quotationIds)
            ->with([
                'vendor',
                'items.rawMaterial',
                'items.unit',
            ])->get();
        return view(
            'purchase_requests.quotation-comparison',
            compact('pr', 'quotations')
        );
    }
    /**
     * Show create form.
     */
    public function create()
    {
        $rawMaterials = RawMaterial::with('unit.unitCategory')->orderBy('name')->get();
        $units = Unit::with('unitCategory')->orderBy('name')->get();

        return view(
            'purchase_requests.create',
            compact('rawMaterials', 'units')
        );
    }

    public function updateStatus(PurchaseRequest $purchaseRequest){

        try{

            $purchaseRequest->update([
                'status'=>'active'
                ]);
                
                return response()->json([
                    'success'=>true,
                    'message'=>"Status updated to '$purchaseRequest->status' successfully."
                    ],200);
                    
        }catch(Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>'Unable to update the status.',
                'error'=>$e->getMessage()
            ],500);
        }


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
                    'delivery_address'=>$validated['delivery_address']
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
            'quotations',
            'quotations.items'

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
                    'delivery_address'=>$validated['delivery_address']
                ]);

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
    public function destroy(PurchaseRequest $purchaseRequest){
        try {
            $purchaseRequest->delete();
            return response()->json([
                'success'=>true,
                'message' => 'Purchase request deleted successfully.',
            ],200);
        } catch (\Throwable $e) {
            return response()->json([
                'success'=>false,
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
