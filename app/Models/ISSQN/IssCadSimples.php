<?php

namespace App\Models\ISSQN;

use App\Models\Issbase;
use App\Models\LegacyModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class IssCadSimples extends LegacyModel
{
    public const CATEGORIA_ME = 1;
    public const CATEGORIA_EPP = 2;
    public const CATEGORIA_MEI = 3;

    public $timestamps = false;

    protected $table = 'issqn.isscadsimples';

    protected $primaryKey = 'q38_sequencial';

    protected $fillable = [
        'q38_sequencial',
        'q38_inscr',
        'q38_dtinicial',
        'q38_categoria',
    ];

    public function issbase(): BelongsTo
    {
        return $this->belongsTo(Issbase::class, 'q02_inscr', 'q38_inscr');
    }

    public function isscadsimplesbaixa(): HasOne
    {
        return $this->hasOne(IssCadSimplesBaixa::class, 'q39_isscadsimples', 'q38_sequencial');
    }
}
