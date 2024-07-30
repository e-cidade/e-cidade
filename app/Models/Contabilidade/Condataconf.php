<?php

namespace App\Models\Contabilidade;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Condataconf extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'contabilidade.condataconf';

    protected $primaryKey = 'c99_anousu , c99_instit';

    public $incrementing = false;

    protected $fillable = [
        'c99_anousu',
        'c99_instit',
        'c99_data',
        'c99_usuario',
        'c99_datapat'
    ];

}
