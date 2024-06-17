<?php

namespace App\Models\ISSQN;

use App\Models\LegacyModel;

class ParIssqn extends LegacyModel
{
    public $timestamps = false;

    protected $table = 'issqn.parissqn';

    protected $primaryKey = 'q60_receit';

    public $incrementing = false;

    protected $fillable = [
        'q60_receit',
        'q60_tipo',
        'q60_aliq',
        'q60_codvencvar',
        'q60_histsemmov',
        'q60_impcodativ',
        'q60_impobsativ',
        'q60_impdatas',
        'q60_impobsissqn',
        'q60_modalvara',
        'q60_integrasani',
        'q60_campoutilcalc',
        'q60_alvbaixadiv',
        'q60_notaavulsapesjur',
        'q60_notaavulsavias',
        'q60_notaavulsavlrmin',
        'q60_notaavulsamax',
        'q60_notaavulsaultimanota',
        'q60_notaavulsadiasprazo',
        'q60_tipopermalvara',
        'q60_tiponumcertbaixa',
        'q60_templatealvara',
        'q60_dataimpmei',
        'q60_bloqemiscertbaixa',
        'q60_isstipoalvaraper',
        'q60_isstipoalvaraprov',
        'q60_parcelasalvara',
        'q60_templatebaixaalvaranormal',
        'q60_templatebaixaalvaraoficial',
        'q60_aliq_reduzida',
        'q60_codvenc_incentivo',
        'q60_notaavulsalinkautenticacao',
        'q60_tipo_notaavulsa',
    ];
}
