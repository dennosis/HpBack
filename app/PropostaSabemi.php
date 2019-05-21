<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropostaSabemi extends Model
{



    public function user(){
        return $this->hasOne(User::class);
    }

    public function mapaComissao(){
        return $this->hasOne(MapaComissaoSabemi::class);
    }

}
