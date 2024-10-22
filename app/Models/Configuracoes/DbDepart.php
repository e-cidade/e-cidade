<?php

namespace App\Models\Configuracoes;

use App\Models\LegacyModel;

class DbDepart extends LegacyModel {
    public $timestamps = false;

    protected $table = 'configuracoes.db_depart';

    protected $primaryKey = 'coddepto';

    public $incrementing = false;

    protected $fillable = [
        'coddepto',
        'descrdepto',
        'nomeresponsavel',
        'emailresponsavel',
        'limite',
        'fonedepto',
        'emaildepto',
        'faxdepto',
        'ramaldepto',
        'instit',
        'numcgm',
    ];
}
