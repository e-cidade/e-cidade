<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Models\Patrimonial\Compras\PcPlanoContratacaoPcPcItem;

class PcpcItem extends LegacyModel {

    public $timestamps = false;

    protected $table = 'compras.pcpcitem';

    protected $primaryKey = 'mpc02_codigo';

    public $incrementing = false;

    protected string $sequenceName = 'mpc02_codigo_seq';

    protected $fillable = [
        'mpc02_codigo',
        'mpc02_codmater',
        'mpc02_categoria',
        'mpc02_un',
        'mpc02_depto',
        'mpc02_catalogo',
        'mpc02_tproduto',
        'mpc02_subgrupo',
    ];

    public function PcPlanoContratacaoPcPcItens(){
        return $this->hasMany(PcPlanoContratacaoPcPcItem::class, 'mpc02_pcpcitem_codigo', 'mpc02_codigo');
    }

}
