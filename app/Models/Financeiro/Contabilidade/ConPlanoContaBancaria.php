<?php

namespace App\Models\Financeiro\Contabilidade;

use App\Traits\LegacyAccount;
use App\Models\LegacyModel;


class ConPlanoContaBancaria extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'conplanocontabancaria';

    protected $primaryKey = 'c56_sequencial';

    protected string $sequenceName = 'conplanocontabancaria_c56_sequencial_seq';

    public $incrementing = false;

    protected $fillable = [
        'c56_sequencial',
        'c56_contabancaria',
        'c56_codcon',
        'c56_anousu',
    ];

}
