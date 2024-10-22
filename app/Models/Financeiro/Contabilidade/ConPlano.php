<?php

namespace App\Models\Financeiro\Contabilidade;

use App\Traits\LegacyAccount;
use App\Models\LegacyModel;


class ConPlano extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'contabilidade.conplano';

    protected $primaryKey = 'c60_codcon';

    public $incrementing = false;

    protected $fillable = [
        'c60_codcon',
        'c60_anousu',
        'c60_estrut',
        'c60_descr',
        'c60_finali',
        'c60_codsis',
        'c60_codcla',
        'c60_consistemaconta',
        'c60_identificadorfinanceiro',
        'c60_naturezasaldo',
        'c60_funcao',
        'c60_tipolancamento',
        'c60_subtipolancamento',
        'c60_desdobramneto',
        'c60_nregobrig',
        'c60_cgmpessoa',
        'c60_naturezadareceita',
        'c60_infcompmsc'
    ];

}
