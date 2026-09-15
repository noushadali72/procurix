<?php

namespace App\Http\Controllers;

use App\Http\Requests\RawMaterial\StoreRawMaterialRequest;
use App\Http\Requests\RawMaterial\UpdateRawMaterialRequest;
use App\Models\RawMaterial;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class RawMaterialController extends Controller
{
    /**
     * Display a listing of raw materials.
     */
    public function index(): View
    {
        $rawMaterials = RawMaterial::with('unit')
            ->latest()
            ->paginate(10);

        return view('raw_materials.index', compact('rawMaterials'));
    }


    /**
     * Show the form for creating a new raw material.
     */
    public function create(): View
    {
        $units = Unit::orderBy('name')->get();

        return view('raw_materials.create', compact('units'));
    }


    /**
     * Store a newly created raw material.
     */
    public function store(StoreRawMaterialRequest $request): JsonResponse
    {
        RawMaterial::create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Raw material created successfully.',
        ]);
    }


    /**
     * Show the form for editing the specified raw material.
     */
    public function edit(RawMaterial $rawMaterial): View
    {
        $units = Unit::orderBy('name')->get();

        return view(
            'raw_materials.edit',
            compact('rawMaterial', 'units')
        );
    }


    /**
     * Update the specified raw material.
     */
    public function update(
        UpdateRawMaterialRequest $request,
        RawMaterial $rawMaterial
    ): JsonResponse {
        $rawMaterial->update(
            $request->validated()
        );

        return response()->json([
            'message' => 'Raw material updated successfully.',
        ]);
    }


    /**
     * Remove the specified raw material.
     */
    public function destroy(RawMaterial $rawMaterial): JsonResponse
    {
        try {

            $rawMaterial->delete();

            return response()->json([
                'message' => 'Raw material deleted successfully.',
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'message' => 'Raw material cannot be deleted because it may be used in other records.',
            ], 409);

        }
    }
}