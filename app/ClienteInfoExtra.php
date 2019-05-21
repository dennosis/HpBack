<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClienteInfoExtra extends Model
{
    

    public function tipoInfo(){
        return $this->hasOne(ClienteTipoInfo::class);
    }


}
