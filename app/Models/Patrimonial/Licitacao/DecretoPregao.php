<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class DecretoPregao extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'licitacao.decretopregao';

    protected $primaryKey = 'l201_sequencial';

    public $incrementing = false;

    protected $fillable = [
        'l201_sequencial',
        'l201_numdecreto',
        'l201_datadecreto',
        'l201_datapublicacao',
        'l201_tipodecreto',
        'l201_instit',
    ];

}
