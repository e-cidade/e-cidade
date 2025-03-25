<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Credenciamento extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'public.credenciamento';

    protected $primaryKey = 'l205_sequencial';

    public $incrementing = false;

    protected string $sequenceName = '';

    protected $fillable = [
        'l205_sequencial',
        'l205_fornecedor',
        'l205_datacred',
        'l205_inscriestadual',
        'l205_item',
        'l205_licitacao',
        'l205_datacreditem',
    ];

}
