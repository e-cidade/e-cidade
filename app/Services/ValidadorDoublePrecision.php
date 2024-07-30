<?php

namespace App\Services;

class ValidadorDoublePrecision
{
    public function execute($valor)
    {
        return preg_match('/^\d+([.,]\d{1,2})?$/',$valor);
    }
}
