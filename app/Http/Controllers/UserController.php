<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartialUpdateRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resoxurce.
     */
    public function index()
    {
        $users = User::when(request()->has('username'), function($query){
            $query->where('username', 'like', '%'.request()->input('username').'%')->get();
        })->when(request()->has('email'), function($query){
            $query->where('email', 'like', '%'.request()->input('email').'%')->get();
        })->paginate(request()->per_page);
        
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */

     public function store(StoreUserRequest $request){
        $data = $request->validated();
        $data['password'] = Str::random(8);

        $user = User::create($data);
        
        return response()->json(UserResource::make($user), 201);


    }



    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json(UserResource::make($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $user->update($data);

        return response()->json(UserResource::make($user));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(User $user)
    {
        $user->delete();

        return response()->json(['El usuario fue eliminado exitosamente']);
    }

    public function patch(PartialUpdateRequest $request, User $user)
    {
        $data = $request->validated();
        $user->update($data);

        return response()->json(UserResource::make($user));
    }
}
