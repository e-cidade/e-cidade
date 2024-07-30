<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Pcproc extends LegacyModel
{
    use LegacyAccount;


    public $timestamps = false;

    protected $table = 'compras.pcproc';

    protected $primaryKey = 'pc80_codproc';

    public $incrementing = false;

    protected $fillable = [
        'pc80_codproc',
        'pc80_data',
        'pc80_usuario',
        'pc80_depto',
        'pc80_resumo',
        'pc80_situacao',
        'pc80_tipoprocesso',
        'pc80_criterioadjudicacao',
        'pc80_numdispensa',
        'pc80_dispvalor',
        'pc80_orcsigiloso',
        'pc80_subcontratacao',
        'pc80_dadoscomplementares',
        'pc80_amparolegal',
        'pc80_categoriaprocesso',
        'pc80_modalidadecontratacao',
        'pc80_criteriojulgamento'
    ];

}
