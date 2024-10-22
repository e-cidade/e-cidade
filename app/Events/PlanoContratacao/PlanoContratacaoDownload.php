<?php

namespace App\Events\PlanoContratacao;

use App\Models\Patrimonial\Compras\PcPlanoContratacao;

class PlanoContratacaoDownload {
    public $item;
    public $justificativa;
    public $anousu;
    public $instit;
    public $id_usuario;
    public $datausu;

    public function __construct(
        PcPlanoContratacao $item
    )
    {
        $this->item = $item;
    }
}
