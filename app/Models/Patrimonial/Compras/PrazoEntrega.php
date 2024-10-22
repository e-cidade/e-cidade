<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class PrazoEntrega extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'compras.prazoentrega';

    protected $primaryKey = 'pc97_sequencial';

    public $incrementing = false;

    protected string $sequenceName = 'pc97_sequencial_seq';

    protected $fillable = [
        'pc97_sequencial',
        'pc97_descricao',
        'pc97_ativo'
    ];

}
