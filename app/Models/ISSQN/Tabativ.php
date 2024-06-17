<?php

namespace App\Models\ISSQN;

use App\Models\Issbase;
use App\Models\LegacyModel;
use App\Traits\LegacyAccount;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tabativ extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;
    public $incrementing = false;

    protected $table = 'issqn.tabativ';

    protected $primaryKey = 'q07_inscr';

    protected $fillable = [
        'q07_inscr',
        'q07_seq',
        'q07_ativ',
        'q07_datain',
        'q07_datafi',
        'q07_databx',
        'q07_quant',
        'q07_tipbx',
        'q07_perman',
        'q07_horaini',
        'q07_horafim',
        'q07_dataini_isen',
        'q07_datafim_isen',
        'q07_justificaisencao',
        'q07_aliquota_incentivo',
    ];

    public function tabativ(): BelongsTo
    {
        return $this->belongsTo(Tabativ::class, 'q07_ativ', 'q03_ativ');
    }

    public function issabae(): BelongsTo
    {
        return $this->belongsTo(Issbase::class, 'q07_inscr', 'q02_inscr');
    }

    public function scopeIsPrincipal(Builder $query): Builder
    {
        return $query
            ->where('q88_inscr', $this->q07_inscr)
            ->where('q88_seq', $this->q07_seq);
    }

    public function scopeInscricaoAtividade(Builder $query, int $inscricao, $atividade): Builder
    {
        return $query->where('q07_inscr', $inscricao)->where('q07_ativ', $atividade);
    }

    public function scopeTabativbaixa(Builder $query)
    {
        return $query->join('tabativbaixa', function ($join) {
           $join->on('tabativbaixa.q11_inscr', '=',  'tabativ.q07_inscr');
           $join->on('tabativbaixa.q11_seq', '=',  'tabativ.q07_seq');
        });
    }
}
