<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class LicLicitaSituacao extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'licitacao.liclicitasituacao';

    protected $primaryKey = 'l11_sequencial';

    public $incrementing = false;

    protected string $sequenceName = 'liclicitasituacao_l11_sequencial_seq';

    protected $fillable = [
        'l11_sequencial',
        'l11_id_usuario',
        'l11_licsituacao',
        'l11_liclicita',
        'l11_obs',
        'l11_data',
        'l11_hora',
    ];

}
