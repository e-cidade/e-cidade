<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Solicitemregistropreco extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'compras.solicitemregistropreco';

    protected $primaryKey = 'pc57_sequencial';

    protected string $sequenceName = 'solicitemregistropreco_pc57_sequencial_seq';

    public $incrementing = false;

    protected $fillable = [
        'pc57_sequencial',
        'pc57_solicitem',
        'pc57_quantmin',
        'pc57_quantmax',
        'pc57_itemorigem',
        'pc57_ativo',
        'pc57_ativo',
        'pc57_quantidadeexecedente'
    ];
}
