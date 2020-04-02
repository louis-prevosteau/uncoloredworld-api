<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Statistique extends Model {
    protected $table = 'statistiques';
    function personne() {
        return $this->hasOne(Personne::class);
    }
}
