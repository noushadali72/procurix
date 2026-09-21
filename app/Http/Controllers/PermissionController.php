<?php

namespace App\Http\Controllers;

use App\Http\Requests\Permission\StorePermissionRequest;
use App\Models\Permission;
use Exception;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::latest()->paginate(10);
        return view('permissions.index',compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {

        $validated = $request->validated();
        try{
            Permission::create($validated);
            return response()->json([
                'success'=>true,
                'message'=>'Permission created successfully.'
            ],200);
        }catch(Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>'Unable to create permission.',
                'error'=>$e
            ],500);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validated();
        try{
            $permission->update($validated);
            return response()->json([
                'success'=>true,
                'message'=>'Permission Updated successfully.'
            ],200);
        }catch(Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>'Unable to update permission.',
                'error'=>$e
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        try{
            $permission->delete();
            return response()->json([
                'success'=>true,
                'message'=>'Permission deleted successfully.'
            ],200);
        }catch(Exception $e){
            return response()->json([
                'success'=>true,
                'message'=>'Unable to delete the permission, May be associated with roles.',
                'error'=>$e->getMessage()
            ],500);
        }
        
    }
}
