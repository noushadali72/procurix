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

        return view('purchase_requests.index', compact('purchaseRequests'));
    }

    public function confirm(PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->status !== 'sent') {
            return response()->json([
                'success' => false,
                'message' => match ($purchaseRequest->status) {
                    'draft' => 'This Purchase Request is still a draft.',
                    'pending' => 'The Purchase Request is in pending review.',
                    'active' => 'This Purchase Request has already been confirmed.',
                    'partially_closed' => 'This Purchase Request has already been partially closed.',
                    'completed' => 'This Purchase Request has already been completed.',
                    'cancelled' => 'This Purchase Request has been cancelled.',
                    'expired' => 'This Purchase Request has expired.',
                    default => 'This Purchase Request cannot be confirmed.',
                }
            ], 422);
        }

        if ($purchaseRequest->purchaseOrder()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'A purchase order has already been created for this Purchase Request.'
            ], 422);
        }

        $purchaseRequest->load('items');

        if ($purchaseRequest->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot confirm a Purchase Request without items.'
            ], 422);
        }

        $order = DB::transaction(function () use ($purchaseRequest) {

            $total = 0;

            $order = PurchaseOrder::create([
                'order_number' => 'PO-' . str_pad(
                    (PurchaseOrder::max('id') ?? 0) + 1,
                    5,
                    '0',
                    STR_PAD_LEFT
                ),
                'purchase_request_id' => $purchaseRequest->id,
                'vendor_id' => $purchaseRequest->vendor_id,
                'status' => 'placed',
                'order_date' => now()->toDateString(),
                'total' => 0,
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

            $order->update([
                'total' => $total,
            ]);

            $purchaseRequest->update([
                'stage' => 'purchase_order',
                'status' => 'active',
            ]);

            $purchaseRequest->activities()->create([
                'action' => 'Purchase Order Created.',
                'description' => "Purchase Order {$order->order_number} created.",
                'user_id' => Auth::id(),
                'vendor_id' => $purchaseRequest->vendor_id,
            ]);

            return $order;
        });

        return response()->json([
            'success' => true,
            'message' => 'Purchase Order created successfully.',
            'redirect' => route('purchase-orders.show', $order),
        ]);
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

    public function resendRfq(PurchaseRequest $purchaseRequest): JsonResponse
    {
        if ($purchaseRequest->stage !== 'confirmation') {
            return response()->json([
                'message' => 'RFQ can only be resent during the confirmation stage.',
            ], 422);
        }

        $purchaseRequest->load([
            'vendor',
            'items.rawMaterial',
            'items.unit',
        ]);

        SendRfqMail::dispatch($purchaseRequest);

        $purchaseRequest->activities()->create([
            'action' => 'RFQ Resent.',
            'description' => 'RFQ resent to ' . $purchaseRequest->vendor->name . '.',
            'user_id' => Auth::id(),
            'vendor_id' => $purchaseRequest->vendor_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'RFQ sent to vendor successfully.',
        ]);
    }

    /**
     * Show create form.
     */
    /**
     * Show create form.
     */
    public function create()
    {
        $rawMaterials = RawMaterial::with('unit.unitCategory')->orderBy('name')->get();
        $units = Unit::with('unitCategory')->orderBy('name')->get();
        $vendors = Vendor::orderBy('name')->get();
        $purchaseRequest = PurchaseRequest::where('status', 'draft')->latest()->first();
        return view(
            'purchase_requests.create',
            compact('rawMaterials', 'units', 'vendors', 'purchaseRequest')
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
    public function saveDraft(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'purchase_request_id' => 'nullable|integer',
            'vendor_id' => 'nullable|exists:vendors,id',
            'delivery_address' => 'nullable|string',
            'notes' => 'nullable|string',
            'due_date' => 'nullable|date',

            'items' => 'nullable|array',
            'items.*.raw_material_id' => 'nullable|integer|exists:raw_materials,id',
            'items.*.qty' => 'nullable|numeric|gt:0',
            'items.*.unit_cost' => 'nullable|numeric|gt:0',
            'items.*.unit_id' => 'nullable|integer|exists:units,id',
        ]);

        $purchaseRequest = DB::transaction(function () use ($validated) {

            if (!empty($validated['purchase_request_id'])) {

                $purchaseRequest = PurchaseRequest::whereKey(
                    $validated['purchase_request_id']
                )
                    ->where('status', 'draft')
                    ->firstOrFail();

                $purchaseRequest->update([
                    'vendor_id' => $validated['vendor_id'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                    'delivery_address' => $validated['delivery_address'] ?? null,
                    'due_date' => $validated['due_date'],
                ]);

                $purchaseRequest->items()->delete();

                $action = 'Purchase Request Draft Updated.';
                $description = 'Purchase Request draft updated by ' . Auth::user()->name . '.';
            } else {

                $purchaseRequest = PurchaseRequest::create([
                    'status' => 'draft',
                    'stage' => 'request',
                    'vendor_id' => $validated['vendor_id'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                    'delivery_address' => $validated['delivery_address'] ?? null,
                    'due_date' => $validated['due_date'],
                ]);

                $action = 'Purchase Request Draft Created.';
                $description = 'Purchase Request draft created by ' . Auth::user()->name . '.';
            }

            foreach ($validated['items'] ?? [] as $item) {

                if (
                    empty($item['raw_material_id']) ||
                    empty($item['unit_id']) ||
                    empty($item['qty']) ||
                    empty($item['unit_cost'])
                ) {
                    continue;
                }

                $purchaseRequest->items()->create([
                    'raw_material_id' => $item['raw_material_id'],
                    'qty' => $item['qty'],
                    'unit_id' => $item['unit_id'],
                    'unit_cost' => $item['unit_cost'],
                    'total' => $item['unit_cost'] * $item['qty'],
                ]);
            }

            $purchaseRequest->activities()->create([
                'action' => $action,
                'description' => $description,
                'user_id' => Auth::id(),
                'vendor_id' => $purchaseRequest->vendor_id,
            ]);

            return $purchaseRequest;
        });

        return response()->json([
            'success' => true,
            'message' => 'Draft saved successfully.',
            'id' => $purchaseRequest->id,
            'request_number' => $purchaseRequest->request_number,
        ]);
    }

    public function compare(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->load([
            'items.rawMaterial',
            'items.unit',
            'vendor',
        ]);

        $purchaseRequests = $this->getComparablePurchaseRequests(
            $purchaseRequest
        );

        return view('purchase_requests.compare', compact(
            'purchaseRequest',
            'purchaseRequests'
        ));
    }

    public function confirmComparison(
        Request $request,
        PurchaseRequest $purchaseRequest
    ): JsonResponse {
        $validated = $request->validate([
            'selected_purchase_request' => [
                'required',
                'integer',
                'exists:purchase_requests,id',
            ],
        ]);

        $selectedId = $validated['selected_purchase_request'];

        $purchaseRequests = $this->getComparablePurchaseRequests(
            $purchaseRequest
        );

        $selectedRequest = $purchaseRequests->firstWhere('id', $selectedId);

        if (!$selectedRequest) {
            return response()->json([
                'success' => false,
                'message' => 'The selected purchase request is not part of this comparison.',
            ], 422);
        }

        if ($selectedRequest->status !== 'sent') {
            return response()->json([
                'success' => false,
                'message' => 'The selected purchase request is no longer available.',
            ], 422);
        }

        if ($selectedRequest->purchaseOrder()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'A purchase order already exists for the selected purchase request.',
            ], 422);
        }

        $order = DB::transaction(function () use (
            $selectedRequest,
            $purchaseRequests
        ) {
            $selectedRequest->load('items');

            $total = 0;

            $order = PurchaseOrder::create([
                'order_number' => 'PO-' . str_pad(
                    (PurchaseOrder::max('id') ?? 0) + 1,
                    5,
                    '0',
                    STR_PAD_LEFT
                ),
                'purchase_request_id' => $selectedRequest->id,
                'vendor_id' => $selectedRequest->vendor_id,
                'status' => 'placed',
                'order_date' => now()->toDateString(),
                'total' => 0,
                'notes' => $selectedRequest->notes,
            ]);

            foreach ($selectedRequest->items as $item) {
                $total += $item->unit_cost * $item->qty;

                $order->items()->create([
                    'raw_material_id' => $item->raw_material_id,
                    'qty' => $item->qty,
                    'unit_id' => $item->unit_id,
                    'unit_cost' => $item->unit_cost,
                    'total' => $item->total,
                ]);
            }

            $order->update([
                'total' => $total,
            ]);

            $selectedRequest->update([
                'status' => 'active',
                'stage' => 'purchase_order',
            ]);

            $selectedRequest->activities()->create([
                'action' => 'Purchase Order Created.',
                'description' => "Purchase Order {$order->order_number} created after vendor request comparison.",
                'user_id' => Auth::id(),
                'vendor_id' => $selectedRequest->vendor_id,
            ]);

            foreach ($purchaseRequests as $requestItem) {
                if ($requestItem->id === $selectedRequest->id) {
                    continue;
                }

                if ($requestItem->status !== 'sent') {
                    continue;
                }

                $requestItem->update([
                    'status' => 'cancelled',
                    'stage' => 'cancelled'
                ]);

                $requestItem->activities()->create([
                    'action' => 'Purchase Request Cancelled.',
                    'description' => "Purchase Request cancelled because another vendor request was selected.",
                    'user_id' => Auth::id(),
                    'vendor_id' => $requestItem->vendor_id,
                ]);
            }

            return $order;
        });

        return response()->json([
            'success' => true,
            'message' => 'Purchase order created successfully.',
            'redirect' => route('purchase-orders.show', $order),
        ]);
    }

    public function duplicate(PurchaseRequest $pr): JsonResponse
    {
        $pr->load('items');

        $duplicate = DB::transaction(function () use ($pr) {
            $purchaseRequest = PurchaseRequest::create([
               
                'status' => 'draft',
                'stage' => 'request',
                'vendor_id' => $pr->vendor_id,
                'notes' => $pr->notes,
                'due_date' => $pr->due_date,
                'delivery_address' => $pr->delivery_address,
            ]);

            foreach ($pr->items as $item) {
                $purchaseRequest->items()->create([
                    'raw_material_id' => $item->raw_material_id,
                    'qty' => $item->qty,
                    'unit_id' => $item->unit_id,
                    'unit_cost' => $item->unit_cost,
                    'total' => $item->total,
                ]);
            }

            $purchaseRequest->activities()->create([
                'action' => 'Purchase Request Duplicated.',
                'description' => "Purchase Request duplicated from {$pr->request_number} by " . Auth::user()->name . '.',
                'user_id' => Auth::id(),
                'vendor_id' => $purchaseRequest->vendor_id,
            ]);

            return $purchaseRequest;
        });

        return response()->json([
            'success' => true,
            'message' => 'Purchase Request duplicated successfully.',
            'id' => $duplicate->id,
            'redirect' => route('purchase-requests.show', $duplicate),
        ]);
    }
    /**
     * Store purchase request.
     */
    public function store(StorePurchaseRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $purchaseRequest = DB::transaction(function () use ($validated) {

            $purchaseRequest = null;

            if (!empty($validated['purchase_request_id'])) {
                $purchaseRequest = PurchaseRequest::where('id', $validated['purchase_request_id'])
                    ->where('status', 'draft')
                    ->first();
            }

            if (!$purchaseRequest) {
                $purchaseRequest = PurchaseRequest::create([
                    'status' => 'sent',
                    'notes' => $validated['notes'] ?? null,
                    'delivery_address' => $validated['delivery_address'] ?? null,
                    'vendor_id' => $validated['vendor_id'],
                    'due_date' => $validated['due_date'],
                ]);
            } else {
                $purchaseRequest->update([
                    'status' => 'sent',
                    'notes' => $validated['notes'] ?? null,
                    'delivery_address' => $validated['delivery_address'] ?? null,
                    'vendor_id' => $validated['vendor_id'],
                    'stage' => 'confirmation',
                    'due_date' => $validated['due_date']
                ]);

                $purchaseRequest->items()->delete();
            }

            foreach ($validated['items'] as $item) {
                $purchaseRequest->items()->create([
                    'raw_material_id' => $item['raw_material_id'],
                    'qty' => $item['qty'],
                    'unit_id' => $item['unit_id'],
                    'unit_cost' => $item['unit_cost'],
                    'total' => $item['unit_cost'] * $item['qty'],
                ]);
            }

            $purchaseRequest->update([
                'stage' => 'confirmation',
            ]);

            $purchaseRequest->activities()->create([
                'action' => 'Purchase Request Submitted.',
                'description' => 'Purchase Request submitted by ' . Auth::user()->name . '.',
                'user_id' => Auth::id(),
                'vendor_id' => $purchaseRequest->vendor_id,
            ]);

            return $purchaseRequest;
        });

        SendRfqMail::dispatch($purchaseRequest);

        $purchaseRequest->activities()->create([
            'action' => 'RFQ Mail Sent.',
            'description' => 'Purchase request RFQ mail sent to ' . $purchaseRequest->vendor->name . '.',
            'user_id' => Auth::id(),
            'vendor_id' => $purchaseRequest->vendor_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Purchase Request sent to vendor successfully.',
            'id' => $purchaseRequest->id,
            'redirect' => route(
                'purchase-requests.confirmation',
                $purchaseRequest
            ),
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
            'vendor',
            'quotations.items',
        ]);

        return match ($purchaseRequest->stage) {
            'request' => view(
                'purchase_requests.create',
                [
                    'purchaseRequest' => $purchaseRequest,
                    'rawMaterials' => RawMaterial::with('unit.unitCategory')->orderBy('name')->get(),
                    'units' => Unit::with('unitCategory')->orderBy('name')->get(),
                    'vendors' => Vendor::orderBy('name')->get(),
                ]
            ),

            'confirmation' => view('purchase_requests.confirmation', compact('purchaseRequest')),
            'cancelled' => view('purchase_requests.show', compact('purchaseRequest')),
            'purchase_order',
            'receiving' => view('purchase_orders.show', ['purchaseOrder' => $purchaseRequest->purchaseOrder]),
            default => view('purchase_requests.show', compact('purchaseRequest')),
        };
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
            'redirect' => route(
                'purchase-requests.confirmation',
                $purchaseRequest
            ),
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


    private function getComparablePurchaseRequests(
        PurchaseRequest $purchaseRequest
    ) {
        $purchaseRequest->load('items');

        $materialIds = $purchaseRequest->items
            ->pluck('raw_material_id')
            ->unique()
            ->sort()
            ->values();

        $requests = PurchaseRequest::query()
            ->with([
                'items.rawMaterial',
                'items.unit',
                'vendor',
            ])
            ->where('status', 'sent')
            ->where('id', '!=', $purchaseRequest->id)
            ->get()
            ->filter(function ($request) use ($materialIds) {
                $requestMaterialIds = $request->items
                    ->pluck('raw_material_id')
                    ->unique()
                    ->sort()
                    ->values();

                return $requestMaterialIds->values()->all()
                    === $materialIds->values()->all();
            });

        return $requests
            ->push($purchaseRequest)
            ->sortBy('id')
            ->values();
    }
}
