<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Pcorcamitemlic extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'licitacao.pcorcamitemlic';

    public $incrementing = false;

    protected $fillable = [
        'pc26_orcamitem',
        'pc26_liclicitem'
    ];
}
