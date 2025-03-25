<?php

namespace App\Models\Contabilidade;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class PrevisaoOperacaoCredito extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'prevoperacaocredito';

    public $incrementing = false;

    protected $primaryKey = 'c242_operacaocredito, c242_fonte, c242_anousu';

    /**
     * @var array
     */
    protected $fillable = [
        "c242_operacaocredito",
        "c242_fonte",
        "c242_anousu",
        "c242_vlprevisto"
    ];
}
