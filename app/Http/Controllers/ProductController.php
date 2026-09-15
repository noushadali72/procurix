<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Models\Unit;
use Exception;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('unit')
            ->latest()
            ->paginate(15);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $units = Unit::orderBy('name')->get();
        return view('products.create', compact('units'));
    }

    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();
        Product::create($validated);
       return response()->json(['message' => 'Product created successfully.'], 201);
    }

    public function manufacture(){
        
    }

    public function show(Product $product)
    {
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'cost_price' => $product->cost_price,
            'sale_price' => $product->sale_price,
            'stock' => $product->stock,
            'minimum_stock' => $product->minimum_stock,
            'unit_id' => $product->unit_id,
            'description' => $product->description,
        ]);
    }

    public function edit(Product $product)
    {
        $units = Unit::orderBy('name')->get();
        return view('products.edit', compact('product', 'units'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();
        $product->update($validated);
        return response()->json(['message' => 'Product updated successfully.'], 200);
    }

    public function destroy(Product $product)
    {
        try{
            $product->delete();
            return response()->json(['message' => 'Product deleted successfully.'], 200);
        }catch(Exception $e){
            return response()->json(['message' => 'Unable to delete product.'], 500);
        }
      
    }


}