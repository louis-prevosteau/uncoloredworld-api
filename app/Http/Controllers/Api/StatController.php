<?php
/**
 * Created by PhpStorm.
 * User: florentmullet
 * Date: 2020-03-30
 * Time: 15:15
 */

namespace App\Http\Controllers\Api;

use App\Http\Resources\StatistiqueRessource;
use App\Http\Controllers\Controller;
use App\Model\Statistique;

class StatController extends Controller
{
    function index() {
        $statistiques = Statistique::all();
        collect($statistiques)->map(function ($statistique) {return new StatistiqueRessource($statistique);});
        return jsend_success(collect($statistiques)->map(function ($statistique) {return new StatistiqueRessource($statistique);}));
    }

    function update(Request $request, $id) {
        try {
            $statistique = Statistique::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return jsend_fail([
                "title" => "Statistic not found.",
            ], 422);
        }

        //$user->name = $request->get('name', $user->name);
        $statistique->save();

        return jsend_success(['statistique'=>$statistique], 200);
    }

    function show($id) {
        try {
            $statistique = Statistique::findOrFail($id);
            Log::info(sprintf("dans la requete modif user de nom %s", $statistique->score ));
        } catch (ModelNotFoundException $e) {
            return jsend_fail([
                "title" => "score not found.",
            ], 422);
        }

        return jsend_success(['message'=>'statistique updated successfully.','statistique'=>$statistique], 200);


    }

    function delete($id) {
        try {
            $statistique = Statistique::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return jsend_fail([
                "title" => "statistique not found.",
            ], 422);
        }
        $statistique->delete();
        return jsend_success(['message'=>'statistique deleted successfully.'], 204);
    }
}
