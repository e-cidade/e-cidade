<?php

namespace App\Models\Caixa;

use App\Models\LegacyModel;

class Slip extends LegacyModel
{
    /**
     * @var string
     */
    protected $table = 'caixa.slip';

    /**
     * @var string
     */
    protected $primaryKey = 'k17_codigo';

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
        'k17_codigo',
        'k17_data',
        'k17_debito',
        'k17_credito',
        'k17_valor',
        'k17_hist',
        'k17_texto',
        'k17_dtaut',
        'k17_autent',
        'k17_instit',
        'k17_dtanu',
        'k17_situacao',
        'k17_tipopagamento',
        'k17_dtestorno',
        'k17_motivoestorno',
        'k17_id_usuario',
        'k17_devolucao',
        'k17_numdocumento',
        'k17_tiposelect',
        'k17_id_documento_assinado',
        'k17_node_id_libresing',
    ];
}
