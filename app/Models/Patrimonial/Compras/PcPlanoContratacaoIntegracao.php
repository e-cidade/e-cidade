<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\Configuracoes\DbDepart;
use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class PcPlanoContratacaoIntegracao extends LegacyModel {
    public $timestamps = false;

    protected $table = 'compras.pcplanocontratacaointegracao';

    protected $primaryKey = 'mpci01_codigo';

    public $incrementing = false;

    protected string $sequenceName = 'pcplanocontratacaointegracao_mpci01_codigo_seq';

    protected $fillable = [
        'mpci01_codigo',
        'mpci01_pcplanocontratacao_codigo',
        'mpci01_sequencial',
        'mpci01_usuario',
        'mpci01_dtlancamento',
        'mpci01_anousu',
        'mpci01_instit',
        'mpci01_ano',
        'mpci01_status',
        'mpci01_response_body',
        'mpci01_send_body',
        'mpci01_response_headers',
        'mpci01_send_headers',
        'mpci01_url',
    ];
}
