<?php

namespace App\Events\PlanoContratacao;

use App\Models\Patrimonial\Compras\PcPlanoContratacao;

class PlanoContratacaoCreated{
    public $item;
    public $anousu;
    public $instit;
    public $id_usuario;
    public $datausu;

    public function __construct(
        PcPlanoContratacao $item,
        string $anousu,
        int $instit,
        int $id_usuario,
        string $datausu
    )
    {
        $this->item = $item;
        $this->anousu = $anousu;
        $this->instit = $instit;
        $this->id_usuario = $id_usuario;
        $this->datausu = $datausu;
    }

}
