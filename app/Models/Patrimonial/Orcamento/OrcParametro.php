<?php

namespace App\Models\Patrimonial\Orcamento;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class OrcParametro extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'orcamento.orcparametro';

    protected $primaryKey = 'o50_anousu';

    public $incrementing = false;

    protected $fillable = [
        'o50_anousu',
        'o50_coddot',
        'o50_subelem',
        'o50_programa',
        'o50_estrutdespesa',
        'o50_estrutelemento',
        'o50_estrutreceita',
        'o50_tipoproj',
        'o50_utilizaopacto',
        'o50_utilizapacto',
        'o50_liberadecimalppa',
        'o50_estruturarecurso',
        'o50_estruturacp',
        'o50_motivosuplementacao',
        'o50_controlafote1017',
        'o50_controlafote10011006',
        'o50_controlaexcessoarrecadacao',
    ];

}
