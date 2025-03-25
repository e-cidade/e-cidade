<?php

namespace App\Models\Patrimonial\Orcamento;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class OrcElemento extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'orcamento.orcelemento';

    protected $primaryKey = 'o56_anousu';

    public $incrementing = false;

    protected $fillable = [
        'o56_codele',
        'o56_anousu',
        'o56_elemento',
        'o56_descr',
        'o56_finali',
        'o56_orcado',
    ];

}
