<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class LicObras extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'public.licobras';

    protected $primaryKey = 'obr01_sequencial';

    public $incrementing = false;

    protected $fillable = [
        'obr01_sequencial',
        'obr01_licitacao',
        'obr01_dtlancamento',
        'obr01_numeroobra',
        'obr01_linkobra',
        'obr01_dtinicioatividades',
        'obr01_instit',
        'obr01_licitacaosistema',
        'obr01_licitacaolote',
    ];

}
