<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();
        $roles = Role::with('permissions')->latest()->paginate(10);
        return view('roles.index',compact('roles','permissions'));
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
    public function store(StoreRoleRequest $request)
    {
        $validated = $request->validated();
        try{
            Role::create($validated);
            return response()->json([
                'success'=>true,
                'message'=>'Role created successfully.'
            ],200);
        }catch(Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>'Unable to create role',
                'error'=>$e
            ],500);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $validated = $request->validated();
        try{
            $role->update($validated);
            return response()->json([
                'success'=>true,
                'message'=>'Role Updated successfully.'
            ],200);
        }catch(Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>'Unable to update role.',
                'error'=>$e
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try{
            $role->delete();
            return response()->json([
                'success'=>true,
                'message'=>'Role deleted successfully.'
            ],200);
        }catch(Exception $e){
            return response()->json([
                'success'=>true,
                'message'=>'Unable to delete the role, May be associated with users.',
                'error'=>$e->getMessage()
            ],500);
        }
    }
}
