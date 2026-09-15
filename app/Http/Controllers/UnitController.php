<?php

namespace App\Http\Controllers;

use App\Http\Requests\Unit\StoreUnitRequest;
use App\Http\Requests\Unit\UpdateUnitRequest;
use App\Models\Unit;
use App\Models\UnitCategory;

class UnitController extends Controller
{
    /**
     * Display all units.
     */
    public function index()
    {
        $unitCategories = UnitCategory::orderBy('name')->get();
        $units = Unit::with('unitCategory')->latest()->paginate(10);
        return view('units.index', compact('units', 'unitCategories'));
    }


    /**
     * Show create form.
     */
    public function create()
    {
        $unitCategories = UnitCategory::orderBy('name')->get();
        return view('units.create', compact('unitCategories'));
    }


    /**
     * Store unit.
     */
    public function store(StoreUnitRequest $request)
    {
        $validated = $request->validated();
        
        if ($request->is_base && Unit::where('unit_category_id', $request->unit_category_id)
        ->where('is_base', true)
        ->exists()) {
        return response()->json([
            'message' => 'This category already has a base unit.'
        ], 422);
        }

        Unit::create([
            'name' => $validated['name'],
            'short_name' => $validated['short_name'],
            'unit_category_id' => $validated['unit_category_id'],
            'is_base' => $validated['is_base']??false,
            'conversion_factor' => $validated['conversion_factor'],
        ]);       
        return response()->json(['message' => 'Unit created successfully.'], 201);
    }


    /**
     * Show unit.
     */
    public function show(Unit $unit)
    {
        $unit->load('unitCategory');
        return view('units.show', compact('unit'));
    }


    /**
     * Show edit form.
     */
    public function edit(Unit $unit)
    {
        $unit->load('unitCategory');
        return view('units.edit', compact('unit'));
    }


    /**
     * Update unit.
     */
    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        $validated = $request->validated();
        
        if ($request->is_base && Unit::where('unit_category_id', $request->unit_category_id)
        ->where('is_base', true)
        ->where('id', '!=', $unit->id)
        ->exists()) {
        
        return response()->json([
            'message' => 'This category already has a base unit.'
        ], 422);

        }

        $unit->update([
            'name' => $validated['name'],
            'short_name' => $validated['short_name'],
            'unit_category_id' => $validated['unit_category_id'],
            'is_base' => $validated['is_base'],
            'conversion_factor' => $validated['conversion_factor'],
        ]);
       return response()->json(['message' => 'Unit updated successfully.'], 200);
    }


    /**
     * Delete unit.
     */
    public function destroy(Unit $unit)
    {
        try {
            $unit->delete();
            return response()->json(['message' => 'Unit deleted successfully.'], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Unable to delete this unit because it is being used.'], 422);
        }
    }
}