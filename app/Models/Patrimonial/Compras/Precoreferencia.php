<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Precoreferencia extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'sicom.precoreferencia';

    protected $primaryKey = 'si01_sequencial';

    public $incrementing = false;

    protected $fillable = [
        'si01_sequencial',
        'si01_processocompra',
        'si01_datacotacao',
        'si01_tipoprecoreferencia',
        'si01_justificativa',
        'si01_cotacaoitem',
        'si01_tipocotacao',
        'si01_numcgmcotacao',
        'si01_tipoorcamento',
        'si01_numcgmorcamento',
        'si01_impjustificativa',
        'si01_valorestimado',
        'si01_casasdecimais'
    ];
}
