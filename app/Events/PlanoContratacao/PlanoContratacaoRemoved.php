<?php

namespace App\Events\PlanoContratacao;

use App\Models\Patrimonial\Compras\PcPlanoContratacao;

class PlanoContratacaoRemoved {
    public $item;
    public $justificativa;
    public $anousu;
    public $instit;
    public $id_usuario;
    public $datausu;

    public function __construct(
        PcPlanoContratacao $item,
        string $justificativa,
        string $anousu,
        int $instit,
        int $id_usuario,
        string $datausu
    )
    {
        $this->item = $item;
        $this->justificativa = $justificativa;
        $this->anousu = $anousu;
        $this->instit = $instit;
        $this->id_usuario = $id_usuario;
        $this->datausu = $datausu;
    }
}
