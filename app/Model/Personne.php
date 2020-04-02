<?php

namespace App\Model;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Personne extends Model {
    function statistique() {
        return $this->belongsTo(Statistique::class);
    }

    function user() {
        return $this->belongsTo(User::class);
    }
}
