<?php

namespace App\Services\Tributario\ISSQN\Redesim\Alvara;

use App\Models\Cadastro\Bairro;
use App\Models\Cadastro\Rua;
use App\Models\Cadastro\RuaBairro;
use App\Models\ISSQN\InscricaoRedesim;
use App\Models\ISSQN\IssBairro;
use App\Models\ISSQN\IssRua;
use App\Repositories\Tributario\ISSQN\Redesim\DTO\CompanyDTO;
use BusinessException;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UpdateOrCreateCompanyAddress
{
    private Rua $rua;
    private Bairro $bairro;
    private RuaBairro $ruaBairro;
    private IssRua $issRua;
    private IssBairro $issBairro;
    private InscricaoRedesim $inscricaoRedesim;

    public function __construct(
        Rua              $rua,
        Bairro           $bairro,
        RuaBairro        $ruaBairro,
        IssRua           $issRua,
        IssBairro        $issBairro,
        InscricaoRedesim $inscricaoRedesim
    )
    {
        $this->rua = $rua;
        $this->bairro = $bairro;
        $this->ruaBairro = $ruaBairro;
        $this->issRua = $issRua;
        $this->issBairro = $issBairro;
        $this->inscricaoRedesim = $inscricaoRedesim;
    }

    /**
     * @param CompanyDTO $data
     * @throws BusinessException
     */
    public function execute(CompanyDTO $data): void
    {
        try {

            $inscricaoRedesim = $this->inscricaoRedesim
                ->newQuery()
                ->where('q179_inscricaoredesim', $data->inscricaoMunicipal)
                ->first();

            if (empty($inscricaoRedesim)) {
                throw new BusinessException('Empresa não cadastrada no e-Cidade: ' . $data->cpfCnpj);
            }

            $rua = $this->rua->nome($data->endereco->getLogradouroEcidadeFormat())->first();

            if (empty($rua)) {
                $rua = $this->createRua($data);
            }

            $this->issRua->newQuery()->updateOrCreate(
                ['q02_inscr' => $inscricaoRedesim->q179_inscricao],
                [
                    'j14_codigo' => $rua->j14_codigo,
                    'q02_numero' => $data->endereco->numero,
                    'z01_cep' => $data->endereco->cep
                ]
            );

            $bairro = $this->bairro->nome($data->endereco->bairro)->first();

            if (empty($bairro)) {
                $bairro = $this->createBairro($data);
            }

            $this->issBairro->newQuery()->updateOrCreate(
                ['q13_inscr' => $inscricaoRedesim->q179_inscricao],
                ['q13_bairro' => $bairro->j13_codi]
            );

            $this->ruaBairro->newQuery()->create(
                [
                    'j16_lograd' => $rua->j14_codigo,
                    'j16_bairro' => $bairro->j13_codi
                ]
            );

        } catch (Exception $exception) {
            throw new BusinessException($exception->getMessage());
        }
    }

    /**
     * @param CompanyDTO $data
     * @return Builder|Model
     */
    public function createRua(CompanyDTO $data)
    {
        return $this->rua->newQuery()->create(
            ['j14_nome' => $data->endereco->getLogradouroEcidadeFormat()]
        );
    }

    /**
     * @param CompanyDTO $data
     * @return Builder|Model
     */
    public function createBairro(CompanyDTO $data)
    {
        return  $this->bairro->newQuery()->create(
            ['j13_descr' => $data->endereco->bairro]
        );
    }
}
