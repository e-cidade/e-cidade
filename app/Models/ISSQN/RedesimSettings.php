<?php

namespace App\Models\ISSQN;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class RedesimSettings extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'issqn.parametros_redesim';

    protected $primaryKey = 'q180_sequencial';

    protected $fillable = [
        'q180_url_api',
        'q180_client_id',
        'q180_vendor_id',
        'q180_access_key'
    ];
}
