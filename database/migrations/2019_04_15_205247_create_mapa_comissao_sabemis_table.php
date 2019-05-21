<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMapaComissaoSabemisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mapa_comissao_sabemis', function (Blueprint $table) {
            $table->increments('id');
            $table->date('data_recepcao_fisico');
            $table->date('data_recepcao_fisico_sabemi');
            $table->date('data_pagamento_comissao');
            $table->date('data_comissao_recebida');
            
            $table->integer('id_proposta')->unsigned();
            $table->foreign('id_proposta')->references('id')->on('proposta_sabemis');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mapa_comissao_sabemis');
    }
}
