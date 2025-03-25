<?php

namespace App\Services\Financeiro\Tesouraria\Command;

use App\Domain\Financeiro\Tesouraria\ContaBancarias;

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

use App\Services\Financeiro\Tesouraria\Command\UpdateContaBancariaCommandInterface;

use Exception;
use Illuminate\Support\Facades\DB;
use stdClass;

class UpdateContaBancariaCommand implements UpdateContaBancariaCommandInterface
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
    }

    public function execute(ContaBancarias $contabancaria,stdClass $chavestabelas): bool
    {

        $dadoscontabancaria        = $this->mountContaBancaria($contabancaria);
        $sequencialcontaBancaria   = $contabancaria->getDb83Sequencial();

        $dadoscontaplano           = $this->mountContaPlano($contabancaria);
        $sequencialcontaPlano      = $chavestabelas->c60_codcon;

        $dadoscontaplanoreduz      = $this->mountContaPlanoReduz($contabancaria);
        $sequencialcontaplanoreduz = $chavestabelas->c61_codcon;

        $dadoscontaplanoconta     = $this->mountContaPlanoConta($contabancaria);
        $sequencialcontaplanoconta = $chavestabelas->c63_codcon;

        $dadoscontaplanoexe        = $this->mountContaPlanoExe($contabancaria);
        $sequencialcontaplanoexe   = $chavestabelas->c62_reduz;

        $dadossaltes               = $this->mountSaltes($contabancaria);
        $sequencialsaltes          = $chavestabelas->k13_conta;

        $dadosempagetipo           = $this->mountEmpagetipo($contabancaria);
        $sequencialempagetipo      = $chavestabelas->e83_conta;
        try {
            DB::beginTransaction();

            $resultContaBancaria = $this->contabancariaRepository->update(
                $sequencialcontaBancaria,
                $dadoscontabancaria
            );

            if (!$resultContaBancaria) {
                throw new Exception("Não foi possível atualizar a conta {$sequencialcontaBancaria}. Erro!");
            }

            $resultContaPlano = $this->contaplanoRepository->update(
                $sequencialcontaPlano,
                $dadoscontaplano
            );

            if (!$resultContaPlano) {
                throw new Exception("Não foi possível atualizar a conta {$sequencialcontaBancaria}. Erro!");
            }

            $resultContaPlanoReduz = $this->contaplanoreduzRepository->update(
                $sequencialcontaplanoreduz,
                $dadoscontaplanoreduz
            );

            if (!$resultContaPlanoReduz) {
                throw new Exception("Não foi possível atualizar a conta {$sequencialcontaBancaria}. Erro!");
            }

            $resultContaPlanoConta = $this->contaplanocontaRepository->update(
                $sequencialcontaplanoconta,
                $dadoscontaplanoconta
            );

            if (!$resultContaPlanoConta) {
                throw new Exception("Não foi possível atualizar ssa conta {$sequencialcontaBancaria}. Erro!");
            }

            

            $resultContaPlanoExe = $this->contaplanoexeRepository->update(
                $sequencialcontaplanoexe,
                $dadoscontaplanoexe
            );

            if (!$resultContaPlanoExe) {
                throw new Exception("Não foi possível atualizar a conta {$sequencialcontaBancaria}. Erro!");
            }


            $resultSaltes = $this->saltesRepository->update(
                $sequencialsaltes,
                $dadossaltes
            );

            if (!$resultSaltes) {
                throw new Exception("Não foi possível atualizar a conta {$sequencialcontaBancaria}. Erro!");
            }

            $resultEmpagetipo = $this->empagetipoRepository->update(
                $sequencialempagetipo,
                $dadosempagetipo
            );

            if (!$resultEmpagetipo) {
                throw new Exception("Não foi possível atualizar a conta {$sequencialcontaBancaria}. Erro!");
            }


            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    private function mountContaBancaria(ContaBancarias $dadosContaBancaria): array
    {

        $contaBancaria = [
            "db83_sequencial"         => $dadosContaBancaria->getDb83Sequencial()  ,
            "db83_descricao"          => $dadosContaBancaria->getDb83Descricao(),
            "db83_bancoagencia"       => $dadosContaBancaria->getDb83Bancoagencia(),
            "db83_conta"              => $dadosContaBancaria->getDb83Conta(),
            "db83_dvconta"            => $dadosContaBancaria->getDb83DvConta(),
            "db83_identificador"      => $dadosContaBancaria->getDb83Identificador(),
            "db83_codigooperacao"     => $dadosContaBancaria->getDb83Codigooperacao(),
            "db83_tipoconta"          => $dadosContaBancaria->getDb83Tipoconta(),
            "db83_contaplano"         => $dadosContaBancaria->getDb83Contaplano(),
            "db83_convenio"           => $dadosContaBancaria->getDb83convenio(),
            "db83_tipoaplicacao"      => $dadosContaBancaria->getDb83Tipoaplicacao(),
            "db83_numconvenio"        => $dadosContaBancaria->getDb83Numconvenio(),
            "db83_nroseqaplicacao"    => $dadosContaBancaria->getDb83Nroseqaplicacao(),
            "db83_codigoopcredito"    => $dadosContaBancaria->getDb83Codigoopcredito(),

        ];

        return $contaBancaria;
    }

    public function mountContaPlano(ContaBancarias $dadosContaPlano): array
    {
        $contaPlano = [
            "c60_descr"                   => $dadosContaPlano->getDb83Descricao()
        ];

        return $contaPlano;
    }

    public function mountContaPlanoReduz(ContaBancarias $dadosContaPlanoReduz): array
    {

        $contaPlanoReduz = [
            "c61_codigo"                   => $dadosContaPlanoReduz->getDb83FontePrincipal()

        ];

        return $contaPlanoReduz;
    }

    public function mountContaPlanoExe(ContaBancarias $dadosContaPlanoExe): array
    {

        $contaPlanoExe = [
            "c62_codrec"                   => $dadosContaPlanoExe->getDb83FontePrincipal()

        ];

        return $contaPlanoExe;
    }

    public function mountContaPlanoConta(ContaBancarias $dadosContaPlanoConta): array
    {

        $contaPlanoConta = [

            "c63_banco"                   => $dadosContaPlanoConta->getDb83CodBanco(),
            "c63_agencia"                 => $dadosContaPlanoConta->getDb83BancoAgencia(),
            "c63_conta"                   => $dadosContaPlanoConta->getDb83Conta(),
            "c63_dvconta"                 => $dadosContaPlanoConta->getDb83DvConta(),
            "c63_dvagencia"               => $dadosContaPlanoConta->getDb83DvAgencia(),
            "c63_identificador"           => $dadosContaPlanoConta->getDb83Identificador(),
            "c63_codigooperacao"          => $dadosContaPlanoConta->getDb83CodigoOperacao(),
            "c63_tipoconta"               => $dadosContaPlanoConta->getDb83TipoConta(),

        ];

        return $contaPlanoConta;
    }

    public function mountSaltes(ContaBancarias $dadosSaltes): array
    {

        $contaSaltes = [
            "k13_descr"                   => substr($dadosSaltes->getDb83Descricao(),0,40),
            "k13_datvlr"                  => $dadosSaltes->getDb83DataImplantaoConta(),
            "k13_dtimplantacao"           => $dadosSaltes->getDb83DataImplantaoConta(),
            "k13_limite"                  => $dadosSaltes->getDb83DataLimite(),
            "k13_dtreativacaoconta"       => $dadosSaltes->getDb83DataReativacaoConta()

        ];

        return $contaSaltes;
    }

    public function mountEmpagetipo(ContaBancarias $dadosEmpagetipo): array
    {

        $contaEmpagetipo = [
            "e83_descr"                   => $dadosEmpagetipo->getDb83Descricao(),

        ];

        return $contaEmpagetipo;
    }








}
