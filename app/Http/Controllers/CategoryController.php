<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::withCount([
            'products',
            'rawMaterials',
            ])->paginate(10);

        if(!$request->ajax()){
            return view('categories.index',compact('categories'));
        }
        return response()->json([
            'success'=>true,
            'categories'=>$categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();
        try{

         $category = Category::create($validated);

        return response()->json([
            'success'=>true,
            'category'=>$category,
            'message'=>'Category created successfully!'
        ],201);

        }catch(Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>'Unable to create category!',
                'error'=>$e->getMessage()
            ],500);
        }
       
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validated = $request->validated();
        try{
            $category->update($validated);
            return response()->json([
                'success'=>true,
                'message'=>'Category updated successfully!'
            ],200);
        }catch(Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>'Unable to update the category!',
                'error'=>$e->getMessage()
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try{
            $category->delete();
            return response()->json([
                'success'=>true,
                'message'=>'Category Deleted successfully!'
            ],200);
        }catch(Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>'Unable to delete Category!',
                'error'=>$e->getMessage()
            ],500);
        }
    }
}
