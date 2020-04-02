<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PersonneResource;
use App\Model\Personne;
use App\Model\Role;
use App\Model\Statistique;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller {
    public $successStatus = 200;
    private $success;


    public function register(Request $request) {
        $validator = Validator::make($request->all(),
            [
                'nom' => 'required',
                'prenom' => 'required',
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required',
            ]);
        if ($validator->fails()) {
            return jsend_fail([
                "title" => "Registration failed",
                "body" => $validator->errors()
            ], 422);
        }
        $this->success = [];
        try {
            DB::transaction(function () use ($request){
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $statistique = factory(Statistique::class)->create([
                'score' => 1,
                'high_score' => 2,
                'tirs' => 2,
                'enemis_tues' => 3,
                'mort' => 5,
                'bonus' => 1,
                'malus' => 3,
            ]);
            $user->role()->save(factory(Role::class)->make(['user_id' => $user->id, 'role' => 'joueur']));
            $personne = factory(Personne::class)->create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'pseudo' => $request->pseudo,
                'actif' => $request->get('actif', true),
                'avatar' => 'avatars/anonymous.png',
                /*'score' => 10,
                'high_score' => 20,
                'tirs' => 2,
                'enemis_tues' => 3,
                'mort' => 5,
                'bonus' => 1,
                'malus' => 3,*/
                'user_id' => $user->id,
                'statistique_id' => $statistique->id,
            ]);
            $path = null;
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->storeAs('avatars', 'avatar_de_' . $personne->id . '.' . $request->file('avatar')->extension(), 'public');
                $personne->avatar = $path;
                $personne->save();
            }
            $this->success['personne'] = new PersonneResource($user->personne);
            $this->success['token'] = $user->createToken('Taches-api', [$user->role()->first()->role])->accessToken;
        });
        } catch (Exception $e) {
            return jsend_error($e->getMessage(), $e->getCode());
        }
        return jsend_success($this->success);
    }

    public function login() {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $userRole = $user->role()->first();
            if ($userRole) {
                $this->scope = $userRole->role;
            }
            $success['personne'] = new PersonneResource($user->personne);
            $success['token'] = $user->createToken('Taches-api', [$this->scope])->accessToken;
            return jsend_success($success);
        } else {
            return jsend_fail([
                "title" => "Unauthorised",
                "body" => "Nom d'utilisateur et/ou mot de passe incorrect"
            ], 401);
        }
    }

    public function logout(Request $request) {
        if (Auth::check()) {
            $token = Auth::user()->token();
            $token->revoke();
            return jsend_success(['successfully logout'], 201);
        }
        return jsend_fail([
            "title" => "Unauthorised",
            "body" => "Token invalid"
        ], 401);
    }

    public function getUser() {
        $user = Auth::user();
        return jsend_success(["user" => $user], 200);
    }
}
