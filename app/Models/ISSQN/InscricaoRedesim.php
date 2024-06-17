<?php

namespace App\Models\ISSQN;

use App\Models\Issbase;
use App\Models\LegacyModel;
use App\Traits\LegacyAccount;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InscricaoRedesim extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;
    protected $table = 'issqn.inscricaoredesim';
    protected $primaryKey = 'q179_sequencial';

    protected string $sequenceName = 'inscricaoredesim_q179_sequencial_seq';

    protected $fillable = [
        'q179_inscricao',
        'q179_inscricaoredesim',
        'q179_dadosregistro'
    ];

    public function issBase(): HasOne
    {
        return $this->hasOne(IssBase::class, "q02_inscr", "q179_inscricao");
    }

    public function scopeBetweenDataCadastroInscricao(Builder $query, $dataInicio, $dataFim): Builder
    {
        if (empty($dataInicio) || empty($dataFim)) {
            return $query;
        }

        return $query->where('inscricaoredesim.q179_inscricao', function ($query) use ($dataInicio, $dataFim) {
            $query->select('q02_inscr')
                ->from('issbase')
                ->whereColumn('q02_inscr', 'inscricaoredesim.q179_inscricao')
                ->whereRaw("TO_CHAR(q02_dtcada, 'YYYY-MM-DD') BETWEEN ? AND ?", [$dataInicio, $dataFim])
                ->limit(1);
        });
    }

    public function scopeInscricaoRedesim(Builder $query, $inscricaoredesim): Builder
    {
        return $query->where('q179_inscricaoredesim', $inscricaoredesim);
    }
}
