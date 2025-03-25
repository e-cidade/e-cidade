<?php

namespace App\Models;

use App\Traits\LegacyAccount;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

class OrcDotacao extends LegacyModel
{
    use LegacyAccount;

    public $timestamps = false;

    protected $table = 'orcamento.orcdotacao';

    public $incrementing = false;

    protected $primaryKey = 'o58_anousu, o58_coddot';

    protected $fillable = [
        'o58_anousu',
        'o58_coddot',
        'o58_orgao',
        'o58_unidade',
        'o58_subfuncao',
        'o58_projativ',
        'o58_codigo',
        'o58_funcao',
        'o58_programa',
        'o58_codele',
        'o58_valor',
        'o58_instit',
        'o58_localizadorgastos',
        'o58_datacriacao',
        'o58_concarpeculiar',
    ];

    public function getOrcamentosDotacoesAnoDestino (int $anoDestino, int $codigoInstituicao) {
        return $this->newQuery()
            ->join('orcelemento', function ($join) {
                $join->on('orcdotacao.o58_codele', '=', 'orcelemento.o56_codele')
                    ->whereColumn('orcdotacao.o58_anousu', '=', 'orcelemento.o56_anousu');
            })
            ->where('o58_anousu', $anoDestino)
            ->select(
                'o58_coddot AS dotacao',
                new Expression("CONCAT(
                CASE
                    WHEN o58_orgao < 10 THEN '0'
                    ELSE ''
                END,
                o58_orgao, '.',
                CASE
                    WHEN o58_unidade < 10 THEN '0'
                    ELSE ''
                END,
                o58_unidade, '.',
                o58_funcao, '.', o58_subfuncao, '.',
                LPAD(o58_programa::text, 4, '0'), '.', o58_projativ, '.', orcelemento.o56_elemento, '.', o58_codigo) AS estrutural"),
                'o58_anousu'
            );
    }
}
