<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest\StorePurchaseRequest;
use App\Http\Requests\PurchaseRequest\UpdatePurchaseRequest;
use App\Jobs\SendRfqMail;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestActivity;
use App\Models\Quotation;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Vendor;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
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
            'vendor'
        ])
            ->latest()
            ->paginate(10);

        return view(
            'purchase_requests.index',
            compact('purchaseRequests')
        );
    }

    public function confirm(PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'This Purchase Request has already been completed.'
            ], 500);
        }

        if ($purchaseRequest->status === 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'The purchase request is in pending review.'
            ], 500);
        }

        if ($purchaseRequest->purchaseOrder()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'A purchase order has already been created for this purchase Request.'
            ], 500);
        }

        $purchaseRequest->load(['items']);

        if ($purchaseRequest->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot accept a confirm Order without items.'
            ], 500);
        }
        $order = null;

        DB::transaction(function () use ($purchaseRequest, &$order) {
            $total = 0;
            $order = PurchaseOrder::create([
                'order_number' => 'PO-' . str_pad((PurchaseOrder::max('id') ?? 0) + 1, 5, '0', STR_PAD_LEFT),
                'purchase_request_id' => $purchaseRequest->id,
                'vendor_id' => $purchaseRequest->vendor_id,
                'status' => 'placed',
                'order_date' => now()->toDateString(),
                'total' => $total,
                'notes' => $purchaseRequest->notes,
            ]);

            foreach ($purchaseRequest->items as $item) {
                $total += $item->unit_cost * $item->qty;
                $order->items()->create([
                    'raw_material_id' => $item->raw_material_id,
                    'qty' => $item->qty,
                    'unit_id' => $item->unit_id,
                    'unit_cost' => $item->unit_cost,
                    'total' => $item->total,
                ]);
            }
            $order->update(['total' => $total]);

            $purchaseRequest->activities()->create([
                'action' => 'Purchase Order Created.',
                'description' => "Purchase Order created.",
                'user_id' => Auth::user()->id,
                'vendor_id' => $purchaseRequest->vendor_id
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Purchase Order created successfully.',
            'redirect' => route('purchase-orders.show', $order)
        ], 200);
    }

    public function confirmation(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->load(['items.rawMaterial', 'items.unit', 'vendor']);

        return view('purchase_requests.confirmation', compact('purchaseRequest'));
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
        $vendors = Vendor::all();
        return view(
            'purchase_requests.create',
            compact('rawMaterials', 'units', 'vendors')
        );
    }

    public function updateStatus(PurchaseRequest $purchaseRequest)
    {

        try {

            $purchaseRequest->update([
                'status' => 'active'
            ]);

            $purchaseRequest->activities()->create([
                'action' => 'Status updated.',
                'description' => "Purchase Request status updated to active by " . Auth::user()->name . '',
                'user_id' => Auth::user()->id,
                'vendor_id' => $purchaseRequest->vendor_id
            ]);

            return response()->json([
                'success' => true,
                'message' => "Status updated to '$purchaseRequest->status' successfully."
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to update the status.',
                'error' => $e->getMessage()
            ], 500);
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
                    'delivery_address' => $validated['delivery_address'],
                    'vendor_id' => $validated['vendor_id'],

                ]);


                foreach ($validated['items'] as $item) {

                    $purchaseRequest->items()->create([
                        'raw_material_id' => $item['raw_material_id'],
                        'qty' => $item['qty'],
                        'unit_id' => $item['unit_id'],
                        'unit_cost' => $item['unit_cost'],
                        'total' => $item['unit_cost'] * $item['qty'],
                    ]);
                }


                $purchaseRequest->activities()->create([
                    'action' => 'Purchase Request Created.',
                    'description' => "Purchase Request created by " . Auth::user()->name . '',
                    'user_id' => Auth::user()->id,
                    'vendor_id' => $purchaseRequest->vendor_id
                ]);

                return $purchaseRequest;
            }
        );

        SendRfqMail::dispatch($purchaseRequest);

        $purchaseRequest->activities()->create([
            'action' => 'Mail Sent.',
            'description' => "Purchase request Mail sent to " . $purchaseRequest->vendor->name . '',
            'user_id' => Auth::user()->id,
            'vendor_id' => $purchaseRequest->vendor_id
        ]);

        return response()->json([
            'message' => 'Purchase Request Send to Vendor successfully.',
            'id' => $purchaseRequest->id,
            'redirect' => route('purchase-requests.confirmation', $purchaseRequest),
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
            'vendor'
        ]);


        $rawMaterials = RawMaterial::with('unit.unitCategory')
            ->orderBy('name')
            ->get();


        $units = Unit::with('unitCategory')
            ->orderBy('name')
            ->get();

        $vendors = Vendor::all();


        return view(
            'purchase_requests.edit',
            compact(
                'purchaseRequest',
                'rawMaterials',
                'units',
                'vendors'
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
                    'delivery_address' => $validated['delivery_address'],
                    'vendor_id' => $validated['vendor_id'],

                ]);

                $purchaseRequest->items()->delete();
                foreach ($validated['items'] as $item) {
                    $purchaseRequest->items()->create([
                        'raw_material_id' => $item['raw_material_id'],
                        'qty' => $item['qty'],
                        'unit_id' => $item['unit_id'],
                        'unit_cost' => $item['unit_cost'],
                        'total' => $item['unit_cost'] * $item['qty'],
                    ]);
                }

                $purchaseRequest->activities()->create([
                    'action' => 'Purchase Request updated.',
                    'description' => "Purchase Request updated by " . Auth::user()->name . '',
                    'user_id' => Auth::user()->id,
                    'vendor_id' => $purchaseRequest->vendor_id
                ]);
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
    public function destroy(PurchaseRequest $purchaseRequest)
    {
        try {
            $purchaseRequest->delete();
            return response()->json([
                'success' => true,
                'message' => 'Purchase request deleted successfully.',
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
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
