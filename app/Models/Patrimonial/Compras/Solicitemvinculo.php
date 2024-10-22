<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Solicitemvinculo extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'compras.solicitemvinculo';

    protected $primaryKey = 'pc55_sequencial';

    protected string $sequenceName = 'solicitemvinculo_pc55_sequencial_seq';

    public $incrementing = false;

    protected $fillable = [
        'pc55_sequencial',
        'pc55_solicitempai',
        'pc55_solicitemfilho'
    ];
}
