<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quotation\StoreQuotationRequest;
use App\Http\Requests\Quotation\UpdateQuotationRequest;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    /**
     * Display quotations.
     */
    public function index()
    {
        $quotations = Quotation::with([
            'purchaseRequest',
            'vendor',
            'items',
        ])
            ->latest()
            ->paginate(10);

        return view('quotations.index', compact('quotations'));
    }

    /**
     * Show create form.
     */
    public function create(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->load([
            'items.rawMaterial',
            'items.unit',
        ]);

        $vendors = Vendor::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('quotations.create', compact(
            'purchaseRequest',
            'vendors'
        ));
    }

    /**
     * Store quotation.
     */
    public function store(StoreQuotationRequest $request)
    {
        $validated = $request->validated();

        $purchaseRequest = PurchaseRequest::findOrFail(
            $validated['purchase_request_id']
        );

        $exists = Quotation::where(
            'purchase_request_id',
            $purchaseRequest->id
        )
            ->where('vendor_id', $validated['vendor_id'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => [
                    'vendor_id' => [
                        'This vendor has already submitted a quotation for this purchase request.',
                    ],
                ],
            ], 422);
        }

        $quotation = DB::transaction(function () use ($validated) {

            $quotation = Quotation::create([
                'purchase_request_id' => $validated['purchase_request_id'],
                'vendor_id' => $validated['vendor_id'],
                'quotation_number' => $validated['quotation_number'] ?? null,
                'status' => $validated['status'],
                'quotation_date' => $validated['quotation_date'],
                'valid_until' => $validated['valid_until'] ?? now()->addDays(3)->toDateString(),
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $total = $item['qty'] * $item['price'];

                $quotation->items()->create([
                    'raw_material_id' => $item['raw_material_id'],
                    'qty' => $item['qty'],
                    'unit_id' => $item['unit_id'],
                    'price' => $item['price'],
                    'total' => $total,
                ]);
            }

            return $quotation;
        });

        return response()->json([
            'message' => 'Quotation created successfully.',
            'id' => $quotation->id,
            'redirect' => route('quotations.index'),
        ], 201);
    }

    /**
     * Show quotation.
     */
    public function show(Quotation $quotation)
    {
        $quotation->load([
            'purchaseRequest.items.rawMaterial',
            'purchaseRequest.items.unit',
            'vendor',
            'items.rawMaterial',
            'items.unit',
        ]);

        return view('quotations.show', compact('quotation'));
    }

    /**
     * Edit quotation.
     */
    public function edit(Quotation $quotation)
    {
        $quotation->load([
            'purchaseRequest.items.rawMaterial',
            'purchaseRequest.items.unit',
            'items',
        ]);

        $purchaseRequest = $quotation->purchaseRequest;

        $vendors = Vendor::where('is_active', true)
            ->orderBy('name')
            ->get();

        $quotationItems = $quotation->items->keyBy('raw_material_id');

        return view('quotations.edit', compact(
            'quotation',
            'purchaseRequest',
            'quotationItems',
            'vendors'
        ));
    }

    /**
     * Update quotation.
     */
    public function update(
        UpdateQuotationRequest $request,
        Quotation $quotation
    ) {
        $validated = $request->validated();

        $exists = Quotation::where(
            'purchase_request_id',
            $quotation->purchase_request_id
        )
            ->where('vendor_id', $validated['vendor_id'])
            ->where('id', '!=', $quotation->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => [
                    'vendor_id' => [
                        'This vendor already has a quotation for this purchase request.',
                    ],
                ],
            ], 422);
        }

        DB::transaction(function () use ($validated, $quotation) {

            $quotation->update([
                'vendor_id' => $validated['vendor_id'],
                'quotation_number' => $validated['quotation_number'] ?? null,
                'status' => $validated['status'],
                'quotation_date' => $validated['quotation_date'],
                'valid_until' => $validated['valid_until'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            $quotation->items()->delete();

            foreach ($validated['items'] as $item) {
                $total = $item['qty'] * $item['price'];

                $quotation->items()->create([
                    'raw_material_id' => $item['raw_material_id'],
                    'qty' => $item['qty'],
                    'unit_id' => $item['unit_id'],
                    'price' => $item['price'],
                    'total' => $total,
                ]);
            }
        });

        return response()->json([
            'message' => 'Quotation updated successfully.',
            'redirect' => route('quotations.index'),
        ]);
    }

    /**
     * Accept quotation and create purchase order.
     */
    public function accept(Quotation $quotation)
    {
        if ($quotation->status === 'accepted') {
            return back()->with(
                'error',
                'This quotation has already been accepted.'
            );
        }

        if ($quotation->status === 'expired') {
            return back()->with(
                'error',
                'This quotation has been Expired.'
            );
        }

        if ($quotation->purchaseOrder()->exists()) {
            return back()->with(
                'error',
                'A purchase order has already been created for this quotation.'
            );
        }

        $quotation->load([
            'items',
            'purchaseRequest',
        ]);

        if ($quotation->items->isEmpty()) {
            return back()->with(
                'error',
                'Cannot accept a quotation without items.'
            );
        }

        DB::transaction(function () use ($quotation) {

            $order = PurchaseOrder::create([
                'order_number' =>
                    'PO-' . str_pad(
                        (PurchaseOrder::max('id') ?? 0) + 1,
                        5,
                        '0',
                        STR_PAD_LEFT
                    ),

                'quotation_id' => $quotation->id,
                'vendor_id' => $quotation->vendor_id,
                'status' => 'placed',
                'order_date' => now()->toDateString(),
                'notes' => $quotation->notes,
            ]);

            foreach ($quotation->items as $item) {
                $order->items()->create([
                    'raw_material_id' => $item->raw_material_id,
                    'qty' => $item->qty,
                    'unit_id' => $item->unit_id,
                    'price' => $item->price,
                    'total' => $item->total,
                ]);
            }

            $quotation->update([
                'status' => 'accepted',
            ]);

            $quotation->purchaseRequest->update([
                'status' => 'completed',
            ]);
        });

        return redirect()
            ->route('quotations.show', $quotation)
            ->with(
                'success',
                'Quotation accepted and Purchase Order created successfully.'
            );
    }

    /**
     * Delete quotation.
     */
    public function destroy(Quotation $quotation)
    {
        try{

            DB::transaction(function () use ($quotation) {
                $quotation->items()->delete();
                $quotation->delete();
                });
                
                return response()->json([
                    'message' => 'Quotation deleted successfully.',
                    ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete quotation. May be associated with a purchase order.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
