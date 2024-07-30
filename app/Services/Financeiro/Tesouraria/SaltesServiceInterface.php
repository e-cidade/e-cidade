<?php

namespace App\Services\Financeiro\Tesouraria;
use Illuminate\Database\Eloquent\Collection;
use stdClass;

interface SaltesServiceInterface
{

    public function insertSaltes(stdClass $saltes);

}
