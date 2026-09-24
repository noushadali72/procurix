<?php

namespace App\Http\Controllers;

use App\Http\Requests\RawMaterial\StoreRawMaterialRequest;
use App\Http\Requests\RawMaterial\UpdateRawMaterialRequest;
use App\Models\RawMaterial;
use App\Models\Unit;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RawMaterialController extends Controller
{
    /**
     * Display a listing of raw materials.
     */
    public function index(Request $request): View
    {
        $categories = Category::all();

        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');

        $rawMaterials = RawMaterial::query()->when($request->filled('searchQuery'), function($query) use($request) {

            $searchQuery = $request->input('searchQuery');

            $query->whereLike('name',"%{$searchQuery}%")->orWhereLike('description',"%{$searchQuery}%");

        })
        ->when($request->filled('category_id'),function($query) use($request){
            $category_id = $request->input('category_id');
            $query->where('category_id',$category_id);
        })
        
        
        
        
        ->with(['unit','category'])->paginate(10);

        return view('raw_materials.index', compact('rawMaterials','categories'));
    }


    /**
     * Show the form for creating a new raw material.
     */
    public function create(): View
    {
        $units = Unit::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('raw_materials.create', compact('units','categories'));
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
        $categories = Category::orderBy('name')->get();
        $rawMaterial->load(['category','unit']);

        return view(
            'raw_materials.edit',
            compact('rawMaterial', 'units','categories')
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