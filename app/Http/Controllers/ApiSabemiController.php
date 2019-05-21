<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use finfo;
use DateTime;

class ApiSabemiController extends Controller
{
    

    protected function execPost($qfunction, $data, $url = ''){
        $url = $url =='' ? env('API_SABEMI_URL') : $url;
        $url = $url."/".$qfunction."";        
        
        try{
            //Inicializa cURL para uma URL.
            $ch = curl_init($url);
            //Marca que vai enviar por POST(1=SIM).
            curl_setopt($ch, CURLOPT_POST, 1);
            //Passa um json para o campo de envio POST.
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            //Marca como tipo de arquivo enviado json
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            //Marca que vai receber string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            //Inicia a conexão
           $result = json_decode(json_encode(simplexml_load_string(curl_exec($ch))), true);
           
            
            //return $data;
            //Fecha a conexão
            curl_close($ch);
        }catch(Exception $e){
            return $e->getMessage();
        }

        return $result;
    }
    
    public function autenticarUsuario($user, $password){
        return $this->execPost('AutenticarUsuario', [
            'login'=> $user,
            'senha'=> $password,
        ]);
    }
    
    public function recuperaPropostaPorCodigo($token, $proposta){
        $response  = $this->execPost('RecuperaPropostaPorCodigo', [
            'token'=> $token,
            'codigoProposta'=> $proposta,
        ]);
        
        $dataTable = array($response) ;

        return $dataTable;
    }
    
    public function recuperaPropostaPorCPF($token, $cpf){
        $response =  $this->execPost('RecuperaPropostaPorCPF', [
            'token'=> $token,
            'cpf'=> $cpf,
        ]);

        $dataTable = $response;

        return $dataTable;
    }
    
    public function recuperaPropostasPorPeriodo($token, DateTime $dtinit, DateTime $dtend){
        $response  =  $this->execPost('RecuperaPropostasPorPeriodo', [
            'token'=> $token,
            'dataHoraInicial'=> $dtinit->format("d/m/Y H:i:s"),
            'dataHoraFinal'=> $dtend->format("d/m/Y H:i:s")
        ]);
        //return '{"sasasasasasa":"efrefe"}';
        if($response and array_key_exists('PropostaMapaProducao',$response)){
           $dataTable = $response['PropostaMapaProducao'];

        }else{
            $dataTable = "";
        }

        return $dataTable;
    }

    public function recuperaDocumentosPorProposta($token, $proposta){
        $response = $this->execPost('RecuperaDocumentosPorProposta', [
            'token'=> $token,
            'codigoProposta'=> $proposta
        ]);

        if($response and array_key_exists('Documento',$response)){
            $dataTable = $response['Documento'];
        }else{
            $dataTable = "";
        }

        return $dataTable;

    }
    
    public function insertTablePropostas($dataTable){

        foreach($dataTable as $value){
            $item = new SabemiPropostas([
                'bairro_cliente'                =>  (empty( $value['BairroCliente'                   ]) ? '': $value[ 'BairroCliente'                 ]),  
                'cep'                           =>  (empty( $value['Cep'                             ]) ? '': $value[ 'Cep'                           ]),  
                'cidade'                        =>  (empty( $value['Cidade'                          ]) ? '': $value[ 'Cidade'                        ]),  
                'codigo_agencia_credito'        =>  (empty( $value['CodigoAgenciaCredito'            ]) ? '': $value[ 'CodigoAgenciaCredito'          ]),  
                'codigo_agenciador'             =>  (empty( $value['CodigoAgenciador'                ]) ? '': $value[ 'CodigoAgenciador'              ]),  
                'codigo_banco_credito'          =>  (empty( $value['CodigoBancoCredito'              ]) ? '': $value[ 'CodigoBancoCredito'            ]),  
                'codigo_desconto'               =>  (empty( $value['CodigoDesconto'                  ]) ? '': $value[ 'CodigoDesconto'                ]),  
                'codigo_financeira'             =>  (empty( $value['CodigoFinanceira'                ]) ? '': $value[ 'CodigoFinanceira'              ]),  
                'codigo_matricula'              =>  (empty( $value['CodigoMatricula'                 ]) ? '': $value[ 'CodigoMatricula'               ]),  
                'codigo_produto'                =>  (empty( $value['CodigoProduto'                   ]) ? '': $value[ 'CodigoProduto'                 ]),  
                'codigo_proposta'               =>  (empty( $value['CodigoProposta'                  ]) ? '': $value[ 'CodigoProposta'                ]),  
                'codigo_sabemi'                 =>  (empty( $value['CodigoSabemi'                    ]) ? '': $value[ 'CodigoSabemi'                  ]),  
                'codigo_situacao_af'            =>  (empty( $value['CodigoSituacaoAf'                ]) ? '': $value[ 'CodigoSituacaoAf'              ]),  
                'codigo_sub_situacao_af'        =>  (empty( $value['CodigoSubSituacaoAf'             ]) ? '': $value[ 'CodigoSubSituacaoAf'           ]),  
                'codigo_tabela_emprestimo'      =>  (empty( $value['CodigoTabelaEmprestimo'          ]) ? '': $value[ 'CodigoTabelaEmprestimo'        ]),  
                'codigo_usuario_portal'         =>  (empty( $value['CodigoUsuarioPortal'             ]) ? '': $value[ 'CodigoUsuarioPortal'           ]),  
                'complemento_endereco_cliente'  =>  (empty( $value['ComplementoEnderecoCliente'      ]) ? '': $value[ 'ComplementoEnderecoCliente'    ]),  
                'cpf_agenciador'                =>  (empty( $value['CpfAgenciador'                   ]) ? '': $value[ 'CpfAgenciador'                 ]),  
                'cpf_cliente'                   =>  (empty( $value['CpfCliente'                      ]) ? '': $value[ 'CpfCliente'                    ]),  
                'cpf_usuario_portal'            =>  (empty( $value['CpfUsuarioPortal'                ]) ? '': $value[ 'CpfUsuarioPortal'              ]),  
                'data_entrada'                  =>  (empty( $value['DataEntrada'                     ]) ? '': $value[ 'DataEntrada'                   ]),  
                'data_liberacao'                =>  (empty( $value['DataLiberacao'                   ]) ? '': $value[ 'DataLiberacao'                 ]),  
                'data_nascimento_cliente'       =>  (empty( $value['DataNascimentoCliente'           ]) ? '': $value[ 'DataNascimentoCliente'         ]),  
                'descricao_situacao_af'         =>  (empty( $value['DescricaoSituacaoAf'             ]) ? '': $value[ 'DescricaoSituacaoAf'           ]),  
                'descricao_sub_situacao_af'     =>  (empty( $value['DescricaoSubSituacaoAf'          ]) ? '': $value[ 'DescricaoSubSituacaoAf'        ]),  
                'endereco_cliente'              =>  (empty( $value['EnderecoCliente'                 ]) ? '': $value[ 'EnderecoCliente'               ]),  
                'matricula_instituidor'         =>  (empty( $value['MatriculaInstituidor'            ]) ? '': $value[ 'MatriculaInstituidor'          ]),  
                'nome_cliente'                  =>  (empty( $value['NomeCliente'                     ]) ? '': $value[ 'NomeCliente'                   ]),  
                'nome_financeira'               =>  (empty( $value['NomeFinanceira'                  ]) ? '': $value[ 'NomeFinanceira'                ]),  
                'nome_mae_cliente'              =>  (empty( $value['NomeMaeCliente'                  ]) ? '': $value[ 'NomeMaeCliente'                ]),  
                'nome_orgao'                    =>  (empty( $value['NomeOrgao'                       ]) ? '': $value[ 'NomeOrgao'                     ]),  
                'nome_pai_cliente'              =>  (empty( $value['NomePaiCliente'                  ]) ? '': $value[ 'NomePaiCliente'                ]),  
                'numero_conta_credito'          =>  (empty( $value['NumeroContaCredito'              ]) ? '': $value[ 'NumeroContaCredito'            ]),  
                'numero_residencia_cliente'     =>  (empty( $value['NumeroResidenciaCliente'         ]) ? '': $value[ 'NumeroResidenciaCliente'       ]),  
                'percentual_comissao'           =>  (empty( $value['PercentualComissao'              ]) ? '': $value[ 'PercentualComissao'            ]),  
                'quantidade_parcelas'           =>  (empty( $value['QuantidadeParcelas'              ]) ? '': $value[ 'QuantidadeParcelas'            ]),  
                'rg_cliente'                    =>  (empty( $value['RgCliente'                       ]) ? '': $value[ 'RgCliente'                     ]),  
                'telefone_cliente'              =>  (empty( $value['TelefoneCliente'                 ]) ? '': $value[ 'TelefoneCliente'               ]),  
                'tipo_operacao'                 =>  (empty( $value['TipoOperacao'                    ]) ? '': $value[ 'TipoOperacao'                  ]),  
                'uf_abreviada'                  =>  (empty( $value['UfAbreviada'                     ]) ? '': $value[ 'UfAbreviada'                   ]),  
                'valor_af'                      =>  (empty( $value['ValorAf'                         ]) ? '': $value[ 'ValorAf'                       ]),  
                'valor_comissao'                =>  (empty( $value['ValorComissao'                   ]) ? '': $value[ 'ValorComissao'                 ]),  
                'valor_liquido_cliente'         =>  (empty( $value['ValorLiquidoCliente'             ]) ? '': $value[ 'ValorLiquidoCliente'           ]),  
                'valor_prestacao'               =>  (empty( $value['ValorPrestacao'                  ]) ? '': $value[ 'ValorPrestacao'                ])
            ]);
            
            $item->insertIgnore();
        }
    }













}
