<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class LicLicitaParam extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'licitacao.licitaparam';

    protected $primaryKey = 'l12_instit';

    public $incrementing = false;

    protected $fillable = [
        'l12_instit',
        'l12_escolherprocesso',
        'l12_escolheprotocolo',
        'l12_qtdediasliberacaoweb',
        'l12_tipoliberacaoweb',
        'l12_usuarioadjundica',
        'l12_validacadfornecedor',
        'l12_pncp',
        'l12_numeracaomanual',
        'l12_loginpncp',
        'l12_passwordpncp',
        'l12_validafornecedor_emailtel',
        'l12_keyapipcp',
        'l12_acessoapipcp',
        'l12_adjudicarprocesso',
        'l12_unidadecompradora'
    ];

}
