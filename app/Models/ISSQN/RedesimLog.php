<?php

namespace App\Models\ISSQN;

use App\Models\LegacyModel;

class RedesimLog extends LegacyModel
{

    public $timestamps = false;

    protected $table = 'issqn.redesim_log';

    protected $primaryKey = 'q190_sequencial';

    protected $fillable = [
        'q190_data',
        'q190_cpfcnpj',
        'q190_json',
    ];
}
