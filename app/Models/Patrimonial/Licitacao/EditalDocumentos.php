<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class EditalDocumentos extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'public.editaldocumentos';

    protected $primaryKey = 'l48_sequencial';

    public $incrementing = false;

    protected $fillable = [
        'l48_sequencial',
        'l48_nomearquivo',
        'l48_tipo',
        'l48_liclicita',
        'l48_arquivo',
    ];

}
