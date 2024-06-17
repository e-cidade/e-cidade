<?php

namespace App\Services\Tributario\ISSQN\Redesim\Alvara;

use App\Models\Cgm;
use App\Repositories\Tributario\ISSQN\Redesim\DTO\CompanyPartnerDTO;
use BusinessException;
use Exception;

class UpdateOrCreateCgmCompanyPartnerService
{
    private Cgm $cgm;

    public function __construct(Cgm $cgm)
    {
        $this->cgm = $cgm;
    }

    /**
     * @param CompanyPartnerDTO $data
     * @return Cgm
     * @throws BusinessException
     */
    public function execute(CompanyPartnerDTO $data): Cgm
    {
        try {

            /**
             * @var Cgm|bool $cgm
             */
            $cgm = $this->cgm->updateOrCreate(
                ['z01_cgccpf' => $data->cpf],
                [
                    'z01_nome' => $data->nome,
                    'z01_cadast' => $data->inclusao->format('Y-m-d'),
                    'z01_cgccpf' => $data->cpf,
                ]
            );

            if (!$cgm) {
                throw new BusinessException('Não foi possível inserir ou atualizar o CGM do sócio.');
            }

        } catch (Exception $exception) {
            throw new BusinessException($exception->getMessage());
        }

        return $cgm;
    }
}
