<?php

namespace App\Models\Empenho;

use App\Models\LegacyModel;

class Empanulado extends LegacyModel
{
    /**
     * @var string
     */
    protected $table = 'empenho.empanulado';

    /**
     * @var string
     */
    protected $primaryKey = 'e94_codanu';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'e94_codanu',
        'e94_numemp',
        'e94_valor',
        'e94_saldoant',
        'e94_data',
        'e94_motivo',
        'e94_empanuladotipo',
        'e94_id_usuario',
        'e94_ato',
        'e94_dataato',
        'e94_id_documento_assinado',
        'e94_node_id_libresing',
    ];
}
