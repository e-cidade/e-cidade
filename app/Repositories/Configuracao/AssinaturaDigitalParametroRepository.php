<?php

namespace App\Repositories\Configuracao;

use App\Repositories\BaseRepository;
use App\Models\Configuracao\AssinaturaDigitalParametro;

class AssinaturaDigitalParametroRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new AssinaturaDigitalParametro();
    }
}
