<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Processocompraloteitem extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'public.processocompraloteitem';

    protected $primaryKey = 'pc69_sequencial';

    public $incrementing = false;

    protected $fillable = [
        'pc69_sequencial',
        'pc69_processocompralote',
        'pc69_pcprocitem',
        'pc69_seq'
    ];
}
