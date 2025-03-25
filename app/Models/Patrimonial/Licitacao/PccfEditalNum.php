<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class PccfEditalNum extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'public.pccfeditalnum';

    protected $primaryKey = '';

    public $incrementing = false;

    protected string $sequenceName = '';

    protected $fillable = [
        'l47_numero',
        'l47_anousu',
        'l47_instit',
        'l47_timestamp',
    ];

}
