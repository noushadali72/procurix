<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {

        $categories = Category::all();
        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');

        $products = Product::query()->when($request->filled('searchQuery'),  function ($query) use ($request) {
            $searchQuery = $request->input('searchQuery');
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', "LIKE", "%{$searchQuery}%")->orWhere('description', "LIKE", "%{$searchQuery}%");
            });
        })
            ->when($request->filled('category_id'), function ($q) use ($request) {
                $category_id = $request->input('category_id');
                $q->where('category_id', $category_id);
            })
            ->when($min_price && $max_price, function ($q) use ($min_price, $max_price) {
                $q->whereBetween('sale_price', [$min_price, $max_price]);
            })
            ->when(!$min_price && $max_price, function ($q) use ($max_price) {
                $q->where('sale_price', "<=", $max_price);
            })
            ->when(!$max_price && $min_price, function ($q) use ($min_price) {
                $q->where('sale_price', ">", $min_price);
            })

            ->with(['unit', 'category'])
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        if (!$request->ajax()) {
            return view('products.index', compact('products', 'categories'));
        }

        return response()->json([
            'success' => true,
            'message' => 'Products fetched successfully.',
            'products' => $products
        ], 200);
    }

    public function create()
    {
        $units = Unit::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('units', 'categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();
        $path = null;
        try {

        // If Product has image
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->store('images/products', 'public');
                $validated['image_path'] = $path;
                unset($validated['image']);
            }

            Product::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully.'
            ], 201);

        } catch (Exception $e) {

        // deleting saved image if any error
            if($path){
                Storage::disk('public')->delete($path);
            }

            return response()->json([
                'success' => false,
                'message' => 'Unable to create the product.',
                'error' => $e->getMessage()
            ], 500);
        }
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
            'category_id' => $product->category_id,
            'description' => $product->description,
            'image_path'=>$product->image_path
        ], 200);
    }

    public function edit(Product $product)
    {
        $units = Unit::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $product->load(['category', 'unit']);

        return view('products.edit', compact('product', 'units', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();
        $existingImage = $product->image_path;
        $newImage = null;
        try {

            if ($request->hasFile('image')) {
                $newImage = $request->file('image')->store('images/products', 'public');
                $validated['image_path'] = $newImage;
                unset($validated['image']);
            }
            $product->update($validated);

            // Delete old image if exists
            if($newImage && $existingImage){
                    Storage::disk('public')->delete($existingImage);
                }
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully.'
            ], 200);
        } catch (Exception $e) {
            //delete new image if unable to update
            if($newImage){
                Storage::disk('public')->delete($newImage);
            }
            return response()->json([
                'success' => false,
                'message' => 'Unable to update the product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Product $product)
    {
        try {
            $existingImage = $product->image_path;
            $product->delete();
            if($existingImage){
                Storage::disk('public')->delete($existingImage);
            }
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to delete product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
