<?php

namespace App\Services\Financeiro\Tesouraria\Command;

use App\Repositories\Financeiro\Contabilidade\ContaPlanoContaBancariaRepository;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoContaCorrenteRepository;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoContaRepository;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoExeRepository;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoReduzRepository;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoRepository;
use App\Repositories\Financeiro\Tesouraria\ContaBancariaRepository;
use App\Repositories\Financeiro\Empenho\EmpagetipoRepository;
use App\Repositories\Financeiro\Tesouraria\SaltesRepository;

use App\Repositories\Financeiro\Contabilidade\ContaPlanoContaBancariaRepositoryInterface;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoContaCorrenteRepositoryInterface;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoContaRepositoryInterface;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoExeRepositoryInterface;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoReduzRepositoryInterface;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoRepositoryInterface;
use App\Repositories\Financeiro\Empenho\EmpagetipoRepositoryInterface;
use App\Repositories\Financeiro\Tesouraria\ContaBancariaRepositoryInterface;
use App\Repositories\Financeiro\Tesouraria\SaltesRepositoryInterface;

use App\Services\Financeiro\Tesouraria\Command\DeleteContaBancariaCommandInterface;

use Exception;
use Illuminate\Support\Facades\DB;
use stdClass;

class DeleteContaBancariaCommand implements DeleteContaBancariaCommandInterface
{
     /**
     * @var ContaBancariaRepositoryInterface
     */
    private ContaBancariaRepositoryInterface $contabancariaRepository;

    /**
     * @var ContaPlanoContaBancariaRepositoryInterface
     */
    private ContaPlanoContaBancariaRepositoryInterface $contaplanocontabancariaRepository;

    /**
     * @var ContaPlanoContaCorrenteRepositoryInterface
     */
    private ContaPlanoContaCorrenteRepositoryInterface $contaplanocontacorrenteRepository;

    /**
     * @var ContaPlanoContaRepositoryInterface
     */
    private ContaPlanoContaRepositoryInterface $contaplanocontaRepository;

    /**
     * @var ContaPlanoExeRepositoryInterface
     */
    private ContaPlanoExeRepositoryInterface $contaplanoexeRepository;

    /**
     *
     * @var ContaPlanoReduzRepositoryInterface
     */
    private ContaPlanoReduzRepositoryInterface $contaplanoreduzRepository;

     /**
     *
     * @var ContaPlanoRepositoryInterface
     */
    private ContaPlanoRepositoryInterface $contaplanoRepository;

      /**
     *
     * @var EmpagetipoRepositoryInterface
     */
    private EmpagetipoRepositoryInterface $empagetipoRepository;

     /**
     *
     * @var SaltesRepositoryInterface
     */
    private SaltesRepositoryInterface $saltesRepository;

      /**
     *
     * @var int $ano
     */
    private int $ano;

     /**
     *
     * @var int $instituicao
     */
    private int $instituicao;

    public function __construct()
    {
        $this->contabancariaRepository           = new ContaBancariaRepository();
        $this->contaplanocontabancariaRepository = new ContaPlanoContaBancariaRepository();
        $this->contaplanocontaRepository         = new ContaPlanoContaRepository();
        $this->contaplanocontacorrenteRepository = new ContaPlanoContaCorrenteRepository();
        $this->contaplanoexeRepository           = new ContaPlanoExeRepository();
        $this->contaplanoreduzRepository         = new ContaPlanoReduzRepository();
        $this->contaplanoRepository              = new ContaPlanoRepository();
        $this->empagetipoRepository              = new EmpagetipoRepository();
        $this->saltesRepository                  = new SaltesRepository();
        $this->ano                               = db_getsession('DB_anousu');
        $this->instituicao                       = db_getsession("DB_instit");
    }

    public function execute(stdClass $chavestabelas): bool
    {

        $sequencialcontaBancaria           = $chavestabelas->db83_sequencial;
        $sequencialcontaPlano              = $chavestabelas->c60_codcon;
        $sequencialcontaplanoreduz         = $chavestabelas->c61_codcon;
        $sequencialcontaplanoexe           = $chavestabelas->c62_reduz;
        $sequencialcontaplanocontabancaria = $chavestabelas->c56_contabancaria;
        $sequencialcontaplanocontacorrente = $chavestabelas->c18_codcon;
        $sequencialsaltes                  = $chavestabelas->k13_conta;
        $sequencialempagetipo              = $chavestabelas->e83_codtipo;

        $dataSessao = $this->contaplanocontaRepository->dataSessao($this->ano,$this->instituicao);

        try {


            DB::beginTransaction();

            $resultContaPlanoReduz = $this->contaplanoreduzRepository->delete($sequencialcontaplanoreduz);
            if (!$resultContaPlanoReduz) {
                throw new Exception("Não foi possível excluir a conta {$sequencialcontaBancaria}. Erro!");
            }

            $resultContaPlanoContaCorrente = $this->contaplanocontacorrenteRepository->delete($sequencialcontaplanocontacorrente);
            if (!$resultContaPlanoContaCorrente) {
                throw new Exception("Não foi possível excluir a conta {$sequencialcontaBancaria}. Erro!");
            }

            $resultContaPlanoExe = $this->contaplanoexeRepository->delete($sequencialcontaplanoexe);
            if (!$resultContaPlanoExe) {
                throw new Exception("Não foi possível excluir a conta {$sequencialcontaBancaria}. Erro!");
            }

            $resultEmpagetipo = $this->empagetipoRepository->delete($sequencialempagetipo);
            if (!$resultEmpagetipo) {
                throw new Exception("Não foi possível excluirb a conta {$sequencialcontaBancaria}. Erro!");
            }

            $resultSaltes = $this->saltesRepository->delete($sequencialsaltes);
            if (!$resultSaltes) {
                throw new Exception("Não foi possível excluirs a conta {$sequencialcontaBancaria}. Erro!");
            }

            $resultContaPlanoContaBancaria =  $this->contaplanocontabancariaRepository->delete($sequencialcontaplanocontabancaria);
            if (!$resultContaPlanoContaBancaria) {
                throw new Exception("Não foi possível excluir a conta {$sequencialcontaBancaria}. Erro!");
            }

            $resultContaBancaria = $this->contabancariaRepository->delete($sequencialcontaBancaria);
            if (!$resultContaBancaria) {
                throw new Exception("Não foi possível excluir a conta {$sequencialcontaBancaria}. Erro!");
            }

            $resultContaPlano = $this->contaplanoRepository->delete($sequencialcontaPlano);
            if (!$resultContaPlano) {
                throw new Exception("Não foi possível excluir a conta {$sequencialcontaBancaria}. Erro!");
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

}
