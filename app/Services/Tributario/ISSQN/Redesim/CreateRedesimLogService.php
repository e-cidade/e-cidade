<?php

namespace App\Services\Tributario\ISSQN\Redesim;

use App\Models\ISSQN\RedesimLog;
use App\Repositories\Tributario\ISSQN\Redesim\DTO\CompanyDTO;
use App\Support\String\StringHelper;

class CreateRedesimLogService
{
    private RedesimLog $redesimLog;

    /**
     * @param RedesimLog $redesimLog
     */
    public function __construct(RedesimLog $redesimLog)
    {
        $this->redesimLog = $redesimLog;
    }

    public function execute(CompanyDTO $data)
    {
        $this->redesimLog->newQuery()->insert(
            [
                'q190_data' => date('Y-m-d H:i'),
                'q190_cpfcnpj' => $data->cpfCnpj,
                'q190_json' => json_encode(StringHelper::utf8_encode_all($data->originalData), JSON_UNESCAPED_UNICODE)
            ]
        );
    }
}
