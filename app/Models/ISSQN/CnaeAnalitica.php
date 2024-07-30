<?php

namespace App\Models\ISSQN;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CnaeAnalitica extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'issqn.cnaeanalitica';

    protected $primaryKey = 'q72_sequencial';

    protected $fillable = [
        'q72_cnae',
    ];


    public function cnae(): BelongsTo
    {
        return $this->belongsTo(Cnae::class, 'q71_sequencial', 'q72_cnae');
    }

    public function atividCnae(): HasMany
    {
        return $this->hasMany(AtividCnae::class, 'q74_cnaeanalitica', 'q72_sequencial');
    }
}
