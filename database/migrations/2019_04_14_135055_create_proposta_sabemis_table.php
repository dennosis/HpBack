<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropostaSabemisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposta_sabemis', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger('codigo_agencia_credito'        )->unsigned()->nullable($value = true);  
            $table->bigInteger('codigo_agenciador'             )->unsigned()->nullable($value = true);  
            $table->bigInteger('codigo_banco_credito'          )->unsigned()->nullable($value = true);  
            $table->bigInteger('codigo_desconto'               )->unsigned()->nullable($value = true);  
            $table->bigInteger('codigo_financeira'             )->unsigned()->nullable($value = true);  
            $table->bigInteger('codigo_matricula'              )->unsigned()->nullable($value = true);  
            $table->bigInteger('codigo_produto'                )->unsigned()->nullable($value = true);  
            $table->bigInteger('codigo_proposta'               )->unsigned()->nullable($value = true);  
            $table->bigInteger('codigo_sabemi'                 )->unsigned()->nullable($value = true);  
            $table->bigInteger('codigo_situacao_af'            )->unsigned()->nullable($value = true);  
            $table->bigInteger('codigo_sub_situacao_af'        )->unsigned()->nullable($value = true);  
            $table->bigInteger('codigo_tabela_emprestimo'      )->unsigned()->nullable($value = true);  
            $table->bigInteger('codigo_usuario_portal'         )->unsigned()->nullable($value = true);  
            $table->bigInteger('cpf_agenciador'                )->unsigned()->nullable($value = true);  
            $table->bigInteger('cpf_usuario_portal'            )->unsigned()->nullable($value = true);  
            $table->dateTime('data_entrada'                     )           ->nullable($value = true);  
            $table->dateTime('data_liberacao'                   )           ->nullable($value = true);  
            $table->string('descricao_situacao_af'          )               ->nullable($value = true);  
            $table->string('descricao_sub_situacao_af'   ,50)               ->nullable($value = true);  
            $table->bigInteger('matricula_instituidor'         )            ->nullable($value = true);  
            $table->string('nome_financeira'             ,50)               ->nullable($value = true);  
            $table->string('nome_orgao'                  ,50)               ->nullable($value = true);  
            $table->string('numero_conta_credito'           ,20)->unsigned()->nullable($value = true);  
            $table->string('percentual_comissao'         , 5, 4)            ->nullable($value = true);  
            $table->integer('quantidade_parcelas'           )>unsigned()     ->nullable($value = true);  
            $table->string('tipo_operacao'               ,50)               ->nullable($value = true);  
            $table->string('valor_af'                    ,15, 4)            ->nullable($value = true);  
            $table->string('valor_comissao'              ,15, 4)            ->nullable($value = true);  
            $table->string('valor_liquido_cliente'       ,15, 4)            ->nullable($value = true);  
            $table->double('valor_prestacao'             ,15, 4)            ->nullable($value = true); 
            
            $table->integer('id_cliente')->unsigned();
            $table->foreign('id_cliente')->references('id')->on('clientes');
            
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
        Schema::dropIfExists('proposta_sabemis');
    }
}
