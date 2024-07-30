<?php

namespace App\Models\Financeiro\Empenho;

use App\Traits\LegacyAccount;
use App\Models\LegacyModel;


class Empagetipo extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'empagetipo';

    protected $primaryKey = 'e83_codtipo';

    protected string $sequenceName = 'empagetipo_e83_codtipo_seq';

    protected $fillable = [
        'e83_codtipo',
        'e83_descr', 
        'e83_conta', 
        'e83_codmod',
        'e83_convenio', 
        'e83_sequencia', 
        'e83_codigocompromisso'
    ];

}
