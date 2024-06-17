<?php

namespace App\Models;

use App\Models\ISSQN\IssbaseParalisacao;
use App\Models\ISSQN\Tabativ;
use App\Traits\LegacyAccount;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Issbase extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'issqn.issbase';

    protected $primaryKey = 'q02_inscr';

    protected string $sequenceName = 'issbase_q02_inscr_seq';

    protected $fillable = [
        'q02_inscr',
        'q02_numcgm',
        'q02_memo',
        'q02_tiplic',
        'q02_regjuc',
        'q02_inscmu',
        'q02_obs',
        'q02_dtcada',
        'q02_dtinic',
        'q02_dtbaix',
        'q02_capit',
        'q02_cep',
        'q02_dtjunta',
        'q02_ultalt',
        'q02_dtalt',
    ];

    public function cgm(): BelongsTo
    {
        return $this->belongsTo(Cgm::class, 'q02_numcgm', 'z01_numcgm');
    }

    public function scopeCpfCnpj(Builder $query, string $cpfCnpj): Builder
    {
        return $query
            ->join('protocolo.cgm', 'issbase.q02_numcgm', '=', 'cgm.z01_numcgm')
            ->where('z01_cgccpf', $cpfCnpj);
    }

    public function issbaseparalisacao(): HasMany
    {
        return $this->hasMany(Issbaseparalisacao::class, 'q140_issbase','q02_inscr');
    }

    public function tabativ(): HasMany
    {
        return $this->hasMany(Tabativ::class, 'q07_inscr','q02_inscr');
    }

    /**
     * If the main activity is down so the issbase is down
     * @return bool
     */
    public function isBaixada(): bool
    {
        return $this->newQuery()
            ->join('tabativ', 'q02_inscr', 'q07_inscr')
            ->join('ativprinc', function ($join) {
                $join->on('ativprinc.q88_inscr', '=', 'tabativ.q07_inscr')
                    ->whereColumn('ativprinc.q88_seq', '=', 'tabativ.q07_seq');
            })
            ->join('tabativbaixa', function ($join) {
                $join->on('tabativbaixa.q11_inscr', '=', 'tabativ.q07_inscr')
                    ->whereColumn('tabativbaixa.q11_seq', '=', 'tabativ.q07_seq');
            })
            ->where('q02_inscr', $this->q02_inscr)
            ->exists();
    }

    public function isParalisada(): bool
    {
        return $this->newQuery()
            ->join('issbaseparalisacao', 'q140_issbase', 'q02_inscr')
            ->where('q02_inscr', $this->q02_inscr)
            ->exists();
    }
}
