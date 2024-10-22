<?php
namespace App\Events\PlanoContratacao;

use App\Models\Patrimonial\Compras\PcPlanoContratacao;

class PlanoContratacaoItemRemoved {
    public $item;
    public $justificativa;
    public $itens;
    public $anousu;
    public $instit;
    public $id_usuario;
    public $datausu;

    public function __construct(
        PcPlanoContratacao $item,
        ?string $justificativa,
        array $itens,
        string $anousu,
        int $instit,
        int $id_usuario,
        string $datausu
    )
    {
        $this->item = $item;
        $this->justificativa = $justificativa;
        $this->itens = $itens;
        $this->anousu = $anousu;
        $this->instit = $instit;
        $this->id_usuario = $id_usuario;
        $this->datausu = $datausu;
    }
}
