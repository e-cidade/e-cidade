<?php

namespace App\Models\Financeiro\Contabilidade;

use App\Traits\LegacyAccount;
use App\Models\LegacyModel;


class ConPlanoConta extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'conplanoconta';

    protected $primaryKey = 'c63_codcon, c63_anousu';

    public $incrementing = false;

    protected $fillable = [
        'c63_codcon',
        'c63_anousu',
        'c63_banco',
        'c63_agencia',
        'c63_conta',
        'c63_dvconta',
        'c63_dvagencia',
        'c63_identificador',
        'c63_codigooperacao',
        'c63_funcao',
        'c63_tipoconta',
       
    ];

}
