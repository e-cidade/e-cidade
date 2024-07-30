<?php

namespace App\Models;

use App\Traits\LegacyAccount;

class IssbaseNumeracao extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'issqn.issbasenumeracao';

    protected $primaryKey = 'q133_sequencial';

    protected $fillable = [
        'q133_sequencial',
        'q133_numeracaoatual'
    ];
}
