<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PersonneResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {
        if ($this->avatar == null)
            $path = 'avatars/anonymous.png';
        else
            $path = $this->avatar;
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'pseudo' => $this->pseudo,
            'actif' => $this->actif,
            'avatar'  => url(Storage::url($path)),
            /*'score' => $this->score,
            'high_score' => $this->high_score,
            'tirs' => $this->tirs,
            'ennemis_tues' => $this->ennemis_tues,
            'morts' => $this->morts,
            'bonus' => $this->bonus,
            'malus' => $this->malus,*/
            'user' => new UserResource($this->user),
            'statistique' => new StatistiqueRessource($this->statistique),
        ];
    }
}
