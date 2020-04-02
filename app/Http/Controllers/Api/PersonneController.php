<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationFormRequest;
use App\Http\Resources\PersonneResource;
use App\Model\Personne;
use App\Model\Role;
use App\Model\Statistique;
use App\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PersonneController extends Controller {

    /**
     * PersonneController constructor.
     */
    public function __construct() {
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index() {
        $personnes = Personne::all();
        $data = PersonneResource::collection($personnes);
        return jsend_success($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(RegistrationFormRequest $request) {
        try {
            DB::transaction(function () use ($request) {
                $user = factory(User::class)->create([
                    'name' => $request->prenom . ' ' . $request->nom,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                ]);
                $user->role()->save(factory(Role::class)->make(['user_id' => $user->id, 'role' => 'joueur']));
                $statistique = factory(Statistique::class)->create([
                    'score' => 1,
                    'high_score' => 2,
                    'tirs' => 2,
                    'enemis_tues' => 3,
                    'mort' => 5,
                    'bonus' => 1,
                    'malus' => 3,
                ]);
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
            });
        } catch (Exception $e) {
            return jsend_error($e->getMessage(), $e->getCode());
        }
        $personne = Personne::select(['personnes.*', 'users.id', 'users.email'])->join('users', 'users.id', '=', 'personnes.user_id')->where('users.email', $request->email)->first();
        $data = new PersonneResource($personne);
        return jsend_success(["data" => $data]);
    }

    public function update(Request $request, $id) {
        try {
            $personne = Personne::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return jsend_fail([
                "title" => "Personne not found.",
            ], 422);
        }

        $user = $personne->user;
        if ($request->has('email') && $personne->user->email != $request->email) {
            $validator = Validator::make($request->all(),
                [
                    'nom' => 'required|string',
                    'prenom' => 'required|string',
                    'email' => ['required', 'email', Rule::unique('users')->ignore($user)],
                ]);
            if ($validator->fails()) {
                return jsend_fail([
                    "title" => "Updating failed",
                    "body" => $validator->errors()
                ], 422);
            }
        }
        $path = $personne->avatar;
        if ($request->hasFile('avatar')) {
            Storage::disk('public')->delete($personne->avatar);
            $path = $request->file('avatar')->storeAs('avatars', 'avatar_de_' . $personne->id . '.' . $request->file('avatar')->extension(), 'public');
        }
        $personne->nom = $request->get('nom');
        $personne->prenom = $request->get('prenom');
        $personne->pseudo = $request->get('pseudo');
        $user->name = $request->prenom . ' ' . $request->nom;
        $user->email = $request->get('email');
        if ($request->has('password'))
            $user->password = bcrypt($request->get('password'));
        if ($request->has('actif')) {
            if ($request->get('actif'))
                $personne->actif = 1;
            else
                $personne->actif = 0;
        }
        $personne->avatar = $path;
        $personne->save();
        $user->save();
        $data = new PersonneResource($personne);
        return jsend_success($data);
    }

    public function show($id) {
        try {
            $personne = Personne::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return jsend_fail([
                "title" => "Personne not found.",
            ], 422);
        }
        $data = new PersonneResource($personne);
        return jsend_success( $data);
    }

    public function destroy($id) {
        try {
            $personne = Personne::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return jsend_fail([
                "title" => "Personne not found.",
            ], 422);
        }
        try {
            DB::transaction(function () use ($personne) {
                Storage::disk('public')->delete($personne->avatar);
                $user = $personne->user;
                $user->delete();
            });
        } catch (Exception $e) {
            return jsend_error($e->getMessage(), $e->getCode());
        }
        return jsend_success(['message' => 'Personne deleted successfully.'], 204);
    }


    public function getPersonne() {
        $user = Auth::user();
        $data = new PersonneResource($user->personne);
        return jsend_success($data);
    }

}
