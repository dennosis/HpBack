<?php

namespace App\Http\Controllers;

use DateTime;

abstract class MapaComissaoController extends Controller
{
    abstract protected function index();
    abstract protected function propostasByDate(DateTime  $start, DateTime $end);
    abstract protected function syncBaseByDate(DateTime  $start, DateTime $end);


}
