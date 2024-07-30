<?php

namespace App\Models\ISSQN;

use App\Models\LegacyModel;

class RedesimLogJob extends LegacyModel
{

    public $timestamps = false;

    protected $table = 'issqn.redesim_log_job';

    protected $primaryKey = 'q191_sequencial';

    protected $fillable = [
        'q191_started',
        'q191_ended',
        'q191_response',
    ];
}
