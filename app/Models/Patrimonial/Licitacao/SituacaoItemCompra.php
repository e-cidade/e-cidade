<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class SituacaoItemCompra extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'public.situacaoitemcompra';

    protected $primaryKey = 'l218_codigo';

    public $incrementing = false;

    protected string $sequenceName = '';

    protected $fillable = [
        'l218_codigo',
        'l218_codigolicitacao',
        'l218_pcorcamitemlic',
        'l218_liclicitem',
        'l218_pcmater',
        'l218_motivoanulacao',
    ];

}
