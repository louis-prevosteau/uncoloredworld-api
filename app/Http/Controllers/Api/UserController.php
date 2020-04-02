<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Model\Role;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {
    function index() {
        $users = User::all();
        collect($users)->map(function ($user) {return new UserResource($user);});
        return jsend_success(collect($users)->map(function ($user) {return new UserResource($user);}));
    }

    function create(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:4'
        ]);
        if ($validator->fails()) {
            return jsend_fail([
                "title" => "Creation failed",
                "body" => $validator->errors()
            ], 422);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $user->role()->save(factory(Role::class)->make(['user_id' => $user->id, 'role' => 'joueur']));

        return jsend_success($user);
    }

    function update(Request $request, $id) {
        try {
            $user = User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return jsend_fail([
                "title" => "User not found.",
            ], 422);
        }

        $user->name = $request->get('name', $user->name);
        $user->save();

        return jsend_success(['user'=>$user], 200);
    }

    function show($id) {
        try {
            $user = User::findOrFail($id);
            Log::info(sprintf("dans la requete modif user de nom %s", $user->email ));
        } catch (ModelNotFoundException $e) {
            return jsend_fail([
                "title" => "User not found.",
            ], 422);
        }

        return jsend_success(['message'=>'User updated successfully.','user'=>$user], 200);


    }

    function delete($id) {
        try {
            $user = User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return jsend_fail([
                "title" => "User not found.",
            ], 422);
        }
        $user->delete();
        return jsend_success(['message'=>'User deleted successfully.'], 204);
    }
}
