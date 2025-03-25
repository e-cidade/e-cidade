<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class LicPregaoCgm extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'public.licpregaocgm';

    protected $primaryKey = 'l46_sequencial';

    public $incrementing = false;

    protected string $sequenceName = '';

    protected $fillable = [
        'l46_sequencial',
        'l46_tipo',
        'l46_numcgm',
        'l46_licpregao',
        'l46_naturezacargo',
        'l46_cargo',
    ];

}
