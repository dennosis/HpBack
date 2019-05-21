<?php
namespace App\Http\Controllers;


use App\Http\Controllers\MapaComissaoController;
use App\Http\Controllers\ApiSabemiController;
use App\Cliente;
use App\ClienteInfoExtra;
use App\PropostaSabemi;
use App\MapaComissaoSabemi;

use DateTime;

class MapaComissaoSabemiController extends MapaComissaoController
{

    private $api ;
    
    public function __construct(){
        $this->api = new ApiSabemiController();
    }  


    public function index(){
        return ["status" =>  "true"];
    }

    public function propostasByDate(DateTime  $start, DateTime $end){
       //$propostas = PropostaSabemi::where('data_entrada', '>=', $start)->where('data_entrada', '<=', $end)->first(); 
       //if(!empty($propostas)){
       //    $propostas->mapaComissao()->get();
       //}
       return PropostaSabemi::where('data_entrada', '>=', $start)->where('data_entrada', '<=', $end)->get();
           // return "";
    }

    
    public function syncBaseByDate(DateTime  $start, DateTime $end){
        
        $userSabemi = env('API_SABEMI_USER');
        $passSabemi = env('API_SABEMI_PASSWORD');
        $token = $this->api->autenticarUsuario($userSabemi, $passSabemi)[0];
        
        $data = $this->api->recuperaPropostasPorPeriodo($token, $start, $end);
        
        if(!empty($data)){

            if(!array_key_exists(0, $data)){
                $data = array($data);
            }
            
            foreach($data as $proposta){

                $cliente = Cliente::where('cpf', $proposta['CpfCliente'])->first();
                
                if(empty($cliente)){
                    $cliente = new Cliente;
                }
                   
                
                $cliente->cpf = $proposta['CpfCliente'];
                $cliente->rg = $proposta['RgCliente'];
                $cliente->data_nascimento = date("Y-m-d", strtotime(str_replace('/', '-', $proposta['DataNascimentoCliente'])));
                $cliente->nome= $proposta['NomeCliente'];
                $cliente->id_status = 1;
                
                $cliente->save();
                

                $endereco = strtoupper(
                ($proposta['EnderecoCliente']?"rua: ".$proposta['EnderecoCliente']."":"").
                ($proposta['NumeroResidenciaCliente']?", Nº".$proposta['NumeroResidenciaCliente']:"").
                ($proposta['ComplementoEnderecoCliente']?", Complemento: ".$proposta['ComplementoEnderecoCliente']:"").
                ($proposta['BairroCliente']?", Bairro: ".$proposta['BairroCliente']:"").
                ($proposta['Cidade']?", Cidade: ".$proposta['Cidade']:"").
                ($proposta['UfAbreviada']?" - ".$proposta['UfAbreviada']:"").
                ($proposta['Cep']?", cep: ".$proposta['Cep']:"")
                );

                if($endereco > ""){
                    $infoExtra = ClienteInfoExtra::where('id_cliente',$cliente->id)->where('id_tipo_info', 1)->first();
                    
                    if(empty($infoExtra)){
                        $infoExtra = new ClienteInfoExtra;
                    }
                   
                    $infoExtra->dado = $endereco;
                    $infoExtra->id_cliente = $cliente->id;
                    $infoExtra->id_tipo_info = 1;
                    $infoExtra->save();
                    //return  $infoExtra;
                }
                
                if($proposta['TelefoneCliente']){
                    $infoExtra = ClienteInfoExtra::where('id_cliente',$cliente->id)->where('id_tipo_info', 2)->where('dado', intval($proposta['TelefoneCliente']))->first();
                     if(empty($infoExtra)){
                        $infoExtra = new ClienteInfoExtra;
                        $infoExtra->dado = intval($proposta['TelefoneCliente']);
                        $infoExtra->id_cliente = $cliente->id;
                        $infoExtra->id_tipo_info = 2;
                        $infoExtra->save();
                     }
                }

                if($proposta['NomeMaeCliente']){
                    $infoExtra = ClienteInfoExtra::where('id_cliente',$cliente->id)->where('id_tipo_info', 4)->first();
                    if(empty($infoExtra)){
                        $infoExtra = new ClienteInfoExtra;
                    }


                    $infoExtra->dado = strtoupper($proposta['NomeMaeCliente']);
                    $infoExtra->id_cliente = $cliente->id;
                    $infoExtra->id_tipo_info = 4;
                    $infoExtra->save();
                }

                if($proposta['NomePaiCliente']){
                    $infoExtra = ClienteInfoExtra::where('id_cliente',$cliente->id)->where('id_tipo_info', 5)->first();
                     if(empty($infoExtra)){
                        $infoExtra = new ClienteInfoExtra;
                     }
                    
                    $infoExtra->dado = strtoupper($proposta['NomePaiCliente']);
                    $infoExtra->id_cliente = $cliente->id;
                    $infoExtra->id_tipo_info = 5;
                    $infoExtra->save();
                }


                $propostaBase = PropostaSabemi::where('codigo_proposta',$proposta['CodigoProposta'])->first();
                if(empty($propostaBase)){
                    $propostaBase = new PropostaSabemi;
                }

                $propostaBase->codigo_agencia_credito      = $proposta['CodigoAgenciaCredito'   ] ?  $proposta['CodigoAgenciaCredito'   ] : $propostaBase->codigo_agencia_credito       ;
                $propostaBase->codigo_agenciador           = $proposta['CodigoAgenciador'       ] ?  $proposta['CodigoAgenciador'       ] : $propostaBase->codigo_agenciador            ;
                $propostaBase->codigo_banco_credito        = $proposta['CodigoBancoCredito'     ] ?  $proposta['CodigoBancoCredito'     ] : $propostaBase->codigo_banco_credito         ;
                $propostaBase->codigo_desconto             = $proposta['CodigoDesconto'         ] ?  $proposta['CodigoDesconto'         ] : $propostaBase->codigo_desconto              ;
                $propostaBase->codigo_financeira           = $proposta['CodigoFinanceira'       ] ?  $proposta['CodigoFinanceira'       ] : $propostaBase->codigo_financeira            ;
                $propostaBase->codigo_matricula            = $proposta['CodigoMatricula'        ] ?  $proposta['CodigoMatricula'        ] : $propostaBase->codigo_matricula             ;
                $propostaBase->codigo_produto              = $proposta['CodigoProduto'          ] ?  $proposta['CodigoProduto'          ] : $propostaBase->codigo_produto               ;
                $propostaBase->codigo_proposta             = $proposta['CodigoProposta'         ] ?  $proposta['CodigoProposta'         ] : $propostaBase->codigo_proposta              ;
                $propostaBase->codigo_sabemi               = $proposta['CodigoSabemi'           ] ?  $proposta['CodigoSabemi'           ] : $propostaBase->codigo_sabemi                ;
                $propostaBase->codigo_situacao_af          = $proposta['CodigoSituacaoAf'      ]  ?  $proposta['CodigoSituacaoAf'      ] : $propostaBase->codigo_situacao_af           ;
                $propostaBase->codigo_sub_situacao_af      = $proposta['CodigoSubSituacaoAf'    ] ?  $proposta['CodigoSubSituacaoAf'    ] : $propostaBase->codigo_sub_situacao_af       ;
                $propostaBase->codigo_tabela_emprestimo    = $proposta['CodigoTabelaEmprestimo' ] ?  $proposta['CodigoTabelaEmprestimo' ] : $propostaBase->codigo_tabela_emprestimo     ;
                $propostaBase->codigo_usuario_portal       = $proposta['CodigoUsuarioPortal'    ] ?  $proposta['CodigoUsuarioPortal'    ] : $propostaBase->codigo_usuario_portal        ;
                $propostaBase->cpf_agenciador              = $proposta['CpfAgenciador'          ] ?  $proposta['CpfAgenciador'          ] : $propostaBase->cpf_agenciador               ;
                $propostaBase->cpf_usuario_portal          = $proposta['CpfUsuarioPortal'       ] ?  $proposta['CpfUsuarioPortal'       ] : $propostaBase->cpf_usuario_portal           ; 
                $propostaBase->descricao_situacao_af       = $proposta['DescricaoSituacaoAf'    ] ?  $proposta['DescricaoSituacaoAf'    ] : $propostaBase->descricao_situacao_af        ;
                $propostaBase->descricao_sub_situacao_af   = $proposta['DescricaoSubSituacaoAf' ] ?  $proposta['DescricaoSubSituacaoAf' ] : $propostaBase->descricao_sub_situacao_af    ;
                $propostaBase->matricula_instituidor       = $proposta['MatriculaInstituidor'   ] ?  $proposta['MatriculaInstituidor'   ] : $propostaBase->matricula_instituidor        ;
                $propostaBase->nome_financeira             = $proposta['NomeFinanceira'         ] ?  $proposta['NomeFinanceira'         ] : $propostaBase->nome_financeira              ;
                $propostaBase->nome_orgao                  = $proposta['NomeOrgao'              ] ?  $proposta['NomeOrgao'              ] : $propostaBase->nome_orgao                   ;
                $propostaBase->numero_conta_credito        = $proposta['NumeroContaCredito'     ] ?  $proposta['NumeroContaCredito'     ] : $propostaBase->numero_conta_credito         ;
                $propostaBase->percentual_comissao         = $proposta['PercentualComissao'     ] ?  $proposta['PercentualComissao'     ] : $propostaBase->percentual_comissao          ;
                $propostaBase->quantidade_parcelas         = $proposta['QuantidadeParcelas'     ] ?  $proposta['QuantidadeParcelas'     ] : $propostaBase->quantidade_parcelas          ;
                $propostaBase->tipo_operacao               = $proposta['TipoOperacao'           ] ?  $proposta['TipoOperacao'           ] : $propostaBase->tipo_operacao                ;
                $propostaBase->valor_af                    = $proposta['ValorAf'                ] ?  $proposta['ValorAf'                ] : $propostaBase->valor_af                     ;
                $propostaBase->valor_comissao              = $proposta['ValorComissao'          ] ?  $proposta['ValorComissao'          ] : $propostaBase->valor_comissao               ;
                $propostaBase->valor_liquido_cliente       = $proposta['ValorLiquidoCliente'    ] ?  $proposta['ValorLiquidoCliente'    ] : $propostaBase->valor_liquido_cliente        ;
                $propostaBase->valor_prestacao             = $proposta['ValorPrestacao'         ] ?  $proposta['ValorPrestacao'         ] : $propostaBase->valor_prestacao              ;

                $propostaBase->data_entrada                = $proposta['DataEntrada'            ] ? date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $proposta['DataEntrada']))) : $propostaBase->data_entrada  ;
                $propostaBase->data_liberacao              = $proposta['DataLiberacao'          ] ? date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $proposta['DataLiberacao']))) : $propostaBase->data_liberacao ;  
                
                $propostaBase->id_cliente =  $cliente->id;

                $propostaBase->save();

               // $mapacomissao = MapaComissaoSabemi::where('id_proposta',$propostaBase->id)->first();
                //if(empty($propostaBase)){
                 //   $mapacomissao = new MapaComissaoSabemi;
                //}





            }
        }
        

        return $data;
    }


}
