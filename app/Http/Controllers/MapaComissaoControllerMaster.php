<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\MapaComissaoController;
use App\Http\Controllers\MapaComissaoSabemiController;
use DateTime;

class MapaComissaoControllerMaster extends Controller
{
    private $mapaComissao;

    public function __construct(){

       //$this->mapaComissao = new MapaComissaoController();
       $this->mapaComissao = new MapaComissaoSabemiController();

	} 

    public function index(Request $request){
        return response()->json($this->mapaComissao->index());
    }

    public function propostasByDate(Request $request){
        $start =  new DateTime(Date( "c", strtotime($request->input('dateStart'))));
        $end =  new DateTime(Date( "c", strtotime($request->input('dateEnd'))));
        return response()->json($this->mapaComissao->propostasByDate($start, $end));
    }

    public function syncBaseByDate(Request $request){
        $start =  new DateTime(Date( "c", strtotime($request->input('dateStart'))));
        $end =  new DateTime(Date( "c", strtotime($request->input('dateEnd'))));
        return response()->json($this->mapaComissao->syncBaseByDate($start, $end));
    }


}
