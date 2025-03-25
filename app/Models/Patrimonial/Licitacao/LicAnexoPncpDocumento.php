<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class LicAnexoPncpDocumento extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'public.licanexopncpdocumento';

    protected $primaryKey = 'l216_sequencial';

    public $incrementing = false;

    protected $fillable = [
        'l216_sequencial',
        'l216_licanexospncp',
        'l216_documento',
        'l216_nomedocumento',
        'l216_tipoanexo',
    ];

}
