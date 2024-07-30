<?php

namespace App\Models\Financeiro\Contabilidade;

use App\Traits\LegacyAccount;
use App\Models\LegacyModel;


class ConPlanoExe extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'conplanoexe';

    protected $primaryKey = 'c62_anousu, c62_reduz';

    public $incrementing = false;

    protected $fillable = [
        'c62_anousu',
        'c62_reduz',
        'c62_codrec',
        'c62_vlrcre',
        'c62_vlrdeb',
            
    ];

}
