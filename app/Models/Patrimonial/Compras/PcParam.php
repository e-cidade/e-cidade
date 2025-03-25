<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class PcParam extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'compras.pcparam';

    protected $primaryKey = 'pc30_instit';

    public $incrementing = false;

    protected $fillable = [
        'pc30_instit',
        'pc30_horas',
        'pc30_dias',
        'pc30_tipcom',
        'pc30_unid',
        'pc30_obrigajust',
        'pc30_obrigamat',
        'pc30_gerareserva',
        'pc30_liberaitem',
        'pc30_liberado',
        'pc30_seltipo',
        'pc30_sugforn',
        'pc30_mincar',
        'pc30_permsemdotac',
        'pc30_passadepart',
        'pc30_digval',
        'pc30_libdotac',
        'pc30_tipoemiss',
        'pc30_comsaldo',
        'pc30_contrandsol',
        'pc30_tipoprocsol',
        'pc30_itenslibaut',
        'pc30_comobs',
        'pc30_ultdotac',
        'pc30_fornecdeb',
        'pc30_emiteemail',
        'pc30_modeloorc',
        'pc30_modeloordemcompra',
        'pc30_modeloorcsol',
        'pc30_dotacaopordepartamento',
        'pc30_valoraproximadoautomatico',
        'pc30_basesolicitacao',
        'pc30_baseprocessocompras',
        'pc30_baseempenhos',
        'pc30_maximodiasorcamento',
        'pc30_validadepadraocertificado',
        'pc30_tipovalidade',
        'pc30_importaresumoemp',
        'pc30_diasdebitosvencidos',
        'pc30_notificaemail',
        'pc30_notificacarta',
        'pc30_permitirgerarnotifdebitos',
        'pc30_consultarelatoriodepartamento',
        'pc30_emitedpsolicitante',
        'pc30_emitedpcompras',
        'pc30_liboccontrato',
        'pc30_prazoent',
    ];

}
