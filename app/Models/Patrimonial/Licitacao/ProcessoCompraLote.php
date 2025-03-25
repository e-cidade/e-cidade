<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class ProcessoCompraLote extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'public.processocompralote';

    protected $primaryKey = 'pc68_sequencial';

    public $incrementing = false;

    protected $fillable = [
        'pc68_sequencial',
        'pc68_nome',
        'pc68_pcproc',
    ];

}
