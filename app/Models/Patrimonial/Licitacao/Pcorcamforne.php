<?php

namespace App\Models\Patrimonial\Licitacao;
use App\Models\LegacyModel;
use App\Traits\LegacyAccount;
use Illuminate\Database\Query\Expression;

class Pcorcamforne extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'compras.pcorcamforne';

    protected $primaryKey = 'pc21_orcamforne';

    public $incrementing = false;

    protected $fillable = [
        'pc21_orcamforne',
        'pc21_codorc',
        'pc21_numcgm',
        'pc21_importado',
        'pc21_prazoent',
        'pc21_validadorc'
    ];
}