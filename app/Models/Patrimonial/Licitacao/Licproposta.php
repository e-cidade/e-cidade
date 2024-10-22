<?php

namespace App\Models\Patrimonial\Licitacao;
use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Licproposta extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'licitacao.licproposta';

    protected $primaryKey = 'l224_sequencial';

    public $incrementing = false;

    protected string $sequenceName = 'licitacao.licproposta_l224_sequencial_seq';

    protected $fillable = [
        'l224_sequencial',
        'l224_codigo',
        'l224_forne',
        'l224_propitem',
        'l224_quant',
        'l224_vlrun',
        'l224_valor',
        'l224_porcent',
        'l224_marca'
    ];
}
