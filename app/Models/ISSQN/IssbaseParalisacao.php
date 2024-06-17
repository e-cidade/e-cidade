<?php

namespace App\Models\ISSQN;

use App\Models\Issbase;
use App\Models\LegacyModel;
use App\Traits\LegacyAccount;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssbaseParalisacao extends LegacyModel
{
    use LegacyAccount;

    public const MOTIVO_PARALIZACAO_NAO_LOCALIZACA = 1;
    public const MOTIVO_PARALIZACAO_REDESIM = 99;

    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'issqn.issbaseparalisacao';
    /**
     * @var string
     */
    protected $primaryKey = 'q140_sequencial';

    protected $fillable = [
        'q140_issbase',
        'q140_issmotivoparalisacao',
        'q140_datainicio',
        'q140_datafim',
        'q140_observacao',
    ];

    public function issbase(): BelongsTo
    {
        return $this->belongsTo(Issbase::class, 'q02_inscr', 'q140_issbase');
    }

    public function scopeActiveByInscricao(Builder $query, int $inscricao): Builder
    {
        return $query
            ->where('q140_issbase', $inscricao)
            ->whereNull('q140_datafim')
            ->orderByDesc('q140_sequencial')
            ->limit(1);
    }
}
