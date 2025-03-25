<?php

namespace App\Models\Patrimonial\Licitacao;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class LicAnexoPncp extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'public.licanexopncp';

    protected $primaryKey = 'l215_sequencial';

    public $incrementing = false;

    protected $fillable = [
        'l215_sequencial',
        'l215_liclicita',
        'l215_dataanexo',
        'l215_id_usuario',
        'l215_hora',
        'l215_instit',
    ];

}
