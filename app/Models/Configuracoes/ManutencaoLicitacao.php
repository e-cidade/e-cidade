<?php

namespace App\Models\Configuracoes;

use App\Models\LegacyModel;

class ManutencaoLicitacao extends LegacyModel {
    public $timestamps = false;

    protected $table = 'configuracoes.manutencaolicitacao';

    protected $primaryKey = 'manutlic_sequencial';

    public $incrementing = false;

    protected $fillable = [
        'manutlic_sequencial',
        'manutlic_codunidsubanterior',
        'manutlic_licitacao',
        'manutlic_editalant',
    ];
}
