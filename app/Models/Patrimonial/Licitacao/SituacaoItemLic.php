<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class SituacaoItemLic extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'public.situacaoitemlic';

    protected $primaryKey = '';

    public $incrementing = false;

    protected string $sequenceName = '';

    protected $fillable = [
        'l219_codigo',
        'l219_situacao',
        'l219_data',
        'l219_id_usuario',
        'l219_hora',
    ];

}
