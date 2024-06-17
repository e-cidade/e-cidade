<?php

namespace App\Models\ISSQN;

use App\Models\LegacyModel;
use App\Traits\LegacyAccount;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ativprinc extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;
    public $incrementing = false;

    protected $table = 'issqn.ativprinc';

    protected $primaryKey = 'q88_inscr, q88_seq';

    protected $fillable = [
        'q88_seq'
    ];

    public function tabativ(): BelongsTo
    {
        return $this->belongsTo(Tabativ::class, 'q07_inscr, q07_seq', 'q88_inscr, q88_seq');
    }
}
