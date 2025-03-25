<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class LicComissaoCgm extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'licitacao.liccomissaocgm';

    protected $primaryKey = 'l31_codigo';

    public $incrementing = false;

    protected string $sequenceName = 'liccomissaocgm_l31_codigo_seq';

    protected $fillable = [
        'l31_codigo',
        'l31_liccomissao',
        'l31_numcgm',
        'l31_tipo',
        'l31_licitacao',
    ];

}
