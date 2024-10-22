<?php

namespace App\Models\Configuracao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class ProtProcesso extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'protocolo.protprocesso';

    protected $primaryKey = 'p58_codproc';

    public $incrementing = false;

    protected $fillable = [
        'p58_codproc',
        'p58_codigo',
        'p58_dtproc',
        'p58_id_usuario',
        'p58_numcgm',
        'p58_requer',
        'p58_coddepto',
        'p58_codandam',
        'p58_obs',
        'p58_despacho',
        'p58_hora',
        'p58_interno',
        'p58_publico',
        'p58_instit',
        'p58_numero',
        'p58_ano',
        'p58_numeracao',
    ];

}
