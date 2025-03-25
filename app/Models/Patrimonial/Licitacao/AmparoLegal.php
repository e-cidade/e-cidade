<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class AmparoLegal extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'public.amparolegal';

    protected $primaryKey = 'l212_codigo';

    public $incrementing = false;

    protected $fillable = [
        'l212_codigo',
        'l212_lei',
    ];

}
