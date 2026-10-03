<?php

namespace App\Http\Controllers;

use App\Http\Requests\RawMaterial\StoreRawMaterialRequest;
use App\Http\Requests\RawMaterial\UpdateRawMaterialRequest;
use App\Models\RawMaterial;
use App\Models\Unit;
use App\Models\Category;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RawMaterialController extends Controller
{
    /**
     * Display a listing of raw materials.
     */
    public function index(Request $request): View
    {
        $categories = Category::all();

        $rawMaterials = RawMaterial::query()
            ->when($request->filled('searchQuery'), function ($query) use ($request) {
                $search = $request->input('searchQuery');

                $query->where(function ($q) use ($search) {
                    $q->whereLike('name', "%{$search}%")
                        ->orWhereLike('description', "%{$search}%");
                });
            })
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->when($request->filled('min_price'), function ($query) use ($request) {
                $query->where('cost_price', '>=', $request->min_price);
            })
            ->when($request->filled('max_price'), function ($query) use ($request) {
                $query->where('cost_price', '<=', $request->max_price);
            })
            ->with(['unit', 'category'])
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('raw_materials.index', compact(
            'rawMaterials',
            'categories'
        ));
    }


    /**
     * Show the form for creating a new raw material.
     */
    public function create(): View
    {
        $units = Unit::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('raw_materials.create', compact('units', 'categories'));
    }


    /**
     * Store a newly created raw material.
     */
    public function store(StoreRawMaterialRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $imagePath = null;
        try{

            if($request->hasFile('image')){
                $imagePath = $request->file('image')->store('images/materials','public');
                $validated['image_path'] = $imagePath;
                unset($validated['image']);
                }
                RawMaterial::create($validated);
                
                return response()->json([
                    'success'=>true,
                    'message' => 'Raw material created successfully.',
                    ],201);
        }catch(Exception $e){
            // Delete image if create failed
            if($imagePath){
                Storage::disk('public')->delete($imagePath);
            }
            return response()->json([
                'success'=>false,
                'message'=>'Unable to create material.',
                'error'=>$e->getMessage()
            ],500);
        }
    }


    /**
     * Show the form for editing the specified raw material.
     */
    public function edit(RawMaterial $rawMaterial): View
    {
        $units = Unit::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $rawMaterial->load(['category', 'unit']);

        return view(
            'raw_materials.edit',
            compact('rawMaterial', 'units', 'categories')
        );
    }


    /**
     * Update the specified raw material.
     */
    public function update(
        UpdateRawMaterialRequest $request,
        RawMaterial $rawMaterial
    ): JsonResponse {

        $newImage = null;
        $oldImage = $rawMaterial->image_path;
        $validated = $request->validated();

        try{
        if($request->hasFile('image')){
            $newImage = $request->file('image')->store('images/materials','public');
            $validated['image_path'] = $newImage;
            unset($validated['image']);
        }
        $rawMaterial->update($validated);
        // Delete old image
        if($oldImage && $newImage){
            Storage::disk('public')->delete($oldImage);
        }
        return response()->json([
            'success'=>true,
            'message' => 'Raw material updated successfully.',
        ],200);

        }catch(Exception $e){

        // In case update failed then deleted new saved image
        if($newImage){
            Storage::disk('public')->delete($newImage);
        }

            return response()->json([
                'success'=>false,
                'message'=>'Unable to update the material.',
                'error'=>$e->getMessage()
            ],500);
        }
    }


    /**
     * Remove the specified raw material.
     */
    public function destroy(RawMaterial $rawMaterial): JsonResponse
    {
        try {

            $image = $rawMaterial->image_path;
            $rawMaterial->delete();
            if($image){
                Storage::disk('public')->delete($image);
            }
            return response()->json([
                'success'=>true,
                'message' => 'Raw material deleted successfully.',
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'success'=>false,
                'message' => 'Raw material cannot be deleted because it may be used in other records.',
                'error'=>$e->getMessage()
            ], 500);
        }
    }
}
