<?php

namespace App\Services\Tributario\ISSQN\Redesim\Alvara;

use App\Models\ISSQN\Ativid;
use App\Models\ISSQN\AtividCnae;
use App\Models\ISSQN\Cnae;
use App\Models\ISSQN\CnaeAnalitica;
use App\Repositories\Tributario\ISSQN\Redesim\DTO\ActivityDTO;

class UpdateOrCreateActivityService
{
    private Cnae $cnae;
    private CnaeAnalitica $cnaeAnalitica;
    private Ativid $ativid;
    private AtividCnae $atividcnae;

    public function __construct(Cnae $cnae, CnaeAnalitica $cnaeAnalitica, AtividCnae $atividcnae, Ativid $ativid)
    {
        $this->cnae = $cnae;
        $this->cnaeAnalitica = $cnaeAnalitica;
        $this->ativid = $ativid;
        $this->atividcnae = $atividcnae;
    }

    /**
     * @param ActivityDTO $data
     * @return void
     */
    public function execute(ActivityDTO $data): void
    {
        $cnae = $this->cnae->newQuery()
            ->where('q71_estrutural', 'like', "%{$data->getCnaeEstruturalEcidade()}")
            ->first();

        if(!$cnae->exists()) {
            $this->cnae->q71_estrutural = $data->getCnaeEstruturalEcidade();
            $this->cnae->q71_descr = $data->getDescricaoEcidade();
            $this->cnae->save();
        }

        $cnaeAnalitica = $this->cnaeAnalitica->newQuery()->firstOrCreate(
            ['q72_cnae' => $cnae->q71_sequencial],
            ['q72_cnae' => $cnae->q71_sequencial]
        );

        if (!$cnaeAnalitica->atividCnae->isEmpty()){
            return;
        }

        $this->ativid->q03_descr = $data->getDescricaoEcidade();
        $this->ativid->save();

        $this->atividcnae->q74_cnaeanalitica = $cnaeAnalitica->q72_sequencial;
        $this->atividcnae->q74_ativid = $this->ativid->q03_ativ;
        $this->atividcnae->save();
    }
}
