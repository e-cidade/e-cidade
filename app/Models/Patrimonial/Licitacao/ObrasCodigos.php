<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class ObrasCodigos extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'public.obrascodigos';

    protected $primaryKey = 'db151_sequencial';

    public $incrementing = false;

    protected string $sequenceName = '';

    protected $fillable = [
        'db151_sequencial',
        'db151_codigoobra',
        'db151_liclicita',
    ];

}
