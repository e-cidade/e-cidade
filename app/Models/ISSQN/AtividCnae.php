<?php

namespace App\Models\ISSQN;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AtividCnae extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;
    public $incrementing = false;

    protected $table = 'issqn.atividcnae';

    protected $primaryKey = 'q74_cnaeanalitica, q74_ativid';

    protected $fillable = [
        'q74_cnaeanalitica',
        'q74_ativid',
    ];


    public function cnaeAnalitica(): BelongsTo
    {
        return $this->belongsTo(CnaeAnalitica::class, 'q72_sequencial', 'q74_cnaeanalitica');
    }

    public function ativid(): HasOne
    {
        return $this->hasOne(Ativid::class, 'q03_ativ', 'q74_ativid');
    }
}
