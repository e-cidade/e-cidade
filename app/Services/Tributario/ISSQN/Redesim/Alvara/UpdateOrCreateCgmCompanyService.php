<?php

namespace App\Services\Tributario\ISSQN\Redesim\Alvara;

use App\Models\Cgm;
use App\Repositories\Tributario\ISSQN\Redesim\DTO\CompanyDTO;
use BusinessException;
use Exception;

class UpdateOrCreateCgmCompanyService
{
    private Cgm $cgm;

    public function __construct(Cgm $cgm)
    {
        $this->cgm = $cgm;
    }

    /**
     * @param CompanyDTO $data
     * @return Cgm
     * @throws BusinessException
     */
    public function execute(CompanyDTO $data): Cgm
    {

        try {

            /**
             * @var Cgm|bool $cgm
             */
            $cgm = $this->cgm->updateOrCreate(
                ['z01_cgccpf' => $data->cpfCnpj],
                [
                    'z01_nome' => $data->getRazaoSocialEcidade(),
                    'z01_ender' => $data->endereco->tipoLogradouroCod.' '.$data->endereco->logradouro,
                    'z01_numero' => $data->endereco->getNumeroFormmated(),
                    'z01_bairro' => $data->endereco->getBairroEcidadeFormat(),
                    'z01_munic' => $data->endereco->getMunicipioEcidadeFormat(),
                    'z01_uf' => $data->endereco->uf,
                    'z01_cep' => $data->endereco->cep,
                    'z01_cadast' => $data->inclusao->format('Y-m-d'),
                    'z01_telef' => $data->dddTelefone . $data->telefone,
                    'z01_email' => $data->email,
                    'z01_cgccpf' => $data->cpfCnpj,
                    'z01_nomefanta' => $data->getNomeFantasiaEcidade(),
                    'z01_nomecomple' => $data->getRazaoSocialEcidade(),
                    'z01_incmunici' => $data->inscricaoMunicipal,
                    'z01_naturezajuridica' => $data->codigoNaturezaJuridica,
                ]
            );

            if (!$cgm) {
                throw new BusinessException('Não foi possível inserir ou atualizar o CGM da empresa.');
            }

        } catch (Exception $exception) {
            throw new BusinessException($exception->getMessage());
        }

        return $cgm;
    }
}
