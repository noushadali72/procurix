<?php

namespace App\Http\Controllers;

use App\Models\ManufacturingFormula;
use App\Models\ManufacturingRecord;
use App\Models\Product;
use App\Models\Unit;
use App\Services\UnitConversionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManufacturingController extends Controller
{


public function index()
    {
        $products = Product::with([
            'unit',
            'manufacturingFormula.items.rawMaterial.unit',
            'manufacturingFormula.items.unit',
        ])->get();

        $units = Unit::with('unitCategory')->get();

        return view('manufacturing.index', compact(
            'products',
            'units'
        ));
    }

    public function records()
        {
            $records = ManufacturingRecord::with([
                'product.unit',
                'manufacturingFormula',
                'unit',
            ])
                ->latest('manufactured_at')
                ->paginate(20);

            return view('manufacturing.records', compact('records'));
        }


    public function manufacture(
    Request $request,
    UnitConversionService $conversionService
): JsonResponse {
    $validated = $request->validate([
        'product_id' => ['required', 'exists:products,id'],
        'quantity'   => ['required', 'numeric', 'gt:0'],
        'unit_id'    => ['required', 'exists:units,id'],
    ]);

    try {
        DB::transaction(function () use ($validated, $conversionService) {

            $product = Product::with([
                'unit',
                'manufacturingFormula.items.rawMaterial.unit',
                'manufacturingFormula.items.unit',
            ])->findOrFail($validated['product_id']);

            $formula = $product->manufacturingFormula;

            if (!$formula) {
                throw new \Exception(
                    'This product does not have a manufacturing formula.'
                );
            }

            $manufacturingUnit = Unit::findOrFail($validated['unit_id']);

            /*
             * Convert the manufacturing quantity
             * into the product's stock unit.
             */
            $productQuantity = $conversionService->convert(
                (float) $validated['quantity'],
                $manufacturingUnit,
                $product->unit
            );

            /*
             * Check and deduct raw materials.
             *
             * Formula item quantity = required quantity
             * of raw material for 1 product unit.
             */
            foreach ($formula->items as $item) {

                $rawMaterial = $item->rawMaterial;

                if (!$rawMaterial) {
                    continue;
                }

                $requiredQuantity =
                    (float) $item->quantity * $productQuantity;

                /*
                 * Convert formula item's unit
                 * into raw material's stock unit.
                 */
                $requiredInStockUnit = $conversionService->convert(
                    $requiredQuantity,
                    $item->unit,
                    $rawMaterial->unit
                );

                if ((float) $rawMaterial->stock < $requiredInStockUnit) {
                    throw new \Exception(
                        "Insufficient stock for {$rawMaterial->name}."
                    );
                }

                $rawMaterial->decrement(
                    'stock',
                    $requiredInStockUnit
                );
            }

            /*
             * Add manufactured quantity to product stock.
             */
            $product->increment(
                'stock',
                $productQuantity
            );

            /*
             * Save manufacturing history.
             */
            ManufacturingRecord::create([
                'product_id' => $product->id,
                'manufacturing_formula_id' => $formula->id,
                'quantity' => $validated['quantity'],
                'unit_id' => $manufacturingUnit->id,
                'manufactured_at' => now(),
            ]);
        });

        return response()->json([
            'message' => 'Product manufactured successfully.',
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'message' => $e->getMessage(),
        ], 422);
    }
}
}