<?php
namespace App\Repositories\Contabilidade;

use App\Models\Contabilidade\OperacaoCredito;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Operacao de Credito Repository
 * @author widouglas
 */
class OperacaoCreditoRepository extends BaseRepository
{
    CONST FONTES = ['15740000', '16340000', '17540000'];

    public function __construct()
    {
        $this->model = new OperacaoCredito();
    }
    /**
     * Funcão responsavel por verificar se existe previsao de operacao de credito
     *
     * @param int $iOperacaoCredito
     */
    public function pegarPorSequencial($iOperacaoCredito)
    {
        return DB::table('public.db_operacaodecredito')
            ->where('op01_sequencial', $iOperacaoCredito)
            ->first();
    }
}