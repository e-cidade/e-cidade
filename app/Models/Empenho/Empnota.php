<?php

namespace App\Models\Empenho;

use App\Models\LegacyModel;

class Empnota extends LegacyModel
{
    /**
     * @var string
     */
    protected $table = 'empenho.empnota';

    /**
     * @var string
     */
    protected $primaryKey = 'e69_codnota';

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
        'e69_codnota',
        'e69_numero',
        'e69_numemp',
        'e69_id_usuario',
        'e69_dtnota',
        'e69_dtrecebe',
        'e69_anousu',
        'e69_tipodocumentosfiscal',
        'e69_dtservidor',
        'e69_dtinclusao',
        'e69_notafiscaleletronica',
        'e69_chaveacesso',
        'e69_nfserie',
        'e69_cgmemitente',
        'e69_id_documento_assinado',
        'e69_node_id_libresing',
    ];
}
