<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClienteObs extends Model
{
    public function user(){
        return $this->belongsToMany(User::class);
    }
}
