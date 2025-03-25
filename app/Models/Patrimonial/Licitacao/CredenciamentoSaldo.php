<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class CredenciamentoSaldo extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'licitacao.credenciamentosaldo';

    protected $primaryKey = 'situacaoitemlic';

    public $incrementing = false;

    protected string $sequenceName = '';

    protected $fillable = [
        'l213_sequencial',
        'l213_licitacao',
        'l213_item',
        'l213_itemlicitacao',
        'l213_qtdlicitada',
        'l213_qtdcontratada',
        'l213_contratado',
        'l213_acordo',
        'l213_autori',
        'l213_valorcontratado',
    ];

}
