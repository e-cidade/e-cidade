<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;

class PcPlanoContratacaoPcPcItem extends LegacyModel {

    public $timestamps = false;

    protected $table = 'compras.pcplanocontratacaopcpcitem';

    protected $primaryKey = 'mpcpc01_codigo';

    public $incrementing = false;

    protected string $sequenceName = 'pcplanocontratacaopcpcitem_mpcpc01_codigo_seq';

    protected $fillable = [
        'mpcpc01_codigo',
        'mpc01_pcplanocontratacao_codigo',
        'mpc02_pcpcitem_codigo',
        'mpcpc01_is_send_pncp',
        'mpcpc01_numero_item',
        'mpcpc01_qtdd',
        'mpcpc01_vlrunit',
        'mpcpc01_vlrtotal',
        'mpcpc01_datap'
    ];
}
