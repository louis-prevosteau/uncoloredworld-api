<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatistiqueRessource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {
        return [
            'score' => $this->score,
            'high_score' => $this->high_score,
            'tirs' => $this->tirs,
            'ennemis_tues' => $this->ennemis_tues,
            'morts' => $this->morts,
            'bonus' => $this->bonus,
            'malus' => $this->malus,
        ];
    }
}
