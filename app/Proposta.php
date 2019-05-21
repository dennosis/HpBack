<?php

namespace App;
use DateTime;
use Illuminate\Database\Eloquent\Model;

abstract class Proposta extends Model
{
    abstract function getPropostaById($id);
    abstract function getPropostasByDateCreate(DateTime  $start, DateTime $end);
    abstract function getPropostasByDateUpdate(DateTime  $start, DateTime $end);
    abstract function getPropostasByCPF($cpf);
}
 