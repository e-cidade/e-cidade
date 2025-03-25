<?php

namespace App\Models\Patrimonial\Compras;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;

class Solicitempcmater extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'compras.solicitempcmater';

    protected $primaryKey = 'pc16_codmater';

    public $incrementing = false;

    protected $fillable = [
        'pc16_codmater',
        'pc16_solicitem'
    ];
        
    public function pcmater()
    {
        return $this->belongsTo(Pcmater::class, 'pc01_codmater', 'pc16_codmater');
    }
}
