<?php

namespace App\Repositories\Tributario\ISSQN\Redesim\ConfirmacaoLeitura\Filters;

use App\Repositories\Tributario\ISSQN\Redesim\Contracts\IFilters;

class ConfirmacaoLeituraBaseFilter implements IFilters
{
    public array $idsConfirmarLeitura;

    public function toArray(): array
    {
        $array = [];

        if (!empty($this->idsConfirmarLeitura)) {
            $array['idsConfirmarLeitura'] = $this->idsConfirmarLeitura;
        }

        return $array;
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}
