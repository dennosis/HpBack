<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
  

    public function status(){
        return $this->hasOne(ClienteStatus::class);
    }
    public function infoExtras(){
        return $this->hasMany(ClienteInfoExtras::class);
    }
    public function clienteObs(){
        return $this->hasMany(ClienteObs::class);
    }
    /*
    public function PropostaSabemi(){
        return $this->hasMany(PropostaSabemi::class);
    }
*/


}
