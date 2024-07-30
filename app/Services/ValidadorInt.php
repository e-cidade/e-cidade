<?php

namespace App\Services;

class ValidadorInt
{
    public function execute($codigoabastecimento)
    {
        return is_numeric($codigoabastecimento);
    }

}
