<?php

namespace App\Models\Financeiro\Contabilidade;

use App\Traits\LegacyAccount;
use App\Models\LegacyModel;


class ConPlanoContaCorrente extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'contabilidade.conplanocontacorrente';

    protected $primaryKey = 'c18_sequencial';

    protected string $sequenceName = 'conplanocontacorrente_c18_sequencial_seq';

    protected $fillable = [
        'c18_sequencial',
        'c18_codcon',
        'c18_anousu',
        'c18_contacorrente',
    ];

}
