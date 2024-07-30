<?php

namespace App\Services\Financeiro\Tesouraria;
use stdClass;

interface ContaBancariaServiceInterface
{
    /**
     *
     * @param integer $sequencial
     * @return array
     */
   
    public function insertContaBancaria(stdClass $dadoscontabancaria);

    public function updateContaBancaria(stdClass $dadoscontabancaria, stdClass $chavestabelas);

    public function searchSequential();

    public function checkAccount(int $sequencial);

    public function checkGeneral(int $sequencial);
}
