<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManufacturingFormula\UpdateManufacturingFormulaRequest;
use App\Http\Requests\ManufacturingFormula\StoreManufacturingFormulaRequest;
use App\Models\ManufacturingFormula;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\Unit;
use Exception;
use Illuminate\Support\Facades\DB;

class ManufacturingFormulaController extends Controller
{
    public function index()
    {
        $formulas = ManufacturingFormula::with([
            'product',
            'items.rawMaterial',
            'items.unit',
        ])
            ->latest()
            ->paginate(15);

        return view('manufacturing_formulas.index', compact('formulas'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        $rawMaterials = RawMaterial::with('unit.unitCategory')->orderBy('name')->get();
        $units = Unit::with('unitCategory')->orderBy('name')->get();
        
        return view('manufacturing_formulas.create',compact('products', 'rawMaterials', 'units'));
    }

    public function store(StoreManufacturingFormulaRequest $request)
    {
        $validated = $request->validated();
        try {
            DB::transaction(function () use ($validated) {

                $formula = ManufacturingFormula::create([
                    'product_id' => $validated['product_id'],
                    'name' => $validated['name'],
                    'description' => $validated['description'] ?? null,
                ]);

                foreach ($validated['items'] as $item) {
                    $formula->items()->create([
                        'raw_material_id' => $item['raw_material_id'],
                        'quantity' => $item['quantity'],
                        'unit_id' => $item['unit_id'],
                    ]);
                }
            });

            return response()->json([
                'message' => 'Manufacturing formula created successfully.'
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unable to create Manufacturing formula.'
            ], 500);
        }
    }

    public function edit(ManufacturingFormula $manufacturingFormula)
    {
        $manufacturingFormula->load([
            'items.rawMaterial.unit',
            'items.unit',
        ]);

        $products = Product::orderBy('name')->get();
        $rawMaterials = RawMaterial::with('unit.unitCategory')
            ->orderBy('name')
            ->get();
        $units = Unit::with('unitCategory')
            ->orderBy('name')
            ->get();
        return view(
            'manufacturing_formulas.edit',
            compact(
                'manufacturingFormula',
                'products',
                'rawMaterials',
                'units'
            )
        );
    }

    public function update(UpdateManufacturingFormulaRequest $request, ManufacturingFormula $manufacturingFormula) {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated, $manufacturingFormula) {

                $manufacturingFormula->update([
                    'product_id' => $validated['product_id'],
                    'name' => $validated['name'],
                    'description' => $validated['description'] ?? null,
                ]);

                $manufacturingFormula->items()->delete();

                foreach ($validated['items'] as $item) {
                    $manufacturingFormula->items()->create([
                        'raw_material_id' => $item['raw_material_id'],
                        'quantity' => $item['quantity'],
                        'unit_id' => $item['unit_id'],
                    ]);
                }
            });

            return response()->json([
                'message' => 'Manufacturing formula updated successfully.'
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'message' => 'Unable to update Manufacturing formula.'
            ], 500);
        }
    }

    public function destroy(ManufacturingFormula $manufacturingFormula)
    {
        try {
            $manufacturingFormula->delete();

            return response()->json([
                'message' => 'Manufacturing formula deleted successfully.'
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'message' => 'Unable to delete Manufacturing formula.'
            ], 500);
        }
    }
}