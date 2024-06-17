<?php

namespace App\Repositories\Tributario\ISSQN\Redesim\Alvara\Filters;

use App\Repositories\Tributario\ISSQN\Redesim\Filters\BaseFilters;

class ObterEmpresasFilter extends BaseFilters
{
    public string $cpfCnpj;

    public function toArray(): array
    {
        $array = parent::toArray();
        if (!empty($this->cpfCnpj)) {
            $array['cpfCnpj'] = $this->cpfCnpj;
        }

        return $array;
    }
}
