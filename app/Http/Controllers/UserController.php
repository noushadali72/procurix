<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        $users = User::with('roles')->latest()->paginate(10);
        return view('users.index',compact('users','roles'));
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
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        try{
            User::create($validated);
            return response()->json([
                'success'=>true,
                'message'=>'User created successfully.'
            ],201);
        }catch(Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>'Unable to update the user.',
                'error'=>$e->getMessage()
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();
        try{
            $user->update($validated);
            return redirect()->back()->with('success','User Updated successfully.');

        }catch(Exception $e){
            return redirect()->back()->with('error','Unable to update user.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
