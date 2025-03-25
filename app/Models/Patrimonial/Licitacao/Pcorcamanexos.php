<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Pcorcamanexos extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'licitacao.pcorcamanexos';

    protected $primaryKey = 'pc98_sequencial';

    public $incrementing = false;


    protected $fillable = [
        'pc98_sequencial',
        'pc98_codorc',
        'pc98_nomearquivo',
        'pc98_anexo'
    ];

}
