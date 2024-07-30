<?php

namespace App\Models\Financeiro\Tesouraria;

use App\Traits\LegacyAccount;
use App\Models\LegacyModel;


class Saltes extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'saltes';

    protected $primaryKey = 'k13_conta';

    public $incrementing = false;

    protected $fillable = [
        'k13_conta',
        'k13_reduz', 
        'k13_descr', 
        'k13_saldo',
        'k13_ident', 
        'k13_vlratu', 
        'k13_datvlr', 
        'k13_limite', 
        'k13_dtimplantacao'
    ];

}
