<?php

namespace App\Models\ISSQN;

use App\Models\LegacyModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class IssCadSimplesBaixa extends LegacyModel
{
    public $timestamps = false;

    protected $table = 'issqn.isscadsimplesbaixa';

    protected $primaryKey = 'q39_sequencial';

    protected $fillable = [
        'q39_sequencial',
        'q39_isscadsimples',
        'q39_dtbaixa',
        'q39_issmotivobaixa',
        'q39_obs',
    ];

    public function isscadsimples(): BelongsTo
    {
        return $this->belongsTo(IssCadSimples::class, 'q38_sequencial', 'q39_isscadsimples');
    }

    public function isscadsimplesbaixa(): HasOne
    {
        return $this->hasOne(IssMotivoBaixa::class, 'q42_sequencial', 'q39_issmotivobaixa');
    }
}
